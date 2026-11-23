<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Farm.php";

class FarmController
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

    public function farms()
    {
        $this->requireFarmAccess();
        $stmt = $this->pdo->prepare("SELECT * FROM farms WHERE user_id = ? ORDER BY farm_id DESC");
        $stmt->execute([$_SESSION["user_id"]]);
        $farms = $stmt->fetchAll();
        $treesTableAvailable = $this->treesTableExists();
        if ($treesTableAvailable && $farms) {
            $counts = $this->pdo->prepare("SELECT farm_id, COUNT(DISTINCT tree_type) AS tree_type_count FROM trees WHERE farm_id IN (" . implode(",", array_fill(0, count($farms), "?")) . ") GROUP BY farm_id");
            $counts->execute(array_column($farms, "farm_id"));
            $countByFarm = array_column($counts->fetchAll(), "tree_type_count", "farm_id");
            foreach ($farms as &$farm) {
                $farm["tree_type_count"] = (int)($countByFarm[$farm["farm_id"]] ?? 0);
            }
            unset($farm);
        }
        require __DIR__ . "/../views/farms/index.php";
    }

    public function saveFarm()
    {
        $this->requireFarmAccess();
        $farmId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        $farm = null;
        if ($farmId) {
            $farm = $this->getOwnedFarm($farmId);
            if (!$farm) {
                http_response_code(404);
                echo "Farm not found.";
                return;
            }
        }

        $error = "";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $farmName = trim($_POST["farm_name"] ?? "");
            $location = trim($_POST["location"] ?? "");
            $areaSize = trim($_POST["area_size"] ?? "");
            if ($farmName === "" || strlen($farmName) > 100 || $location === "" || strlen($location) > 255 || !is_numeric($areaSize) || (float)$areaSize <= 0) {
                $error = "Enter a farm name, location, and area greater than zero.";
            } else {
                $farmModel = new Farm($farmName, $location, $areaSize, $_SESSION["user_id"], $farmId);
                if ($farm) {
                    $stmt = $this->pdo->prepare("UPDATE farms SET farm_name = ?, location = ?, area_size = ? WHERE farm_id = ? AND user_id = ?");
                    $stmt->execute([$farmModel->getFarmName(), $farmModel->getLocation(), $farmModel->getAreaSize(), $farmModel->getFarmId(), $farmModel->getUserId()]);
                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO farms (user_id, farm_name, location, area_size) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$farmModel->getUserId(), $farmModel->getFarmName(), $farmModel->getLocation(), $farmModel->getAreaSize()]);
                    $farmId = (int)$this->pdo->lastInsertId();
                }
                header("Location: index.php?action=farm&id=" . (int)$farmId);
                exit;
            }
            $farm = array_merge($farm ?: [], ["farm_name" => $farmName, "location" => $location, "area_size" => $areaSize]);
        }
        require __DIR__ . "/../views/frontoffice/farmForm.php";
    }

    public function deleteFarm()
    {
        $this->requireFarmAccess();
        $farmId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !$farmId || !$this->getOwnedFarm($farmId)) {
            header("Location: index.php?action=farms");
            exit;
        }
        $this->pdo->beginTransaction();
        try {
            if ($this->treesTableExists()) {
                $this->pdo->prepare("DELETE FROM trees WHERE farm_id = ?")->execute([$farmId]);
            }
            $this->pdo->prepare("DELETE FROM farms WHERE farm_id = ? AND user_id = ?")->execute([$farmId, $_SESSION["user_id"]]);
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        header("Location: index.php?action=farms");
        exit;
    }

    public function farm()
    {
        $this->requireFarmAccess();
        $farmId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        $farm = $farmId ? $this->getOwnedFarm($farmId) : false;
        if (!$farm) {
            http_response_code(404);
            echo "Farm not found.";
            return;
        }
        $trees = [];
        $treesTableAvailable = $this->treesTableExists();
        if ($treesTableAvailable) {
            $stmt = $this->pdo->prepare("SELECT * FROM trees WHERE farm_id = ? ORDER BY tree_type, farm_tree_id");
            $stmt->execute([$farmId]);
            $trees = $stmt->fetchAll();
        }
        $farm["tree_type_count"] = count(array_unique(array_column($trees, "tree_type")));
        require __DIR__ . "/../views/farms/show.php";
    }

    public function adminUserFarms()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        $stmt = $this->pdo->prepare("SELECT user_id, name, mail, role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $managedUser = $stmt->fetch();
        if (!$managedUser) {
            header("Location: index.php?action=users");
            exit;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM farms WHERE user_id = ? ORDER BY farm_id DESC");
        $stmt->execute([$userId]);
        $farms = $stmt->fetchAll();
        $hasTreesTable = $this->treesTableExists();
        if ($hasTreesTable && $farms) {
            $counts = $this->pdo->prepare("SELECT farm_id, COUNT(DISTINCT tree_type) AS tree_type_count FROM trees WHERE farm_id IN (" . implode(",", array_fill(0, count($farms), "?")) . ") GROUP BY farm_id");
            $counts->execute(array_column($farms, "farm_id"));
            $countByFarm = array_column($counts->fetchAll(), "tree_type_count", "farm_id");
            foreach ($farms as &$farm) {
                $farm["tree_type_count"] = (int)($countByFarm[$farm["farm_id"]] ?? 0);
            }
            unset($farm);
        }
        require __DIR__ . "/../views/backoffice/user_farms.php";
    }

    public function adminSaveFarm()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
        $farmId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        $stmt = $this->pdo->prepare("SELECT user_id, name, mail, role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $managedUser = $stmt->fetch();
        if (!$managedUser) {
            header("Location: index.php?action=users");
            exit;
        }
        $farm = null;
        if ($farmId) {
            $stmt = $this->pdo->prepare("SELECT * FROM farms WHERE farm_id = ? AND user_id = ?");
            $stmt->execute([$farmId, $userId]);
            $farm = $stmt->fetch();
            if (!$farm) {
                header("Location: index.php?action=adminUserFarms&id=" . (int)$userId);
                exit;
            }
        }
        $error = "";
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $farmName = trim($_POST["farm_name"] ?? "");
            $location = trim($_POST["location"] ?? "");
            $areaSize = trim($_POST["area_size"] ?? "");
            if ($farmName === "" || strlen($farmName) > 100 || $location === "" || strlen($location) > 255 || !is_numeric($areaSize) || (float)$areaSize <= 0) {
                $error = "Enter a farm name, location, and area greater than zero.";
            } else {
                $farmModel = new Farm($farmName, $location, $areaSize, $userId, $farmId);
                if ($farm) {
                    $stmt = $this->pdo->prepare("UPDATE farms SET farm_name = ?, location = ?, area_size = ? WHERE farm_id = ? AND user_id = ?");
                    $stmt->execute([$farmModel->getFarmName(), $farmModel->getLocation(), $farmModel->getAreaSize(), $farmModel->getFarmId(), $farmModel->getUserId()]);
                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO farms (user_id, farm_name, location, area_size) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$farmModel->getUserId(), $farmModel->getFarmName(), $farmModel->getLocation(), $farmModel->getAreaSize()]);
                }
                header("Location: index.php?action=adminUserFarms&id=" . (int)$userId);
                exit;
            }
            $farm = array_merge($farm ?: [], ["farm_name" => $farmName, "location" => $location, "area_size" => $areaSize]);
        }
        require __DIR__ . "/../views/backoffice/user_farm_form.php";
    }

    public function adminDeleteFarm()
    {
        $this->checkAdmin();
        $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
        $farmId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $userId && $farmId) {
            $check = $this->pdo->prepare("SELECT farm_id FROM farms WHERE farm_id = ? AND user_id = ?");
            $check->execute([$farmId, $userId]);
            if (!$check->fetch()) {
                header("Location: index.php?action=adminUserFarms&id=" . (int)$userId);
                exit;
            }
            $this->pdo->beginTransaction();
            try {
                if ($this->treesTableExists()) {
                    $this->pdo->prepare("DELETE FROM trees WHERE farm_id = ?")->execute([$farmId]);
                }
                $this->pdo->prepare("DELETE FROM farms WHERE farm_id = ? AND user_id = ?")->execute([$farmId, $userId]);
                $this->pdo->commit();
            } catch (Throwable $e) {
                $this->pdo->rollBack();
                throw $e;
            }
        }
        header("Location: index.php?action=adminUserFarms&id=" . (int)$userId);
        exit;
    }
}
