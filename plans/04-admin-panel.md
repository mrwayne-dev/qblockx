# Plan 04 — Admin Panel
**Status:** Pending
**Execution Order:** 4 (after 03-user-dashboard)
**Depends on:** Plan 01 (design system), Plan 03 (DB tables)

## Goal
Replace crypto admin panel (Trades, Referrals) with CrestVale Bank admin panel (Savings Management, Deposits Management, Loans Management, Settings/Rates Engine).

## Admin Sidebar Navigation
Replace:
- Dashboard ✓
- Users ✓
- Transactions ✓ (keep, update filters)
- Trades ✗ → **Savings**
- (new) → **Deposits**
- (new) → **Loans**
- Referrals ✗ → **Settings**
- Logout ✓

## Admin Mobile Dock (includes/admin-mobile-dock.php)
Mirror sidebar changes.

## Pages

### admin/dashboard.php — Stats Rewrite
Stat cards:
- Total Users
- Total Wallet Balance
- Active Savings Plans
- Active Fixed Deposits
- Active Loans (pending + approved)

Charts (keep existing chart library, update data endpoints):
- Deposits vs Withdrawals (bar chart)
- User Growth (line chart)
- Loan Repayments (bar chart)

### admin/users.php — Minor Updates
- Add KYC Status column
- Add links: "View Savings", "View Deposits", "View Loans" per user
- Existing actions (Edit, Disable, Reset Password) unchanged

### admin/transactions.php — Filter Updates
- Add filter dropdown for new transaction types: `loan_payment`, `savings_contribution`, `deposit_return`
- Approve Deposit / Approve Withdrawal actions unchanged
- Add: Reverse Transaction action

### admin/savings.php (NEW)
Route: `/admin/savings`

Table columns:
| User | Plan Name | Target Amount | Saved Amount | Rate | Duration | Status | Actions |

Admin Actions per row:
- Edit Plan (modal: adjust target, rate)
- Cancel Plan
- Adjust Balance (credit/debit)

Summary stats bar: Total Plans, Total Saved Across All Plans

### admin/deposits.php (NEW)
Route: `/admin/deposits`

Table columns:
| User | Deposit Amount | Interest Rate | Duration | Start Date | Maturity Date | Expected Return | Status | Actions |

Admin Actions per row:
- Approve Deposit (pending → active)
- Close Deposit (after maturity)
- Adjust Interest Rate

### admin/loans.php (NEW)
Route: `/admin/loans`

Two tabs: **Applications** | **Active Loans**

Applications table:
| User | Requested Amount | Duration | Applied Date | Status | Actions |
Actions: Approve | Reject | Modify Terms

Active Loans table:
| User | Loan Amount | Remaining Balance | Monthly Payment | Rate | Status | Actions |
Actions: Record Repayment | Adjust Loan | Close Loan

### admin/settings.php (NEW)
Route: `/admin/settings`

**Interest Rates Engine section:**
Table: Product Type | Duration (months) | Interest Rate | Created | Actions
- Product types: `savings`, `fixed_deposit`, `loan`
- Actions: Edit Rate | Delete Rate
- "Add Rate" button → modal

Example pre-loaded rates:
| savings | 12 months | 5% |
| fixed_deposit | 6 months | 10% |
| fixed_deposit | 12 months | 12% |
| loan | 12 months | 12% |

**System Settings section:**
- Toggle: Enable Deposits (on/off)
- Toggle: Enable Withdrawals (on/off)
- Toggle: Maintenance Mode

**Security section:**
- Admin Roles display
- Audit Logs link

### Redirects
- `pages/admin/trades.php` → `header('Location: /admin/loans')`
- `pages/admin/referrals.php` → `header('Location: /admin/settings')`

## Routes (.htaccess)
```
/admin/savings   → /pages/admin/savings.php
/admin/deposits  → /pages/admin/deposits.php
/admin/loans     → /pages/admin/loans.php
/admin/settings  → /pages/admin/settings.php
```

## API Endpoints Needed
In `/api/admin-dashboard/`:
- `GET/PUT savings.php` — list and manage savings plans
- `GET/PUT deposits.php` — list and manage deposits
- `GET/PUT loans.php` — list, approve, reject, manage loans
- `GET/POST/PUT rates.php` — CRUD for interest rates engine
- `GET/PUT settings.php` — system settings

## Verification
- [ ] Admin sidebar shows correct links
- [ ] /admin/savings, /admin/deposits, /admin/loans, /admin/settings routes work
- [ ] Dashboard stat cards render
- [ ] Rates table loads and "Add Rate" modal opens
- [ ] Loan approve/reject actions work
- [ ] Admin trades and referrals redirect correctly
