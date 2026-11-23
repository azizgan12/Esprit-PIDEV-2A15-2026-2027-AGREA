<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Farms - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>
<main class="admin-container">
    <div class="dashboard-heading">
        <div><h2>My Farms</h2><p>Create and manage your farms and the trees growing in them.</p></div>
        <a class="primary-action" href="index.php?action=saveFarm">+ Add Farm</a>
    </div>
    <?php if (!$treesTableAvailable): ?><div class="error" role="status">Farm management is available. Apply <code>database/migrations/2026_11_23_create_trees_table.sql</code> to enable tree records.</div><?php endif; ?>

    <?php if (!$farms): ?>
        <div class="empty-state"><h3>No farms yet</h3><p>Add your first farm to record its location, size, and tree types.</p><a class="primary-action" href="index.php?action=saveFarm">Add your first farm</a></div>
    <?php else: ?>
        <div class="farm-grid">
            <?php foreach ($farms as $farm): ?>
                <article class="farm-card">
                    <h3><?= htmlspecialchars($farm["farm_name"], ENT_QUOTES, "UTF-8") ?></h3>
                    <p><?= htmlspecialchars($farm["location"], ENT_QUOTES, "UTF-8") ?></p>
                    <p><strong>Area:</strong> <?= htmlspecialchars((string)$farm["area_size"], ENT_QUOTES, "UTF-8") ?> ha</p>
                    <p><strong>Tree types:</strong> <?= (int)($farm["tree_type_count"] ?? 0) ?></p>
                    <div class="farm-actions">
                        <a href="index.php?action=farm&amp;id=<?= (int)$farm["farm_id"] ?>">View trees</a>
                        <?php if ($treesTableAvailable): ?><a href="index.php?action=saveTree&amp;farm_id=<?= (int)$farm["farm_id"] ?>">Edit trees</a><?php endif; ?>
                        <a href="index.php?action=saveFarm&amp;id=<?= (int)$farm["farm_id"] ?>">Edit</a>
                        <form method="post" action="index.php?action=deleteFarm&amp;id=<?= (int)$farm["farm_id"] ?>" onsubmit="return confirm('Delete this farm and all its tree records?')">
<?= csrf_field() ?>
                            <button class="text-danger" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<script src="assets/js/app.js" defer></script>
</body>
</html>
