/**
 * CrestVale Bank — Auth API helpers
 * Reusable auth functions for login, register, logout across pages
 */

const AuthAPI = (() => {
    async function login(email, password) {
        const res = await fetch('/api/auth/user-login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });
        return res.json();
    }

    async function register(email, password, full_name, ref_code = '', currency = 'USD') {
        const res = await fetch('/api/auth/user-register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password, full_name, ref_code, currency }),
        });
        return res.json();
    }

    async function logout() {
        await fetch('/api/auth/user-logout.php', { method: 'POST' });
        window.location.href = '/login';
    }

    async function forgotPassword(email) {
        const res = await fetch('/api/auth/user-forgot-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email }),
        });
        return res.json();
    }

    async function resetPassword(token, password) {
        const res = await fetch('/api/auth/user-reset-password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token, password }),
        });
        return res.json();
    }

    // Attach form handlers when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        // Login form
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = loginForm.querySelector('button[type="submit"]');
                const msg = document.getElementById('login-msg');
                btn.disabled = true;
                const data = Object.fromEntries(new FormData(loginForm));
                const result = await login(data.email, data.password);
                btn.disabled = false;
                if (result.success) {
                    window.location.href = '/dashboard';
                } else if (msg) {
                    msg.textContent   = result.message;
                    msg.className     = 'error';
                    msg.style.display = 'block';
                }
            });
        }

        // Register form
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn     = registerForm.querySelector('button[type="submit"]');
                const btnText = btn.querySelector('.btn-text');
                const spinner = btn.querySelector('.btn-spinner');
                const msg     = document.getElementById('authMsg');
                const refCode = new URLSearchParams(window.location.search).get('ref') || '';
                btn.disabled = true;
                if (btnText) btnText.style.display = 'none';
                if (spinner) spinner.style.display = '';
                const data   = Object.fromEntries(new FormData(registerForm));
                const result = await register(data.email, data.password, data.full_name || '', refCode, data.currency || 'USD');
                btn.disabled = false;
                if (btnText) btnText.style.display = '';
                if (spinner) spinner.style.display = 'none';
                if (result.success) {
                    window.location.href = '/login';
                } else if (msg) {
                    msg.textContent = result.message;
                    msg.className   = 'auth-msg auth-msg--error';
                    msg.style.display = 'block';
                }
            });
        }

        // Forgot password form
        const forgotForm = document.getElementById('forgotForm');
        if (forgotForm) {
            forgotForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const msg    = document.getElementById('msg');
                const data   = Object.fromEntries(new FormData(forgotForm));
                const result = await forgotPassword(data.email);
                if (msg) {
                    msg.textContent   = result.message;
                    msg.className     = result.success ? 'success' : 'error';
                    msg.style.display = 'block';
                }
            });
        }

        // Reset password form
        const resetForm = document.getElementById('resetForm');
        if (resetForm) {
            resetForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const msg    = document.getElementById('msg');
                const fd     = new FormData(resetForm);
                const token  = new URLSearchParams(window.location.search).get('token');
                if (fd.get('password') !== fd.get('confirm')) {
                    if (msg) { msg.textContent = 'Passwords do not match'; msg.className = 'error'; msg.style.display = 'block'; }
                    return;
                }
                const result = await resetPassword(token, fd.get('password'));
                if (msg) {
                    msg.textContent   = result.message;
                    msg.className     = result.success ? 'success' : 'error';
                    msg.style.display = 'block';
                }
                if (result.success) setTimeout(() => window.location.href = '/login', 2000);
            });
        }

        // Logout buttons
        document.querySelectorAll('[data-logout]').forEach(btn => {
            btn.addEventListener('click', logout);
        });
    });

    return { login, register, logout, forgotPassword, resetPassword };
})();
