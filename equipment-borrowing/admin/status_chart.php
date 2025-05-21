<?php
require_once '../includes/db.php';
$data = $conn->query("SELECT name, total_qty, available_qty FROM items");
$labels = $totals = $avails = [];
while ($row = $data->fetch_assoc()) {
    $labels[] = $row['name'];
    $totals[] = $row['total_qty'];
    $avails[] = $row['available_qty'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>กราฟสถานะครุภัณฑ์</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>กราฟแสดงสถานะครุภัณฑ์</h2>
    <canvas id="statusChart" width="400" height="200"></canvas>
</div>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'จำนวนทั้งหมด',
                data: <?= json_encode($totals) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }, {
                label: 'ว่างให้ยืม',
                data: <?= json_encode($avails) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
 <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</body>
</html>
