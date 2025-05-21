<?php
session_start();
if (!isset($_SESSION["user_id"])) exit;
require_once '../includes/db.php';

$item_id = $_GET["item_id"];
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("INSERT INTO borrow_requests (user_id, item_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();

header("Location: dashboard.php");

