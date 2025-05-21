<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION["user_id"];

// ดึงประวัติการยืม-คืนทั้งหมดของผู้ใช้
$sql = "
    SELECT 
        i.name AS item_name, 
        br.borrow_date, 
        br.return_date,
        br.confirmed_by_admin
    FROM borrow_return br
    JOIN items i ON br.item_id = i.id
    WHERE br.user_id = ?
    ORDER BY br.borrow_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>สถานะการยืม</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>ประวัติการยืม/คืนของคุณ</h2>

    <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>อุปกรณ์</th>
                <th>วันที่ยืม</th>
                <th>วันที่คืน</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["item_name"] ?></td>
                <td><?= $row["borrow_date"] ?></td>
                <td><?= $row["return_date"] ?? '-' ?></td>
                <td>
                    <?php
                        if (is_null($row["return_date"])) {
                            echo "📦 กำลังยืมอยู่";
                        } elseif ($row["confirmed_by_admin"] == 0) {
                            echo "⏳ รอแอดมินยืนยันการคืน";
                        } else {
                            echo "✅ คืนเรียบร้อย";
                        }
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>คุณยังไม่มีประวัติการยืมอุปกรณ์</p>
    <?php endif; ?>

    <p><a href="dashboard.php">← กลับหน้าหลัก</a></p>
</body>
</html>
