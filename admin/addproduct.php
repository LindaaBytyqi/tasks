<?php
include "admin_auth.php";
include "../includes/database.php";

$sql = "SELECT * FROM categories ORDER BY name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if(isset($_POST['add_product'])){

    $category_id = $_POST['category_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $sale_price = !empty($_POST['sale_price'])
        ? $_POST['sale_price']
        : null;
    $status = $_POST['status'] ?? '';

    if(empty($name)){
        $errors[] = "Product name is required.";
    } elseif(strlen($name) < 2){
        $errors[] = "Product name must contain at least 2 characters.";
    }

    if(empty($category_id)){
        $errors[] = "Please select a category.";
    }

    if($price === ''){
        $errors[] = "Price is required.";
    } elseif(!is_numeric($price) || $price <= 0){
        $errors[] = "Price must be greater than 0.";
    }

    if($stock === ''){
        $errors[] = "Stock is required.";
    } elseif(!is_numeric($stock) || $stock < 0){
        $errors[] = "Stock cannot be negative.";
    }

    if($sale_price !== null){
        if(!is_numeric($sale_price) || $sale_price <= 0){
            $errors[] = "Sale price must be greater than 0.";
        } elseif($sale_price >= $price){
            $errors[] = "Sale price must be lower than the original price.";
        }
    }

    if($status !== '0' && $status !== '1'){
        $errors[] = "Invalid product status.";
    }

    if(empty($_FILES['image']['name'])){
        $errors[] = "Product image is required.";
    } else {

        $allowed_types = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if(!in_array($_FILES['image']['type'], $allowed_types)){
            $errors[] = "Only JPG, PNG and WEBP images are allowed.";
        }
    }

    if(empty($errors)){
        $image = $_FILES['image']['name'];
        $target = "../images/" . $image;
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $target
        );

        $sql = "INSERT INTO products
        (category_id, name, price, stock, description, image, sale_price, status)
        VALUES
        (:category_id, :name, :price, :stock, :description, :image, :sale_price, :status)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "category_id" => $category_id,
            "name" => $name,
            "price" => $price,
            "stock" => $stock,
            "description" => $description,
            "image" => $image,
            "sale_price" => $sale_price,
            "status" => $status
        ]);

        header("Location: admindashboard.php?page=products");
        exit();
    }
}
?>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow">
<div class="card-header bg-primary text-white">
<h3>
Add Product
</h3>
</div>

<div class="card-body">

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="mb-3">

<label>
Product Name
</label>
<input type="text"
name="name"
class="form-control"
required>
</div>

<div class="mb-3">
<label>
Category
</label>
<select name="category_id"
class="form-select"
required>

<option value="">
Choose Category
</option>


<?php foreach($categories as $category): ?>
<option value="<?= $category['id']; ?>">
<?= htmlspecialchars($category['name']); ?>
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
required>
</div>

<div class="mb-3">
<label>
Stock
</label>
<input type="number"
name="stock"
class="form-control"
required>
</div>

<div class="mb-3">
<label>
Description
</label>
<textarea name="description"
class="form-control"></textarea>
</div>



<div class="mb-3">
<label>
Sale Price
</label>
<input type="number"
step="0.01"
name="sale_price"
class="form-control">
</div>


<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select" required>
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<div class="mb-3">

<label>
Product Image
</label>

<input type="file"
name="image"
class="form-control"
accept="image/*">
</div>


<button type="submit"
name="add_product"
class="btn btn-success">
Save Product
</button>

<a href="admindashboard.php?page=products"
class="btn btn-secondary">
Cancel
</a>
</form>
</div>
</div>
</div>
</div>
</div>
