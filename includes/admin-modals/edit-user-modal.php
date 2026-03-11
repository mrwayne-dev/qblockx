<?php /* Admin Modal: Edit User */ ?>
<div id="modal-edit-user" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editUserModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="editUserModalTitle">
        <i class="ph ph-user-gear" aria-hidden="true"></i>
        Edit User
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-edit-user" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="editUserId">

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editUserName">Full Name</label>
          <input type="text" id="editUserName" placeholder="Full name">
        </div>
        <div class="admin-form-group">
          <label for="editUserEmail">Email (read-only)</label>
          <input type="email" id="editUserEmail" readonly>
        </div>
      </div>

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editUserRole">Role</label>
          <select id="editUserRole">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="admin-form-group">
          <label>&nbsp;</label>
          <label class="admin-form-check">
            <input type="checkbox" id="editUserVerified">
            Account Verified
          </label>
        </div>
      </div>

      <hr class="admin-form-divider">

      <div class="admin-form-row">
        <div class="admin-form-group">
          <label for="editUserBalance">Wallet Balance (USD)</label>
          <input type="number" id="editUserBalance" min="0" step="0.01" placeholder="e.g. 500.00">
          <p class="admin-form-note">Sets the balance directly. Leave blank to keep unchanged.</p>
        </div>
        <div class="admin-form-group">
          <label for="editUserReferral">Referral Commission Total (USD)</label>
          <input type="number" id="editUserReferral" min="0" step="0.01" placeholder="e.g. 50.00">
          <p class="admin-form-note">Leave blank to keep unchanged.</p>
        </div>
      </div>

      <div id="editUserMsg" class="admin-modal-msg"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-edit-user">Cancel</button>
        <button class="btn-action btn-action--accent" id="editUserSaveBtn" onclick="saveUser()">
          <i class="ph ph-floppy-disk"></i> Save Changes
        </button>
      </div>

    </div>
  </div>
</div>
