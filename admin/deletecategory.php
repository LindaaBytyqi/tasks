<?php
include "admin_auth.php";
include "../includes/database.php";

if(isset($_GET['id'])){

    $id = $_GET['id'];
    $sql = "DELETE FROM categories
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id" => $id
    ]);
}

header("Location: admindashboard.php?page=category");
exit();
?>