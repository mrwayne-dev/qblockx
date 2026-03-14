<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset Password - CrestVale Bank</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <div class="main-content">
        <div class="auth-container">
            <div class="form-box">
                <h4>Set New Admin Password</h4>
                <form id="resetForm">
                    <div class="input-container">
                        <input type="password" name="password" id="password" placeholder="New password" minlength="8" required>
                        <span class="uil showHidePw" onclick="togglePw()">&#128065;</span>
                    </div>
                    <div class="input-container">
                        <input type="password" name="confirm" placeholder="Confirm password" minlength="8" required>
                    </div>
                    <button type="submit" class="form-button">Reset Password</button>
                    <p id="msg" style="display:none; margin-top:1rem;"></p>
                </form>
            </div>
        </div>
    </div>
    <script>
    function togglePw() {
        const pw = document.getElementById('password');
        pw.type = pw.type === 'password' ? 'text' : 'password';
    }
    const token = new URLSearchParams(window.location.search).get('token');
    document.getElementById('resetForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('msg');
        const fd  = new FormData(e.target);
        if (fd.get('password') !== fd.get('confirm')) {
            msg.textContent = 'Passwords do not match';
            msg.className   = 'error';
            msg.style.display = 'block';
            return;
        }
        const res = await fetch('/api/auth/admin-reset-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token, password: fd.get('password') })
        });
        const result = await res.json();
        msg.textContent = result.message;
        msg.className   = result.success ? 'success' : 'error';
        msg.style.display = 'block';
        if (result.success) setTimeout(() => window.location.href = '/pages/admin/login.php', 2000);
    });
    </script>
</body>
</html>
