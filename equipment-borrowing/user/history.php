<?php include '../includes/auth.php'; include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติการยืม/คืน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>ประวัติการยืม/คืนของคุณ</h2>
    <table class="table">
        <thead><tr><th>ครุภัณฑ์</th><th>การกระทำ</th><th>วันที่</th></tr></thead>
        <tbody>
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT items.name, transactions.action, transactions.date 
                FROM transactions JOIN items ON transactions.item_id = items.id 
                WHERE transactions.user_id = $user_id ORDER BY transactions.date DESC";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr><td>{$row['name']}</td><td>{$row['action']}</td><td>{$row['date']}</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</div>
</body>
</html>