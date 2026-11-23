<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - AGREA</title>
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

<main class="admin-container">
    <div class="dashboard-heading">
        <div>
            <h2>Welcome, <?= htmlspecialchars($user["name"], ENT_QUOTES, "UTF-8") ?></h2>
            <p>Manage AGREA accounts and access future administration tools.</p>
        </div>
        <span class="role-badge">Administrator</span>
    </div>

    <section class="admin-stats" aria-label="User totals">
        <div class="admin-stat-card"><h3><?= (int)$counts["total"] ?></h3><p>Total Users</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["farmers"] ?></h3><p>Farmers</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["clients"] ?></h3><p>Clients</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["employees"] ?></h3><p>Employees</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["suppliers"] ?></h3><p>Suppliers</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["experts"] ?></h3><p>Experts</p></div>
        <div class="admin-stat-card"><h3><?= (int)$counts["admins"] ?></h3><p>Administrators</p></div>
    </section>

    <section class="dashboard-section">
        <h2>Administration</h2>
        <div class="dashboard-grid">
            <a class="dashboard-card" href="index.php?action=users">
                <span class="dashboard-card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <span class="dashboard-card-title">User Management</span>
                <span class="dashboard-card-description">View, edit, and manage registered user profiles.</span>
                <span class="dashboard-card-link">Manage users →</span>
            </a>
            <a class="dashboard-card" href="index.php?action=editProfile">
                <span class="dashboard-card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
                <span class="dashboard-card-title">My Admin Profile</span>
                <span class="dashboard-card-description">Update your own contact details and profile picture.</span>
                <span class="dashboard-card-link">Edit my profile →</span>
            </a>
            <div class="dashboard-card dashboard-card-coming-soon">
                <span class="dashboard-card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
                <span class="dashboard-card-title">Reports</span>
                <span class="dashboard-card-description">A place for future platform activity and reporting tools.</span>
                <span class="dashboard-card-status">Coming soon</span>
            </div>
            <div class="dashboard-card dashboard-card-coming-soon">
                <span class="dashboard-card-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></span>
                <span class="dashboard-card-title">Platform Settings</span>
                <span class="dashboard-card-description">A place for future AGREA configuration tools.</span>
                <span class="dashboard-card-status">Coming soon</span>
            </div>
        </div>
    </section>

    <p class="dashboard-footer">
        Signed in as <?= htmlspecialchars($user["mail"], ENT_QUOTES, "UTF-8") ?>
        <span>·</span> <a href="index.php?action=logout">Logout</a>
    </p>
</main>
<script src="assets/js/app.js" defer></script>
</body>
</html>
