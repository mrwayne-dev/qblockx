<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - arqoracapital</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <form id="resetForm">
            <input type="password" name="password" placeholder="New password" minlength="8" required><br><br>
            <input type="password" name="confirm" placeholder="Confirm password" required><br><br>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <p id="msg"></p>
    </div>
    <script>
    const token = new URLSearchParams(window.location.search).get('token');
    document.getElementById('resetForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        if (fd.get('password') !== fd.get('confirm')) {
            document.getElementById('msg').textContent = 'Passwords do not match';
            return;
        }
        const res = await fetch('/api/auth/reset-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token, password: fd.get('password') })
        });
        const result = await res.json();
        document.getElementById('msg').textContent = result.message;
        if (result.success) setTimeout(() => window.location.href = '/pages/public/login.php', 2000);
    });
    </script>
</body>
</html>