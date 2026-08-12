<?php if (!empty($products)): ?>
<?php foreach($products as $product): ?>

<div class="product-card">
<img src="images/<?= $product['image']; ?>">
<h3>
<?= htmlspecialchars($product['name']); ?>
</h3>


<p>
<?= htmlspecialchars($product['description']); ?>
</p>

<?php if($product['sale_price']): ?>
    <span>
        $<?= $product['sale_price']; ?>
    </span>
    <del>
        $<?= $product['price']; ?>
    </del>
<?php else: ?>
    <span>
        $<?= $product['price']; ?>
    </span>
<?php endif; ?>

<a href="product-details.php?id=<?= $product['id']; ?>">
    View Product
</a>

<button>
    Add to Cart
</button>

</div>
<?php endforeach; ?>
<?php endif; ?>