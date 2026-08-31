<?php
include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header("Location: admindashboard.php?page=users");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$current_role = $_SESSION['role'];

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

$target_role = $user['role'];

if ($current_role === 'user') {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit;
}
if ((int)$current_user_id === (int)$user_id) {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit;
}
if ($current_role === 'admin' && $target_role !== 'user') {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit;
}

if ($current_role === 'admin') {
    $new_role = 'admin';
} elseif ($current_role === 'superadmin') {

    if ($target_role === 'user') {
        $new_role = 'admin';
    } elseif ($target_role === 'admin') {
        $new_role = 'user';
    } else {
        header("Location: admindashboard.php?page=users&error=no_permission");
        exit;
    }

} else {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit;
}


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