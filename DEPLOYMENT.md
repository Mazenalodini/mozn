# Mozn Deployment Guide

This guide describes the general steps required to deploy the Mozn E-Commerce Platform in a PHP/MySQL hosting environment.

---

## 1. Prepare the Application

1. Upload the project files to your hosting environment.
2. Make sure `index.php` is located in the web root or the configured application directory.
3. Do not deploy local-only files such as `config.php`, `.env`, logs, or development-specific files.

---

## 2. Prepare the Database

1. Create a MySQL or MariaDB database on your hosting provider.
2. Create a database user and assign the required privileges.
3. Import the provided `database.sql` file into the new database.

The included SQL file provides the database schema and demo data required for a local or demonstration setup.

---

## 3. Configure the Application

Create the production configuration file from the provided template:

```bash
cp config.example.php config.php
```

Update the database and application settings with your hosting environment:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');

define('APP_NAME', 'Mozn');
define('APP_URL', 'https://your-domain.com');

define('APP_ENV', 'production');
```

Keep `config.php` private and do not commit it to version control.

---

## 4. Verify the Deployment

After configuration:

1. Open the application in a browser.
2. Verify that the homepage loads correctly.
3. Test user registration and login.
4. Test product browsing and product details.
5. Test shopping cart and checkout workflows.
6. Verify that orders are stored correctly in the database.
7. Verify the administration dashboard using an administrator account configured for the deployment environment.

---

## 5. Email Handling

The current application uses the local logging mechanism implemented in `includes/mail.php` for demonstration purposes.

For production email delivery, replace the demonstration mail implementation with a properly configured email service or SMTP solution.

Do not store SMTP credentials directly in source code.

---

## 6. Troubleshooting

### Blank or White Page

Check the server's PHP error logs and verify that:

* PHP is enabled and supported.
* The database configuration is correct.
* Required files are present.
* File and directory permissions are appropriate.

### Database Connection Error

Verify:

* `DB_HOST`
* `DB_USER`
* `DB_PASS`
* `DB_NAME`

Also confirm that the database user has the required privileges.

### Application Configuration

For local debugging, `APP_ENV` can be set to:

```php
define('APP_ENV', 'local');
```

For production deployments, use:

```php
define('APP_ENV', 'production');
```

---

## Notes

This deployment guide describes the general application setup and should be adapted to the specific hosting environment and server configuration.
