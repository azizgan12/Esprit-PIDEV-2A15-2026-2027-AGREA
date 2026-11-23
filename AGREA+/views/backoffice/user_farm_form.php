<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= !empty($farm["farm_id"]) ? "Edit Farm" : "Add Farm" ?> - AGREA Admin</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>
<main class="auth-container">
    <h1>AGREA</h1>
    <h2><?= !empty($farm["farm_id"]) ? "Edit Farm" : "Add Farm" ?></h2>
    <p>Farm owner: <?= htmlspecialchars($managedUser["name"], ENT_QUOTES, "UTF-8") ?></p>
    <?php if ($error !== ""): ?><div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
    <form method="post" action="index.php?action=adminSaveFarm&amp;user_id=<?= (int)$managedUser["user_id"] ?><?= !empty($farm["farm_id"]) ? "&amp;id=" . (int)$farm["farm_id"] : "" ?>">
<?= csrf_field() ?>
        <label for="farm_name">Farm Name *</label>
        <input id="farm_name" name="farm_name" maxlength="100" value="<?= htmlspecialchars($farm["farm_name"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
        <label for="location">Location *</label>
        <input id="location" name="location" maxlength="255" value="<?= htmlspecialchars($farm["location"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
        <label for="area_size">Farm Area (hectares) *</label>
        <input id="area_size" type="number" name="area_size" min="0.01" step="0.01" value="<?= htmlspecialchars((string)($farm["area_size"] ?? ""), ENT_QUOTES, "UTF-8") ?>" required>
        <button type="submit">Save Farm</button>
    </form>
    <p><a href="index.php?action=adminUserFarms&amp;id=<?= (int)$managedUser["user_id"] ?>">Cancel and return to user farms</a></p>
</main>
<script src="assets/js/app.js" defer></script>
</body>
</html>
