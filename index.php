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
</style>
<section class="hero-section">
    <div class="hero-card">
        <div class="hero-content">
            <h5>Discover Our Amazing Products</h5>
            <p>
                Find premium quality products at the best prices.
                Shop with confidence and enjoy fast delivery.
            </p>
            <div class="hero-buttons">
                <a href="product.php" class="btn-primary">Shop Now</a>
                <a href="about.php" class="btn-secondary">Learn More</a>
            </div>
        </div>
    <div class="hero-image">
    <div class="slider">
        <img src="images/hair1.png" class="slide active">
        <img src="images/skincare1.png" class="slide">
        <img src="images/makeup1.png" class="slide">
        <img src="images/perfume2.png" class="slide">
        <img src="images/bodycare.png" class="slide">
        <img src="images/skincare2.png" class="slide">
    </div>
    </div>
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

setTimeout(function () {
    newsletterPopup.classList.add("show");
}, 7000);

closeNewsletterPopup.addEventListener("click", function () {
    newsletterPopup.classList.remove("show");
});

newsletterPopup.addEventListener("click", function (event) {
    if (event.target === newsletterPopup) {
        newsletterPopup.classList.remove("show");

    }

});

</script>