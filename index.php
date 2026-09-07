<?php
include "includes/header.php";
include "includes/database.php";

$sql = "SELECT * FROM categories WHERE status = true  ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql="SELECT * FROM products ORDER BY id DESC LIMIT 8";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products= $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
.products-heading {
    text-align: center;
    margin: 50px auto 35px;
    padding: 15px 0;
    position: relative;
}
.products-heading::before,
.products-heading::after {
    content: "";
    display: block;
    width: 390px;
    height: 3px;
    background: #eb3f81;
    margin: 0 auto;
    border-radius: 5px;
}
.products-heading::before {
    margin-bottom: 15px;
}
.products-heading::after {
    margin-top: 15px;
}
.products-heading h2 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
    color: #222;
    letter-spacing: 1px;
}
@media (min-width: 769px) and (max-width: 992px) {
    .products-heading {
        margin: 40px auto 30px;
    }
    .products-heading h2 {
        font-size: 28px;
    }
    .products-heading p {
        font-size: 15px;
    }
}
@media (min-width: 481px) and (max-width: 768px) {
    .products-heading {
        margin: 35px auto 25px;
        padding: 0 15px;
    }
    .products-heading h2 {
        font-size: 25px;
    }
    .products-heading p {
        font-size: 14px;
    }
}
@media (max-width: 480px) {
    .products-heading {
        margin: 30px auto 25px;
        padding: 0 15px;
    }
    .products-heading h2 {
        font-size: 23px;
    }
    .products-heading p {
        font-size: 14px;
        line-height: 1.5;
    }
    .heading-line {
        display: block;
        width: 45px;
        height: 3px;
        margin: 10px auto;
    }
}




.skincare-hero {
    width: 100%;
    height: 100vh; 
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: #eeeae8;
}

.hero-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    opacity: 0;
    visibility: hidden;
    transform: scale(1.02);
    transition:
        opacity 1s ease-in-out,
        visibility 1s ease-in-out,
        transform 6s ease;
}
.hero-slide.active {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            90deg,
            rgba(25, 22, 22, 0.62) 0%,
            rgba(25, 22, 22, 0.42) 32%,
            rgba(25, 22, 22, 0.12) 65%,
            rgba(25, 22, 22, 0.03) 100%
        );
}

.hero-content {
    position: relative;
    z-index: 2;
    height: 100%;
    max-width: 1250px;
    margin: 0 auto;
    padding: 0 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    transform: translateX(-290px);
}


.hero-tag {
    color: #ffffff;
    font-size: 13px;

    font-weight: 700;

    letter-spacing: 3px;

    margin-bottom: 20px;

    position: relative;

    padding-left: 38px;
}

.hero-tag::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    width: 25px;
    height: 1px;
    background: #eb3f81;
}
.hero-content h1 {
    margin: 0;
    color: #ffffff;
    font-size: 68px;
    line-height: 1.05;
    font-weight: 700;
    letter-spacing: -2px;
}
.hero-content h1 span {
    color: #eb3f81;
}
.hero-content p {
    margin: 25px 0 32px;
    max-width: 510px;
    color: #ffffff !important;
    font-size: 20px !important;
    line-height: 1.75;
}
.hero-buttons {
    display: flex;
    align-items: center;
    gap: 17px;
}
.hero-btn {
    min-width: 145px;
    height: 50px;
    padding: 0 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 18px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.hero-btn-primary {
    background: #eb3f81;
    color: #ffffff;
    border: 1px solid #eb3f81;
    box-shadow: 0 8px 25px rgba(235, 63, 129, 0.25);
}

.hero-btn-primary:hover {
    background: #d92f6e;
    border-color: #d92f6e;
    color: #ffffff;
    transform: translateY(-2px);
}

.hero-btn-primary i {
    transition: transform 0.3s ease;
}

.hero-btn-primary:hover i {
    transform: translateX(4px);
}


.hero-btn-outline {
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.7);
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(5px);
}

.hero-btn-outline:hover {
    background: #ffffff;
    color: #292525;
    border-color: #ffffff;
    transform: translateY(-2px);
}

.hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.55);
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
    color: #ffffff;
    font-size: 17px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.hero-arrow:hover {
    background: #eb3f81;
    border-color: #eb3f81;
}

.hero-prev {
    left: 25px;
}

.hero-next {
    right: 25px;
}

.hero-dots {
    position: absolute;
    z-index: 5;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;

    gap: 8px;
}

.hero-dot {
    width: 8px;
    height: 8px;
    padding: 0;
    border: none;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.55);
    cursor: pointer;
    transition: all 0.35s ease;
}

