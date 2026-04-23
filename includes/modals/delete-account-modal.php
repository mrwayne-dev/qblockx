<?php
/**
 * Project: qblockx
 * Modal: Delete Account — confirmation dialog
 *
 * Submits action:"delete" to POST /api/user-dashboard/profile.php
 * On success, JS redirects user to /pages/public/login.php
 */
?>

<div id="modal-delete-account" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="deleteModalTitle" style="color:#FF4B4B;">
        <i class="ph ph-warning" aria-hidden="true"></i>
        Delete Account
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-delete-account')" aria-label="Close delete account modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <div class="modal-warning">
        <i class="ph ph-trash" aria-hidden="true"></i>
        <span>
          <strong>This action is permanent and cannot be undone.</strong><br>
          All your account data, investment history, wallet balance, and referrals will be permanently deleted.
        </span>
      </div>

      <p class="modal-note">
        Any remaining wallet balance will be forfeited. Ensure you have withdrawn all funds before proceeding.
      </p>

      <div id="deleteMsg" class="form-message" style="display:none;"></div>

      <button type="button" class="btn-danger full-width" id="confirmDeleteBtn" style="justify-content:center; width:100%;">
        <i class="ph ph-trash" aria-hidden="true"></i>
        Yes, Delete My Account Permanently
      </button>

      <button type="button" class="btn-outline" onclick="closeModal('modal-delete-account')" style="margin-top:1rem;">
        <i class="ph ph-arrow-left" aria-hidden="true"></i>
        Cancel — Keep My Account
      </button>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-delete-account -->
