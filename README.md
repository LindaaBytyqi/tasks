# E-Commerce Web Application

## 1. Project Overview

This project is a web-based **e-commerce application** developed for an online skincare and beauty store using **PHP, PostgreSQL, HTML5, CSS3, Bootstrap, and JavaScript**.

Customers can browse and search products, filter and sort products, view product details, manage their shopping cart, place orders, create an account, manage their profile, view orders, and subscribe to the newsletter.

The project also includes an **admin panel** for managing products, categories, users, orders, and website content.


### 📸 Project Preview

<img width="1902" height="951" alt="image" src="https://github.com/user-attachments/assets/8b575bd8-3465-4987-b572-5cd632addd8a" />

<img width="1900" height="955" alt="image" src="https://github.com/user-attachments/assets/5dcd5ca6-a7f1-4756-a9fa-7b549fb1dd2d" />

<img width="1911" height="948" alt="image" src="https://github.com/user-attachments/assets/bc86fc64-597d-484c-8db3-bc98ca091cf2" />

<img width="1919" height="949" alt="image" src="https://github.com/user-attachments/assets/d6410528-450f-49c8-9c33-fbf4833b544c" />


---

## 2. Technologies Used

* PHP
* PostgreSQL
* PDO

---

## 2. Technologies Used

* PHP
* PostgreSQL
* PDO
* HTML5 & CSS3
* Bootstrap 5.3.3
* JavaScript
* PHPMailer
* Composer
* Git & GitHub
* XAMPP

---

## 3. Database

The application uses **PostgreSQL** with the database:

```text
tasksproject
```

Main tables include:

```text
users
categories
products
orders
order_items
newsletter_subscribers
hero_slides
about_sections
blogs
contact_settings
```

---

## 4. Main Features

### Customer

* Registration and login
* Product browsing and search
* Category, price and stock filtering
* Product sorting
* Shopping cart and checkout
* Order history
* Account management
* Newsletter subscription
* Contact form
* Blog

### Admin

* Admin authentication and authorization
* Dashboard statistics
* Product management
* Category management
* User management
* Order management
* Hero slide management
* About Us content management
* Blog management
* Contact information management

---

## 5. Email Integration

The Contact Us form is connected to **Gmail SMTP** using **PHPMailer**.

Composer is used to manage the PHPMailer dependency.

---

## 6. Authentication & Security

The application includes:

* User authentication
* Admin authorization
* PHP sessions
* Password hashing
* CSRF protection
* PDO prepared statements
* `htmlspecialchars()` for output escaping

These mechanisms help protect the application against common security issues such as **SQL Injection, XSS, and CSRF attacks**.

---

## 7. Project Structure

```text
tasks/
│
├── admin/              # Admin panel
├── includes/           # Database, authentication, CSRF, header & footer
├── css/                # Custom CSS
├── images/             # Website images
├── index.php           # Homepage
├── product.php         # Products
├── productdetails.php  # Product details
├── cart.php            # Shopping cart
├── checkout.php        # Checkout
├── aboutus.php         # About Us
├── contactus.php       # Contact Us
├── blogs.php           # Blog
├── dashboard.php       # User dashboard
├── composer.json
└── README.md
```

---

## 8. Installation

Install the required software:

* XAMPP
* PHP
* PostgreSQL
* pgAdmin
* Composer
* Git

Clone the repository and place it in:

```text
C:\xampp\htdocs\tasks
```

Create the PostgreSQL database:

```text
tasksproject
```

Configure the database connection in:

```text
includes/database.php
```

Install Composer dependencies:

```bash
composer install
```

Run the application:

```text
http://localhost/tasks/
```

---

## 9. Project Purpose

The purpose of this project is to demonstrate practical knowledge of **web development, database management, CRUD operations, authentication, authorization, security, responsive design, email integration, and dynamic content management** using PHP and PostgreSQL.
