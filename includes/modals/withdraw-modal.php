<?php
/**
 * Project: crestvalebank
 * Modal: Withdraw — request a withdrawal to a crypto address
 *
 * data-action="withdraw" — handled by submitWithdrawal() in user-dashboard.js
 * Submitted to: POST /api/user-dashboard/wallet.php
 * Fields: amount, currency, wallet_address
 */
?>

<div id="modal-withdraw" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="withdrawModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="withdrawModalTitle">
        <i class="ph ph-arrow-circle-up" aria-hidden="true"></i>
        Withdraw Funds
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-withdraw')" aria-label="Close withdrawal modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <div class="modal-warning">
        <i class="ph ph-clock" aria-hidden="true"></i>
        <span>Withdrawals are processed within <strong>24–48 hours</strong> after admin review.</span>
      </div>

      <form data-action="withdraw" novalidate>

        <div class="form-group">
          <label for="withdrawAmount">Amount (USD)</label>
          <input type="number" id="withdrawAmount" name="amount"
                 min="10" step="1" placeholder="e.g. 200"
                 inputmode="decimal" autocomplete="off">
        </div>

        <div class="form-group">
          <label for="withdrawCurrency">Withdraw in</label>
          <select id="withdrawCurrency" name="currency">
            <option value="usdttrc20" selected>USDT (TRC-20)</option>
            <option value="usdterc20">USDT (ERC-20)</option>
            <option value="btc">Bitcoin (BTC)</option>
            <option value="eth">Ethereum (ETH)</option>
            <option value="bnbbsc">BNB (BSC)</option>
          </select>
        </div>

        <div class="form-group">
          <label for="withdrawAddress">Destination Wallet Address</label>
          <textarea id="withdrawAddress" name="wallet_address"
                    placeholder="Paste your crypto wallet address here"
                    rows="3" autocomplete="off" spellcheck="false"></textarea>
        </div>

        <p class="modal-note">
          Ensure the wallet address matches the selected currency. Funds sent to the wrong address cannot be recovered.
        </p>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary">
          <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
          Request Withdrawal
        </button>

        <button type="button" class="btn-outline" onclick="closeModal('modal-withdraw')">
          Cancel
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-withdraw -->
