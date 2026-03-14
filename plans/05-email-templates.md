# Plan 05 — Email Templates
**Status:** Pending
**Execution Order:** 5 (last, after all pages done)
**Depends on:** Plan 01 (colors), Plan 03 & 04 (new product names)

## Goal
Rebrand all email templates from ArqoraCapital to CrestVale Bank, apply dark fintech color scheme, and rewrite content for banking context.

## Color Scheme for Emails
| Element | Value |
|---------|-------|
| Background | `#0F1115` |
| Card/Panel | `#1A1D23` |
| Accent / CTA buttons | `#3FE0A1` |
| Button text | `#0F1115` (dark on emerald) |
| Primary text | `#E6E8EC` |
| Secondary text | `#6B7280` |
| Border | `rgba(255,255,255,0.08)` |

## Template Changes

### Branding Pass (ALL 14 templates)
- Replace: "ArqoraCapital" → "CrestVale Bank"
- Replace: logo `src` → CrestVale Bank logo path
- Replace: blue CTA color (#2563EB) → emerald (#3FE0A1)
- Replace: white background → #0F1115
- Replace: light card bg → #1A1D23
- Replace: dark text (#0F172A) → silver (#E6E8EC)

### Template-by-Template

| Current File | New File | Content Change |
|---|---|---|
| `welcome.html` | `welcome.html` | Rewrite: welcome to CrestVale, highlight products (Savings, Deposits, Loans, Transfers) |
| `verify-email.html` | `verify-email.html` | Branding only |
| `password-reset.html` | `password-reset.html` | Branding only |
| `admin-signin.html` | `admin-signin.html` | Branding only |
| `user-signin.html` | `user-signin.html` | Branding only |
| `deposit-pending.html` | `deposit-pending.html` | Update: bank deposit context, amount pending admin review |
| `deposit-confirmed.html` | `deposit-confirmed.html` | Update: funds confirmed in wallet |
| `withdrawal-pending.html` | `withdrawal-pending.html` | Branding + minor copy |
| `withdrawal-confirmed.html` | `withdrawal-confirmed.html` | Branding + minor copy |
| `withdrawal-rejected.html` | `withdrawal-rejected.html` | Branding + minor copy |
| `investment-started.html` | **`savings-plan-created.html`** | Rewrite: savings plan created, target amount, duration, rate |
| `investment-completed.html` | **`deposit-matured.html`** | Rewrite: fixed deposit matured, principal + interest returned to wallet |
| `profit-credited.html` | **`interest-credited.html`** | Rewrite: monthly/periodic interest credited to account |
| `referral-bonus.html` | `referral-bonus.html` | Keep for now, update branding only |

## Code Updates
After renaming templates, update references in:
- `api/auth/` — welcome.html, verify-email.html, password-reset.html
- `api/payments/` or `api/user-dashboard/` — deposit/withdrawal templates
- Any cron job handlers in `api/cron/` — interest-credited, deposit-matured
- `config/email.php` — if template paths are configured there

## Email Template Structure (standard for all)
```html
<!-- Outer: bg #0F1115, max-width 600px, centered -->
<!-- Header: CrestVale Bank logo + name -->
<!-- Body card: bg #1A1D23, border-radius 12px, padding 32px -->
<!-- H1: #E6E8EC, font-size 24px -->
<!-- Body text: #6B7280, font-size 16px, line-height 1.6 -->
<!-- CTA button: bg #3FE0A1, color #0F1115, border-radius 8px, padding 14px 28px -->
<!-- Footer: #6B7280, small text, unsubscribe link -->
```

## Verification
- [ ] Send test welcome email — CrestVale branding shows
- [ ] Deposit confirmed email — correct amounts populate
- [ ] savings-plan-created email sends on plan creation
- [ ] deposit-matured email sends on maturity (or manual trigger)
- [ ] No broken image references
- [ ] Renders correctly in Gmail and Outlook
