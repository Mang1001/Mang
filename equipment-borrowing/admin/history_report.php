<?php
require_once '../includes/db.php';
$history = $conn->query("SELECT br.*, u.name as uname, i.name as iname
    FROM borrow_requests br
    JOIN users u ON br.user_id = u.id
    JOIN items i ON br.item_id = i.id
    ORDER BY br.request_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>รายงานประวัติการยืม/คืน</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>ประวัติการยืม/คืน</h2>
    <table border="1" width="100%" cellpadding="5">
        <tr><th>ผู้ใช้</th><th>อุปกรณ์</th><th>วันที่ยืม</th><th>วันที่คืน</th><th>สถานะ</th></tr>
        <?php while ($row = $history->fetch_assoc()): ?>
        <tr>
            <td><?= $row["uname"] ?></td>
            <td><?= $row["iname"] ?></td>
            <td><?= $row["request_date"] ?></td>
            <td><?= $row["return_date"] ?? '-' ?></td>
            <td><?= $row["status"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</div>
</body>
</html>
