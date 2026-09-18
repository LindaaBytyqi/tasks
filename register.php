<?php

session_set_cookie_params([
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);

session_start();

include "includes/database.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $first_name = trim($_POST['firstname'] ?? '');
    $last_name  = trim($_POST['lastname'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $passwordRaw = $_POST['password'] ?? '';

    $role = "user";

    try {

        $password = password_hash(
            $passwordRaw,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO users
                (first_name, last_name, email, password, role)
                VALUES
                (:first_name, :last_name, :email, :password, :role)";

        $stmt = $conn->prepare($sql);

        if ($stmt->execute([
            ':first_name' => $first_name,
            ':last_name'  => $last_name,
            ':email'      => $email,
            ':password'   => $password,
            ':role'       => $role
        ])) {

            $success = "Registration successful!";

        }

    } catch (PDOException $e) {

        if ($e->getCode() == 23505) {

            $error = "This email is already registered.";

        } else {

            $error = "Something went wrong. Please try again later.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

<style>
* {
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
}

html,
body {
    height: 100%;
    margin: 0;
}

.auth-wrapper {
    display: flex;
    min-height: 100vh;
    width: 100%;
}

.auth-image {
    flex: 1;
    background-image: url("images/log.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.auth-form-side {
    flex: 1;
    background: #ffffff;

    display: flex;
    flex-direction: column;

    padding: 40px 60px;
}
.auth-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.auth-logo {
    font-size: 26px;
    font-weight: 700;
    color: #eb3f81;
    letter-spacing: -0.5px;
    text-decoration: none;
}

.auth-return {
    font-size: 15px;
    color: #111827;
    text-decoration: underline;
    transition: color .25s ease;
}

.auth-return:hover {
    color: #eb3f81;
}

.auth-form-container {
    flex: 1;

    display: flex;
    flex-direction: column;

    justify-content: center;
    align-items: center;

    text-align: center;
}

.auth-form-inner {
    width: 100%;
    max-width: 480px;
}

.auth-form-inner h1 {
    font-size: 42px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #111827;

    margin: 0 0 40px;
}
.row-inputs {
    display: flex;
    gap: 15px;
    width: 100%;
}

.input-box {
    flex: 1;
}

.form-group {
    width: 100%;
    position: relative;
}

.auth-form-inner input {
    width: 100%;
    height: 58px;

    font-size: 15px;
    letter-spacing: .5px;

    border-radius: 30px;
    border: 1px solid #d1d5db;

    padding: 0 24px;

    margin-bottom: 18px;

    outline: none;

    background: #fff;

    transition:
        border-color .25s ease,
        box-shadow .25s ease;
}

.auth-form-inner input::placeholder {
    color: #9ca3af;

    text-transform: uppercase;

    font-size: 13px;

    letter-spacing: 1px;
}

.auth-form-inner input:focus {
    box-shadow: none;
    border-color: #111827;
}
#togglePassword {
    position: absolute;
    right: 22px;
    top: 29px;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    color: #6b7280;
    z-index: 2;
    transition: color .25s ease;
}

#togglePassword:hover {
    color: #0a0a0a;
}
.terms-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 2px 0 25px;
    font-size: 13px;
    color: #6b7280;
    text-align: left;
}

.terms-box input[type="checkbox"] {
    width: 17px;
    height: 17px;
    margin: 0;
    cursor: pointer;
    accent-color: #eb3f81;
}

.terms-box label {
    cursor: pointer;
    user-select: none;
}
.register-btn {
    height: 56px;
    width: 100%;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    background: #1f1f1f;
    color: #ffffff;
    border: none;
    cursor: pointer;
    transition:
        background .25s ease,
        transform .25s ease,
        box-shadow .25s ease;
}

.register-btn:hover {
    background: #eb3f81;
    transform: translateY(-2px);
    box-shadow:
        0 8px 20px rgba(235, 63, 129, .20);
}

.auth-signup {
    margin-top: auto;
    padding-top: 30px;
    text-align: center;
    font-size: 14px;
    color: #6b7280;
}
.auth-signup a {
    color: #111827;
    text-decoration: underline;
    font-weight: 500;
    transition: color .25s ease;
}
.auth-signup a:hover {
    color: #eb3f81;
}
.error-text {
    display: block;
    color: #dc2626;
    font-size: 12px;
    text-align: left;
    margin-top: -12px;
    margin-bottom: 10px;
}
.input-error {
    border-color: #dc2626 !important;
}

.input-success {
    border-color: #050505 !important;
}
.popup-message {
    position: fixed;
    top: 10%;
    left: 73%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.popup-content {
    width: 90vw;
    max-width: 350px;
    padding: 25px 20px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow:
        0 10px 30px rgba(0, 0, 0, .20);
    text-align: center;
    animation: popup .3s ease;
}

.popup-content p {
    margin: 15px 0 0;
    font-size: 17px;
    font-weight: 600;
}

.popup-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 55px;
    height: 55px;
    margin: auto;
    border-radius: 50%;
    font-size: 25px;
    font-weight: bold;
}

.success-popup .popup-icon {
    background: #dcfce7;
    color: #16a34a;
}
.error-popup .popup-icon {
    background: #fee2e2;
    color: #dc2626;
}
@keyframes popup {
    from {
        opacity: 0;
        transform: translate(-50%, -30px);
    }
    to {
        opacity: 1;
        transform: translate(-50%, 0);
    }
}
@media (max-width: 900px) {
    .auth-wrapper {
        flex-direction: column;
    }
    .auth-image {
        min-height: 260px;
        flex: none;
    }
    .auth-form-side {
        padding: 30px 24px;

        min-height: calc(100vh - 260px);
    }
    .auth-form-inner {
        max-width: 500px;
    }
    .auth-form-inner h1 {
        font-size: 34px;
        margin-bottom: 32px;
    }
}


@media (max-width: 600px) {

    .auth-image {
        min-height: 180px;
    }
    .auth-form-side {
        padding: 20px 18px;
    }
    .auth-topbar {
        gap: 12px;
    }
    .auth-logo {
        font-size: 20px;
    }
    .auth-return {
        font-size: 12px;
    }
    .auth-form-inner {
        max-width: 100%;
    }
    .auth-form-inner h1 {
        font-size: 26px;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }
    .row-inputs {
        flex-direction: column;
        gap: 0;
    }
    .auth-form-inner input {
        height: 48px;
        font-size: 13px;
        padding: 0 18px;
        margin-bottom: 13px;
    }
    .auth-form-inner input::placeholder {
        font-size: 11px;
        letter-spacing: .8px;
    }
    #togglePassword {
        right: 18px;
        top: 24px;
        font-size: 18px;
    }
    .error-text {
        font-size: 10px;
        margin-top: -8px;
        margin-bottom: 8px;
    }
    .terms-box {
        gap: 7px;
        margin: 0 0 18px;
        font-size: 11px;
    }
    .terms-box input[type="checkbox"] {
        width: 15px;
        height: 15px;
    }
    .register-btn {
        height: 48px;
        font-size: 13px;
        letter-spacing: .8px;
    }
    .auth-signup {
        padding-top: 20px;
        font-size: 12px;
    }
}

</style>
</head>
<body>
<?php if ($success !== ""): ?>
<div class="popup-message success-popup">
    <div class="popup-content">
        <div class="popup-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <p>
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
        </p>

    </div>
</div>
<?php endif; ?>

<?php if ($error !== ""): ?>
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
            <a
                href="index.php"
                class="auth-logo"
            >
                MyShop
            </a>
            <a
                href="index.php"
                class="auth-return"
            >
                Return to Store
            </a>

        </div>
        <div class="auth-form-container">
            <div class="auth-form-inner">
                <h1>REGISTER</h1>
                <form
                    method="POST"
                    action="register.php"
                    class="register-form"
                    novalidate
                >
                    <div class="row-inputs">
                        <div class="input-box">
                            <input
                                type="text"
                                id="firstname"
                                name="firstname"
                                placeholder="First Name"
                                required
                            >
                            <span
                                class="error-text"
                                id="firstnameError"
                            ></span>
                        </div>
                        <div class="input-box">
                            <input
                                type="text"
                                id="lastname"
                                name="lastname"
                                placeholder="Last Name"
                                required
                            >
                            <span
                                class="error-text"
                                id="lastnameError"
                            ></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Email Address"
                            required
                        >
                        <span
                            class="error-text"
                            id="emailError"
                        ></span>
                    </div>
                    <div class="form-group">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password"
                            required
                        >
                        <span
                            class="error-text"
                            id="passwordError"
                        ></span>
                        <i
                            class="bi bi-eye-slash"
                            id="togglePassword"
                        ></i>
                    </div>
                    <div class="terms-box">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required
                        >
                        <label for="terms">
                            I agree to the Terms of Service.
                        </label>
                    </div>
                    <span
                        class="error-text"
                        id="termsError"
                    ></span>

                    <button
                        type="submit"
                        name="register"
                        class="register-btn"
                    >
                        Register
                    </button>

                </form>
            </div>

        </div>

        <div class="auth-signup">

            Already Have an Account?

            <br>

            <a href="login.php">
                Login
            </a>
        </div>
    </div>
</div>
<script>

const form = document.querySelector(".register-form");

form.addEventListener("submit", function(e) {

    let valid = true;

    const firstName =
        document.getElementById("firstname");

    const lastName =
        document.getElementById("lastname");

    const email =
        document.getElementById("email");

    const password =
        document.getElementById("password");

    const terms =
        document.getElementById("terms");


    const nameRegex =
        /^[A-ZÇË][a-zçë]{1,29}$/;

    const emailRegex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    document
        .querySelectorAll(".error-text")
        .forEach(function(error) {

            error.textContent = "";

        });


    document
        .querySelectorAll("input")
        .forEach(function(input) {

            input.classList.remove("input-error");
            input.classList.remove("input-success");

        });

    if (!nameRegex.test(firstName.value.trim())) {

        document.getElementById(
            "firstnameError"
        ).textContent =
            "First name should contain only letters.";

        firstName.classList.add("input-error");

        valid = false;

    } else {

        firstName.classList.add("input-success");

    }
    if (!nameRegex.test(lastName.value.trim())) {

        document.getElementById(
            "lastnameError"
        ).textContent =
            "Last name should contain only letters.";

        lastName.classList.add("input-error");

        valid = false;

    } else {

        lastName.classList.add("input-success");

    }

    if (!emailRegex.test(email.value.trim())) {
        document.getElementById(
            "emailError"
        ).textContent =
            "Enter a valid email address.";

        email.classList.add("input-error");

        valid = false;
    } else {

        email.classList.add("input-success");

    }

    if (password.value.length < 8) {
        document.getElementById(
            "passwordError"
        ).textContent =
            "Password must have at least 8 characters.";

        password.classList.add("input-error");

        valid = false;
    } else {
        password.classList.add("input-success");
    }


    if (!terms.checked) {
        document.getElementById(
            "termsError"
        ).textContent =
            "You must agree to the Terms of Service.";

        valid = false;

    }
    if (!valid) {
        e.preventDefault();
    }

});

const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener(
    "click",
    function() {

        if (password.type === "password") {

            password.type = "text";

            this.classList.remove(
                "bi-eye-slash"
            );

            this.classList.add(
                "bi-eye"
            );

        } else {

            password.type = "password";

            this.classList.remove(
                "bi-eye"
            );

            this.classList.add(
                "bi-eye-slash"
            );

        }
    }
);

const popup =
    document.querySelector(".popup-message");
if (popup) {
    setTimeout(function() {
        popup.style.display = "none";
    }, 3000);

}

<?php if ($success !== ""): ?>
setTimeout(function() {
    window.location.href = "login.php";
}, 3000);
<?php endif; ?>
</script>


</body>
</html>