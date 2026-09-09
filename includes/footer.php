<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/tasks/css/style.css">
    <title>Document</title>
<style>
.site-footer {
    width: 100%;
    background: #e9e8e8;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #111;
    margin-top: 10px;
}
.footer-main {
    padding: 65px 0 65px;
}
.footer-container {
    width: 100%;
    max-width: 1590px;
    margin: 0 auto;
    padding: 0 20px;
}
.footer-top {
    display: flex;
    align-items: center;
    width: 100%;
    margin-bottom: 68px;
}
.footer-logo {
    font-size: 42px;
    font-weight: 700;
    letter-spacing: -2px;
    min-width: 125px;
    color: #eb3f81;
}
.footer-logo span {
    color: #eb3f81;
}
.footer-line {
    height: 4px;
    /* background: #d9d9d9; */
    background: #6d6a6a;
    flex: 1;
    margin: 0 45px;
}
.footer-socials {
    display: flex;
    gap: 20px;
    align-items: center; 
}
.footer-socials a {
    color: #111;
    text-decoration: none;
    font-size: 33px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.footer-socials a:hover {
    color: #eb3f81;
    transform: translateY(-2px);
}
.footer-columns {
    display: grid;
    grid-template-columns: 1.1fr 1fr 1fr;
    column-gap: 80px;
}
.footer-column {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.footer-column h3 {
    margin: 0 0 22px;
    font-size: 21px;
    font-weight: 600;
    color: #111;
}
.footer-column p {
    margin: 0;
    font-size: 18px;
    font-weight: 500;
    line-height: 1.8;
    color: #26364a;
}
.footer-column a {
    display: block;
    margin-bottom: 18px;
    font-size: 18px;
    color: #26364a;
    text-decoration: none;
    transition: color 0.3s ease;
}
.footer-column a:hover {
    color: #eb3f81;
}
.second-location {
    margin-top: 25px !important;
}
.contact-column span {
    color: #999;
    font-size: 18px;
    margin-bottom: 7px;
}
.contact-column .contact-link {
    color: #111;
    font-size: 18px;
    font-weight: 500;
    text-decoration: underline;
    margin-bottom: 27px;
}
.contact-column .contact-link:hover {
    color: #eb3f81;
}
.phone-title {
    margin-top: 0;
}
.footer-bottom {
    position: relative;
    background: #d4d1cf;
    min-height: 105px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.footer-bottom-container {
    width: 100%;
    max-width: 1290px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}
.footer-bottom p {
    margin: 0;
    font-size: 19px;
    color: #111;
    text-align: center;
}

.footer-column h3 i {
    color: #111;
    margin-right: 8px;
    font-size: 21px;
}

.contact-column span i {
    color: #111;
    margin-right: 7px;
    font-size: 21px;
}

@media (max-width: 1100px) {
    .footer-container {
        max-width: 100%;
        padding: 0 40px;
    }
    .footer-line {
        margin: 0 30px;
    }
    .footer-columns {
        column-gap: 50px;
    }
    .footer-column h3 {
        font-size: 19px;
    }
    .footer-column p,
    .footer-column a,
    .contact-column span,
    .contact-column .contact-link {
        font-size: 16px;
    }
}
@media (max-width: 768px) {
    .footer-main {
        padding: 50px 0 60px;
    }
    .footer-container {
        padding: 0 30px;
    }
    .footer-top {
        flex-wrap: wrap;
        margin-bottom: 50px;
        gap: 25px;
    }
    .footer-logo {
        font-size: 38px;
        min-width: auto;
    }

    .footer-socials {
        margin-left: auto;
    }
    .footer-line {
        order:3;
        flex-basis: 100%;
        width: 100%;
        margin: 0;
        height: 3px;
    }
    .footer-columns {
        grid-template-columns: 1fr 1fr;
        column-gap: 50px;
        row-gap: 45px;
    }
    .contact-column {
        grid-column: 1 / -1;
    }
    .footer-column h3 {
        font-size: 19px;
        margin-bottom: 18px;
    }
    .footer-column p,
    .footer-column a {
        font-size: 16px;
    }
    .contact-column span {
        font-size: 16px;
    }
    .contact-column .contact-link {
        font-size: 17px;
    }
    .footer-bottom {
        min-height: 90px;
    }
    .footer-bottom-container {
        padding: 0 25px;
    }

    .footer-bottom p {
        font-size: 17px;
    }
}

@media (max-width: 576px) {
    .footer-main {
        padding: 40px 0 50px;
    }
    .footer-container {
        padding: 0 20px;
    }
    .footer-top {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 22px;
        margin-bottom: 40px;
    }
    .footer-logo {
        font-size: 36px;
        text-align: center;
    }
    .footer-socials {
        margin: 0;
        gap: 10px;
    }
    .footer-socials a {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    .footer-line {
        order: 3;
        width: 100%;
        margin-top: 5px;
    }
    .footer-columns {
        grid-template-columns: 1fr;
        gap: 35px;
        justify-items: center;
        text-align: center;
    }
    .footer-column {
        width: 100%;
        align-items: center;
        text-align: center;
    }
    .contact-column {
        grid-column: auto;
    }
    .footer-column h3 {
        font-size: 19px;
        margin-bottom: 15px;
        text-align: center;
    }
    .footer-column p {
        font-size: 16px;
        text-align: center;
    }
    .footer-column a {
        font-size: 16px;
        margin-bottom: 13px;
        text-align: center;
    }
    .second-location {
        margin-top: 25px !important;
    }
    .contact-column span {
        font-size: 15px;
        text-align: center;
    }
    .contact-column .contact-link {
        font-size: 16px;
        margin-bottom: 22px;
        text-align: center;
    }
    .footer-bottom {
        min-height: 80px;
    }
    .footer-bottom-container {
        padding: 0 15px;
        justify-content: center;
        text-align: center;
    }
    .footer-bottom p {
        font-size: 14px;
        line-height: 1.5;
        text-align: center;
    }
}

@media (max-width: 380px) {
    .footer-logo {
        font-size: 32px;
    }
    .footer-socials a {
        width: 37px;
        height: 37px;
        font-size: 15px;
    }
    .footer-bottom p {
        font-size: 13px;
    }
}

</style>
</head>
<body>
    
<footer class="site-footer">
    <div class="footer-main">
        <div class="footer-container">
            <div class="footer-top">
                <div class="footer-logo">
                   MyShop
                </div>

                <div class="footer-line"></div>
                <div class="footer-socials">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-pinterest"></i></a>
                </div>
            </div>

            <div class="footer-columns">
                <div class="footer-column">
                    <h3><i class="bi bi-geo-alt-fill"></i> Georgia</h3>
                         <p>
                            105 Rigby Dr Warner<br>
                            Usain Robins
                        </p>

                        <h3 class="second-location">
                            <i class="bi bi-geo-alt-fill"></i> Texas
                        </h3>
                        <p>
                            12111 Gulf Fwy Byon<br>
                            Houston
                        </p>
                </div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <a href="index.php">Home</a>
                    <a href="product.php">Shop Now</a>
                    <a href="contactus.php">Contact</a>
                    <a href="register.php">Account</a>
                </div>

                <div class="footer-column contact-column">
                    <h3>Contact Info</h3>

                     <span>
                        <i class="bi bi-envelope-fill"></i> Email Us
                    </span>
                    <a href="mailto:hello@posh.com" class="contact-link">
                        info@yoursite.com
                    </a>

                    <span class="phone-title">
                        <i class="bi bi-telephone-fill"></i> Phone
                    </span>
                    <a href="tel:+16787726710" class="contact-link">
                        +383 49 123 456
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-container">
            <p>
                © Copyright 2026 MyShop. All Rights Reserved
            </p>  
        </div>
    </div>

</footer>
</body>
</html>