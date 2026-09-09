<?php
include "../includes/user_auth.php";
include "../includes/csrf.php";
$user_id = $_SESSION['user_id'];

if(isset($_POST['change_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT password
            FROM users
            WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id"=>$user_id
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user || !password_verify($current_password, $user['password'])){

        $message = "
        <div class='alert alert-danger'>
            Current password is incorrect.
        </div>";
    }elseif(password_verify($new_password, $user['password'])){
    $message = "
    <div class='alert alert-danger'>
        New password must be different from the current password.
    </div>";
    }elseif($new_password !== $confirm_password){
        $message = "
        <div class='alert alert-danger'>
            New passwords do not match.
        </div>";

    }elseif(strlen($new_password) < 8){
        $message = "
        <div class='alert alert-danger'>
            Password must contain at least 8 characters.
        </div>";
    }else{
        $hashed_password = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE users
                SET password = :password
                WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "password"=>$hashed_password,
            "id"=>$user_id
        ]);

        $_SESSION['success'] = "Password changed successfully.";

        echo "
        <script>
            window.location='dashboard.php?page=profile';
        </script>";

        exit();
    }
}

?>

<style>

.password-card{
    width:650px;
    margin-left:300px;
}
.password-card .card-header{
    padding:18px;
}
.password-card .card-header h4{
    font-size:30px;
}
.password-card .card-body{
    padding:25px;
}
.password-card .form-label {
    font-size: 18px;
    font-weight: 600;
}
.password-card .form-control {
    height: 50px;
    font-size: 17px;
}
.password-card .btn-primary {
    padding: 12px 22px;
    font-size: 18px;
    border-radius: 10px;
    background: #e681b3;
    border: none;
    box-shadow: none;
    outline: none;
}

.password-card .btn-primary:hover,
.password-card .btn-primary:focus,
.password-card .btn-primary:active {
    background: #b1b4b6;
    border: none;
    box-shadow: none;
    outline: none;
}
.password-card .position-relative i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 22px;
    color: #6b7280;
}

.password-buttons {
    display: flex;
    gap: 15px;
}

@media (max-width: 991px) {
    .password-card {
        width: 100%;
        max-width: 100%;
        margin-left: 0;
        margin-right: 0;
    }
    .password-card .card-body {
        padding: 20px;
    }
}
@media (max-width: 767px) {

    .password-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
    }

    .password-card .card-header {
        padding: 15px;
    }

    .password-card .card-header h4 {
        font-size: 23px;
    }

    .password-card .card-body {
        padding: 20px;
    }

    .password-card .form-label {
        font-size: 16px;
    }

    .password-card .form-control {
        width: 100%;
        height: 46px;
        font-size: 16px;
    }

    .password-card .position-relative i {
        right: 15px;
        font-size: 20px;
    }

    .password-buttons {
        display: flex;
        gap: 15px;
        width: 100%;
    }

    .password-buttons .btn {
        flex: 1;
        font-size: 16px;
        padding: 10px 8px;
    }
}


</style>
<div class="card shadow-sm password-card">

    <div class="card-header">
        <h4 class="mb-0">
            Change Password
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
            Current Password
        </label>
        <div class="position-relative">
            <input
                type="password"
                name="current_password"
                id="current_password"
                class="form-control"
                required>

            <i class="bi bi-eye-slash toggle-password"
               data-target="current_password">
            </i>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">
            New Password
        </label>
        <div class="position-relative">
            <input
                type="password"
                name="new_password"
                id="new_password"
                class="form-control"
                required>
            <i class="bi bi-eye-slash toggle-password"
               data-target="new_password">
            </i>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">
            Confirm New Password
        </label>
        <div class="position-relative">
            <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                class="form-control"
                required>
            <i class="bi bi-eye-slash toggle-password"
               data-target="confirm_password">
            </i>
        </div>
    </div>
    <div class="password-buttons">
     <button
                type="submit"
                name="change_password"
                class="btn btn-primary">
                Save Changes
            </button>
            <a
                href="dashboard.php?page=profile"
                class="btn btn-primary">
                Cancel
            </a>
    </div>      
        </form>
    </div>

</div>

<script>
const toggleButtons = document.querySelectorAll(".toggle-password");
toggleButtons.forEach(button => {
    button.addEventListener("click", function(){

        const inputId = this.getAttribute("data-target");
        const input = document.getElementById(inputId);

        if(input.type === "password"){
            input.type = "text";
            this.classList.remove("bi-eye-slash");
            this.classList.add("bi-eye");
        }else{
            input.type = "password";
            this.classList.remove("bi-eye");
            this.classList.add("bi-eye-slash");
        }
    });
});
</script>