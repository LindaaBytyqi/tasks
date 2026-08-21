<?php
include "admin_auth.php";
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

$revenueLabels = [];
$revenueData = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dayName = date('D', strtotime("-$i days")); 

    $sql = "SELECT COALESCE(SUM(total), 0) FROM orders WHERE DATE(created_at) = :date";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':date' => $date]);
    
    $revenueLabels[] = $dayName;
    $revenueData[] = (float)$stmt->fetchColumn();
}

$sql = "SELECT c.name AS category_name, COUNT(oi.id) AS total_sales
        FROM categories c
        LEFT JOIN products p ON p.category_id = c.id
        LEFT JOIN order_items oi ON oi.product_id = p.id
        GROUP BY c.id, c.name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categoryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryLabels = [];
$categoryData = [];

foreach ($categoryResults as $row) {
    $categoryLabels[] = $row['category_name'];
    $categoryData[] = (int)$row['total_sales'];
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">
           <h3>DASHBOARD OVERVIEW</h3>
        </div>
    </div>
<div class="row g-3 mb-5 pt-4">

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


<div class="row g-4 mb-4" style="margin-top: 80px">
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Total Revenue & Orders</h5>
        <span class="badge bg-light text-dark border">Last 7 Days</span>
      </div>
      <div style="position: relative; height: 500px;">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm p-4 h-100 rounded-3">
      <h5 class="fw-bold mb-3">Sales by Category</h5>
      <div style="position: relative; height: 360px;" class="d-flex justify-content-center">
        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const revenueLabels = <?=json_encode($revenueLabels)?>;
  const revenueData = <?=json_encode($revenueData)?>;
  const categoryLabels = <?=json_encode($categoryLabels)?>;
  const categoryData = <?=json_encode($categoryData)?>;
  const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: revenueLabels,
      datasets: [{
        label: 'Revenue ($)',
        data: revenueData,
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13, 110, 253, 0.08)',
        fill: true,
        tension: 0.4,
        borderWidth: 3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
        x: { grid: { display: false } }
      }
    }
  });

  const ctxCategory = document.getElementById('categoryChart').getContext('2d');
  new Chart(ctxCategory, {
    type: 'doughnut',
    data: {
      labels: categoryLabels,
      datasets: [{
        data: categoryData,
        backgroundColor: [
          '#0d6efd',
          '#dc3545',
          '#ffc107',
          '#198754',
          '#6f42c1',
          '#fd7e14'
        ],
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 12, padding: 15 }
        }
      },
      cutout: '70%'
    }
  });
</script>