.hero-dot.active {
    width: 30px;

    background: #eb3f81;
}



.why-shop-section {
    padding: 150px 30px;
    background: #ffffff;
}
.why-shop-container {
    max-width: 1400px;
    margin: 0 auto;
}
.why-shop-heading {
    text-align: center;
    max-width: 350px;
    margin: 0 auto 55px;
}
.why-shop-heading span {
    display: inline-block;
    margin-bottom: 12px;
    color: #eb3f81;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 3px;
}

.why-shop-heading h2 {
    margin: 0 0 15px;
    color: #222;
    font-size: 34px;
    font-weight: 700;
    letter-spacing: -0.5px;
}
.why-shop-heading p {
    margin: 0;
    color: #777;
    font-size: 18px;
    line-height: 1.7;
}
.why-shop-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid #eeeeee;
    border-bottom: 1px solid #eeeeee;
}
.why-shop-item {
    position: relative;
    text-align: center;
    padding: 40px 30px;
}
.why-shop-item:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 35px;
    right: 0;
    width: 1px;
    height: 100px;
    background: #eeeeee;
}
.why-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff4f8;
    color: #eb3f81;
    font-size: 23px;
    transition: all 0.3s ease;
}

.why-shop-item:hover .why-icon {
    background: #eb3f81;
    color: #ffffff;
    transform: translateY(-4px);
}
.why-shop-item h3 {
    margin: 0 0 10px;
    color: #292929;
    font-size: 18px;
    font-weight: 600;
}
.why-shop-item p {
    max-width: 210px;
    margin: 0 auto;
    color: #888;
    font-size: 16px;
    line-height: 1.7;
}

@media (max-width: 992px) {
    .why-shop-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .why-shop-item:nth-child(2)::after {
        display: none;
    }
    .why-shop-item:nth-child(1),
    .why-shop-item:nth-child(2) {
        border-bottom: 1px solid #eeeeee;
    }
    .why-shop-item:nth-child(3)::after {
        display: block;
    }
}


@media (max-width: 576px) {
    .why-shop-section {
        padding: 65px 20px;
    }
    .why-shop-heading {
        margin-bottom: 40px;
    }
    .why-shop-heading h2 {
        font-size: 28px;
    }
    .why-shop-grid {
        grid-template-columns: 1fr;
    }
    .why-shop-item {
        padding: 30px 20px;
        border-bottom: 1px solid #eeeeee;
    }
    .why-shop-item:not(:last-child)::after {
        display: none;
    }

    .why-shop-item:last-child {
        border-bottom: none;
    }
}
</style>


<section class="skincare-hero">

    <div class="hero-slide active"
         style="background-image: url('images/back3.png');">

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <span class="hero-tag">PREMIUM SKINCARE</span>

            <h1>
                Your Skin.<br>
                <span>Your Glow.</span>
            </h1>

            <p>
                Discover carefully selected skincare essentials
                created to nourish, hydrate and reveal your
                natural glow.
            </p>

            <div class="hero-buttons">
                <a href="product.php" class="hero-btn hero-btn-primary">
                    Shop Now
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="contactus.php" class="hero-btn hero-btn-outline">
                    Learn More
                </a>
            </div>
        </div>
    </div>


    <div class="hero-slide"
         style="background-image: url('images/back4.jfif');">

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <span class="hero-tag">YOUR DAILY RITUAL</span>

            <h1>
                Care For<br>
                <span>Your Skin.</span>
            </h1>

            <p>
                Turn your everyday routine into a moment
                of self-care with products your skin will love.
            </p>

            <div class="hero-buttons">
                <a href="product.php" class="hero-btn hero-btn-primary">
                    Shop Skincare
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="about.php" class="hero-btn hero-btn-outline">
                    Discover More
                </a>
            </div>
        </div>
    </div>


    <div class="hero-slide"
         style="background-image: url('images/back2.png');">

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <span class="hero-tag">NATURAL BEAUTY</span>

            <h1>
                Let Your Skin<br>
                <span>Shine.</span>
            </h1>

            <p>
                Beautiful skin starts with the right care.
                Find everything you need for your perfect glow.
            </p>

            <div class="hero-buttons">
                <a href="product.php" class="hero-btn hero-btn-primary">
                    Shop Now
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a href="about.php" class="hero-btn hero-btn-outline">
                    Our Story
                </a>
            </div>
        </div>
    </div>

    <button class="hero-arrow hero-prev" aria-label="Previous slide">
        <i class="bi bi-chevron-left"></i>
    </button>

    <button class="hero-arrow hero-next" aria-label="Next slide">
        <i class="bi bi-chevron-right"></i>
    </button>

    <div class="hero-dots">
        <button class="hero-dot active" data-slide="0"></button>
        <button class="hero-dot" data-slide="1"></button>
        <button class="hero-dot" data-slide="2"></button>
    </div>

