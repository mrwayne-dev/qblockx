<?php
/**
 * Project: crestvalebank
 * Modal: Generic Admin Confirm / Prompt
 *
 * Driven entirely by JS helpers:
 *   adminConfirm(message, onConfirm)
 *   adminPrompt(message, inputLabel, onConfirm)
 */
?>

<div id="modal-admin-confirm" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="adminConfirmTitle">
  <div class="admin-modal admin-modal--sm">

    <div class="admin-modal-header">
      <h2 class="admin-modal-title" id="adminConfirmTitle">
        <i class="ph ph-warning-circle" aria-hidden="true"></i>
        <span id="adminConfirmTitleText">Confirm Action</span>
      </h2>
      <button class="admin-modal-close" onclick="closeAdminModal('modal-admin-confirm')" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>

    <div class="admin-modal-body">
      <p id="adminConfirmMessage" style="margin:0 0 1rem;line-height:1.6;"></p>

      <!-- Optional input (shown for prompt-style calls) -->
      <div id="adminConfirmInputGroup" style="display:none;" class="admin-form-group">
        <label id="adminConfirmInputLabel" for="adminConfirmInput">Value</label>
        <input id="adminConfirmInput" class="admin-input" type="text" autocomplete="off">
      </div>
    </div>

    <div class="admin-modal-footer">
      <button class="btn-sm" type="button" onclick="closeAdminModal('modal-admin-confirm')">Cancel</button>
      <button class="btn-sm btn-accent" type="button" id="adminConfirmBtn">
        <i class="ph ph-check"></i> Confirm
      </button>
    </div>

  </div>
</div>
