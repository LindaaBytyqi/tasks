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
<style>

    .search-form{
    position:relative;
    display:flex;
    align-items:center;
    /* margin-right: 1220px; */
    flex:1;
    max-width: 500px;
    margin-left:auto;
    margin-right:60px;
}
.search-form i{
    position:absolute;
    left:18px;
    color:#6b7280;
    font-size:23px;
}
.search-form input{
    width:500px;
    height:60px;
    padding-left:50px;
    padding-right:50px;
    border:none;
    border-radius:25px;
    background:#f1f5f9;
    font-size:20px;
    outline:none;
    transition:.3s;
}
.search-form input:focus{
    background:white;
    box-shadow:0 0 0 2px #eb3f81;
}
.search-form input[type="search"]::-webkit-search-cancel-button{
    -webkit-appearance: none;
    appearance: none;
    width:18px;
    height:18px;
    cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='3' stroke-linecap='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'/%3E%3Cline x1='6' y1='6' x2='18' y2='18'/%3E%3C/svg%3E");
    background-repeat:no-repeat;
      background-position:center;
}
</style>
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