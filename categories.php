<?php

include "includes/header.php";
include "includes/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: product.php");
    exit;
}

$category_id = (int) $_GET['id'];

$sql = "
    SELECT *
    FROM categories
    WHERE id = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([
    "id" => $category_id
]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$category) {
    header("Location: product.php");
    exit;
}




$sort = $_GET['sort'] ?? "newest";
$min_price = $_GET['min_price'] ?? "";
$max_price = $_GET['max_price'] ?? "";
$stock_status = $_GET['stock'] ?? "";


/* =========================================================
   MAX PRICE FOR THIS CATEGORY
========================================================= */

$max_price_sql = "
    SELECT MAX(COALESCE(sale_price, price)) AS highest_price
    FROM products
    WHERE category_id = :category_id
    AND status IN ('1', 'active')
";

$max_price_stmt = $conn->prepare($max_price_sql);

$max_price_stmt->execute([
    "category_id" => $category_id
]);

$max_price_row = $max_price_stmt->fetch(PDO::FETCH_ASSOC);


$db_max_price = !empty($max_price_row['highest_price'])
    ? ceil($max_price_row['highest_price'])
    : 500;


/* =========================================================
   GET ALL CATEGORIES + PRODUCT COUNTS
========================================================= */

$cat_sql = "
    SELECT
        c.*,
        COUNT(
            CASE
                WHEN p.status IN ('1', 'active')
                THEN p.id
            END
        ) AS total_products

    FROM categories c

    LEFT JOIN products p
        ON c.id = p.category_id

    GROUP BY c.id

    ORDER BY c.id ASC
";

$cat_stmt = $conn->prepare($cat_sql);
$cat_stmt->execute();

$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   PAGINATION
========================================================= */

$products_per_page = 9;

$page = isset($_GET['page']) && is_numeric($_GET['page'])
    ? (int) $_GET['page']
    : 1;

$page = max($page, 1);

$offset = ($page - 1) * $products_per_page;


/* =========================================================
   COUNT PRODUCTS
========================================================= */

$count_sql = "
    SELECT COUNT(*)
    FROM products p
    WHERE p.category_id = :category_id
    AND p.status IN ('1', 'active')
";

$count_params = [
    "category_id" => $category_id
];


/* STOCK FILTER */

if ($stock_status === "instock") {

    $count_sql .= " AND p.stock > 0";

} elseif ($stock_status === "outofstock") {

    $count_sql .= " AND p.stock <= 0";
}


/* MIN PRICE */

if ($min_price !== "") {

    $count_sql .= "
        AND COALESCE(p.sale_price, p.price) >= :min_price
    ";

    $count_params["min_price"] = $min_price;
}


/* MAX PRICE */

if ($max_price !== "") {

    $count_sql .= "
        AND COALESCE(p.sale_price, p.price) <= :max_price
    ";

    $count_params["max_price"] = $max_price;
}


$count_stmt = $conn->prepare($count_sql);

$count_stmt->execute($count_params);

$total_products = (int) $count_stmt->fetchColumn();

$total_pages = (int) ceil(
    $total_products / $products_per_page
);


/* =========================================================
   GET PRODUCTS
========================================================= */

$sql = "
    SELECT

        p.*,

        c.name AS category_name,

        CASE
            WHEN p.sale_price IS NOT NULL
                 AND p.sale_price < p.price

            THEN ROUND(
                (
                    100 -
                    (p.sale_price / p.price * 100)
                )::numeric
            )

            ELSE NULL
        END AS discount_percent,

        (
            p.created_at >= NOW() - INTERVAL '14 days'
        ) AS is_new

    FROM products p

    LEFT JOIN categories c
        ON c.id = p.category_id

    WHERE p.category_id = :category_id

    AND p.status IN ('1', 'active')
";

$params = [
    "category_id" => $category_id
];


/* =========================================================
   STOCK FILTER
========================================================= */

if ($stock_status === "instock") {

    $sql .= " AND p.stock > 0";

} elseif ($stock_status === "outofstock") {

    $sql .= " AND p.stock <= 0";
}


/* =========================================================
   MIN PRICE
========================================================= */

if ($min_price !== "") {

    $sql .= "
        AND COALESCE(p.sale_price, p.price) >= :min_price
    ";

    $params["min_price"] = $min_price;
}


/* =========================================================
   MAX PRICE
========================================================= */

