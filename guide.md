# Qblockx — Production Deployment Guide

## Requirements

| Component | Minimum | Recommended |
|---|---|---|
| PHP | 8.0 | 8.2+ with FPM |
| MySQL | 8.0 | 8.0+ |
| Web server | Apache 2.4 | Nginx 1.24 |
| OS | Ubuntu 20.04 | Ubuntu 22.04 LTS |
| SSL | Required | Let's Encrypt (Certbot) |

PHP extensions required: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `json`

---

## 1. Server Prep

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 FPM + required extensions
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-curl php8.2-json php8.2-xml

# Install MySQL 8
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Certbot for SSL
sudo apt install -y certbot python3-certbot-nginx
```

---

## 2. MySQL Setup

```bash
# Secure the MySQL installation
sudo mysql_secure_installation
# → Set root password, remove anonymous users, disable remote root login

# Create the database and dedicated user
sudo mysql -u root -p
```

```sql
CREATE DATABASE qblockx CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'qblockx_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON qblockx.* TO 'qblockx_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Import the schema (one time only — do NOT re-run on a live database)
mysql -u qblockx_user -p qblockx < /var/www/qblockx/dbschema/database.sql
```

---

## 3. Deploy the Code

```bash
# Create the web root
sudo mkdir -p /var/www/qblockx

# Option A: Git clone (recommended)
cd /var/www
sudo git clone https://github.com/YOUR_REPO/qblockx.git

# Option B: Upload via SFTP/SCP, then extract

# Set ownership
sudo chown -R www-data:www-data /var/www/qblockx

# File permissions
sudo find /var/www/qblockx -type f -name "*.php" -exec chmod 644 {} \;
sudo find /var/www/qblockx -type d -exec chmod 755 {} \;

# Uploads directory (if it exists) needs to be writable
sudo chmod -R 775 /var/www/qblockx/uploads
```

---

## 4. Environment Configuration

Copy the example env and fill in your production values:

```bash
sudo cp /var/www/qblockx/.env.example /var/www/qblockx/.env
sudo nano /var/www/qblockx/.env
```

Fill in every value — nothing should stay as a placeholder:

```ini
# Database
DB_HOST=localhost
DB_PORT=3306
DB_NAME=qblockx
DB_USER=qblockx_user
DB_PASS=STRONG_PASSWORD_HERE

# Application
APP_NAME="Qblockx"
APP_URL=https://qblockx.com
APP_ENV=production          # <-- must be "production" on live

# Email (SMTP)
SMTP_HOST=mail.spacemail.com
SMTP_PORT=465
SMTP_USER=support@qblockx.com
SMTP_PASS=YOUR_SMTP_PASSWORD
SMTP_FROM=support@qblockx.com
SMTP_FROM_NAME="Qblockx"

# NOWPayments (crypto payment gateway)
NOWPAYMENTS_API_KEY=YOUR_NOWPAYMENTS_API_KEY
NOWPAYMENTS_PUBLIC_KEY=YOUR_NOWPAYMENTS_PUBLIC_KEY
NOWPAYMENTS_IPN_SECRET=YOUR_IPN_SECRET
NOWPAYMENTS_IPN_CALLBACK_URL=https://qblockx.com/api/webhooks/webhook.php
NOWPAYMENTS_SUCCESS_URL=https://qblockx.com/pages/user/dashboard.php#wallet
NOWPAYMENTS_CANCEL_URL=https://qblockx.com/pages/user/dashboard.php#wallet

# Admin invite code (used for first admin account creation)
ADMIN_INVITE_CODE=CHANGE_TO_SOMETHING_STRONG

# AES-256 key for Trust Wallet phrase encryption (32-byte hex, 64 chars)
APP_KEY=GENERATE_32_BYTE_HEX_HERE
APP_IV=GENERATE_8_BYTE_HEX_HERE
```

**Generate secure APP_KEY / APP_IV:**
```bash
# APP_KEY (32 bytes = 64 hex chars)
openssl rand -hex 32

# APP_IV (8 bytes = 16 hex chars)
openssl rand -hex 8
```

**Lock down the .env file:**
```bash
sudo chmod 640 /var/www/qblockx/.env
sudo chown www-data:www-data /var/www/qblockx/.env
```

---

## 5. Nginx Configuration

Create `/etc/nginx/sites-available/qblockx`:

```nginx
server {
    listen 80;
    server_name qblockx.com www.qblockx.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name qblockx.com www.qblockx.com;

    root /var/www/qblockx;
    index index.php index.html;

    ssl_certificate     /etc/letsencrypt/live/qblockx.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/qblockx.com/privkey.pem;
    include             /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam         /etc/letsencrypt/ssl-dhparams.pem;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Block direct access to sensitive files
    location ~ /\.(env|git|htaccess) { deny all; }
    location ~ ^/(config|dbschema|includes)/  { deny all; }

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include        snippets/fastcgi-php.conf;
        fastcgi_pass   unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    # Block PHP execution in uploads
    location /uploads/ {
        location ~ \.php$ { deny all; }
    }

    # Logs
    access_log /var/log/nginx/qblockx_access.log;
    error_log  /var/log/nginx/qblockx_error.log;
}
```

```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/qblockx /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 6. SSL Certificate

