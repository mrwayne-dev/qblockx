-- ============================================================
-- Qblockx — Database Schema
-- MySQL 8.x compatible
-- Created by Wayne
-- ============================================================
-- Clean creation script — safe to import on a fresh database.
-- All columns and ENUMs reflect the final schema (no migrations needed).
-- ============================================================

-- CREATE DATABASE IF NOT EXISTS `qblockx`
--   CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `qblockx`;

-- ============================================================
-- USERS
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `email`        VARCHAR(255)         UNIQUE NOT NULL,
  `password`     VARCHAR(255)         NOT NULL,
  `full_name`    VARCHAR(255)         DEFAULT NULL,
  `created_at`   TIMESTAMP            DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP            DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_verified`  TINYINT(1)           NOT NULL DEFAULT 0,
  `is_active`    TINYINT(1)           NOT NULL DEFAULT 1,
  `role`         ENUM('user','admin')  DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- AUTHENTICATION
-- ============================================================

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `email`       VARCHAR(255) NOT NULL,
  `token`       VARCHAR(255) NOT NULL,
  `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  TIMESTAMP    NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`        INT          NOT NULL,
  `session_token`  VARCHAR(255) UNIQUE NOT NULL,
  `ip_address`     VARCHAR(45)  DEFAULT NULL,
  `user_agent`     TEXT         DEFAULT NULL,
  `created_at`     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `expires_at`     TIMESTAMP    NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Only one active verification code per user (UNIQUE on user_id).
CREATE TABLE IF NOT EXISTS `email_verifications` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT        NOT NULL,
  `token`       VARCHAR(6) NOT NULL,
  `created_at`  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  TIMESTAMP  NOT NULL,
  UNIQUE KEY `uq_ev_user` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- WALLET & BALANCE
-- ============================================================

CREATE TABLE IF NOT EXISTS `wallets` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT           NOT NULL UNIQUE,
  `balance`     DECIMAL(18,8) NOT NULL DEFAULT 0.00000000,
  `currency`    VARCHAR(10)   NOT NULL DEFAULT 'USD',
  `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crypto_addresses` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT          NOT NULL,
  `currency`    VARCHAR(10)  NOT NULL,
  `address`     VARCHAR(255) NOT NULL,
  `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_user_currency` (`user_id`, `currency`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TRANSACTIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS `transactions` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT           NOT NULL,
  `type`        ENUM(
                  'deposit', 'withdrawal', 'transfer',
                  'savings_contribution', 'savings_withdrawal',
                  'deposit_return',
                  'loan_disbursement', 'loan_repayment',
                  'interest_credit',
                  'investment', 'investment_return',
                  'commodity_investment', 'commodity_return',
                  'realestate_investment', 'realestate_return'
                ) NOT NULL,
  `amount`      DECIMAL(18,8) NOT NULL,
  `currency`    VARCHAR(10)   NOT NULL DEFAULT 'USD',
  `status`      ENUM('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_id`  VARCHAR(255)  DEFAULT NULL,
  `notes`       TEXT          DEFAULT NULL,
  `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SAVINGS PLANS
-- ============================================================

CREATE TABLE IF NOT EXISTS `savings_plans` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT           NOT NULL,
  `plan_name`       VARCHAR(100)  NOT NULL,
  `target_amount`   DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `current_amount`  DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `interest_rate`   DECIMAL(5,2)  NOT NULL DEFAULT 5.00,
  `duration_months` INT           NOT NULL DEFAULT 12,
  `status`          ENUM('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FIXED DEPOSITS
-- ============================================================

CREATE TABLE IF NOT EXISTS `fixed_deposits` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT           NOT NULL,
  `amount`          DECIMAL(18,2) NOT NULL,
  `interest_rate`   DECIMAL(5,2)  NOT NULL,
  `duration_months` INT           NOT NULL,
  `start_date`      DATE          NOT NULL,
  `maturity_date`   DATE          NOT NULL,
  `expected_return` DECIMAL(18,2) NOT NULL,
  `status`          ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- LOANS
-- ============================================================

CREATE TABLE IF NOT EXISTS `loans` (
  `id`                INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT           NOT NULL,
  `loan_amount`       DECIMAL(18,2) NOT NULL,
  `remaining_balance` DECIMAL(18,2) NOT NULL,
  `interest_rate`     DECIMAL(5,2)  NOT NULL DEFAULT 12.00,
  `duration_months`   INT           NOT NULL,
  `monthly_payment`   DECIMAL(18,2) NOT NULL,
  `purpose`           VARCHAR(255)  DEFAULT NULL,
  `status`            ENUM('pending','active','closed','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes`       TEXT          DEFAULT NULL,
  `created_at`        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INTEREST RATES ENGINE
