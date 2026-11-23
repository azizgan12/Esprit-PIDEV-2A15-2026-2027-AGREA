
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile - AGREA</title>
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
    <h2>Edit My Profile</h2>
    <p>Update your personal information.</p>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST"
          action="index.php?action=editProfile"
          enctype="multipart/form-data">
<?= csrf_field() ?>

        <label>Profile Picture</label>

        <?php if (!empty($user["profile_picture"])): ?>
            <img
                src="<?= htmlspecialchars($user["profile_picture"]) ?>"
                alt="Current profile picture"
                width="100"
                height="100"
                style="display:block; object-fit:cover; border-radius:50%; margin:10px 0;"
            >
        <?php endif; ?>

        <input type="file"
               name="profile_picture"
               accept="image/jpeg,image/png,image/webp">

        <small>JPG, PNG or WEBP. Maximum size: 2 MB.</small>

        <label>Full Name *</label>
        <input type="text"
               name="name"
               value="<?= htmlspecialchars($user["name"]) ?>"
               required>

        <label>Birth Date</label>
        <input type="date"
               name="birth_date"
               value="<?= htmlspecialchars($user["birth_date"] ?? "") ?>">

        <label>Country</label>
        <input type="text"
               name="country"
               value="<?= htmlspecialchars($user["country"] ?? "") ?>">

        <label>Phone Number</label>
        <input type="tel"
               name="phone_number"
               value="<?= htmlspecialchars($user["phone_number"] ?? "") ?>">

        <label>Email *</label>
        <input type="email"
               name="mail"
               value="<?= htmlspecialchars($user["mail"]) ?>"
               required>

        <button type="submit">Save Changes</button>

    </form>

    <p>
        <a href="index.php?action=profile">Cancel and return to profile</a>
    </p>

</div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
