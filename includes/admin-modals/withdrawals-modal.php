<?php /* Admin Modal: Pending Withdrawals */ ?>
<div id="modal-withdrawals" class="admin-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="withdrawalModalTitle">
  <div class="admin-modal admin-modal--wide">
    <div class="admin-modal-header">
      <h3 class="admin-modal-title" id="withdrawalModalTitle">
        <i class="ph ph-arrow-circle-up-right" aria-hidden="true"></i>
        Withdrawal Requests
      </h3>
      <button class="admin-modal-close" data-close-modal="modal-withdrawals" aria-label="Close">
        <i class="ph ph-x"></i>
      </button>
    </div>
    <div class="admin-modal-body">

      <div class="table-filters" style="margin-bottom:1rem;">
        <button class="filter-btn active" data-wr-modal-filter="pending">Pending</button>
        <button class="filter-btn" data-wr-modal-filter="approved">Approved</button>
        <button class="filter-btn" data-wr-modal-filter="rejected">Rejected</button>
        <button class="filter-btn" data-wr-modal-filter="all">All</button>
      </div>

      <div class="modal-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>User</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Details</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="withdrawalsModalTable">
            <tr><td colspan="7">
              <div class="loading-rows"><i class="ph ph-circle-notch ph-spin"></i> Loading…</div>
            </td></tr>
          </tbody>
        </table>
      </div>

      <div id="withdrawalModalMsg" class="admin-modal-msg"></div>

    </div>
  </div>
</div>
