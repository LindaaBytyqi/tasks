
<?php
include "includes/header.php";
include "includes/database.php";
if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}
$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    "id" => $product_id
]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo "Product not found";
    exit;
}
?>

<style>
.product-details-main {
    /* padding: 30px 0 55px;
    background: #fff;
    margin-top: 50px; */
   padding: 40px 0;   
    margin-top: 0;    
    background: transparent;

}
.product-details-box {
    width: 85%; 
    max-width: 1550px;
    min-height: 670px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
    padding: 40px 55px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 100px;
    align-items: center;
}
.product-image-box {
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-image-box img {
    width: 420px; 
    height: 520px;
    object-fit: contain;
    border: 2px solid black; 
    padding: 10px; 
    border-radius: 0px;
}
.product-info-box {
    padding-right: 30px;
}
.product-title {
    font-size: 35px; 
    font-weight: 700;
    color: #111;
    margin: 0 0 15px;
}
.product-description {
    font-size: 19px; 
    color: #777;
    line-height: 1.6;
    margin-bottom: 25px;
    max-width: 500px; 
}
.product-price {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}
.sale-price,
.normal-price {
    font-size: 22px; 
    font-weight: 600;
    color: #eb3f81;
}
.old-price {
    font-size: 16px; 
    color: #888;
}
.stock-status {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 16px; 
    margin-bottom: 30px;
}
.in-stock {
    color: #159570;
}
.out-stock {
    color: #dc3545;
}
.quantity-section {
    margin-bottom: 25px;
}
.quantity-section label {
    display: block;
    font-size: 19px; 
    color: #333;
    margin-bottom: 10px;
}
.quantity-box {
    display: flex;
    align-items: center;
    width: 100px; 
    height: 38px; 
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    background: #fff;
}
.quantity-btn {
    width: 35px; 
    height: 100%;
    border: none;
    background: #fff;
    font-size: 20px; 
    color: #333;
    cursor: pointer;
    transition: 0.2s;
}
.quantity-btn:hover {
    background: #f7f7f7;
}
.quantity-box input {
    width: 38px; 
    height: 100%;
    border: none;
    outline: none;
    text-align: center;
    font-size: 19px; 
    color: #222;
}
.quantity-box input::-webkit-inner-spin-button,
.quantity-box input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.add-cart-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: none;
    background: #eb3f81;
    color: white;
    padding: 12px 25px; 
    border-radius: 25px;
    font-size: 16px; 
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
.add-cart-btn:hover {
    background: #d92f70;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(235, 63, 129, 0.20);
}
.add-cart-btn:active {
    background: #eb3f81;
    transform: translateY(0);
}



@media (min-width: 993px) and (max-width: 1200px) {

    .product-details-main {
        margin-top: 35px;
        padding: 25px 0 45px;
    }

    .product-details-box {
        width: 92%;
        min-height: 600px;
        padding: 35px 35px;
        gap: 45px;
    }

    .product-image-box img {
        width: 350px;
        height: 450px;
    }

    .product-info-box {
        padding-right: 10px;
    }

    .product-title {
        font-size: 31px;
    }

    .product-description {
        font-size: 17px;
    }
}

@media (min-width: 769px) and (max-width: 992px) {

    .product-details-main {
        margin-top: 30px;
        padding: 20px 0 40px;
    }

    .product-details-box {
        width: 94%;
        min-height: auto;
        padding: 35px 30px;
        grid-template-columns: 1fr 1fr;
        gap: 35px;
    }

    .product-image-box img {
        width: 100%;
        max-width: 330px;
        height: 420px;
    }

    .product-info-box {
        padding-right: 0;
    }

    .product-title {
        font-size: 28px;
    }

    .product-description {
        font-size: 17px;
        line-height: 1.5;
    }

    .sale-price,
    .normal-price {
        font-size: 21px;
    }

    .stock-status {
        font-size: 15px;
    }

    .quantity-section label {
        font-size: 17px;
    }
}
@media (min-width: 481px) and (max-width: 768px) {

    .product-details-main {
        margin-top: 20px;
        padding: 15px 0 35px;
    }

    .product-details-box {
        width: 94%;
        min-height: auto;
        padding: 25px 20px 35px;
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .product-image-box {
        width: 100%;
    }

    .product-image-box img {
        width: 100%;
        max-width: 330px;
        height: 400px;
    }

    .product-info-box {
        padding-right: 0;
    }

    .product-title {
        font-size: 27px;
        margin-bottom: 12px;
    }

    .product-description {
        font-size: 17px;
        max-width: 100%;
        line-height: 1.5;
    }

    .product-price {
        margin-bottom: 15px;
    }

    .sale-price,
    .normal-price {
        font-size: 21px;
    }

    .stock-status {
        font-size: 15px;
        margin-bottom: 25px;
    }

    .quantity-section label {
        font-size: 17px;
    }

    .add-cart-btn {
        padding: 11px 22px;
    }
}

@media (min-width: 380px) and (max-width: 480px) {
    .product-details-main {
        margin-top: 15px;
        padding: 10px 0 30px;
    }
    .product-details-box {
        width: 94%;
        min-height: auto;
        padding: 20px 16px 30px;
        grid-template-columns: 1fr;
        gap: 25px;
        border-radius: 10px;
    }
    .product-image-box img {
        width: 100%;
        max-width: 280px;
        height: 350px;
    }
    .product-info-box {
        padding-right: 0;
    }
    .product-title {
        font-size: 24px;
        line-height: 1.3;
        margin-bottom: 12px;
    }
    .product-description {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .product-price {
        gap: 10px;
        margin-bottom: 15px;
    }
    .sale-price,
    .normal-price {
        font-size: 20px;
    }
    .old-price {
        font-size: 15px;
    }
    .stock-status {
        font-size: 14px;
        margin-bottom: 22px;
    }
    .quantity-section label {
        font-size: 16px;
    }
    .add-cart-btn {
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
    }
}
@media (max-width: 379px) {
    .product-details-main {
        margin-top: 10px;
        padding: 10px 0 25px;
    }

    .product-details-box {
        width: 95%;
        min-height: auto;
        padding: 18px 12px 25px;
        grid-template-columns: 1fr;
        gap: 22px;
        border-radius: 8px;
    }
    .product-image-box img {
        width: 100%;
        max-width: 240px;
        height: 300px;
    }
    .product-info-box {
        padding-right: 0;
    }
    .product-title {
        font-size: 22px;
        line-height: 1.3;
        margin-bottom: 10px;
    }
    .product-description {
        font-size: 15px;
        line-height: 1.5;
        margin-bottom: 18px;
    }
    .product-price {
        gap: 8px;
    }
    .sale-price,
    .normal-price {
        font-size: 19px;
    }
    .old-price {
        font-size: 14px;
    }
    .stock-status {
        font-size: 13px;
        margin-bottom: 20px;
    }
    .quantity-section label {
        font-size: 15px;
    }
    .add-cart-btn {
        width: 100%;
        justify-content: center;
        font-size: 15px;
        padding: 11px 15px;
    }
}
</style>
<main class="product-details-main">
    <div class="product-details-box">
        <div class="product-image-box">
            <img
                src="images/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>"
            >
        </div>
        <div class="product-info-box">
            <h1 class="product-title">
                <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p class="product-description">
                <?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?>
            </p>
            <div class="product-price">

                <?php if (!empty($product['sale_price'])): ?>

                    <span class="sale-price">
                        $<?= number_format($product['sale_price'], 2); ?>
                    </span>

                    <del class="old-price">
                        $<?= number_format($product['price'], 2); ?>
                    </del>
                <?php else: ?>
                    <span class="normal-price">
                        $<?= number_format($product['price'], 2); ?>
                    </span>
                <?php endif; ?>

                </div>
            <div class="stock-status">

                <?php if ($product['stock'] > 0): ?>
                    <span class="in-stock">
                        <i class="bi bi-check-circle"></i>
                        In Stock
                        (<?=(int) $product['stock']; ?> available)
                    </span>

                <?php else: ?>
                    <span class="out-stock">
                        <i class="bi bi-x-circle"></i>
                        Out of Stock
                    </span>
                <?php endif; ?>

            </div>

            <?php if ($product['stock'] > 0): ?>
                <form action="cart.php" method="POST">
                    <input
                        type="hidden"
                        name="product_id"
                        value="<?= $product['id']; ?>"
                    >
                    <div class="quantity-section">
                        <label for="quantity">
                            Quantity
                        </label>
                        <div class="quantity-box">
                            <button
                                type="button"
                                class="quantity-btn"
                                onclick="decreaseQ()"
                            >
                                −
                            </button>

                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                value="1"
                                min="1"
                                max="<?= (int)$product['stock']; ?>"
                            >
                            <button
                                type="button"
                                class="quantity-btn"
                                onclick="increaseQ()"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <button
                        type="submit"
                        name="add_to_cart"
                        class="add-cart-btn"
                    >
                        <i class="bi bi-cart-plus"></i>
                        Add to Cart
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>


<?php include "includes/footer.php"; ?>
<script>
function increaseQ() {
    let quantity = document.getElementById("quantity");
    let max = parseInt(quantity.max);
    let current = parseInt(quantity.value);
    if (current < max) {
        quantity.value = current + 1;
    }
}

function decreaseQ() {
    let quantity = document.getElementById("quantity");
    let current = parseInt(quantity.value);
    if (current > 1) {
        quantity.value = current - 1;
    }
}
</script>
