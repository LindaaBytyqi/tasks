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

$sql = "SELECT role, status FROM users WHERE id = :id";

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

    if ($current_role === 'superadmin' && $target_role === 'superadmin') {
        header("Location: admindashboard.php?page=users&error=no_permission");
        exit;
    }

$current_status = ( 
    $user['status'] === true ||
     $user['status'] === 't' 
);

if ($target_role === 'admin' && $current_status === true) {
     $sql = "SELECT COUNT(*) FROM users
      WHERE role = 'admin'
      AND status = true AND id != :id";
      $stmt = $conn->prepare($sql);
      $stmt->execute([ 'id' => $user_id ]);

      $admin_count = (int)$stmt->fetchColumn(); 
      if ($admin_count < 1) {
         header("Location: admindashboard.php?page=users&error=last_admin");
          exit; 
        } }

$new_status = !$current_status;

$sql = "UPDATE users
        SET status = :status
        WHERE id = :id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'status' => $new_status ? 'true' : 'false',
    'id' => $user_id
]);

header("Location: admindashboard.php?page=users");
exit;