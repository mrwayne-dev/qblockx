<?php
/**
 * Project: Qblockx
 * Modal: Invest in Real Estate — pool selector + amount input
 */
?>

<div class="modal-overlay" id="modal-invest-realestate" aria-hidden="true" role="dialog"
     aria-labelledby="investRETitle" aria-modal="true">
  <div class="modal-card">

    <div class="modal-header">
      <h2 class="modal-title" id="investRETitle">
        <i class="ph ph-buildings" aria-hidden="true"></i>
        Real Estate Investment
      </h2>
      <button class="modal-close" onclick="closeModal('modal-invest-realestate')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="modal-body">

      <!-- Available balance indicator -->
      <div class="modal-balance-bar">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Available balance: <strong id="reBalance">—</strong>
      </div>

      <div class="form-group">
        <label for="rePoolSelect">Select Property Pool</label>
        <select id="rePoolSelect" class="form-select" onchange="onREPoolChange()">
          <option value="">— Choose a pool —</option>
        </select>
      </div>

      <!-- Pool info bar -->
      <div id="rePoolInfo" class="invest-info-bar" style="display:none;">
        <div class="invest-info-item">
          <span class="invest-info-label">Duration</span>
          <span class="invest-info-value" id="reDuration">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Return Range</span>
          <span class="invest-info-value" id="reYield">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Payout</span>
          <span class="invest-info-value" id="rePayoutFreq">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Min. Investment</span>
          <span class="invest-info-value" id="reMin">—</span>
        </div>
      </div>

      <div class="form-group" id="reAmountGroup" style="display:none;">
        <label for="reInvestAmount">Amount (USD)</label>
        <div class="input-wrapper">
          <i class="ph ph-currency-dollar input-icon" aria-hidden="true"></i>
          <input type="number" id="reInvestAmount" class="has-icon"
                 placeholder="Enter amount" min="0" step="0.01">
        </div>
        <span class="input-hint" id="reAmountHint"></span>
      </div>

      <div id="reInvestMsg" class="auth-msg" style="display:none;" role="alert"></div>

      <div class="modal-actions">
        <button type="button" class="btn-outline"
                onclick="closeModal('modal-invest-realestate')">Cancel</button>
        <button type="button" class="btn-primary" id="reInvestBtn"
                onclick="submitREInvestment()" disabled>
          <span class="btn-text">Confirm Investment</span>
          <span class="btn-spinner" style="display:none;"></span>
        </button>
      </div>

    </div>
  </div>
</div>
