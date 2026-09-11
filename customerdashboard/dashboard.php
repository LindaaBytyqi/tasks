<?php

session_start();

include "../includes/user_auth.php";

include "../includes/header.php";

include "../includes/database.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit;

}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name

        FROM users

        WHERE id = :id";

$stmt = $conn->prepare($sql);

$stmt->execute([

    "id" => $user_id

]);

$current_user = $stmt->fetch(PDO::FETCH_ASSOC);

$_SESSION['name'] = $current_user['first_name'];

$page = isset($_GET['page']) ? $_GET['page'] : "home";

$first_name = htmlspecialchars(

    $current_user['first_name'],

    ENT_QUOTES,

    'UTF-8'

);

$last_name = htmlspecialchars(

    $current_user['last_name'] ?? '',

    ENT_QUOTES,

    'UTF-8'

);

?>

<style>

.account-dashboard {

    width: 100%;

    max-width: 1450px;

    margin: 170px auto 100px auto;

    padding: 0 45px;

    box-sizing: border-box;

}

.account-hero {

    width: 100%;

    padding: 35px 0 42px 0;

    border-bottom: 1px solid #dedede;

}

.account-hero-top {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 28px;

}

.account-label {

    font-size: 16px;

    font-weight: 700;

    letter-spacing: 4px;

    color: #222;

}

.account-space {

    font-size: 15px;

    font-weight: 600;

    letter-spacing: 2px;

    color: #777;

    text-transform: uppercase;

}

.account-space::first-letter {

    color: #e681b3;

}

.account-hero h1 {

    margin: 0;

    font-size: clamp(40px, 4vw, 62px);

    font-weight: 400;

    line-height: 1.05;

    letter-spacing: -2px;

    color: #171717;

}

.account-hero h1 span {

    font-weight: 600;

}

.account-description {

    margin: 15px 0 0 0;

    font-size: 18px;

    color: #777;

    letter-spacing: .3px;

}

.account-options {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 22px;

    margin-top: 48px;

}

.account-option {

    position: relative;

    min-height: 330px;

    padding: 30px 30px 28px 30px;

    border: 1px solid #dedede;

    background: #fff;

    text-decoration: none;

    color: #171717;

    display: flex;

    flex-direction: column;

    justify-content: space-between;

    transition:

        transform .35s ease,

        border-color .35s ease,

        box-shadow .35s ease,

        background .35s ease;

    overflow: hidden;

}

.account-option::before {

    content: "";

    position: absolute;

    width: 0;

    height: 3px;

    left: 0;

    top: 0;

    background: #e681b3;

    transition: width .4s ease;

}

.account-option:hover {

    transform: translateY(-7px);

    border-color: #d8d8d8;

    box-shadow: 0 18px 45px rgba(0,0,0,.08);

    color: #171717;

}

.account-option:hover::before {

    width: 100%;

}

.account-icon {

    width: 58px;

    height: 58px;

    border: 1px solid #dedede;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-top: 32px;

    transition:

        background .35s ease,

        border-color .35s ease,

        transform .35s ease;

}

.account-icon i {

    font-size: 24px;

    font-weight: 400;

    color: #333;

}

.account-option:hover .account-icon {

    background: #fdf0f6;

    border-color: #e681b3;

    transform: rotate(5deg);

}

.account-card-content {

    margin-top: 28px;

}

.account-card-content h3 {

    margin: 0;

    font-size: 21px;

    font-weight: 700;

    letter-spacing: 1px;

}

.account-card-content p {

    margin: 10px 0 0 0;

    max-width: 220px;

    font-size: 17px;

    line-height: 1.7;

    color: #858585;

}

.account-arrow {

    display: flex;

    justify-content: flex-end;

    align-items: center;

    margin-top: 20px;

}

.account-arrow span {

    font-size: 25px;

    font-weight: 300;

    transition: transform .35s ease;

}

.account-option:hover .account-arrow span {

    transform: translateX(7px);

}

.account-profile-footer {

    text-align: center;

    margin-top: 90px;

    padding-top: 50px;

    border-top: 1px solid #dedede;

}

.account-profile-footer span {

    display: block;

    font-size: 14px;

    font-weight: 700;

    letter-spacing: 4px;

    color: #999;

    margin-bottom: 15px;

}

.account-profile-footer h2 {

    margin: 0;

    font-size: 23px;

    font-weight: 500;

    letter-spacing: .5px;

    color: #222;

}

.account-profile-footer h2 strong {
    font-weight: 700;
}

.account-profile-footer h2 em {

    font-style: normal;

    color: #aaa;

    margin: 0 7px;

}

.account-inner-page {

    width: 100%;

}

