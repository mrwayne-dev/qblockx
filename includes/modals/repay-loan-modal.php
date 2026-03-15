<?php
/**
 * Project: crestvalebank
 * Modal: Loan Repayment
 *
 * Opened by repayLoan(loanId, outstanding, monthly) in user-dashboard.js
 * Submits via submitRepayLoan() → POST /api/user-dashboard/loans.php {action:"repay"}
 */
?>

<div id="modal-repay-loan" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="repayLoanModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="repayLoanModalTitle">
        <i class="ph ph-bank" aria-hidden="true"></i>
        Make Repayment
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-repay-loan')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <input type="hidden" id="repayLoanId" value="">

      <div class="calc-preview" style="margin-bottom:1.1rem;">
        <div class="calc-row">
          <span>Outstanding Balance</span>
          <strong id="repayOutstanding">$0.00</strong>
        </div>
        <div class="calc-row">
          <span>Monthly Payment</span>
          <strong id="repayMonthly">$0.00</strong>
        </div>
      </div>

      <div class="form-group">
        <label for="repayAmount">Repayment Amount (USD)</label>
        <input type="number" id="repayAmount" min="1" step="0.01"
               placeholder="e.g. 250" inputmode="decimal" autocomplete="off">
      </div>

      <div id="repayLoanMsg" class="form-message" style="display:none;"></div>

      <button type="button" class="btn-primary" id="repayLoanBtn" onclick="submitRepayLoan()">
        <span class="btn-text">
          <i class="ph ph-check-circle" aria-hidden="true"></i>
          Submit Repayment
        </span>
        <span class="btn-spinner" style="display:none;">
          <i class="ph ph-circle-notch ph-spin" aria-hidden="true"></i>
        </span>
      </button>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-repay-loan -->
