<?php
/**
 * Project: qblockx
 * Modal: Record Loan Repayment
 *
 * Admin enters repayment amount for a specific loan.
 * → POST /api/admin-dashboard/loans.php
 *   { action: "record_repayment", id: loan_id, amount }
 *
 * Reduces remaining_balance; closes loan if balance reaches 0.
 * Creates a loan_repayment transaction record.
 */
?>

<div id="modal-record-repayment" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="repaymentModalTitle">
  <div class="admin-modal">

    <div class="admin-modal-header">
      <h2 class="admin-modal-title" id="repaymentModalTitle">
        <i class="ph ph-hand-coins" aria-hidden="true"></i>
        Record Loan Repayment
      </h2>
      <button class="admin-modal-close" data-close-modal="modal-record-repayment" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="admin-modal-body">

      <input type="hidden" id="repayLoanId" value="">

      <div class="admin-modal-info-row" id="repayLoanInfo" style="
        background:var(--color-surface-2,rgba(255,255,255,0.04));
        border:1px solid var(--color-border);
        border-radius:8px;
        padding:0.75rem 1rem;
        margin-bottom:1rem;
        font-size:0.85rem;
        color:var(--color-text-muted);
      ">
        Loading…
      </div>

      <div class="admin-form-group">
        <label for="repayAmount">Repayment Amount (USD)</label>
        <input id="repayAmount" class="admin-input" type="number"
               min="0.01" step="0.01" placeholder="0.00">
      </div>

      <div id="repayMsg" class="admin-modal-msg" style="display:none;"></div>

    </div><!-- .admin-modal-body -->

    <div class="admin-modal-footer">
      <button class="btn-sm" data-close-modal="modal-record-repayment" type="button">Cancel</button>
      <button class="btn-sm btn-accent" onclick="submitAdminRepayment()" type="button" id="repaySubmitBtn">
        <i class="ph ph-check"></i> Record Payment
      </button>
    </div>

  </div><!-- .admin-modal -->
</div><!-- #modal-record-repayment -->
