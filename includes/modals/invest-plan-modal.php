<?php
/**
 * Project: Qblockx
 * Modal: Invest in Plan — two-tab (Starter / Elite) plan selector
 * Data loaded dynamically by user-dashboard.js from /api/user-dashboard/investments.php
 */
?>

<div class="modal-overlay" id="modal-invest-plan" aria-hidden="true" role="dialog"
     aria-labelledby="investPlanTitle" aria-modal="true">
  <div class="modal-card modal-card--lg">

    <div class="modal-header">
      <h2 class="modal-title" id="investPlanTitle">
        <i class="ph ph-chart-line-up" aria-hidden="true"></i>
        Investment Plans
      </h2>
      <button class="modal-close" onclick="closeModal('modal-invest-plan')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <!-- Tier tabs -->
    <div class="modal-tabs" id="planTierTabs">
      <button class="modal-tab active" data-plan-tier="starter" type="button">
        Starter Plans
      </button>
      <button class="modal-tab" data-plan-tier="elite" type="button">
        Elite Plans
      </button>
    </div>

    <div class="modal-body">

      <!-- Plan cards grid — populated by JS -->
      <div class="plan-cards-grid" id="planCardsGrid">
        <p class="empty-text">Loading plans…</p>
      </div>

      <!-- Amount input — shown after selecting a plan card -->
      <div id="planInvestForm" style="display:none;">
        <div class="modal-divider"></div>
        <p class="invest-selected-label">
          Investing in: <strong id="selectedPlanName">—</strong>
        </p>
        <div class="form-group">
          <label for="planInvestAmount">Amount (USD)</label>
          <div class="input-wrapper">
            <i class="ph ph-currency-dollar input-icon" aria-hidden="true"></i>
            <input type="number" id="planInvestAmount" class="has-icon"
                   placeholder="Enter amount" min="0" step="0.01">
          </div>
          <span class="input-hint" id="planAmountHint"></span>
        </div>
        <div id="planInvestMsg" class="auth-msg" style="display:none;" role="alert"></div>
        <div class="modal-actions">
          <button type="button" class="btn-sm btn-outline"
                  onclick="document.getElementById('planInvestForm').style.display='none'">
            Back to Plans
          </button>
          <button type="button" class="btn-primary" id="planInvestBtn"
                  onclick="submitPlanInvestment()">
            <span class="btn-text">Confirm Investment</span>
            <span class="btn-spinner" style="display:none;"></span>
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
