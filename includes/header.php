<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Commerce</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet"  href="/tasks/css/style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg custom-navbar">
<div class="container-fluid px-5">

<a class="navbar-brand logo" href="/tasks/index.php">
    MyShop
</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">
<form class="search-form" action="search.php" method="GET">
    <i class="bi bi-search"></i>
    <input 
        type="search"
        name="query"
        placeholder="Search products..."
        value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
        >
</form>
<ul class="navbar-nav align-items-center">
<li class="nav-item">
<a class="nav-link active" href="/tasks/index.php">
Home
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/tasks/categories.php">
Products
</a>
</li>
    <li class="nav-item dropdown ms-2">
    <a class="nav-link dropdown-toggle user-icon"
        href="#"
        role="button"
        data-bs-toggle="dropdown">
        <i class="bi bi-person-circle fs-4"></i>
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="/tasks/logout.php">
                    Logout
                </a>
            </li>
        <?php else: ?>
            <li>
                <a class="dropdown-item" href="/tasks/login.php">
                    Login
                </a>
            </li>

            <li>
                <a class="dropdown-item" href="/tasks/register.php">
                    Register
                </a>
            </li>
        <?php endif; ?>
    </ul>
</li>

<li class="nav-item ms-3">
    <a class="nav-link position-relative" href="/tasks/customerdashboard/dashboard.php">
        <i class="bi bi-cart3 fs-4"></i>
    </a>
</li>
</ul>
</div>
</div>
</nav>