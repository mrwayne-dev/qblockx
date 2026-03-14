<?php
/**
 * Project: crestvalebank
 * Modal: Add Interest Rate
 *
 * Admin fills product/label/duration/rate → POST /api/admin-dashboard/settings.php
 * { action: "add_rate", product, label, duration_months, rate }
 */
?>

<div id="modal-add-rate" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="addRateModalTitle">
  <div class="admin-modal">

    <div class="admin-modal-header">
      <h2 class="admin-modal-title" id="addRateModalTitle">
        <i class="ph ph-plus-circle" aria-hidden="true"></i>
        Add Interest Rate
      </h2>
      <button class="admin-modal-close" data-close-modal="modal-add-rate" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="admin-modal-body">

      <div class="admin-form-group">
        <label for="addRateProduct">Product</label>
        <select id="addRateProduct" class="admin-input">
          <option value="savings">Savings</option>
          <option value="fixed_deposit">Fixed Deposit</option>
          <option value="loan">Loan</option>
        </select>
      </div>

      <div class="admin-form-group">
        <label for="addRateLabel">Label</label>
        <input id="addRateLabel" class="admin-input" type="text"
               placeholder="e.g. Standard Savings" maxlength="100" autocomplete="off">
      </div>

      <div class="admin-form-group">
        <label for="addRateDuration">Duration (months)</label>
        <input id="addRateDuration" class="admin-input" type="number"
               min="1" max="360" placeholder="12">
      </div>

      <div class="admin-form-group">
        <label for="addRateValue">Rate (% p.a.)</label>
        <input id="addRateValue" class="admin-input" type="number"
               min="0" max="100" step="0.01" placeholder="5.00">
      </div>

      <div id="addRateMsg" class="admin-modal-msg" style="display:none;"></div>

    </div><!-- .admin-modal-body -->

    <div class="admin-modal-footer">
      <button class="btn-sm" data-close-modal="modal-add-rate" type="button">Cancel</button>
      <button class="btn-sm btn-accent" onclick="saveNewRate()" type="button">
        <i class="ph ph-check"></i> Add Rate
      </button>
    </div>

  </div><!-- .admin-modal -->
</div><!-- #modal-add-rate -->
