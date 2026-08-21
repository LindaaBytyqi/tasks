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
.text-center {
    font-weight: 600;
    font-size: 24px;
}
.text-muted {
    font-size: 20px;
}
.welcome-card {
    width: 900px;
    max-width: 100%;
    margin: 50px auto;
    box-sizing: border-box;
}
@media (min-width: 1201px) {
    .welcome-card {
        width: 900px;
        margin: 50px auto;
    }
    .account-sidebar {
        width: 100%;
    }
    .account-content {
        width: 100%;
    }
}
@media (min-width: 993px) and (max-width: 1200px) {
    .welcome-card {
        width: 100%;
        max-width: 800px;
        margin: 40px auto;
    }
    .text-center {
        font-size: 22px;
    }
    .text-muted {
        font-size: 18px;
    }
    .account-sidebar {
        width: 100%;
    }
    .account-content {
        width: 100%;
    }
}
@media (min-width: 769px) and (max-width: 992px) {
    .account-sidebar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0 15px;
        margin-bottom: 30px;
        box-sizing: border-box;
    }
    .account-content {
        width: 100%;
        padding: 0 15px;
        box-sizing: border-box;
    }
    .welcome-card {
        width: 100%;
        max-width: 700px;
        margin: 30px auto;
    }
    .text-center {
        font-size: 22px;
    }
    .text-muted {
        font-size: 18px;
    }
    .password-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
}
@media (min-width: 481px) and (max-width: 768px) {
    .account-sidebar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0 12px;
        margin-bottom: 25px;
        box-sizing: border-box;
    }
    .account-content {
        width: 100%;
        padding: 0 12px;
        box-sizing: border-box;
    }
    .welcome-card {
        width: 100%;
        max-width: 100%;
        margin: 25px auto;
    }
    .welcome-card .card-body {
        padding: 35px 25px !important;
    }
    .text-center {
        font-size: 20px;
    }
    .text-muted {
        font-size: 16px;
    }
    .password-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
}

@media (min-width: 380px) and (max-width: 480px) {
    .account-sidebar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0 10px;
        margin-bottom: 20px;
        box-sizing: border-box;
    }
    .account-content {
        width: 100%;
        padding: 0 10px;
        box-sizing: border-box;
    }
    .welcome-card {
        width: 100%;
        max-width: 100%;
        margin: 20px auto;
    }
    .welcome-card .card-body {
        padding: 28px 18px !important;
    }
    .text-center {
        font-size: 19px;
    }
    .text-muted {
        font-size: 15px;
        line-height: 1.5;
    }
    .password-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
    }
}


@media (max-width: 379px) {
    .account-sidebar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0 8px;
        margin-bottom: 20px;
        box-sizing: border-box;
    }
    .account-content {
        width: 100%;
        padding: 0 8px;
        box-sizing: border-box;
    }
    .welcome-card {
        width: 100%;
        max-width: 100%;
        margin: 20px auto;
    }
    .welcome-card .card-body {
        padding: 25px 15px !important;
    }
    .text-center {
        font-size: 18px;
    }
    .text-muted {
        font-size: 14px;
        line-height: 1.5;
    }
    .password-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
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

            } elseif($page == "orderdetail"){
                include "orderdetail.php";
            } else {
            ?>
                <div class="card shadow-sm welcome-card">
                    <div class="card-body text-center p-5">
                        <h3>
                            Welcome <?php echo htmlspecialchars($_SESSION['name']); ?> 👋
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