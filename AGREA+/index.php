<?php

require_once "config/session.php";

// Reject any POST request that does not carry a valid CSRF token
if ($_SERVER["REQUEST_METHOD"] === "POST" && !csrf_verify()) {
    http_response_code(419);
    echo "Your session expired or the form is invalid. Please go back, refresh the page and try again.";
    exit;
}

$action = $_GET["action"] ?? "login";

switch ($action) {

    case "register":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->register();
        break;

    case "login":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->login();
        break;

    case "profile":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->profile();
        break;

    case "editProfile":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->editProfile();
        break;

    case "logout":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->logout();
        break;

    case "users":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->users();
        break;

    case "editUser":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->editUser();
        break;

    case "deleteUser":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->deleteUser();
        break;

    case "farms":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->farms();
        break;

    case "farm":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->farm();
        break;

    case "saveFarm":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->saveFarm();
        break;

    case "deleteFarm":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->deleteFarm();
        break;

    case "saveTree":
        require_once "controllers/TreeController.php";
        $controller = new TreeController();
        $controller->saveTree();
        break;

    case "deleteTree":
        require_once "controllers/TreeController.php";
        $controller = new TreeController();
        $controller->deleteTree();
        break;

    case "community":
        require_once "controllers/UserController.php";
        $controller = new UserController();
        $controller->community();
        break;

    case "adminUserFarms":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->adminUserFarms();
        break;

    case "adminSaveFarm":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->adminSaveFarm();
        break;

    case "adminDeleteFarm":
        require_once "controllers/FarmController.php";
        $controller = new FarmController();
        $controller->adminDeleteFarm();
        break;

    case "adminFarmTrees":
        require_once "controllers/TreeController.php";
        $controller = new TreeController();
        $controller->adminFarmTrees();
        break;

    case "adminSaveTrees":
        require_once "controllers/TreeController.php";
        $controller = new TreeController();
        $controller->adminSaveTrees();
        break;

    case "adminDeleteTree":
        require_once "controllers/TreeController.php";
        $controller = new TreeController();
        $controller->adminDeleteTree();
        break;

    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}
