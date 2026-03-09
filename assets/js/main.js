document.addEventListener('DOMContentLoaded', () => {
    // ✅ Google Translate Initialization
    window.googleTranslateElementInit = function () {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'af,am,ar,az,be,bg,bn,bs,ca,cs,cy,da,de,el,en,es,et,fa,fi,fr,ga,gl,gu,ha,he,hi,hr,ht,hu,hy,id,is,it,ja,jw,ka,kk,km,kn,ko,la,lo,lt,lv,mk,ml,mn,mr,ms,mt,my,ne,nl,no,pa,pl,pt,ro,ru,sd,si,sk,sl,so,sq,sr,sv,sw,ta,te,th,tl,tr,uk,ur,uz,vi,yi,yo,zh-CN,zh-TW,zu',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google-translate-element');
    };

    function loadGoogleTranslate() {
        if (document.getElementById('google-translate-element')) {
            const script = document.createElement('script');
            script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            script.async = true;
            document.body.appendChild(script);
        }
    }

    // ✅ Smartsupp Live Chat
    function loadSmartsupp() {
        if (window._smartsupp) return;
        window._smartsupp = { key: 'c2ede9e40874edde3bc568b69597a5312f60694f' };
        const s = document.createElement('script');
        s.src = 'https://www.smartsuppchat.com/loader.js?';
        s.async = true;
        document.body.appendChild(s);
    }

    // ✅ Navbar Toggle
    function toggleNavbar() {
        const navbar = document.querySelector('.mobile-navbar');
        const toggleBtn = document.querySelector('[data-nav-toggler]');
        if (navbar && toggleBtn) {
            navbar.classList.toggle('active');
            toggleBtn.classList.toggle('active');
        }
    }

    // ✅ Appear On Scroll
    function initAppearOnScroll() {
        const elements = document.querySelectorAll('[data-appear]');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('appear');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });
        elements.forEach(el => observer.observe(el));
    }

    // ✅ Crypto Price Updates
    async function updateCryptoPrices() {
        const coins = ['bitcoin', 'ethereum', 'binancecoin', 'tether'];
        const ids = coins.join('%2C');
        const url = `https://api.coingecko.com/api/v3/simple/price?ids=${ids}&vs_currencies=usd`;

        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('API Error');
            const prices = await res.json();

            const map = {
                bitcoin: 'btc',
                ethereum: 'eth',
                ripple: 'xrp',
                cardano: 'ada',
                litecoin: 'ltc',
                polkadot: 'dot'
            };

            Object.entries(map).forEach(([coin, id]) => {
                const price = prices[coin]?.usd;
                const el = document.getElementById(`${id}-price`);
                if (el) el.textContent = price ? `$${price.toFixed(2)}` : '$0.00';
            });
        } catch (err) {
            console.error('Failed to fetch crypto prices:', err);
            ['btc', 'eth', 'xrp', 'ada', 'ltc', 'dot'].forEach(id => {
                const el = document.getElementById(`${id}-price`);
                if (el) el.textContent = '$0.00 (Updating)';
            });
        }
    }

    // ✅ Form Toggle
    function toggleSignUpLogin() {
        document.getElementById('signup-form')?.classList.toggle('hidden');
        document.getElementById('login-form')?.classList.toggle('hidden');
    }

    // ✅ Button Redirects
    function setupButtonRedirects() {
        document.getElementById('openacct-btn')?.addEventListener('click', () => location.href = 'auth.html');
        document.getElementById('support-btn')?.addEventListener('click', () => location.href = 'contact.html');
    }

    // ✅ Initialization
    loadGoogleTranslate();
    loadSmartsupp();
    initAppearOnScroll();
    updateCryptoPrices();
    setupButtonRedirects();

    // ✅ Nav toggle
    document.querySelector('[data-nav-toggler]')?.addEventListener('click', toggleNavbar);

    // ✅ Form switching
    document.querySelectorAll('.toggle-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            toggleSignUpLogin();
        });
    });

    // ✅ Mobile menu closes on click
    document.querySelectorAll('.navbar-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 899) toggleNavbar();
        });
    });

    // ✅ Refresh prices every 60s
    setInterval(updateCryptoPrices, 60000);
});
