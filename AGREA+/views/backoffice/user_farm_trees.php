<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farm Tree Types - AGREA Admin</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>
<main class="admin-container">
    <p><a href="index.php?action=adminUserFarms&amp;id=<?= (int)$farm["user_id"] ?>">← User Farms</a></p>
    <div class="dashboard-heading">
        <div>
            <h2><?= htmlspecialchars($farm["farm_name"], ENT_QUOTES, "UTF-8") ?>: Tree Types</h2>
            <p>Owned by <?= htmlspecialchars($farm["owner_name"], ENT_QUOTES, "UTF-8") ?> · <?= htmlspecialchars($farm["location"], ENT_QUOTES, "UTF-8") ?></p>
        </div>
        <?php if ($hasTreesTable): ?>
            <a class="primary-action" href="index.php?action=adminSaveTrees&amp;user_id=<?= (int)$farm["user_id"] ?>&amp;farm_id=<?= (int)$farm["farm_id"] ?>">Add / Edit Tree Types</a>
        <?php endif; ?>
    </div>
    <?php if (!$hasTreesTable): ?><div class="error" role="status">Tree records are unavailable until <code>database/migrations/2026_11_23_create_trees_table.sql</code> is applied.</div><?php endif; ?>
    <section class="users-table-container">
        <table class="users-table">
            <thead><tr><th>Tree Type</th><th>Quantity</th><th>Area (ha)</th><th>Planting Year</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($trees as $tree): ?>
                <tr>
                    <td><?= htmlspecialchars($tree["tree_type"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= (int)$tree["quantity"] ?></td>
                    <td><?= htmlspecialchars((string)$tree["area_size"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string)$tree["planting_year"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><form class="inline-form" method="post" action="index.php?action=adminDeleteTree&amp;user_id=<?= (int)$farm["user_id"] ?>&amp;farm_id=<?= (int)$farm["farm_id"] ?>&amp;id=<?= (int)$tree["farm_tree_id"] ?>" onsubmit="return confirm('Delete this tree type?')">
<?= csrf_field() ?><button class="text-danger" type="submit">Delete</button></form></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$trees): ?><tr><td colspan="5">No tree types recorded for this farm yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
<script src="assets/js/app.js" defer></script>
</body>
</html>
