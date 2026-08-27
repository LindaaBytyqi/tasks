<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/header.php";
include "includes/database.php";
if (!isset($_SESSION['last_order_id'])) {
    ?>
    <div class="container text-center"
         style="margin-top:150px; margin-bottom:100px;">

        <h2>No order found</h2>

        <p class="text-muted mt-3">
            You have not placed an order yet.
        </p>

        <a href="/tasks/index.php"
           class="btn btn-primary mt-3">

            Continue Shopping
        </a>

    </div>
    <?php
    include "includes/footer.php";
    exit;
}


$order_id = (int) $_SESSION['last_order_id'];
$sql = "SELECT *
        FROM orders
        WHERE id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'order_id' => $order_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    unset($_SESSION['last_order_id']);
    header("Location: index.php");
    exit;
}
$sql = "SELECT
            order_items.quantity,
            order_items.price,
            products.name,
            products.image
        FROM order_items
        JOIN products
            ON products.id = order_items.product_id
        WHERE order_items.order_id = :order_id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'order_id' => $order_id
]);

$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$payment_method = ($order['payment_method'] == '0')
    ? 'Cash on Delivery'
    : 'Online Payment';

?>
<div class="container"
     style="max-width:700px; margin-top:120px; margin-bottom:80px;">
    <div class="text-center mb-4">

        <div style="font-size:55px;">
            ✓
        </div>

        <h1>
            Order Details
        </h1>

        <p class="lead">

            Thank you,
            <?= htmlspecialchars(
                $order['fullname'],
                ENT_QUOTES,
                'UTF-8'
            ); ?>!

        </p>

        <p>
            Order ID:
            <strong>
                #<?= htmlspecialchars(
                    $order['id'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>
            </strong>
        </p>

        <p>

            <strong>
                Order Date & Time:
            </strong>

            <?= date(
                'd M Y, H:i',
                strtotime($order['created_at'])
            ); ?>

        </p>

    </div>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <h4 class="mb-3">
                Customer Information
            </h4>

            <p class="mb-1">

                <strong>
                    Full Name:
                </strong>

                <?= htmlspecialchars(
                    $order['fullname'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

            <p class="mb-1">

                <strong>
                    Email:
                </strong>

                <?= htmlspecialchars(
                    $order['email'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

            <p class="mb-0">

                <strong>
                    Phone:
                </strong>

                <?= htmlspecialchars(
                    $order['phone'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>
        </div>
    </div>
    <div class="card mb-3 shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">
                Shipping Information
            </h4>

            <p class="mb-1">

                <strong>
                    Address:
                </strong>

                <?= htmlspecialchars(
                    $order['address'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

            <p class="mb-1">

                <strong>
                    City:
                </strong>

                <?= htmlspecialchars(
                    $order['city'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

            <p class="mb-0">

                <strong>
                    Zip Code:
                </strong>

                <?= htmlspecialchars(
                    $order['zipcode'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

        </div>

    </div>

    <div class="card mb-3 shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">
                Payment
            </h4>

            <p class="mb-1">

                <strong>
                    Payment Method:
                </strong>

                <?= htmlspecialchars(
                    $payment_method,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </p>

            <p class="mb-0">

                <strong>
                    Order Status:
                </strong>

                <span class="badge bg-warning text-dark">

                    <?= htmlspecialchars(
                        $order['status'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>

                </span>

            </p>
        </div>
    </div>

    <div class="card mb-3 shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">
                Order Items
            </h4>


            <?php foreach ($order_items as $item): ?>

                <div class="d-flex align-items-center justify-content-between border-bottom py-3">

                    <div class="d-flex align-items-center">


                        <?php if (!empty($item['image'])): ?>

                            <img
                                src="images/<?= htmlspecialchars(
                                    $item['image'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                alt="<?= htmlspecialchars(
                                    $item['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                                style="
                                    width:70px;
                                    height:70px;
                                    object-fit:cover;
                                    border-radius:8px;
                                    margin-right:15px;
                                "
                            >
                        <?php endif; ?>
                        <div>
                            <strong>
                                <?= htmlspecialchars(
                                    $item['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </strong>
                            <br>
                            <small class="text-muted">
                                Quantity:
                                <?= htmlspecialchars(
                                    $item['quantity'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </small>
                        </div>
                    </div>
                    <strong>

                        €<?= number_format(
                            $item['price'] * $item['quantity'],
                            2
                        ); ?>

                    </strong>

                </div>

            <?php endforeach; ?>
            <div class="d-flex justify-content-between mt-4">
                <h4>
                    Total:
                </h4>
                <h4 class="text-primary">
                    €<?= number_format(
                        $order['total'],
                        2
                    ); ?>
                </h4>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="/tasks/index.php"
           class="btn btn-primary">
            Continue Shopping
        </a>
    </div>
</div>
<?php
include "includes/footer.php";
?>