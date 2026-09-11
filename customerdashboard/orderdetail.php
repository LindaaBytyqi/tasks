<?php
include "../includes/user_auth.php";
include "../includes/csrf.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/database.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT email FROM users WHERE id = :id");
$user_stmt->execute(['id' => $user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
$user_email = $user['email'] ?? '';
$sql = "SELECT * 
        FROM orders 
        WHERE email = :email OR user_id = :user_id 
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'email'   => $user_email,
    'user_id' => $user_id
]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <h2 class="mb-4">
        My Orders
    </h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
        <a href="../index.php" class="btn btn-primary">
            Start Shopping
        </a>
    <?php else: ?>

        <?php foreach ($orders as $order): ?>
            <div class="card mb-4 shadow-sm ">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h5>
                                Order #<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?> 
                            </h5>

                            <p class="mb-1">
                                <strong>Total:</strong>
                                €<?= number_format($order['total'], 2); ?>
                            </p>

                            <p class="mb-1">
                                <strong>Payment:</strong>
                                <?php if ($order['payment_method'] == '0'): ?>
                                    Cash on Delivery
                                <?php else: ?>
                                    Online Payment
                                <?php endif; ?>
                            </p>

                            <p class="mb-0">
                                <strong>Status:</strong>
                                <span class="badge bg-warning text-dark">
                                    <?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); ?>  
                                </span>
                            </p>
                        </div>

                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                             <a
                             href="viewdetails.php?order_id=<?= $order['id']; ?>"
                            class="btn btn-outline-primary"
                            >
                             View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>