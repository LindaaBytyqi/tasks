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

.text-center{
    font-weight:600;
    font-size:24px;
}
.text-muted{
    font-size:20px;
}
.welcome-card{
    width:900px;
    margin:50px auto;
}
@media (max-width: 991px) {
    .account-sidebar {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0 15px;
        margin-bottom: 40px;
    }
    .account-content {
        width: 100%;
        padding: 0 15px;
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
            <?php include "sidebar.php"; ?>
        </div>
        <div class="col-md-9">
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
<?php
include "../includes/footer.php";
?>