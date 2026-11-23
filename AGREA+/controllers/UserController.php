
<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/User.php";


class UserController
{
    private $pdo;

   public function __construct()
{
    $database = new Database();
    $this->pdo = $database->connect();
}

    // Accepts an empty value or a real Y-m-d date that is not in the future
    private function isValidBirthDate($value)
    {
        if ($value === null || $value === "") {
            return true;
        }
        $date = DateTime::createFromFormat("Y-m-d", $value);
        return $date
            && $date->format("Y-m-d") === $value
            && $date <= new DateTime("today")
            && (int)$date->format("Y") >= 1900;
    }

    // Removes an uploaded profile picture, only inside assets/images/profiles
    private function deleteProfilePicture($path)
    {
        if (!$path || strpos($path, "assets/images/profiles/") !== 0) {
            return;
        }
        $file = __DIR__ . "/../assets/images/profiles/" . basename($path);
        if (is_file($file)) {
            @unlink($file);
        }
    }

    private function redirectIfLoggedIn()
    {
        if (isset($_SESSION["user_id"])) {
            header("Location: index.php?action=profile");
            exit;
        }
    }

    public function register()
    {
        $this->redirectIfLoggedIn();
        $error = "";
        $roles = ["farmer", "client", "employee", "supplier", "expert"];
        $role = $_POST["role"] ?? "farmer";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $name = trim($_POST["name"] ?? "");
            $birth_date = $_POST["birth_date"] ?? null;
            $country = trim($_POST["country"] ?? "");
            $phone_number = trim($_POST["phone_number"] ?? "");
            $mail = trim($_POST["mail"] ?? "");
            $password = $_POST["password"] ?? "";
            $confirm_password = $_POST["confirm_password"] ?? "";

            if (
                $name === "" ||
                $mail === "" ||
                $password === "" ||
                $confirm_password === ""
            ) {
                $error = "Please fill in all required fields.";

            } elseif (!in_array($role, $roles, true)) {
                $error = "Please choose a valid account type.";

            } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email address.";

            } elseif (!$this->isValidBirthDate($birth_date)) {
                $error = "Enter a valid birth date (not in the future).";

            } elseif (strlen($password) < 8) {
                $error = "Password must contain at least 8 characters.";

            } elseif ($password !== $confirm_password) {
                $error = "Passwords do not match.";

            } else {

                $check = $this->pdo->prepare(
                    "SELECT user_id FROM users WHERE mail = ?"
                );

                $check->execute([$mail]);

                if ($check->fetch()) {
                    $error = "This email is already registered.";

                } else {

                    $user = new User(
                        $name,
                        $birth_date ?: null,
                        $country,
                        $phone_number,
                        $mail,
                        password_hash($password, PASSWORD_DEFAULT),
                        null,
                        $role
                    );

                    $sql = "INSERT INTO users
                    (name, birth_date, country, phone_number, mail, password, role)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $this->pdo->prepare($sql);

                    $stmt->execute([
                        $user->getName(),
                        $user->getBirthDate(),
                        $user->getCountry(),
                        $user->getPhoneNumber(),
                        $user->getMail(),
                        $user->getPassword(),
                        $user->getRole()
                    ]);

                    header("Location: index.php?action=login");
                    exit;
                }
            }
        }

        require __DIR__ . "/../views/frontoffice/register.php";
    }

