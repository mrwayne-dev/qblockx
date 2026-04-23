<?php
/**
 * Project: qblockx
 * Modal: Add Funds to Savings Plan
 *
 * Opened by addFunds(planId, planName) in user-dashboard.js
 * Submits via submitAddFunds() → POST /api/user-dashboard/savings.php {action:"add_funds"}
 */
?>

<div id="modal-add-funds" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="addFundsModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="addFundsModalTitle">
        <i class="ph ph-piggy-bank" aria-hidden="true"></i>
        Add Funds to Plan
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-add-funds')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <input type="hidden" id="addFundsPlanId" value="">

      <p id="addFundsPlanLabel" style="margin:0 0 1.1rem;font-size:.9rem;color:var(--text-muted);">
        Plan: —
      </p>

      <div class="form-group">
        <label for="addFundsAmount">Amount to Add (<span class="js-currency-code">USD</span>)</label>
        <input type="number" id="addFundsAmount" min="1" step="1"
               placeholder="e.g. 100" inputmode="decimal" autocomplete="off">
      </div>

      <div id="addFundsMsg" class="form-message" style="display:none;"></div>

      <button type="button" class="btn-primary" id="addFundsBtn" onclick="submitAddFunds()">
        <span class="btn-text">
          <i class="ph ph-plus-circle" aria-hidden="true"></i>
          Add Funds
        </span>
        <span class="btn-spinner" style="display:none;">
          <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
        </span>
      </button>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-add-funds -->
