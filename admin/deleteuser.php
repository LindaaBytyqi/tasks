<?php
include "../includes/database.php";
if(isset($_GET['id'])){

    $id = $_GET['id'];
    $sql = "DELETE FROM users
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id" => $id
    ]);
}

header("Location: admindashboard.php?page=users");
exit();
?>