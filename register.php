<?php
session_start();
include "../tasks/includes/database.php";

if(isset($_POST['register'])){

    $first_name = $_POST['firstname'];
    $last_name  = $_POST['lastname'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role       = "user";

    try {
        $sql = "INSERT INTO users 
                (first_name, last_name, email, password, role)
                VALUES 
                (:first_name, :last_name, :email, :password, :role)";

        $stmt = $conn->prepare($sql);
        if($stmt->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':password'   => $password,
            ':role'       => $role
        ])){
            echo "
            <div class='popup-message success-popup'>
                <div class='popup-content'>
                    <span class='popup-icon'>✓</span>
                    <p>Registration successful!</p>
                </div>
            </div>

            <script>
                setTimeout(function(){
                    window.location.href='login.php';
                },3000);
            </script>
            ";
        }
    } catch(PDOException $e) {
        if($e->getCode() == 23505){
            echo "
            <div class='popup-message error-popup'>
                <div class='popup-content'>
                    <span class='popup-icon'>!</span>
                    <p>This email is already registered.</p>
                </div>
            </div>
            ";
        } else {
            echo "
            <div class='popup-message error-popup'>
                <div class='popup-content'>
                    <span class='popup-icon'>!</span>
                    <p>Something went wrong. Please try again later.</p>
                </div>
            </div>
            ";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<body>
    <div class="register-container">
        <form class="register-form" action="register.php" method="post">
            <h2>Registration</h2>
            <hr class="divider">

            <div class="row-inputs">
                <div class="input-box">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <span class="error-text" id="firstnameError"></span>
                </div>
                <div class="input-box">
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                    <span class="error-text" id="lastnameError"></span>
                </div>
            </div>

            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Email Address" required>
                <span class="error-text" id="emailError"></span>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="error-text" id="passwordError"></span>
                <i class="bi bi-eye-slash" id="togglePassword"></i>
            </div>

            <div class="terms-box">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the Terms of Service.</label>
                <span class="error-text" id="termsError"></span>
            </div>

            <button type="submit" name="register">Register</button>
        </form>
    </div>
<script>
const form = document.querySelector(".register-form");
form.addEventListener("submit", function(e){
    let valid = true;
    const firstName = document.getElementById("firstname");
    const lastName = document.getElementById("lastname");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const nameRegex = /^[A-ZÇË][a-zçë]{1,29}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const terms = document.getElementById("terms");


    const togglePassword = document.getElementById("togglePassword");

    document.querySelectorAll(".error-text").forEach(e=>{
        e.textContent="";
    });
    document.querySelectorAll("input").forEach(input=>{
        input.classList.remove("input-error");
        input.classList.remove("input-success");
    });

    if(!nameRegex.test(firstName.value)){
        document.getElementById("firstnameError").textContent =
        "First name should contain only letters";
        firstName.classList.add("input-error");
        valid=false;
    }else{
        firstName.classList.add("input-success");
    }

    if(!nameRegex.test(lastName.value)){
        document.getElementById("lastnameError").textContent =
        "Last name should contain only letters";
        lastName.classList.add("input-error");
        valid=false;
    }else{
        lastName.classList.add("input-success");
    }

    if(!emailRegex.test(email.value)){
        document.getElementById("emailError").textContent =
        "Enter a valid email address";
        email.classList.add("input-error");
        valid=false;

    }else{
        email.classList.add("input-success");
    }

    if(password.value.length < 8){
        document.getElementById("passwordError").textContent =
        "Password must have at least 8 characters";
        password.classList.add("input-error");
        valid=false;
    }else{
        password.classList.add("input-success");
    }
    if(!terms.checked){
    document.getElementById("termsError").textContent =
    "You must agree to the Terms of Service.";
    valid=false;
}
    if(!valid){
        e.preventDefault();
    }
   
});
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

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