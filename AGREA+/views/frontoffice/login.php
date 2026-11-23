<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">

<div class="auth-shell">

    <?php require __DIR__ . "/../shared/auth_hero.php"; ?>

    <main class="auth-panel">
        <div class="auth-container">

            <h1>AGREA</h1>
            <h2>Welcome back 👋</h2>
            <p>Sign in to your account to continue.</p>

            <?php if (!empty($error)): ?>
                <div class="error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET["success"])): ?>
                <div class="success" role="status">
                    Login successful! Welcome to AGREA.
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=login">
<?= csrf_field() ?>

                <label for="mail">Email</label>
                <input id="mail" type="email" name="mail" placeholder="you@example.com" autocomplete="email" value="<?= htmlspecialchars($_POST["mail"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Your password" autocomplete="current-password" required>

                <button type="submit">Login</button>

            </form>

            <p>
                Don't have an account?
                <a href="index.php?action=register">Register</a>
            </p>

        </div>
    </main>

</div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
