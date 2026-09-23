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

<style>

.empty-order-page {
    width: 100%;
    min-height: 620px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 200px 20px 120px;
    box-sizing: border-box;
    background: #fff;
}

.empty-order-content {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
}

.empty-order-icon {
    width: 82px;
    height: 82px;
    margin: 0 auto 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e2e2;
    border-radius: 50%;
    background: #fff;
    color: #e681b3;
    font-size: 32px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
}

.empty-order-label {
    display: block;
    margin-bottom: 14px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 4px;
    color: #999;
}

.empty-order-content h1 {
    margin: 0;
    font-size: clamp(38px, 4vw, 52px);
    font-weight: 500;
    line-height: 1.15;
    letter-spacing: -1.5px;
    color: #171717;
}

.empty-order-content p {
    max-width: 450px;
    margin: 18px auto 0;
    font-size: 16px;
    line-height: 1.8;
    color: #858585;
}

.empty-order-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-top: 32px;
    padding: 14px 24px;
    background: #e681b3;
    color: #fff !important;
    text-decoration: none !important;
    font-size: 15px;
    font-weight: 600;
    border-radius: 6px;
    transition:
        background .3s ease,
        box-shadow .3s ease,
        transform .3s ease;
}

.empty-order-button span {
    display: inline-block;
    font-size: 21px;
    font-weight: 300;
    transition: transform .3s ease;
}

.empty-order-button:hover {
    background: #d96fa5;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(230, 129, 179, .22);
}

.empty-order-button:hover span {
    transform: translateX(5px);
}

@media (max-width: 991px) {
    .empty-order-page {
        min-height: 560px;
        padding: 90px 25px 100px;
    }

}


@media (max-width: 767px) {
    .empty-order-page {
        min-height: 500px;
        padding: 80px 20px 100px;
    }

    .empty-order-icon {
        width: 70px;
        height: 70px;
        margin-bottom: 22px;
        font-size: 27px;
    }

    .empty-order-label {
        font-size: 10px;
        letter-spacing: 3px;
    }

    .empty-order-content h1 {
        font-size: 36px;
    }

    .empty-order-content p {
        max-width: 350px;
        font-size: 14px;
        line-height: 1.7;
    }

    .empty-order-button {
        margin-top: 27px;
        padding: 13px 20px;
        font-size: 14px;
    }
}
</style>
<div class="container" style="margin-top: 40px; margin-bottom: 80px;">
    <h2 class="mb-4">
        My Orders
    </h2>

    <?php if (empty($orders)): ?>
        <!-- <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
        <a href="../product.php" class="btn btn-primary">
            Start Shopping
        </a> -->


        <section class="empty-order-page"> 
            <div class="empty-order-content"> 
                <div class="empty-order-icon">
                     <i class="bi bi-bag"></i> 
                    </div> 
                <span class="empty-order-label"> MY ORDERS </span> 
                <h1> No orders yet </h1>
                 <p> You haven't placed an order yet. Discover something beautiful and start shopping. </p> 
                 <a href="/tasks/product.php" 
                 class="empty-order-button"> 
                 Continue Shopping <span>→</span> 
                </a> 
            </div>
        </section>

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