<?php
/**
 * Project: Qblockx
 * Modal: Invest in Plan — select-based pattern matching commodity/realestate modals
 * Plans loaded dynamically by user-dashboard.js from /api/user-dashboard/investments.php
 */
?>

<div class="modal-overlay" id="modal-invest-plan" aria-hidden="true" role="dialog"
     aria-labelledby="investPlanTitle" aria-modal="true">
  <div class="modal-card">

    <div class="modal-header">
      <h2 class="modal-title" id="investPlanTitle">
        <i class="ph ph-chart-line-up" aria-hidden="true"></i>
        Investment Plans
      </h2>
      <button class="modal-close" onclick="closeModal('modal-invest-plan')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="modal-body">

      <!-- Available balance indicator -->
      <div class="modal-balance-bar">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Available balance: <strong id="investPlanBalance">—</strong>
      </div>

      <div class="form-group">
        <label for="investPlanSelect">Select Plan</label>
        <select id="investPlanSelect" class="form-select" onchange="onInvestPlanChange()">
          <option value="">— Choose a plan —</option>
        </select>
      </div>

      <!-- Plan info bar — shown after selection -->
      <div id="investPlanInfo" class="invest-info-bar" style="display:none;">
        <div class="invest-info-item">
          <span class="invest-info-label">Duration</span>
          <span class="invest-info-value" id="planInfoDuration">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">APY Range</span>
          <span class="invest-info-value" id="planInfoYield">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Min. Investment</span>
          <span class="invest-info-value" id="planInfoMin">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Tier</span>
          <span class="invest-info-value" id="planInfoTier">—</span>
        </div>
      </div>

      <div class="form-group" id="investPlanAmountGroup" style="display:none;">
        <label for="investPlanAmount">Amount (USD)</label>
        <div class="input-wrapper">
          <i class="ph ph-currency-dollar input-icon" aria-hidden="true"></i>
          <input type="number" id="investPlanAmount" class="has-icon"
                 placeholder="Enter amount" min="0" step="0.01">
        </div>
        <span class="input-hint" id="investPlanAmountHint"></span>
      </div>

      <div id="investPlanMsg" class="auth-msg" style="display:none;" role="alert"></div>

      <div class="modal-actions">
        <button type="button" class="btn-outline"
                onclick="closeModal('modal-invest-plan')">Cancel</button>
        <button type="button" class="btn-primary" id="investPlanBtn"
                onclick="submitPlanInvestment()" disabled>
          <span class="btn-text">Confirm Investment</span>
          <span class="btn-spinner" style="display:none;"></span>
        </button>
      </div>

    </div>
  </div>
</div>
