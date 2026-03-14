<?php
/**
 * Project: crestvalebank
 * Modal: Withdraw — crypto wallet or bank transfer
 *
 * data-action="withdraw" — handled by submitWithdrawal() in user-dashboard.js
 * Submitted to: POST /api/user-dashboard/wallet.php
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
        <span>Withdrawals are processed within <strong>24–48 hours</strong> by Crestvale Bank.</span>
      </div>

      <!-- Method Tabs -->
      <style>
        .withdraw-tabs { display:flex; gap:.5rem; margin-bottom:1.25rem; }
        .withdraw-tab {
          flex:1; padding:.65rem .5rem; border-radius:8px; font-weight:600;
          cursor:pointer; font-size:.88rem; border:1.5px solid var(--border);
          background:transparent; color:var(--text-muted);
          transition:background .18s, color .18s, border-color .18s;
          display:flex; align-items:center; justify-content:center; gap:.4rem;
        }
        .withdraw-tab:hover { border-color:var(--accent); color:var(--accent); }
        .withdraw-tab.active {
          background:var(--accent); border-color:var(--accent);
          color:#fff; box-shadow:0 2px 12px rgba(63,224,161,.25);
        }
        .withdraw-tab .ph { font-size:1rem; }
      </style>
      <div class="withdraw-tabs">
        <button type="button" class="withdraw-tab active" data-withdraw-tab="crypto">
          <i class="ph ph-currency-circle-dollar"></i> Crypto Wallet
        </button>
        <button type="button" class="withdraw-tab" data-withdraw-tab="bank">
          <i class="ph ph-bank"></i> Bank Transfer
        </button>
      </div>

      <form data-action="withdraw" novalidate>
        <input type="hidden" name="withdrawal_method" value="crypto">

        <!-- Amount — shared -->
        <div class="form-group">
          <label for="withdrawAmount">Amount (USD)</label>
          <input type="number" id="withdrawAmount" name="amount"
                 min="10" step="1" placeholder="e.g. 200"
                 inputmode="decimal" autocomplete="off">
          <p id="withdrawFeeNote" style="display:none;margin:.4rem 0 0;font-size:.82rem;color:var(--text-muted);"></p>
        </div>

        <!-- ── Crypto section ──────────────────────────────────── -->
        <div id="withdrawCryptoSection">

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

        </div><!-- /#withdrawCryptoSection -->

        <!-- ── Bank section ────────────────────────────────────── -->
        <div id="withdrawBankSection" style="display:none;">

          <div class="form-group">
            <label for="withdrawBankCountry">Country</label>
            <select id="withdrawBankCountry" name="bank_country">
              <option value="">Select Country</option>
              <option>United States</option>
              <option>Germany</option>
              <option>France</option>
              <option>United Kingdom</option>
              <option>Italy</option>
              <option>Spain</option>
              <option>Netherlands</option>
              <option>Sweden</option>
              <option>Switzerland</option>
              <option>Poland</option>
              <option>Austria</option>
              <option>Greece</option>
              <option>Portugal</option>
              <option>Norway</option>
              <option>Denmark</option>
              <option>Belgium</option>
              <option>Finland</option>
              <option>Ireland</option>
              <option>Czech Republic</option>
              <option>Hungary</option>
              <option>Ukraine</option>
            </select>
          </div>

          <div class="form-group">
            <label for="withdrawBankName">Bank</label>
            <select id="withdrawBankName" name="bank_name" disabled>
              <option value="">Select a country first</option>
            </select>
          </div>

          <div class="form-group">
            <label for="withdrawAccountHolder">Account Holder Name</label>
            <input type="text" id="withdrawAccountHolder" name="account_holder_name"
                   placeholder="Full Name" autocomplete="name">
          </div>

          <div class="form-group">
            <label for="withdrawIban">IBAN</label>
            <input type="text" id="withdrawIban" name="iban"
                   placeholder="e.g. DE89370400440532013000"
                   autocomplete="off" spellcheck="false" style="font-family:monospace;">
          </div>

          <div class="form-group">
            <label for="withdrawBic">BIC/SWIFT Code</label>
            <input type="text" id="withdrawBic" name="bic_swift"
                   placeholder="e.g. DEUTDEFFXXX"
                   autocomplete="off" spellcheck="false" style="font-family:monospace;">
          </div>

          <div class="form-group" id="withdrawSortCodeGroup" style="display:none;">
            <label for="withdrawSortCode">Sort Code (UK only)</label>
            <input type="text" id="withdrawSortCode" name="sort_code"
                   placeholder="e.g. 12-34-56" autocomplete="off">
          </div>

          <div class="form-group">
            <label for="withdrawBankCurrency">Currency</label>
            <input type="text" id="withdrawBankCurrency" name="bank_currency"
                   value="EUR" placeholder="EUR" style="text-transform:uppercase;">
          </div>

          <div class="form-group">
            <label for="withdrawTxRef">Transaction Reference <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label>
            <input type="text" id="withdrawTxRef" name="transaction_reference"
                   placeholder="e.g. Withdrawal July 2025" autocomplete="off">
          </div>

          <p class="modal-note">
            Local bank conversions will be done based on the currency selected.
          </p>

        </div><!-- /#withdrawBankSection -->

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