</section>



<section class="categories-menu">
    <div class="categories-nav">
        <?php foreach($categories as $category): ?>
            <a href="categories.php?id=<?= $category['id']; ?>" class="category-link">
                <?= htmlspecialchars($category['name']); ?>
            </a>
        <?php endforeach; ?>
        <a href="saleproducts.php" class="category-link sale-link">
            Sale Products
        </a>
    </div>
</section>


<section class="products-section">

    <div class="products-heading">
        <h2>TOP NEWEST PRODUCTS</h2>
    </div>
    <div class="products-container">
        <?php foreach($products as $product): ?>
            <div class="product-card">
                <div class="product-info">
                    <img 
                        src="images/<?= htmlspecialchars($product['image']); ?>" 
                        alt="<?= htmlspecialchars($product['name']); ?>"
                    >

                    <h3>
                        <?= htmlspecialchars($product['name']); ?>
                    </h3>
                    <div class="price">

                        <?php if(
                            $product['sale_price'] !== null && 
                            $product['sale_price'] < $product['price']
                        ): ?>

                            <span class="sale-price">
                                $<?= number_format($product['sale_price'], 2); ?>
                            </span>

                            <span class="old-price">
                                $<?= number_format($product['price'], 2); ?>
                            </span>

                        <?php else: ?>

                            <span class="regular-price">
                                $<?= number_format($product['price'], 2); ?>
                            </span>

                        <?php endif; ?>
                    </div>
                </div>

                <div class="product-bottom">
                    <?php if($product['stock'] > 0): ?>

                        <span class="stock">
                            In Stock
                        </span>

                    <?php else: ?>

                        <span class="stock">
                            Out of Stock
                        </span>

                    <?php endif; ?>
                    <div class="buttons">
                        <a href="productdetails.php?id=<?= $product['id']; ?>">
                            View Product
                        </a>
                        <a 
                            href="cart.php?action=add&id=<?= $product['id']; ?>" 
                            class="btn-add">
                            Add to Cart
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>



<section class="why-shop-section">
    <div class="why-shop-container">

        <div class="why-shop-heading">
            <h2>Why Shop With Us?</h2>
            <p>Everything you need for a simple, beautiful skincare routine.</p>
        </div>

        <div class="why-shop-grid">

            <div class="why-shop-item">
                <div class="why-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <h3>Free Shipping</h3>
                <p>Enjoy free delivery on orders over $50.</p>
            </div>

            <div class="why-shop-item">
                <div class="why-icon">
                    <i class="bi bi-stars"></i>
                </div>
                <h3>Quality Products</h3>
                <p>Carefully selected products your skin will love.</p>
            </div>

            <div class="why-shop-item">
                <div class="why-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Secure Payment</h3>
                <p>Your payment and personal information are protected.</p>
            </div>

            <div class="why-shop-item">
                <div class="why-icon">
                    <i class="bi bi-heart"></i>
                </div>
                <h3>Made With Care</h3>
                <p>We choose beauty essentials with you in mind.</p>
            </div>

        </div>

    </div>
</section>

<section class="brands-section">
    <div class="brands-track">
        <div class="brand-item"><img src="images/147.png" alt="Brand 1"></div>
        <div class="brand-item"><img src="images/345.png" alt="Brand 2"></div>
        <div class="brand-item"><img src="images/anas.png" alt="Brand 3"></div>
        <div class="brand-item"><img src="images/beautyj.png" alt="Brand 4"></div>
        <div class="brand-item"><img src="images/dior.png" alt="Brand 5"></div>

        <div class="brand-item"><img src="images/147.png" alt="Brand 1"></div>
        <div class="brand-item"><img src="images/345.png" alt="Brand 2"></div>
        <div class="brand-item"><img src="images/anas.png" alt="Brand 3"></div>
        <div class="brand-item"><img src="images/beautyj.png" alt="Brand 4"></div>
        <div class="brand-item"><img src="images/dior.png" alt="Brand 5"></div>

    </div>
</section>


