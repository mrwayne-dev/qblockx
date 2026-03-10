<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ArqoraCapital</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="main-content">
        <div class="auth-container">
            <div class="img"><img src="../../assets/images/logo.png" alt="ArqoraCapital" onerror="this.style.display='none'"></div>
            <div class="form-box">
                <h4>Admin Portal</h4>
                <form id="adminLoginForm">
                    <div class="input-container">
                        <input type="email" name="email" placeholder="Admin email" required>
                    </div>
                    <div class="input-container">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                        <span class="uil showHidePw" onclick="togglePw()">&#128065;</span>
                    </div>
                    <button type="submit" class="form-button">Sign In</button>
                    <p id="msg" class="error" style="display:none;"></p>
                </form>
                <p><a href="forgot-password.php" class="forgot-password">Forgot password?</a></p>
            </div>
        </div>
    </div>
    <script>
    function togglePw() {
        const pw = document.getElementById('password');
        pw.type = pw.type === 'password' ? 'text' : 'password';
    }
    document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('msg');
        msg.style.display = 'none';
        const data = Object.fromEntries(new FormData(e.target));
        const res  = await fetch('/api/auth/admin-login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        if (result.success) {
            window.location.href = '/pages/admin/dashboard.php';
        } else {
            msg.textContent = result.message;
            msg.style.display = 'block';
        }
    });
    </script>
</body>
</html>
