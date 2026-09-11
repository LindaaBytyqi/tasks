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
    /* margin-top: 10px; */
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
 .accordion-title {
     display: none;
} 
.accordion-content {
     max-height: none; 
     overflow: visible;
      opacity: 1; 
    } 
@media (max-width: 576px) {
    .site-footer {
    margin-top: 0;
    }
  .footer-columns .accordion-title {
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        background: none;
        border: none;
        color: #111 !important;
        font-family: inherit !important;
        font-size: 17px !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        opacity: 1 !important;
        cursor: pointer;
        text-align: left;
    }
    .footer-columns .accordion-title span {
        color: #111 !important;
        font-family: inherit !important;
        font-size: 17px !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        margin: 0 !important;
        padding: 0 !important;
        opacity: 1 !important;
    }
    .footer-columns .accordion-title i {
        color: #111 !important;
        font-size: 16px !important;
        margin: 0 !important;
    }
    .footer-main { 
        padding: 30px 0 20px;
    } 
    .footer-container {
        padding: 0 20px;
    } 
    .footer-top { 
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
        margin-bottom: 25px; 
    } 
    .footer-logo {
        font-size: 32px;
        letter-spacing: -1.5px;
        min-width: auto; 
    } 
    .footer-socials { 
        margin: 0;
        gap: 13px;
    } 
    .footer-socials a { 
        width: 35px;
        height: 35px;
        font-size: 18px;
    } 
    .footer-line { 
        order: 3;
        width: 100%;
        height: 2px;
        margin: 8px 0 0; 
    } 
    .footer-columns { 
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 0; 
    } 
    .footer-column {
        width: 100%;
        align-items: flex-start;
        text-align: left; 
    } 
    .accordion-column {
        border-bottom: 1px solid #c8c6c5; 
    } 
    .accordion-title { 
        display: flex;
        width: 100%;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        background: none;
        border: none;
        color: #111;
        font-family: inherit; 
        font-size: 17px;
        font-weight: 600;
        cursor: pointer; 
        text-align: left;
    }  
    .accordion-title i { 
        font-size: 14px;
        transition: transform 0.3s ease;
    } 
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        padding: 0; 
        transition: max-height 0.35s ease, opacity 0.25s ease, padding 0.35s ease; } 
         
    .accordion-column.active .accordion-content { 
        max-height: 300px; 
        opacity: 1; 
        padding: 0 0 16px; 
    } 
    .accordion-column.active .accordion-title i {
         transform: rotate(180deg); 
    } 
    .accordion-content h3 { 
        margin: 0 0 7px;
        font-size: 15px;
        font-weight: 600;
        color: #111; 
    } 
    .accordion-content h3 i { 
        margin-right: 6px; 
        color: #111; 
        font-size: 15px; 
    } 
    .accordion-content p { 
        margin: 0; 
        font-size: 14px; 
        line-height: 1.5; 
        color: #26364a; 
    } 
    .accordion-content .second-location { 
        margin-top: 18px !important; 
    }  
    .accordion-content a { 
        display: block;
        margin-bottom: 11px;
        font-size: 15px;
        color: #26364a;
        text-decoration: none;
        transition: color 0.3s ease; 
    } 
    .accordion-content a:last-child {
        margin-bottom: 0; 
    } 
    .accordion-content a:hover { 
        color: #eb3f81;
     } 
    .contact-column .accordion-content span {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #777; 
    } 
    .contact-column .accordion-content span i {
        margin-right: 6px;
        color: #111;
        font-size: 14px;
    } 
    .contact-column .accordion-content .contact-link { 
        display: block;
        margin-bottom: 15px;
        font-size: 15px; 
        color: #111; 
    } 
    .contact-column .accordion-content .contact-link:last-child {
        margin-bottom: 0;
    } 
    .footer-bottom {
        min-height: 65px;
    } 
    .footer-bottom-container {
        padding: 0 15px;
        justify-content: center;
        text-align: center;
    } 
    .footer-bottom p { 
        margin: 0;
        font-size: 12px; 
        line-height: 1.4; 
        text-align: center; 
        
    } 
} 
@media (max-width: 380px) { 
    .footer-main { 
        padding: 25px 0 18px;
    } 
    .footer-container {
        padding: 0 18px;
    } 
    .footer-logo {
        font-size: 29px;
    }
    .footer-socials {
        gap: 10px; 
    } 
    .footer-socials a {
        width: 32px;
        height: 32px;
        font-size: 16px; 
    } 
    .accordion-title { 
        padding: 14px 0; 
        font-size: 16px; 
    } 
    .accordion-title i { 
        font-size: 13px;
    } 
    .accordion-content a { 
        font-size: 14px;
    } 
    .accordion-content p { 
        font-size: 13px;
    }
    .footer-bottom { 
        min-height: 60px;
    } 
     .footer-bottom p {
         font-size: 11px;
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

                <div class="footer-column accordion-column">
                    <button class="accordion-title" type="button">
                        <span>Locations</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="accordion-content">

                        <h3>
                            <i class="bi bi-geo-alt-fill"></i>
                            Georgia
                        </h3>

                        <p>
                            105 Rigby Dr Warner<br>
                            Usain Robins
                        </p>

                        <h3 class="second-location">
                            <i class="bi bi-geo-alt-fill"></i>
                            Texas
                        </h3>
                        <p>
                            12111 Gulf Fwy Byon<br>
                            Houston
                        </p>
                    </div>
                </div>



                <div class="footer-column accordion-column">

                    <button class="accordion-title" type="button">
                        <span>Quick Links</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="accordion-content">
                        <h3>Quick Links</h3>

                        <a href="index.php">
                            Home
                        </a>

                        <a href="product.php">
                            Shop Now
                        </a>

                        <a href="contactus.php">
                            Contact
                        </a>

                        <a href="aboutus.php">
                            AboutUs
                        </a>

                        <a href="register.php">
                            Account
                        </a>
                    </div>
                </div>

                <div class="footer-column contact-column accordion-column">
                    <button class="accordion-title" type="button">
                        <span>Contact Info</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="accordion-content">

                        <h3>Contact Info</h3>

                        <span>
                            <i class="bi bi-envelope-fill"></i>
                            Email Us
                        </span>

                        <a href="mailto:info@yoursite.com"
                           class="contact-link">
                            info@yoursite.com
                        </a>

                        <span>
                            <i class="bi bi-telephone-fill"></i>
                            Phone
                        </span>

                        <a href="tel:+38349123456"
                           class="contact-link">
                            +383 49 123 456
                        </a>
                    </div>
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


<script>
document.querySelectorAll('.accordion-title').forEach(button => {
    button.addEventListener('click', function () {
        const current = this.closest('.accordion-column');

        document.querySelectorAll('.accordion-column').forEach(column => {

            if (column !== current) {
                column.classList.remove('active');
            }

        });
        current.classList.toggle('active');

    });

});

</script>
</body>
</html>