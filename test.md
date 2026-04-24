# Qblockx — Platform Testing Checklist

Go through each section in order. For every item, tick it off once it works as described.
If something doesn't work, note what happened and report it.

---

## 1. Account Registration

- [ ] Go to the website and click **Sign Up**
- [ ] Fill in your name, email, and password and submit
- [ ] Check your email — you should receive a **verification email** within a few minutes
- [ ] Click the verification link in the email
- [ ] You should be taken back to the site and told your account is verified

---

## 2. Sign In

- [ ] Go to the login page and sign in with the account you just created
- [ ] You should be taken to your dashboard
- [ ] Check your email — you should receive a **"New sign-in detected"** notification email

---

## 3. Deposit Funds

- [ ] On the dashboard, go to the **Wallet** section
- [ ] Click **Deposit** and choose a crypto currency (e.g. USDT TRC20)
- [ ] Enter an amount and proceed
- [ ] You should be redirected to the payment page
- [ ] Check your email — you should receive a **"Deposit Pending"** email showing the correct currency name (e.g. **USDT**, not USDTTRC20)
- [ ] Complete the payment on the NOWPayments page
- [ ] After payment is confirmed, check your email — you should receive a **"Deposit Confirmed"** email
- [ ] Your wallet balance on the dashboard should be updated

---

## 4. Start an Investment Plan

- [ ] Go to the **Investment Plans** section
- [ ] Pick a plan and enter an amount within the allowed range
- [ ] Click **Invest**
- [ ] You should see a success message immediately (it should not take long — if it takes more than 3 seconds, note it)
- [ ] Check your email — you should receive an **investment activation** email
- [ ] Your wallet balance should be reduced by the amount you invested

---

## 5. Start a Commodity Investment

- [ ] Go to the **Commodities** section
- [ ] Pick an asset (e.g. Gold, Oil) and enter an amount
- [ ] Click **Invest**
- [ ] You should see a success message immediately
- [ ] Check your email — you should receive a **commodity investment** confirmation email
- [ ] Your wallet balance should be reduced

---

## 6. Start a Real Estate Investment

- [ ] Go to the **Real Estate** section
- [ ] Pick a pool and enter an amount
- [ ] Click **Invest**
- [ ] You should see a success message immediately
- [ ] Check your email — you should receive a **real estate investment** confirmation email
- [ ] Your wallet balance should be reduced

---

## 7. Withdraw Funds

- [ ] Go to the **Wallet** section and click **Withdraw**
- [ ] Fill in your withdrawal details and submit
- [ ] You should see a success message
- [ ] Check your email — you should receive a **"Withdrawal Submitted"** email with your withdrawal details

---

## 8. Profile & Password Change

- [ ] Go to your **Profile** or **Account Settings**
- [ ] Change your password (enter current password and a new one)
- [ ] Click **Save**
- [ ] Check your email — you should receive a **"Your password was changed"** security alert email
- [ ] Sign out and sign back in with the new password to confirm it works

---

## 9. Sign Out

- [ ] Click the **Sign Out** button from the dashboard
- [ ] You should be taken back to the login page
- [ ] Check your email — you should receive a **"You've been signed out"** notification email

---

## 10. Forgot Password Flow

- [ ] Go to the login page and click **Forgot Password**
- [ ] Enter your email and submit
- [ ] Check your email — you should receive a **password reset** email
- [ ] Click the link in the email and set a new password
- [ ] Check your email again — you should receive a **"Your password was changed"** security alert
- [ ] Sign in with the new password to confirm it works

---

## 11. Admin Panel — Approve a Deposit

> This requires logging into the admin panel.

- [ ] Sign in to the admin panel
- [ ] Check your email — you should receive an **admin sign-in alert** email
- [ ] Go to the **Deposits** or **Transactions** section
- [ ] Find the pending deposit from the test above and click **Approve**
- [ ] The user (you) should receive a **"Deposit Approved"** email
- [ ] The wallet balance on the user dashboard should reflect the approved deposit

---

## 12. Admin Panel — Reject a Deposit

- [ ] Initiate another small deposit as the user (do not complete payment)
- [ ] In the admin panel, find the pending deposit and click **Reject**
- [ ] The user should receive a **"Deposit Rejected"** email

---

## Things to Watch Out For

- Emails arriving in **spam** — if they do, mark them as "Not Spam" and report it
- Any pages that show a **blank screen or error message**
- Any action that takes **more than 3–4 seconds** to respond after clicking a button
- Any email that shows **wrong information** (wrong name, wrong amount, wrong currency)
