<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        "httponly" => true,
        "samesite" => "Lax",
    ]);
    session_start();
}

/**
 * CSRF protection: every POST request must carry the session token.
 * Views add it with <?= csrf_field() ?>, index.php verifies it centrally.
 */
function csrf_token(): string
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES, "UTF-8") . '">';
}

function csrf_verify(): bool
{
    $sent = $_POST["csrf_token"] ?? "";
    return is_string($sent)
        && !empty($_SESSION["csrf_token"])
        && hash_equals($_SESSION["csrf_token"], $sent);
}
