
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>

<div class="profile-container">

    <div class="profile-header">
        <h1>AGREA</h1>
        <p>My Profile</p>
    </div>

    <div class="profile-card">

        <div class="profile-picture">
            <?php if (!empty($user["profile_picture"])): ?>
                <img
                    src="<?= htmlspecialchars($user["profile_picture"]) ?>"
                    alt="Profile picture"
                >
            <?php else: ?>
                <div class="default-avatar">
                    <?= strtoupper(substr($user["name"], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>

        <h2>
            <?= htmlspecialchars($user["name"]) ?>
        </h2>

        <span class="role">
            <?= htmlspecialchars(ucfirst($user["role"])) ?>
        </span>

        <div class="profile-information">

            <div class="profile-row">
                <strong>User ID</strong>
                <span><?= htmlspecialchars($user["user_id"]) ?></span>
            </div>

            <div class="profile-row">
                <strong>Birth Date</strong>
                <span><?= htmlspecialchars($user["birth_date"] ?? "Not provided") ?></span>
            </div>

            <div class="profile-row">
                <strong>Country</strong>
                <span><?= htmlspecialchars($user["country"] ?: "Not provided") ?></span>
            </div>

            <div class="profile-row">
                <strong>Phone Number</strong>
                <span><?= htmlspecialchars($user["phone_number"] ?: "Not provided") ?></span>
            </div>

            <div class="profile-row">
                <strong>Email</strong>
                <span><?= htmlspecialchars($user["mail"]) ?></span>
            </div>

            <div class="profile-row">
                <strong>Account Created</strong>
                <span><?= htmlspecialchars($user["created_at"]) ?></span>
            </div>

        </div>

        <div class="profile-actions">
            <a href="index.php?action=editProfile">Edit Profile</a>
            <a href="index.php?action=logout" class="logout-btn">Logout</a>
        </div>

    </div>

</div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
