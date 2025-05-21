<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    // หากยังไม่ได้ล็อกอินในฐานะแอดมิน
    header("Location: login.php");
    exit();
}
