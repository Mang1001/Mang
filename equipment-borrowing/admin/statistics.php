<?php include '../includes/admin_auth.php'; include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สถิติการยืม/คืน</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>กราฟสถิติการยืม/คืน</h2>
    <canvas id="statsChart" width="400" height="200"></canvas>
</div>
<script>
const ctx = document.getElementById('statsChart').getContext('2d');
fetch('get_stats.php')
.then(response => response.json())
.then(data => {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'จำนวนครั้ง',
                data: data.counts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });
});
</script>
<p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</body>
</html>