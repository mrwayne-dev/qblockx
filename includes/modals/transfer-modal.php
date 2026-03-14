<?php
/**
 * Project: crestvalebank
 * Modal: Transfer Funds — internal CrestVale-to-CrestVale transfer
 *
 * User enters recipient email + amount → JS POSTs to wallet.php with
 * action=transfer → funds deducted from sender, credited to recipient,
 * dual transaction records created.
 *
 * data-action="transfer" — handled by form submit delegation in user-dashboard.js
 */
?>

<div id="modal-transfer" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="transferModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="transferModalTitle">
        <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
        Transfer Funds
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-transfer')" aria-label="Close transfer modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <p class="modal-note">
        Send money instantly to any verified CrestVale Bank account holder using their registered email address.
      </p>

      <form data-action="transfer" novalidate>

        <div class="form-group">
          <label for="transferEmail">Recipient Email</label>
          <input type="email" id="transferEmail" name="recipient_email"
                 placeholder="recipient@example.com"
                 inputmode="email" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="transferAmount">Amount (USD)</label>
          <input type="number" id="transferAmount" name="amount"
                 min="1" step="0.01" placeholder="0.00"
                 inputmode="decimal" autocomplete="off" required>
        </div>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary" id="transferBtn">
          <span class="btn-text">
            <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
            Send Transfer
          </span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-transfer -->
