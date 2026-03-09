# arqoracapital

> Created by **Wayne** using WebStarter CLI

## Quick Setup

```bash
# 1. Import database
mysql -u root -p < dbschema/database.sql

# 2. Configure environment
cp .env .env.local
# Edit .env with your DB credentials and SMTP settings

# 3. Install dependencies (if not already done)
composer install

# 4. Start dev server
php -S localhost:8000
```

## Features

- ✅ Authentication (login, register, forgot/reset password)
- ✅ Admin panel (`/pages/admin/dashboard.php`)
- ✅ PHPMailer (SMTP email)
- ✅ Phosphor Icons (`assets/icons/`)

## Project Structure

```
├── api/
│   ├── auth/           # Login, register, password reset
│   ├── admin-dashboard/# Admin API endpoints
│   ├── tracking/       # Tracking endpoint
│   ├── utilities/      # Auth check, contact, image upload, email templates
│   └── webhooks/       # Webhook handler
├── assets/
│   ├── css/            # Stylesheets (+ admin/styles.css)
│   ├── fonts/          # Custom fonts
│   ├── icons/          # Phosphor Icons
│   └── images/
├── config/             # DB, env, constants
├── pages/
│   ├── public/         # Public-facing pages
│   ├── user/           # Authenticated user pages
│   └── admin/          # Admin pages
├── dbschema/           # SQL schema
└── uploads/            # User uploads
```