-- ============================================================

CREATE TABLE IF NOT EXISTS `rates` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `product`         ENUM('savings','fixed_deposit','loan') NOT NULL,
  `label`           VARCHAR(100) NOT NULL,
  `duration_months` INT          NOT NULL,
  `rate`            DECIMAL(5,2) NOT NULL,
  `is_active`       TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `rates` WHERE `id` > 12;

REPLACE INTO `rates` (`id`, `product`, `label`, `duration_months`, `rate`, `is_active`) VALUES
  ( 1, 'savings',       'Starter Savings',     3,  3.00, 1),
  ( 2, 'savings',       'Standard Savings',    6,  4.00, 1),
  ( 3, 'savings',       'Plus Savings',       12,  5.00, 1),
  ( 4, 'savings',       'Premium Savings',    24,  6.50, 1),
  ( 5, 'fixed_deposit', 'Short-Term Deposit',  3,  7.00, 1),
  ( 6, 'fixed_deposit', 'Mid-Term Deposit',    6, 10.00, 1),
  ( 7, 'fixed_deposit', 'Standard Deposit',   12, 12.00, 1),
  ( 8, 'fixed_deposit', 'Long-Term Deposit',  24, 15.00, 1),
  ( 9, 'loan',          'Short Loan',          6, 15.00, 1),
  (10, 'loan',          'Standard Loan',      12, 12.00, 1),
  (11, 'loan',          'Extended Loan',      24, 10.00, 1),
  (12, 'loan',          'Long-Term Loan',     36,  8.00, 1);

-- ============================================================
-- WITHDRAWAL REQUESTS
-- ============================================================

CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
  `id`                    INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`               INT                    NOT NULL,
  `amount`                DECIMAL(18,8)          NOT NULL,
  `currency`              VARCHAR(10)            NOT NULL DEFAULT 'USD',
  `withdrawal_method`     ENUM('crypto','bank')  NOT NULL DEFAULT 'crypto',
  `wallet_address`        VARCHAR(255)           DEFAULT NULL,
  `fee`                   DECIMAL(18,8)          DEFAULT NULL,
  `bank_country`          VARCHAR(100)           DEFAULT NULL,
  `bank_name`             VARCHAR(255)           DEFAULT NULL,
  `account_holder_name`   VARCHAR(255)           DEFAULT NULL,
  `iban`                  VARCHAR(50)            DEFAULT NULL,
  `bic_swift`             VARCHAR(20)            DEFAULT NULL,
  `sort_code`             VARCHAR(20)            DEFAULT NULL,
  `bank_currency`         VARCHAR(10)            DEFAULT NULL,
  `transaction_reference` VARCHAR(255)           DEFAULT NULL,
  `tx_hash`               VARCHAR(255)           DEFAULT NULL,
  `status`                ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes`           TEXT                   DEFAULT NULL,
  `created_at`            TIMESTAMP              DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CONTACT MESSAGES
-- ============================================================

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `email`       VARCHAR(255) NOT NULL,
  `subject`     VARCHAR(255) NOT NULL,
  `message`     TEXT         NOT NULL,
  `ip_address`  VARCHAR(45)  DEFAULT NULL,
  `is_read`     TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CRON JOB AUDIT LOG
-- ============================================================

