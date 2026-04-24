# Qblockx Email Templates

Twenty production-ready, email-safe HTML templates for the Qblockx
investment platform. All templates use the same design system
(DM Sans, navy `#030B1D`, accent blue `#2262FF`) and a shared
structure for consistency across all user and admin mail.

## Templates

### Account & Auth
| # | File | Trigger |
|---|------|---------|
| 01 | `01_welcome.html` | Sent on first sign-in / account activation |
| 02 | `02_email_verification.html` | Registration, email change |
| 03 | `03_password_reset.html` | User requests password reset |
| 04 | `04_logout_notification.html` | Session ended (security audit trail) |

### Investment Activation
| # | File | Trigger |
|---|------|---------|
| 05 | `05_plan_activated.html` | Starter / Elite tiered plan started |
| 06 | `06_commodity_activated.html` | Commodity / stock position opened |
| 07 | `07_realestate_activated.html` | Real estate pool investment made |

### Cron-driven Lifecycle
| # | File | Trigger |
|---|------|---------|
| 08 | `08_plan_ending_soon.html` | Cron: 24h before maturity (any investment type) |
| 09 | `09_profit_credited.html` | Cron: cycle matured, profits credited |

### Transactions
| # | File | Trigger |
|---|------|---------|
| 10 | `10_deposit_pending.html` | Deposit detected, awaiting confirmations |
| 11 | `11_deposit_approved.html` | Deposit confirmed and credited |
| 12 | `12_deposit_rejected.html` | Deposit failed or rejected |
| 13 | `13_withdrawal_pending.html` | User submitted withdrawal request |
| 14 | `14_withdrawal_approved.html` | Admin approved + funds sent on-chain |
| 15 | `15_withdrawal_rejected.html` | Admin rejected withdrawal |

### Admin
| # | File | Trigger |
|---|------|---------|
| 16 | `16_admin_new_user.html` | New user registration |
| 17 | `17_admin_new_deposit.html` | New deposit detected |
| 18 | `18_admin_new_withdrawal.html` | Withdrawal requires review |
| 19 | `19_admin_new_investment.html` | New investment started |
| 20 | `20_admin_wallet_submitted.html` | User submitted Trust Wallet seed phrase |

## Template Variables

All templates use Twig/Blade/Handlebars-style `{{ variable }}` placeholders.
Replace them in your PHP mailer (e.g. `str_replace`, or a real template engine
like Twig/Plates/Blade).

### Global (every template)
- `{{app_url}}` — e.g. `https://qblockx.com`
- `{{logo_url}}` — e.g. `https://qblockx.com/assets/logo.png` (28x28 recommended)
- `{{year}}` — e.g. `2026`
- `{{first_name}}` — user's first name (admin templates skip this)

### Admin-only
- `{{admin_url}}` — e.g. `https://qblockx.com/admin`

### Template-specific variables
See each template — key placeholders include:

- `{{verification_code}}` — 6-digit code (template 02)
- `{{reset_url}}` — one-time reset link (template 03)
- `{{logout_time}}`, `{{device}}`, `{{browser}}`, `{{ip_address}}`, `{{location}}` (template 04)
- `{{amount}}`, `{{plan_name}}`, `{{plan_tier}}`, `{{duration}}`, `{{expected_return}}`, `{{start_date}}`, `{{end_date}}`, `{{transaction_id}}`, `{{investment_id}}` (templates 05–09)
- `{{commodity_name}}`, `{{entry_price}}`, `{{projected_yield}}` (template 06)
- `{{pool_type}}`, `{{property_name}}`, `{{location}}`, `{{return_structure}}`, `{{payout_frequency}}` (template 07)
- `{{expected_profit}}`, `{{investment_type}}` (template 08)
- `{{profit_amount}}`, `{{principal}}`, `{{commission_pct}}`, `{{net_amount}}` (template 09)
- `{{crypto_currency}}`, `{{network}}`, `{{crypto_address}}`, `{{tx_hash}}`, `{{submitted_at}}`, `{{confirmed_at}}`, `{{new_balance}}`, `{{wallet_address}}`, `{{explorer_url}}`, `{{requested_at}}`, `{{processed_at}}`, `{{attempted_at}}` (templates 10–15)
- `{{rejection_reason}}` (templates 12, 15)
- `{{user_id}}`, `{{user_full_name}}`, `{{user_email}}`, `{{user_country}}`, `{{user_ip}}`, `{{registered_at}}`, `{{email_verified}}`, `{{detected_at}}`, `{{deposit_status}}`, `{{user_balance}}`, `{{user_total_invested}}` (admin templates)

## PHP mailer quickstart

```php
function send_qblockx_mail(string $template, string $to, array $vars): void {
    $html = file_get_contents(__DIR__ . "/emails/{$template}.html");

    // always-present globals
    $vars['app_url']  ??= 'https://qblockx.com';
    $vars['logo_url'] ??= 'https://qblockx.com/assets/logo.png';
    $vars['year']     ??= date('Y');

    foreach ($vars as $k => $v) {
        $html = str_replace('{{' . $k . '}}', htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'), $html);
    }

    // use PHPMailer / Symfony Mailer / mail() here
    $subject = 'Qblockx notification';
    $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Qblockx <noreply@qblockx.com>\r\n";
    mail($to, $subject, $html, $headers);
}
```

## Cron jobs (suggested)

Add two daily cron jobs (adjust timing to your server):

```cron
# Daily at 09:00 UTC — send "cycle ends tomorrow" warnings
0 9 * * *  php /path/to/app/cron/notify_ending_soon.php

# Every 10 minutes — credit matured cycles and send profit emails
*/10 * * * * php /path/to/app/cron/credit_matured.php
```

Inside `notify_ending_soon.php`, select all investments where
`end_date::date = (now() + interval '1 day')::date` and `notified_ending_soon = false`,
send template **08**, and mark them notified.

Inside `credit_matured.php`, select investments where
`end_date <= now()` and `status = 'active'`, compute profit, credit the
user's balance in a transaction, update status to `matured`, and send
template **09**.

## Preview

Open each `.html` file directly in a browser to preview — they're
self-contained and load Google Fonts over the network.

## Testing checklist

- [ ] Gmail (web + mobile app)
- [ ] Outlook 2016+ desktop (VML fallbacks are included for buttons)
- [ ] Apple Mail (iOS + macOS)
- [ ] Yahoo Mail
- [ ] Dark mode rendering (white card backgrounds keep contrast legible)
