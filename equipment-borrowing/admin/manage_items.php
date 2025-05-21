<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$edit_mode = false;
$item = null;

// เช็คว่ากำลังแก้ไขหรือเพิ่มใหม่
if (isset($_GET['id'])) {
    $edit_mode = true;
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM items WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $item = $res->fetch_assoc();
    } else {
        header("Location: manage_items.php");
        exit();
    }
}

// ฟังก์ชันอัปโหลดรูป
function uploadImage($file, $oldFile = null) {
    $allowed_ext = ['jpg','jpeg','png','gif'];
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
    $uploadDir = "item_images/";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fileTmp = $file['tmp_name'];
    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fileTmp);
    finfo_close($finfo);

    if (!in_array($fileExt, $allowed_ext) || !in_array($mime, $allowed_mimes)) {
        return [false, "ไฟล์ภาพต้องเป็น JPG, JPEG, PNG หรือ GIF เท่านั้น"];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return [false, "ขนาดไฟล์ต้องไม่เกิน 2MB"];
    }

    $newFileName = uniqid('item_') . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $uploadPath)) {
        if ($oldFile && file_exists($uploadDir . $oldFile)) {
            unlink($uploadDir . $oldFile);
        }
        return [true, $newFileName];
    } else {
        return [false, "อัปโหลดไฟล์ไม่สำเร็จ"];
    }
}


// เพิ่มอุปกรณ์ใหม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $total_qty = intval($_POST['total_qty']);
    $available_qty = $total_qty;

    if ($edit_mode) {
        // แก้ไข
        $id = intval($_POST['id']);
        $available_qty = intval($_POST['available_qty']);

        // อัปโหลดรูปถ้ามี
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            list($success, $result) = uploadImage($_FILES['image'], $_POST['old_image']);
            if ($success) {
                $image_name = $result;
            } else {
                $msg = $result;
            }
        } else {
            $image_name = $_POST['old_image'];
        }

        if ($msg == "") {
            $sql = "UPDATE items SET name='$name', total_qty=$total_qty, available_qty=$available_qty, image=" . ($image_name ? "'$image_name'" : "NULL") . " WHERE id=$id";
            if ($conn->query($sql)) {
                $msg = "แก้ไขอุปกรณ์เรียบร้อยแล้ว";
                header("Location: manage_items.php");
                exit();
            } else {
                $msg = "เกิดข้อผิดพลาด: " . $conn->error;
            }
        }

    } else {
        // เพิ่มใหม่ + อัปโหลดรูป
        $image_name = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            list($success, $result) = uploadImage($_FILES['image']);
            if ($success) {
                $image_name = $result;
            } else {
                $msg = $result;
            }
        }

        if ($msg == "") {
            $sql = "INSERT INTO items (name, total_qty, available_qty, image) VALUES ('$name', $total_qty, $available_qty, " . ($image_name ? "'$image_name'" : "NULL") . ")";
            if ($conn->query($sql)) {
                $msg = "เพิ่มอุปกรณ์เรียบร้อยแล้ว";
            } else {
                $msg = "เกิดข้อผิดพลาด: " . $conn->error;
            }
        }
    }
}

// ดึงรายการอุปกรณ์ทั้งหมด
$result = $conn->query("SELECT * FROM items ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>จัดการอุปกรณ์</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2><?= $edit_mode ? "แก้ไขอุปกรณ์" : "เพิ่มอุปกรณ์ใหม่" ?></h2>

    <?php if($msg): ?>
        <p><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form action="manage_items.php<?= $edit_mode ? "?id=".$item['id'] : "" ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_mode ? $item['id'] : '' ?>">
        <input type="hidden" name="old_image" value="<?= $edit_mode ? htmlspecialchars($item['image']) : '' ?>">

        <label>ชื่ออุปกรณ์:</label><br>
        <input type="text" name="name" required value="<?= $edit_mode ? htmlspecialchars($item['name']) : '' ?>"><br>

        <label>จำนวนทั้งหมด:</label><br>
        <input type="number" name="total_qty" min="1" required value="<?= $edit_mode ? $item['total_qty'] : '' ?>"><br>

        <?php if ($edit_mode): ?>
            <label>จำนวนที่ว่าง:</label><br>
            <input type="number" name="available_qty" min="0" max="<?= $edit_mode ? $item['total_qty'] : '' ?>" required value="<?= $edit_mode ? $item['available_qty'] : '' ?>"><br>
        <?php endif; ?>

        <label>รูปภาพอุปกรณ์ (jpg, png, gif สูงสุด 2MB):</label><br>
        <?php if ($edit_mode && $item['image']): ?>
            <img src="item_images/<?= htmlspecialchars($item['image']) ?>" alt="รูปภาพ" style="width:150px; height:auto; margin-bottom:10px;"><br>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit"><?= $edit_mode ? "บันทึกการแก้ไข" : "เพิ่มอุปกรณ์" ?></button>
        <?php if ($edit_mode): ?>
            <a href="manage_items.php" style="margin-left: 10px;">ยกเลิก</a>
        <?php endif; ?>
    </form>

    <hr>

    <h3>รายการอุปกรณ์ทั้งหมด</h3>
    <table>
        <thead>
            <tr>
                <th>รูปภาพ</th>
                <th>ชื่อ</th>
                <th>จำนวนทั้งหมด</th>
                <th>จำนวนว่าง</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if ($row['image']): ?>
                        <img src="item_images/<?= htmlspecialchars($row['image']) ?>" alt="รูปภาพ" style="width:100px; height:auto;">
                    <?php else: ?>
                        ไม่มีรูปภาพ
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['total_qty'] ?></td>
                <td><?= $row['available_qty'] ?></td>
                <td>
                    <a href="manage_items.php?id=<?= $row['id'] ?>">แก้ไข</a> | 
                    <a href="delete_item.php?id=<?= $row['id'] ?>" onclick="return confirm('ยืนยันการลบอุปกรณ์นี้?');">ลบ</a>
                    
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</div>
</body>
</html>