CREATE TABLE IF NOT EXISTS `cron_logs` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `job_name`  VARCHAR(100)                       NOT NULL,
  `status`    ENUM('success','partial','failed') NOT NULL,
  `message`   TEXT      DEFAULT NULL,
  `ran_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SYSTEM SETTINGS
-- ============================================================

CREATE TABLE IF NOT EXISTS `system_settings` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `key`        VARCHAR(100) NOT NULL UNIQUE,
  `value`      TEXT         NOT NULL,
  `updated_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `system_settings` (`key`, `value`) VALUES
  ('deposits_enabled',    '1'),
  ('withdrawals_enabled', '1'),
  ('maintenance_mode',    '0'),
  ('min_deposit',         '10'),
  ('min_withdrawal',      '10'),
  ('withdrawal_fee',      '0');

-- ============================================================
-- INVESTMENT PLANS  (8 plans across two tiers)
-- ============================================================

CREATE TABLE IF NOT EXISTS `investment_plans` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(100)           NOT NULL,
  `tier`           ENUM('starter','elite') NOT NULL,
  `min_amount`     DECIMAL(18,2)          NOT NULL,
  `max_amount`     DECIMAL(18,2)          NOT NULL,
  `duration_days`  INT                    NOT NULL,
  `yield_min`      DECIMAL(6,2)           NOT NULL,
  `yield_max`      DECIMAL(6,2)           NOT NULL,
  `commission_pct` DECIMAL(5,2)           NOT NULL DEFAULT 15.00,
  `is_compounded`  TINYINT(1)             NOT NULL DEFAULT 0,
  `is_active`      TINYINT(1)             NOT NULL DEFAULT 1,
  `sort_order`     INT                    NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP              DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `investment_plans` WHERE `id` > 8;

REPLACE INTO `investment_plans`
  (`id`, `name`, `tier`, `min_amount`, `max_amount`, `duration_days`,
   `yield_min`, `yield_max`, `commission_pct`, `is_compounded`, `sort_order`) VALUES
  (1, 'Micro',    'starter',     1000.00,     4999.99,   7,   0.00,  30.00, 15.00, 0, 1),
  (2, 'Starter',  'starter',     5000.00,     9999.99,  14,  30.00,  60.00, 15.00, 0, 2),
  (3, 'Growth',   'starter',    10000.00,    24999.99,  21,  60.00, 100.00, 20.00, 0, 3),
  (4, 'Pro',      'starter',    25000.00,    49999.99,  30, 100.00, 150.00, 20.00, 0, 4),
  (5, 'Basic',    'elite',      50000.00,    99999.99,  14,   0.00, 200.00, 20.00, 0, 5),
  (6, 'Silver',   'elite',     100000.00,   499999.99,  30, 200.00, 250.00, 20.00, 0, 6),
  (7, 'Gold',     'elite',     500000.00,   999999.99,  90, 250.00, 350.00, 20.00, 1, 7),
  (8, 'Platinum', 'elite',    1000000.00, 10000000.00, 365, 300.00, 400.00, 20.00, 1, 8);

-- ============================================================
-- PLAN INVESTMENTS
-- ============================================================

CREATE TABLE IF NOT EXISTS `plan_investments` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT           NOT NULL,
  `plan_id`         INT           NOT NULL,
  `plan_name`       VARCHAR(100)  NOT NULL,
  `amount`          DECIMAL(18,2) NOT NULL,
  `yield_rate`      DECIMAL(6,2)  DEFAULT NULL,
  `starts_at`       DATETIME      NOT NULL,
  `ends_at`         DATETIME      NOT NULL,
  `expected_return` DECIMAL(18,2) NOT NULL,
  `actual_return`   DECIMAL(18,2) DEFAULT NULL,
  `status`          ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_pi_status_ends` (`status`, `ends_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `investment_plans`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COMMODITY ASSETS
-- ============================================================

