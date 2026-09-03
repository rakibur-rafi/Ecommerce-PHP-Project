# Maze - E-Commerce Website

Maze is a PHP and MySQL e-commerce storefront with a responsive customer-facing shop and an admin area for managing products, categories, and orders. The app uses a shared layout, session-based cart handling, and database-driven product listings to support the full shopping flow from browsing to checkout.

## Screenshot

![Image](https://github.com/user-attachments/assets/ebbaaa04-0cfc-403d-a3a9-7f01ee3d0f62)

## Overview

- Browse products by category and view product details
- Add items to the cart and manage quantities
- Register, log in, and access account pages
- Place orders and view order history
- Use admin pages to create and edit products and categories
- Upload and display multiple product images

## Main Technologies

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Bootstrap

## Dependencies


- PHP with `mysqli`
- MySQL
- Apache web server
- Bootstrap 
- SimpleBar
- Tiny Slider
- Smooth Scroll
- Vendor UI assets already included in `vendor/`

## Main Features

- User registration and login
- Session-based cart
- Product browsing and category filtering
- Product detail pages with image support
- Checkout flow and order handling
- Customer account pages
- Admin product management
- Admin category management
- Order status updates
- Responsive layout for desktop and mobile

## Run Locally

1. Install a local PHP stack such as XAMPP, WAMP, or MAMP.
2. Place the project folder in your web server document root, for example `htdocs/ecommerce`.
3. Create a MySQL database named `ecommerce`.
4. Import the project database schema and data into that database. The repository does not include a `.sql` dump, so you may need to restore your own backup or recreate the tables.
5. Open `files/functions.php` and confirm the database credentials and `BASE_URL` match your local setup.
6. Start Apache and MySQL.
7. Open `http://localhost/ecommerce/` in your browser.

## Links
- Source code: https://github.com/rakibur-rafi/Ecommerce-PHP-Project

