<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION["admin_id"])) exit;

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("UPDATE borrow_requests SET status = 'approved', confirm_date = NOW() WHERE id = $id");
    $conn->query("UPDATE items SET available_qty = available_qty - 1 WHERE id = (SELECT item_id FROM borrow_requests WHERE id = $id)");
    header("Location: confirm_borrow.php");
    exit;
}

$pending = $conn->query("SELECT br.*, u.name as uname, i.name as iname FROM borrow_requests br
                         JOIN users u ON br.user_id = u.id
                         JOIN items i ON br.item_id = i.id
                         WHERE br.status = 'pending'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>ยืนยันการยืม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h3>คำขอยืม</h3>
<table class="table">
    <tr><th>ผู้ใช้</th><th>อุปกรณ์</th><th>วันที่ขอ</th><th>ยืนยัน</th></tr>
    <?php while ($row = $pending->fetch_assoc()): ?>
    <tr>
        <td><?= $row["uname"] ?></td>
        <td><?= $row["iname"] ?></td>
        <td><?= $row["request_date"] ?></td>
        <td><a href="?id=<?= $row["id"] ?>" class="btn btn-sm btn-success">ยืนยัน</a></td>
    </tr>
    <?php endwhile; ?>
    <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</table>
</body>
</html>