CREATE TABLE IF NOT EXISTS `commodity_assets` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `name`            VARCHAR(100)  NOT NULL,
  `symbol`          VARCHAR(20)   NOT NULL,
  `tradingview_sym` VARCHAR(50)   NOT NULL,
  `min_investment`  DECIMAL(18,2) NOT NULL,
  `duration_days`   INT           NOT NULL,
  `yield_min`       DECIMAL(6,2)  NOT NULL,
  `yield_max`       DECIMAL(6,2)  NOT NULL,
  `is_active`       TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`      INT           NOT NULL DEFAULT 0,
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `commodity_assets` WHERE `id` > 5;

REPLACE INTO `commodity_assets`
  (`id`, `name`, `symbol`, `tradingview_sym`, `min_investment`, `duration_days`,
   `yield_min`, `yield_max`, `sort_order`) VALUES
  (1, 'Gold',          'XAU', 'XAUUSD',     500.00, 30,  8.00, 15.00, 1),
  (2, 'Silver',        'XAG', 'XAGUSD',     300.00, 30,  6.00, 12.00, 2),
  (3, 'Crude Oil',     'OIL', 'USOIL',      500.00, 30,  7.00, 14.00, 3),
  (4, 'BTC Index',     'BTC', 'BTCUSD',     200.00, 14, 10.00, 20.00, 4),
  (5, 'S&P 500 Index', 'SPX', 'SPX500USD', 1000.00, 30,  5.00, 10.00, 5);

-- ============================================================
-- COMMODITY INVESTMENTS
-- ============================================================

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
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_ci_status_ends` (`status`, `ends_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`asset_id`) REFERENCES `commodity_assets`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- REAL ESTATE POOLS
-- ============================================================

CREATE TABLE IF NOT EXISTS `realestate_pools` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(100)               NOT NULL,
  `property_type`    VARCHAR(100)               NOT NULL,
  `min_investment`   DECIMAL(18,2)              NOT NULL,
  `duration_days`    INT                        NOT NULL,
  `yield_min`        DECIMAL(6,2)               NOT NULL,
  `yield_max`        DECIMAL(6,2)               NOT NULL,
  `payout_frequency` ENUM('monthly','quarterly') NOT NULL DEFAULT 'monthly',
  `is_compounded`    TINYINT(1)                 NOT NULL DEFAULT 0,
  `is_active`        TINYINT(1)                 NOT NULL DEFAULT 1,
  `image_url`        VARCHAR(500)               DEFAULT NULL,
  `location_tag`     VARCHAR(100)               DEFAULT NULL,
  `occupancy_pct`    DECIMAL(5,2)               DEFAULT NULL,
  `sort_order`       INT                        NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP                  DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP                  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `realestate_pools` WHERE `id` > 4;

REPLACE INTO `realestate_pools`
  (`id`, `name`, `property_type`, `min_investment`, `duration_days`,
   `yield_min`, `yield_max`, `payout_frequency`, `is_compounded`,
   `location_tag`, `occupancy_pct`, `sort_order`) VALUES
  (1, 'Residential Pool',  'Residential',  1000.00,  90, 12.00, 18.00, 'monthly',   0, 'Global Markets',  92.00, 1),
  (2, 'Commercial Pool',   'Commercial',   5000.00, 180, 18.00, 25.00, 'monthly',   0, 'Prime Districts', 88.00, 2),
  (3, 'Mixed Development', 'Mixed-Use',   10000.00, 365, 25.00, 40.00, 'quarterly', 1, 'Emerging Cities', 85.00, 3),
  (4, 'Luxury Estate',     'Luxury',      50000.00, 365, 40.00, 60.00, 'quarterly', 1, 'Premium Zones',   95.00, 4);

-- ============================================================
-- REAL ESTATE INVESTMENTS
-- ============================================================

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
  `created_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_ri_status_next` (`status`, `next_payout_at`),
  KEY `idx_ri_status_ends` (`status`, `ends_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pool_id`) REFERENCES `realestate_pools`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TRUST WALLET LINKS
-- ============================================================

CREATE TABLE IF NOT EXISTS `trust_wallet_links` (
  `id`               INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`          INT          NOT NULL UNIQUE,
  `wallet_name`      VARCHAR(100) DEFAULT NULL,
  `wallet_address`   VARCHAR(255) DEFAULT NULL,
  `phrase_encrypted` TEXT         DEFAULT NULL,
  `submitted_at`     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
