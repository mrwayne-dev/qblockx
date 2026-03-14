-- ============================================================
-- CrestVale Bank — Database Schema
-- MySQL 8.x compatible
-- Created by Wayne
-- ============================================================
-- Safe to run on BOTH fresh installs AND existing servers:
--   • CREATE TABLE IF NOT EXISTS  → skips tables that already exist
--   • DELIMITER $$ procedure      → conditional column / key migrations
--   • REPLACE INTO rates          → updates plan data idempotently
--   • INSERT IGNORE               → skips duplicate seed rows
-- ============================================================

CREATE DATABASE IF NOT EXISTS `crestvalebank`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crestvalebank`;

-- ============================================================
-- USERS
-- ============================================================

CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `email`        VARCHAR(255) UNIQUE NOT NULL,
  `password`     VARCHAR(255) NOT NULL,
  `full_name`    VARCHAR(255),
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_verified`  TINYINT(1) NOT NULL DEFAULT 0,
  `role`         ENUM('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- AUTHENTICATION
-- ============================================================

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `email`       VARCHAR(255) NOT NULL,
  `token`       VARCHAR(255) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`        INT NOT NULL,
  `session_token`  VARCHAR(255) UNIQUE NOT NULL,
  `ip_address`     VARCHAR(45),
  `user_agent`     TEXT,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at`     TIMESTAMP NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- uq_ev_user ensures only ONE active verification code exists per user.
-- Application must DELETE the old row (or use INSERT ... ON DUPLICATE KEY UPDATE)
-- before issuing a new code.
CREATE TABLE IF NOT EXISTS `email_verifications` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT NOT NULL,
  `token`       VARCHAR(6) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  TIMESTAMP NOT NULL,
  UNIQUE KEY `uq_ev_user` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- WALLET & BALANCE
-- ============================================================

CREATE TABLE IF NOT EXISTS `wallets` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT NOT NULL UNIQUE,
  `balance`     DECIMAL(18,8) NOT NULL DEFAULT 0.00000000,
  `currency`    VARCHAR(10)   NOT NULL DEFAULT 'USD',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crypto_addresses` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT NOT NULL,
  `currency`    VARCHAR(10) NOT NULL,
  `address`     VARCHAR(255) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_user_currency` (`user_id`, `currency`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TRANSACTIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS `transactions` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT NOT NULL,
  `type`        ENUM(
                  'deposit','withdrawal','transfer',
                  'savings_contribution','savings_withdrawal',
                  'deposit_return',
                  'loan_disbursement','loan_repayment',
                  'interest_credit'
                ) NOT NULL,
  `amount`      DECIMAL(18,8) NOT NULL,
  `currency`    VARCHAR(10)   NOT NULL DEFAULT 'USD',
  `status`      ENUM('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_id`  VARCHAR(255)  DEFAULT NULL,
  `notes`       TEXT          DEFAULT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Migrations for existing servers
--
-- MySQL 8 does NOT support ADD COLUMN IF NOT EXISTS (MariaDB only).
-- We use a stored procedure with INFORMATION_SCHEMA checks instead.
--
-- IMPORTANT: DELIMITER must be changed before defining the procedure
-- so MySQL does not mistake the semicolons inside the body as
-- statement terminators.
-- ============================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS `_cv_migrate`$$

CREATE PROCEDURE `_cv_migrate`()
BEGIN

  -- ── 1. transactions.currency ─────────────────────────────────────────────
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'transactions'
      AND COLUMN_NAME  = 'currency'
  ) THEN
    ALTER TABLE `transactions`
      ADD COLUMN `currency` VARCHAR(10) NOT NULL DEFAULT 'USD' AFTER `amount`;
  END IF;

  -- ── 2. email_verifications.token — shrink to VARCHAR(6) ──────────────────
  IF EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA         = DATABASE()
      AND TABLE_NAME           = 'email_verifications'
      AND COLUMN_NAME          = 'token'
      AND CHARACTER_MAXIMUM_LENGTH > 6
  ) THEN
    ALTER TABLE `email_verifications`
      MODIFY COLUMN `token` VARCHAR(6) NOT NULL;
  END IF;

  -- ── 3. email_verifications: add UNIQUE KEY on user_id ────────────────────
  -- Prevents stale tokens piling up; only one active code per user.
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA    = DATABASE()
      AND TABLE_NAME      = 'email_verifications'
      AND CONSTRAINT_NAME = 'uq_ev_user'
      AND CONSTRAINT_TYPE = 'UNIQUE'
  ) THEN
    ALTER TABLE `email_verifications`
      ADD UNIQUE KEY `uq_ev_user` (`user_id`);
  END IF;

  -- ── 4. users.is_active — admin disable/enable ────────────────────────────
  IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'users'
      AND COLUMN_NAME  = 'is_active'
  ) THEN
    ALTER TABLE `users`
      ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_verified`;
  END IF;

END$$

DELIMITER ;

CALL `_cv_migrate`();
DROP PROCEDURE IF EXISTS `_cv_migrate`;

-- ============================================================
-- SAVINGS PLANS
-- ============================================================

CREATE TABLE IF NOT EXISTS `savings_plans` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT NOT NULL,
  `plan_name`       VARCHAR(100)  NOT NULL,
  `target_amount`   DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `current_amount`  DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `interest_rate`   DECIMAL(5,2)  NOT NULL DEFAULT 5.00,
  `duration_months` INT           NOT NULL DEFAULT 12,
  `status`          ENUM('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FIXED DEPOSITS
-- ============================================================

CREATE TABLE IF NOT EXISTS `fixed_deposits` (
  `id`              INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT NOT NULL,
  `amount`          DECIMAL(18,2) NOT NULL,
  `interest_rate`   DECIMAL(5,2)  NOT NULL,
  `duration_months` INT           NOT NULL,
  `start_date`      DATE          NOT NULL,
  `maturity_date`   DATE          NOT NULL,
  `expected_return` DECIMAL(18,2) NOT NULL,
  `status`          ENUM('active','matured','cancelled') NOT NULL DEFAULT 'active',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- LOANS
-- ============================================================

CREATE TABLE IF NOT EXISTS `loans` (
  `id`                INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`           INT NOT NULL,
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

