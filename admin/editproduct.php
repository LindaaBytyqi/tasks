<?php
include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $sql = "SELECT * FROM products WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id"=>$id
    ]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}


if(isset($_POST['update_product'])){
    verifyCsrfToken();

    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $sale_price = !empty($_POST['sale_price'])
        ? $_POST['sale_price']
        : null;
    $status = $_POST['status'];

          $image = $product['image'];
    $allowed_types = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if(!empty($_FILES['image']['name'])){

        if($_FILES['image']['error'] !== UPLOAD_ERR_OK){
            $errors[] = "Image upload failed.";
        } else {
            $max_file_size = 2 * 1024 * 1024;
            if($_FILES['image']['size'] > $max_file_size){
                $errors[] = "Image size must not exceed 2MB.";
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file(
                $_FILES['image']['tmp_name']
            );

            if(!array_key_exists($mime_type, $allowed_types)){
                $errors[] = "Only JPG, PNG and WEBP images are allowed.";
            }
        }
    }

       if(empty($errors)){

        $sql = "UPDATE products SET
                category_id=:category_id,
                name=:name,
                price=:price,
                stock=:stock,
                description=:description,
                image=:image,
                sale_price=:sale_price,
                status=:status
                WHERE id=:id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "id" => $id,
            "category_id" => $category_id,
            "name" => $name,
            "price" => $price,
            "stock" => $stock,
            "description" => $description,
            "image" => $image,
            "sale_price" => $sale_price,
            "status" => $status
        ]);

      echo  
        '<script>
            window.location.href = "admindashboard.php?page=products";
        </script>';
    exit;
    }
}

?>


<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow">

<div class="card-header bg-warning">
<h3>Edit Product</h3>
</div>


<div class="card-body">
<form method="POST" enctype="multipart/form-data">
    <input type="hidden"
       name="csrf_token"
       value="<?= htmlspecialchars(generateCsrfToken()) ?>">

<input type="hidden" 
name="id"
value="<?= $product['id']; ?>">

<div class="mb-3">
<label>
Product Name
</label>
<input type="text"
name="name"
class="form-control"
value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>"
required>
</div>


<div class="mb-3">
<label>
Category
</label>
<select name="category_id"
class="form-select"
required>
<?php foreach($categories as $category): ?>
<option value="<?= $category['id']; ?>"
<?= $category['id']==$product['category_id'] ? "selected" : "" ?>>
<?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
</option>
<?php endforeach; ?>
</select>

</div>
<div class="mb-3">
<label>
Price
</label>
<input type="number"
step="0.01"
name="price"
class="form-control"
value="<?= $product['price']; ?>">
</div>

<div class="mb-3">
<label>
Stock
</label>
<input type="number"
name="stock"
class="form-control"
value="<?= $product['stock']; ?>">
</div>

<div class="mb-3">
<label>
Description
</label>
<textarea name="description"
class="form-control"><?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
</div>

<div class="mb-3">
<label>
Sale Price
</label>
<input type="number"
step="0.01"
name="sale_price"
class="form-control"
value="<?= $product['sale_price']; ?>">
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select">
        <option value="1"
        <?= $product['status'] == 1 ? 'selected' : ''; ?>>
            Active
        </option>
        <option value="0"
        <?= $product['status'] == 0 ? 'selected' : ''; ?>>
            Inactive
        </option>
    </select>
</div>

<div class="mb-3">
<label>
Product Image
</label>
<input type="file"
name="image"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">
</div>

<button type="submit"
name="update_product"
class="btn btn-success">
Update Product
</button>

<a href="product.php"
class="btn btn-secondary">

Cancel

</a>
</form>

</div>
</div>

</div>

</div>

</div>


</body>
</html>