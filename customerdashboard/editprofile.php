<?php
// include "../includes/user_auth.php";
// include "../includes/csrf.php";
$user_id = $_SESSION['user_id'];

if(isset($_POST['update_profile'])){

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);


    if(empty($first_name) || empty($last_name) || empty($email)){
        $message = "<div class='alert alert-danger'>
                        All fields are required.
                    </div>";
    }elseif(!preg_match("/^[a-zA-Z ]+$/",$first_name)
    
        || !preg_match("/^[a-zA-Z ]+$/",$last_name)){
        $message = "<div class='alert alert-danger'>
                        Name and surname must contain only letters.
                    </div>";

    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $message = "<div class='alert alert-danger'>
                        Invalid email address.
                    </div>";
    }else{

        $sql = "SELECT id
                FROM users
                WHERE email = :email
                AND id != :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "email" => $email,
            "id"    => $user_id
        ]);

        if($stmt->fetch()){
            $message = "<div class='alert alert-danger'>
                            Email already exists.
                        </div>";
        }else{
            $sql = "UPDATE users
                    SET first_name = :first_name,
                        last_name = :last_name,
                        email = :email
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                "first_name" => $first_name,
                "last_name"  => $last_name,
                "email"      => $email,
                "id"         => $user_id
            ]);

            $_SESSION['name'] = $first_name;
            $_SESSION['success'] = "Profile updated successfully.";
            echo "
            <script>
                window.location='dashboard.php?page=profile';
            </script>";

            exit();
        }
    }
}

$sql = "SELECT first_name,last_name,email
        FROM users
        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    "id"=>$user_id
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<style>
.edit-card {
    width: 100%;
    max-width: 650px;
    /* margin: 0 auto; */
    margin-top: 130px;
}
.edit-card .card-header {
    padding: 18px;
}
.edit-card .card-header h4 {
    font-size: 30px;
}
.edit-card .card-body {
    padding: 25px;
}
.edit-card .form-label {
    font-size: 18px;
    font-weight: 600;
}
.edit-card .form-control {
    height: 50px;
    font-size: 17px;
}
.edit-card .btn-primary,
.edit-card .btn-secondary {
    padding: 12px 22px;
    font-size: 18px;
    border-radius: 10px;
}
.edit-card .btn-primary,
.edit-card .btn-primary:hover,
.edit-card .btn-primary:focus,
.edit-card .btn-primary:active {
    background: #e681b3;
    border: none;
    box-shadow: none;
    outline: none;
}
.edit-card .btn-secondary,
.edit-card .btn-secondary:hover,
.edit-card .btn-secondary:focus,
.edit-card .btn-secondary:active {
    background: #d1ced0;
    border: none;
    box-shadow: none;
    outline: none;
    color: #333;
}
.edit-card .text-primary {
    font-size: 20px;
    color: black !important;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 30px;
}
.profile-buttons {
    display: flex;
    gap: 15px;
    align-items: center;
}

@media (max-width: 991px) {
    .edit-card {
        max-width: 100%;
    }
}

@media (max-width: 576px) {
    .edit-card {
        width: 100%;
        margin: 0;
    }
    .edit-card .card-header {
        padding: 15px;
    }
    .edit-card .card-header h4 {
        font-size: 23px;
    }
    .edit-card .card-body {
        padding: 20px;
    }
    .edit-card .form-label {
        font-size: 16px;
    }
    .edit-card .form-control {
        height: 46px;
        font-size: 16px;
    }
    .edit-card .btn-primary,
    .edit-card .btn-secondary {
        font-size: 16px;
        padding: 10px 16px;
    }
    .edit-card .text-primary {
        font-size: 17px;
        margin-bottom: 20px;
    }
}

@media (max-width: 767px) {
    .account-sidebar {
        display: flex;
        justify-content: center;
        padding: 0 15px;
        margin-bottom: 45px;
    }
    .account-sidebar > * {
        width: 100%;
        max-width: 400px;
    }
    .account-content {
        padding: 0 15px;
    }
    .welcome-card {
        width: 100%;
        margin: 0 auto;
    }
    .edit-card {
        width: 100%;
        margin: 0 auto;
    }
    .edit-card .card-body {
        padding: 20px;
    }
    .edit-card .d-flex {
        gap: 15px !important;
    }
    .edit-card .btn-primary,
    .edit-card .btn-secondary {
        flex: 1;
        min-width: 120px;
    }
     .profile-buttons {
        display: flex;
        gap: 15px;
        width: 100%;
    }
    .profile-buttons .btn {
        flex: 1;
        text-align: center;
    }
}

@media (max-width: 450px) {
    .profile-buttons {
        gap: 10px;
    }
    .profile-buttons .btn {
        font-size: 15px;
        padding: 10px 8px;
    }
}
</style>
<div class="card shadow-sm edit-card">
    <div class="card-header">
        <h4 class="mb-0">
            Edit Personal Information
        </h4>
    </div>
    <div class="card-body">
        <?php
        if(isset($message)){
            echo $message;
        }
        ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">
                    First Name
                </label>
                <input
                    type="text"
                    name="first_name"
                    class="form-control"
                    value="<?= htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8'); ?>"  
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">
                    Last Name
                </label>
                <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    value="<?= htmlspecialchars($user['last_name'], ENT_QUOTES, 'UTF-8'); ?>"  
                    required>
            </div>
            <div class="mb-4">
                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>"  
                    required>
            </div>
            <!-- <a href="dashboard.php?page=changepassword"
            class="text-primary">
            Change Password
            </a> -->
            </br>

            <div class="profile-buttons">
            <button
                type="submit"
                name="update_profile"
                class="btn btn-primary">
                Save Changes
            </button>
            <a
                href="dashboard.php?page=profile"
                class="btn btn-secondary">
                Cancel
            </a>
        </div>
        </form>
    </div>
</div>