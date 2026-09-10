<?php
include "includes/header.php";
include "includes/database.php";

$search = trim($_GET['query'] ?? '');

$sql = "SELECT *
        FROM products
        WHERE LOWER(name) LIKE LOWER(:search)
           OR LOWER(description) LIKE LOWER(:search)
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([
    "search" => "%$search%"
]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .products-section{
        margin-top: 130px;
    }
</style>
<section class="products-section">

<h2>
Search results for:
<?= htmlspecialchars($search); ?>
</h2>


<div class="products-container">
<?php if(empty($products)): ?>
    <h3>
        No products found.
    </h3>
<?php else: ?>

<?php foreach($products as $product): ?>
    <div class="product-card">

        <img
            src="images/<?= htmlspecialchars($product['image']); ?>"
            alt="<?= htmlspecialchars($product['name']); ?>"
        >
        <h3>
            <?= htmlspecialchars($product['name']); ?>
        </h3>

        <div class="price">
            <?php if (
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
        <?php if ($product['stock'] > 0): ?>

            <span class="stock">
                In Stock
            </span>

        <?php else: ?>

            <span class="stock">
                Out of Stock
            </span>

        <?php endif; ?>
        <div class="buttons">
            <a
                href="productdetails.php?id=<?= $product['id']; ?>"
            >
                View Product
            </a>
            <?php if ($product['stock'] > 0): ?>
                <a
                    href="cart.php?action=add&id=<?= $product['id']; ?>"
                    class="btn-add"
                >
                    Add to Cart
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>


<?php endif; ?>
</div>
</section>




<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        if (searchInput.value.length > 0) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
        let timeout = null;
        searchInput.addEventListener("input", function () {
            clearTimeout(timeout);
            const query = this.value.trim();
            if (query.length >= 3) {
                timeout = setTimeout(() => {
                    searchInput.form.submit();
                }, 600);
            }
        });
    }
});
</script>
<?php
include "includes/footer.php";
?>