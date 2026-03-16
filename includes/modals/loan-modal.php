<?php
/**
 * Project: crestvalebank
 * Modal: Apply for a Loan
 *
 * User selects a duration plan and enters amount.
 * Live calculation shows monthly payment, total interest and total repayable.
 * On submit: POST /api/user-dashboard/loans.php {action:"apply", loan_amount, duration_months, purpose}
 *
 * data-action="loan-application" — handled by form submit delegation in user-dashboard.js
 */
?>

<div id="modal-loan" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="loanModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="loanModalTitle">
        <i class="ph ph-hand-coins" aria-hidden="true"></i>
        Apply for a Loan
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-loan')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <form data-action="loan-application" novalidate>

        <div class="form-group">
          <label for="loanPlan">Loan Duration</label>
          <select id="loanPlan" name="duration_months" required>
            <option value="" disabled selected>Loading plans…</option>
          </select>
        </div>

        <div class="form-group">
          <label for="loanAmountInput">Loan Amount (<span class="js-currency-code">USD</span>)</label>
          <input type="number" id="loanAmountInput" name="loan_amount"
                 min="100" step="0.01" placeholder="Minimum 100"
                 inputmode="decimal" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="loanPurposeInput">Purpose <span class="form-optional">(optional)</span></label>
          <input type="text" id="loanPurposeInput" name="purpose"
                 placeholder="e.g. Home renovation, Medical bills"
                 maxlength="255" autocomplete="off">
        </div>

        <!-- Live Calculation Preview -->
        <div class="calc-preview" id="loanCalcPreview" style="display:none;">
          <div class="calc-preview-header">
            <i class="ph ph-calculator" aria-hidden="true"></i>
            Repayment Breakdown
          </div>
          <div class="calc-row">
            <span>Loan Amount</span>
            <strong id="loanCalcPrincipal">0.00</strong>
          </div>
          <div class="calc-row">
            <span>Monthly Payment</span>
            <strong id="loanCalcMonthly" class="calc-highlight">0.00</strong>
          </div>
          <div class="calc-row">
            <span>Duration</span>
            <strong id="loanCalcDuration">—</strong>
          </div>
          <div class="calc-row">
            <span>Interest Rate p.a.</span>
            <strong id="loanCalcRate">—</strong>
          </div>
          <div class="calc-row">
            <span>Total Interest</span>
            <strong id="loanCalcInterest" class="calc-warn">0.00</strong>
          </div>
          <div class="calc-row calc-row--total">
            <span>Total Repayable</span>
            <strong id="loanCalcTotal">0.00</strong>
          </div>
        </div>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary" id="loanApplicationBtn">
          <span class="btn-text">
            <i class="ph ph-paper-plane-tilt" aria-hidden="true"></i>
            Submit Application
          </span>
          <span class="btn-spinner" style="display:none;">
            <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
          </span>
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-loan -->
