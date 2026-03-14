# Plan 02 — Public Pages
**Status:** Pending
**Execution Order:** 2 (after 01-design-system)
**Depends on:** Plan 01 (design system must be in place)

## Goal
Rebrand all public-facing pages from ArqoraCapital (crypto) to CrestVale Bank (banking). Update navigation, rewrite all content, create Security and Help pages.

## Navigation Changes (header.php)
- Logo text: ArqoraCapital → **CrestVale Bank**
- Nav links:
  - Solutions → **Products** (dropdown: Savings, Fixed Deposits, Loans, Transfers)
  - Keep: Company, About, Contact
  - Add: **Security**, **Help**
- CTA: "Get Started" → `/register`

## Footer Changes (footer.php)
- Brand name → CrestVale Bank
- Nav links match header
- Legal links: Privacy, Terms, Risk Disclosure
- Copyright: © 2025 CrestVale Bank

## Pages Checklist

### Existing Pages — Content Rewrite
| Page | File | Change |
|------|------|--------|
| Home | `pages/public/index.php` | Full rewrite — banking hero, product cards, banking stats, features, testimonials |
| Products | `pages/public/solutions.php` | Repurpose — Savings, Fixed Deposits, Loans, Transfers with rates & user flows |
| About | `pages/public/about.php` | CrestVale Bank mission, values, story |
| Company | `pages/public/company.php` | Corporate info, regulatory, leadership |
| Contact | `pages/public/contact.php` | CrestVale contact details, support hours |
| Privacy | `pages/public/privacy.php` | Replace ArqoraCapital with CrestVale Bank, update product references |
| Terms | `pages/public/terms.php` | Same — branding + banking product terms |
| Risk | `pages/public/risk.php` | Same — banking risk disclosure |
| Help/FAQ | `pages/public/learnmore.php` | Repurpose as FAQ with accordion sections |

### New Pages
| Page | File | Content |
|------|------|---------|
| Security | `pages/public/security.php` | AES-256 encryption, 2FA, fraud monitoring, compliance cards |

## Homepage Sections (index.php)
Remove:
- Crypto ticker (BTC/ETH/etc.)
- Daily % yield pricing plans
- Investment-focused copy

Add/Replace:
- **Hero:** "Banking Built for the Future" — CTA: Open Account + Learn More
- **Product Cards:** Savings (goal-based), Fixed Deposits (locked returns), Loans (flexible), Transfers (instant)
- **Stats:** e.g., 50K+ Customers, $200M+ Managed, 99.9% Uptime, 4.8★ Rating
- **Features:** Goal-Based Savings, Competitive Rates, Instant Transfers, Bank-Grade Security
- **Testimonials:** Rewrite for banking customers
- **CTA Banner:** "Start banking smarter today"

## Security Page Sections (security.php)
- Hero: "Your Money, Fully Protected"
- Feature cards (Phosphor icons):
  - AES-256 Encryption
  - Two-Factor Authentication
  - 24/7 Fraud Monitoring
  - Regulatory Compliance
  - Secure Data Centres
  - Zero-Knowledge Architecture
- Trust badges / certifications area

## Help/FAQ Page Sections (learnmore.php)
Accordion sections:
- Getting Started (account creation, verification, first deposit)
- Savings Plans (how they work, interest, withdrawals)
- Fixed Deposits (locking funds, maturity, early exit)
- Loans (eligibility, approval, repayment)
- Transfers (limits, speed, fees)
- Account & Security (2FA, password, KYC)

## Routes to Add/Update
In `.htaccess`:
```
/products  → /pages/public/solutions.php
/security  → /pages/public/security.php
/help      → /pages/public/learnmore.php
```
In `index.php` router: add matching cases.

## Verification
- [ ] All nav links work (including Products dropdown)
- [ ] Mobile drawer opens with correct links
- [ ] Homepage has no crypto references
- [ ] /products, /security, /help routes resolve
- [ ] Legal pages have CrestVale branding
- [ ] Contact form submits (existing API)
