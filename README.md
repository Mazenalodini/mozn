# Mozn E-Commerce Platform

Mozn is a comprehensive, feature-rich E-Commerce platform built with PHP and MySQL. It is designed to provide a robust shopping experience with an integrated machine learning component for predicting purchase intentions.

## 🚀 Features

- **User Authentication:** Secure registration, login, and profile management for users and administrators.
- **Product Catalog:** Browse, view details, and manage products.
- **Shopping Cart & Checkout:** Seamless cart management and order processing.
- **AI-Powered Insights:** Python-based machine learning module for online shopper purchase-intention prediction.
- **Admin Dashboard:** Full control over categories, products, users, and orders.
- **SEO Ready:** Optimized structure with `sitemap.xml` and `robots.txt` out of the box.

## 🛠️ Technology Stack

- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, JavaScript (Vanilla)
- **Machine Learning:** Python, scikit-learn, pandas (Pickled models loaded via `Prediction_script.py`)

## 📁 Project Structure

```text
mozn/
├── admin/               # Admin dashboard and management scripts
├── ai/                  # ML models, datasets, and prediction scripts
├── assets/              # Static assets (CSS, JS, Images)
├── includes/            # Reusable PHP components (Header, Footer, Mail, etc.)
├── logs/                # System and email logs
├── index.php            # Main homepage
├── shop.php             # Product catalog page
├── product.php          # Single product view
├── cart.php             # Shopping cart
├── checkout.php         # Checkout process
└── database.sql         # Database schema and seed data
```

## ⚙️ Setup & Installation

### Prerequisites
- Web Server (Apache/Nginx) with PHP 7.4 or 8.x.
- MySQL or MariaDB Database.
- Python 3.x (optional, only if retraining the ML model).

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/<USERNAME>/<REPOSITORY>.git
   cd mozn
   ```

2. **Database Setup:**
   - Create a new MySQL database (e.g., `mozn_db`).
   - Import the `database.sql` file into your newly created database.

3. **Configuration:**
   - Copy `config.example.php` to `config.php`.
   - Update the database credentials in `config.php`:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'mozn_db');
     ```

4. **Running the Application:**
   - Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP or `public_html`).
   - Access the site via your browser at `http://localhost/mozn` (or your configured local domain).

## 🧠 Machine Learning Component

The `ai/` directory contains an ML model that predicts whether a visitor will make a purchase based on their session metrics.
- The model is pre-trained and saved as `shopper_model.pkl`.
- To generate predictions, the system uses `Prediction_script.py`, which processes incoming user data and outputs a purchase probability.

## 📄 Documentation

- [Deployment Guide](DEPLOYMENT.md)
- [SEO Guide](SEO_GUIDE.md)

## 🔒 Security Note

Please ensure that `config.php`, `logs/`, and `reset_db.php` (if applicable) are securely handled and never exposed publicly.
