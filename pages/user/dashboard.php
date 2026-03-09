<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../api/utilities/auth-check.php';
requireAuth();
$user = getAuthUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - arqoracapital</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['email']); ?>!</p>
        <button onclick="logout()" class="btn btn-primary">Logout</button>
    </div>
    <script>
    async function logout() {
        await fetch('/api/auth/logout.php');
        window.location.href = '/pages/public/login.php';
    }
    </script>
</body>
</html>
