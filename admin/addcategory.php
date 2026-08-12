<?php
include "../includes/database.php";

if(isset($_POST['add_category'])){
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO categories(name, description)
            VALUES(:name, :description)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "name"=>$name,
        "description"=>$description
    ]);
    header("Location: category.php");
    exit();

}

?>


<!DOCTYPE html>
<html>
<head>
<title>Add Category</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>
Add Category
</h3>

</div>


<div class="card-body">
<form method="POST">
<div class="mb-3">
<label class="form-label">
Category Name
</label>


<input type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">
Description
</label>


<textarea name="description"
class="form-control"
rows="4"></textarea>


</div>

<button type="submit"
name="add_category"
class="btn btn-success">
Save Category
</button>

<a href="category.php"
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