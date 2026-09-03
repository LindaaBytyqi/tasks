<?php
include "includes/header.php";
include "includes/database.php";

$sort = $_GET['sort'] ?? "newest";
$min_price = $_GET['min_price'] ?? "";
$max_price = $_GET['max_price'] ?? "";
$category_id  = $_GET['category'] ?? "";     
$stock_status = $_GET['stock'] ?? "";

$max_price_sql = "SELECT MAX(COALESCE(sale_price, price)) as highest_price FROM products";
$max_price_stmt = $conn->prepare($max_price_sql);
$max_price_stmt->execute();
$max_price_row = $max_price_stmt->fetch(PDO::FETCH_ASSOC);
$db_max_price = !empty($max_price_row['highest_price']) ? ceil($max_price_row['highest_price']) : 500;

$cat_sql = "SELECT c.*, COUNT(p.id) AS total_products 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id";
$cat_stmt = $conn->prepare($cat_sql);
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($category_id)) {
    $sql .= " AND category_id = :category_id";
    $params["category_id"] = $category_id;
}

if ($stock_status === "instock") {
    $sql .= " AND stock > 0";
} elseif ($stock_status === "outofstock") {
    $sql .= " AND stock <= 0";
}

if ($min_price !== "") {

    $sql .= " AND COALESCE(sale_price, price) >= :min_price";
    $params["min_price"] = $min_price;
}
if ($max_price !== "") {

    $sql .= " AND COALESCE(sale_price, price) <= :max_price";
    $params["max_price"] = $max_price;
}
switch ($sort) {
    case "low":
        $sql .= " ORDER BY COALESCE(sale_price, price) ASC";
        break;
    case "high":
        $sql .= " ORDER BY COALESCE(sale_price, price) DESC";
        break;
    case "az":
        $sql .= " ORDER BY name ASC";
        break;
    case "za":
        $sql .= " ORDER BY name DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<section class="products-section">
    <div class="shop-layout">
            <div class="filter-sidebar">
        
            <div class="sidebar-widget">
                <h3 class="widget-title">Categories</h3>
                <ul class="sidebar-categories-list">
                    <li>
                        <a href="product.php" class="<?= empty($category_id) ? 'active' : '' ?>">
                            All Categories
                        </a>
                    </li>
                    <?php foreach($categories as $category): ?>
                        <li>
                            <a href="product.php?category=<?= $category['id'];
                            ?>&sort=<?= urlencode($sort); 
                            ?>&min_price=<?= urlencode($min_price); 
                            ?>&max_price=<?= urlencode($max_price); 
                            ?>&stock=<?= urlencode($stock_status); ?>" 
                            class="<?= $category_id == $category['id'] ? 'active' : ''; ?>">
                                <?= htmlspecialchars($category['name']); ?>
                                 <span>(<?= $category['total_products']; ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <form method="GET" action="product.php" class="filter-form">

                <?php if(!empty($category_id)): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category_id); ?>">
                <?php endif; ?>

                <div class="sidebar-widget">
    <label class="widget-title" for="sort-select">Sort By</label>
    <select name="sort" id="sort-select" class="styled-select" onchange="this.form.submit()">
        <option value="newest" <?= $sort === "newest" ? "selected" : ""; ?>>Newest</option>
        <option value="low" <?= $sort === "low" ? "selected" : ""; ?>>Price Low to High</option>
        <option value="high" <?= $sort === "high" ? "selected" : ""; ?>>Price High to Low</option>
        <option value="az" <?= $sort === "az" ? "selected" : ""; ?>>Alphabetically (A-Z)</option>
        <option value="za" <?= $sort === "za" ? "selected" : ""; ?>>Alphabetically (Z-A)</option>
    </select>
</div>

<div class="sidebar-widget">
    <label class="widget-title" for="stock-select">Availability</label>
    <select name="stock" id="stock-select" class="styled-select" onchange="this.form.submit()">
        <option value="">All Products</option>
        <option value="instock" <?= $stock_status === "instock" ? "selected" : ""; ?>>In Stock</option>
        <option value="outofstock" <?= $stock_status === "outofstock" ? "selected" : ""; ?>>Out of Stock</option>
    </select>
</div>

                <div class="sidebar-widget">
                    <h3 class="widget-title">Filter</h3>
                    
        <div class="range-slider-wrapper">
            <div class="slider-track"></div>
            <input type="range" id="range-min" min="0" max="<?= $db_max_price; ?>" value="<?= $min_price !== '' ? htmlspecialchars($min_price) : '0'; ?>" step="1">
            <input type="range" id="range-max" min="0" max="<?= $db_max_price; ?>" value="<?= $max_price !== '' ? htmlspecialchars($max_price) : $db_max_price; ?>" step="1">
        </div>

            <input type="hidden" name="min_price" id="min_price_input" value="<?= htmlspecialchars($min_price); ?>">
            <input type="hidden" name="max_price" id="max_price_input" value="<?= htmlspecialchars($max_price); ?>">

            <div class="price-range-text">
                Price: $<span id="min-price-display">0</span> &mdash; $<span id="max-price-display"><?= $db_max_price; ?></span>
            </div>

                    <button type="submit" class="btn-filter-blue">
                        Filter <span class="arrow">&rarr;</span>
                    </button>
                </div>

            </form>
        </div>

        <div class="products-container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
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

                                    $<?= number_format(
                                        $product['sale_price'],
                                        2
                                    ); ?>

                                </span>
                                <span class="old-price">

                                    $<?= number_format(
                                        $product['price'],
                                        2
                                    ); ?>

                                </span>
                            <?php else: ?>
                                <span class="regular-price">

                                    $<?= number_format(
                                        $product['price'],
                                        2
                                    ); ?>

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
            <?php else: ?>
                <p>
                    No products found.
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
const minRange = document.getElementById('range-min');
const maxRange = document.getElementById('range-max');
const minDisplay = document.getElementById('min-price-display');
const maxDisplay = document.getElementById('max-price-display');
const minInput = document.getElementById('min_price_input');
const maxInput = document.getElementById('max_price_input');
const track = document.querySelector('.slider-track');
const maxLimit = parseInt(maxRange.max);
function updateSlider() {
    let minVal = parseInt(minRange.value);
    let maxVal = parseInt(maxRange.value);
    const maxLimit = parseInt(minRange.max) || 1; 

    if (minVal >= maxVal) {
        minVal = maxVal - 1;
        if (minVal < 0) minVal = 0;
        minRange.value = minVal;
    }

    minDisplay.textContent = minVal;
    maxDisplay.textContent = maxVal;
    minInput.value = minVal;
    maxInput.value = maxVal;

    const percentMin = (minVal / maxLimit) * 100;
    const percentMax = (maxVal / maxLimit) * 100;

    track.style.left = percentMin + "%";
    track.style.width = (percentMax - percentMin) + "%";
}
minRange.addEventListener('input', updateSlider);
maxRange.addEventListener('input', updateSlider);

updateSlider();
</script>

<?php
include "includes/footer.php";
?>