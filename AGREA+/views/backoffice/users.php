
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management - AGREA</title>
    <link rel="icon" href="assets/images/logo/AGREA.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require __DIR__ . "/../shared/sidebar.php"; ?>

<header class="header">
    <h1>AGREA</h1>
    <p>Administration Back Office</p>
</header>

<div class="admin-container">

    <h2>User Management</h2>
    <p>Manage all registered AGREA users.</p>

    <div class="admin-stats">
        <div class="admin-stat-card">
            <h3><?= count($users) ?></h3>
            <p>Total Users</p>
        </div>

        <div class="admin-stat-card">
            <h3>
                <?= count(array_filter($users, fn($u) => $u["role"] === "farmer")) ?>
            </h3>
            <p>Farmers</p>
        </div>

        <div class="admin-stat-card">
            <h3><?= count(array_filter($users, fn($u) => $u["role"] === "client")) ?></h3>
            <p>Clients</p>
        </div>

        <div class="admin-stat-card">
            <h3><?= count(array_filter($users, fn($u) => $u["role"] === "employee")) ?></h3>
            <p>Employees</p>
        </div>

        <div class="admin-stat-card">
            <h3><?= count(array_filter($users, fn($u) => $u["role"] === "supplier")) ?></h3>
            <p>Suppliers</p>
        </div>

        <div class="admin-stat-card">
            <h3>
                <?= count(array_filter($users, fn($u) => $u["role"] === "expert")) ?>
            </h3>
            <p>Experts</p>
        </div>

        <div class="admin-stat-card">
            <h3>
                <?= count(array_filter($users, fn($u) => $u["role"] === "admin")) ?>
            </h3>
            <p>Administrators</p>
        </div>
    </div>

    <div class="users-table-container">

        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Role</th>
                    <th>Farms</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($users as $user): ?>

                    <tr>
                        <td><?= htmlspecialchars($user["user_id"]) ?></td>
                        <td><?= htmlspecialchars($user["name"]) ?></td>
                        <td><?= htmlspecialchars($user["mail"]) ?></td>
                        <td><?= htmlspecialchars($user["country"] ?? "—") ?></td>
                        <td>
                            <span class="role-badge">
                                <?= htmlspecialchars(ucfirst($user["role"])) ?>
                            </span>
                        </td>
                        <td><a href="index.php?action=adminUserFarms&amp;id=<?= (int)$user["user_id"] ?>">Manage Farms</a></td>
                        <td><?= htmlspecialchars($user["created_at"]) ?></td>
                        <td>
                            <a href="index.php?action=editUser&id=<?= (int)$user["user_id"] ?>">
                                Edit
                            </a>
                            |
                            <?php if ($user["user_id"] != $_SESSION["user_id"]): ?>
                                <form class="inline-form" method="post" action="index.php?action=deleteUser&amp;id=<?= (int)$user["user_id"] ?>"
                                      onsubmit="return confirm('Delete this user, their farms and tree records? This cannot be undone.')">
                                    <?= csrf_field() ?>
                                    <button class="text-danger" type="submit">Delete</button>
                                </form>
                            <?php else: ?>
                                <span>Current account</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8">No users found.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

    </div>

    <p>
        <a href="index.php?action=profile">Return to Profile</a>
        |
        <a href="index.php?action=logout">Logout</a>
    </p>

</div>

<script src="assets/js/app.js" defer></script>
</body>
</html>