public function login()
{
    $this->redirectIfLoggedIn();
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $mail = trim($_POST["mail"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($mail === "" || $password === "") {
            $error = "Please enter your email and password.";

        } else {

            $stmt = $this->pdo->prepare(
                "SELECT * FROM users WHERE mail = ?"
            );

            $stmt->execute([$mail]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["mail"] = $user["mail"];
                $_SESSION["role"] = $user["role"];

               header("Location: index.php?action=profile");
                exit;

            } else {
                $error = "Incorrect email or password.";
            }
        }
    }

    require __DIR__ . "/../views/frontoffice/login.php";
}

public function profile()
{
    // Check if user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php?action=login");
        exit;
    }

    // Get current user's information
    $stmt = $this->pdo->prepare("
        SELECT user_id, name, birth_date, country,
               phone_number, mail, profile_picture,
               role, created_at
        FROM users
        WHERE user_id = ?
    ");

    $stmt->execute([$_SESSION["user_id"]]);

    $user = $stmt->fetch();

    if (!$user) {
        session_unset();
        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }

    if ($user["role"] === "admin") {
        $counts = $this->pdo->query(
            "SELECT COUNT(*) AS total,
                    SUM(role = 'farmer') AS farmers,
                    SUM(role = 'client') AS clients,
                    SUM(role = 'employee') AS employees,
                    SUM(role = 'supplier') AS suppliers,
                    SUM(role = 'expert') AS experts,
                    SUM(role = 'admin') AS admins
             FROM users"
        )->fetch();

        require __DIR__ . "/../views/backoffice/dashboard.php";
        return;
    }

    // Display profile
    require __DIR__ . "/../views/frontoffice/profile.php";
}

public function editProfile()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php?action=login");
        exit;
    }

    $error = "";

    // Get current user information
    $stmt = $this->pdo->prepare(
        "SELECT * FROM users WHERE user_id = ?"
    );

    $stmt->execute([$_SESSION["user_id"]]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: index.php?action=logout");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $name = trim($_POST["name"] ?? "");
        $birth_date = $_POST["birth_date"] ?? null;
        $country = trim($_POST["country"] ?? "");
        $phone_number = trim($_POST["phone_number"] ?? "");
        $mail = trim($_POST["mail"] ?? "");

        $profile_picture = $user["profile_picture"];

        if ($name === "" || $mail === "") {
            $error = "Name and email are required.";

        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";

        } elseif (!$this->isValidBirthDate($birth_date)) {
            $error = "Enter a valid birth date (not in the future).";

        } else {

            // Check whether another user uses this email
            $check = $this->pdo->prepare(
                "SELECT user_id FROM users
                 WHERE mail = ? AND user_id != ?"
            );

            $check->execute([$mail, $_SESSION["user_id"]]);

            if ($check->fetch()) {
                $error = "This email is already used by another account.";

            } else {

                // Handle profile picture upload
                if (
                    isset($_FILES["profile_picture"]) &&
                    $_FILES["profile_picture"]["error"] !== UPLOAD_ERR_NO_FILE
                ) {

                    if ($_FILES["profile_picture"]["error"] !== UPLOAD_ERR_OK) {
                        $error = "Image upload failed.";

                    } elseif ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
                        $error = "Image must be smaller than 2 MB.";

                    } else {

                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file(
                            $_FILES["profile_picture"]["tmp_name"]
                        );

                        $allowed = [
                            "image/jpeg" => "jpg",
                            "image/png" => "png",
                            "image/webp" => "webp"
                        ];

                        if (!isset($allowed[$mime])) {
                            $error = "Only JPG, PNG and WEBP images are allowed.";

                        } else {

                            $folder = __DIR__ . "/../assets/images/profiles/";

                            if (!is_dir($folder)) {
                                mkdir($folder, 0755, true);
                            }

                            $filename = bin2hex(random_bytes(16))
                                . "." . $allowed[$mime];

                            if (move_uploaded_file(
                                $_FILES["profile_picture"]["tmp_name"],
                                $folder . $filename
                            )) {
                                $profile_picture =
                                    "assets/images/profiles/" . $filename;
                            } else {
                                $error = "Could not save the uploaded image.";
                            }
                        }
                    }
                }

                if ($error === "") {

                    $update = $this->pdo->prepare("
                        UPDATE users
                        SET name = ?,
                            birth_date = ?,
                            country = ?,
                            phone_number = ?,
                            mail = ?,
                            profile_picture = ?
                        WHERE user_id = ?
                    ");

                    $update->execute([
                        $name,
                        $birth_date ?: null,
                        $country,
                        $phone_number,
                        $mail,
                        $profile_picture,
                        $_SESSION["user_id"]
                    ]);

                    if ($profile_picture !== $user["profile_picture"]) {
                        $this->deleteProfilePicture($user["profile_picture"]);
                    }

                    $_SESSION["name"] = $name;
                    $_SESSION["mail"] = $mail;

                    header("Location: index.php?action=profile");
                    exit;
                }
            }
        }

        // Refresh displayed values if validation fails
        $user["name"] = $name;
        $user["birth_date"] = $birth_date;
        $user["country"] = $country;
        $user["phone_number"] = $phone_number;
        $user["mail"] = $mail;
        $user["profile_picture"] = $profile_picture;
    }

    require __DIR__ . "/../views/frontoffice/edit_profile.php";
}

public function logout()
{
    // Clear session data
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Return to login page
    header("Location: index.php?action=login");
    exit;
}

private function checkAdmin()
{
    if (
        !isset($_SESSION["user_id"]) ||
        !isset($_SESSION["role"]) ||
        $_SESSION["role"] !== "admin"
    ) {
        header("Location: index.php?action=login");
        exit;
    }
}

