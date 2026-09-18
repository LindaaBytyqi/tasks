
<style>
.products-section{
    width:100%;
    padding:70px 80px;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing:border-box;
}
.products-heading{
    text-align:center;
    max-width:480px;
    margin:0 auto 50px;
}
.heading-eyebrow{
    display:inline-block;
    margin-bottom:10px;
    color:#eb3f81;
    font-size:12px;
    font-weight:700;
    letter-spacing:3px;
}
.products-heading h2{
    margin:0 0 12px;
    font-size:34px;
    font-weight:700;
    letter-spacing:-.5px;
    color:#1a1a1a;
}
.products-heading p{
    margin:0;
    font-size:16px;
    line-height:1.7;
    color:#828282;
}
.products-container{
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
}

.product-image-wrap{
    position:relative;
    background:#f6f6f6;
    border-radius:16px;
    overflow:hidden;
    height:400px;
    margin-bottom:16px;
}
.product-image-link{
    display:block;
    width:100%;
    height:100%;
}
.product-image-wrap img{
    width:100%;
    height:100%;
    object-fit:cover;
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
    font-size:11px;
    font-weight:700;
    letter-spacing:.4px;
    color:#fff;
}
.tag-new{
    background:#1a1a1a;
}
.tag-sale{
    background:#eb3f81;
}
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
    font-size:13px;
    font-weight:600;
    letter-spacing:.3px;
    padding:12px;
    border-radius:10px;
    transition:.25s ease;
}
.quick-add-btn:hover{
    background:#eb3f81;
}
.quick-add-btn.disabled{
    background:#c9c9c9;
    cursor:not-allowed;
    pointer-events:none;
}

.product-info{
    padding:0 2px;
}
.product-category{
    display:block;
    font-size:12px;
    font-weight:700;
    letter-spacing:.6px;
    text-transform:uppercase;
    color:#b7b7b7;
    margin-bottom:6px;
}
.product-name-link{
    text-decoration:none;
}
.product-info h3{
    margin:0 0 2px;
    font-size:18px;
    font-weight:600;
    color:#1a1a1a;
    line-height:1.3;
    display:-webkit-box;
    -webkit-box-orient:vertical;
    -webkit-line-clamp:2;
    overflow:hidden;
    min-height:42px;
    transition:color .2s ease;
}
.product-card:hover h3{
    color:#eb3f81;
}

.product-bottom-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.price{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom: 10px;
    margin-top: 0px;
}
.regular-price{
    font-size:16px;
    font-weight:700;
    color:#1a1a1a;
}
.sale-price{
    font-size:16px;
    font-weight:700;
    color:#eb3f81;
}
.old-price{
    font-size:13px;
    color:#aaa;
    text-decoration:line-through;
}

.stock-dot{
    width:9px;
    height:9px;
    border-radius:50%;
}
.stock-dot.in-stock{ background:#3ec070; }
.stock-dot.out-stock{ background:#e04b4b; }

@media (max-width:1200px){
    .products-container{ grid-template-columns:repeat(2, 1fr); }
}
@media (max-width:600px){
    .products-section{ padding:45px 20px; }
    .products-container{ grid-template-columns:1fr; gap:22px; }
    .product-image-wrap{ height:300px; }
    .wishlist-btn{ opacity:1; transform:none; } 
    .quick-add{ bottom:14px; } 
}
</style>

<?php
include "includes/database.php";

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
    ORDER BY p.created_at DESC
    LIMIT 8
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="products-section">

    <div class="products-heading">
        <span class="heading-eyebrow">JUST ARRIVED</span>
        <h2>Top Newest Products</h2>
    </div>

    <div class="products-container">
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
                                Shto në Shportë
                            </a>
                        <?php else: ?>
                            <span class="quick-add-btn disabled">Nuk ka Stok</span>
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

                        <span class="stock-dot <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>"
                              title="<?= $product['stock'] > 0 ? 'Në Stok' : 'Pa Stok'; ?>"></span>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</section>
