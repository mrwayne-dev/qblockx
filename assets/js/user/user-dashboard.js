(function () {
  'use strict';

  // ── Currency ──────────────────────────────────────────────────────────────────

  var _currencySymbols = {
    // Major Global
    USD:'$',    EUR:'€',    GBP:'£',    JPY:'¥',    CHF:'Fr',
    // Americas
    AUD:'A$',   CAD:'C$',   NZD:'NZ$',  BRL:'R$',   MXN:'MX$',
    COP:'Col$', ARS:'AR$',  CLP:'CL$',  PEN:'S/',   UYU:'$U',
    DOP:'RD$',  TTD:'TT$',  JMD:'J$',
    // Europe
    SEK:'kr',   NOK:'kr',   DKK:'kr',   PLN:'zł',   CZK:'Kč',
    HUF:'Ft',   RON:'lei',  BGN:'лв',   HRK:'kn',   RSD:'din',
    UAH:'₴',    RUB:'₽',    TRY:'₺',    ISK:'kr',
    // Asia & Pacific
    CNY:'¥',    INR:'₹',    SGD:'S$',   HKD:'HK$',  KRW:'₩',
    TWD:'NT$',  IDR:'Rp',   PHP:'₱',    THB:'฿',    MYR:'RM',
    VND:'₫',    BDT:'৳',    PKR:'₨',    LKR:'₨',    NPR:'₨',
    MMK:'K',    KHR:'៛',
    // Middle East
    AED:'د.إ',  SAR:'﷼',    QAR:'﷼',    KWD:'KD',   BHD:'BD',
    OMR:'﷼',    JOD:'JD',   ILS:'₪',    IQD:'ع.د',  IRR:'﷼',
    // Africa
    NGN:'₦',    ZAR:'R',    KES:'KSh',  GHS:'₵',    EGP:'£E',
    MAD:'MAD',  TZS:'TSh',  UGX:'USh',  ETB:'Br',   DZD:'دج',
    TND:'DT',   XOF:'CFA',  XAF:'FCFA', RWF:'RF',   ZMW:'ZK',
    MZN:'MT',   BWP:'P'
  };
  var _currencySymbol = '$';
  var _currencyCode   = 'USD';

  function initCurrency(code) {
    _currencyCode   = (code || 'USD').toUpperCase();
    _currencySymbol = _currencySymbols[_currencyCode] || (_currencyCode + '\u00a0');
    try { localStorage.setItem('cv_currency', _currencyCode); } catch(e) {}
    document.querySelectorAll('.js-currency-code').forEach(function(el) {
      el.textContent = _currencyCode;
    });
    document.querySelectorAll('.js-currency-sym').forEach(function(el) {
      el.textContent = _currencySymbol;
    });
  }

  // ── Helpers ──────────────────────────────────────────────────────────────────

  async function apiFetch(url, opts) {
    opts = opts || {};
    const res = await fetch(url, Object.assign({
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    }, opts));
    return res.json();
  }

  function fmt(num) {
    return parseFloat(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function fmtDate(dt) {
    if (!dt) return '--';
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  var _txTypeLabels = {
    deposit:                 'Deposit',
    withdrawal:              'Withdrawal',
    transfer:                'Transfer',
    savings_contribution:    'Savings',
    savings_withdrawal:      'Savings Out',
    deposit_return:          'Deposit Return',
    loan_disbursement:       'Loan In',
    loan_repayment:          'Loan Repayment',
    interest_credit:         'Interest',
    investment:              'Investment',
    commodity_investment:    'Commodities',
    realestate_investment:   'Real Estate',
    admin_credit:            'Qblockx Fund',
    admin_debit:             'Admin Debit',
  };

  function txTypeBadge(type) {
    var label = _txTypeLabels[type] || (type || '—').replace(/_/g, ' ');
    return '<span class="badge badge-tx-' + (type || 'muted') + '">' + label + '</span>';
  }

  // Works on HTTP (no secure context) and HTTPS
  function copyText(text, cb) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(function () { cb(true); }).catch(function () { cb(false); });
    } else {
      try {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        var ok = document.execCommand('copy');
        document.body.removeChild(ta);
        cb(ok);
      } catch (e) { cb(false); }
    }
  }

  function badge(status) {
    var map = {
      completed: 'badge-success',
      active:    'badge-success',
      approved:  'badge-success',
      pending:   'badge-warning',
      rejected:  'badge-error',
      failed:    'badge-error',
      cancelled: 'badge-muted'
    };
    return '<span class="badge ' + (map[status] || 'badge-muted') + '">' + status + '</span>';
  }

  function showMsg(el, msg, isError) {
    if (!el) return;
    el.textContent = msg;
    el.className = isError
      ? 'form-message form-message--error'
      : 'form-message form-message--success';
    el.style.display = 'block';
    setTimeout(function () { el.style.display = 'none'; }, 5000);
  }

  function qs(sel) { return document.querySelector(sel); }
  function setText(sel, val) { var el = qs(sel); if (el) el.textContent = val; }
  function setHTML(sel, val) { var el = qs(sel); if (el) el.innerHTML  = val; }
  function setVal(sel, val)  { var el = qs(sel); if (el) el.value = val || ''; }

  // Phosphor percent icon — used wherever % would appear in rendered text
  var pctIcon = '<i class="ph ph-percent" aria-label="%"></i>';

  // ── Modal System ─────────────────────────────────────────────────────────────

  function openModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Populate balance bar in all invest modals
    var balFmt = _currencySymbol + fmt(_lastBalance);
    if (id === 'modal-invest-plan') {
      // Reset select-based form state
      var sel = document.getElementById('investPlanSelect');
      if (sel) sel.value = '';
      var info = document.getElementById('investPlanInfo');
      var amtGrp = document.getElementById('investPlanAmountGroup');
      var btn = document.getElementById('investPlanBtn');
      var msg = document.getElementById('investPlanMsg');
      if (info)   info.style.display   = 'none';
      if (amtGrp) amtGrp.style.display = 'none';
      if (btn)    btn.disabled         = true;
      if (msg)    msg.style.display    = 'none';
      var balEl = document.getElementById('investPlanBalance');
      if (balEl) balEl.textContent = balFmt;
      // Load plans from cache or fetch
      if (window._cachedInvestmentPlans && window._cachedInvestmentPlans.length) {
        populateInvestPlanSelect(window._cachedInvestmentPlans);
      } else {
        loadInvestmentsForModal();
      }
    } else if (id === 'modal-invest-commodity') {
      var b = document.getElementById('commodityBalance');
      if (b) b.textContent = balFmt;
    } else if (id === 'modal-invest-realestate') {
      var b2 = document.getElementById('reBalance');
      if (b2) b2.textContent = balFmt;
    }
  }

  async function loadInvestmentsForModal() {
    var sel = document.getElementById('investPlanSelect');
    if (sel) {
      sel.innerHTML = '<option value="">Loading plans…</option>';
      sel.disabled = true;
    }
    try {
      var r = await apiFetch('/api/user-dashboard/investments.php');
      if (r.success) {
        window._cachedInvestmentPlans = r.data.plans || [];
        populateInvestPlanSelect(window._cachedInvestmentPlans);
        renderInvPlansPreview(window._cachedInvestmentPlans);
      } else {
        if (sel) sel.innerHTML = '<option value="">Failed to load plans</option>';
      }
    } catch (e) {
      if (sel) sel.innerHTML = '<option value="">Network error — try again</option>';
    } finally {
      if (sel) sel.disabled = false;
    }
  }

  function closeModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('active');
    // Restore scroll only if no other modals are open
    if (!document.querySelector('.modal-overlay.active')) {
      document.body.style.overflow = '';
    }
  }

  function closeAllModals() {
    document.querySelectorAll('.modal-overlay.active').forEach(function (el) {
      el.classList.remove('active');
    });
    document.body.style.overflow = '';
  }

  // Expose modal functions globally so inline onclick="" handlers work
  window.openModal      = openModal;
  window.closeModal     = closeModal;
  window.closeAllModals = closeAllModals;


  // ── Toast System ─────────────────────────────────────────────────────────────

  function showToast(msg, type) {
    var c = document.getElementById('toastContainer');
    if (!c) return;
    var t = document.createElement('div');
    t.className = 'toast toast--' + (type || 'info');
    t.innerHTML = '<span class="toast-msg">' + msg + '</span>'
      + '<button class="toast-close" type="button" aria-label="Close notification">'
      + '<i class="ph ph-x"></i></button>';
    t.querySelector('.toast-close').onclick = function () { t.remove(); };
    c.appendChild(t);
    setTimeout(function () { if (t.parentNode) t.remove(); }, 4000);
  }

  window.showToast = showToast;

  // ── Global Loader ────────────────────────────────────────────────────────────

  function showLoader() {
    var l = document.getElementById('globalLoader');
    if (l) l.classList.add('active');
  }

  function hideLoader() {
    var l = document.getElementById('globalLoader');
    if (l) l.classList.remove('active');
  }

  window.showLoader = showLoader;
  window.hideLoader = hideLoader;

  // ── Module State ─────────────────────────────────────────────────────────────

  var _lastBalance      = 0;  // cache for balance-hide toggle
  var _rates            = []; // cache for product rates
  var _ratesFilter      = 'savings'; // active rates tab

  // ── Background Refresh ───────────────────────────────────────────────────────
  //
  // Two-layer silent refresh — no loading states, no spinners, no flicker:
  //   • Core timer  (30 s) — updates stats + balance across all sections
  //   • Section timer (60 s) — re-renders the current section's table data
  //
  // Both timers restart whenever the user navigates to a different section.

  var _coreTimer    = null;
  var _sectionTimer = null;
  var _activeSection = 'overview';

  // Silently refresh global stats + rates from the dashboard endpoint.
  // Called every 30 s regardless of which section is visible.
  async function refreshCore() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      // Balance (overview stat card + wallet hero)
      if (d.currency) initCurrency(d.currency);
      var balStr = _currencySymbol + fmt(d.balance);
      setText('[data-stat="balance"]',          balStr);
      setText('[data-wallet="balance"]',        balStr);
      _lastBalance = parseFloat(d.balance || 0);
      applyBalanceHidden(localStorage.getItem('balanceHidden') === '1');

      // Recent transactions (overview table only — not wallet history)
      var tbody = qs('[data-table="recent-transactions"]');
      if (tbody && d.recent_transactions) {
        tbody.innerHTML = d.recent_transactions.length
          ? d.recent_transactions.map(function (tx) {
              return '<tr>'
                + '<td>' + txTypeBadge(tx.type) + '</td>'
                + '<td>' + _currencySymbol + fmt(tx.amount) + '</td>'
                + '<td>' + badge(tx.status) + '</td>'
                + '<td>' + fmtDate(tx.created_at) + '</td>'
                + '</tr>';
            }).join('')
          : '<tr><td colspan="4" class="empty-row">No transactions yet</td></tr>';
      }

      // Aggregate investment stat cards
      setText('[data-stat="inv-total-invested"]', _currencySymbol + fmt(d.total_invested_all || 0));
      setText('[data-stat="inv-active-count"]',   d.active_count_all || 0);
      setText('[data-stat="inv-total-returned"]', _currencySymbol + fmt(d.total_expected_all || 0));

      // Update rates cache & re-render if data changed
      if (d.rates && d.rates.length) {
        _rates = d.rates;
        renderRates(_rates, _ratesFilter);
        populateProductSelects(_rates);
      }
      // Refresh market prices silently
      loadMarketPrices();
    } catch (e) { /* silent — network blips should not surface to the user */ }
  }

  // Silently refresh the current section's detailed table/list data.
  // Only runs when the user is already looking at that section.
  function refreshSection() {
    var loader = sectionLoaders[_activeSection];
    if (loader) loader();
  }

  function startBackgroundRefresh(sectionName) {
    _activeSection = sectionName;

    // Core stats — every 30 s
    clearInterval(_coreTimer);
    _coreTimer = setInterval(refreshCore, 30000);

    // Section detail — every 60 s
    clearInterval(_sectionTimer);
    _sectionTimer = setInterval(refreshSection, 60000);
  }

  // ── Dashboard Overview ───────────────────────────────────────────────────────

  async function loadDashboard() {
    try {
      var r = await apiFetch('/api/user-dashboard/dashboard.php');
      if (!r.success) return;
      var d = r.data;

      if (d.currency) initCurrency(d.currency);
      setText('[data-stat="balance"]',           _currencySymbol + fmt(d.balance));
      _lastBalance = parseFloat(d.balance || 0);
      applyBalanceHidden(localStorage.getItem('balanceHidden') === '1');

      if (d.user) {
        var displayName = d.user.full_name || d.user.email || '';
        document.querySelectorAll('[data-user="name"]').forEach(function (el) {
          el.textContent = displayName;
        });
        // Set avatar initials
        var initial = displayName.trim().charAt(0).toUpperCase() || 'U';
        document.querySelectorAll('[data-user="initial"]').forEach(function (el) {
          el.textContent = initial;
        });
      }

      // Cache and render rates
      if (d.rates && d.rates.length) {
        _rates = d.rates;
        renderRates(_rates, _ratesFilter);
        populateProductSelects(_rates);
      }

      var tbody = qs('[data-table="recent-transactions"]');
      if (tbody) {
        if (d.recent_transactions && d.recent_transactions.length) {
          tbody.innerHTML = d.recent_transactions.map(function (tx) {
            return '<tr>'
              + '<td>' + txTypeBadge(tx.type) + '</td>'
              + '<td>' + _currencySymbol + fmt(tx.amount) + '</td>'
              + '<td>' + badge(tx.status) + '</td>'
              + '<td>' + fmtDate(tx.created_at) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="4" class="empty-row">No transactions yet</td></tr>';
        }
      }
      // Aggregate investment stat cards (all 3 investment types)
      setText('[data-stat="inv-total-invested"]', _currencySymbol + fmt(d.total_invested_all || 0));
      setText('[data-stat="inv-active-count"]',   d.active_count_all || 0);
      setText('[data-stat="inv-total-returned"]', _currencySymbol + fmt(d.total_expected_all || 0));

    } catch (e) {
      console.error('loadDashboard:', e);
    }

    // Populate profile fields used in wallet header card on first load
    loadProfile();

    // Live market prices ticker
    loadMarketPrices();

    // Portfolio allocation chart
    renderPortfolioChart();
  }

  // ── Rates ──────────────────────────────────────────────────────────────────────

  // Modal open action for pricing plan CTA buttons
  var _productModalMap = {
    savings:       'modal-create-savings',
    fixed_deposit: 'modal-fixed-deposit',
    loan:          'modal-loan'
  };

  function renderRates(rates, filter) {
    var grid = document.getElementById('ratesGrid');
    if (!grid) return;
    var filtered = rates.filter(function (r) { return r.product === filter; });
    if (!filtered.length) {
      grid.innerHTML = '<p class="empty-text">No rates configured for this product.</p>';
      return;
    }
    // Feature the 12-month plan (or the middle plan if none is 12mo)
    var featuredIdx = filtered.findIndex(function (r) { return parseInt(r.duration_months, 10) === 12; });
    if (featuredIdx === -1) featuredIdx = Math.floor(filtered.length / 2);

    var ctaLabels = { savings: 'Start Saving', fixed_deposit: 'Open Deposit', loan: 'Apply Now' };
    var cta = ctaLabels[filter] || 'Get Started';
    var modalId = _productModalMap[filter] || '';

    grid.innerHTML = filtered.map(function (r, i) {
      var featured = i === featuredIdx;
      var cardClass = 'plan-card' + (featured ? ' plan-card--featured' : '');
      var badge = featured ? '<span class="plan-badge">Popular</span>' : '';
      return '<div class="' + cardClass + '">'
        + badge
        + '<div class="plan-card-name">' + r.label + '</div>'
        + '<div class="plan-card-duration">' + r.duration_months + ' months</div>'
        + '<div class="plan-card-rate">' + parseFloat(r.rate).toFixed(2)
          + '<span><i class="ph ph-percent" aria-label="%"></i>&thinsp;p.a.</span></div>'
        + (modalId
          ? '<button class="plan-card-btn" type="button" onclick="openModal(\'' + modalId + '\')">' + cta + '</button>'
          : '')
        + '</div>';
    }).join('');
  }

  function populateProductSelects(rates) {
    // Savings modal duration select
    var savSel = document.getElementById('savingsDuration');
    if (savSel) {
      var savRates = rates.filter(function (r) { return r.product === 'savings'; });
      if (savRates.length) {
        savSel.innerHTML = savRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateSavingsCalc();
      }
    }

    // Fixed deposit modal plan select
    var fdSel = document.getElementById('fdPlan');
    if (fdSel) {
      var fdRates = rates.filter(function (r) { return r.product === 'fixed_deposit'; });
      if (fdRates.length) {
        fdSel.innerHTML = fdRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateFdCalc();
      }
    }

    // Loan modal plan select
    var loanSel = document.getElementById('loanPlan');
    if (loanSel) {
      var loanRates = rates.filter(function (r) { return r.product === 'loan'; });
      if (loanRates.length) {
        loanSel.innerHTML = loanRates.map(function (r) {
          return '<option value="' + r.duration_months + '" data-rate="' + r.rate + '">'
            + r.label + ' — ' + r.duration_months + 'mo (' + parseFloat(r.rate).toFixed(2) + '% p.a.)'
            + '</option>';
        }).join('');
        updateLoanCalc();
      }
    }
  }

  // kept for backward compat — no longer used as standalone but harmless
  function populateSavingsDurationSelect(rates) { populateProductSelects(rates); }

  // ── Live Calculation Helpers ──────────────────────────────────────────────────

  function getSelectedRate(selectId) {
    var sel = document.getElementById(selectId);
    if (!sel) return { rate: 0, months: 0 };
    var opt = sel.options[sel.selectedIndex];
    if (!opt) return { rate: 0, months: 0 };
    return {
      rate:   parseFloat(opt.getAttribute('data-rate') || 0),
      months: parseInt(opt.value, 10) || 0
    };
  }

  function addMonths(date, n) {
    var d = new Date(date);
    d.setMonth(d.getMonth() + n);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  }

  function updateSavingsCalc() {
    var preview  = document.getElementById('savingsCalcPreview');
    if (!preview) return;
    var amount   = parseFloat((document.getElementById('savingsTargetAmount') || {}).value) || 0;
    var plan     = getSelectedRate('savingsDuration');
    // Store rate in hidden field
    var hiddenEl = document.getElementById('savingsInterestRate');
    if (hiddenEl) hiddenEl.value = plan.rate || '';

    if (!amount || !plan.rate) { preview.style.display = 'none'; return; }
    var interest = amount * (plan.rate / 100) * (plan.months / 12);
    var total    = amount + interest;
    preview.style.display = 'block';
    setText('#savingsCalcPrincipal', _currencySymbol + fmt(amount));
    setHTML('#savingsCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#savingsCalcDuration',  plan.months + ' months');
    setText('#savingsCalcInterest',  '+' + _currencySymbol + fmt(interest));
    setText('#savingsCalcTotal',     _currencySymbol + fmt(total));
  }

  function updateFdCalc() {
    var preview = document.getElementById('fdCalcPreview');
    if (!preview) return;
    var amount  = parseFloat((document.getElementById('fdAmount') || {}).value) || 0;
    var plan    = getSelectedRate('fdPlan');

    if (!amount || !plan.rate) { preview.style.display = 'none'; return; }
    var interest = amount * (plan.rate / 100) * (plan.months / 12);
    var total    = amount + interest;
    preview.style.display = 'block';
    setText('#fdCalcPrincipal', _currencySymbol + fmt(amount));
    setText('#fdCalcInterest',  '+' + _currencySymbol + fmt(interest));
    setText('#fdCalcDuration',  plan.months + ' months');
    setHTML('#fdCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#fdCalcTotal',     _currencySymbol + fmt(total));
    setText('#fdCalcMaturity',  addMonths(new Date(), plan.months));
  }

  function updateLoanCalc() {
    var preview = document.getElementById('loanCalcPreview');
    if (!preview) return;
    var principal = parseFloat((document.getElementById('loanAmountInput') || {}).value) || 0;
    var plan      = getSelectedRate('loanPlan');

    if (!principal || !plan.rate) { preview.style.display = 'none'; return; }
    var monthlyRate = plan.rate / 100 / 12;
    var n           = plan.months;
    var monthly, totalRepayable, totalInterest;
    if (monthlyRate === 0) {
      monthly       = principal / n;
      totalRepayable = principal;
      totalInterest  = 0;
    } else {
      monthly       = principal * (monthlyRate * Math.pow(1 + monthlyRate, n)) / (Math.pow(1 + monthlyRate, n) - 1);
      totalRepayable = monthly * n;
      totalInterest  = totalRepayable - principal;
    }
    preview.style.display = 'block';
    setText('#loanCalcPrincipal', _currencySymbol + fmt(principal));
    setText('#loanCalcMonthly',   _currencySymbol + fmt(monthly));
    setText('#loanCalcDuration',  n + ' months');
    setHTML('#loanCalcRate',      parseFloat(plan.rate).toFixed(2) + pctIcon + '&thinsp;p.a.');
    setText('#loanCalcInterest',  _currencySymbol + fmt(totalInterest));
    setText('#loanCalcTotal',     _currencySymbol + fmt(totalRepayable));
  }

  // ── NOWPayments Auto-Verify ───────────────────────────────────────────────────

  var _paymentPollCount  = 0;
  var _paymentPollMax    = 8;   // max 8 attempts (~40 seconds)
  var _paymentPollTimer  = null;

  async function checkPendingPayment(invoiceId, attempt) {
    attempt = attempt || 0;
    if (attempt >= _paymentPollMax) return;

    try {
      var r = await apiFetch('/api/payments/now-payment-status.php', {
        method: 'POST',
        body: JSON.stringify({ invoice_id: invoiceId })
      });

      if (r.success && r.status === 'completed') {
        try { sessionStorage.removeItem('np_invoice_id'); } catch(e) {}
        if (r.credited) {
          showToast('Payment confirmed! Your wallet has been credited.', 'success');
        }
        loadWallet();
        loadDashboard();
        return;
      }

      if (r.success && (r.status === 'failed')) {
        try { sessionStorage.removeItem('np_invoice_id'); } catch(e) {}
        showToast('Payment failed or expired. Please try again.', 'error');
        return;
      }

      // Still pending — retry with exponential back-off (5s, 5s, 10s, 10s …)
      var delay = attempt < 2 ? 5000 : 10000;
      _paymentPollTimer = setTimeout(function () {
        checkPendingPayment(invoiceId, attempt + 1);
      }, delay);

    } catch (e) {
      // Network error — try again once more
      if (attempt < 2) {
        _paymentPollTimer = setTimeout(function () {
          checkPendingPayment(invoiceId, attempt + 1);
        }, 5000);
      }
    }
  }

  // ── Wallet ────────────────────────────────────────────────────────────────────

  // Withdrawal fee cached from last wallet load
  var _withdrawalFee = 0;

  // Banks by country for bank transfer modal
  var _banksByCountry = {
    "United States":  ["JPMorgan Chase Bank","Bank of America","Wells Fargo","Citibank","U.S. Bank","Truist Bank","PNC Bank","Capital One","TD Bank","Goldman Sachs Bank USA","Morgan Stanley Bank","BMO Harris Bank","Fifth Third Bank","Citizens Bank","Ally Bank"],
    "Germany":        ["Deutsche Bank","Commerzbank","Postbank","HypoVereinsbank (UniCredit Bank AG)","DZ Bank","KfW (Kreditanstalt für Wiederaufbau)","Sparkasse","Volksbanken und Raiffeisenbanken","Berenberg Bank","Bankhaus Lampe"],
    "France":         ["BNP Paribas","Crédit Agricole","Société Générale","BPCE (Banque Populaire and Caisse d'Epargne)","Crédit Mutuel","La Banque Postale","HSBC France","CIC (Crédit Industriel et Commercial)"],
    "United Kingdom": ["HSBC","Barclays","Lloyds Banking Group","NatWest Group","Standard Chartered","Santander UK","Nationwide Building Society","TSB Bank"],
    "Italy":          ["UniCredit","Intesa Sanpaolo","Banco BPM","Monte dei Paschi di Siena","UBI Banca","Mediobanca","Banca Nazionale del Lavoro (BNL)","Cassa Depositi e Prestiti (CDP)"],
    "Spain":          ["Banco Santander","BBVA (Banco Bilbao Vizcaya Argentaria)","CaixaBank","Bankia","Sabadell","Bankinter","Kutxabank","Abanca"],
    "Netherlands":    ["ING Bank","Rabobank","ABN AMRO","De Nederlandsche Bank (DNB)","SNS Bank","F. van Lanschot Bankiers","Achmea Bank","KAS BANK"],
    "Sweden":         ["Nordea","SEB (Skandinaviska Enskilda Banken)","Swedbank","Handelsbanken","Länsförsäkringar Bank","SBAB Bank","Ikano Bank"],
    "Switzerland":    ["UBS","Credit Suisse","Raiffeisen Switzerland","Zürcher Kantonalbank (ZKB)","Julius Baer","Pictet & Cie","PostFinance","Banque Cantonale Vaudoise (BCV)"],
    "Poland":         ["PKO Bank Polski","Bank Pekao","Santander Bank Polska","mBank","ING Bank Śląski","Bank Millennium","Alior Bank","Getinormen Bank"],
    "Austria":        ["Erste Group Bank","Raiffeisen Bank International","BAWAG P.S.K.","UniCredit Bank Austria","Oberbank","Volksbank Wien","Sberbank Europe","Kathrein & Co. Privatgeschäftsbank"],
    "Greece":         ["National Bank of Greece","Piraeus Bank","Alpha Bank","Eurobank","Attica Bank","HSBC Greece","Pancreta Bank","Optima Bank"],
    "Portugal":       ["Caixa Geral de Depósitos","Millennium BCP (Banco Comercial Português)","Banco BPI","Novo Banco","Santander Totta","Montepio","Crédito Agrícola","Banco Mais"],
    "Norway":         ["DNB Bank","Nordea Bank Norge","SpareBank 1 Group","Handelsbanken Norge","Danske Bank Norway","Storebrand Bank","Santander Consumer Bank","Sparebanken Vest"],
    "Denmark":        ["Danske Bank","Nordea Bank Danmark","Jyske Bank","Sydbank","Nykredit Bank","Spar Nord Bank","Arbejdernes Landsbank","Saxo Bank"],
    "Belgium":        ["KBC Bank","BNP Paribas Fortis","ING Belgium","Belfius Bank","Argenta","Bank J. Van Breda en Co","AXA Bank Belgium","Crelan"],
    "Finland":        ["Nordea Bank Finland","OP Financial Group","Danske Bank Finland","S-Pankki (S-Bank)","Aktia Bank","Bank of Åland","Handelsbanken Finland","Oma Savings Bank"],
    "Ireland":        ["Bank of Ireland","Allied Irish Banks (AIB)","Ulster Bank","Permanent TSB","KBC Bank Ireland","Citibank Europe","Danske Bank Ireland","Bank of America Europe"],
    "Czech Republic": ["ČSOB (Československá Obchodní Banka)","Česká Spořitelna","Komerční Banka","UniCredit Bank Czech Republic","Raiffeisenbank Czech Republic","MONETA Money Bank","Air Bank","Fio Banka"],
    "Hungary":        ["OTP Bank","K&H Bank","Erste Bank Hungary","UniCredit Bank Hungary","Raiffeisen Bank Hungary","CIB Bank","MKB Bank","Budapest Bank"],
    "Ukraine":        ["PrivatBank","Oschadbank","Ukrgasbank","Raiffeisen Bank Aval","Ukrsibbank","Sense Bank","PUMB (First Ukrainian International Bank)","UkrEximBank"]
  };

  // ── Withdraw modal tab + bank dropdown logic ────────────────────────────────
  document.addEventListener('click', function (e) {
    var tab = e.target.closest('[data-withdraw-tab]');
    if (!tab) return;
    var method = tab.dataset.withdrawTab;

    // Update hidden input
    var form = document.querySelector('#modal-withdraw form[data-action="withdraw"]');
    if (form) {
      var methodInput = form.querySelector('[name="withdrawal_method"]');
      if (methodInput) methodInput.value = method;
    }

    // Toggle tab active class (CSS handles all styling)
    document.querySelectorAll('[data-withdraw-tab]').forEach(function (t) {
      t.classList.toggle('active', t.dataset.withdrawTab === method);
    });

    // Show/hide sections
    var cryptoSection = document.getElementById('withdrawCryptoSection');
    var bankSection   = document.getElementById('withdrawBankSection');
    if (cryptoSection) cryptoSection.style.display = method === 'crypto' ? '' : 'none';
    if (bankSection)   bankSection.style.display   = method === 'bank'   ? '' : 'none';
  });

  // Country → bank dropdown
  document.addEventListener('change', function (e) {
    var countrySelect = e.target.closest('#withdrawBankCountry');
    if (!countrySelect) return;
    var country   = countrySelect.value;
    var bankSelect = document.getElementById('withdrawBankName');
    if (!bankSelect) return;
    bankSelect.innerHTML = '';
    if (!country || !_banksByCountry[country]) {
      bankSelect.innerHTML = '<option value="">Select a country first</option>';
      bankSelect.disabled  = true;
      return;
    }
    var opts = '<option value="">Select a bank</option>';
    _banksByCountry[country].forEach(function (b) {
      opts += '<option value="' + b.replace(/"/g, '&quot;') + '">' + b + '</option>';
    });
    bankSelect.innerHTML = opts;
    bankSelect.disabled  = false;

    // Show/hide sort code for UK
    var sortGroup = document.getElementById('withdrawSortCodeGroup');
    if (sortGroup) sortGroup.style.display = country === 'United Kingdom' ? '' : 'none';
  });

  // Fee display on amount input
  document.addEventListener('input', function (e) {
    var amountInput = e.target.closest('#withdrawAmount');
    if (!amountInput) return;
    var feeNote = document.getElementById('withdrawFeeNote');
    if (!feeNote) return;
    var amt = parseFloat(amountInput.value) || 0;
    if (_withdrawalFee > 0 && amt > 0) {
      feeNote.style.display = '';
      feeNote.textContent   = 'Withdrawal fee: $' + fmt(_withdrawalFee)
        + ' · Total deducted from wallet: $' + fmt(amt + _withdrawalFee);
    } else if (_withdrawalFee > 0) {
      feeNote.style.display = '';
      feeNote.textContent   = 'Withdrawal fee: $' + fmt(_withdrawalFee);
    } else {
      feeNote.style.display = 'none';
    }
  });

  // ── Transaction Pagination ────────────────────────────────────────────────────
  var _allTransactions = [];
  var _txPage    = 1;
  var _txPerPage = 10;

  function renderTxPage() {
    var tbody = document.getElementById('txTableBody');
    if (!tbody) return;
    if (!_allTransactions.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="empty-row">No transactions yet</td></tr>';
      return;
    }
    var start = (_txPage - 1) * _txPerPage;
    var page  = _allTransactions.slice(start, start + _txPerPage);
    tbody.innerHTML = page.map(function (tx) {
      return '<tr>'
        + '<td>' + txTypeBadge(tx.type) + '</td>'
        + '<td>' + _currencySymbol + fmt(tx.amount) + '</td>'
        + '<td>' + badge(tx.status) + '</td>'
        + '<td>' + (tx.notes || '--') + '</td>'
        + '<td>' + fmtDate(tx.created_at) + '</td>'
        + '</tr>';
    }).join('');
  }

  function renderTxPagination() {
    var el = document.getElementById('txPagination');
    if (!el) return;
    var totalPages = Math.ceil(_allTransactions.length / _txPerPage);
    if (totalPages <= 1) { el.innerHTML = ''; return; }
    var html = '<div class="tx-pag-inner">';
    html += '<button class="tx-pag-btn" onclick="txGoPage(' + (_txPage - 1) + ')" '
      + (_txPage === 1 ? 'disabled' : '') + '>'
      + '<i class="ph ph-caret-left"></i></button>';
    for (var i = 1; i <= totalPages; i++) {
      html += '<button class="tx-pag-btn' + (i === _txPage ? ' tx-pag-btn--active' : '')
        + '" onclick="txGoPage(' + i + ')">' + i + '</button>';
    }
    html += '<button class="tx-pag-btn" onclick="txGoPage(' + (_txPage + 1) + ')" '
      + (_txPage === totalPages ? 'disabled' : '') + '>'
      + '<i class="ph ph-caret-right"></i></button>';
    html += '</div><span class="tx-pag-info">'
      + (_allTransactions.length) + ' transactions</span>';
    el.innerHTML = html;
  }

  window.txGoPage = function (page) {
    var totalPages = Math.ceil(_allTransactions.length / _txPerPage);
    if (page < 1 || page > totalPages) return;
    _txPage = page;
    renderTxPage();
    renderTxPagination();
  };

  window.exportTransactionsCSV = function () {
    if (!_allTransactions.length) return showToast('No transactions to export', 'info');
    var header = ['Type','Amount','Status','Description','Date'];
    var rows = _allTransactions.map(function (tx) {
      return [
        (tx.type || '').replace(/_/g, ' '),
        parseFloat(tx.amount || 0).toFixed(2),
        tx.status || '',
        (tx.notes || '').replace(/,/g, ' '),
        fmtDate(tx.created_at)
      ].join(',');
    });
    var csv  = [header.join(',')].concat(rows).join('\n');
    var blob = new Blob([csv], { type: 'text/csv' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a');
    a.href     = url;
    a.download = 'qblockx-transactions.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast('Transactions exported', 'success');
  };

  async function loadWallet() {
    // Populate wallet-info-card fields that come from profile data
    loadProfile();

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php');
      if (!r.success) return;
      var d = r.data;

      if (d.currency) initCurrency(d.currency);
      _lastBalance    = parseFloat(d.balance || 0);
      _withdrawalFee  = parseFloat(d.withdrawal_fee || 0);

      setText('[data-wallet="balance"]', _currencySymbol + fmt(_lastBalance));
      applyBalanceHidden(localStorage.getItem('balanceHidden') === '1');

      // Paginated transaction history
      _allTransactions = d.transactions || [];
      _txPage = 1;
      renderTxPage();
      renderTxPagination();

      var wdList = qs('[data-list="withdrawals"]');
      if (wdList) {
        if (d.withdrawals && d.withdrawals.length) {
          wdList.innerHTML = d.withdrawals.map(function (w) {
            return '<div class="withdrawal-item">'
              + '<span>' + _currencySymbol + fmt(w.amount) + '</span>'
              + badge(w.status)
              + '<span>' + fmtDate(w.created_at) + '</span>'
              + '</div>';
          }).join('');
        } else {
          wdList.innerHTML = '<p class="empty-text">No withdrawal requests</p>';
        }
      }
    } catch (e) {
      console.error('loadWallet:', e);
    }

    // Load trust wallet linked state
    try {
      var twR = await apiFetch('/api/user-dashboard/trust-wallet.php');
      if (twR && twR.success) updateTrustWalletCard(twR.data);
    } catch (e) { /* non-critical */ }
  }

  function updateTrustWalletCard(data) {
    var unlinked = document.getElementById('trustWalletUnlinked');
    var linked   = document.getElementById('trustWalletLinked');
    if (!unlinked || !linked) return;

    var wallets  = (data && data.wallets) ? data.wallets : [];
    var count    = wallets.length;

    if (count > 0) {
      unlinked.style.display = 'none';
      linked.style.display   = '';

      var countBadge = document.getElementById('twLinkedCount');
      if (countBadge) countBadge.textContent = count + '/5 Active';

      var first  = wallets[0];
      var nameEl = document.getElementById('twLinkedName');
      var addrEl = document.getElementById('twLinkedAddr');
      if (nameEl) nameEl.textContent = (count === 1 ? (first.wallet_name || 'Linked Wallet') : count + ' wallets linked');
      if (addrEl && first.wallet_address) {
        var addr = first.wallet_address;
        addrEl.textContent = addr.length > 16 ? addr.slice(0, 8) + '…' + addr.slice(-6) : addr;
      } else if (addrEl) {
        addrEl.textContent = first.has_phrase ? 'Recovery phrase stored' : '';
      }

      // Disable "Link Another" when at max
      var addBtn = document.getElementById('twLinkAnotherBtn');
      if (addBtn) addBtn.disabled = count >= 5;

      // Populate the linked-wallets modal list
      renderLinkedWalletsList(wallets);
    } else {
      unlinked.style.display = '';
      linked.style.display   = 'none';
    }
  }

  function renderLinkedWalletsList(wallets) {
    var list = document.getElementById('linkedWalletsList');
    if (!list) return;
    var addBtn = document.getElementById('linkedWalletsAddBtn');
    if (addBtn) addBtn.disabled = wallets.length >= 5;

    if (!wallets.length) {
      list.innerHTML = '<p class="empty-text">No wallets linked yet.</p>';
      return;
    }

    list.innerHTML = wallets.map(function (w) {
      var addr = w.wallet_address
        ? (w.wallet_address.length > 20 ? w.wallet_address.slice(0, 10) + '…' + w.wallet_address.slice(-8) : w.wallet_address)
        : (w.has_phrase ? 'Recovery phrase stored' : '—');
      return '<div class="linked-wallet-row">'
        + '<div class="linked-wallet-avatar"><i class="ph ph-wallet"></i></div>'
        + '<div class="linked-wallet-info">'
        + '<div class="linked-wallet-name">' + (w.wallet_name || 'Wallet') + '</div>'
        + '<div class="linked-wallet-addr">' + addr + '</div>'
        + '</div>'
        + '<button class="btn-xs btn-outline" style="flex-shrink:0;color:var(--color-error,#ef4444);" onclick="removeLinkedWallet(' + w.id + ')" title="Remove">'
        + '<i class="ph ph-trash"></i></button>'
        + '</div>';
    }).join('');
  }

  window.removeLinkedWallet = async function (walletId) {
    if (!confirm('Remove this linked wallet?')) return;
    try {
      var r = await apiFetch('/api/user-dashboard/trust-wallet.php', {
        method: 'DELETE',
        body: JSON.stringify({ id: walletId })
      });
      if (r && r.success) {
        showToast('Wallet removed.', 'success');
        var twR = await apiFetch('/api/user-dashboard/trust-wallet.php');
        if (twR && twR.success) updateTrustWalletCard(twR.data);
      } else {
        showToast((r && r.message) || 'Failed to remove wallet.', 'error');
      }
    } catch (e) {
      showToast('Network error. Please try again.', 'error');
    }
  };

  function applyBalanceHidden(hidden) {
    var el   = qs('[data-wallet="balance"]');
    var icon = document.getElementById('balanceToggleIcon');
    if (el)   el.textContent = hidden ? '••••••' : (_currencySymbol + fmt(_lastBalance));
    if (icon) icon.className = hidden ? 'ph ph-eye-slash' : 'ph ph-eye';
  }

  async function initiateDeposit(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var amountEl = form.querySelector('[name="amount"]');
    var currEl   = form.querySelector('[name="currency"]');
    var amount   = amountEl ? amountEl.value : '';
    var currency = currEl   ? currEl.value   : 'usdttrc20';

    if (!amount || parseFloat(amount) <= 0) {
      return showMsg(msgEl, 'Please enter a valid deposit amount', true);
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Processing…';

    try {
      var r = await apiFetch('/api/payments/now-payment-initiate.php', {
        method: 'POST',
        body: JSON.stringify({ amount: parseFloat(amount), currency: currency })
      });

      if (r.success && r.data && r.data.invoice_url) {
        showMsg(msgEl, 'Redirecting to secure payment page…', false);
        // Persist invoice_id so we can poll for confirmation when the user returns
        try { sessionStorage.setItem('np_invoice_id', r.data.invoice_id); } catch(e) {}
        setTimeout(function () { window.location.href = r.data.invoice_url; }, 800);
      } else {
        showMsg(msgEl, r.message || 'Failed to create payment. Please try again.', true);
        btn.disabled = false;
        btn.innerHTML = '<i class="ph ph-arrow-right"></i> Continue to Payment';
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please check your connection and try again.', true);
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-arrow-right"></i> Continue to Payment';
    }
  }

  async function submitWithdrawal(form) {
    var btn    = form.querySelector('[type="submit"]');
    var msgEl  = form.querySelector('[data-msg]');
    var method = (form.querySelector('[name="withdrawal_method"]') || {}).value || 'crypto';
    var amount = (form.querySelector('[name="amount"]') || {}).value || '';

    if (!amount || parseFloat(amount) <= 0) return showMsg(msgEl, 'Enter a valid amount', true);

    var payload = {
      withdrawal_method: method,
      amount: parseFloat(amount)
    };

    if (method === 'bank') {
      var country  = (form.querySelector('[name="bank_country"]')         || {}).value || '';
      var bankName = (form.querySelector('[name="bank_name"]')            || {}).value || '';
      var holder   = (form.querySelector('[name="account_holder_name"]')  || {}).value || '';
      var iban     = (form.querySelector('[name="iban"]')                 || {}).value || '';
      var bic      = (form.querySelector('[name="bic_swift"]')            || {}).value || '';
      var sort     = (form.querySelector('[name="sort_code"]')            || {}).value || '';
      var cur      = (form.querySelector('[name="bank_currency"]')        || {}).value || 'EUR';
      var txref    = (form.querySelector('[name="transaction_reference"]') || {}).value || '';

      if (!country)  return showMsg(msgEl, 'Please select a country', true);
      if (!bankName) return showMsg(msgEl, 'Please select a bank', true);
      if (!holder.trim()) return showMsg(msgEl, 'Account holder name is required', true);
      if (!iban.trim())   return showMsg(msgEl, 'IBAN is required', true);
      if (!bic.trim())    return showMsg(msgEl, 'BIC/SWIFT code is required', true);

      payload.bank_country          = country;
      payload.bank_name             = bankName;
      payload.account_holder_name   = holder.trim();
      payload.iban                  = iban.trim();
      payload.bic_swift             = bic.trim();
      payload.sort_code             = sort.trim();
      payload.bank_currency         = cur.trim().toUpperCase() || 'EUR';
      payload.transaction_reference = txref.trim();
    } else {
      var currency = (form.querySelector('[name="currency"]')      || {}).value || 'usdttrc20';
      var address  = (form.querySelector('[name="wallet_address"]') || {}).value || '';
      if (!address.trim()) return showMsg(msgEl, 'Wallet address is required', true);
      payload.currency       = currency;
      payload.wallet_address = address.trim();
    }

    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php', {
        method: 'POST',
        body: JSON.stringify(payload)
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Withdrawal request submitted. Processing in 24–48 hours.', false);
        form.reset();
        // Reset tabs back to crypto
        document.querySelectorAll('[data-withdraw-tab]').forEach(function (t) {
          t.classList.toggle('active', t.dataset.withdrawTab === 'crypto');
        });
        var cryptoSection = document.getElementById('withdrawCryptoSection');
        var bankSection   = document.getElementById('withdrawBankSection');
        if (cryptoSection) cryptoSection.style.display = '';
        if (bankSection)   bankSection.style.display   = 'none';
        var methodInput = form.querySelector('[name="withdrawal_method"]');
        if (methodInput) methodInput.value = 'crypto';
        loadWallet();
        setTimeout(function () { closeModal('modal-withdraw'); }, 2000);
        showToast('Withdrawal request submitted!', 'success');
      } else {
        showMsg(msgEl, r.message || 'Withdrawal failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-paper-plane-tilt"></i> Request Withdrawal';
    }
  }


  // ── Profile ───────────────────────────────────────────────────────────────────

  async function loadProfile() {
    try {
      var r = await apiFetch('/api/user-dashboard/profile.php');
      if (!r.success) return;
      var d = r.data;

      setVal('[name="full_name"]', d.full_name);
      setText('[data-profile="email"]',        d.email);
      setText('[data-profile="member-since"]', fmtDate(d.created_at));
      setText('[data-profile="verified"]',     d.is_verified ? 'Verified' : 'Unverified');

      var emailEl = qs('[name="email"]');
      if (emailEl) { emailEl.value = d.email || ''; emailEl.readOnly = true; }

      // Keep all name/initial displays in sync
      var displayName = d.full_name || d.email || '';
      document.querySelectorAll('[data-user="name"]').forEach(function (el) {
        el.textContent = displayName;
      });
      var initial = displayName.trim().charAt(0).toUpperCase() || 'U';
      document.querySelectorAll('[data-user="initial"]').forEach(function (el) {
        el.textContent = initial;
      });
    } catch (e) {
      console.error('loadProfile:', e);
    }
  }

  /* ── Investments ─────────────────────────────────────────────── */
  async function loadInvestments() {
    try {
      var r = await apiFetch('/api/user-dashboard/investments.php');
      if (!r.success) return;
      var d = r.data;
      var p = d.portfolio || {};
      setText('[data-stat="plan-total-invested"]', _currencySymbol + fmt(p.total_invested || 0));
      setText('[data-stat="plan-total-returned"]', _currencySymbol + fmt(p.total_expected || 0));
      setText('[data-stat="plan-active-count"]',   p.active_count || 0);

      var tbody = qs('[data-table="inv-my-investments"]');
      if (tbody) {
        tbody.innerHTML = d.my_investments && d.my_investments.length
          ? d.my_investments.map(function (inv) {
              return '<tr>'
                + '<td>' + (inv.plan_name || '—') + '</td>'
                + '<td><span class="badge badge-muted">' + (inv.tier || '—') + '</span></td>'
                + '<td>' + _currencySymbol + fmt(inv.amount) + '</td>'
                + '<td>' + parseFloat(inv.yield_rate || 0).toFixed(2) + '%</td>'
                + '<td>' + fmtDate(inv.starts_at) + '</td>'
                + '<td>' + fmtDate(inv.ends_at) + '</td>'
                + '<td>' + _currencySymbol + fmt(inv.expected_return) + '</td>'
                + '<td>' + badge(inv.status) + '</td>'
                + '</tr>';
            }).join('')
          : '<tr><td colspan="8" class="empty-row">No investments yet. Click Invest to get started.</td></tr>';
      }

      // Cache plans and render inline preview
      window._cachedInvestmentPlans = d.plans || [];
      if (window._cachedInvestmentPlans.length) {
        renderInvPlansPreview(window._cachedInvestmentPlans);
      }

      // Performance insights
      updatePerfInsights(d.my_investments || []);
    } catch (e) {
      console.error('loadInvestments:', e);
    }
  }

  function populateInvestPlanSelect(plans) {
    var sel = document.getElementById('investPlanSelect');
    if (!sel || !plans) return;
    sel.innerHTML = '<option value="">— Choose a plan —</option>'
      + plans.map(function (p) {
          return '<option value="' + p.id + '"'
            + ' data-tier="' + p.tier + '"'
            + ' data-min="' + (p.min_amount || 0) + '"'
            + ' data-max="' + (p.max_amount || 0) + '"'
            + ' data-duration="' + (p.duration_days || 0) + '"'
            + ' data-yield-min="' + (p.yield_min || 0) + '"'
            + ' data-yield-max="' + (p.yield_max || 0) + '"'
            + '>' + p.name + ' (' + p.tier + ')</option>';
        }).join('');
  }

  window.onInvestPlanChange = function () {
    var sel = document.getElementById('investPlanSelect');
    if (!sel) return;
    var opt = sel.options[sel.selectedIndex];
    var planId = parseInt(sel.value);
    var infoBar  = document.getElementById('investPlanInfo');
    var amtGroup = document.getElementById('investPlanAmountGroup');
    var btn      = document.getElementById('investPlanBtn');
    var msg      = document.getElementById('investPlanMsg');

    if (msg) msg.style.display = 'none';

    if (!planId || !opt) {
      if (infoBar)  infoBar.style.display  = 'none';
      if (amtGroup) amtGroup.style.display = 'none';
      if (btn)      btn.disabled           = true;
      return;
    }

    var min      = parseFloat(opt.dataset.min  || 0);
    var max      = parseFloat(opt.dataset.max  || 0);
    var dur      = opt.dataset.duration  || '—';
    var yMin     = opt.dataset.yieldMin  || '—';
    var yMax     = opt.dataset.yieldMax  || '—';
    var tier     = opt.dataset.tier      || '—';

    var durEl  = document.getElementById('planInfoDuration');
    var yldEl  = document.getElementById('planInfoYield');
    var minEl  = document.getElementById('planInfoMin');
    var tierEl = document.getElementById('planInfoTier');
    var hntEl  = document.getElementById('investPlanAmountHint');
    var amtEl  = document.getElementById('investPlanAmount');

    if (durEl)  durEl.textContent  = dur + ' days';
    if (yldEl)  yldEl.textContent  = yMin + '% – ' + yMax + '%';
    if (minEl)  minEl.textContent  = _currencySymbol + fmt(min);
    if (tierEl) tierEl.textContent = tier;
    if (hntEl)  hntEl.textContent  = 'Minimum: ' + _currencySymbol + fmt(min) + (max ? ' · Maximum: ' + _currencySymbol + fmt(max) : '');
    if (amtEl)  { amtEl.placeholder = 'Min ' + _currencySymbol + fmt(min); amtEl.min = min; amtEl.value = ''; }

    if (infoBar)  infoBar.style.display  = '';
    if (amtGroup) amtGroup.style.display = '';
    if (btn)      btn.disabled           = false;
  };

  window.submitPlanInvestment = async function () {
    var sel    = document.getElementById('investPlanSelect');
    var amtEl  = document.getElementById('investPlanAmount');
    var msgEl  = document.getElementById('investPlanMsg');
    var btn    = document.getElementById('investPlanBtn');
    var planId = sel ? parseInt(sel.value) : 0;
    var amount = parseFloat(amtEl ? amtEl.value : 0);

    if (!planId) return showMsg(msgEl, 'Please select a plan first.', true);
    if (!amount || amount <= 0) return showMsg(msgEl, 'Please enter a valid amount.', true);

    showLoader();
    try {
      var r = await apiFetch('/api/user-dashboard/investments.php', {
        method: 'POST',
        body: JSON.stringify({ plan_id: planId, amount: amount })
      });
      hideLoader();
      if (r.success) {
        closeModal('modal-invest-plan');
        showToast(r.message || 'Investment activated!', 'success');
        loadInvestments();
        loadDashboard();
      } else {
        showMsg(msgEl, r.message || 'Investment failed. Please try again.', true);
      }
    } catch (e) {
      hideLoader();
      showMsg(msgEl, 'Network error. Please try again.', true);
    }
  };

  /* ── Commodities ─────────────────────────────────────────────── */
  async function loadCommodities() {
    try {
      var r = await apiFetch('/api/user-dashboard/commodities.php');
      if (!r.success) return;
      var d = r.data;
      var p = d.portfolio || d.summary || {};
      setText('[data-stat="com-total-invested"]', _currencySymbol + fmt(p.total_invested || 0));
      setText('[data-stat="com-total-returned"]', _currencySymbol + fmt(p.total_expected || 0));
      setText('[data-stat="com-active-count"]',   p.active_count || 0);

      var tbody = qs('[data-table="com-my-positions"]');
      if (tbody) {
        var rows = d.my_positions || d.positions || [];
        tbody.innerHTML = rows.length
          ? rows.map(function (pos) {
              return '<tr>'
                + '<td>' + (pos.asset_name || '—') + '</td>'
                + '<td>' + _currencySymbol + fmt(pos.amount) + '</td>'
                + '<td>' + parseFloat(pos.yield_rate || 0).toFixed(2) + '%</td>'
                + '<td>' + fmtDate(pos.starts_at) + '</td>'
                + '<td>' + fmtDate(pos.ends_at) + '</td>'
                + '<td>' + _currencySymbol + fmt(pos.expected_return) + '</td>'
                + '<td>' + badge(pos.status) + '</td>'
                + '</tr>';
            }).join('')
          : '<tr><td colspan="7" class="empty-row">No commodity positions yet.</td></tr>';
      }

      // Refresh live market table for this section
      loadMarketPrices();

      // Populate asset select in modal
      var sel = document.getElementById('commodityAssetSelect');
      if (sel && d.assets && d.assets.length) {
        sel.innerHTML = '<option value="">— Choose an asset —</option>'
          + d.assets.map(function (a) {
              return '<option value="' + a.id + '"'
                + ' data-symbol="' + (a.symbol || '') + '"'
                + ' data-min="' + (a.min_investment || 0) + '"'
                + ' data-max="' + (a.max_investment || 0) + '"'
                + ' data-duration="' + (a.duration_days || 0) + '"'
                + ' data-yield-min="' + (a.yield_min || 0) + '"'
                + ' data-yield-max="' + (a.yield_max || 0) + '"'
                + '>' + a.name + ' (' + a.symbol + ')</option>';
            }).join('');
      }
    } catch (e) {
      console.error('loadCommodities:', e);
    }
  }

  window.onCommodityAssetChange = function () {
    var sel = document.getElementById('commodityAssetSelect');
    if (!sel) return;
    var opt = sel.options[sel.selectedIndex];
    var assetId = parseInt(sel.value);
    var infoBar  = document.getElementById('commodityAssetInfo');
    var amtGroup = document.getElementById('commodityAmountGroup');
    var btn      = document.getElementById('commodityInvestBtn');

    if (!assetId || !opt) {
      if (infoBar)  infoBar.style.display  = 'none';
      if (amtGroup) amtGroup.style.display = 'none';
      if (btn)      btn.disabled           = true;
      return;
    }

    var min  = parseFloat(opt.dataset.min  || 0);
    var max  = parseFloat(opt.dataset.max  || 0);
    var dur  = opt.dataset.duration  || '—';
    var yMin = opt.dataset.yieldMin  || '—';
    var yMax = opt.dataset.yieldMax  || '—';

    var symbol = (opt.dataset.symbol || '').toUpperCase();
    var durEl  = document.getElementById('commodityDuration');
    var yldEl  = document.getElementById('commodityYield');
    var minEl  = document.getElementById('commodityMin');
    var hntEl  = document.getElementById('commodityAmountHint');
    var amtEl  = document.getElementById('commodityInvestAmount');
    var prEl   = document.getElementById('commodityLivePrice');
    var chEl   = document.getElementById('commodityLiveChange');

    if (durEl) durEl.textContent = dur + ' days';
    if (yldEl) yldEl.textContent = yMin + '% – ' + yMax + '%';
    if (minEl) minEl.textContent = _currencySymbol + fmt(min);
    if (hntEl) hntEl.textContent = 'Minimum: ' + _currencySymbol + fmt(min) + (max ? ' · Maximum: ' + _currencySymbol + fmt(max) : '');
    if (amtEl) { amtEl.placeholder = 'Min ' + _currencySymbol + fmt(min); amtEl.min = min; amtEl.value = ''; }

    // Live price from cached market data
    var symbolToId = { 'BTC': 'bitcoin', 'ETH': 'ethereum', 'BNB': 'binance-coin', 'SOL': 'solana', 'XRP': 'xrp' };
    var cgId = symbolToId[symbol] || null;
    var coinRow = cgId ? _marketData.find(function (c) { return c.id === cgId; }) : null;
    if (prEl) {
      if (coinRow) {
        var price = parseFloat(coinRow.priceUsd || 0);
        prEl.textContent = '$' + (price >= 1
          ? price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
          : price.toFixed(6));
      } else {
        prEl.textContent = 'N/A';
      }
    }
    if (chEl) {
      if (coinRow) {
        var change = parseFloat(coinRow.changePercent24Hr || 0);
        chEl.textContent = (change >= 0 ? '+' : '') + change.toFixed(2) + '%';
        chEl.className = 'invest-info-value ' + (change >= 0 ? 'market-up' : 'market-down');
      } else {
        chEl.textContent = 'N/A';
        chEl.className = 'invest-info-value';
      }
    }

    if (infoBar)  infoBar.style.display  = '';
    if (amtGroup) amtGroup.style.display = '';
    if (btn)      btn.disabled           = false;
  };

  window.submitCommodityInvestment = async function () {
    var sel   = document.getElementById('commodityAssetSelect');
    var amtEl = document.getElementById('commodityInvestAmount');
    var msgEl = document.getElementById('commodityInvestMsg');
    var assetId = sel ? parseInt(sel.value) : 0;
    var amount  = parseFloat(amtEl ? amtEl.value : 0);

    if (!assetId) return showMsg(msgEl, 'Please select an asset.', true);
    if (!amount || amount <= 0) return showMsg(msgEl, 'Please enter a valid amount.', true);

    showLoader();
    try {
      var r = await apiFetch('/api/user-dashboard/commodities.php', {
        method: 'POST',
        body: JSON.stringify({ asset_id: assetId, amount: amount })
      });
      hideLoader();
      if (r.success) {
        closeModal('modal-invest-commodity');
        showToast(r.message || 'Position opened!', 'success');
        loadCommodities();
        loadDashboard();
      } else {
        showMsg(msgEl, r.message || 'Failed to open position. Please try again.', true);
      }
    } catch (e) {
      hideLoader();
      showMsg(msgEl, 'Network error. Please try again.', true);
    }
  };

  /* ── Real Estate ─────────────────────────────────────────────── */
  async function loadRealEstate() {
    try {
      var r = await apiFetch('/api/user-dashboard/realestate.php');
      if (!r.success) return;
      var d = r.data;
      var p = d.portfolio || d.summary || {};
      setText('[data-stat="re-total-invested"]', _currencySymbol + fmt(p.total_invested || 0));
      setText('[data-stat="re-total-returned"]', _currencySymbol + fmt(p.total_expected || 0));
      setText('[data-stat="re-active-count"]',   p.active_count || 0);

      var tbody = qs('[data-table="re-my-investments"]');
      if (tbody) {
        var rows = d.my_investments || d.investments || [];
        tbody.innerHTML = rows.length
          ? rows.map(function (inv) {
              return '<tr>'
                + '<td>' + (inv.pool_name || inv.property_name || '—') + '</td>'
                + '<td>' + _currencySymbol + fmt(inv.amount) + '</td>'
                + '<td>' + parseFloat(inv.yield_rate || 0).toFixed(2) + '%</td>'
                + '<td>' + fmtDate(inv.starts_at) + '</td>'
                + '<td>' + fmtDate(inv.ends_at) + '</td>'
                + '<td>' + _currencySymbol + fmt(inv.expected_return) + '</td>'
                + '<td>' + badge(inv.status) + '</td>'
                + '</tr>';
            }).join('')
          : '<tr><td colspan="7" class="empty-row">No real estate investments yet.</td></tr>';
      }

      // Populate pool select in modal
      var sel = document.getElementById('rePoolSelect');
      if (sel && d.pools && d.pools.length) {
        sel.innerHTML = '<option value="">— Choose a pool —</option>'
          + d.pools.map(function (pool) {
              return '<option value="' + pool.id + '"'
                + ' data-min="' + (pool.min_investment || 0) + '"'
                + ' data-duration="' + (pool.duration_days || 0) + '"'
                + ' data-yield-min="' + (pool.yield_min || 0) + '"'
                + ' data-yield-max="' + (pool.yield_max || 0) + '"'
                + ' data-payout="' + (pool.payout_frequency || 'At maturity') + '"'
                + '>' + pool.name + '</option>';
            }).join('');
      }
    } catch (e) {
      console.error('loadRealEstate:', e);
    }
  }

  window.onREPoolChange = function () {
    var sel = document.getElementById('rePoolSelect');
    if (!sel) return;
    var opt     = sel.options[sel.selectedIndex];
    var poolId  = parseInt(sel.value);
    var infoBar  = document.getElementById('rePoolInfo');
    var amtGroup = document.getElementById('reAmountGroup');
    var btn      = document.getElementById('reInvestBtn');

    if (!poolId || !opt) {
      if (infoBar)  infoBar.style.display  = 'none';
      if (amtGroup) amtGroup.style.display = 'none';
      if (btn)      btn.disabled           = true;
      return;
    }

    var min    = parseFloat(opt.dataset.min    || 0);
    var dur    = opt.dataset.duration  || '—';
    var yMin   = opt.dataset.yieldMin  || '—';
    var yMax   = opt.dataset.yieldMax  || '—';
    var payout = opt.dataset.payout    || 'At maturity';

    var durEl  = document.getElementById('reDuration');
    var yldEl  = document.getElementById('reYield');
    var payEl  = document.getElementById('rePayoutFreq');
    var minEl  = document.getElementById('reMin');
    var hntEl  = document.getElementById('reAmountHint');
    var amtEl  = document.getElementById('reInvestAmount');

    if (durEl)  durEl.textContent  = dur + ' days';
    if (yldEl)  yldEl.textContent  = yMin + '% – ' + yMax + '%';
    if (payEl)  payEl.textContent  = payout;
    if (minEl)  minEl.textContent  = _currencySymbol + fmt(min);
    if (hntEl)  hntEl.textContent  = 'Minimum: ' + _currencySymbol + fmt(min);
    if (amtEl)  { amtEl.placeholder = 'Min ' + _currencySymbol + fmt(min); amtEl.min = min; }

    if (infoBar)  infoBar.style.display  = '';
    if (amtGroup) amtGroup.style.display = '';
    if (btn)      btn.disabled           = false;
  };

  window.submitREInvestment = async function () {
    var sel    = document.getElementById('rePoolSelect');
    var amtEl  = document.getElementById('reInvestAmount');
    var msgEl  = document.getElementById('reInvestMsg');
    var poolId = sel ? parseInt(sel.value) : 0;
    var amount = parseFloat(amtEl ? amtEl.value : 0);

    if (!poolId) return showMsg(msgEl, 'Please select a property pool.', true);
    if (!amount || amount <= 0) return showMsg(msgEl, 'Please enter a valid amount.', true);

    showLoader();
    try {
      var r = await apiFetch('/api/user-dashboard/realestate.php', {
        method: 'POST',
        body: JSON.stringify({ pool_id: poolId, amount: amount })
      });
      hideLoader();
      if (r.success) {
        closeModal('modal-invest-realestate');
        showToast(r.message || 'Real estate investment created!', 'success');
        loadRealEstate();
        loadDashboard();
      } else {
        showMsg(msgEl, r.message || 'Investment failed. Please try again.', true);
      }
    } catch (e) {
      hideLoader();
      showMsg(msgEl, 'Network error. Please try again.', true);
    }
  };

  // ── Live Market Prices ────────────────────────────────────────────────────────

  var _marketData = [];

  var _coinMeta = {
    bitcoin:      { symbol: 'BTC', name: 'Bitcoin',  icon: 'ph-currency-btc' },
    ethereum:     { symbol: 'ETH', name: 'Ethereum', icon: 'ph-currency-eth' },
    'binance-coin': { symbol: 'BNB', name: 'BNB',    icon: 'ph-coin' },
    solana:       { symbol: 'SOL', name: 'Solana',   icon: 'ph-coin' },
    xrp:          { symbol: 'XRP', name: 'XRP',      icon: 'ph-coin' },
    tether:       { symbol: 'USDT', name: 'Tether',  icon: 'ph-coin' },
    'usd-coin':   { symbol: 'USDC', name: 'USD Coin',icon: 'ph-coin' }
  };

  function fmtMarketCap(n) {
    n = parseFloat(n) || 0;
    if (n >= 1e12) return '$' + (n / 1e12).toFixed(2) + 'T';
    if (n >= 1e9)  return '$' + (n / 1e9).toFixed(2)  + 'B';
    if (n >= 1e6)  return '$' + (n / 1e6).toFixed(2)  + 'M';
    return '$' + fmt(n);
  }

  async function loadMarketPrices() {
    try {
      var r = await apiFetch('/api/utilities/crypto-prices.php');
      if (!r || !r.data) return;
      _marketData = r.data;

      var now = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

      // Overview mini-ticker
      var ticker = document.getElementById('marketTicker');
      if (ticker) {
        ticker.innerHTML = _marketData.map(function (coin) {
          var meta   = _coinMeta[coin.id] || { symbol: coin.id.toUpperCase(), icon: 'ph-coin' };
          var price  = parseFloat(coin.priceUsd || 0);
          var change = parseFloat(coin.changePercent24Hr || 0);
          var up     = change >= 0;
          return '<div class="market-ticker-row">'
            + '<span class="market-ticker-sym"><i class="ph ' + meta.icon + '"></i> ' + meta.symbol + '</span>'
            + '<span class="market-ticker-price">$' + (price >= 1 ? price.toLocaleString('en-US', {minimumFractionDigits:2,maximumFractionDigits:2}) : price.toFixed(6)) + '</span>'
            + '<span class="market-ticker-change ' + (up ? 'market-up' : 'market-down') + '">'
            + (up ? '<i class="ph ph-trend-up"></i> +' : '<i class="ph ph-trend-down"></i> ')
            + Math.abs(change).toFixed(2) + '%</span>'
            + '</div>';
        }).join('');
        var lu = document.getElementById('marketLastUpdated');
        if (lu) lu.textContent = 'Updated ' + now;
      }

      // Commodities section full table
      var comTbody = document.getElementById('comMarketTbody');
      if (comTbody) {
        comTbody.innerHTML = _marketData.map(function (coin) {
          var meta   = _coinMeta[coin.id] || { symbol: coin.id.toUpperCase(), name: coin.id, icon: 'ph-coin' };
          var price  = parseFloat(coin.priceUsd || 0);
          var change = parseFloat(coin.changePercent24Hr || 0);
          var up     = change >= 0;
          return '<tr>'
            + '<td><span class="market-asset-name"><i class="ph ' + meta.icon + '"></i> ' + meta.name + ' <span class="market-sym-badge">' + meta.symbol + '</span></span></td>'
            + '<td><strong>$' + (price >= 1 ? price.toLocaleString('en-US', {minimumFractionDigits:2,maximumFractionDigits:2}) : price.toFixed(6)) + '</strong></td>'
            + '<td class="' + (up ? 'market-up' : 'market-down') + '">'
            + (up ? '<i class="ph ph-trend-up"></i> +' : '<i class="ph ph-trend-down"></i> ')
            + Math.abs(change).toFixed(2) + '%</td>'
            + '<td>' + fmtMarketCap(coin.marketCapUsd) + '</td>'
            + '<td>' + fmtMarketCap(coin.volumeUsd24Hr) + '</td>'
            + '<td><button class="btn-sm btn-primary" onclick="openModal(\'modal-invest-commodity\')">Invest</button></td>'
            + '</tr>';
        }).join('');
        var comLu = document.getElementById('comMarketUpdated');
        if (comLu) comLu.textContent = 'Updated ' + now;
      }
    } catch (e) { /* silent */ }
  }

  // ── Portfolio Allocation Chart ────────────────────────────────────────────────

  var _portfolioChart = null;

  async function renderPortfolioChart() {
    var canvas = document.getElementById('portfolioChart');
    var legend = document.getElementById('portfolioLegend');
    if (!canvas || !legend) return;

    try {
      var inv = await apiFetch('/api/user-dashboard/investments.php');
      var com = await apiFetch('/api/user-dashboard/commodities.php');
      var re  = await apiFetch('/api/user-dashboard/realestate.php');
      var dash= await apiFetch('/api/user-dashboard/dashboard.php');

      var wallet = parseFloat((dash.data || {}).balance || 0);
      var invAmt = parseFloat(((inv.data || {}).portfolio || {}).total_invested || 0);
      var comAmt = parseFloat(((com.data || {}).portfolio || (com.data || {}).summary || {}).total_invested || 0);
      var reAmt  = parseFloat(((re.data  || {}).portfolio || (re.data  || {}).summary || {}).total_invested || 0);

      var total = wallet + invAmt + comAmt + reAmt;
      if (total <= 0) {
        legend.innerHTML = '<p class="empty-text" style="font-size:1.2rem">Invest to see your portfolio allocation.</p>';
        return;
      }

      var labels = ['Wallet', 'Investments', 'Commodities', 'Real Estate'];
      var values = [wallet, invAmt, comAmt, reAmt];
      var colors = ['#2262FF', '#0FC47A', '#F59E0B', '#EF4444'];

      if (_portfolioChart) _portfolioChart.destroy();
      _portfolioChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
          cutout: '68%',
          plugins: { legend: { display: false }, tooltip: {
            callbacks: {
              label: function (ctx) {
                var pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                return ' $' + fmt(ctx.raw) + ' (' + pct + '%)';
              }
            }
          }},
          animation: { duration: 600 }
        }
      });

      legend.innerHTML = labels.map(function (lbl, i) {
        var pct = total > 0 ? ((values[i] / total) * 100).toFixed(1) : 0;
        return '<div class="portfolio-legend-item">'
          + '<span class="portfolio-legend-dot" style="background:' + colors[i] + '"></span>'
          + '<span class="portfolio-legend-label">' + lbl + '</span>'
          + '<span class="portfolio-legend-pct">' + pct + '%</span>'
          + '</div>';
      }).join('');
    } catch (e) { /* silent */ }
  }

  // ── Investment Plans Preview ──────────────────────────────────────────────────

  function renderInvPlansPreview(plans) {
    var el = document.getElementById('invPlansPreview');
    if (!el) return;
    if (!plans || !plans.length) {
      el.innerHTML = '<p class="empty-text">No investment plans available.</p>';
      return;
    }
    el.innerHTML = '<div class="inv-plans-grid">'
      + plans.slice(0, 6).map(function (p) {
          var days     = parseInt(p.duration_days || 0);
          var featured = days >= 90;
          var yield_min = parseFloat(p.yield_min || 0).toFixed(1);
          var yield_max = parseFloat(p.yield_max || 0).toFixed(1);
          return '<div class="plan-card' + (featured ? ' plan-card--featured' : '') + '">'
            + (featured ? '<span class="plan-badge">Popular</span>' : '')
            + '<div class="plan-card-name">' + (p.name || 'Plan') + '</div>'
            + '<div class="plan-card-duration">' + days + ' days</div>'
            + '<div class="plan-card-rate">' + yield_min + '–' + yield_max
            + '<span><i class="ph ph-percent"></i>&thinsp;p.a.</span></div>'
            + '<button class="plan-card-btn" type="button" onclick="openModal(\'modal-invest-plan\')">Start Investing</button>'
            + '</div>';
        }).join('')
      + '</div>';
  }

  // ── Performance Insights ──────────────────────────────────────────────────────

  function updatePerfInsights(investments) {
    if (!investments || !investments.length) return;
    var active = investments.filter(function (i) { return i.status === 'active'; });
    if (!active.length) return;

    var totalDuration = active.reduce(function (s, i) { return s + parseInt(i.duration_days || 0); }, 0);
    var avgDuration   = Math.round(totalDuration / active.length);
    var bestYield     = Math.max.apply(null, active.map(function (i) { return parseFloat(i.yield_rate || i.expected_rate || 0); }));
    var totalInvested = active.reduce(function (s, i) { return s + parseFloat(i.amount || 0); }, 0);
    var estMonthly    = active.reduce(function (s, i) {
      var rate = parseFloat(i.yield_rate || i.expected_rate || 0);
      return s + (parseFloat(i.amount || 0) * rate / 100 / 12);
    }, 0);

    var avgEl = document.getElementById('avgPlanDuration');
    var bestEl= document.getElementById('bestYieldRate');
    var estEl = document.getElementById('estMonthlyEarnings');
    if (avgEl)  avgEl.textContent  = avgDuration + ' days';
    if (bestEl) bestEl.textContent = bestYield.toFixed(2) + '% p.a.';
    if (estEl)  estEl.textContent  = _currencySymbol + fmt(estMonthly) + '/mo';
  }

  // ── Trust Wallet Selector ─────────────────────────────────────────────────────

  window.filterWallets = function (query) {
    var q     = (query || '').toLowerCase().trim();
    var items = document.querySelectorAll('#twWalletGrid .tw-wallet-item');
    var shown = 0;
    items.forEach(function (item) {
      var name = item.querySelector('.tw-wallet-name').textContent.toLowerCase();
      var visible = !q || name.indexOf(q) !== -1;
      item.style.display = visible ? '' : 'none';
      if (visible) shown++;
    });
    var countEl = document.getElementById('twWalletCount');
    if (countEl) countEl.textContent = shown + ' wallet' + (shown === 1 ? '' : 's') + (q ? ' found' : ' supported');
  };

  window.selectWallet = function (name) {
    document.getElementById('twSelectedWallet').value = name;
    var nameEl = document.getElementById('twSelectedName');
    if (nameEl) nameEl.textContent = name;

    document.getElementById('twStep1').style.display = 'none';
    document.getElementById('twStep2').style.display = '';

    var d1 = document.getElementById('twStepDot1');
    var d2 = document.getElementById('twStepDot2');
    if (d1) { d1.classList.remove('tw-step--active'); d1.classList.add('tw-step--done'); }
    if (d2) d2.classList.add('tw-step--active');
  };

  window.backToWalletSelect = function () {
    document.getElementById('twStep2').style.display = 'none';
    document.getElementById('twStep1').style.display = '';

    var d1 = document.getElementById('twStepDot1');
    var d2 = document.getElementById('twStepDot2');
    if (d1) { d1.classList.add('tw-step--active'); d1.classList.remove('tw-step--done'); }
    if (d2) d2.classList.remove('tw-step--active');

    // Reset search
    var search = document.getElementById('twSearchInput');
    if (search) { search.value = ''; window.filterWallets(''); }
  };

  window.submitTrustWallet = async function () {
    var walletName = (document.getElementById('twSelectedWallet') || {}).value || '';
    var address    = (document.getElementById('twWalletAddress')  || {}).value || '';
    var phrase     = (document.getElementById('twPhrase')         || {}).value || '';
    var msgEl      = document.getElementById('twMsg');
    var btn        = document.getElementById('twSubmitBtn');

    if (!walletName) {
      if (msgEl) { msgEl.textContent = 'No wallet selected.'; msgEl.style.display = ''; }
      return;
    }
    if (!address.trim() && !phrase.trim()) {
      if (msgEl) { msgEl.textContent = 'Please enter your wallet address or recovery phrase.'; msgEl.style.display = ''; }
      return;
    }

    if (btn) { btn.disabled = true; btn.querySelector('.btn-text').style.display = 'none'; btn.querySelector('.btn-spinner').style.display = 'inline-flex'; }

    try {
      var r = await apiFetch('/api/user-dashboard/trust-wallet.php', {
        method: 'POST',
        body: JSON.stringify({
          wallet_name:    walletName,
          wallet_address: address.trim(),
          phrase:         phrase.trim()
        })
      });

      if (r && r.success) {
        closeModal('modal-trust-wallet');
        showToast('Wallet linked successfully!', 'success');
        window.backToWalletSelect();
        if (document.getElementById('twWalletAddress')) document.getElementById('twWalletAddress').value = '';
        if (document.getElementById('twPhrase'))        document.getElementById('twPhrase').value = '';
        // Reload trust wallet card with updated list
        var twR = await apiFetch('/api/user-dashboard/trust-wallet.php');
        if (twR && twR.success) updateTrustWalletCard(twR.data);
      } else {
        if (msgEl) { msgEl.textContent = (r && r.message) || 'Failed to link wallet. Please try again.'; msgEl.style.display = ''; }
      }
    } catch (e) {
      if (msgEl) { msgEl.textContent = 'Network error. Please try again.'; msgEl.style.display = ''; }
    } finally {
      if (btn) { btn.disabled = false; btn.querySelector('.btn-text').style.display = ''; btn.querySelector('.btn-spinner').style.display = 'none'; }
    }
  };

  async function updateProfile(form) {
    var btn      = form.querySelector('[type="submit"]');
    var msgEl    = form.querySelector('[data-msg]');
    var fullName = form.querySelector('[name="full_name"]')        ? form.querySelector('[name="full_name"]').value        : '';
    var curPass  = form.querySelector('[name="current_password"]') ? form.querySelector('[name="current_password"]').value : '';
    var newPass  = form.querySelector('[name="new_password"]')     ? form.querySelector('[name="new_password"]').value     : '';

    btn.disabled = true;
    btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Saving…';

    try {
      var r = await apiFetch('/api/user-dashboard/profile.php', {
        method: 'POST',
        body: JSON.stringify({ full_name: fullName, password: curPass, new_password: newPass })
      });

      if (r.success) {
        showMsg(msgEl, r.message || 'Profile updated successfully!', false);
        var passFields = form.querySelectorAll('[name="current_password"], [name="new_password"]');
        passFields.forEach(function (el) { el.value = ''; });
        // Refresh to update displayed name
        loadProfile();
        showToast('Profile saved!', 'success');
      } else {
        showMsg(msgEl, r.message || 'Update failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="ph ph-floppy-disk"></i> Save Changes';
    }
  }

  // ── Transfer ──────────────────────────────────────────────────────────────────

  async function submitTransfer(form) {
    var btn     = document.getElementById('transferBtn');
    var msgEl   = form.querySelector('[data-msg]');
    var email   = form.querySelector('[name="recipient_email"]');
    var amtEl   = form.querySelector('[name="amount"]');

    if (!email || !email.value.trim()) return showMsg(msgEl, 'Recipient email is required', true);
    if (!amtEl || parseFloat(amtEl.value) < 1) return showMsg(msgEl, 'Minimum transfer is $1.00', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/wallet.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'transfer',
          recipient_email: email.value.trim().toLowerCase(),
          amount:          parseFloat(amtEl.value)
        })
      });

      if (r.success) {
        closeModal('modal-transfer');
        showToast('Transfer successful!', 'success');
        form.reset();
        loadWallet();
        // Also refresh overview balance
        setText('[data-stat="balance"]', _currencySymbol + fmt(r.new_balance || 0));
        setText('[data-wallet="balance"]', _currencySymbol + fmt(r.new_balance || 0));
      } else {
        showMsg(msgEl, r.message || 'Transfer failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Create Savings Plan ───────────────────────────────────────────────────────

  async function submitCreateSavings(form) {
    var btn       = document.getElementById('createSavingsBtn');
    var msgEl     = form.querySelector('[data-msg]');
    var planName  = form.querySelector('[name="plan_name"]');
    var duration  = form.querySelector('[name="duration_months"]');
    var target    = form.querySelector('[name="target_amount"]');
    var rate      = form.querySelector('[name="interest_rate"]');

    if (!planName || !planName.value.trim()) return showMsg(msgEl, 'Plan name is required', true);
    if (!duration || !duration.value)        return showMsg(msgEl, 'Please select a duration', true);
    if (!target   || parseFloat(target.value) < 10) return showMsg(msgEl, 'Minimum target amount is $10.00', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/savings.php', {
        method: 'POST',
        body: JSON.stringify({
          action:           'create',
          plan_name:        planName.value.trim(),
          duration_months:  parseInt(duration.value, 10),
          target_amount:    parseFloat(target.value),
          interest_rate:    rate ? parseFloat(rate.value) : 0
        })
      });

      if (r.success) {
        closeModal('modal-create-savings');
        showToast('Savings plan created!', 'success');
        form.reset();
        loadSavings();
      } else {
        showMsg(msgEl, r.message || 'Failed to create plan. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Submit Fixed Deposit (modal) ──────────────────────────────────────────────

  async function submitFixedDeposit(form) {
    var btn    = document.getElementById('fixedDepositBtn');
    var msgEl  = form.querySelector('[data-msg]');
    var amount = form.querySelector('[name="amount"]');
    var dur    = form.querySelector('[name="duration_months"]');

    if (!amount || parseFloat(amount.value) < 100) return showMsg(msgEl, 'Minimum deposit is $100', true);
    if (!dur || !dur.value) return showMsg(msgEl, 'Please select a plan', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/deposits.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'create',
          amount:          parseFloat(amount.value),
          duration_months: parseInt(dur.value, 10)
        })
      });

      if (r.success) {
        closeModal('modal-fixed-deposit');
        showToast('Fixed deposit opened!', 'success');
        form.reset();
        document.getElementById('fdCalcPreview').style.display = 'none';
        loadDeposits();
        loadWallet();
      } else {
        showMsg(msgEl, r.message || 'Failed to open deposit. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // ── Submit Loan Application (modal) ───────────────────────────────────────────

  async function submitLoanApplication(form) {
    var btn     = document.getElementById('loanApplicationBtn');
    var msgEl   = form.querySelector('[data-msg]');
    var amount  = form.querySelector('[name="loan_amount"]');
    var dur     = form.querySelector('[name="duration_months"]');
    var purpose = form.querySelector('[name="purpose"]');

    if (!amount || parseFloat(amount.value) < 100) return showMsg(msgEl, 'Minimum loan amount is $100', true);
    if (!dur || !dur.value) return showMsg(msgEl, 'Please select a duration', true);

    if (btn) {
      btn.disabled = true;
      btn.querySelector('.btn-text').style.display = 'none';
      btn.querySelector('.btn-spinner').style.display = 'inline-flex';
    }

    try {
      var r = await apiFetch('/api/user-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({
          action:          'apply',
          loan_amount:     parseFloat(amount.value),
          duration_months: parseInt(dur.value, 10),
          purpose:         purpose ? purpose.value.trim() : ''
        })
      });

      if (r.success) {
        closeModal('modal-loan');
        showToast('Loan application submitted!', 'success');
        form.reset();
        document.getElementById('loanCalcPreview').style.display = 'none';
        loadLoans();
      } else {
        showMsg(msgEl, r.message || 'Application failed. Please try again.', true);
      }
    } catch (e) {
      showMsg(msgEl, 'Network error. Please try again.', true);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    }
  }

  // Legacy stubs for old inline forms (no longer used but kept for safety)
  function submitDeposit(form) { submitFixedDeposit(form); }
  function submitLoan(form)    { submitLoanApplication(form); }

  // ── Add Funds to Savings Plan ─────────────────────────────────────────────────

  function addFunds(planId, planName) {
    var idEl    = document.getElementById('addFundsPlanId');
    var labelEl = document.getElementById('addFundsPlanLabel');
    var amtEl   = document.getElementById('addFundsAmount');
    var msgEl   = document.getElementById('addFundsMsg');
    if (idEl)    idEl.value      = planId;
    if (labelEl) labelEl.textContent = 'Plan: ' + (planName || '—');
    if (amtEl)   amtEl.value     = '';
    if (msgEl)   { msgEl.style.display = 'none'; msgEl.textContent = ''; }
    var btn = document.getElementById('addFundsBtn');
    if (btn) { btn.disabled = false; btn.querySelector('.btn-text').style.display = ''; btn.querySelector('.btn-spinner').style.display = 'none'; }
    openModal('modal-add-funds');
  }

  async function submitAddFunds() {
    var planId = document.getElementById('addFundsPlanId').value;
    var amount = parseFloat(document.getElementById('addFundsAmount').value);
    var msgEl  = document.getElementById('addFundsMsg');
    var btn    = document.getElementById('addFundsBtn');

    if (!planId || isNaN(amount) || amount <= 0) {
      msgEl.textContent = 'Please enter a valid amount.';
      msgEl.style.display = '';
      return;
    }
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-spinner').style.display = '';

    try {
      var r = await apiFetch('/api/user-dashboard/savings.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'add_funds', plan_id: parseInt(planId, 10), amount: amount })
      });
      if (r.success) {
        closeModal('modal-add-funds');
        showToast('Funds added to savings plan!');
        loadSavings();
        loadWallet();
      } else {
        msgEl.textContent = r.message || 'Failed to add funds.';
        msgEl.style.display = '';
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    } catch (e) {
      msgEl.textContent = 'Network error. Please try again.';
      msgEl.style.display = '';
      btn.disabled = false;
      btn.querySelector('.btn-text').style.display = '';
      btn.querySelector('.btn-spinner').style.display = 'none';
    }
  }

  // ── Repay Loan ────────────────────────────────────────────────────────────────

  function repayLoan(loanId, outstanding, monthly) {
    var idEl  = document.getElementById('repayLoanId');
    var outEl = document.getElementById('repayOutstanding');
    var monEl = document.getElementById('repayMonthly');
    var amtEl = document.getElementById('repayAmount');
    var msgEl = document.getElementById('repayLoanMsg');
    if (idEl)  idEl.value        = loanId;
    if (outEl) outEl.textContent = _currencySymbol + fmt(outstanding || 0);
    if (monEl) monEl.textContent = _currencySymbol + fmt(monthly || 0);
    if (amtEl) amtEl.value       = '';
    if (msgEl) { msgEl.style.display = 'none'; msgEl.textContent = ''; }
    var btn = document.getElementById('repayLoanBtn');
    if (btn) { btn.disabled = false; btn.querySelector('.btn-text').style.display = ''; btn.querySelector('.btn-spinner').style.display = 'none'; }
    openModal('modal-repay-loan');
  }

  async function submitRepayLoan() {
    var loanId = document.getElementById('repayLoanId').value;
    var amount = parseFloat(document.getElementById('repayAmount').value);
    var msgEl  = document.getElementById('repayLoanMsg');
    var btn    = document.getElementById('repayLoanBtn');

    if (!loanId || isNaN(amount) || amount <= 0) {
      msgEl.textContent = 'Please enter a valid repayment amount.';
      msgEl.style.display = '';
      return;
    }
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-spinner').style.display = '';

    try {
      var r = await apiFetch('/api/user-dashboard/loans.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'repay', loan_id: parseInt(loanId, 10), amount: amount })
      });
      if (r.success) {
        closeModal('modal-repay-loan');
        showToast('Repayment submitted successfully!');
        loadLoans();
        loadWallet();
      } else {
        msgEl.textContent = r.message || 'Repayment failed.';
        msgEl.style.display = '';
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = '';
        btn.querySelector('.btn-spinner').style.display = 'none';
      }
    } catch (e) {
      msgEl.textContent = 'Network error. Please try again.';
      msgEl.style.display = '';
      btn.disabled = false;
      btn.querySelector('.btn-text').style.display = '';
      btn.querySelector('.btn-spinner').style.display = 'none';
    }
  }

  // Expose for inline onclick handlers in table rows
  window.addFunds       = addFunds;
  window.submitAddFunds = submitAddFunds;
  window.repayLoan      = repayLoan;
  window.submitRepayLoan = submitRepayLoan;

  // ── Delete Account ────────────────────────────────────────────────────────────

  async function deleteAccount() {
    var btn   = document.getElementById('confirmDeleteBtn');
    var msgEl = document.getElementById('deleteMsg');

    if (btn) { btn.disabled = true; btn.textContent = 'Deleting…'; }

    try {
      var r = await apiFetch('/api/user-dashboard/profile.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'delete' })
      });

      if (r.success) {
        showToast('Account deleted. Redirecting…', 'info');
        setTimeout(function () {
          window.location.href = '/login';
        }, 1500);
      } else {
        if (msgEl) showMsg(msgEl, r.message || 'Deletion failed. Please contact support.', true);
      }
    } catch (e) {
      if (msgEl) showMsg(msgEl, 'Network error. Please try again.', true);
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="ph ph-trash"></i> Yes, Delete My Account Permanently'; }
    }
  }


  // ── Savings Plans ─────────────────────────────────────────────────────────────

  async function loadSavings() {
    try {
      var d = await apiFetch('/api/user-dashboard/savings.php');
      if (!d || d.error) return;

      setText('[data-stat="total-saved"]',       _currencySymbol + fmt(d.total_saved || 0));
      setText('[data-stat="locked-in-savings"]', _currencySymbol + fmt(d.total_saved || 0));
      setText('[data-stat="savings-count"]',      d.active_count || 0);

      var tbody = qs('[data-table="savings-plans"]');
      if (tbody) {
        if (d.plans && d.plans.length) {
          tbody.innerHTML = d.plans.map(function (p) {
            var pct = p.target_amount > 0
              ? Math.min(100, Math.round((p.current_amount / p.target_amount) * 100))
              : 0;
            return '<tr>'
              + '<td>' + p.plan_name + '</td>'
              + '<td>' + _currencySymbol + fmt(p.target_amount) + '</td>'
              + '<td>' + _currencySymbol + fmt(p.current_amount) + '</td>'
              + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td><div class="progress-bar"><div class="progress-fill" style="width:' + pct + '%"></div></div> ' + pct + '<i class="ph ph-percent"></i></td>'
              + '<td>' + (p.duration_months || '—') + ' mo</td>'
              + '<td>' + badge(p.status) + '</td>'
              + '<td><button class="btn-xs btn-outline" onclick="addFunds(' + p.id + ',\'' + (p.plan_name || '').replace(/'/g, "\\'") + '\')">Add Funds</button></td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="8" class="empty-row">No savings plans yet. Create your first plan above.</td></tr>';
        }
      }

      // Also update savings overview table on Dashboard
      var overviewTbody = qs('[data-table="savings-overview"]');
      if (overviewTbody) {
        if (d.plans && d.plans.length) {
          overviewTbody.innerHTML = d.plans.slice(0, 3).map(function (p) {
            var pct = p.target_amount > 0
              ? Math.min(100, Math.round((p.current_amount / p.target_amount) * 100))
              : 0;
            return '<tr>'
              + '<td>' + p.plan_name + '</td>'
              + '<td>' + _currencySymbol + fmt(p.target_amount) + '</td>'
              + '<td>' + _currencySymbol + fmt(p.current_amount) + '</td>'
              + '<td>' + (p.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td><div class="progress-bar"><div class="progress-fill" style="width:' + pct + '%"></div></div> ' + pct + '<i class="ph ph-percent"></i></td>'
              + '<td>' + badge(p.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          overviewTbody.innerHTML = '<tr><td colspan="6" class="empty-row">No active savings plans.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadSavings:', e);
    }
  }

  // ── Fixed Deposits ────────────────────────────────────────────────────────────

  async function loadDeposits() {
    try {
      var d = await apiFetch('/api/user-dashboard/deposits.php');
      if (!d || d.error) return;

      setText('[data-stat="total-deposited"]',       _currencySymbol + fmt(d.total_deposited || 0));
      setText('[data-stat="total-expected-return"]', _currencySymbol + fmt(d.total_expected_return || 0));

      var tbody = qs('[data-table="fixed-deposits"]');
      if (tbody) {
        if (d.deposits && d.deposits.length) {
          tbody.innerHTML = d.deposits.map(function (dep) {
            return '<tr>'
              + '<td>' + _currencySymbol + fmt(dep.amount) + '</td>'
              + '<td>' + (dep.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td>' + (dep.duration_months || '—') + ' mo</td>'
              + '<td>' + fmtDate(dep.start_date) + '</td>'
              + '<td>' + fmtDate(dep.maturity_date) + '</td>'
              + '<td>' + _currencySymbol + fmt(dep.expected_return) + '</td>'
              + '<td>' + badge(dep.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="empty-row">No fixed deposits yet. Open your first deposit above.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadDeposits:', e);
    }
  }

  // ── Loans ─────────────────────────────────────────────────────────────────────

  async function loadLoans() {
    try {
      var d = await apiFetch('/api/user-dashboard/loans.php');
      if (!d || d.error) return;

      setText('[data-stat="total-borrowed"]',    _currencySymbol + fmt(d.total_borrowed || 0));
      setText('[data-stat="remaining-balance"]', _currencySymbol + fmt(d.remaining_balance || 0));

      var activeTbody = qs('[data-table="active-loans"]');
      if (activeTbody) {
        if (d.active_loans && d.active_loans.length) {
          activeTbody.innerHTML = d.active_loans.map(function (l) {
            return '<tr>'
              + '<td>' + _currencySymbol + fmt(l.loan_amount) + '</td>'
              + '<td>' + _currencySymbol + fmt(l.remaining_balance) + '</td>'
              + '<td>' + _currencySymbol + fmt(l.monthly_payment) + '</td>'
              + '<td>' + (l.interest_rate || '—') + '<i class="ph ph-percent"></i>&thinsp;p.a.</td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td>' + badge(l.status) + '</td>'
              + '<td><button class="btn-xs btn-primary" onclick="repayLoan(' + l.id + ',' + l.remaining_balance + ',' + l.monthly_payment + ')">Repay</button></td>'
              + '</tr>';
          }).join('');
        } else {
          activeTbody.innerHTML = '<tr><td colspan="7" class="empty-row">No active loans.</td></tr>';
        }
      }

      var pendingTbody = qs('[data-table="pending-loans"]');
      if (pendingTbody) {
        if (d.pending_loans && d.pending_loans.length) {
          pendingTbody.innerHTML = d.pending_loans.map(function (l) {
            return '<tr>'
              + '<td>' + _currencySymbol + fmt(l.loan_amount) + '</td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td>' + fmtDate(l.created_at) + '</td>'
              + '<td>' + badge(l.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          pendingTbody.innerHTML = '<tr><td colspan="4" class="empty-row">No pending applications.</td></tr>';
        }
      }

      // Upcoming payments on overview
      var upcomingEl = qs('[data-list="upcoming-payments"]');
      if (upcomingEl) {
        if (d.active_loans && d.active_loans.length) {
          upcomingEl.innerHTML = d.active_loans.map(function (l) {
            return '<tr>'
              + '<td>' + (l.purpose || '—') + '</td>'
              + '<td><strong>' + _currencySymbol + fmt(l.monthly_payment) + '</strong></td>'
              + '<td>' + _currencySymbol + fmt(l.remaining_balance) + '</td>'
              + '<td>' + (l.duration_months || '—') + ' mo</td>'
              + '<td>' + badge(l.status) + '</td>'
              + '</tr>';
          }).join('');
        } else {
          upcomingEl.innerHTML = '<tr><td colspan="5" class="empty-row">No upcoming loan payments.</td></tr>';
        }
      }
    } catch (e) {
      console.error('loadLoans:', e);
    }
  }

  // ── Section Navigation ────────────────────────────────────────────────────────

  // Page titles for each section
  var sectionTitles = {
    overview:    'Dashboard',
    wallet:      'Wallet',
    profile:     'Profile',
    investments: 'Investments',
    commodities: 'Commodities',
    realestate:  'Real Estate'
  };

  // Section loaders — key matches data-nav and data-section values
  var sectionLoaders = {
    overview:    loadDashboard,
    wallet:      loadWallet,
    profile:     loadProfile,
    investments: loadInvestments,
    commodities: loadCommodities,
    realestate:  loadRealEstate
  };

  // Derive section name from current URL path
  function sectionFromPath() {
    var seg = location.pathname.split('/').filter(Boolean)[0] || '';
    if (!seg || seg === 'dashboard') return 'overview';
    return sectionLoaders[seg] ? seg : 'overview';
  }

  function activateSection(name, pushState) {
    // Show/hide sections
    document.querySelectorAll('[data-section]').forEach(function (el) {
      el.style.display = el.dataset.section === name ? 'block' : 'none';
    });

    // Scroll to top after DOM change so the browser measures the correct layout
    window.scrollTo({ top: 0, behavior: 'instant' });

    // Update active state on nav items (sidebar + mobile dock)
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.classList.toggle('active', el.dataset.nav === name);
    });

    // Update page title in header
    var titleEl = document.getElementById('pageTitle');
    if (titleEl) titleEl.textContent = sectionTitles[name] || name;

    // Update browser document title
    document.title = (sectionTitles[name] || name) + ' — Qblockx';

    // Push clean path URL  (/wallet, /savings, … — overview stays /dashboard)
    if (pushState !== false && history.pushState) {
      var url = (name === 'overview') ? '/dashboard' : '/' + name;
      history.pushState({ section: name }, '', url);
    }

    // Load data for this section
    if (sectionLoaders[name]) sectionLoaders[name]();

    // Restart both background timers for the new section
    startBackgroundRefresh(name);
  }

  // ── Init ──────────────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {

    // ── Browser back / forward ─────────────────────────────────────────────
    window.addEventListener('popstate', function (e) {
      var section = (e.state && e.state.section) ? e.state.section : sectionFromPath();
      activateSection(section, false); // false = don't push again
    });

    // ── Nav: sidebar + mobile dock link clicks ──────────────────────────────
    document.querySelectorAll('[data-nav]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        activateSection(this.dataset.nav);
      });
    });

    // ── Global form submit delegation ──────────────────────────────────────
    document.addEventListener('submit', function (e) {
      var form   = e.target;
      var action = form.dataset.action;
      if (!action) return;
      e.preventDefault();

      switch (action) {
        case 'deposit':           initiateDeposit(form);         break;
        case 'withdraw':          submitWithdrawal(form);        break;
        case 'transfer':          submitTransfer(form);          break;
        case 'create-savings':    submitCreateSavings(form);     break;
        case 'fixed-deposit':     submitFixedDeposit(form);      break;
        case 'loan-application':  submitLoanApplication(form);   break;
        case 'invest':            submitTradeInvestment(form);   break;
        // legacy
        case 'submit-deposit':    submitFixedDeposit(form);      break;
        case 'submit-loan':       submitLoanApplication(form);   break;
        case 'update-profile':    updateProfile(form);           break;
      }
    });

    // ── Rates tabs ────────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
      var tab = e.target.closest('.rates-tab');
      if (!tab) return;
      _ratesFilter = tab.dataset.ratesFilter;
      document.querySelectorAll('.rates-tab').forEach(function (t) {
        t.classList.toggle('active', t.dataset.ratesFilter === _ratesFilter);
      });
      renderRates(_rates, _ratesFilter);
    });

    // ── Live calc: input and change events ────────────────────────────────
    document.addEventListener('input', function (e) {
      var id = e.target && e.target.id;
      if (id === 'savingsTargetAmount') updateSavingsCalc();
      if (id === 'fdAmount')            updateFdCalc();
      if (id === 'loanAmountInput')     updateLoanCalc();
    });
    document.addEventListener('change', function (e) {
      var id = e.target && e.target.id;
      if (id === 'savingsDuration') updateSavingsCalc();
      if (id === 'fdPlan')          updateFdCalc();
      if (id === 'loanPlan')        updateLoanCalc();
    });

    // ── Balance visibility toggle ──────────────────────────────────────────
    var balanceToggle = document.getElementById('balanceToggle');
    if (balanceToggle) {
      balanceToggle.addEventListener('click', function () {
        var hidden = localStorage.getItem('balanceHidden') === '1';
        localStorage.setItem('balanceHidden', hidden ? '0' : '1');
        applyBalanceHidden(!hidden);
      });
    }

    // ── Delete account button ──────────────────────────────────────────────
    var deleteBtn = document.getElementById('confirmDeleteBtn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', deleteAccount);
    }

    // ── Global delegated click: [data-copy-text] buttons ──────────────────
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-copy-text]');
      if (!btn || !btn.dataset.copyText) return;
      copyText(btn.dataset.copyText, function (ok) {
        if (!ok) return;
        var orig = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(function () { btn.textContent = orig; }, 2000);
      });
    });

    // ── Modal: close on overlay background click ───────────────────────────
    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('modal-overlay')) {
        closeAllModals();
      }
    });

    // ── Modal: close on ESC key ────────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAllModals();
    });

    // ── Seed currency from cache so symbol is correct before first fetch ──
    try {
      var _cachedCurrency = localStorage.getItem('cv_currency');
      if (_cachedCurrency) initCurrency(_cachedCurrency);
    } catch(e) {}

    // ── Load section from URL path, default to overview ───────────────────
    activateSection(sectionFromPath(), false); // false = path already correct, don't re-push

    // ── NOWPayments: poll for pending payment on return from invoice page ──
    try {
      var pendingInvoice = sessionStorage.getItem('np_invoice_id');
      if (pendingInvoice) {
        // Small delay so wallet section is loaded first
        setTimeout(function () { checkPendingPayment(pendingInvoice, 0); }, 2000);
      }
    } catch(e) {}

  });

})();
