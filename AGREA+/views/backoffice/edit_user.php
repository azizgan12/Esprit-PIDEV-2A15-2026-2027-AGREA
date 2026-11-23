<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>
<div class="auth-container">
    <h1>AGREA</h1>
    <h2>Edit User Profile</h2>
    <p>Update account details and access role for <?= htmlspecialchars($user["name"], ENT_QUOTES, "UTF-8") ?>.</p>

    <?php if ($error !== ""): ?>
        <div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=editUser&amp;id=<?= (int)$user["user_id"] ?>" enctype="multipart/form-data">
<?= csrf_field() ?>
        <label for="profile_picture">Profile Picture</label>
        <?php if (!empty($user["profile_picture"])): ?>
            <img src="<?= htmlspecialchars($user["profile_picture"], ENT_QUOTES, "UTF-8") ?>" alt="Current profile picture" width="100" height="100" style="display:block;object-fit:cover;border-radius:50%;margin:10px 0;">
        <?php endif; ?>
        <input id="profile_picture" type="file" name="profile_picture" accept="image/jpeg,image/png,image/webp">
        <small>JPG, PNG or WEBP. Maximum size: 2 MB.</small>

        <label for="name">Full Name *</label>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($user["name"], ENT_QUOTES, "UTF-8") ?>" required>

        <label for="birth_date">Birth Date</label>
        <input id="birth_date" type="date" name="birth_date" value="<?= htmlspecialchars($user["birth_date"] ?? "", ENT_QUOTES, "UTF-8") ?>">

        <label for="country">Country</label>
        <input id="country" type="text" name="country" value="<?= htmlspecialchars($user["country"] ?? "", ENT_QUOTES, "UTF-8") ?>">

        <label for="phone_number">Phone Number</label>
        <input id="phone_number" type="tel" name="phone_number" value="<?= htmlspecialchars($user["phone_number"] ?? "", ENT_QUOTES, "UTF-8") ?>">

        <label for="mail">Email *</label>
        <input id="mail" type="email" name="mail" value="<?= htmlspecialchars($user["mail"], ENT_QUOTES, "UTF-8") ?>" required>

        <label for="role">Role</label>
        <select id="role" name="role" required>
            <?php foreach (["farmer" => "Farmer", "client" => "Client", "employee" => "Employee", "supplier" => "Supplier", "expert" => "Expert", "admin" => "Administrator"] as $value => $label): ?>
                <option value="<?= $value ?>" <?= $user["role"] === $value ? "selected" : "" ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save User</button>
    </form>
    <p><a href="index.php?action=users">Cancel and return to users</a></p>
</div>
<script src="assets/js/app.js" defer></script>
</body>
</html>
