# Mozn

Mozn is a full-stack e-commerce platform built with PHP and MySQL, providing a complete shopping experience for customers and administrators. The platform includes user authentication, product management, shopping cart and checkout workflows, order management, reviews, wishlist, coupons, customer addresses, and an administration dashboard.

The project also includes a standalone Python-based machine learning module for predicting online shopper purchase intention using session and behavioral data.

## Features

* **User Authentication** — Registration, login, logout, session management, profile management, and role-based access control.
* **Product Catalog** — Product browsing, product details, categories, search, filtering, sorting, pricing, and image management.
* **Shopping Cart & Checkout** — Session-based cart management, quantity updates, discount handling, checkout, and order creation.
* **Order Management** — Order records, order items, order status management, order history, and shipping addresses.
* **Wishlist & Reviews** — Product wishlists, ratings, and customer reviews.
* **Coupon System** — Percentage-based and fixed-value discounts with expiration dates.
* **Admin Dashboard** — Administrative tools for product management and store activity monitoring.
* **Bilingual Interface** — Arabic and English localization with RTL/LTR layout support.
* **Responsive UI** — Responsive layouts, dark-mode support, and interactive frontend components.
* **Machine Learning Module** — Python-based purchase-intention prediction using online shopper behavioral data.
* **Technical SEO** — Includes `robots.txt` and `sitemap.xml` to support search-engine discoverability.

## Technology Stack

**Backend**

* PHP
* PDO

**Database**

* MySQL / MariaDB

**Frontend**

* HTML5
* Tailwind CSS
* Alpine.js
* JavaScript

**Machine Learning**

* Python
* Pandas
* NumPy
* Scikit-learn
* Joblib

## Project Structure

```text
mozn/
├── admin/                 # Administration dashboard and management functionality
├── ai/                    # Machine learning scripts, models, and dataset
├── assets/                # CSS, JavaScript, and image assets
├── includes/              # Shared PHP components and application helpers
├── logs/                  # Local runtime logs (not committed)
├── index.php              # Application homepage
├── shop.php               # Product catalog
├── product.php            # Product details
├── cart.php               # Shopping cart
├── checkout.php           # Checkout and order creation
├── profile.php            # User profile and account management
├── wishlist.php           # Wishlist
├── login.php              # User login
├── register.php           # User registration
├── database.sql           # Database schema and demo data
├── config.example.php     # Example application configuration
├── robots.txt             # Search-engine crawling rules
└── sitemap.xml            # XML sitemap
```

## Setup

### Requirements

* PHP 7.4 or later
* MySQL or MariaDB
* Apache or Nginx
* Python 3.x for working with the machine learning module

### Installation

1. Clone the repository:

```bash
git clone https://github.com/Mazenalodini/mozn.git
cd mozn
```

2. Create a MySQL or MariaDB database.

3. Import `database.sql` into the newly created database.

4. Create `config.php` from `config.example.php`.

5. Update the database configuration for your local environment:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'mozn_db');
```

6. Configure the application URL and environment settings as required.

7. Place the project in your PHP web server directory and open the application through your configured local URL.

## Machine Learning

The `ai/` directory contains a standalone machine learning component for online shopper purchase-intention prediction.

The module includes:

* `Training_script.py` — Model training and preprocessing workflow.
* `Prediction_script.py` — Prediction and inference workflow.
* `online_shoppers_intention.csv` — Shopper behavior dataset.
* `shopper_model.pkl` — Trained Random Forest model.
* `le_month.pkl` — Saved month encoder.
* `le_visitor.pkl` — Saved visitor-type encoder.

The prediction pipeline processes session and behavioral features to estimate purchase intention.

## Database

The application uses a relational MySQL/MariaDB database containing entities for:

* Users
* Categories
* Products
* Orders
* Order Items
* Reviews
* Wishlist
* Coupons
* Addresses

The included `database.sql` provides the database schema and demo data for local or demonstration setup.

## Documentation

* [Deployment Guide](DEPLOYMENT.md)
