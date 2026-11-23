<?php
$agreaAction = $_GET["action"] ?? "";
$agreaRole = $_SESSION["role"] ?? "";
$agreaName = $_SESSION["name"] ?? "";

$agreaIsActive = static function (array $actions) use ($agreaAction): string {
    return in_array($agreaAction, $actions, true) ? " active" : "";
};
?>
<button class="sidebar-toggle" type="button" aria-label="Open menu" aria-controls="app-sidebar" aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
</button>
<div class="sidebar-backdrop"></div>

<aside class="app-sidebar" id="app-sidebar">
    <a class="sidebar-brand" href="index.php?action=profile">
        <span class="brand-mark"><img src="assets/images/logo/AGREA.png" alt="AGREA logo" onerror="this.replaceWith(document.createTextNode('A'))"></span>
        <span class="brand-text">AGREA<small>Grow greener</small></span>
    </a>

    <nav aria-label="Main navigation">
        <a class="<?= trim($agreaIsActive(["profile", "editProfile"])) ?>" href="index.php?action=profile">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>My Profile</span>
        </a>
        <a class="<?= trim($agreaIsActive(["community"])) ?>" href="index.php?action=community">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Community</span>
        </a>
        <?php if (in_array($agreaRole, ["farmer", "admin"], true)): ?>
            <a class="<?= trim($agreaIsActive(["farms", "farm", "saveFarm", "saveTree"])) ?>" href="index.php?action=farms">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                <span>My Farms</span>
            </a>
        <?php endif; ?>
        <?php if ($agreaRole === "admin"): ?>
            <a class="<?= trim($agreaIsActive(["users", "editUser", "adminUserFarms", "adminSaveFarm", "adminFarmTrees", "adminSaveTrees"])) ?>" href="index.php?action=users">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>User Management</span>
            </a>
        <?php endif; ?>
    </nav>

    <?php if ($agreaName !== ""): ?>
        <div class="sidebar-user">
            <span class="avatar"><?= htmlspecialchars(strtoupper(substr($agreaName, 0, 1)), ENT_QUOTES, "UTF-8") ?></span>
            <div>
                <strong><?= htmlspecialchars($agreaName, ENT_QUOTES, "UTF-8") ?></strong>
                <span><?= htmlspecialchars($agreaRole, ENT_QUOTES, "UTF-8") ?></span>
            </div>
        </div>
    <?php endif; ?>

    <a class="sidebar-logout" href="index.php?action=logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        <span>Logout</span>
    </a>
</aside>
