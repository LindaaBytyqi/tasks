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
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">

                            <div class="product-image-wrap">

                                <div class="product-tags">
                                    <?php if (!empty($product['is_new'])): ?>
                                        <span class="tag tag-new">NEW</span>
                                    <?php endif; ?>

                                    <?php if (!empty($product['discount_percent'])): ?>
                                        <span class="tag tag-sale">-<?= (int) $product['discount_percent']; ?>%</span>
                                    <?php endif; ?>
                                </div>

                                <button type="button" class="wishlist-btn" aria-label="Shto te të preferuarat">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 21s-7.5-4.6-10-9.1C.5 8.4 2.3 5 5.8 5c2 0 3.4 1 4.2 2.3C10.8 6 12.2 5 14.2 5c3.5 0 5.3 3.4 3.8 6.9-2.5 4.5-6 9.1-6 9.1z"/>
                                    </svg>
                                </button>

                                <a href="productdetails.php?id=<?= $product['id']; ?>" class="product-image-link">
                                    <img
                                        src="images/<?= htmlspecialchars($product['image']); ?>"
                                        alt="<?= htmlspecialchars($product['name']); ?>"
                                        loading="lazy"
                                    >
                                </a>

                                <div class="quick-add">
                                    <?php if ($product['stock'] > 0): ?>
                                        <a href="cart.php?action=add&id=<?= $product['id']; ?>" class="quick-add-btn">
                                            Add Cart
                                        </a>
                                    <?php else: ?>
                                        <span class="quick-add-btn disabled">Out of Stock</span>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <div class="product-info">
                                <?php if (!empty($product['category_name'])): ?>
                                    <span class="product-category"><?= htmlspecialchars($product['category_name']); ?></span>
                                <?php endif; ?>

                                <a href="productdetails.php?id=<?= $product['id']; ?>" class="product-name-link">
                                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                                </a>

                                <div class="product-bottom-row">
                                    <div class="price">
                                        <?php if ($product['sale_price'] !== null && $product['sale_price'] < $product['price']): ?>
                                            <span class="sale-price">$<?= number_format($product['sale_price'], 2); ?></span>
                                            <span class="old-price">$<?= number_format($product['price'], 2); ?></span>
                                        <?php else: ?>
                                            <span class="regular-price">$<?= number_format($product['price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- <span class="stock-dot <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>"
                                          title="<?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>"></span> -->
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-products">No products found.</p>
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