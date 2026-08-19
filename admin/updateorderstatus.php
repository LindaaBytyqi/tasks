<?php
include "admin_auth.php";
include "../includes/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admindashboard.php?page=orders");
    exit;
}

$order_id = (int) ($_POST['order_id'] ?? 0);
$status = strtolower(trim($_POST['status'] ?? ''));
$allowed_statuses = [
    'pending',
    'processing',
    'shipped',
    'completed',
    'cancelled'
];

if (
    $order_id <= 0 ||
    !in_array($status, $allowed_statuses, true)
) {
    header("Location: admindashboard.php?page=orders");
    exit;
}

$sql = "UPDATE orders
        SET status = :status
        WHERE id = :id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':status' => $status,
    ':id' => $order_id
]);

header(
    "Location: admindashboard.php?page=orders&id=" . $order_id
);

exit;