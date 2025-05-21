<?php
require_once '../includes/db.php';
$pending = $conn->query("SELECT br.*, u.name as uname, i.name as iname
    FROM borrow_requests br
    JOIN users u ON br.user_id = u.id
    JOIN items i ON br.item_id = i.id
    WHERE br.status = 'pending'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>แจ้งเตือนคำขอยืม</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>รายการคำขอยืมที่รอการอนุมัติ</h2>
    <?php if ($pending->num_rows == 0): ?>
        <p>ไม่มีรายการใหม่</p>
    <?php else: ?>
        <ul>
        <?php while ($row = $pending->fetch_assoc()): ?>
            <li><?= $row["uname"] ?> ขอยืม <?= $row["iname"] ?> (<?= $row["request_date"] ?>)</li>
        <?php endwhile; ?>
        </ul>
    <?php endif; ?>
     <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>

</div>
</body>
</html>
