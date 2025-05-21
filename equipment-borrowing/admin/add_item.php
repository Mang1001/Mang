<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
require_once '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $qty = (int)$_POST["qty"];
    $stmt = $conn->prepare("INSERT INTO items (name, total_qty, available_qty) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $name, $qty, $qty);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>เพิ่มอุปกรณ์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>เพิ่มอุปกรณ์ใหม่</h3>
    <form method="POST">
        <div class="mb-3">
            <label>ชื่ออุปกรณ์</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>จำนวนทั้งหมด</label>
            <input type="number" name="qty" class="form-control" required min="1">
        </div>
        <button class="btn btn-success">บันทึก</button>
        <a href="dashboard.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
</body>
</html>