-- Seed / update rates — 4 plans per product (12 total).
-- REPLACE INTO updates existing rows by primary key and inserts new ones.
-- Any stale rows beyond ID 12 are removed first.
DELETE FROM `rates` WHERE `id` > 12;

REPLACE INTO `rates` (`id`, `product`, `label`, `duration_months`, `rate`, `is_active`) VALUES
  -- Savings Plans (3 / 6 / 12 / 24 months)
  ( 1, 'savings',       'Starter Savings',     3,  3.00, 1),
  ( 2, 'savings',       'Standard Savings',    6,  4.00, 1),
  ( 3, 'savings',       'Plus Savings',       12,  5.00, 1),
  ( 4, 'savings',       'Premium Savings',    24,  6.50, 1),
  -- Fixed Deposits (3 / 6 / 12 / 24 months)
  ( 5, 'fixed_deposit', 'Short-Term Deposit',  3,  7.00, 1),
  ( 6, 'fixed_deposit', 'Mid-Term Deposit',    6, 10.00, 1),
  ( 7, 'fixed_deposit', 'Standard Deposit',   12, 12.00, 1),
  ( 8, 'fixed_deposit', 'Long-Term Deposit',  24, 15.00, 1),
  -- Loans (6 / 12 / 24 / 36 months)
  ( 9, 'loan',          'Short Loan',          6, 15.00, 1),
  (10, 'loan',          'Standard Loan',      12, 12.00, 1),
  (11, 'loan',          'Extended Loan',      24, 10.00, 1),
  (12, 'loan',          'Long-Term Loan',     36,  8.00, 1);

-- ============================================================
-- WITHDRAWAL REQUESTS
-- ============================================================

CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`        INT NOT NULL,
  `amount`         DECIMAL(18,8) NOT NULL,
  `currency`       VARCHAR(10)   NOT NULL DEFAULT 'USD',
  `wallet_address` VARCHAR(255)  NOT NULL,
  `status`         ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes`    TEXT DEFAULT NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `message`     TEXT NOT NULL,
  `ip_address`  VARCHAR(45) DEFAULT NULL,
  `is_read`     TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CRON JOB AUDIT LOG
-- ============================================================

CREATE TABLE IF NOT EXISTS `cron_logs` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `job_name`  VARCHAR(100) NOT NULL,
  `status`    ENUM('success','failed') NOT NULL,
  `message`   TEXT DEFAULT NULL,
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
  ('min_withdrawal',      '10');
