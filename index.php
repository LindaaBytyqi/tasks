<?php
include "includes/header.php";
include "includes/database.php";

$sql = "SELECT * FROM categories WHERE status = true  ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql="SELECT * FROM products ORDER BY id DESC LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products= $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "SELECT * FROM hero_slides
    WHERE status = true
    ORDER BY sort_order ASC, id ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$slides = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "
    SELECT id, title, describe, published_at, main_image
    FROM blogs
    WHERE status = TRUE
    ORDER BY published_at ASC
    LIMIT 3
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);


$cat_sql = "SELECT c.*, COUNT(p.id) AS total_products  
            FROM categories c  
            LEFT JOIN products p ON c.id = p.category_id  
            GROUP BY c.id"; 
$cat_stmt = $conn->prepare($cat_sql); 
$cat_stmt->execute(); 
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC); 


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
    transform: translateX(100%);
    transition:
        transform 0.55s cubic-bezier(.4, 0, .2, 1),
        opacity 0.4s ease;
}
.hero-slide.active {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
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

.hero-content-right {
    margin-left: auto;
    margin-right: 0;
    transform: translateX(290px);
}

.hero-slide {
    background-size: cover;
    background-position: center center;
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

.skincare-hero {
    cursor: grab;
    user-select: none;
}
.skincare-hero.is-dragging {
    cursor: grabbing;
}
.skincare-hero.is-dragging .hero-slide {
    transition: none !important;
}

@media (max-width: 1200px) {
    .skincare-hero {
        height: 80vh;
        min-height: 650px;
    }
    .hero-content {
        max-width: 100%;
        padding: 0 80px;
        transform: translateX(0);
    }
    .hero-content h1 {
        font-size: 58px;
    }
    .hero-content p {
        max-width: 480px;
        font-size: 18px !important;
    }
    .hero-arrow {
        width: 44px;
        height: 44px;
    }
    .hero-prev {
        left: 20px;
    }
    .hero-next {
        right: 20px;
    }
}

@media (max-width: 992px) {
    .skincare-hero {
        height: 75vh;
        min-height: 600px;
    }
    .hero-content {
        padding: 0 60px;
        transform: translateX(0);
    }
    .hero-tag {
        font-size: 12px;
        letter-spacing: 2.5px;
        margin-bottom: 16px;
    }
    .hero-content h1 {
        font-size: 52px;
        line-height: 1.08;
        letter-spacing: -1.5px;
    }
    .hero-content p {
        max-width: 440px;
        margin: 22px 0 28px;
        font-size: 17px !important;
        line-height: 1.65;
    }
    .hero-btn {
        min-width: 135px;
        height: 47px;
        padding: 0 20px;
        font-size: 16px;
    }
    .hero-arrow {
        width: 42px;
        height: 42px;
        font-size: 15px;
    }
    .hero-prev {
        left: 15px;
    }

    .hero-next {
        right: 15px;
    }

    .hero-dots {
        bottom: 25px;
    }
}

@media (max-width: 768px) {
    .skincare-hero {
        height: 75vh;
        min-height: 560px;
        max-height: 700px;
    }
    .hero-slide {
        background-position: center center;
    }
    .hero-overlay {
        background:
            linear-gradient(
                90deg,
                rgba(25, 22, 22, 0.70) 0%,
                rgba(25, 22, 22, 0.50) 45%,
                rgba(25, 22, 22, 0.18) 100%
            );
    }
    .hero-content {
        width: 100%;
        max-width: 100%;
        height: 100%;
        padding: 0 55px;
        justify-content: center;
        align-items: flex-start;
        transform: translateX(0);
    }

    .hero-tag {
        font-size: 11px;
        letter-spacing: 2px;
        margin-bottom: 15px;
        padding-left: 30px;
    }

    .hero-tag::before {
        width: 20px;
    }

    .hero-content h1 {
        font-size: 43px;
        line-height: 1.08;
        letter-spacing: -1px;
    }
    .hero-content p {
        max-width: 390px;
        margin: 20px 0 26px;

        font-size: 16px !important;
        line-height: 1.6;
    }
    .hero-buttons {
        gap: 10px;
        flex-wrap: wrap;
    }
    .hero-btn {
        min-width: 125px;
        height: 45px;
        padding: 0 17px;
        font-size: 15px;
        border-radius: 5px;
    }
    .hero-arrow {
        width: 38px;
        height: 38px;
        font-size: 14px;
    }
    .hero-prev {
        left: 12px;
    }
    .hero-next {
        right: 12px;
    }
    .hero-dots {
        bottom: 22px;
        gap: 7px;
    }

    .hero-dot {
        width: 7px;
        height: 7px;
    }
    .hero-dot.active {
        width: 25px;
    }
}
@media (max-width: 576px) {
    .skincare-hero {
        height: 72vh;
        min-height: 520px;
        max-height: 620px;
    }
    .hero-slide {
        background-position: center center;
    }
    .hero-content {
        padding: 0 42px;
    }
    .hero-tag {
        font-size: 10px;
        letter-spacing: 1.7px;
        margin-bottom: 13px;
        padding-left: 27px;
    }
    .hero-tag::before {
        width: 18px;
    }
    .hero-content h1 {
        font-size: 37px;
        line-height: 1.08;
        letter-spacing: -0.8px;
    }
    .hero-content p {
        max-width: 330px;
        margin: 17px 0 23px;

        font-size: 14px !important;
        line-height: 1.55;
    }
    .hero-buttons {
        gap: 9px;
    }
    .hero-btn {
        min-width: 115px;
        height: 43px;
        padding: 0 14px;
        font-size: 14px;
        gap: 7px;
    }
    .hero-arrow {
        width: 34px;
        height: 34px;
        font-size: 12px;
        background: rgba(255, 255, 255, 0.12);
    }
    .hero-prev {
        left: 9px;
    }
    .hero-next {
        right: 9px;
    }
    .hero-dots {
        bottom: 18px;
    }
}

@media (max-width: 380px) {
    .skincare-hero {
        height: 70vh;
        min-height: 490px;
        max-height: 570px;
    }
    .hero-content {
        padding: 0 35px;
    }
    .hero-tag {
        font-size: 9px;
        letter-spacing: 1.4px;
        padding-left: 24px;
    }
    .hero-tag::before {
        width: 16px;
    }
    .hero-content h1 {
        font-size: 32px;
        letter-spacing: -0.5px;
    }
    .hero-content p {
        max-width: 285px;
        font-size: 13px !important;
        line-height: 1.5;
        margin: 15px 0 20px;
    }
    .hero-buttons {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .hero-btn {
        min-width: 125px;
        height: 40px;
        font-size: 13px;
    }
    .hero-arrow {
        width: 30px;
        height: 30px;
        font-size: 11px;
    }
    .hero-prev {
        left: 7px;
    }
    .hero-next {
        right: 7px;
    }
    .hero-dots {
        bottom: 15px;
    }
}




.why-shop-section {
    margin-top: 80px;
    padding: 90px 30px;
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
    font-size: 19px;
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
    top: 40px;
    right: 0;
    width: 1px;
    height: calc(100% - 80px);
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
    font-size: 17px;
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



.about-promise {
    background-color: white;
    max-width: 850px;
    margin: auto;
    padding: 135px 30px;
    text-align: center;
}
.about-promise h2 {
    margin: 18px 0 22px;
    font-size: clamp(43px, 5vw, 65px);
    line-height: 1.04;
    letter-spacing: -2.5px;
}
.about-promise p {
    max-width: 650px;
    margin: 0 auto 35px;
    color: #777;
    line-height: 1.9;
    font-size: 16px;
}

</style>



<section class="skincare-hero">
    <?php foreach ($slides as $index => $slide): ?> 
    <div 
        class="hero-slide <?= $index === 0 ? 'active' : ''; ?>" 
        style="background-image: url('images/<?= htmlspecialchars($slide['image'], ENT_QUOTES, 'UTF-8'); ?>');"
    >

        <div class="hero-overlay"></div>
        <div class="hero-content <?= $index === 1 ? 'hero-content-right' : ''; ?>">

            <span class="hero-tag">
                <?= htmlspecialchars($slide['tag'], ENT_QUOTES, 'UTF-8'); ?>
            </span>

            <h1>
                <?= htmlspecialchars($slide['title_line1'], ENT_QUOTES, 'UTF-8'); ?>
                <?php if (!empty($slide['title_line2'])): ?>
                    <br>
                    <span>
                        <?= htmlspecialchars($slide['title_line2'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endif; ?>
            </h1>

            <p>
                <?= htmlspecialchars($slide['description'], ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <div class="hero-buttons">

                <a
                    href="<?= htmlspecialchars($slide['primary_button_link'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="hero-btn hero-btn-primary"
                >
                    <?= htmlspecialchars($slide['primary_button_text'], ENT_QUOTES, 'UTF-8'); ?>
                    <i class="bi bi-arrow-right"></i>
                </a>

                <a
                    href="<?= htmlspecialchars($slide['secondary_button_link'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="hero-btn hero-btn-outline"
                >
                    <?= htmlspecialchars($slide['secondary_button_text'], ENT_QUOTES, 'UTF-8'); ?>
                </a>

            </div>

        </div>
    </div>
<?php endforeach; ?>



    <?php if (count($slides) > 1): ?>
        <button
            class="hero-arrow hero-prev"
            aria-label="Previous slide"
        >
            <i class="bi bi-chevron-left"></i>
        </button>

        <button
            class="hero-arrow hero-next"
            aria-label="Next slide"
        >
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="hero-dots">
            <?php foreach ($slides as $index => $slide): ?>
                <button
                    class="hero-dot <?= $index === 0 ? 'active' : ''; ?>"
                    data-slide="<?= $index; ?>"
                    aria-label="Go to slide <?= $index + 1; ?>"
                ></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>



<!-- <section class="categories-menu">
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
</section> -->



<section class="shop-by-category">
    <h2 class="category-title">Shop By Category</h2>
    
   <div class="categories-grid">
        <?php foreach($categories as $category): ?>
            <a href="categories.php?id=<?= $category['id']; ?>" class="category-card">
                <div class="category-image-wrapper">
                    <img src="images/categories/category-<?= $category['id']; ?>.png" alt="<?= htmlspecialchars($category['name']); ?>">
                </div>
                <h3 class="category-name"><?= htmlspecialchars($category['name']); ?></h3>
                <span class="category-count"><?= $category['total_products']; ?> Items</span>
            </a>
        <?php endforeach; ?>

        <a href="saleproducts.php" class="category-card sale-card">
            <div class="category-image-wrapper">
                <img src="images/sale1.png" alt="Sale Products">
            </div>
            <h3 class="category-name">Sale Products</h3>
            <span class="category-count">Special Offers</span>
        </a>
    </div>
</section>





<section class="newest-products-carousel">
    <div class="newest-carousel-header">
        <div class="newest-carousel-title-box">
            <span class="newest-carousel-subtitle">
                JUST ARRIVED
            </span>

            <h2 class="newest-carousel-title">
                Newest Products
            </h2>
        </div>

        <a href="product.php" class="newest-view-all">
            VIEW ALL
        </a>

        
         <div class="newest-carousel-controls">

        <button
            type="button"
            class="newest-carousel-btn"
            id="newestPrev"
            aria-label="Previous products"
        >
            <i class="bi bi-arrow-left"></i>
        </button>

        <button
            type="button"
            class="newest-carousel-btn"
            id="newestNext"
            aria-label="Next products"
        >
            <i class="bi bi-arrow-right"></i>
        </button>
    </div>
    </div>

    <div class="newest-carousel-track">
        <?php foreach ($products as $product): ?>

            <?php
                $hasSale = !empty($product['sale_price']) &&
                           $product['sale_price'] < $product['price'];

                $currentPrice = $hasSale
                    ? $product['sale_price']
                    : $product['price'];
            ?>

            <article class="newest-carousel-item">
                <span class="newest-product-badge">
                    NEW
                </span>

                <a
                    href="productdetails.php?id=<?= (int)$product['id']; ?>"
                    class="newest-product-image-link"
                >
                    <div class="newest-carousel-image">

                          <span class="newest-product-badge">
                                NEW
                          </span>
                        <img
                            src="images/<?= htmlspecialchars(
                                $product['image'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $product['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                        >

                    </div>
                </a>

                <div class="newest-carousel-info">
                    <a
                        href="productdetails.php?id=<?= (int)$product['id']; ?>"
                        class="newest-carousel-name-link"
                    >
                        <h3 class="newest-carousel-name">
                            <?= htmlspecialchars(
                                $product['name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>
                        </h3>
                    </a>


                    <!-- <div class="newest-carousel-price">

                        <span class="newest-carousel-current-price">
                            $<?= number_format(
                                (float)$currentPrice,
                                2
                            ); ?>
                        </span>

                        <?php if ($hasSale): ?>

                            <span class="newest-carousel-old-price">
                                $<?= number_format(
                                    (float)$product['price'],
                                    2
                                ); ?>
                            </span>

                        <?php endif; ?>

                    </div> -->
                </div>

                <div class="newest-product-hover-footer">
                    <form
                        action="cart.php"
                        method="GET"
                        class="newest-add-cart-form"
                    >

                        <input
                            type="hidden"
                            name="action"
                            value="add"
                        >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int)$product['id']; ?>"
                        >

                        <button
                            type="submit"
                            class="newest-add-cart-btn"
                        >
                            <i class="bi bi-bag-plus"></i>
                            <span>ADD TO CART</span>
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>








<section class="beauty-banner">
    <div class="beauty-banner-image">
        <img src="images/products.png"
             alt="Woman applying skincare">

        <div class="image-badge">
            <i class="bi bi-stars"></i>
            <span>BEAUTY ESSENTIALS</span>
        </div>
    </div>

    <div class="beauty-banner-content">
        <span class="beauty-banner-label">
            ELEVATE YOUR ROUTINE
        </span>
        <h2>
            Your skin
            <span>deserves the best.</span>
        </h2>
        <p>
            Thoughtfully selected skincare essentials
            for your everyday glow.
        </p>
        <a href="beauty-quiz.php" class="beauty-banner-btn">
            <span>Discover Skincare</span>
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>



<section class="tips-section">
    <h2 class="section-title">
        EXPERT TIPS AND INSPIRATION
    </h2>

    <div class="cards-container">
        <?php foreach ($blogs as $blog): ?>
            <div
                class="tip-card"
                onclick="window.location.href='blogdetails.php?id=<?= (int)$blog['id']; ?>'"
            >
                <div class="card-category">
                    <!-- CARE -->
                </div>

                <div class="card-image">
                    <?php if (!empty($blog['main_image'])): ?>
                        <img
                            src="images/<?= htmlspecialchars(
                                $blog['main_image'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $blog['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                        >
                    <?php endif; ?>
                </div>


                <div class="card-body">
                    <h3 class="card-title">
                        <?= htmlspecialchars(
                            $blog['title'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>
                    </h3>

                    <div class="card-footer">
                        <span class="meta-item">
                            <i class="far fa-clock"></i>
                            <?= date(
                                'M d, Y',
                                strtotime($blog['published_at'])
                            ); ?>
                        </span>
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
    <!-- <p class="brands-label">Brands We Carry</p> -->
    <div class="brands-track">
        <div class="brand-item">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Dior%20Logo%202022.svg" alt="Dior">
        </div>
        <div class="brand-item">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Chanel%20logo.svg" alt="Chanel">
        </div>
        <div class="brand-item">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/L'Or%C3%A9al%20logo.svg" alt="L'Oréal">
        </div>
        <div class="brand-item">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Logo%20Clinique.jpg" alt="Clinique">
        </div>
        <div class="brand-item">
            <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Est%C3%A9e%20Lauder%20Companies%20Logo.svg" alt="Estée Lauder">
        </div>
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

if (newsletterPopup && closeNewsletterPopup) {

    if (sessionStorage.getItem("newsletterShown") !== "true") {

        setTimeout(function () {
            newsletterPopup.classList.add("show");
            sessionStorage.setItem("newsletterShown", "true");
        }, 7000);
    }

    closeNewsletterPopup.addEventListener("click", function () {
        newsletterPopup.classList.remove("show");
    });

    newsletterPopup.addEventListener("click", function (event) {
        if (event.target === newsletterPopup) {
            newsletterPopup.classList.remove("show");
        }
    });
}




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
    const hero = document.querySelector(".skincare-hero");

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
    nextButton.addEventListener("click", function () {
        nextSlide();
        restartAutoSlide();
    });
    prevButton.addEventListener("click", function () {
        previousSlide();
        restartAutoSlide();
    });

    dots.forEach((dot) => {

        dot.addEventListener("click", function () {
            const slideNumber =
                Number(this.dataset.slide);
            showSlide(slideNumber);
            restartAutoSlide();
        });

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

    hero.addEventListener("mouseenter", function () {
        clearInterval(autoSlide);
    });

    hero.addEventListener("mouseleave", function () {
        startAutoSlide();
    });

    let isDragging = false;
    let startX = 0;
    let currentX = 0;
    let dragDistance = 0;

    hero.addEventListener("mousedown", function (e) {
        if ( e.target.closest("button") ||  e.target.closest("a")) {
            return;
        }

        isDragging = true;

        startX = e.clientX;
        currentX = e.clientX;

        hero.classList.add("is-dragging");
        clearInterval(autoSlide);

        e.preventDefault();
    });

    hero.addEventListener("mousemove", function (e) {

        if (!isDragging) return;
        currentX = e.clientX;
        dragDistance = currentX - startX;
        const current = slides[currentSlide];
        current.style.transition = "none";
        current.style.transform =
            `translateX(${dragDistance}px) scale(1)`;

    });

    hero.addEventListener("mouseup", function () {
        if (!isDragging) return;
        isDragging = false;

        hero.classList.remove("is-dragging");
        const current = slides[currentSlide];
        current.style.transition =
            "transform 0.5s ease, opacity 0.5s ease";
        if (Math.abs(dragDistance) > 100) {

            if (dragDistance < 0) {
                nextSlide();
            } else {
                previousSlide();
            }
        } else {
            current.style.transform =
                "translateX(0) scale(1)";
        }

        dragDistance = 0;
        restartAutoSlide();
    });

    hero.addEventListener("mouseleave", function () {
        if (!isDragging) return;

        isDragging = false;
        hero.classList.remove("is-dragging");

        const current = slides[currentSlide];
        current.style.transition =
            "transform 0.5s ease";
        current.style.transform =
            "translateX(0) scale(1)";
        dragDistance = 0;
        startAutoSlide();
    });

    showSlide(0);
    startAutoSlide();

});














// document.addEventListener('DOMContentLoaded', () => {
//   const track = document.getElementById('carouselTrack');
//   const prevBtn = document.getElementById('prevBtn');
//   const nextBtn = document.getElementById('nextBtn');
//   const cards = document.querySelectorAll('.product-card');

//   let currentIndex = 0;

//   function getCardsPerView() {
//     if (window.innerWidth <= 600) return 1;
//     if (window.innerWidth <= 900) return 2;
//     return 3;
//   }

//   function updateCarousel() {
//     const cardsPerView = getCardsPerView();
//     const maxIndex = cards.length - cardsPerView;
    
//     if (currentIndex < 0) currentIndex = 0;
//     if (currentIndex > maxIndex) currentIndex = maxIndex;

//     const cardWidth = cards[0].offsetWidth + 24; 
//     track.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
//   }

//   nextBtn.addEventListener('click', () => {
//     const cardsPerView = getCardsPerView();
//     if (currentIndex < cards.length - cardsPerView) {
//       currentIndex++;
//       updateCarousel();
//     }
//   });

//   prevBtn.addEventListener('click', () => {
//     if (currentIndex > 0) {
//       currentIndex--;
//       updateCarousel();
//     }
//   });

//   window.addEventListener('resize', updateCarousel);
// });



document.addEventListener("DOMContentLoaded", function () {

    const track = document.querySelector(".newest-carousel-track");
    const prevBtn = document.getElementById("newestPrev");
    const nextBtn = document.getElementById("newestNext");

    if (!track || !prevBtn || !nextBtn) {
        return;
    }

    let currentIndex = 0;

    function getVisibleProducts() {
        if (window.innerWidth <= 640) return 1;
        if (window.innerWidth <= 900) return 2;
        if (window.innerWidth <= 1200) return 3;
        return 4;
    }

    function updateCarousel() {
        const products = track.querySelectorAll(".newest-carousel-item");

        if (products.length === 0) return;

        const visibleProducts = getVisibleProducts();
        const totalProducts = products.length;
        const maxIndex = Math.max(0, totalProducts - visibleProducts);

        if (currentIndex > maxIndex) {
            currentIndex = maxIndex;
        }

        const productWidth = products[0].getBoundingClientRect().width;
        const gap = parseFloat(getComputedStyle(track).gap) || 0;
        const moveAmount = currentIndex * (productWidth + gap);

        track.style.transform = `translateX(-${moveAmount}px)`;

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= maxIndex;

        prevBtn.style.opacity = currentIndex === 0 ? "0.45" : "1";
        nextBtn.style.opacity = currentIndex >= maxIndex ? "0.45" : "1";
    }

    nextBtn.addEventListener("click", function () {
        const products = track.querySelectorAll(".newest-carousel-item");
        const visibleProducts = getVisibleProducts();
        const maxIndex = Math.max(0, products.length - visibleProducts);

        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        }
    });

    prevBtn.addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    window.addEventListener("resize", updateCarousel);
    updateCarousel();
});
</script>