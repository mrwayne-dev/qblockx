<?php /* Admin Modal: Add / Edit Investment Plan */ ?>
<div id="modal-edit-plan" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editPlanModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editPlanModalTitle">
        <i class="ph ph-chart-line-up" aria-hidden="true"></i>
        <span id="editPlanModalTitleText">Add Investment Plan</span>
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-plan" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editPlanId">

      <div class="admin-form-row">
        <div class="admin-form-group" style="flex:2;">
          <label for="editPlanName">Plan Name <span style="color:var(--color-danger)">*</span></label>
          <input type="text" id="editPlanName" placeholder="e.g. Starter">
        </div>
        <div class="admin-form-group">
          <label for="editPlanTier">Tier <span style="color:var(--color-danger)">*</span></label>
          <select id="editPlanTier" class="admin-select">
            <option value="starter">Starter</option>
            <option value="elite">Elite</option>
          </select>
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanMin">Min Amount (USD) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editPlanMin" min="0" step="1" placeholder="e.g. 1000">
        </div>
        <div class="admin-form-group">
          <label for="editPlanMax">Max Amount (USD)</label>
          <input type="number" id="editPlanMax" min="0" step="1" placeholder="Leave blank = unlimited">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanDays">Duration (days) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editPlanDays" min="1" step="1" placeholder="e.g. 30">
        </div>
        <div class="admin-form-group">
          <label for="editPlanCommission">Commission (%)</label>
          <input type="number" id="editPlanCommission" min="0" max="100" step="0.01" placeholder="e.g. 15">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanYieldMin">Yield Min (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editPlanYieldMin" min="0" max="9999" step="0.01" placeholder="e.g. 30">
        </div>
        <div class="admin-form-group">
          <label for="editPlanYieldMax">Yield Max (%) <span style="color:var(--color-danger)">*</span></label>
          <input type="number" id="editPlanYieldMax" min="0" max="9999" step="0.01" placeholder="e.g. 60">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanSortOrder">Sort Order</label>
          <input type="number" id="editPlanSortOrder" min="0" step="1" placeholder="e.g. 1">
        </div>
        <div class="admin-form-group" style="display:flex;align-items:center;gap:0.75rem;padding-top:1.6rem;">
          <input type="checkbox" id="editPlanCompounded" style="width:18px;height:18px;cursor:pointer;">
          <label for="editPlanCompounded" style="margin:0;cursor:pointer;">Compounded returns</label>
        </div>
      </div>

      <div id="editPlanMsg" class="admin-modal-msg" style="display:none;"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-plan">Cancel</button>
        <button class="btn-action btn-action--accent" id="editPlanSaveBtn" onclick="saveInvPlan()">
          <i class="ph ph-floppy-disk"></i> Save Plan
        </button>
      </div>

    </div>
  </div>
</div>