.account-back {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 30px;

    color: #555;

    text-decoration: none;

    font-size: 18px;

    font-weight: 600;

    transition: color .25s ease;

}

.account-back:hover {

    color: #e681b3;

}

@media (max-width: 991px) {

    .account-dashboard {

        padding: 0 30px;

        margin-top: 45px;

    }

    .account-options {

        grid-template-columns: 1fr;

        gap: 18px;

    }

    .account-option {

        min-height: 260px;

    }

    .account-card-content p {

        max-width: 500px;

    }

    .account-profile-footer {

        margin-top: 65px;

    }

}

@media (max-width: 600px) {

    .account-dashboard {

        padding: 0 18px;

        margin-top: 30px;

        margin-bottom: 60px;

    }

    .account-hero {

        padding: 25px 0 30px 0;

    }

    .account-hero-top {

        align-items: flex-start;

        flex-direction: column;

        gap: 12px;

    }

    .account-label {

        font-size: 11px;

        letter-spacing: 3px;

    }

    .account-space {
        font-size: 10px;
        letter-spacing: 1.5px;
    }
    .account-hero h1 {
        font-size: 38px;
        letter-spacing: -1px;
    }
    .account-description {
        font-size: 14px;
    }
    .account-options {
        margin-top: 30px;
        gap: 15px;
    }
    .account-option {
        min-height: 275px;
        padding: 24px;
    }
    .account-icon {
        margin-top: 25px;
    }
    .account-card-content {
        margin-top: 22px;
    }
    .account-card-content h3 {
        font-size: 19px;
    }
    .account-card-content p {
        font-size: 13px;
    }
    .account-profile-footer {
        margin-top: 55px;
        padding-top: 35px;
    }
    .account-profile-footer h2 {
        font-size: 19px;
    }
}

@media (max-width: 380px) {
    .account-dashboard {
        padding: 0 14px;

    }

    .account-hero h1 {
        font-size: 34px;
    }
    .account-option {
        min-height: 255px;
        padding: 22px;
    }

    .account-profile-footer h2 {
        font-size: 17px;
    }

}

.profile-expand {
    display: none;
    max-width: 850px;
    margin: 35px auto 0 auto;
    padding: 45px;
    background: #fafafa;
    border: 1px solid #dedede;
    animation: profileReveal .45s ease;
}

.profile-expand.active {
    display: block;
}

.profile-expand-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 30px;
    margin-bottom: 10px;
    border-bottom: 1px solid #dedede;
}

.profile-expand-label {
    display: block;
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 3px;
    color: #999;
}

.profile-expand-header h2 {
    margin: 0;
    font-size: 34px;
    font-weight: 500;
    color: #171717;
    letter-spacing: -1px;
}

.profile-expand-header p {
    margin: 10px 0 0;
    font-size: 16px;
    color: #858585;
}

.profile-close {
    width: 42px;
    height: 42px;
    border: 1px solid #dcdcdc;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #555;
    transition: all .3s ease;
}
.profile-close:hover {
    background: #fdf0f6;
    border-color: #e681b3;
    color: #e681b3;
    transform: rotate(90deg);
}

.profile-expand .edit-card {
    max-width: 100%;
    max-width: 850px;
    margin: 25px auto 0 auto;
    box-shadow: none !important;
    border: 1px solid #dedede;

}
.profile-expand .edit-card .card-header {
    background: #fff;
}
@keyframes profileReveal {

    from {

        opacity: 0;

        transform: translateY(-15px);

    }

    to {

        opacity: 1;

        transform: translateY(0);

    }

}

@media (max-width: 767px) {

    .profile-expand {

        padding: 25px 20px;

        margin-top: 25px;

    }

    .profile-expand-header {

        gap: 20px;

    }

    .profile-expand-header h2 {

        font-size: 28px;

    }

    .profile-expand-header p {

        font-size: 13px;

        line-height: 1.6;

    }

    .profile-close {

        flex-shrink: 0;

    }

}

@media (max-width: 450px) {

    .profile-expand {

        padding: 20px 15px;

    }

    .profile-expand-header h2 {

        font-size: 24px;

    }

    .profile-expand-label {

        font-size: 10px;

        letter-spacing: 2px;

    }

}


.security-expand {
    margin-top: 35px;
}

.security-expand .password-card {
    max-width: 100%;
    margin: 25px 0 0 0;
    box-shadow: none !important;
    border: 1px solid #dedede;
}

.security-expand .password-card .card-header {
    background: #fff;
}

</style>

<div class="account-dashboard">

    <?php if ($page == "home"): ?>

        <section class="account-hero">

            <div class="account-hero-top">

                <span class="account-label">

                    MY ACCOUNT

                </span>

                <span class="account-space">

                    ✦ PERSONAL SPACE

                </span>

            </div>

            <h1>

                Welcome back,

                <span><?= $first_name; ?>.</span>

            </h1>

            <p class="account-description">

                Manage your beauty account.

            </p>