```bash
# Obtain certificate (HTTP must be reachable first)
sudo certbot --nginx -d qblockx.com -d www.qblockx.com

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## 7. PHP-FPM Session Configuration

Edit `/etc/php/8.2/fpm/php.ini` (or create a pool override):

```ini
session.cookie_secure = 1
session.cookie_httponly = 1
session.cookie_samesite = "Lax"
session.gc_maxlifetime = 3600
session.use_strict_mode = 1
```

```bash
sudo systemctl restart php8.2-fpm
```

---

## 8. Cron Jobs

Run as `www-data` (same user as web server) to avoid permission issues:

```bash
sudo crontab -u www-data -e
```

Add these 4 lines:

```cron
# Investment plan maturity — daily at midnight
0 0 * * * php /var/www/qblockx/api/cron/investment-maturity.php >> /var/log/qblockx_cron.log 2>&1

# Commodity position maturity — daily at 01:00
0 1 * * * php /var/www/qblockx/api/cron/commodity-maturity.php >> /var/log/qblockx_cron.log 2>&1

# Real estate periodic payouts — daily at 02:00
0 2 * * * php /var/www/qblockx/api/cron/realestate-payouts.php >> /var/log/qblockx_cron.log 2>&1

# "Ending soon" reminders — daily at 09:00
0 9 * * * php /var/www/qblockx/api/cron/plan-ending-soon.php >> /var/log/qblockx_cron.log 2>&1
```

Create the log file:
```bash
sudo touch /var/log/qblockx_cron.log
sudo chown www-data:www-data /var/log/qblockx_cron.log
```

Test each cron manually before relying on the schedule:
```bash
sudo -u www-data php /var/www/qblockx/api/cron/investment-maturity.php
sudo -u www-data php /var/www/qblockx/api/cron/commodity-maturity.php
sudo -u www-data php /var/www/qblockx/api/cron/realestate-payouts.php
sudo -u www-data php /var/www/qblockx/api/cron/plan-ending-soon.php
```

---

## 9. NOWPayments Webhook

1. Log into your NOWPayments dashboard → **Stores & Projects** → select your project
2. Set the **IPN Callback URL** to: `https://qblockx.com/api/webhooks/webhook.php`
3. Set the **Success URL** and **Cancel URL** to the dashboard wallet page
4. Copy the **IPN Secret** and paste it into `.env` as `NOWPAYMENTS_IPN_SECRET`
5. Enable **Auto-convert** if you want all incoming crypto credited as USD equivalent

To verify the webhook is reachable from NOWPayments' servers:
```bash
curl -I https://qblockx.com/api/webhooks/webhook.php
# Should return HTTP 405 (Method Not Allowed) for a GET — confirms the file is reachable
```

---

## 10. Create the First Admin Account

Register a normal user account through the UI at `https://qblockx.com/pages/auth/register.php`, then promote it via MySQL:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
```

Or use the admin invite code flow if the registration page supports it.

---

## 11. Security Checklist

- [ ] `APP_ENV=production` in `.env` (disables error display)
- [ ] `.env` permissions are `640` and owned by `www-data`
- [ ] `.env` is in `.gitignore` — confirmed, never push secrets
- [ ] `APP_KEY` and `APP_IV` are random values unique to this deployment
- [ ] `ADMIN_INVITE_CODE` changed from the default
- [ ] Nginx blocks direct access to `/config/`, `/dbschema/`, `/includes/`, `.env`
- [ ] SSL certificate installed and HTTP redirects to HTTPS
- [ ] `session.cookie_secure = 1` in PHP config
- [ ] MySQL root password set, remote root access disabled
- [ ] Database user has privileges on `qblockx.*` only (not `*.*`)
- [ ] Firewall: only ports 22, 80, 443 open (`sudo ufw allow 22 80 443 && sudo ufw enable`)

---

## 12. Post-Deployment Smoke Tests

Run these in order after going live:

1. **Homepage loads** — `https://qblockx.com` returns 200
2. **User registration** — register with a real email, confirm verification email arrives
3. **User login** — sign in, confirm sign-in notification email arrives
4. **Admin login** — sign in at `/pages/admin/login.php`, confirm alert email arrives
5. **Deposit flow** — initiate a small crypto deposit, confirm "Deposit Pending" email arrives and `transactions` table has a row with the real currency (e.g., `usdttrc20`), not `USD`
6. **Cron dry-run** — check `/var/log/qblockx_cron.log` after the first midnight pass for success entries
7. **Admin resolves deposit** — approve a deposit via admin panel, confirm "Deposit Approved" email arrives and wallet balance updates
8. **Investment** — start a plan/commodity/real estate investment, confirm activation email arrives instantly (not after SMTP delay)
9. **Withdrawal** — submit a withdrawal, confirm user and admin notification emails arrive
10. **Password reset** — trigger a reset, confirm "Password Changed" email arrives after completing the reset

---

## 13. Ongoing Maintenance

| Task | How |
|---|---|
| Check cron health | `tail -f /var/log/qblockx_cron.log` |
| Check cron DB logs | `SELECT * FROM cron_logs ORDER BY ran_at DESC LIMIT 20;` |
| Renew SSL | Automatic via `certbot.timer`; check with `sudo certbot renew --dry-run` |
| PHP error log | `/var/log/php8.2-fpm.log` or `error_log` setting in php.ini |
| Nginx error log | `/var/log/nginx/qblockx_error.log` |
| DB backups | `mysqldump -u qblockx_user -p qblockx > backup_$(date +%F).sql` (automate with cron) |
