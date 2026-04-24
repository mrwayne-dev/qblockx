<?php /* Admin Modal: Add / Edit Commodity Asset */ ?>
<div id="modal-edit-commodity" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editComModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editComModalTitle">
        <i class="ph ph-cube" aria-hidden="true"></i>
        <span id="editComModalTitleText">Add Commodity Asset</span>
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-commodity" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editComId">

      <div class="admin-form-row">
        <div class="admin-form-group" style="flex:2;">
          <label for="editComName">Asset Name <span style="color:var(--color-danger)">*</span></label>
          <input type="text" id="editComName" placeholder="e.g. Gold">
        </div>
        <div class="admin-form-group">
          <label for="editComSymbol">Symbol <span style="color:var(--color-danger)">*</span></label>
          <input type="text" id="editComSymbol" placeholder="e.g. XAU" maxlength="20" style="text-transform:uppercase;">
        </div>
      </div>

      <div class="admin-form-group">
        <label for="editComTVSym">TradingView Symbol <span style="color:var(--color-danger)">*</span></label>
        <input type="text" id="editComTVSym" placeholder="e.g. XAUUSD">
        <p class="admin-form-note">Used to embed the live price chart widget.</p>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editComMinInv">Min Investment (USD) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editComMinInv" min="0" step="1" placeholder="e.g. 500">
        </div>
        <div class="admin-form-group">
          <label for="editComDays">Duration (days) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editComDays" min="1" step="1" placeholder="e.g. 30">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editComYieldMin">Yield Min (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editComYieldMin" min="0" max="9999" step="0.01" placeholder="e.g. 8">
        </div>
        <div class="admin-form-group">
          <label for="editComYieldMax">Yield Max (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editComYieldMax" min="0" max="9999" step="0.01" placeholder="e.g. 15">
        </div>
      </div>

      <div class="admin-form-group">
        <label for="editComSortOrder">Sort Order</label>
        <input type="number" id="editComSortOrder" min="0" step="1" placeholder="e.g. 1">
      </div>

      <div id="editComMsg" class="admin-modal-msg" style="display:none;"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-commodity">Cancel</button>
        <button class="btn-action btn-action--accent" id="editComSaveBtn" onclick="saveComAsset()">
          <i class="ph ph-floppy-disk"></i> Save Asset
        </button>
      </div>

    </div>
  </div>
</div>
