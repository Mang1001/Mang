<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // ป้องกัน SQL Injection

    // ดึง item_id ของรายการคืนนี้ก่อน
    $res = $conn->query("SELECT item_id FROM borrow_requests WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $item_id = intval($row['item_id']);

        // อัปเดตสถานะ borrow_requests เป็น 'completed' หรือ 'returned_confirmed' (สถานะที่แสดงว่า ยืนยันคืนเรียบร้อย)
        if (!$conn->query("UPDATE borrow_requests SET status = 'completed' WHERE id = $id")) {
            echo "Error updating borrow_requests: " . $conn->error;
            exit;
        }

        // อัปเดตจำนวน available_qty ของ item
        if (!$conn->query("UPDATE items SET available_qty = available_qty + 1 WHERE id = $item_id")) {
            echo "Error updating items: " . $conn->error;
            exit;
        }

        header("Location: confirm_return.php");
        exit;
    } else {
        echo "ไม่พบรายการขอคืน";
        exit;
    }
}

// ดึงรายการที่ status = 'returned' (รอแอดมินยืนยัน) พร้อมรูปภาพ
$returning = $conn->query("SELECT br.*, u.name as uname, i.name as iname, i.image FROM borrow_requests br
                         JOIN users u ON br.user_id = u.id
                         JOIN items i ON br.item_id = i.id
                         WHERE br.status = 'returned'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>ยืนยันการคืน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h3>คำขอคืน</h3>
<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ผู้ใช้</th>
            <th>อุปกรณ์</th>
            <th>รูปภาพ</th>
            <th>วันที่คืน</th>
            <th>ยืนยัน</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $returning->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row["uname"]) ?></td>
        <td><?= htmlspecialchars($row["iname"]) ?></td>
        <td>
            <?php if ($row['image'] && file_exists("item_images/" . $row['image'])): ?>
                <img src="item_images/<?= htmlspecialchars($row['image']) ?>" alt="รูปอุปกรณ์" style="width:80px; height:auto; border-radius:4px;">
            <?php else: ?>
                ไม่มีรูปภาพ
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row["return_date"]) ?></td>
        <td>
            <a href="?id=<?= $row["id"] ?>" class="btn btn-sm btn-warning" 
               onclick="return confirm('ยืนยันการคืนอุปกรณ์นี้ใช่หรือไม่?')">ยืนยันคืน</a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</body>
</html>
