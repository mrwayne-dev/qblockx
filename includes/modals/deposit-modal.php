<?php
/**
 * Project: arqoracapital
 * Modal: Deposit — NOWPayments hosted invoice flow
 *
 * User enters amount → JS calls now-payment-initiate.php → receives
 * invoice_url → browser is redirected to NOWPayments hosted payment page.
 * After payment, NOWPayments redirects back to the wallet page and fires
 * the IPN webhook which credits the wallet automatically.
 *
 * data-action="deposit" — handled by initiateDeposit() in user-dashboard.js
 */
?>

<div id="modal-deposit" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="depositModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="depositModalTitle">
        <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
        Deposit Funds
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-deposit')" aria-label="Close deposit modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <form data-action="deposit" novalidate>

        <div class="form-group">
          <label for="depositCurrency">Cryptocurrency</label>
          <select id="depositCurrency" name="currency">
            <option value="btc">Bitcoin (BTC)</option>
            <option value="eth">Ethereum (ETH)</option>
            <option value="usdttrc20" selected>USDT (TRC-20)</option>
            <option value="usdterc20">USDT (ERC-20)</option>
            <option value="bnbbsc">BNB (BSC)</option>
          </select>
        </div>

        <div class="form-group">
          <label for="depositAmount">Amount (USD)</label>
          <input type="number" id="depositAmount" name="amount"
                 min="100" step="1" placeholder="e.g. 500"
                 inputmode="decimal" autocomplete="off">
        </div>

        <p class="modal-note">
          Minimum deposit: <strong>$100</strong>.
          You will be redirected to a secure payment page to complete the payment in your chosen cryptocurrency.
        </p>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary">
          <i class="ph ph-arrow-right" aria-hidden="true"></i>
          Continue to Payment
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-deposit -->
