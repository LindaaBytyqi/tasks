<?php
include "admin_auth.php";
include "../includes/database.php";


if(isset($_GET['id'])){
    $id= $_GET['id'];
    $sql="DELETE FROM newsletter_subscribers WHERE id = :id";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        "id" =>$id
    ]);
}
header("Location: admindashboard.php?page=newsletter");
exit();
?>