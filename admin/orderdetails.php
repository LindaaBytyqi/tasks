<?php
include "../includes/database.php";
$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    header("Location: admindashboard.php?page=orders");
    exit;
}

$sql = "SELECT *
        FROM orders
        WHERE id = :order_id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    'order_id' => $order_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: admindashboard.php?page=orders");
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

$payment_method = $order['payment_method'] == '0'
    ? 'Cash on Delivery'
    : 'Online Payment';

?>
<div class="container-fluid py-3" style="max-width: 1400px; margin: 0 auto;">
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">
            Customer Information
        </h4>

        <p class="mb-1">
            <strong>Full Name:</strong>
            <?= htmlspecialchars($order['fullname']); ?>
        </p>

        <p class="mb-1">
            <strong>Email:</strong>
            <?= htmlspecialchars($order['email']); ?>
        </p>

        <p class="mb-0">
            <strong>Phone:</strong>
            <?= htmlspecialchars($order['phone']); ?>
        </p>
    </div>
</div>



<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">
            Shipping Information
        </h4>

        <p class="mb-1">
            <strong>Address:</strong>
            <?= htmlspecialchars($order['address']); ?>
        </p>

        <p class="mb-1">
            <strong>City:</strong>
            <?= htmlspecialchars($order['city']); ?>
        </p>

        <p class="mb-0">
            <strong>Zip Code:</strong>
            <?= htmlspecialchars($order['zipcode']); ?>
        </p>
    </div>
</div>



<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">
            Purchased Products
        </h4>
        <?php foreach ($order_items as $item): ?>
            <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                <div class="d-flex align-items-center">
                    <?php if (!empty($item['image'])): ?>
                        <img
                            src="../images/<?= htmlspecialchars($item['image']); ?>"
                            alt="<?= htmlspecialchars($item['name']); ?>"
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
                            <?= htmlspecialchars($item['name']); ?>
                        </strong>
                        <br>
                        <small class="text-muted">
                            Quantity:
                            <?= htmlspecialchars($item['quantity']); ?>
                        </small>
                        <br>
                        <small class="text-muted">
                            Price:
                            €<?= number_format($item['price'], 2); ?>
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
                €<?= number_format($order['total'], 2); ?>
            </h4>
        </div>
    </div>
</div>



<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">
            Order Status
        </h4>
        <form action="updateorderstatus.php" method="POST">

    <input 
        type="hidden" 
        name="order_id" 
        value="<?= $order['id']; ?>"
    >

    <select 
        name="status" 
        class="form-select"
    >

        <option 
            value="pending"
            <?= strtolower($order['status']) === 'pending' ? 'selected' : ''; ?>
        >
            Pending
        </option>

        <option 
            value="processing"
            <?= strtolower($order['status']) === 'processing' ? 'selected' : ''; ?>
        >
            Processing
        </option>

        <option 
            value="shipped"
            <?= strtolower($order['status']) === 'shipped' ? 'selected' : ''; ?>
        >
            Shipped
        </option>

        <option 
            value="completed"
            <?= strtolower($order['status']) === 'completed' ? 'selected' : ''; ?>
        >
            Completed
        </option>

        <option 
            value="cancelled"
            <?= strtolower($order['status']) === 'cancelled' ? 'selected' : ''; ?>
        >
            Cancelled
        </option>

    </select>

    <button 
        type="submit" 
        class="btn btn-primary mt-3"
    > 
        <i class="bi bi-arrow-repeat"></i> 
        Update Status 
    </button>

        </form>
    </div>
</div>
</div>