if ($max_price !== "") {

    $sql .= "
        AND COALESCE(p.sale_price, p.price) <= :max_price
    ";

    $params["max_price"] = $max_price;
}


/* =========================================================
   SORT
========================================================= */

switch ($sort) {

    case "low":

        $sql .= "
            ORDER BY COALESCE(p.sale_price, p.price) ASC
        ";

        break;


    case "high":

        $sql .= "
            ORDER BY COALESCE(p.sale_price, p.price) DESC
        ";

        break;


    case "az":

        $sql .= "
            ORDER BY p.name ASC
        ";

        break;


    case "za":

        $sql .= "
            ORDER BY p.name DESC
        ";

        break;


    default:

        $sql .= "
            ORDER BY p.created_at DESC
        ";

        break;
}


/* =========================================================
   PAGINATION LIMIT
========================================================= */

$sql .= "
    LIMIT :limit
    OFFSET :offset
";


$stmt = $conn->prepare($sql);


/* Bind normal parameters */

foreach ($params as $key => $value) {

    $stmt->bindValue(
        ":$key",
        $value
    );
}


/* Bind pagination */

$stmt->bindValue(
    ":limit",
    $products_per_page,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ":offset",
    $offset,
    PDO::PARAM_INT
);


$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>

    .products-section {
        margin-top: 130px;
    }

    .category-page-title {
        max-width: 1450px;
        margin: 0 auto 35px;
        padding: 0 20px;
    }

    .category-page-title h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 600;
    }

</style>


