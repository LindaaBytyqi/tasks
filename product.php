<?php
include "includes/header.php";
include "includes/database.php";


$sort = $_GET['sort'] ?? "newest";
$min_price = $_GET['min_price'] ?? "";
$max_price = $_GET['max_price'] ?? "";

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
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
            <h3>
                Filter
            </h3>

            <form
                method="GET"
                action="product.php"
            >
                <label>
                    Sort By
                </label>
                <select name="sort">
                    <option
                        value="newest"
                        <?= $sort === "newest" ? "selected" : ""; ?>
                    >
                        Newest
                    </option>
                    <option
                        value="low"
                        <?= $sort === "low" ? "selected" : ""; ?>
                    >
                        Price Low to High
                    </option>
                    <option
                        value="high"
                        <?= $sort === "high" ? "selected" : ""; ?>
                    >
                        Price High to Low
                    </option>
                    <option
                        value="az"
                        <?= $sort === "az" ? "selected" : ""; ?>
                    >
                        Alphabetically (A-Z)
                    </option>

                    <option
                        value="za"
                        <?= $sort === "za" ? "selected" : ""; ?>
                    >
                        Alphabetically (Z-A)
                    </option>
                </select>
                <label>
                    Min Price
                </label>
                <input
                    type="number"
                    name="min_price"
                    value="<?= htmlspecialchars($min_price); ?>"
                    min="0"
                    step="0.01"
                >
                <label>
                    Max Price
                </label>

                <input
                    type="number"
                    name="max_price"
                    value="<?= htmlspecialchars($max_price); ?>"
                    min="0"
                    step="0.01"
                >
                <button
                    type="submit"
                    class="btn-apply"
                >
                    Apply
                </button>
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

<?php
include "includes/footer.php";
?>