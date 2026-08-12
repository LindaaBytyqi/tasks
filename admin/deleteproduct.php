<?php
include "../includes/database.php";

if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $sql = "DELETE FROM products WHERE id=:id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        "id"=>$id
    ]);
}

header("Location: product.php");
exit();

?>