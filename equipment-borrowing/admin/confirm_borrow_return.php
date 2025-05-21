<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION["user_id"];

// เมื่อผู้ใช้กดยืม
if (isset($_GET["borrow"])) {
    $item_id = $_GET["borrow"];

    // 1. บันทึกใน borrow_return
    $sql = "INSERT INTO borrow_return (user_id, item_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();

    // 2. เปลี่ยนสถานะของอุปกรณ์เป็น borrowed
    $sql2 = "UPDATE items SET status = 'borrowed' WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $item_id);
    $stmt2->execute();

    $stmt->close();
    $stmt2->close();
}

// ดึงรายการอุปกรณ์ที่ยังว่างอยู่
$sql = "SELECT * FROM items WHERE status = 'available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ยืมอุปกรณ์</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>รายการอุปกรณ์ที่สามารถยืมได้</h2>

    <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ชื่ออุปกรณ์</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["name"] ?></td>
                <td>
                    <a href="?borrow=<?= $row["id"] ?>" onclick="return confirm('ยืนยันยืมอุปกรณ์นี้หรือไม่?')">ยืม</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>ไม่มีอุปกรณ์ว่างให้ยืมในขณะนี้</p>
    <?php endif; ?>

    <p>
        
        <a href="dashboard.php">← หน้าหลัก</a>
    </p>
</body>
</html>
