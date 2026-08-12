<?php
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

.edit-card{
    width:650px;
    margin-left:300px;
}
.edit-card .card-header{
    padding:18px;
}
.edit-card .card-header h4{
    font-size:30px;
}
.edit-card .card-body{
    padding:25px;
}
.form-label{
    font-size:18px;
    font-weight:600;
}
.form-control{
    height:50px;
    font-size:17px;
}
.btn.btn-primary{
    padding:12px 22px;
    font-size:18px;
    border-radius:10px;
}
.btn.btn-secondary{
    padding:11px 20px;
    font-size:18px;
    border-radius:10px;
}
.btn-primary,
.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active{
    background:#2563eb;
    border:none;
    box-shadow:none;
    outline:none;
}

.btn-secondary,s
.btn-secondary:hover,
.btn-secondary:focus,
.btn-secondary:active{
    background:whitesmoke;
    border:none;
    box-shadow:none;
    outline:none;
    color: white;
}
.text-primary{
    font-size:20px;
    color:black !important;
    text-decoration:none;
    display:inline-block;
    margin-bottom:30px;
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
                    value="<?= htmlspecialchars($user['first_name']); ?>"
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
                    value="<?= htmlspecialchars($user['last_name']); ?>"
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
                    value="<?= htmlspecialchars($user['email']); ?>"
                    required>
            </div>
            <a href="dashboard.php?page=changepassword"
            class="text-primary">
            Change Password
            </a>
            </br>

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
        </form>
    </div>
</div>