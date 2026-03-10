/**
 * ArqoraCapital — Contact form handler
 */

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm') || document.getElementById('supportForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const msg = document.getElementById('contact-msg') || document.getElementById('msg');
        btn.disabled    = true;
        btn.textContent = 'Sending...';

        const data = Object.fromEntries(new FormData(form));
        try {
            const res    = await fetch('/api/utilities/contact.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });
            const result = await res.json();

            if (msg) {
                msg.textContent   = result.message;
                msg.className     = result.success ? 'success' : 'error';
                msg.style.display = 'block';
            }
            if (result.success) form.reset();
        } catch (err) {
            if (msg) {
                msg.textContent   = 'Something went wrong. Please try again.';
                msg.className     = 'error';
                msg.style.display = 'block';
            }
        } finally {
            btn.disabled    = false;
            btn.textContent = 'Send Message';
        }
    });
});
