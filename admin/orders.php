<?php

include "../includes/database.php";
$search = trim($_GET['search'] ?? '');

$sql = "SELECT *
        FROM orders";

if ($search !== '') {
    $sql .= " WHERE
                CAST(id AS TEXT) ILIKE :search
                OR fullname ILIKE :search
                OR email ILIKE :search
                OR status ILIKE :search";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);

if ($search !== '') {
    $stmt->execute([
        ':search' => '%' . $search . '%'
    ]);
} else {
    $stmt->execute();
}
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Orders</h2>
    </div>
    <form method="GET" action="admindashboard.php" class="mb-4">
        <input type="hidden" name="page" value="orders">

        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by order ID, customer, email or status..."
                value="<?= htmlspecialchars($search); ?>"
            >
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                Search
            </button>
            <?php if ($search !== ''): ?>
                <a
                    href="admindashboard.php?page=orders"
                    class="btn btn-secondary"
                >
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No orders found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <strong>
                                    #<?= htmlspecialchars($order['id']); ?>
                                </strong>
                            </td>
                            <td>
                                <?= htmlspecialchars($order['fullname']); ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($order['email']); ?>
                            </td>
                            <td>
                                <?= date(
                                    'd M Y, H:i',
                                    strtotime($order['created_at'])
                                ); ?>
                            </td>
                            <td>
                                €<?= number_format($order['total'], 2); ?>
                            </td>
                            <td>
                                <?php if ($order['status'] === 'pending'): ?>

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                <?php elseif ($order['status'] === 'processing'): ?>

                                    <span class="badge bg-primary">
                                        Processing
                                    </span>
                                <?php elseif ($order['status'] === 'shipped'): ?>

                                    <span class="badge bg-info">
                                        Shipped
                                    </span>
                                <?php elseif ($order['status'] === 'completed'): ?>

                                    <span class="badge bg-success">
                                        Completed
                                    </span>
                                <?php elseif ($order['status'] === 'cancelled'): ?>

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>
                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($order['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a
                                    href="admindashboard.php?page=orderdetails&id=<?= $order['id']; ?>"
                                    class="btn btn-primary btn-sm"
                                >
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>