<section class="newsletter-section newsletter-popup-overlay" id="newsletter">
    <div class="newsletter-card">
       <span class="newsletter-popup-close" id="closeNewsletterPopup">×</span>
        <div class="newsletter-icon">
            ✉
        </div>
        <h2>
            Subscribe to our newsletter
        </h2>
        <h6>
            Get latest products and offers.
        </h6>

        <form id="newsletterForm" action="newsletter.php" method="POST">
            <input type="email"  name="email"
                placeholder="Enter your email"
                required >
            <button type="submit">
                Subscribe
            </button>
        </form>
        
    </div>
</section> 



<?php
include "includes/footer.php";
?>

<script>
const slides = document.querySelectorAll(".slide");
let currentSlide = 0;

function showSlide(index) {
    slides.forEach(slide => {
        slide.classList.remove("active");
    });
    slides[index].classList.add("active");
}

setInterval(() => {
    currentSlide++;
    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }
    showSlide(currentSlide);
}, 3000);



const newsletterForm = document.getElementById("newsletterForm");
newsletterForm.addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(newsletterForm);
    fetch("newsletter.php", {
        method: "POST",
        body: formData
    })

    .then(response => response.json())
    .then(data => {
        const oldPopup = document.querySelector(".popup-message");
        if (oldPopup) {
            oldPopup.remove();
        }
        const popup = document.createElement("div");
        popup.className = data.success
            ? "popup-message success-popup"
            : "popup-message error-popup";

        popup.innerHTML = `
            <div class="popup-content">
                <div class="popup-icon">
                    ${data.success ? "✓" : "✕"}
                </div>

                <p>
                    ${data.message}
                </p>
            </div>
        `;
        newsletterForm.parentElement.appendChild(popup);

        setTimeout(function() {

            popup.style.opacity = "0";
            popup.style.transition = "opacity 0.5s ease";

            setTimeout(function() {
                popup.remove();
            }, 500);

        }, 3000);

        if (data.success) {
            newsletterForm.reset();
            setTimeout(function() {
                newsletterPopup.classList.remove("show");
            }, 1000);
        }
    })
    .catch(error => {
        console.error(error);
    });
});



const newsletterPopup = document.getElementById("newsletter");
const closeNewsletterPopup = document.getElementById("closeNewsletterPopup");

let newsletterShown = false;

// setTimeout(function () {
//     if (!newsletterShown) {
//         newsletterPopup.classList.add("show");
//         newsletterShown = true;
//     }
// }, 7000);

closeNewsletterPopup.addEventListener("click", function () {
    newsletterPopup.classList.remove("show");
});

newsletterPopup.addEventListener("click", function (event) {
    if (event.target === newsletterPopup) {
        newsletterPopup.classList.remove("show");
    }
});



const brandsTrack=document.getElementById("brandsTrack");
if(brandsTrack){
   const items = Array.from(brandsTrack.children); 
   items.forEach(items=>{
        const clone = item.cloneNode(true);
        brandsTrack.appendChild(clone);
   });
}



document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".hero-dot");

    const prevButton = document.querySelector(".hero-prev");
    const nextButton = document.querySelector(".hero-next");

    let currentSlide = 0;

    let autoSlide;
    function showSlide(index) {

        if (index >= slides.length) {
            index = 0;
        }
        if (index < 0) {
            index = slides.length - 1;
        }

        currentSlide = index;
        slides.forEach((slide, i) => {
            slide.classList.toggle(
                "active",
                i === currentSlide
            );
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle(
                "active",
                i === currentSlide
            );
        });

    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function previousSlide() {
        showSlide(currentSlide - 1);
    }

    nextButton.addEventListener(
        "click",
        function () {
            nextSlide();
            restartAutoSlide();

        }
    );


    prevButton.addEventListener(
        "click",
        function () {
            previousSlide();
            restartAutoSlide();
        }
    );

    dots.forEach((dot) => {
        dot.addEventListener(
            "click",
            function () {
                const slideNumber =
                    Number(this.dataset.slide);
                showSlide(slideNumber);
                restartAutoSlide();
            }
        );
    });

    function startAutoSlide() {
        autoSlide = setInterval(
            nextSlide,
            5500
        );
    }

    function restartAutoSlide() {
        clearInterval(autoSlide);
        startAutoSlide();
    }

    showSlide(0);
    startAutoSlide();

    const hero =
        document.querySelector(".skincare-hero");
    hero.addEventListener(
        "mouseenter",
        function () {
            clearInterval(autoSlide);
        }
    );

    hero.addEventListener(
        "mouseleave",
        function () {
            startAutoSlide();
        }
    );
});
</script>