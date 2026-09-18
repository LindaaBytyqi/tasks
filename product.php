<?php
include "includes/header.php";
include "includes/database.php";

$sort = $_GET['sort'] ?? "newest";
$min_price = $_GET['min_price'] ?? "";
$max_price = $_GET['max_price'] ?? "";
$category_id = $_GET['category'] ?? "";
$stock_status = $_GET['stock'] ?? "";

$max_price_sql = "SELECT MAX(COALESCE(sale_price, price)) as highest_price FROM products";
$max_price_stmt = $conn->prepare($max_price_sql);
$max_price_stmt->execute();
$max_price_row = $max_price_stmt->fetch(PDO::FETCH_ASSOC);

$db_max_price = !empty($max_price_row['highest_price'])
    ? ceil($max_price_row['highest_price'])
    : 500;

$cat_sql = "SELECT c.*, COUNT(p.id) AS total_products
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id";
$cat_stmt = $conn->prepare($cat_sql);
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$products_per_page = 9;

$page = isset($_GET['page']) && is_numeric($_GET['page'])
    ? (int) $_GET['page']
    : 1;
$page = max($page, 1);
$offset = ($page - 1) * $products_per_page;

$count_sql = "SELECT COUNT(*) FROM products p WHERE p.status IN ('1', 'active')";
$count_params = [];

if (!empty($category_id)) {
    $count_sql .= " AND p.category_id = :category_id";
    $count_params["category_id"] = $category_id;
}
if ($stock_status === "instock") {
    $count_sql .= " AND p.stock > 0";
} elseif ($stock_status === "outofstock") {
    $count_sql .= " AND p.stock <= 0";
}
if ($min_price !== "") {
    $count_sql .= " AND COALESCE(p.sale_price, p.price) >= :min_price";
    $count_params["min_price"] = $min_price;
}
if ($max_price !== "") {
    $count_sql .= " AND COALESCE(p.sale_price, p.price) <= :max_price";
    $count_params["max_price"] = $max_price;
}

$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute($count_params);
$total_products = (int) $count_stmt->fetchColumn();
$total_pages = (int) ceil($total_products / $products_per_page);

$sql = "
    SELECT
        p.*,
        c.name AS category_name,
        CASE
            WHEN p.sale_price IS NOT NULL AND p.sale_price < p.price
            THEN ROUND((100 - (p.sale_price / p.price * 100))::numeric)
            ELSE NULL
        END AS discount_percent,
        (p.created_at >= NOW() - INTERVAL '14 days') AS is_new
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.status IN ('1', 'active')
";
$params = [];

if (!empty($category_id)) {
    $sql .= " AND p.category_id = :category_id";
    $params["category_id"] = $category_id;
}
if ($stock_status === "instock") {
    $sql .= " AND p.stock > 0";
} elseif ($stock_status === "outofstock") {
    $sql .= " AND p.stock <= 0";
}
if ($min_price !== "") {
    $sql .= " AND COALESCE(p.sale_price, p.price) >= :min_price";
    $params["min_price"] = $min_price;
}
if ($max_price !== "") {
    $sql .= " AND COALESCE(p.sale_price, p.price) <= :max_price";
    $params["max_price"] = $max_price;
}

switch ($sort) {
    case "low":
        $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
        break;
    case "high":
        $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
        break;
    case "az":
        $sql .= " ORDER BY p.name ASC";
        break;
    case "za":
        $sql .= " ORDER BY p.name DESC";
        break;
    default:
        $sql .= " ORDER BY p.created_at DESC";
        break;
}

$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(":limit", $products_per_page, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>

.products-section{
    margin-top:190px;
    padding:0 40px 80px;
    padding: 70px 80px;
    width: 100%;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing:border-box;
    margin-bottom: 55px;
}
.shop-layout{
    display:flex;
    gap: 46px;
    width:100%;
    align-items:flex-start;
    max-width:1600px;
    margin:0 auto;
}

.filter-sidebar{
    width:340px;
    min-width:340px;
    background:#fff;
    border-radius:16px;
    padding:30px 25px;
    box-shadow:0 4px 20px rgba(0,0,0,.04);
    box-sizing:border-box;
    position:sticky;
    top:130px;
}
.sidebar-widget{
    margin-bottom:32px;
    padding-bottom:28px;
    border-bottom:1px solid #eee;
}
.sidebar-widget:last-child{
    margin-bottom:0;
    padding-bottom:0;
    border-bottom:none;
}
.widget-title{
    display:block;
    font-size:18px;
    font-weight:700;
    color:#1a1a1a;
    margin-bottom:14px;
}

.sidebar-categories-list{
    list-style:none;
    padding:0;
    margin:0;
}
.sidebar-categories-list li{
    margin-bottom:4px;
}
.sidebar-categories-list li a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    text-decoration:none;
    color:#555;
    font-size:17px;
    padding:9px 10px;
    border-radius:8px;
    transition:.2s ease;
}
.sidebar-categories-list li a span{
    color:#aaa;
    font-size:16px;
}
.sidebar-categories-list li a:hover{
    background:#fdf1f6;
    color:#eb3f81;
}
.sidebar-categories-list li a.active{
    background:#fdf1f6;
    color:#eb3f81;
    font-weight:600;
}
.sidebar-categories-list li a.active span{
    color:#eb3f81;
    opacity:.7;
}

