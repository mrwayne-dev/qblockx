<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - arqoracapital</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form id="forgotForm">
            <input type="email" name="email" placeholder="Your email" required><br><br>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
        <p id="msg"></p>
    </div>
    <script>
    document.getElementById('forgotForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target));
        const res  = await fetch('/api/auth/forgot-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        document.getElementById('msg').textContent = result.message;
    });
    </script>
</body>
</html>