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

    <img src="images/<?= htmlspecialchars($product['image']); ?>"
         alt="<?= htmlspecialchars($product['name']); ?>">

    <h3>
        <?= htmlspecialchars($product['name']); ?>
    </h3>

    <p>
        <?= htmlspecialchars($product['description']); ?>
    </p>

    <div class="price">
        $<?= number_format($product['price'],2); ?>
    </div>

    <div class="buttons">
        <a href="product-details.php?id=<?= $product['id']; ?>">
            View Product
        </a>

        <button>
            Add to Cart
        </button>
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