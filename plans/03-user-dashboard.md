# Plan 03 — User Dashboard
**Status:** Pending
**Execution Order:** 3 (after 02-public-pages)
**Depends on:** Plan 01 (design system)

## Goal
Replace crypto investment dashboard (Trade, Referral) with CrestVale Bank user dashboard (Savings, Fixed Deposits, Loans, Transfers/Wallet).

## Sidebar Navigation (includes/sidebar.php)
Replace:
- Dashboard ✓ (keep)
- Wallet ✓ (keep)
- Trade ✗ → **Savings**
- Referral ✗ → **Deposits**
- (new) → **Loans**
- Profile ✓ (keep)
- Logout ✓ (keep)

## Mobile Dock (includes/mobile-dock.php)
Mirror sidebar: Dashboard, Wallet, Savings, Deposits, Loans, Profile

## Pages

### dashboard.php — Overview Rewrite
Stat cards:
- Total Balance (wallet)
- Savings Balance (sum of savings_plans.current_amount)
- Deposits Balance (sum of fixed_deposits.amount)
- Loan Balance (sum of loans.remaining_balance)

Widgets:
- Recent Transactions (last 5 from transactions table)
- Savings Progress (active plans with progress bars)
- Upcoming Loan Payments (next due dates)

Quick Actions bar: Deposit | Withdraw | Transfer

### wallet.php — Transaction Types Update
- Update type labels: `deposit`, `withdrawal`, `transfer`, `loan_payment`, `savings_contribution`, `deposit_return`
- Remove investment/trade UI blocks
- Add product action buttons:
  - "Create Savings Plan" → /savings
  - "Open Fixed Deposit" → /deposits
  - "Repay Loan" → /loans

### savings.php (NEW)
Route: `/savings`

Sections:
- Summary bar: Total Saved, Active Plans count
- Plans list table:
  | Plan Name | Target | Saved | Rate | Duration | Progress | Status | Actions |
- Progress bar per plan (current/target %)
- "Create Plan" button → modal

Create Savings Plan modal fields:
- Plan Name, Target Amount, Duration (months) — rate auto-populated from rates table

Actions per plan: Add Funds | View Details | Close Plan

Money flow label: Wallet → Savings Plan

### deposits.php (NEW)
Route: `/deposits`

Sections:
- Summary bar: Total Invested, Expected Returns
- Deposits list table:
  | Amount | Rate | Duration | Start Date | Maturity Date | Expected Return | Status | Actions |
- "Open Fixed Deposit" button → modal

Open Deposit modal fields:
- Amount, Duration (months) — rate auto-populated from rates table
- Displays expected return calculation

Actions per deposit: View Details | (Close — only after maturity)

Money flow label: Wallet → Fixed Deposit

### loans.php (NEW)
Route: `/loans`

Sections:
- Active Loans tab
- Pending Applications tab
- Closed Loans tab

Active Loans table:
| Loan Amount | Remaining Balance | Monthly Payment | Rate | Duration | Status | Actions |

Pending Applications table:
| Requested Amount | Duration | Applied Date | Status |

Actions: Apply for Loan (modal) | Make Repayment | View Agreement

Apply for Loan modal fields:
- Loan Amount, Duration (months) — monthly payment auto-calculated
- Formula: Monthly Payment = Total Loan / Duration

### Redirects
- `pages/user/trade.php` → `header('Location: /savings')`
- `pages/user/referral.php` → `header('Location: /dashboard')`

## Routes (.htaccess)
```
/savings   → /pages/user/savings.php
/deposits  → /pages/user/deposits.php
/loans     → /pages/user/loans.php
```

## Database Tables Referenced
- `savings_plans` (id, user_id, plan_name, target_amount, current_amount, interest_rate, duration_months, start_date, end_date, status)
- `fixed_deposits` (id, user_id, amount, interest_rate, duration_months, start_date, maturity_date, expected_return, status)
- `loans` (id, user_id, loan_amount, interest_rate, duration_months, monthly_payment, remaining_balance, status, created_at)
- `rates` (id, product_type, duration, interest_rate, created_at)
- `transactions` (existing — add new type values)

Add these tables to `dbschema/database.sql`.

## API Endpoints Needed
These will be wired up in `/api/user-dashboard/`:
- `GET savings.php` — list user's savings plans
- `POST savings.php` — create savings plan
- `POST savings-fund.php` — add funds to plan
- `GET deposits.php` — list user's deposits
- `POST deposits.php` — open fixed deposit
- `GET loans.php` — list user's loans
- `POST loans.php` — apply for loan
- `POST loan-repayment.php` — make repayment

## Verification
- [ ] Sidebar shows correct banking links
- [ ] Dashboard stat cards render (may show 0 initially)
- [ ] /savings, /deposits, /loans routes work
- [ ] Wallet shows correct transaction types
- [ ] Create plan modal opens and form renders
- [ ] Trade and referral redirects work
