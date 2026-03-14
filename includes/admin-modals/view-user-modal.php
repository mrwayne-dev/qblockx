<?php
/**
 * Project: crestvalebank
 * Modal: View User Profile
 *
 * Read-only user profile showing wallet balance + linked
 * savings plans, fixed deposits, active loans, recent transactions.
 *
 * Populated by JS openViewUserModal(userId) which fetches
 * GET /api/admin-dashboard/user-profile.php?id=X
 */
?>

<div id="modal-view-user" class="admin-modal-overlay admin-modal-overlay--wide" role="dialog" aria-modal="true" aria-labelledby="viewUserModalTitle">
  <div class="admin-modal admin-modal--wide">

    <div class="admin-modal-header">
      <h2 class="admin-modal-title" id="viewUserModalTitle">
        <i class="ph ph-user-circle" aria-hidden="true"></i>
        User Profile
      </h2>
      <button class="admin-modal-close" data-close-modal="modal-view-user" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="admin-modal-body admin-modal-body--scrollable" id="viewUserBody">
      <div class="loading-rows">
        <i class="ph ph-circle-notch ph-spin"></i>
        Loading profile…
      </div>
    </div>

  </div><!-- .admin-modal -->
</div><!-- #modal-view-user -->