<section class="account-options">

            <a href="#profile-section" class="account-option" id="profileToggle">

                <div>

                    <div class="account-icon">

                        <i class="bi bi-person"></i>

                    </div>

                    <div class="account-card-content">

                        <h3>

                            MY PROFILE

                        </h3>

                        <p>

                            Personal information

                            and account details.

                        </p>

                    </div>

                </div>

                <div class="account-arrow">

                    <span>→</span>

                </div>

            </a>

            <!-- <a
                href="dashboard.php?page=changepassword"
                class="account-option"
            > -->
            <a
                href="#security-section"
                class="account-option"
                id="securityToggle"
            >

                <div>
                    <div class="account-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <div class="account-card-content">
                        <h3>

                            SECURITY

                        </h3>
                        <p>

                            Password and
                            account security.

                        </p>
                    </div>
                </div>

                <div class="account-arrow">

                    <span>→</span>

                </div>

            </a>

            <a href="dashboard.php?page=orderdetail"  class="account-option" >
                <div>
                    <div class="account-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="account-card-content">
                        <h3>
                            MY ORDERS
                        </h3>

                        <p>
                            Your beauty purchases
                            and order history.
                        </p>

                    </div>

                </div>
                <div class="account-arrow">

                    <span>→</span>

                </div>
            </a>

</section>

<section class="account-profile-footer">
            <span>
                YOUR BEAUTY PROFILE
            </span>
            <h2>
                <strong>

                    <?= $first_name . " " . $last_name; ?>

                </strong>
                <em>·</em>
                Member
            </h2>

</section>

<section class="profile-expand" id="profile-section">

    <div class="profile-expand-header">

        <div>

            <span class="profile-expand-label">PERSONAL INFORMATION</span>

            <h2>My Profile</h2>

            <p>Manage your personal information and account details.</p>

        </div>

        <button type="button" class="profile-close" id="profileClose">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>

    <div class="profile-expand-content">

        <?php include "editprofile.php"; ?>

    </div>

</section>



<section class="profile-expand security-expand" id="security-section">

    <div class="profile-expand-header">

        <div>

            <span class="profile-expand-label">
                ACCOUNT SECURITY
            </span>

            <h2>
                Change Password
            </h2>

            <p>
                Update your password and keep your account secure.
            </p>

        </div>

        <button
            type="button"
            class="profile-close"
            id="securityClose"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>

    <div class="profile-expand-content">

        <?php include "changepassword.php"; ?>

    </div>

</section>



    <?php else: ?>

<section class="account-inner-page">

            <a

                href="dashboard.php"

                class="account-back"

            >

                ← Back to My Account

            </a>

            <?php

            if ($page == "profile") {

                include "profile.php";

            } elseif ($page == "editprofile") {

                include "editprofile.php";

            } elseif ($page == "changepassword") {

                include "changepassword.php";

            } elseif ($page == "orderdetail") {

                include "orderdetail.php";

            } elseif ($page == "viewdetails") {

                include "viewdetails.php";

            } else {

                include "profile.php";

            }

            ?>

</section>
    <?php endif; ?>
</div>




<script>
document.addEventListener("DOMContentLoaded", function () {
    const profileToggle = document.getElementById("profileToggle");
    const profileSection = document.getElementById("profile-section");
    const profileClose = document.getElementById("profileClose");


    if (profileToggle && profileSection) {

        profileToggle.addEventListener("click", function (e) {

            e.preventDefault();
            const securitySection = document.getElementById("security-section");

            if (securitySection) {
                securitySection.classList.remove("active");
            }

            profileSection.classList.add("active");

            setTimeout(function () {

                profileSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }, 100);

        });

    }


    if (profileClose) {

        profileClose.addEventListener("click", function () {

            profileSection.classList.remove("active");

            window.scrollTo({
                top: profileToggle.offsetTop - 100,
                behavior: "smooth"
            });

        });

    }

    const securityToggle = document.getElementById("securityToggle");
    const securitySection = document.getElementById("security-section");
    const securityClose = document.getElementById("securityClose");


    if (securityToggle && securitySection) {

        securityToggle.addEventListener("click", function (e) {

            e.preventDefault();
            if (profileSection) {
                profileSection.classList.remove("active");
            }

            securitySection.classList.add("active");

            setTimeout(function () {

                securitySection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }, 100);

        });

    }


    if (securityClose) {
        securityClose.addEventListener("click", function () {
            securitySection.classList.remove("active");
            window.scrollTo({
                top: securityToggle.offsetTop - 100,
                behavior: "smooth"
            });
        });
    }

});

</script>