.styled-select{
    width:100%;
    height:44px;
    padding:8px 14px;
    border:1px solid #e3e9ef;
    border-radius:8px;
    font-size:14px;
    color:#333;
    background-color:#fff;
    outline:none;
    cursor:pointer;
    box-sizing:border-box;
    transition:border-color .2s ease;
}
.styled-select:focus{
    border-color:#eb3f81;
}

.range-slider-wrapper{
    position:relative;
    height:4px;
    background:#eee;
    border-radius:4px;
    margin:20px 4px 14px;
}
.slider-track{
    position:absolute;
    height:4px;
    background:#eb3f81;
    border-radius:4px;
}
.range-slider-wrapper input[type="range"]{
    position:absolute;
    top:-8px;
    left:0;
    width:100%;
    height:20px;
    margin:0;
    background:transparent;
    pointer-events:none;
    -webkit-appearance:none;
    appearance:none;
}
.range-slider-wrapper input[type="range"]::-webkit-slider-thumb{
    pointer-events:all;
    -webkit-appearance:none;
    appearance:none;
    width:18px;
    height:18px;
    border-radius:50%;
    background:#fff;
    border:3px solid #eb3f81;
    cursor:pointer;
}
.range-slider-wrapper input[type="range"]::-moz-range-thumb{
    pointer-events:all;
    width:18px;
    height:18px;
    border-radius:50%;
    background:#fff;
    border:3px solid #eb3f81;
    cursor:pointer;
}
.price-range-text{
    font-size:14px;
    color:#555;
    margin-bottom:18px;
}

.btn-filter-blue{
    width:100%;
    background: #e2e0e0 !important;
    color: #0a0a0a !important;
    border:none;
    border-radius:8px;
    padding:13px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    transition:.25s ease;
}
.btn-filter-blue:hover{
    background:#fffefe;
}
.btn-filter-blue .arrow{
    transition:transform .25s ease;
}
.btn-filter-blue:hover .arrow{
    transform:translateX(4px);
}

.products-area{
    flex:1;
    min-width:0;
    margin-bottom: 45px;
}
.products-container{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    /* gap:40px 3px; */
    gap: 32px;
}
.no-products{
    /* grid-column:1 / -1;
    text-align:center;
    padding:60px 20px;
    color:#999;
    font-size:16px; */
    max-width:1400px;
    width:100%;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:28px;
}
.product-card{
    display:flex;
    flex-direction:column;
    /* height:100%; */
    /* height:430px;*/
     max-width:330px; 
}
.product-image-wrap{
    position:relative;
    background:#f6f6f6;
    border-radius:16px;
    overflow:hidden;
    height:400px;
    margin-bottom:6px;
}
.product-image-link{
    display:block;
    width:100%;
    height:100%;
}
.product-image-wrap img{
    display: block;
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    transition:transform .45s ease;
}
.product-card:hover .product-image-wrap img{
    transform:scale(1.06);
}

