<?php
include "../includes/database.php";
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header("Location: admindashboard.php?page=users");
    exit;
}

$sql = "SELECT role FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $user_id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header("Location: admindashboard.php?page=users");
    exit;
}

$new_role = ($user['role'] === 'admin') ? 'user' : 'admin';
$sql = "UPDATE users
        SET role = :role
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'role' => $new_role,
    'id' => $user_id
]);
header("Location: admindashboard.php?page=users");
exit;