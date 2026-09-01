# E-Commerce Web Application

## 1. Project Overview

This project is a web-based e-commerce application developed using **PHP, PostgreSQL, HTML, CSS, Bootstrap, and JavaScript**.

The application is designed for an online skincare store and allows users to:

* Browse, search, filter, and sort products
* View product details
* Add products to a shopping cart
* Place orders
* Create and manage an account
* View orders and order details
* Subscribe to the newsletter

The project also includes an **admin panel** for managing products, categories, users, orders, and dashboard statistics.
### 📸 Project Preview
<img width="1902" height="951" alt="image" src="https://github.com/user-attachments/assets/5253264c-202e-401f-890a-6d22c854d89d" />
<img width="1898" height="953" alt="image" src="https://github.com/user-attachments/assets/d330154a-270e-4560-9470-10884a827609" />

---

## 2. Technologies Used

* **PHP**
* **PostgreSQL**
* **HTML5 & CSS3**
* **Bootstrap**
* **JavaScript**
* **Git & GitHub**
* **XAMPP**

---

## 3. Database Setup

The application uses **PostgreSQL** with a database named:

```text
tasksproject
```

Main tables:

* `users`
* `categories`
* `products`
* `orders`
* `order_items`
* `newsletter_subscribers`

---

## 4. Installation & Configuration

### Requirements

Make sure the following are installed:

* XAMPP
* PHP
* PostgreSQL
* Git
* Web browser

### Installation

Clone the repository:

```bash
git clone <repository-url>
```

Place the project in:

```text
C:\xampp\htdocs\tasks
```

Create the PostgreSQL database `tasksproject` and configure the connection in:

```text
includes/database.php
```

Example:

```php
$host = "localhost";
$port = "5432";
$dbname = "tasksproject";
$user = "postgres";
$password = "your_password";
```

Make sure **PDO, `pdo_pgsql`, and PHP Sessions** are enabled.

Run the application at:

```text
http://localhost/tasks/
```

---

## 5. Authentication & Security

User authentication is handled through:

```text
includes/user_auth.php
```

Admin authorization is handled through:

```text
admin/admin_auth.php
```

CSRF protection is implemented in:

```text
includes/csrf.php
```

The application also uses **prepared statements** for database queries and `htmlspecialchars()` when displaying user-controlled data to help prevent **SQL Injection and XSS attacks**.

Sensitive database credentials should not be committed to the Git repository.

---

## 6. Project Structure

```text
tasks/
│
├── admin/              # Admin panel
├── includes/           # Database, authentication, CSRF, header & footer
├── css/                # Custom CSS
├── index.php           # Homepage
├── login.php           # Login
├── register.php        # Registration
├── category.php        # Categories & filters
├── search.php          # Product search
├── productdetails.php  # Product details
├── cart.php            # Shopping cart
├── checkout.php        # Checkout
└── README.md
```

---

## 7. Main Features

### Customer

* Registration and login
* Product browsing and search
* Category and price filtering
* Product sorting
* Shopping cart and checkout
* Order history
* Account management
* Newsletter subscription

### Admin

* Admin authentication
* Dashboard statistics
* Product management
* Category management
* User management
* Order management

---

## 8. Project Purpose

The purpose of this project is to demonstrate practical knowledge of **web development, database management, CRUD operations, authentication, authorization, security, and responsive design**.

It demonstrates the integration of a **PHP backend with PostgreSQL** and a responsive frontend using **HTML, CSS, Bootstrap, and JavaScript**.
