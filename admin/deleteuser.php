<?php
include "admin_auth.php";
include "../includes/csrf.php";
include "../includes/database.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "SELECT role, status
            FROM users
            WHERE id = :id";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        header("Location: admindashboard.php?page=users");
        exit();
    }

    $current_user_id = $_SESSION['user_id'];
$current_role = $_SESSION['role'];

if ((int)$current_user_id === (int)$id) {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit();
}

if ($current_role === 'user') {
    header("Location: ../index.php");
    exit();
}

if ($current_role === 'admin' && $user['role'] !== 'user') {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit();
}

if ($current_role === 'superadmin' && $user['role'] === 'superadmin') {
    header("Location: admindashboard.php?page=users&error=no_permission");
    exit();
}

    $current_status = ($user['status'] === true || $user['status'] === 't');
    if ($user['role'] === 'admin' && $current_status === true) {

        $sql = "SELECT COUNT(*)
                FROM users
                WHERE role = 'admin'
                AND status = true";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $admin_count = (int) $stmt->fetchColumn();

        if ($admin_count <= 1) {

            header("Location: admindashboard.php?page=users&error=last_admin");
            exit();
        }
    }

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