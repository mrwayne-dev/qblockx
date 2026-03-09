<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../api/utilities/auth-check.php';
requireAdmin();
$user = getAuthUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - arqoracapital</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/admin/styles.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>arqoracapital</h3>
        <nav>
            <a href="dashboard.php">Dashboard</a>
        </nav>
    </aside>
    <div class="admin-main">
        <header class="admin-header">
            <span>Welcome, <?php echo htmlspecialchars($user['email']); ?></span>
        </header>
        <h1>Admin Dashboard</h1>
        <div id="stats"></div>
    </div>
</div>
<script>
fetch('/api/admin-dashboard/dashboard.php')
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('stats').innerHTML =
                '<div class="stat-card"><h3>Total Users</h3><p>' + data.data.total_users + '</p></div>';
        }
    });
</script>
</body>
</html>
