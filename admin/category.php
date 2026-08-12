<?php

include "../includes/database.php";


$sql = "SELECT * FROM categories ORDER BY id ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Categories</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">
Categories
</h2>


<a href="addcategory.php" class="btn btn-primary">
<i class="bi bi-plus-circle"></i>
Add Category
</a>
</div>


<div class="card shadow-sm">
<div class="card-body">

<table class="table table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Description</th>
<th>Actions</th>

</tr>

</thead>

<tbody>


<?php foreach($categories as $category): ?>

<tr>

<td>
<?= $category['id']; ?>
</td>

<td>
<?= htmlspecialchars($category['name']); ?>
</td>

<td>
<?= htmlspecialchars($category['description']); ?>
</td>


<td>
<a href="editcategory.php?id=<?= $category['id']; ?>"
class="btn btn-warning btn-sm">
Edit
</a>

<a href="deletecategory.php?id=<?= $category['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure?');">
Delete
</a>

</td>

</tr>


<?php endforeach; ?>


</tbody>
</table>
</div>
</div>

</div>

</body>

</html>