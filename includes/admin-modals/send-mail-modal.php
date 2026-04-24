<?php /* Admin Modal: Send Mail to User */ ?>
<div id="modal-send-mail" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="sendMailModalTitle">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="sendMailModalTitle">
        <i class="ph ph-envelope" aria-hidden="true"></i>
        Send Mail to User
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-send-mail" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <input type="hidden" id="mailUserId">

      <div class="admin-form-group" style="margin-bottom:1rem;">
        <label>Recipient</label>
        <div id="mailRecipientDisplay" style="
          padding:10px 14px;
          background:var(--bg-soft,#F7F8FC);
          border:1px solid var(--border,#D9DEEA);
          border-radius:8px;
          font-size:14px;
          color:var(--text-muted,#4A4F5F);
          line-height:1.4;
        ">—</div>
      </div>

      <div class="admin-form-group">
        <label for="mailSubject">Subject</label>
        <input type="text" id="mailSubject" placeholder="e.g. Important account update" maxlength="200">
      </div>

      <div class="admin-form-group">
        <label for="mailBody">Message</label>
        <textarea id="mailBody" rows="7" placeholder="Write your message here. Plain text only — line breaks will be preserved." maxlength="5000" style="resize:vertical;"></textarea>
        <p class="admin-form-note">Sent from the platform support address. No HTML — plain text only.</p>
      </div>

      <div id="sendMailMsg" class="admin-modal-msg"></div>

      <div class="admin-modal-actions">
        <button class="btn-action" data-close-modal="modal-send-mail">Cancel</button>
        <button class="btn-action btn-action--accent" id="sendMailBtn" onclick="sendUserMail()">
          <i class="ph ph-paper-plane-tilt"></i> Send Mail
        </button>
      </div>

    </div>
  </div>
</div>
