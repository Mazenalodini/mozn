# 🚀 Mozn Deployment Guide

Congratulations! Your **Mozn E-Commerce Platform** is ready to go live. Follow these steps to upload your website to any standard PHP/MySQL hosting provider (like Hostinger, Bluehost, Namecheap, or InfinityFree).

---

## 1. Prepare Your Files 📦
1.  Go to your project folder (`c:\xampp\htdocs\mozn`).
2.  **Delete** unnecessary development files:
    - `.git` (folder)
    - `.vscode` (folder)
    - `reset_db.php` (⚠️ **Security Risk**: Never upload this to a live server!)
    - `README.md`, `DEPLOYMENT.md`, `task.md` (Optional, keeps root clean).
3.  **Zip** all remaining files into a single archive called `mozn_upload.zip`.

---

## 2. Prepare Your Database 🗄️
1.  Open **phpMyAdmin** on your local machine (`http://localhost/phpmyadmin`).
2.  Select the `mozn_db` database.
3.  Click the **Export** tab.
4.  Keep settings as "Quick" and Format as "SQL".
5.  Click **Export** to download `mozn_db.sql`.

---

## 3. Upload to Hosting ☁️
1.  Log in to your Hosting Control Panel (cPanel).
2.  Open **File Manager**.
3.  Navigate to `public_html`.
4.  **Upload** your `mozn_upload.zip`.
5.  **Extract** the zip file inside `public_html`.
6.  Ensure `index.php` is directly inside `public_html` (not in a subfolder).

---

## 4. Import Database 📥
1.  In cPanel, find **MySQL Databases**.
2.  **Create a New Database** (e.g., `u123_mozn`).
3.  **Create a New User** (e.g., `u123_admin`) and set a strong password.
4.  **Add User to Database** and grant **All Privileges**.
5.  Go back to cPanel Home and open **phpMyAdmin**.
6.  Select your new empty database.
7.  Click **Import**.
8.  Upload the `mozn_db.sql` file you exported earlier.
9.  Click **Go**.

---

## 5. Configure Connectivity ⚙️
1.  In File Manager, find `config.php` and **Edit** it.
2.  Update the constants with your **Hosting Details**:

```php
// Database Credentials (Get these from your Hosing Panel)
define('DB_HOST', 'localhost'); // Check if your host uses a specific IP, but usually it's localhost
define('DB_USER', 'your_database_username');      // Your NEW database username
define('DB_PASS', 'your_database_password');      // Your NEW database password
define('DB_NAME', 'your_database_name');          // Your NEW database name

// App Settings
define('APP_NAME', 'Mozn');
define('APP_URL', 'https://your-domain.com'); // Your real website URL

// Environment
define('APP_ENV', 'production'); // Ensure this is 'production'
```

---

## 6. Final Checks ✅
1.  Visit your website URL (e.g., `www.your-domain.com`).
2.  Try to **Register** a new account (tests DB write access).
3.  Try to **Login** to Admin Panel using the administrator credentials configured for your local environment.
4.  **Test Email**: Place a fake order and check if the checkout completes (Real emails need SMTP configuration in `includes/mail.php`, for now, they are just logged).

---

## 🆘 Troubleshooting
- **White Screen?**
    - Check `error_log` file in File Manager.
    - Temporarily set `define('APP_ENV', 'local');` in `config.php` to see the error.
- **Database Error?**
    - Double-check `DB_USER` and `DB_PASS` in `config.php`.
    - Ensure the user has "All Privileges" in cPanel.
