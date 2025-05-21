<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/db.php';

$user_id = $_SESSION["user_id"];
$items = $conn->query("SELECT * FROM items");
$requests = $conn->query("SELECT br.*, i.name, i.image FROM borrow_requests br 
                          JOIN items i ON br.item_id = i.id
                          WHERE br.user_id = $user_id ORDER BY br.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        img.item-thumb {
            width: 100px;
            height: auto;
            border-radius: 6px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h4>สวัสดี <?= htmlspecialchars($_SESSION["user_name"]) ?></h4>

    <h5 class="mt-4">รายการอุปกรณ์</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รูป</th>
                <th>ชื่ออุปกรณ์</th>
                <th>พร้อมให้ยืม</th>
                <th>ยืม</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($item["image"]): ?>
                    <img src="../admin/item_images/<?= htmlspecialchars($item["image"]) ?>" alt="รูปภาพ" class="item-thumb">
                <?php else: ?>
                    ไม่มีรูป
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($item["name"]) ?></td>
            <td><?= $item["available_qty"] ?></td>
            <td>
                <a href="borrow.php?item_id=<?= $item["id"] ?>" class="btn btn-sm btn-primary" 
                   <?= $item["available_qty"] > 0 ? "" : "disabled" ?>>ยืม</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h5 class="mt-5">ประวัติการยืม/คืน</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>รูป</th>
                <th>อุปกรณ์</th>
                <th>สถานะ</th>
                <th>ขอยืม</th>
                <th>คืน</th>
                <th>คืนของ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $requests->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if ($row["image"]): ?>
                    <img src="../admin/item_images/<?= htmlspecialchars($row["image"]) ?>" alt="รูปภาพ" class="item-thumb">
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["status"]) ?></td>
            <td><?= $row["request_date"] ?></td>
            <td><?= $row["return_date"] ?: '-' ?></td>
            <td>
                <?php if ($row["status"] == "approved"): ?>
                    <a href="return.php?id=<?= $row["id"] ?>" class="btn btn-sm btn-warning">ส่งคืน</a>
                <?php else: ?> -
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="../logout.php" class="btn btn-outline-danger mt-3">ออกจากระบบ</a>
</div>
</body>
</html>
