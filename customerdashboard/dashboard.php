<?php
session_start();
include "../includes/user_auth.php";
include "../includes/header.php";
include "../includes/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name 
        FROM users 
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    "id"=>$user_id
]);

$current_user = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['name'] = $current_user['first_name'];

$page = isset($_GET['page']) ? $_GET['page'] : "home";
?>
<style>
.sidebar-card {
    width: 100%;
    min-height: 500px;
    margin: 0;
}

.sidebar-item {
    padding: 25px 20px !important;
    font-size: 18px;
    font-weight: 600;
}

.sidebar-subitem {
    padding: 22px 35px !important;
    font-size: 16px;
}

.orders-item {
    margin-top: 25px;
}

.sidebar-card .list-group-item:hover {
    background: #f8fafc;
}

.account-content {
    width: 100%;
}

.welcome-card {
    width: 100%;
    max-width: 900px;
    margin: 30px auto;
    border: none;
    border-radius: 12px;
}

.welcome-card .card-body {
    padding: 50px 40px;
}

.welcome-card h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    line-height: 1.4;
}

.welcome-card .text-muted {
    margin-bottom: 0;
    font-size: 18px;
    line-height: 1.6;
}

@media (min-width: 992px) {

    .container-fluid {
        padding-left: 35px;
        padding-right: 35px;
    }

    .account-sidebar {
        width: 100%;
    }

    .account-content {
        width: 100%;
    }

    .welcome-card {
        max-width: 900px;
        margin: 30px auto;
    }
}

@media (min-width: 768px) and (max-width: 991px) {

    .container-fluid {
        width: 100%;
        padding-left: 25px;
        padding-right: 25px;
        margin-top: 30px !important;
    }

    .col-md-3,
    .col-md-9 {
        width: 100%;
    }

    .account-sidebar {
        width: 100%;
        max-width: 700px;
        margin: 0 auto 25px auto;
    }

    .sidebar-card {
        width: 100%;
        min-height: auto;
        margin: 0;
    }

    .sidebar-card .card-header {
        padding: 22px 20px !important;
    }

    .sidebar-card .card-header h5 {
        font-size: 20px;
    }

    .sidebar-item {
        padding: 20px 22px !important;
        font-size: 17px;
    }

    .sidebar-subitem {
        padding: 16px 32px !important;
        font-size: 15px;
    }

    .orders-item {
        margin-top: 15px;
    }

    .account-content {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .welcome-card {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .welcome-card .card-body {
        padding: 42px 35px;
    }

    .welcome-card h3 {
        font-size: 25px;
    }

    .welcome-card .text-muted {
        font-size: 17px;
        line-height: 1.6;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .container-fluid {
        width: 100%;
        padding-left: 40px;
        padding-right: 40px;
        margin-top: 30px !important;
    }
    .row {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    .col-md-3,
    .col-md-9 {
        width: 100%;
        max-width: 100%;
        flex: none;
    }

    .account-sidebar {
        width: 100%;
        max-width: 650px;
        margin: 0 auto;
    }

    .sidebar-card {
        width: 100%;
        min-height: auto;
        margin: 0;
    }

    .sidebar-card .card-header {
        padding: 24px 20px !important;
    }

    .sidebar-card .card-header h5 {
        font-size: 21px;
    }

    .sidebar-item {
        padding: 21px 24px !important;
        font-size: 18px;
    }
    .sidebar-subitem {
        padding: 17px 35px !important;
        font-size: 16px;
    }
    .orders-item {
        margin-top: 15px;
    }
    .account-content {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }
    .welcome-card {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }
    .welcome-card .card-body {
        padding: 45px 40px !important;
    }
    .welcome-card h3 {
        font-size: 26px;
    }
    .welcome-card .text-muted {
        font-size: 18px;
        line-height: 1.6;
    }
}

@media (min-width: 481px) and (max-width: 767px) {
    .container-fluid {
        width: 100%;
        padding-left: 18px;
        padding-right: 18px;
        margin-top: 25px !important;
    }

    .col-md-3,
    .col-md-9 {
        width: 100%;
    }

    .account-sidebar {
        width: 100%;
        max-width: 550px;
        margin: 0 auto 20px auto;
    }

    .sidebar-card {
        width: 100%;
        min-height: auto;
        margin: 0;
    }

    .sidebar-card .card-header {
        padding: 20px 15px !important;
    }
    .sidebar-card .card-header h5 {
        font-size: 18px;
    }
    .sidebar-item {
        padding: 18px 20px !important;
        font-size: 17px;
    }
    .sidebar-subitem {
        padding: 15px 30px !important;
        font-size: 15px;
    }
    .orders-item {
        margin-top: 15px;
    }

    .account-content {
        width: 100%;
        margin: 0 auto;
    }
    .welcome-card {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }
    .welcome-card .card-body {
        padding: 35px 25px;
    }
    .welcome-card h3 {
        font-size: 22px;
    }
    .welcome-card .text-muted {
        font-size: 16px;
        line-height: 1.5;
    }
}

@media (max-width: 480px) {

    .container-fluid {
        width: 100%;
        padding-left: 12px;
        padding-right: 12px;
        margin-top: 20px !important;
    }

    .col-md-3,
    .col-md-9 {
        width: 100%;
    }

    .account-sidebar {
        width: 100%;
        max-width: 400px;
        margin: 0 auto 20px auto;
    }

    .sidebar-card {
        width: 100%;
        min-height: auto;
        margin: 0;
    }

    .sidebar-card .card-header {
        padding: 20px 15px !important;
    }

    .sidebar-card .card-header h5 {
        font-size: 18px;
    }

    .sidebar-item {
        padding: 17px 18px !important;
        font-size: 16px;
    }

    .sidebar-subitem {
        padding: 14px 28px !important;
        font-size: 14px;
    }

    .orders-item {
        margin-top: 12px;
    }
    .account-content {
        width: 100%;
        margin: 0 auto;
    }
    .welcome-card {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        border-radius: 10px;
    }
    .welcome-card .card-body {
        padding: 30px 18px;
    }
    .welcome-card h3 {
        font-size: 20px;
        line-height: 1.4;
    }
    .welcome-card .text-muted {
        font-size: 15px;
        line-height: 1.5;
    }
}

@media (max-width: 379px) {

    .container-fluid {
        padding-left: 8px;
        padding-right: 8px;
    }

    .sidebar-item {
        padding: 15px 16px !important;
        font-size: 15px;
    }

    .sidebar-subitem {
        padding: 12px 24px !important;
        font-size: 13px;
    }

    .sidebar-card .card-header h5 {
        font-size: 17px;
    }

    .welcome-card .card-body {
        padding: 25px 15px;
    }

    .welcome-card h3 {
        font-size: 18px;
    }

    .welcome-card .text-muted {
        font-size: 14px;
    }
}
</style>

<div class="container-fluid mt-5" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-3">
            <div class="account-sidebar">
                <?php include "sidebar.php"; ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="account-content">
                <?php
                if($page == "profile"){
                    include "profile.php";
                }elseif($page == "editprofile"){
                    include "editprofile.php";
                }elseif($page == "changepassword"){
                    include "changepassword.php";
                }elseif($page == "orderdetail"){
                    include "orderdetail.php";
                }else{
                ?>
                    <div class="card shadow-sm welcome-card">
                        <div class="card-body text-center">
                            <h3>
                                Welcome <?= htmlspecialchars($_SESSION['name']); ?> 👋
                            </h3>
                            <p class="text-muted mt-3">
                                Please select an option from the menu to manage your account.
                            </p>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include "../includes/footer.php";
?>  
