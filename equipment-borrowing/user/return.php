<?php
session_start();
if (!isset($_SESSION["user_id"])) exit;
require_once '../includes/db.php';

$request_id = $_GET["id"];
// เปลี่ยนสถานะเป็น "returned"
$stmt = $conn->prepare("UPDATE borrow_requests SET status = 'returned', return_date = NOW() WHERE id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();

header("Location: dashboard.php");
