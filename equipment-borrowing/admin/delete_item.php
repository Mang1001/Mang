<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // ดึงชื่อไฟล์รูปภาพก่อนลบ
    $res = $conn->query("SELECT image FROM items WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $image = $row['image'];

        // ลบข้อมูลจากฐานข้อมูล
        if ($conn->query("DELETE FROM items WHERE id = $id")) {
            // ลบไฟล์รูปภาพถ้ามี
            if ($image && file_exists("item_images/" . $image)) {
                unlink("item_images/" . $image);
            }
            header("Location: manage_items.php?msg=deleted");
            exit();
        } else {
            echo "ลบข้อมูลไม่สำเร็จ: " . $conn->error;
        }
    } else {
        echo "ไม่พบข้อมูลอุปกรณ์นี้";
    }
} else {
    echo "ไม่พบ ID ที่จะลบ";
}
?>
