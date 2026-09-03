<?php
include "admin_auth.php";
include "../includes/database.php";

$search = trim($_GET['query'] ?? '');
$pagination = 10;

$page_number = isset($_GET['page_number'])
    ? (int)$_GET['page_number']
    : 1;
if($page_number < 1){
    $page_number = 1;
}

$count_sql = "SELECT COUNT(*)
              FROM products
              WHERE LOWER(name) LIKE LOWER(:search)
                 OR LOWER(description) LIKE LOWER(:search)";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute([
    "search" => "%$search%"
]);

$total_products = $count_stmt->fetchColumn();
$total_pages = ceil($total_products / $pagination);
$offset = ($page_number - 1) * $pagination;
$sql = "SELECT 
            products.*,
            categories.name AS category_name
        FROM products
        LEFT JOIN categories 
        ON products.category_id = categories.id

        WHERE LOWER(products.name) LIKE LOWER(:search)
           OR LOWER(products.description) LIKE LOWER(:search)

        ORDER BY products.id DESC

        LIMIT :limit OFFSET :offset";


$stmt = $conn->prepare($sql);
$stmt->bindValue(
    ':search',
    "%$search%",
    PDO::PARAM_STR
);
$stmt->bindValue(
    ':limit',
    $pagination,
    PDO::PARAM_INT
);
$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);
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
<style>

    .search-form{
    position:relative;
    display:flex;
    align-items:center;
    flex:1;
    max-width: 500px;
    margin-left:auto;
    margin-right:60px;
}
.search-form i{
    position:absolute;
    left:18px;
    color:#6b7280;
    font-size:23px;
}
.search-form input{
    width:500px;
    height:60px;
    padding-left:50px;
    padding-right:50px;
    border:none;
    border-radius:25px;
    background:#f1f5f9;
    font-size:20px;
    outline:none;
    transition:.3s;
}
.search-form input:focus{
    background:white;
    box-shadow:0 0 0 2px #eb3f81;
}
.search-form input[type="search"]::-webkit-search-cancel-button{
    -webkit-appearance: none;
    appearance: none;
    width:18px;
    height:18px;
    cursor:pointer;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='3' stroke-linecap='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'/%3E%3Cline x1='6' y1='6' x2='18' y2='18'/%3E%3C/svg%3E");
    background-repeat:no-repeat;
    background-position:center;
}
</style>
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">

    <form class="search-form" action="admindashboard.php" method="GET">
          <input type="hidden" name="page" value="products">
        <i class="bi bi-search"></i>
    <input 
        type="search"
        name="query"
        id="searchBox"
        placeholder="Search products..."
          value="<?= htmlspecialchars($search); ?>"
        >
    </form>
        <a href="admindashboard.php?page=addproduct" class="btn btn-primary">
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
            <img src="../images/<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
            width="80">
            </td>
            <td class="fw-semibold">
                <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td>    
                <?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td>
                $<?= number_format($product['price'], 2); ?>
            </td>
            <td>
                <?= $product['stock']; ?>
            </td>
            <td>
                <?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td>
                $<?= number_format($product['sale_price'], 2); ?>
            </td>
            <td>
                <?= $product['status'] ? 'Active' : 'Inactive'; ?>
            </td>
            <td>
                <a href="admindashboard.php?page=editproduct&edit=<?= $product['id']; ?>" class="btn btn-warning btn-sm">
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
            <nav class="mt-4">

    <ul class="pagination justify-content-center">
        <?php if($page_number > 1): ?>
            <li class="page-item">
                <a 
                    class="page-link"
                    href="admindashboard.php?page=products&query=<?= urlencode($search); ?>&page_number=<?= $page_number - 1; ?>"
                >
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page_number ? 'active' : ''; ?>">
                <a 
                    class="page-link"
                    href="admindashboard.php?page=products&query=<?= urlencode($search); ?>&page_number=<?= $i; ?>"
                >
                    <?= $i; ?>
                </a>
            </li>

        <?php endfor; ?>
        <?php if($page_number < $total_pages): ?>
            <li class="page-item">
                <a 
                    class="page-link"
                    href="admindashboard.php?page=products&query=<?= urlencode($search); ?>&page_number=<?= $page_number + 1; ?>"
                >
                    Next
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

        </div>
    </div>
</div>
<script>
const searchInput = document.getElementById('searchBox');
searchInput.addEventListener('input', function () {

    const query = this.value.trim();
    if (query.length >= 3 || query.length === 0) {
        const url = new URL(window.location.href);

        url.searchParams.set('page', 'products');
        url.searchParams.set('query', query);
        url.searchParams.set('page_number', '1');

        window.location.href = url.toString();
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>