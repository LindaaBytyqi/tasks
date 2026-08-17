<?php
include "../includes/database.php";
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header("Location: admindashboard.php?page=users");
    exit;
}
$sql = "SELECT status FROM users WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $user_id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header("Location: admindashboard.php?page=users");
    exit;
}
$current_status = ($user['status'] === true || $user['status'] === 't');

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