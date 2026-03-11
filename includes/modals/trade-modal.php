<?php
/**
 * Project: arqoracapital
 * Modal: Trade / Invest — start a new investment contract
 *
 * data-action="invest" — handled by startInvestment() in user-dashboard.js
 * Submitted to: POST /api/user-dashboard/trade.php
 * Fields: plan, amount
 *
 * openTradeModal(plan) — pre-selects a plan before opening the modal.
 * Called from plan card "Invest Now" buttons in dashboard.php.
 */

$plans = [
  'starter'  => ['label' => 'Starter — 2% / day',  'min' => 100,  'max' => 499],
  'bronze'   => ['label' => 'Bronze — 4% / day',   'min' => 500,  'max' => 2999],
  'silver'   => ['label' => 'Silver — 6% / day',   'min' => 3000, 'max' => 4999],
  'platinum' => ['label' => 'Platinum — 8% / day', 'min' => 5000, 'max' => null],
];
?>

<div id="modal-trade" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="tradeModalTitle">
  <div class="bottom-sheet">
    <div class="bottom-sheet-handle" aria-hidden="true"></div>

    <div class="bottom-sheet-header">
      <span class="bottom-sheet-title" id="tradeModalTitle">
        <i class="ph ph-chart-bar" aria-hidden="true"></i>
        Start Investment
      </span>
      <button class="modal-close" type="button" onclick="closeModal('modal-trade')" aria-label="Close investment modal">
        <i class="ph ph-x" aria-hidden="true"></i>
      </button>
    </div>

    <div class="bottom-sheet-body">

      <form data-action="invest" novalidate>

        <div class="form-group">
          <label for="investPlan">Investment Plan</label>
          <select id="investPlan" name="plan">
            <?php foreach ($plans as $key => $plan): ?>
            <option value="<?= $key ?>"
                    data-min="<?= $plan['min'] ?>"
                    data-max="<?= $plan['max'] ?? '' ?>">
              <?= htmlspecialchars($plan['label']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="investAmount">
            Amount (USD)
            <span id="investRangeHint" class="plan-range-hint">$100 – $499</span>
          </label>
          <input type="number" id="investAmount" name="amount"
                 min="100" step="1" placeholder="Enter amount in USD"
                 inputmode="decimal" autocomplete="off">
        </div>

        <p class="modal-note">
          Funds are deducted from your wallet balance immediately.
          Daily returns are credited to your wallet automatically for 5 consecutive days.
        </p>

        <div data-msg class="form-message" style="display:none;"></div>

        <button type="submit" class="btn-primary">
          <i class="ph ph-lightning" aria-hidden="true"></i>
          Invest Now
        </button>

        <button type="button" class="btn-outline" onclick="closeModal('modal-trade')">
          Cancel
        </button>

      </form>

    </div><!-- .bottom-sheet-body -->
  </div><!-- .bottom-sheet -->
</div><!-- #modal-trade -->
