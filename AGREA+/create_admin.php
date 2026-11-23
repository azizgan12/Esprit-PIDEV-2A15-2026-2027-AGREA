<?php

// One-time setup script. Run it from the terminal:  php create_admin.php
// It refuses to run from a remote browser request.
$isCli = PHP_SAPI === "cli";
$isLocal = in_array($_SERVER["REMOTE_ADDR"] ?? "", ["127.0.0.1", "::1"], true);

if (!$isCli && !$isLocal) {
    http_response_code(403);
    exit("Forbidden.");
}

require_once __DIR__ . "/config/database.php";

$database = new Database();
$pdo = $database->connect();

$name = "AGREA Admin";
$mail = "admin@agrea.com";
$password = "00000000"; // change this password right after the first login
$role = "admin";

// Check whether the account already exists
$check = $pdo->prepare(
    "SELECT user_id FROM users WHERE mail = ?"
);
$check->execute([$mail]);

if ($check->fetch()) {
    echo "Admin account already exists.";
} else {

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $stmt = $pdo->prepare("
        INSERT INTO users (name, mail, password, role)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $name,
        $mail,
        $hashedPassword,
        $role
    ]);

    echo "Admin account created successfully!";
}
