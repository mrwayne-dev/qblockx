<?php /* Admin Modal: Pending Deposits */ ?>
<div id="modal-pending-deposits" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="pendingDepModalTitle">
  <div class="admin-modal admin-modal--wide">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="pendingDepModalTitle">
        <i class="ph ph-arrow-circle-down" aria-hidden="true"></i>
        Pending Deposits
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-pending-deposits" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <div class="modal-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Amount</th>
              <th>Order ID</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="pendingDepositsTable">
            <tr><td colspan="5">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
            </td></tr>
          </tbody>
        </table>
      </div>

      <div id="pendingDepositsMsg" class="admin-modal-msg"></div>

    </div>
  </div>
</div>
