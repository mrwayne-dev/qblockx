<?php
/**
 * Project: crestvalebank
 * Modal: Open Fixed Deposit
 *
 * User selects a plan (duration) and enters amount.
 * Live calculation shows expected interest and total return.
 * On submit: POST /api/user-dashboard/deposits.php {action:"create", amount, duration_months}
 *
 * data-action="fixed-deposit" — handled by form submit delegation in user-dashboard.js
 */
?>

<div id="modal-fixed-deposit" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="fixedDepositModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="fixedDepositModalTitle">
        <i class="ph ph-vault" aria-hidden="true"></i>
        Open Fixed Deposit
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-fixed-deposit')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <form data-action="fixed-deposit" novalidate>

        <div class="form-group">
          <label for="fdPlan">Select Plan</label>
          <select id="fdPlan" name="duration_months" required>
            <option value="" disabled selected>Loading plans…</option>
          </select>
        </div>

        <div class="form-group">
          <label for="fdAmount">Deposit Amount (<span class="js-currency-code">USD</span>)</label>
          <input type="number" id="fdAmount" name="amount"
                 min="100" step="0.01" placeholder="Minimum 100"
                 inputmode="decimal" autocomplete="off" required>
        </div>

        <!-- Live Calculation Preview -->
        <div class="calc-preview" id="fdCalcPreview" style="display:none;">
          <div class="calc-preview-header">
            <i class="ph ph-calculator" aria-hidden="true"></i>
            Return Projection
          </div>
          <div class="calc-row">
            <span>Principal</span>
            <strong id="fdCalcPrincipal">0.00</strong>
          </div>
          <div class="calc-row">
            <span>Interest Earned</span>
            <strong id="fdCalcInterest" class="calc-positive">+0.00</strong>
          </div>
          <div class="calc-row">
            <span>Duration</span>
            <strong id="fdCalcDuration">—</strong>
          </div>
          <div class="calc-row">
            <span>Rate p.a.</span>
            <strong id="fdCalcRate">—</strong>
          </div>
          <div class="calc-row calc-row--total">
            <span>Total at Maturity</span>
            <strong id="fdCalcTotal">0.00</strong>
          </div>
          <div class="calc-row">
            <span>Maturity Date</span>
            <strong id="fdCalcMaturity">—</strong>
          </div>
        </div>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary" id="fixedDepositBtn">
          <span class="btn-text">
            <i class="ph ph-vault" aria-hidden="true"></i>
            Open Deposit
          </span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-fixed-deposit -->
