<?php
include "includes/header.php";
include "includes/database.php";

$sql = "SELECT * FROM categories WHERE status = true  ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql="SELECT * FROM products ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products= $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<section class="hero-section">
    <div class="hero-card">
        <div class="hero-content">
            <h5>Discover Our Amazing Products</h5>
            <p>
                Find premium quality products at the best prices.
                Shop with confidence and enjoy fast delivery.
            </p>
            <div class="hero-buttons">
                <a href="products.php" class="btn-primary">Shop Now</a>
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


<section class="newsletter-section" id="newsletter">
    <div class="newsletter-card">
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


<!-- <?php if (isset($_SESSION["newsletter_error"])): ?>
    <div class="popup-message error-popup">
        <div class="popup-content">

            <div class="popup-icon">
                ✕
            </div>

            <p>
                <?= htmlspecialchars($_SESSION["newsletter_error"]) ?>
            </p>

        </div>
    </div>
    <?php unset($_SESSION["newsletter_error"]); ?>
<?php endif; ?>


<?php if (isset($_SESSION["newsletter_success"])): ?>
    <div class="popup-message success-popup">
        <div class="popup-content">

            <div class="popup-icon">
                ✓
            </div>

            <p>
                <?= htmlspecialchars($_SESSION["newsletter_success"]) ?>
            </p>
        </div>
    </div>
    <?php unset($_SESSION["newsletter_success"]); ?>
<?php endif; ?> -->



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
        }
    })
    .catch(error => {
        console.error(error);
    });
});




// setTimeout(function () {
//         const popup = document.querySelector('.popup-message');

//         if (popup) {
//             popup.style.opacity = '0';
//             popup.style.transition = 'opacity 0.5s ease';

//             setTimeout(function () {
//                 popup.remove();
//             }, 500);
//         }
//     }, 3000);

</script>