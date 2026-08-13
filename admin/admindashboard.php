<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
        }
        .admin-sidebar {
            width: 320px;
            min-height: 100vh;
            background-color: #ffffff;
            padding: 45px 20px;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.07);
        }
        .admin-logo {
            font-size: 30px;
            font-weight: 700;
            color: #eb3f81;
            text-align: center;
            margin-bottom: 70px;
        }
        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .admin-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px 18px;
            color: #444;
            font-size: 21px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.25s ease;
        }
        .admin-nav .nav-link i {
            font-size: 21px;
            width: 25px;
        }
        .admin-nav .nav-link:hover {
            background-color: #fce7f0;
            color: #eb6c9c;
            transform: translateX(4px);
        }
        .admin-layout {
        display: flex;
        min-height: 100vh;
        }
        .admin-content {
        flex: 1;
        padding: 50px;
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                Admin Panel
            </div>
            <nav class="admin-nav">
                <a href="#" class="nav-link active">
                    <span>Dashboard</span>
                </a>

                <a href="admindashboard.php?page=category" class="nav-link">
                    <i class="bi bi-grid"></i>
                    <span>Category</span>
                </a>

                <a href="admindashboard.php?page=products" class="nav-link">
                    <i class="bi bi-bag"></i>
                    <span>Products</span>
                </a>

                <a href="#" class="nav-link">
                    <i class="bi bi-cart3"></i>
                    <span>Orders</span>
                </a>

                <a href="#" class="nav-link">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-envelope"></i>
                    <span>Subscribers</span>
                </a>
            </nav>
        </aside>
    <main class="admin-content">

    <?php

    if(isset($_GET['page'])) {

        $page = $_GET['page'];

        if($page == "products") {

            include "product.php";

        } elseif($page == "category") {

            include "category.php";

        } elseif($page == "orders") {

            include "orders.php";

        } elseif($page == "users") {

            include "users.php";

        } elseif($page == "subscribers") {

            include "subscribers.php";

        } else {

            echo "<h2>Page not found</h2>";

        }

    } else {

        echo "<h2>Welcome to Admin Dashboard</h2>";

    }

    ?>

</main>
    </div>
</body>
</html>