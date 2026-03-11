<?php /* Admin Modal: Edit Investment Plan */ ?>
<div id="modal-edit-plan" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editPlanModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editPlanModalTitle">
        <i class="ph ph-gear" aria-hidden="true"></i>
        Edit Investment Plan
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-plan" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editPlanId">

      <div class="admin-form-group">
        <label for="editPlanName">Plan Name</label>
        <input type="text" id="editPlanName" placeholder="e.g. Starter">
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanMin">Minimum Amount (USD)</label>
          <input type="number" id="editPlanMin" min="0" step="1">
        </div>
        <div class="admin-form-group">
          <label for="editPlanMax">Maximum Amount (USD)</label>
          <input type="number" id="editPlanMax" min="0" step="1" placeholder="Leave blank = unlimited">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editPlanRate">Daily Rate (%)</label>
          <input type="number" id="editPlanRate" min="0" max="100" step="0.01" placeholder="e.g. 2.00">
          <p class="admin-form-note">Enter as a percentage — e.g. 2 = 2%/day.</p>
        </div>
        <div class="admin-form-group">
          <label for="editPlanDays">Duration (days)</label>
          <input type="number" id="editPlanDays" min="1" step="1">
        </div>
      </div>

      <div id="editPlanMsg" class="admin-modal-msg"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-plan">Cancel</button>
        <button class="btn-action btn-action--accent" id="editPlanSaveBtn" onclick="savePlan()">
          <i class="ph ph-floppy-disk"></i> Save Plan
        </button>
      </div>

    </div>
  </div>
</div>
