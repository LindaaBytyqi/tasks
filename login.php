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

        if ($user['role'] === 'admin' ||
            $user['role'] === 'superadmin')
        {
            header("Location: admin/admindashboard.php?page=dashboard");
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>

*{
    font-family:'Poppins', sans-serif;
}

html, body{
    height:100%;
    margin:0;
}

.auth-wrapper{
    display:flex;
    min-height:100vh;
}

.auth-image{
    flex:1;
    background-image:url("images/log.png");
    background-size:cover;
      display:flex;
    background-position:center;
    background-repeat:no-repeat;
}

.auth-form-side{
    flex:1;
    background:#ffffff;
    display:flex;
    flex-direction:column;
    padding:40px 60px;
    position:relative;
}

.auth-topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.auth-logo{
    font-size:26px;
    font-weight:700;
    color: #eb3f81;
    letter-spacing:-0.5px;
    text-decoration:none;
}

.auth-return{
    font-size:15px;
    color:#111827;
    text-decoration:underline;
}
.auth-return:hover{
    color:#2563eb;
}
.auth-form-container{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}
.auth-form-inner{
    width:100%;
    max-width:420px;
}
.auth-form-inner h1{
    font-size:42px;
    font-weight:700;
    letter-spacing:2px;
    color:#111827;
    margin-bottom:44px;
}
.auth-subtitle{
    font-size:15px;
    color:#9ca3af;
    line-height:1.6;
    margin-bottom:35px;
}
.form-control{
    height:58px;
    font-size:15px;
    letter-spacing:0.5px;
    border-radius:30px;
    border:1px solid #d1d5db;
    padding:0 24px;
    margin-bottom:18px;
}
.form-control::placeholder{
    color:#9ca3af;
    text-transform:uppercase;
    font-size:13px;
    letter-spacing:1px;
}
.form-control:focus{
    box-shadow:none;
    border-color:#111827;
}
.position-relative i{
    position:absolute;
    right:18px;
    top:50%;
    transform:translateY(-50%);
    font-size:18px;
}
.position-relative i:hover{
    color:#111827;
}

.forgot-link{
    display:block;
    font-size:14px;
    color:#111827;
    text-decoration:underline;
    margin-bottom:28px;
}
.forgot-link:hover{
    color:#2563eb;
}

.btn-primary{
    height:56px;
    width:100%;
    border-radius:30px;
    font-size:15px;
    font-weight:600;
    letter-spacing:1px;
    text-transform:uppercase;
    background:#1f1f1f;
    border:none;
}
.btn-primary:hover{
    background:#eb3f81;
}

.auth-signup{
    margin-top:auto;
    padding-top:40px;
    text-align:center;
    font-size:14px;
    color:#6b7280;
}
.auth-signup a{
    color:#111827;
    text-decoration:underline;
    font-weight:500;
}
.auth-signup a:hover{
    color:#2563eb;
}

.popup-message{
    position:fixed;
    top:10%;
    left:73%;
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

@media (max-width: 900px){

    .auth-wrapper{
        flex-direction:column;
    }

    .auth-image{
        min-height:180px;
    }

    .auth-form-side{
        padding:20px 18px;
    }

    .auth-topbar{
        gap:12px;
    }

    .auth-logo{
        font-size:20px;
    }

    .auth-return{
        font-size:12px;
    }

    .auth-form-inner{
        max-width:100%;
    }

    .auth-form-inner h1{
        font-size:26px;
        letter-spacing:1px;
        margin-bottom:25px;
    }

    .form-control{
        height:48px;
        font-size:13px;
        padding:0 18px;
        margin-bottom:13px;
    }

    .form-control::placeholder{
        font-size:11px;
        letter-spacing:.8px;
    }

    .position-relative i{
        right:18px;
        top:24px;
        font-size:18px;
    }

    .btn-primary{
        height:48px;
        font-size:13px;
        letter-spacing:.8px;
    }

    .auth-signup{
        padding-top:20px;
        font-size:12px;
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

<div class="auth-wrapper">

    <div class="auth-image"></div>

    <div class="auth-form-side">

        <div class="auth-topbar">
            <a href="index.php" class="auth-logo">MyShop</a>
            <a href="index.php" class="auth-return">Return to Store</a>
        </div>

        <div class="auth-form-container">
            <div class="auth-form-inner">

                <h1>LOGIN</h1>
                <!-- <p class="auth-subtitle">
                    By accessing your TrueKind Account you can track and manage your orders
                    and also save multiple addresses.
                </p> -->

                <form method="POST" action="login.php">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control"
                        placeholder="Enter your email"
                        required>

                    <div class="position-relative">
                        <input 
                            type="password" 
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Password"
                            required>
                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                    </div>

                    <button 
                        type="submit" 
                        name="login"
                        class="btn btn-primary">
                        Login
                    </button>
                </form>

            </div>
        </div>

        <div class="auth-signup">
            Don't Have an Account Already?
            <br>
            <a href="register.php">Sign Up</a>
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

const popup = document.querySelector(".popup-message");
if(popup){
    setTimeout(()=>{
        popup.style.display="none";
    },3000);
}

</script>
</body>
</html>