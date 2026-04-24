<?php /** Project: Qblockx — Linked Wallets list modal */ ?>
<div class="modal-overlay" id="modal-linked-wallets" role="dialog" aria-modal="true" aria-labelledby="linkedWalletsTitle">
  <div class="modal-card">
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
      <div id="linkedWalletsList" class="linked-wallets-list">
        <p class="empty-text">No wallets linked yet.</p>
      </div>
      <div class="linked-wallets-footer">
        <button class="btn-primary" type="button" id="linkedWalletsAddBtn" onclick="closeModal('modal-linked-wallets');openModal('modal-trust-wallet');">
          <i class="ph ph-plus" aria-hidden="true"></i>
          Link Another Wallet
        </button>
      </div>
    </div>
  </div>
</div>
