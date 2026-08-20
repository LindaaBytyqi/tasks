<?php
include "../includes/user_auth.php";
include "../includes/csrf.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name, email
        FROM users
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    "id" => $user_id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<style>
.profile-card{
    width:650px;
    margin-left: 300px;
}
.profile-card .card-header{
    padding:18px;
}
.profile-card .card-header h4{
    font-size:32px;
}
.profile-card .card-body{
    padding:25px;
}
.profile-card h6{
    font-size:22px;
    font-weight:700;
    margin-bottom:5px;
}
.profile-card p{
    font-size:19px;
    margin-bottom:15px;
}
.btn.btn-primary{
    /* background:#2563eb; */
    border:none;
    font-size:17px;
    padding:12px 22px;
    border-radius:12px;
}
@media (max-width: 991px) {
    .profile-card {
        width: 100%;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
    }
}

@media (max-width: 767px) {
    .profile-card {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    .profile-card .card-header {
        padding: 15px;
    }
    .profile-card .card-header h4 {
        font-size: 23px;
    }
    .profile-card .card-body {
        padding: 20px;
    }
    .profile-card h6 {
        font-size: 18px;
    }
    .profile-card p {
        font-size: 16px;
    }
    .profile-card .btn-primary {
        width: 100%;
        font-size: 16px;
        padding: 11px 18px;
    }
}
</style>
<div class="card shadow-sm profile-card">
    <div class="card-header">
        <h4 class="mb-0">
            Personal Information
        </h4>
    </div>


    <div class="card-body">
        <div class="mb-4">
            <h6>
                First Name
            </h6>
            <p>
                <?= $user['first_name']; ?>
            </p>
        </div>

        <div class="mb-4">
            <h6>
                Last Name
            </h6>
            <p>
                <?= $user['last_name']; ?>
            </p>
        </div>

        <div class="mb-4">
            <h6>
                Email
            </h6>
            <p>
                <?= $user['email']; ?>
            </p>
        </div>

        <a href="dashboard.php?page=editprofile"
           class="btn btn-primary">
            Edit Information
        </a>
    </div>
</div>