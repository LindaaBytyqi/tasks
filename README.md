# E-Commerce Web Application

## 1. Project Overview

This project is a web-based e-commerce application developed using **PHP, PostgreSQL, HTML, CSS, Bootstrap, and JavaScript**.

The application is designed for an online skincare store and provides the following main functionalities:

* Browse products
* Search for products
* Filter and sort products by category and price
* View detailed product information
* Add products to a shopping cart
* Place orders
* Create and manage a customer account
* View customer orders and order details
* Subscribe to the newsletter

The project also includes an **admin panel** that allows administrators to:

* Manage products
* Manage categories
* Manage users
* Manage orders
* View dashboard statistics
* Monitor store activity

---

## 2. Technologies Used

* **PHP**
* **PostgreSQL**
* **HTML5**
* **CSS3**
* **Bootstrap**
* **JavaScript**
* **Git & GitHub**
* **XAMPP**

---

## 3. Database Setup

The project uses **PostgreSQL** as its database management system.

Create a PostgreSQL database named:

```text
tasksproject
```

The database contains the following main tables:

* `users`
* `categories`
* `products`
* `orders`
* `order_items`
* `newsletter_subscribers`

---

## 4. Installation Instructions


Before installing the project, make sure the following are installed:

* XAMPP
* PHP
* PostgreSQL
* Git
* A web browser

### Installation Steps

#### 1. Clone the repository

Clone the project repository using Git:

```bash
git clone <repository-url>
```

#### 2. Move the project

Place the project inside the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\tasks
```

#### 3. Start XAMPP

Open XAMPP and start the required services.

#### 4. Configure the database

Create the PostgreSQL database and configure the database connection as described in the **Configuration Instructions** section.

#### 5. Open the application

Open the following URL in your web browser:

```text
http://localhost/tasks/
```

---

## 5. Configuration Instructions

Before running the application, check and update the configuration settings according to your local development environment.

### 5.1 Database Configuration

The database connection is configured in:

```text
includes/database.php
```

Update the PostgreSQL credentials:

```php
$host = "localhost";
$port = "5432";
$dbname = "tasksproject";
$user = "postgres";
$password = "your_password";
```

Replace the username and password with the PostgreSQL credentials configured on your local machine.

### 5.2 Project Location

The project should be located inside the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\tasks
```

The application can then be accessed through:

```text
http://localhost/tasks/
```

### 5.3 PHP Configuration

The following PHP features must be enabled:

* PDO
* PostgreSQL PDO driver (`pdo_pgsql`)
* PHP Sessions

PHP sessions are required for:

* User authentication
* Authorization
* Shopping cart functionality

### 5.4 Authentication & CSRF Protection

User authentication is handled by:

```text
includes/user_auth.php
```

Administrator authorization is handled by:

```text
admin/admin_auth.php
```

CSRF protection is implemented through:

```text
includes/csrf.php
```

### 5.5 Security

Database credentials and other sensitive information should not be committed to the Git repository.

For a production environment, sensitive credentials should be stored securely using environment variables or another appropriate configuration method.

### 5.6 Final Check

Before running the application, make sure that:

* PostgreSQL is running
* The `tasksproject` database exists
* Database credentials are correct
* PDO is enabled
* The PostgreSQL PDO driver is enabled
* The project is located inside the XAMPP `htdocs` directory

---

## 6. Project Structure

The project is organized into separate folders and PHP files, with each component responsible for a specific part of the application.

```text
tasks/
│
├── admin/
│   ├── admindashboard.php
│   ├── products.php
│   ├── categories.php
│   ├── users.php
│   └── orders.php
│
├── includes/
│   ├── database.php
│   ├── header.php
│   ├── footer.php
│   ├── user_auth.php
│   └── csrf.php
│
├── css/
│   └── style.css
│
├── index.php
├── login.php
├── register.php
├── category.php
├── search.php
├── productdetails.php
├── cart.php
├── checkout.php
└── README.md
```

### `admin/`

Contains the administrator dashboard and management pages for:

* Products
* Categories
* Users
* Orders

### `includes/`

Contains reusable PHP files used throughout the application, including:

* Database connection
* Header
* Footer
* User authentication
* CSRF protection

### `css/`

Contains the custom CSS used for the website layout, styling, and responsive design.

### Main PHP Files

The main PHP files provide the customer-side functionality of the application, including:

* Homepage
* User registration
* User login
* Product categories
* Product search
* Product details
* Shopping cart
* Checkout
* Newsletter

---

## 7. Main Features

### Customer Features

* User registration and login
* Product browsing
* Product search
* Category filtering
* Price filtering
* Product sorting
* Product details
* Shopping cart
* Checkout
* Order placement
* Customer account management
* Order history
* Newsletter subscription

### Admin Features

* Admin authentication and authorization
* Dashboard statistics
* Product management
* Category management
* User management
* Order management

---


---

## 8. Project Purpose

The purpose of this project is to develop a functional e-commerce application while applying practical concepts in **web development, database management, authentication, authorization, security, responsive design, and CRUD operations**.

The project demonstrates the integration of a PHP backend with a PostgreSQL database and a responsive frontend built using HTML, CSS, Bootstrap, and JavaScript.
