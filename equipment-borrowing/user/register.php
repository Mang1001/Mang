<?php
session_start();
include '../includes/db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    } elseif ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจสอบว่าอีเมลซ้ำหรือไม่
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "อีเมลนี้มีอยู่ในระบบแล้ว";
        } else {
            // เข้ารหัสรหัสผ่านและบันทึก
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($insert_stmt->execute()) {
                $success = "สมัครสมาชิกสำเร็จ! ไปที่ <a href='login.php'>เข้าสู่ระบบ</a>";
            } else {
                $error = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
            }
            $insert_stmt->close();
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(-45deg, #1d976c, #93f9b9, #3a7bd5, #3a6073);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
        }
        @keyframes gradient {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        .register-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>
<body>
<div class="d-flex justify-content-center align-items-center h-100">
    <div class="register-container">
        <h2 class="text-center mb-4">สมัครสมาชิก</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <input type="text" name="name" class="form-control" placeholder="ชื่อ-นามสกุล" required>
            </div>
            <div class="form-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="ยืนยันรหัสผ่าน" required>
            </div>
            <button type="submit" class="btn btn-success w-100">สมัครสมาชิก</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
        </div>
    </div>
</div>
</body>
</html>
