<?php /** Project: Qblockx — Linked Wallets list modal */ ?>
<div class="modal-overlay" id="modal-linked-wallets" role="dialog" aria-modal="true" aria-labelledby="linkedWalletsTitle">
  <div class="modal-box">
    <div class="modal-header">
      <h2 class="modal-title" id="linkedWalletsTitle">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Your Linked Wallets
      </h2>
      <button class="modal-close" type="button" onclick="closeModal('modal-linked-wallets')" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="modal-body">
      <p class="text-muted" style="margin-bottom:1rem;">
        You can link up to <strong>5</strong> external wallets. Your seed phrases are encrypted and stored securely.
      </p>
      <div id="linkedWalletsList" style="display:flex;flex-direction:column;gap:0.75rem;">
        <p class="empty-text">No wallets linked yet.</p>
      </div>
      <div style="margin-top:1.25rem;text-align:right;">
        <button class="btn-primary" type="button" id="linkedWalletsAddBtn" onclick="closeModal('modal-linked-wallets');openModal('modal-trust-wallet');">
          <i class="ph ph-plus" aria-hidden="true"></i>
          Link Another Wallet
        </button>
      </div>
    </div>
  </div>
</div>
