<?php
session_set_cookie_params([
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);
session_start();
include "includes/database.php";

$error = "";
if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        "email" => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){

        $error = "Email or password incorrect";

    } elseif(!password_verify($password, $user['password'])){

        $error = "Email or password incorrect";

    } elseif($user['status'] !== true){

        $error = "Your account is inactive.";

    } else {

           session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = $user['status'];

        if($user['role'] === 'admin'){
            header("Location: admin/admindashboard.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{
    border:none;
    border-radius:20px;
}
.card h2{
    font-size:36px;
    font-weight:700;
    color:#2563eb;
}
.form-label{
    font-size:17px;
    font-weight:600;
}
.form-control{
    height:55px;
    font-size:17px;
    border-radius:12px;
}
.position-relative i{
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:22px;
    color:#6b7280;
}
.position-relative i:hover{
    color:#2563eb;
}
.btn-primary{
    height:55px;
    border-radius:12px;
    font-size:18px;
    font-weight:600;
}
.popup-message{
    position:fixed;
    top:10%;
    left:50%;
    transform:translate(-50%, -50%);
    z-index:9999;
}
.popup-content{
    width:90vw;
    max-width:350px;
    padding:25px 20px;
    background:white;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    text-align:center;
    animation:popup .3s ease;
}
.popup-content p{
    margin:15px 0 0;
    font-size:18px;
    font-weight:600;
}
.popup-icon{
    display:flex;
    justify-content:center;
    align-items:center;
    width:55px;
    height:55px;
    margin:auto;
    border-radius:50%;
    font-size:30px;
    font-weight:bold;
}
.error-popup .popup-icon{
    background:#fee2e2;
    color:#dc2626;
}
@keyframes popup{
    from{
        opacity:0;
        transform:translateY(-20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>
</head>
<body>

<?php 
if($error != ""): ?>
<div class="popup-message error-popup">
    <div class="popup-content">
        <div class="popup-icon">
            <i class="bi bi-x-lg"></i>
        </div>
        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </div>
</div>
<?php endif; ?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg p-5">
                <h2 class="text-center mb-4">
                    Login
                </h2>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input 
                            type="email" 
                            name="email" 
                            class="form-control"
                            placeholder="Enter your email"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password
                        </label>
                    <div class="position-relative">
                    <input 
                        type="password" 
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required>
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                    </div>
                    </div>
                    <button 
                        type="submit" 
                        name="login"
                        class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <p class="text-center mt-3">
                    Don't have an account?
                    <a href="register.php">
                        Register
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<script>

const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function(){

    if(password.type === "password"){
        
        password.type = "text";
        this.classList.remove("bi-eye-slash");
        this.classList.add("bi-eye");
        
    }else{
        password.type = "password";

        this.classList.remove("bi-eye");
        this.classList.add("bi-eye-slash");
    }
});

setTimeout(()=>{
    document.querySelector(".popup-message").style.display="none";
},3000);

</script>
</body>
</html>