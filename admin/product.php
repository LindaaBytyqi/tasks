<?php
include "../includes/database.php";

$sql = "SELECT 
            products.*,
            categories.name AS category_name
        FROM products
        LEFT JOIN categories 
        ON products.category_id = categories.id
        ORDER BY products.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            Products
        </h2>

        <a href="addproduct.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Product
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>                                      
            <th>Price</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Sale Price</th>
            <th>Status</th>
            <th width="180">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($products as $product): ?>
        <tr>
            <td>
                <?= $product['id']; ?>
            </td>
           <td>
            <img src="../images/<?= htmlspecialchars($product['image']); ?>"
            width="80">
            </td>
            <td class="fw-semibold">
                <?= htmlspecialchars($product['name']); ?>
            </td>
            <td>    
                <?= htmlspecialchars($product['category_name']); ?>
            </td>
            <td>
                $<?= number_format($product['price'], 2); ?>
            </td>
            <td>
                <?= $product['stock']; ?>
            </td>
            <td>
                <?= htmlspecialchars($product['description']); ?>
            </td>
            <td>
                $<?= number_format($product['sale_price'], 2); ?>
            </td>
            <td>
                <?= $product['status'] ? 'Active' : 'Inactive'; ?>
            </td>
            <td>
                <a href="editproduct.php?edit=<?= $product['id']; ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="deleteproduct.php?delete=<?= $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                    <i class="bi bi-trash"></i> Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>