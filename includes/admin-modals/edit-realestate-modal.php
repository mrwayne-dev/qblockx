<?php /* Admin Modal: Add / Edit Real Estate Pool */ ?>
<div id="modal-edit-realestate" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editReModalTitle">
  <div class="admin-modal admin-modal--wide">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editReModalTitle">
        <i class="ph ph-buildings" aria-hidden="true"></i>
        <span id="editReModalTitleText">Add Real Estate Pool</span>
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-realestate" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editReId">

      <div class="admin-form-row">
        <div class="admin-form-group" style="flex:2;">
          <label for="editReName">Pool Name <span style="color:var(--color-danger)">*</span></label>
          <input type="text" id="editReName" placeholder="e.g. Residential Pool">
        </div>
        <div class="admin-form-group">
          <label for="editRePropertyType">Property Type <span style="color:var(--color-danger)">*</span></label>
          <input type="text" id="editRePropertyType" placeholder="e.g. Residential">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editReMinInv">Min Investment (USD) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editReMinInv" min="0" step="1" placeholder="e.g. 1000">
        </div>
        <div class="admin-form-group">
          <label for="editReDays">Duration (days) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editReDays" min="1" step="1" placeholder="e.g. 90">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editReYieldMin">Yield Min (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editReYieldMin" min="0" max="9999" step="0.01" placeholder="e.g. 12">
        </div>
        <div class="admin-form-group">
          <label for="editReYieldMax">Yield Max (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editReYieldMax" min="0" max="9999" step="0.01" placeholder="e.g. 18">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editRePayoutFreq">Payout Frequency</label>
          <select id="editRePayoutFreq" class="admin-select">
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
          </select>
        </div>
        <div class="admin-form-group">
          <label for="editReOccupancy">Occupancy (%)</label>
          <input type="number" id="editReOccupancy" min="0" max="100" step="0.1" placeholder="e.g. 92">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editReLocationTag">Location Tag</label>
          <input type="text" id="editReLocationTag" placeholder="e.g. Global Markets">
        </div>
        <div class="admin-form-group">
          <label for="editReSortOrder">Sort Order</label>
          <input type="number" id="editReSortOrder" min="0" step="1" placeholder="e.g. 1">
        </div>
      </div>

      <div class="admin-form-group">
        <label for="editReImageUrl">Image URL</label>
        <input type="text" id="editReImageUrl" placeholder="https://…">
      </div>

      <div class="admin-form-group" style="display:flex;align-items:center;gap:0.75rem;">
        <input type="checkbox" id="editReCompounded" style="width:18px;height:18px;cursor:pointer;">
        <label for="editReCompounded" style="margin:0;cursor:pointer;">Compounded returns</label>
      </div>

      <div id="editReMsg" class="admin-modal-msg" style="display:none;"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-realestate">Cancel</button>
        <button class="btn-action btn-action--accent" id="editReSaveBtn" onclick="saveRePool()">
          <i class="ph ph-floppy-disk"></i> Save Pool
        </button>
      </div>

    </div>
  </div>
</div>
