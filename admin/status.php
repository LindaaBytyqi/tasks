<?php
include "../includes/database.php";
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: admindashboard.php?page=category");
    exit();
}

$id = (int) $_GET['id'];
$status = (int) $_GET['status'];

if ($status !== 0 && $status !== 1) {
    header("Location: admindashboard.php?page=category");
    exit();
}

$sql = "UPDATE categories 
        SET status = :status 
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':status' => $status,
    ':id' => $id
]);

header("Location: admindashboard.php?page=category");
exit();