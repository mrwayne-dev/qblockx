<?php
/**
 * Project: crestvalebank
 * Modal: Create Savings Plan
 *
 * Duration select populated from rates (savings product) by JS.
 * Live calculation shows projected interest and total at maturity
 * based on target amount as user types.
 * On submit: POST /api/user-dashboard/savings.php {action:"create", plan_name, duration_months, target_amount, interest_rate}
 *
 * data-action="create-savings" — handled by form submit delegation in user-dashboard.js
 */
?>

<div id="modal-create-savings" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="createSavingsModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="createSavingsModalTitle">
        <i class="ph ph-piggy-bank" aria-hidden="true"></i>
        Create Savings Plan
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-create-savings')" aria-label="Close savings modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <form data-action="create-savings" novalidate>

        <div class="form-group">
          <label for="savingsPlanName">Plan Name</label>
          <input type="text" id="savingsPlanName" name="plan_name"
                 placeholder="e.g. Emergency Fund"
                 maxlength="100" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="savingsDuration">Savings Plan</label>
          <select id="savingsDuration" name="duration_months" required>
            <option value="" disabled selected>Loading plans…</option>
          </select>
        </div>

        <div class="form-group">
          <label for="savingsTargetAmount">Target Amount (USD)</label>
          <input type="number" id="savingsTargetAmount" name="target_amount"
                 min="10" step="0.01" placeholder="0.00"
                 inputmode="decimal" autocomplete="off" required>
        </div>

        <input type="hidden" id="savingsInterestRate" name="interest_rate" value="">

        <!-- Live Calculation Preview -->
        <div class="calc-preview" id="savingsCalcPreview" style="display:none;">
          <div class="calc-preview-header">
            <i class="ph ph-calculator" aria-hidden="true"></i>
            Projected Returns
          </div>
          <div class="calc-row">
            <span>Target Amount</span>
            <strong id="savingsCalcPrincipal">$0.00</strong>
          </div>
          <div class="calc-row">
            <span>Interest Rate</span>
            <strong id="savingsCalcRate">—</strong>
          </div>
          <div class="calc-row">
            <span>Duration</span>
            <strong id="savingsCalcDuration">—</strong>
          </div>
          <div class="calc-row">
            <span>Interest Earned</span>
            <strong id="savingsCalcInterest" class="calc-positive">+$0.00</strong>
          </div>
          <div class="calc-row calc-row--total">
            <span>Total at Maturity</span>
            <strong id="savingsCalcTotal">$0.00</strong>
          </div>
        </div>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary" id="createSavingsBtn">
          <span class="btn-text">
            <i class="ph ph-check-circle" aria-hidden="true"></i>
            Create Plan
          </span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-create-savings -->
