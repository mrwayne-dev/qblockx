<?php
/**
 * Project: qblockx
 * Modal: Edit Interest Rate
 *
 * Opened by openEditRateModal(id, label, duration, rate, isActive)
 * Submits via saveEditRate() → POST /api/admin-dashboard/settings.php { action: 'update_rate' }
 */
?>

<div id="modal-edit-rate" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editRateModalTitle">
  <div class="admin-modal">

    <div class="admin-modal-header">
      <h2 class="admin-modal-title" id="editRateModalTitle">
        <i class="ph ph-pencil-simple" aria-hidden="true"></i>
        Edit Rate
      </h2>
      <button class="admin-modal-close" data-close-modal="modal-edit-rate" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="admin-modal-body">

      <input type="hidden" id="editRateId">

      <div class="admin-form-group">
        <label>Plan Label</label>
        <input id="editRateLabel" class="admin-input" type="text" disabled
               style="opacity:0.6;cursor:not-allowed;">
        <p class="admin-form-note">Label and duration cannot be changed. Delete and re-add the rate to change these.</p>
      </div>

      <div class="admin-form-group">
        <label>Duration</label>
        <input id="editRateDuration" class="admin-input" type="text" disabled
               style="opacity:0.6;cursor:not-allowed;">
      </div>

      <div class="admin-form-group">
        <label for="editRateValue">Rate (% p.a.)</label>
        <input id="editRateValue" class="admin-input" type="number" min="0" step="0.01" placeholder="5.00">
      </div>

      <div class="admin-form-check">
        <input id="editRateActive" type="checkbox" checked>
        <label for="editRateActive">Active (visible to users)</label>
      </div>

      <div id="editRateMsg" class="admin-modal-msg" style="display:none;"></div>

    </div><!-- .admin-modal-body -->

    <div class="admin-modal-footer">
      <button class="btn-sm" data-close-modal="modal-edit-rate" type="button">Cancel</button>
      <button class="btn-sm btn-accent" onclick="saveEditRate()" type="button">
        <i class="ph ph-check"></i> Save Changes
      </button>
    </div>

  </div><!-- .admin-modal -->
</div><!-- #modal-edit-rate -->
