<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($farm["farm_name"], ENT_QUOTES, "UTF-8") ?> - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>
<main class="admin-container">
    <p><a href="index.php?action=farms">← All Farms</a></p>
    <div class="dashboard-heading">
        <div>
            <h2><?= htmlspecialchars($farm["farm_name"], ENT_QUOTES, "UTF-8") ?></h2>
            <p><?= htmlspecialchars($farm["location"], ENT_QUOTES, "UTF-8") ?> · <?= htmlspecialchars((string)$farm["area_size"], ENT_QUOTES, "UTF-8") ?> hectares · <?= (int)($farm["tree_type_count"] ?? 0) ?> tree types</p>
        </div>
        <div class="farm-actions">
            <a href="index.php?action=saveFarm&amp;id=<?= (int)$farm["farm_id"] ?>">Edit farm</a>
            <?php if ($treesTableAvailable): ?>
                <a class="primary-action" href="index.php?action=saveTree&amp;farm_id=<?= (int)$farm["farm_id"] ?>">Add / Edit Tree Types</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!$treesTableAvailable): ?><div class="error" role="status">Tree records are not set up in the database yet. Apply <code>database/migrations/2026_11_23_create_trees_table.sql</code> to enable them.</div><?php endif; ?>
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
                    <td>
                        <form class="inline-form" method="post" action="index.php?action=deleteTree&amp;farm_id=<?= (int)$farm["farm_id"] ?>&amp;id=<?= (int)$tree["farm_tree_id"] ?>" onsubmit="return confirm('Delete this tree record?')">
<?= csrf_field() ?>
                            <button class="text-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$trees): ?><tr><td colspan="5">No tree types recorded yet. Add the trees growing on this farm.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
<script src="assets/js/app.js" defer></script>
</body>
</html>
