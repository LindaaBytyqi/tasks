<?php
include "../includes/database.php";

$sql= "SELECT COUNT(*) FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalusers = $stmt->fetchColumn();

$sql="SELECT COUNT(*) FROM orders ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalorders = $stmt->fetchColumn();

$sql = "SELECT COUNT(*) FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalproducts = $stmt -> fetchColumn();

$sql = "SELECT COUNT(*) FROM newsletter_subscribers";
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalSubscribers = $stmt->fetchColumn();

$sql = "SELECT COALESCE(SUM(total), 0) FROM orders";
$stmt = $conn->prepare($sql);
$stmt->execute();
$totalRevenue = $stmt->fetchColumn();

$sql = "
    SELECT 
        TO_CHAR(created_at, 'Mon') AS month,
        COUNT(*) AS total
    FROM orders
    GROUP BY 
        EXTRACT(MONTH FROM created_at),
        TO_CHAR(created_at, 'Mon')
    ORDER BY EXTRACT(MONTH FROM created_at)
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$ordersByMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

$months = [];
$orderCounts = [];

foreach ($ordersByMonth as $row) {
    $months[] = $row['month'];
    $orderCounts[] = $row['total'];
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">
           <h3>ADMIN DASHBOARD</h3>
        </div>
    </div>
<div class="row g-3 mb-4">

  <div class="col-12 col-sm-6 col-lg">
    <div class="card border border-2 border-secondary-subtle shadow py-5 px-4 rounded-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 51px; height: 51px;">
          <i class="bi bi-people-fill fs-5"></i>
        </div>
        <div>
          <small class="text-muted d-block fw-medium" style="font-size: 1rem;">USERS</small>
          <h4 class="mb-0 fw-bold"><?=$totalusers?></h4>
        </div>
        </div>
    </div>
  </div> 

<div class="col-12 col-sm-6 col-lg">
    <div class="card border border-2 border-secondary-subtle shadow py-5 px-4 rounded-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 51px; height: 51px;">
          <i class="bi bi-box-seam-fill fs-3"></i>
        </div>
        <div>
          <small class="text-muted d-block fw-medium" style="font-size: 1rem;">PRODUCTS</small>
          <h4 class="mb-0 fw-bold"><?=$totalproducts?></h4>
        </div>
        </div>
    </div>
  </div> 

<div class="col-12 col-sm-6 col-lg">
    <div class="card border border-2 border-secondary-subtle shadow py-5 px-4 rounded-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 51px; height: 51px;">
          <i class="bi bi-cart-fill fs-3"></i>
        </div>
        <div>
          <small class="text-muted d-block fw-medium" style="font-size: 1rem;">ORDERS</small>
          <h4 class="mb-0 fw-bold"><?=$totalorders?></h4>
        </div>
        </div>
    </div>
  </div> 

<div class="col-12 col-sm-6 col-lg">
    <div class="card border border-2 border-secondary-subtle shadow py-5 px-4 rounded-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 51px; height: 51px;">
         <i class="bi bi-currency-dollar fs-3"></i>
        </div>
        <div>
          <small class="text-muted d-block fw-medium" style="font-size: 1rem;">TOTAL REVENUE</small>
          <h4 class="mb-0 fw-bold"><?=$totalRevenue?></h4>
        </div>
        </div>
    </div>
  </div> 

<div class="col-12 col-sm-6 col-lg">
    <div class="card border border-2 border-secondary-subtle shadow py-5 px-4 rounded-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 p-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 51px; height: 51px;">
          <i class="bi bi-envelope-fill fs-3"></i>
        </div>
        <div>
          <small class="text-muted d-block fw-medium" style="font-size: 1rem;">NEWSLETTER SUBSCRIPTION</small>
          <h4 class="mb-0 fw-bold"><?=$totalSubscribers?></h4>
        </div>
        </div>
    </div>
  </div> 


</div>
</div>




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const months = <?= json_encode($months) ?>;
const orderCounts = <?= json_encode($orderCounts) ?>;
const ctx = document.getElementById('ordersChart');

new Chart(ctx, {

    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Orders',
                data: orderCounts,
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1

                }
            }
        }
    }
});
</script>

