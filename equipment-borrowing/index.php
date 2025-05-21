<?php session_start(); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
        }

        .text-center a {
            color:rgb(3, 0, 4);
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 bg-white p-4 rounded shadow">
                <h3 class="text-center mb-4">เลือกประเภทผู้ใช้งาน</h3>

                <!-- ลิงก์สำหรับเข้าสู่ระบบแยกประเภท -->
                <div class="text-center">
                    <a href="user/login.php?role=student" class="btn btn-primary w-100 mb-2">เข้าสู่ระบบ นักเรียน</a>
                    <a href="admin/login.php?role=admin" class="btn btn-danger w-100 mb-2">เข้าสู่ระบบ แอดมิน</a>
                </div>

                <p class="text-center mt-3">
                    ยังไม่มีบัญชี? <a href="user/register.php">สมัครสมาชิก</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