.product-tags{
    position:absolute;
    top:14px;
    left:14px;
    display:flex;
    flex-direction:column;
    gap:6px;
    z-index:2;
}
.tag{
    padding:5px 11px;
    border-radius:20px;
    font-size:13px;
    font-weight:700;
    letter-spacing:.4px;
    color:#fff;
}
.tag-new{ background:#1a1a1a; }
.tag-sale{ background:#eb3f81; }

.wishlist-btn{
    position:absolute;
    top:14px;
    right:14px;
    z-index:2;
    width:34px;
    height:34px;
    border-radius:50%;
    border:none;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#333;
    cursor:pointer;
    opacity:0;
    transform:translateY(-6px);
    transition:.25s ease;
}
.product-card:hover .wishlist-btn{
    opacity:1;
    transform:translateY(0);
}
.wishlist-btn:hover{
    background:#eb3f81;
    color:#fff;
}

.quick-add{
    position:absolute;
    left:12px;
    right:12px;
    bottom:-50px;
    z-index:2;
    transition:bottom .3s ease;
}
.product-card:hover .quick-add{
    bottom:14px;
}
.quick-add-btn{
    display:block;
    text-align:center;
    background:#1a1a1a;
    color:#fff;
    text-decoration:none;
    font-size:15px;
    font-weight:600;
    letter-spacing:.3px;
    padding:10px;
    border-radius:10px;
    transition:.25s ease;
}
.quick-add-btn:hover{ background:#eb3f81; }
.quick-add-btn.disabled{
    background:#c9c9c9;
    cursor:not-allowed;
    pointer-events:none;
}

.product-info{
    display:flex;
    flex-direction:column;
    flex:1;
    padding:0 2px;
}
.product-category{
    display:block;
    font-size:13px;
    font-weight:700;
    letter-spacing:.6px;
    text-transform:uppercase;
    color:#b7b7b7;
    margin-bottom:6px;
}
.product-name-link{ text-decoration:none; }
.product-info h3{
    margin:0 0 2px;
    font-size:18px;
    font-weight:600;
    color:#1a1a1a;
    line-height:1.1;
    display:-webkit-box;
    -webkit-box-orient:vertical;
    -webkit-line-clamp:2;
    overflow:hidden;
    min-height:42px;
    transition:color .2s ease;
}
.product-card:hover h3{ color:#eb3f81; }

.product-bottom-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-top: 0px;
}
.price{ display:flex; align-items:center; gap:8px; }
.regular-price{ font-size:16px; font-weight:700; color:#1a1a1a; }
.sale-price{ font-size:16px; font-weight:700; color:#eb3f81; }
.old-price{ font-size:13px; color:#aaa; text-decoration:line-through; }

.stock-dot{ width:9px; height:9px; border-radius:50%; }
.stock-dot.in-stock{ background:#3ec070; }
.stock-dot.out-stock{ background:#e04b4b; }

.pagination{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    margin-top:50px;
}
.pagination a{
    min-width:38px;
    height:38px;
    padding:0 10px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
    border:1px solid #eee;
    text-decoration:none;
    color:#333;
    font-size:14px;
    font-weight:500;
    transition:.2s ease;
}
.pagination a:hover{
    border-color:#eb3f81;
    color:#eb3f81;
}
.pagination a.active{
    background:#eb3f81;
    border-color:#eb3f81;
    color:#fff;
}
.pagination-arrow{
    background:#fff;
}

@media (max-width:1200px){
    .products-container{ grid-template-columns:repeat(2, 1fr); }
}
@media (max-width:992px){
    .shop-layout{ flex-direction:column; }
    .filter-sidebar{
        width:100%;
        min-width:0;
        position:static;
    }
}
@media (max-width:600px){
    /* .products-section{ padding:0 20px 50px; margin-top:100px; }
    .products-container{ grid-template-columns:1fr; gap:22px; }
    .product-image-wrap{ height:320px; }
    .wishlist-btn{ opacity:1; transform:none; }
     .product-image-wrap{
        height:320px;
    }
    .wishlist-btn{
        opacity:1;
        transform:none;
    }
    .quick-add{
        bottom:14px;
    }
    .quick-add-btn{
        background:#eb3f81;
    }
    .quick-add-btn:hover{
        background:#d93672;
    } */

    .products-section{
        padding:0 20px 50px;
        margin-top:100px;
    }
    .products-container{
        grid-template-columns:1fr;
        gap:22px;
    }
    .product-image-wrap{
        height:320px;
        background:#f6f6f6;
    }
    .product-image-wrap img{
        width:100%;
        height:100%;
        object-fit:contain;
        object-position:center;
    }
    .wishlist-btn{
        opacity:1;
        transform:none;
    }
    .quick-add{
        bottom:14px;
    }
    .quick-add-btn{
        background:#eb3f81;
    }
    .quick-add-btn:hover{
        background:#d93672;
    }
}
</style>

<section class="products-section">
    <div class="shop-layout">

        <div class="filter-sidebar">

            <div class="sidebar-widget">
                <h2 class="widget-title">Categories</h2>
                <ul class="sidebar-categories-list">
                    <li>
                        <a href="product.php" class="<?= empty($category_id) ? 'active' : '' ?>">
                            All Categories
                        </a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a
                                href="product.php?category=<?= $category['id']; ?>&sort=<?= urlencode($sort); ?>&min_price=<?= urlencode($min_price); ?>&max_price=<?= urlencode($max_price); ?>&stock=<?= urlencode($stock_status); ?>"
                                class="<?= $category_id == $category['id'] ? 'active' : ''; ?>"
                            >
                                <?= htmlspecialchars($category['name']); ?>
                                <span>(<?= $category['total_products']; ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form method="GET" action="product.php" class="filter-form">

                <?php if (!empty($category_id)): ?>
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
                        <input type="range" id="range-min" min="0" max="<?= $db_max_price; ?>"
                               value="<?= $min_price !== '' ? htmlspecialchars($min_price) : '0'; ?>" step="1">
                        <input type="range" id="range-max" min="0" max="<?= $db_max_price; ?>"
                               value="<?= $max_price !== '' ? htmlspecialchars($max_price) : $db_max_price; ?>" step="1">
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
        <div class="products-area">

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

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a class="pagination-arrow" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                           class="<?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a class="pagination-arrow" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
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
