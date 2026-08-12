<?php

include "../includes/database.php";


if(isset($_GET['id'])){

    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id=:id";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
        "id"=>$id
    ]);

    $category = $stmt->fetch(PDO::FETCH_ASSOC);

}



if(isset($_POST['update_category'])){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "UPDATE categories
            SET name=:name,
                description=:description
            WHERE id=:id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([

        "id"=>$id,
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="row justify-content-center">

<div class="col-md-6">


<div class="card shadow">


<div class="card-header bg-warning">

<h3>
Edit Category
</h3>

</div>


<div class="card-body">


<form method="POST">


<input type="hidden" 
name="id"
value="<?= $category['id']; ?>">



<div class="mb-3">

<label>
Category Name
</label>

<input type="text"
name="name"
class="form-control"
value="<?= htmlspecialchars($category['name']); ?>"
required>

</div>



<div class="mb-3">

<label>
Description
</label>


<textarea name="description"
class="form-control"
rows="4"><?= htmlspecialchars($category['description']); ?></textarea>


</div>



<button type="submit"
name="update_category"
class="btn btn-success">

Update

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