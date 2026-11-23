<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - AGREA</title>
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
            <h2>Create your account 🌱</h2>
            <p>Join AGREA and build a greener future.</p>

            <?php if (!empty($error)): ?>
                <div class="error" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=register">
<?= csrf_field() ?>

                <div class="form-grid">
                    <div class="full">
                        <label for="name">Full Name *</label>
                        <input id="name" type="text" name="name" autocomplete="name" required>
                    </div>

                    <div>
                        <label for="birth_date">Birth Date</label>
                        <input id="birth_date" type="date" name="birth_date">
                    </div>

                    <div>
                        <label for="country">Country</label>
                        <input id="country" type="text" name="country" autocomplete="country-name">
                    </div>

                    <div>
                        <label for="phone_number">Phone Number</label>
                        <input id="phone_number" type="tel" name="phone_number" autocomplete="tel">
                    </div>

                    <div>
                        <label for="role">I am a *</label>
                        <select id="role" name="role" required>
                            <?php foreach (["farmer" => "Farmer", "client" => "Client", "employee" => "Employee", "supplier" => "Supplier", "expert" => "Expert"] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($role ?? "farmer") === $value ? "selected" : "" ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="full">
                        <label for="mail">Email *</label>
                        <input id="mail" type="email" name="mail" autocomplete="email" required>
                    </div>

                    <div>
                        <label for="password">Password *</label>
                        <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" required>
                    </div>

                    <div>
                        <label for="confirm_password">Confirm Password *</label>
                        <input id="confirm_password" type="password" name="confirm_password" minlength="8" autocomplete="new-password" required>
                    </div>
                </div>

                <button type="submit">Create Account</button>

            </form>

            <p>Already have an account?
                <a href="index.php?action=login">Login</a>
            </p>

        </div>
    </main>

</div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
