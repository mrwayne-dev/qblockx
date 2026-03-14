<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password - CrestVale Bank</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="main-content">
        <div class="auth-container">
            <div class="form-box">
                <h4>Reset Admin Password</h4>
                <form id="forgotForm">
                    <div class="input-container">
                        <input type="email" name="email" placeholder="Admin email" required>
                    </div>
                    <button type="submit" class="form-button">Send Reset Link</button>
                    <p id="msg" style="display:none; margin-top:1rem;"></p>
                </form>
                <p><a href="/admin/login" class="forgot-password">Back to admin login</a></p>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('forgotForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('msg');
        const data = Object.fromEntries(new FormData(e.target));
        const res  = await fetch('/api/auth/admin-forgot-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        msg.textContent = result.message;
        msg.className   = result.success ? 'success' : 'error';
        msg.style.display = 'block';
    });
    </script>
</body>
</html>
