<?php
include '../includes/admin_auth.php';
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4">รายการสมาชิกทั้งหมด</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>ชื่อ</th>
                <th>อีเมล</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
            $i = 1;

            if (isset($_GET['delete'])) {
                $id = (int)$_GET['delete'];
                mysqli_query($conn, "DELETE FROM users WHERE id = $id");
                echo "<script>window.location.href='manage_users.php';</script>";
            }

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <a href='?delete={$row['id']}' onclick=\"return confirm('ยืนยันการลบผู้ใช้นี้?')\" class='btn btn-danger btn-sm'>ลบ</a>
                        </td>
                      </tr>";
                $i++;
            }
            ?>
        </tbody>
    </table>
    <p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</div>
</body>
</html>
