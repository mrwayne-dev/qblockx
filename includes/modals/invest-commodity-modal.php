<?php
/**
 * Project: Qblockx
 * Modal: Invest in Commodity — asset selector + amount input
 */
?>

<div class="modal-overlay" id="modal-invest-commodity" aria-hidden="true" role="dialog"
     aria-labelledby="investCommodityTitle" aria-modal="true">
  <div class="modal-card">

    <div class="modal-header">
      <h2 class="modal-title" id="investCommodityTitle">
        <i class="ph ph-currency-dollar" aria-hidden="true"></i>
        Commodity Investment
      </h2>
      <button class="modal-close" onclick="closeModal('modal-invest-commodity')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="modal-body">

      <!-- Available balance indicator -->
      <div class="modal-balance-bar">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Available balance: <strong id="commodityBalance">—</strong>
      </div>

      <div class="form-group">
        <label for="commodityAssetSelect">Select Asset</label>
        <select id="commodityAssetSelect" class="form-select" onchange="onCommodityAssetChange()">
          <option value="">— Choose an asset —</option>
        </select>
      </div>

      <!-- Asset info bar — shown after selection -->
      <div id="commodityAssetInfo" class="invest-info-bar" style="display:none;">
        <div class="invest-info-item">
          <span class="invest-info-label">Live Price</span>
          <span class="invest-info-value" id="commodityLivePrice">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">24h Change</span>
          <span class="invest-info-value" id="commodityLiveChange">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Duration</span>
          <span class="invest-info-value" id="commodityDuration">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Simulated Yield</span>
          <span class="invest-info-value" id="commodityYield">—</span>
        </div>
        <div class="invest-info-item">
          <span class="invest-info-label">Min. Investment</span>
          <span class="invest-info-value" id="commodityMin">—</span>
        </div>
      </div>

      <div class="form-group" id="commodityAmountGroup" style="display:none;">
        <label for="commodityInvestAmount">Amount (USD)</label>
        <div class="input-wrapper">
          <i class="ph ph-currency-dollar input-icon" aria-hidden="true"></i>
          <input type="number" id="commodityInvestAmount" class="has-icon"
                 placeholder="Enter amount" min="0" step="0.01">
        </div>
        <span class="input-hint" id="commodityAmountHint"></span>
      </div>

      <div id="commodityInvestMsg" class="auth-msg" style="display:none;" role="alert"></div>

      <div class="modal-actions">
        <button type="button" class="btn-outline"
                onclick="closeModal('modal-invest-commodity')">Cancel</button>
        <button type="button" class="btn-primary" id="commodityInvestBtn"
                onclick="submitCommodityInvestment()" disabled>
          <span class="btn-text">Open Position</span>
          <span class="btn-spinner" style="display:none;"></span>
        </button>
      </div>

    </div>
  </div>
</div>