public function users()
{
    $this->checkAdmin();

    $stmt = $this->pdo->query("
        SELECT user_id, name, mail, country, role, created_at
        FROM users
        ORDER BY user_id DESC
    ");

    $users = $stmt->fetchAll();

    require __DIR__ . "/../views/backoffice/users.php";
}

public function editUser()
{
    $this->checkAdmin();

    $userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$userId || $userId < 1) {
        header("Location: index.php?action=users");
        exit;
    }

    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: index.php?action=users");
        exit;
    }

    $error = "";
    $roles = ["farmer", "client", "employee", "supplier", "expert", "admin"];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = trim($_POST["name"] ?? "");
        $birthDate = trim($_POST["birth_date"] ?? "");
        $country = trim($_POST["country"] ?? "");
        $phoneNumber = trim($_POST["phone_number"] ?? "");
        $mail = trim($_POST["mail"] ?? "");
        $role = $_POST["role"] ?? "";
        $profilePicture = $user["profile_picture"];

        if ($name === "" || $mail === "") {
            $error = "Name and email are required.";
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $error = "Enter a valid email address.";
        } elseif (!in_array($role, $roles, true)) {
            $error = "Choose a valid role.";
        } elseif (!$this->isValidBirthDate($birthDate)) {
            $error = "Enter a valid birth date (not in the future).";
        } elseif ((int)$_SESSION["user_id"] === (int)$userId && $role !== "admin") {
            $error = "You cannot remove your own administrator role.";
        } else {
            $check = $this->pdo->prepare(
                "SELECT user_id FROM users WHERE mail = ? AND user_id != ?"
            );
            $check->execute([$mail, $userId]);
            if ($check->fetch()) {
                $error = "This email is already used by another account.";
            }
        }

        if ($error === "" && isset($_FILES["profile_picture"]) &&
            $_FILES["profile_picture"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES["profile_picture"];
            if ($file["error"] !== UPLOAD_ERR_OK) {
                $error = "Image upload failed.";
            } elseif ($file["size"] > 2 * 1024 * 1024) {
                $error = "Image must be smaller than 2 MB.";
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file["tmp_name"]);
                $allowed = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
                if (!isset($allowed[$mime])) {
                    $error = "Only JPG, PNG and WEBP images are allowed.";
                } else {
                    $folder = __DIR__ . "/../assets/images/profiles/";
                    if (!is_dir($folder) && !mkdir($folder, 0755, true) && !is_dir($folder)) {
                        $error = "Could not create the profile image folder.";
                    } else {
                        $filename = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
                        if (move_uploaded_file($file["tmp_name"], $folder . $filename)) {
                            $profilePicture = "assets/images/profiles/" . $filename;
                        } else {
                            $error = "Could not save the uploaded image.";
                        }
                    }
                }
            }
        }

        if ($error === "") {
            $update = $this->pdo->prepare("UPDATE users SET name = ?, birth_date = ?, country = ?, phone_number = ?, mail = ?, profile_picture = ?, role = ? WHERE user_id = ?");
            $update->execute([$name, $birthDate ?: null, $country, $phoneNumber, $mail, $profilePicture, $role, $userId]);

            if ($profilePicture !== $user["profile_picture"]) {
                $this->deleteProfilePicture($user["profile_picture"]);
            }

            if ((int)$_SESSION["user_id"] === (int)$userId) {
                $_SESSION["name"] = $name;
                $_SESSION["mail"] = $mail;
                $_SESSION["role"] = $role;
            }
            header("Location: index.php?action=users");
            exit;
        }

        $user = array_merge($user, [
            "name" => $name, "birth_date" => $birthDate, "country" => $country,
            "phone_number" => $phoneNumber, "mail" => $mail, "role" => $role,
            "profile_picture" => $profilePicture
        ]);
    }

    require __DIR__ . "/../views/backoffice/edit_user.php";
}

public function deleteUser()
{
    $this->checkAdmin();

    $userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    // Deleting is only allowed through a POST form, never for the current account
    if (
        $_SERVER["REQUEST_METHOD"] !== "POST" ||
        !$userId ||
        (int)$userId === (int)$_SESSION["user_id"]
    ) {
        header("Location: index.php?action=users");
        exit;
    }

    $stmt = $this->pdo->prepare("SELECT profile_picture FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $target = $stmt->fetch();

    if (!$target) {
        header("Location: index.php?action=users");
        exit;
    }

    $this->pdo->beginTransaction();
    try {
        $hasTrees = (bool)$this->pdo->query("SHOW TABLES LIKE 'trees'")->fetchColumn();

        if ($hasTrees) {
            $this->pdo->prepare(
                "DELETE FROM trees WHERE farm_id IN (SELECT farm_id FROM farms WHERE user_id = ?)"
            )->execute([$userId]);
        }

        $this->pdo->prepare("DELETE FROM farms WHERE user_id = ?")->execute([$userId]);
        $this->pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$userId]);
        $this->pdo->commit();
    } catch (Throwable $e) {
        $this->pdo->rollBack();
        throw $e;
    }

    $this->deleteProfilePicture($target["profile_picture"]);

    header("Location: index.php?action=users");
    exit;
}

public function community()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php?action=login");
        exit;
    }
    require __DIR__ . "/../views/community/index.php";
}

}
