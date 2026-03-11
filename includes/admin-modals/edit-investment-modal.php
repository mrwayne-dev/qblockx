<?php /* Admin Modal: Edit Investment */ ?>
<div id="modal-edit-investment" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editInvModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editInvModalTitle">
        <i class="ph ph-arrows-left-right" aria-hidden="true"></i>
        Edit Trade
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-investment" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editInvId">

      <div class="admin-form-group">
        <label for="editInvUser">User</label>
        <input type="text" id="editInvUser" readonly>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editInvAmount">Amount (USD)</label>
          <input type="number" id="editInvAmount" min="0" step="0.01">
        </div>
        <div class="admin-form-group">
          <label for="editInvRate">Daily Rate (%)</label>
          <input type="number" id="editInvRate" min="0" max="100" step="0.01" placeholder="e.g. 2.00">
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editInvEarned">Total Earned (USD)</label>
          <input type="number" id="editInvEarned" min="0" step="0.01">
        </div>
        <div class="admin-form-group">
          <label for="editInvStatus">Status</label>
          <select id="editInvStatus">
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editInvStartsAt">Start Date</label>
          <input type="datetime-local" id="editInvStartsAt">
        </div>
        <div class="admin-form-group">
          <label for="editInvEndsAt">End Date</label>
          <input type="datetime-local" id="editInvEndsAt">
        </div>
      </div>

      <div id="editInvMsg" class="admin-modal-msg"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-investment">Cancel</button>
        <button class="btn-action btn-action--accent" id="editInvSaveBtn" onclick="saveInvestment()">
          <i class="ph ph-floppy-disk"></i> Save Changes
        </button>
      </div>

    </div>
  </div>
</div>