<section class="products-section">


    <!-- =====================================================
         CATEGORY TITLE
    ====================================================== -->

    <div class="category-page-title">

        <h1>
            <?= htmlspecialchars(
                $category['name'],
                ENT_QUOTES,
                'UTF-8'
            ); ?>
        </h1>

    </div>


    <!-- =====================================================
         SHOP LAYOUT
    ====================================================== -->

    <div class="shop-layout">


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <div class="filter-sidebar">


            <!-- ================= CATEGORIES ================= -->

            <div class="sidebar-widget">

                <h2 class="widget-title">
                    Categories
                </h2>


                <ul class="sidebar-categories-list">


                    <!-- ALL CATEGORIES -->

                    <li>

                        <a
                            href="product.php"
                            class="<?= empty($category_id) ? 'active' : ''; ?>"
                        >

                            All Categories

                        </a>

                    </li>


                    <!-- CATEGORY LIST -->

                    <?php foreach ($categories as $cat): ?>

                        <li>

                            <a
                                href="category.php?id=<?= (int) $cat['id']; ?>&sort=<?= urlencode($sort); ?>&min_price=<?= urlencode($min_price); ?>&max_price=<?= urlencode($max_price); ?>&stock=<?= urlencode($stock_status); ?>"
                                class="<?= $category_id == $cat['id'] ? 'active' : ''; ?>"
                            >

                                <?= htmlspecialchars(
                                    $cat['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                <span>
                                    (<?= (int) $cat['total_products']; ?>)
                                </span>

                            </a>

                        </li>

                    <?php endforeach; ?>


                </ul>

            </div>


            <!-- =================================================
                 FILTER FORM
            ================================================== -->

            <form
                method="GET"
                action="category.php"
                class="filter-form"
            >


                <!-- CATEGORY ID -->

                <input
                    type="hidden"
                    name="id"
                    value="<?= htmlspecialchars(
                        $category_id,
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                >


                <!-- ================= SORT ================= -->

                <div class="sidebar-widget">

                    <label
                        class="widget-title"
                        for="sort-select"
                    >
                        Sort By
                    </label>


                    <select
                        name="sort"
                        id="sort-select"
                        class="styled-select"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="newest"
                            <?= $sort === "newest"
                                ? "selected"
                                : ""; ?>
                        >
                            Newest
                        </option>


                        <option
                            value="low"
                            <?= $sort === "low"
                                ? "selected"
                                : ""; ?>
                        >
                            Price Low to High
                        </option>


                        <option
                            value="high"
                            <?= $sort === "high"
                                ? "selected"
                                : ""; ?>
                        >
                            Price High to Low
                        </option>


                        <option
                            value="az"
                            <?= $sort === "az"
                                ? "selected"
                                : ""; ?>
                        >
                            Alphabetically (A-Z)
                        </option>


                        <option
                            value="za"
                            <?= $sort === "za"
                                ? "selected"
                                : ""; ?>
                        >
                            Alphabetically (Z-A)
                        </option>

                    </select>

                </div>


                <!-- ================= AVAILABILITY ================= -->

                <div class="sidebar-widget">

                    <label
                        class="widget-title"
                        for="stock-select"
                    >
                        Availability
                    </label>


                    <select
                        name="stock"
                        id="stock-select"
                        class="styled-select"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            All Products
                        </option>


                        <option
                            value="instock"
                            <?= $stock_status === "instock"
                                ? "selected"
                                : ""; ?>
                        >
                            In Stock
                        </option>


                        <option
                            value="outofstock"
                            <?= $stock_status === "outofstock"
                                ? "selected"
                                : ""; ?>
                        >
                            Out of Stock
                        </option>

                    </select>

                </div>


                <!-- ================= PRICE ================= -->

                <div class="sidebar-widget">

                    <h3 class="widget-title">
                        Filter
                    </h3>


                    <div class="range-slider-wrapper">

                        <div class="slider-track"></div>


                        <input
                            type="range"
                            id="range-min"
                            min="0"
                            max="<?= $db_max_price; ?>"
                            value="<?= $min_price !== ''
                                ? htmlspecialchars($min_price)
                                : '0'; ?>"
                            step="1"
                        >


                        <input
                            type="range"
                            id="range-max"
                            min="0"
                            max="<?= $db_max_price; ?>"
                            value="<?= $max_price !== ''
                                ? htmlspecialchars($max_price)
                                : $db_max_price; ?>"
                            step="1"
                        >

                    </div>


                    <!-- HIDDEN VALUES -->

                    <input
                        type="hidden"
                        name="min_price"
                        id="min_price_input"
                        value="<?= htmlspecialchars($min_price); ?>"
                    >


                    <input
                        type="hidden"
                        name="max_price"
                        id="max_price_input"
                        value="<?= htmlspecialchars($max_price); ?>"
                    >


                    <!-- PRICE TEXT -->

                    <div class="price-range-text">

                        Price:

                        $
                        <span id="min-price-display">
                            0
                        </span>

                        &mdash;

                        $
                        <span id="max-price-display">
                            <?= $db_max_price; ?>
                        </span>

                    </div>


                    <!-- FILTER BUTTON -->

                    <button
                        type="submit"
                        class="btn-filter-blue"
                    >

                        Filter

                        <span class="arrow">
                            &rarr;
                        </span>

                    </button>


                </div>


            </form>


        </div>


        <!-- =================================================
             PRODUCTS AREA
        ================================================== -->

        <div class="products-area">


            <div class="products-container">


                <?php if (!empty($products)): ?>


                    <?php foreach ($products as $product): ?>


                        <div class="product-card">


                            <!-- ================= IMAGE ================= -->

                            <div class="product-image-wrap">


                                <!-- PRODUCT TAGS -->

                                <div class="product-tags">


                                    <?php if (!empty($product['is_new'])): ?>

                                        <span class="tag tag-new">
                                            NEW
                                        </span>

                                    <?php endif; ?>


                                    <?php if (!empty($product['discount_percent'])): ?>

                                        <span class="tag tag-sale">

                                            -<?= (int) $product['discount_percent']; ?>%

                                        </span>

                                    <?php endif; ?>


                                </div>


                                <!-- WISHLIST -->

                                <button
                                    type="button"
                                    class="wishlist-btn"
                                    aria-label="Shto te të preferuarat"
                                >

                                    <svg
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path d="M12 21s-7.5-4.6-10-9.1C.5 8.4 2.3 5 5.8 5c2 0 3.4 1 4.2 2.3C10.8 6 12.2 5 14.2 5c3.5 0 5.3 3.4 3.8 6.9-2.5 4.5-6 9.1-6 9.1z"/>

                                    </svg>

                                </button>


                                <!-- PRODUCT IMAGE -->

                                <a
                                    href="productdetails.php?id=<?= (int) $product['id']; ?>"
                                    class="product-image-link"
                                >

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
                                        loading="lazy"
                                    >

                                </a>


                                <!-- QUICK ADD -->

                                <div class="quick-add">


                                    <?php if ((int) $product['stock'] > 0): ?>

                                        <a
                                            href="cart.php?action=add&id=<?= (int) $product['id']; ?>"
                                            class="quick-add-btn"
                                        >
                                            Add Cart
                                        </a>

                                    <?php else: ?>

                                        <span class="quick-add-btn disabled">
                                            Out of Stock
                                        </span>

                                    <?php endif; ?>


                                </div>


                            </div>


                            <!-- ================= PRODUCT INFO ================= -->

                            <div class="product-info">


                                <!-- CATEGORY -->

                                <?php if (!empty($product['category_name'])): ?>

                                    <span class="product-category">

                                        <?= htmlspecialchars(
                                            $product['category_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </span>

                                <?php endif; ?>


                                <!-- PRODUCT NAME -->

                                <a
                                    href="productdetails.php?id=<?= (int) $product['id']; ?>"
                                    class="product-name-link"
                                >

                                    <h3>

                                        <?= htmlspecialchars(
                                            $product['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>

                                    </h3>

                                </a>


                                <!-- ================= PRICE ================= -->

                                <div class="product-bottom-row">


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


                                    <!-- STOCK DOT
                                    <span
                                        class="stock-dot <?= $product['stock'] > 0
                                            ? 'in-stock'
                                            : 'out-stock'; ?>"
                                        title="<?= $product['stock'] > 0
                                            ? 'In Stock'
                                            : 'Out of Stock'; ?>"
                                    ></span>
                                    -->


                                </div>


                            </div>


                        </div>


                    <?php endforeach; ?>


                <?php else: ?>


                    <p class="no-products">
                        No products found in this category.
                    </p>


                <?php endif; ?>


            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->

            <?php if ($total_pages > 1): ?>

                <div class="pagination">


                    <!-- PREVIOUS -->

                    <?php if ($page > 1): ?>

                        <a
                            class="pagination-arrow"
                            href="?<?= http_build_query(
                                array_merge(
                                    $_GET,
                                    [
                                        'page' => $page - 1
                                    ]
                                )
                            ); ?>"
                        >

                            <i class="bi bi-arrow-left"></i>

                        </a>

                    <?php endif; ?>


                    <!-- PAGE NUMBERS -->

                    <?php for (
                        $i = 1;
                        $i <= $total_pages;
                        $i++
                    ): ?>

                        <a
                            href="?<?= http_build_query(
                                array_merge(
                                    $_GET,
                                    [
                                        'page' => $i
                                    ]
                                )
                            ); ?>"
                            class="<?= $i == $page
                                ? 'active'
                                : ''; ?>"
                        >

                            <?= $i; ?>

                        </a>

                    <?php endfor; ?>


                    <!-- NEXT -->

                    <?php if ($page < $total_pages): ?>

                        <a
                            class="pagination-arrow"
                            href="?<?= http_build_query(
                                array_merge(
                                    $_GET,
                                    [
                                        'page' => $page + 1
                                    ]
                                )
                            ); ?>"
                        >

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    <?php endif; ?>


                </div>

            <?php endif; ?>


        </div>


    </div>

</section>


<!-- =========================================================
     PRICE SLIDER
========================================================= -->

<script>

const minRange =
    document.getElementById('range-min');

const maxRange =
    document.getElementById('range-max');

const minDisplay =
    document.getElementById('min-price-display');

const maxDisplay =
    document.getElementById('max-price-display');

const minInput =
    document.getElementById('min_price_input');

const maxInput =
    document.getElementById('max_price_input');

const track =
    document.querySelector('.slider-track');


function updateSlider() {

    let minVal =
        parseInt(minRange.value);

    let maxVal =
        parseInt(maxRange.value);


    const maxLimit =
        parseInt(minRange.max) || 1;


    if (minVal >= maxVal) {

        minVal = maxVal - 1;

        if (minVal < 0) {
            minVal = 0;
        }

        minRange.value = minVal;

    }


    minDisplay.textContent =
        minVal;

    maxDisplay.textContent =
        maxVal;


    minInput.value =
        minVal;

    maxInput.value =
        maxVal;


    const percentMin =
        (minVal / maxLimit) * 100;

    const percentMax =
        (maxVal / maxLimit) * 100;


    track.style.left =
        percentMin + "%";

    track.style.width =
        (percentMax - percentMin) + "%";

}


minRange.addEventListener(
    'input',
    updateSlider
);

maxRange.addEventListener(
    'input',
    updateSlider
);


updateSlider();

</script>


<?php

include "includes/footer.php";

?>

