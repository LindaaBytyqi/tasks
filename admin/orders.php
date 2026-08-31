<?php
include "admin_auth.php";
include "../includes/database.php";

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM orders where 1=1";

$params = [];

if ($search !== '') { 
    $sql .= " AND (
                CAST(id AS TEXT) ILIKE :search 
                OR fullname ILIKE :search 
                OR email ILIKE :search 
                OR status ILIKE :search
              )";

    $params[':search'] = '%' . $search . '%';
}

if ($status !== '') {
    $sql .= " AND status = :status";
    $params[':status'] = $status;
}
 
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql); 
$stmt->execute($params);


$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_sql = "SELECT status, COUNT(*) AS total
              FROM orders
              GROUP BY status";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->execute();
$status_counts = [];

while ($row = $count_stmt->fetch(PDO::FETCH_ASSOC)) {
    $status_counts[$row['status']] = $row['total'];
}
$total_orders = array_sum($status_counts);

?>


<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Orders</h2>
    </div>
    <form method="GET" action="admindashboard.php" class="mb-4">
    <input type="hidden" name="page" value="orders">

    <div class="d-flex gap-2">
        <div style="flex: 0 0 65%; position: relative;">

    <input
        type="text"
        name="search"
        id="searchBox"
        class="form-control pe-5"
        placeholder="Search by order ID, customer or email..."
        value="<?= htmlspecialchars($search); ?>"
    >

    <?php if ($search !== ''): ?>
        <a
            href="admindashboard.php?page=orders"
            class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
            title="Clear search"
        >
            <i class="bi bi-x-lg"></i>
        </a>
    <?php endif; ?>
        </div>

        <div style="flex: 0 0 calc(35% - 0.5rem);">

            <select
                name="status"
                id="statusFilter"
                class="form-select"
            >
                <option value="">
                    All Status (<?= $total_orders; ?>)
                </option>

                <option value="pending"
                    <?= $status === 'pending' ? 'selected' : ''; ?>>
                    Pending (<?= $status_counts['pending'] ?? 0; ?>)
                </option>

                <option value="processing"
                    <?= $status === 'processing' ? 'selected' : ''; ?>>
                    Processing (<?= $status_counts['processing'] ?? 0; ?>)
                </option>

                <option value="shipped"
                    <?= $status === 'shipped' ? 'selected' : ''; ?>>
                    Shipped (<?= $status_counts['shipped'] ?? 0; ?>)
                </option>

                <option value="completed"
                    <?= $status === 'completed' ? 'selected' : ''; ?>>
                    Completed (<?= $status_counts['completed'] ?? 0; ?>)
                </option>

                <option value="cancelled"
                    <?= $status === 'cancelled' ? 'selected' : ''; ?>>
                    Cancelled (<?= $status_counts['cancelled'] ?? 0; ?>)
                </option>

            </select>

        </div>

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

<script>
const searchInput = document.getElementById('searchBox');
const statusFilter = document.getElementById('statusFilter');

searchInput.addEventListener('input', function () {
    const search = this.value.trim();
    if (search.length >= 3 || search.length === 0) {

        const url = new URL(window.location.href);

        url.searchParams.set('page', 'orders');
        url.searchParams.set('search', search);
        url.searchParams.set('status', statusFilter.value);

        window.location.href = url.toString();
    }
});

statusFilter.addEventListener('change', function () {
    const url = new URL(window.location.href);

    url.searchParams.set('page', 'orders');
    url.searchParams.set('search', searchInput.value.trim());
    url.searchParams.set('status', this.value);

    window.location.href = url.toString();
});

</script>