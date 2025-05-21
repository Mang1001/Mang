<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แผงควบคุมแอดมิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #1a1a1a;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #111;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.6);
        }

        header h1 {
            margin: 0;
            color: #00ffcc;
        }

        nav {
            background-color: #222;
            padding: 15px;
            box-shadow: inset 0 -1px 0 #333;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        nav ul li {
            display: inline;
        }

        nav a {
            text-decoration: none;
            color: #00ffcc;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        nav a:hover {
            background-color: #00ffcc;
            color: #000;
        }

        section {
            max-width: 800px;
            margin: 40px auto;
            background-color:rgb(56, 53, 53);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(102, 198, 179, 0.1);
        }

        section h2 {
            color: #00ffcc;
            margin-bottom: 20px;
        }

        section ul {
            list-style: none;
            padding-left: 0;
        }

        section ul li {
            padding: 10px 0;
            border-bottom: 1px solid #444;
        }
    </style>
<head>
    <title>แผงควบคุมผู้ดูแลระบบ</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>ยินดีต้อนรับแอดมิน <?= $_SESSION["admin_name"] ?></h2>
    <ul>
        <li><a href="manage_items.php">จัดการอุปกรณ์</a></li>
        <li><a href="confirm_borrow.php">ยืนยันการยืม</a></li>
        <li><a href="confirm_return.php">ยืนยันการคืน</a></li>
        <li><a href="manage_users.php">ดูรายชื่อผู้ใช้งาน</a></li>
        <li><a href="history_report.php">รายงานประวัติการยืม/คืน</a></li>
        <li><a href="notifications.php">แจ้งเตือนคำขอยืม</a></li>
        <li><a href="status_chart.php">กราฟสถานะครุภัณฑ์</a></li>
        <li><a href="../logout.php">ออกจากระบบ</a></li>
    </ul>
</div>
</body>
</html>
