

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$sql = "SELECT role, status
        FROM users
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $current_user_id
]);

$current_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current_user) {
    session_unset();
    session_destroy();

    header("Location: ../login.php");
    exit();
}

if (
    !in_array($current_user['role'], ['admin', 'superadmin'], true)
    || $current_user['status'] !== true
    
) {
    header("Location: ../index.php");
    exit();
}

$_SESSION['role'] = $current_user['role'];
$_SESSION['status'] = $current_user['status'];

?>

