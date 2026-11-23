<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Tree.php";

class TreeController
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    private function requireLogin()
    {
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    private function requireFarmAccess()
    {
        $this->requireLogin();
        if (!in_array($_SESSION["role"] ?? "", ["farmer", "admin"], true)) {
            header("Location: index.php?action=profile");
            exit;
        }
    }

    private function checkAdmin()
    {
        if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
            header("Location: index.php?action=login");
            exit;
        }
    }

    private function treesTableExists()
    {
        return (bool)$this->pdo->query("SHOW TABLES LIKE 'trees'")->fetchColumn();
    }

    private function getOwnedFarm($farmId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM farms WHERE farm_id = ? AND user_id = ?");
        $stmt->execute([$farmId, $_SESSION["user_id"]]);
        return $stmt->fetch();
    }

    public function saveTree()
    {
        $this->requireFarmAccess();
        $farmId = filter_input(INPUT_GET, "farm_id", FILTER_VALIDATE_INT);
        $farm = $farmId ? $this->getOwnedFarm($farmId) : false;
        if (!$farm) {
            http_response_code(404);
            echo "Farm not found.";
            return;
        }
        $trees = $this->loadFarmTrees($farmId);
        $this->renderTreeCollectionForm(
            $farm,
            $trees,
            "index.php?action=saveTree&farm_id=" . (int)$farmId,
            "index.php?action=farm&id=" . (int)$farmId,
            __DIR__ . "/../views/frontoffice/treeForm.php"
        );
    }

    private function loadFarmTrees($farmId)
    {
        if (!$this->treesTableExists()) {
            return [];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM trees WHERE farm_id = ? ORDER BY tree_type, farm_tree_id");
        $stmt->execute([$farmId]);
        return $stmt->fetchAll();
    }

    private function renderTreeCollectionForm($farm, $trees, $formAction, $backUrl, $viewPath)
    {
        $error = "";
        if (!$this->treesTableExists()) {
            $error = "Tree storage is not set up yet. Apply database/migrations/2026_11_23_create_trees_table.sql to enable tree records.";
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
            $count = filter_var($_POST["tree_type_count"] ?? null, FILTER_VALIDATE_INT);
            $submittedTrees = $_POST["trees"] ?? [];
            if ($count === false || $count < 1 || $count > 50 || !is_array($submittedTrees) || count($submittedTrees) !== $count) {
                $error = "Choose between 1 and 50 tree types and complete each type.";
                $trees = is_array($submittedTrees)
                    ? array_map(static fn($item) => is_array($item) ? $item : [], array_values($submittedTrees))
                    : [];
            } else {
                $models = [];
                $seenTypes = [];
                $currentYear = (int)date("Y");
                foreach ($submittedTrees as $item) {
                    if (!is_array($item) || !is_scalar($item["tree_type"] ?? null) || !is_scalar($item["quantity"] ?? null) || !is_scalar($item["area_size"] ?? null) || !is_scalar($item["planting_year"] ?? null)) {
                        $error = "Complete all tree details before saving.";
                        break;
                    }
                    $treeType = trim((string)$item["tree_type"]);
                    $quantity = filter_var($item["quantity"], FILTER_VALIDATE_INT);
                    $areaSize = $item["area_size"];
                    $plantingYear = filter_var($item["planting_year"], FILTER_VALIDATE_INT);
                    $typeKey = function_exists("mb_strtolower") ? mb_strtolower($treeType) : strtolower($treeType);
                    if ($treeType === "" || strlen($treeType) > 100 || isset($seenTypes[$typeKey]) || $quantity === false || $quantity < 1 || !is_numeric($areaSize) || (float)$areaSize <= 0 || $plantingYear === false || $plantingYear < 1901 || $plantingYear > $currentYear) {
                        $error = "Enter a unique tree type, positive quantity and area, and a valid planting year for every row.";
                        break;
                    }
                    $seenTypes[$typeKey] = true;
                    $models[] = new Tree($farm["farm_id"], $treeType, $quantity, $areaSize, $plantingYear);
                }

                if ($error === "") {
                    $this->pdo->beginTransaction();
                    try {
                        $this->pdo->prepare("DELETE FROM trees WHERE farm_id = ?")->execute([$farm["farm_id"]]);
                        $insert = $this->pdo->prepare("INSERT INTO trees (farm_id, tree_type, quantity, area_size, planting_year) VALUES (?, ?, ?, ?, ?)");
                        foreach ($models as $tree) {
                            $insert->execute([
                                $tree->getFarmId(),
                                $tree->getTreeType(),
                                $tree->getQuantity(),
                                $tree->getAreaSize(),
                                $tree->getPlantingYear()
                            ]);
                        }
                        $this->pdo->commit();
                        header("Location: " . $backUrl);
                        exit;
                    } catch (Throwable $e) {
                        $this->pdo->rollBack();
                        throw $e;
                    }
                }
                $trees = array_map(static fn($item) => is_array($item) ? $item : [], $submittedTrees);
            }
        }

        if (!$trees) {
            $trees = [["tree_type" => "", "quantity" => "", "area_size" => "", "planting_year" => ""]];
        }
        $treeTypeCount = count($trees);
        require $viewPath;
    }

    public function deleteTree()
    {
        $this->requireFarmAccess();
        $farmId = filter_input(INPUT_GET, "farm_id", FILTER_VALIDATE_INT);
        $treeId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !$farmId || !$treeId || !$this->getOwnedFarm($farmId) || !$this->treesTableExists()) {
            header("Location: index.php?action=farms");
            exit;
        }
        $stmt = $this->pdo->prepare("DELETE FROM trees WHERE farm_tree_id = ? AND farm_id = ?");
        $stmt->execute([$treeId, $farmId]);
        header("Location: index.php?action=farm&id=" . (int)$farmId);
        exit;
    }

    public function adminFarmTrees()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
        $farmId = filter_input(INPUT_GET, "farm_id", FILTER_VALIDATE_INT);
        $farm = false;
        if ($userId && $farmId) {
            $stmt = $this->pdo->prepare("SELECT f.*, u.name AS owner_name FROM farms f JOIN users u ON u.user_id = f.user_id WHERE f.farm_id = ? AND f.user_id = ?");
            $stmt->execute([$farmId, $userId]);
            $farm = $stmt->fetch();
        }
        if (!$farm) {
            header("Location: index.php?action=users");
            exit;
        }
        $hasTreesTable = $this->treesTableExists();
        $trees = $this->loadFarmTrees($farmId);
        require __DIR__ . "/../views/backoffice/user_farm_trees.php";
    }

    public function adminSaveTrees()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
        $farmId = filter_input(INPUT_GET, "farm_id", FILTER_VALIDATE_INT);
        $stmt = $this->pdo->prepare("SELECT f.*, u.name AS owner_name FROM farms f JOIN users u ON u.user_id = f.user_id WHERE f.farm_id = ? AND f.user_id = ?");
        $stmt->execute([$farmId, $userId]);
        $farm = $stmt->fetch();
        if (!$farm) {
            header("Location: index.php?action=users");
            exit;
        }
        $this->renderTreeCollectionForm(
            $farm,
            $this->loadFarmTrees($farmId),
            "index.php?action=adminSaveTrees&user_id=" . (int)$userId . "&farm_id=" . (int)$farmId,
            "index.php?action=adminFarmTrees&user_id=" . (int)$userId . "&farm_id=" . (int)$farmId,
            __DIR__ . "/../views/backoffice/user_trees_form.php"
        );
    }

    public function adminDeleteTree()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
        $farmId = filter_input(INPUT_GET, "farm_id", FILTER_VALIDATE_INT);
        $treeId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $userId && $farmId && $treeId && $this->treesTableExists()) {
            $stmt = $this->pdo->prepare("SELECT t.farm_tree_id FROM trees t JOIN farms f ON f.farm_id = t.farm_id WHERE t.farm_tree_id = ? AND t.farm_id = ? AND f.user_id = ?");
            $stmt->execute([$treeId, $farmId, $userId]);
            if ($stmt->fetch()) {
                $stmt = $this->pdo->prepare("DELETE FROM trees WHERE farm_tree_id = ? AND farm_id = ?");
                $stmt->execute([$treeId, $farmId]);
            }
        }
        header("Location: index.php?action=adminFarmTrees&user_id=" . (int)$userId . "&farm_id=" . (int)$farmId);
        exit;
    }
}
