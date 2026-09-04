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
<link rel="stylesheet" href="/tasks/css/style.css">
</head>
<div class="floating-header-wrapper">
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">

            <button
                class="navbar-toggler mobile-menu-btn"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mobileMenu"
                aria-controls="mobileMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <i class="bi bi-list menu-open-icon"></i>
                <i class="bi bi-x-lg menu-close-icon"></i>
            </button>
            <a
                class="navbar-brand logo"
                href="/tasks/index.php"
            >
                MyShop
            </a>

            <div
                class="collapse navbar-collapse mobile-menu"
                id="mobileMenu"
            >
                <div class="navbar-left">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                href="/tasks/index.php"
                            >
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/tasks/product.php"
                            >
                                Products
                            </a>
                        </li>


                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/tasks/contactus.php"
                            >
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-right">
                    <div class="nav-item dropdown search-dropdown">
                        <a
                            class="nav-link search-toggle-btn"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                        >
                            <i class="bi bi-search search-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end search-dropdown-box p-3 shadow-sm">
                            <form
                                action="search.php"
                                method="GET"
                                class="m-0"
                            >
                                <div class="search-input-wrapper">
                                    <input
                                        type="search"
                                        name="query"
                                        id="searchBox"
                                        class="form-control custom-search-input"
                                        placeholder="Search here..."
                                        value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                                    >
                                    <button
                                        type="submit"
                                        class="search-btn-inside"
                                        id="searchButton"
                                    >

                                        <i class="bi bi-search"></i>

                                    </button>
                                    <button
                                        type="button"
                                        class="clear-search-inside"
                                        id="clearSearchInside"
                                    >
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="nav-item dropdown">
                        <a
                            class="nav-link user-icon"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                        >
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/tasks/logout.php"
                                    >
                                        Logout
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/tasks/login.php"
                                    >
                                        Login
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/tasks/register.php"
                                    >
                                        Register
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>

                    </div>

                    <div class="nav-item">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a
                                class="nav-link position-relative"
                                href="/tasks/customerdashboard/dashboard.php"
                            >
                                <i class="bi bi-cart3 fs-5"></i>
                            </a>

                        <?php else: ?>
                            <a
                                class="nav-link position-relative"
                                href="/tasks/myorder.php"
                            >
                                <i class="bi bi-cart3 fs-5"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchBox = document.getElementById("searchBox");
    const searchButton = document.getElementById("searchButton");
    const clearSearchInside =
        document.getElementById("clearSearchInside");


    function updateSearchInput() {
        if (searchBox.value.trim().length > 0) {
            searchButton.style.display = "none";
            clearSearchInside.style.display = "flex";
        } else {
            searchButton.style.display = "flex";
            clearSearchInside.style.display = "none";
        }
    }


    searchBox.addEventListener(
        "input",
        updateSearchInput
    );
    clearSearchInside.addEventListener(
        "click",
        function () {

            searchBox.value = "";
            updateSearchInput();
            searchBox.focus();

        }
    );


    updateSearchInput();

    const menuButton =
        document.querySelector(".mobile-menu-btn");

    const mobileMenu =
        document.getElementById("mobileMenu");

    mobileMenu.addEventListener(
        "shown.bs.collapse",
        function () {

            menuButton
                .querySelector(".menu-open-icon")
                .style.display = "none";

            menuButton
                .querySelector(".menu-close-icon")
                .style.display = "block";

        }
    );

    mobileMenu.addEventListener(
        "hidden.bs.collapse",
        function () {
            menuButton
                .querySelector(".menu-open-icon")
                .style.display = "block";
            menuButton
                .querySelector(".menu-close-icon")
                .style.display = "none";

        }
    );
});

</script>


