<?php 
include '../includes/auth.php'; 
include '../includes/db.php'; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ยืม / คืน ครุภัณฑ์</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #23a6d5, #23d5ab);
      font-family: 'Kanit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      max-width: 400px;
      width: 100%;
    }
    .card-header {
      background: #fff;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      text-align: center;
      padding: 1.5rem;
    }
    .card-header h2 {
      margin: 0;
      color: #23a6d5;
      font-size: 1.75rem;
    }
    .card-body {
      background: #fff;
      padding: 2rem;
      border-bottom-left-radius: 1rem;
      border-bottom-right-radius: 1rem;
    }
    .form-select, .btn {
      border-radius: .5rem;
    }
    .btn-borrow {
      width: 48%;
    }
    .btn-return {
      width: 48%;
    }
    .btn + .btn {
      margin-left: 4%;
    }
    a.back-links {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: #fff;
      text-decoration: none;
      font-size: .9rem;
      transition: color .2s;
    }
    a.back-links:hover {
      color: #f0f0f0;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="card-header">
      <h2><i class="bi bi-box-seam"></i> ยืม / คืน ครุภัณฑ์</h2>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="mb-4">
          <label for="item_id" class="form-label">เลือกครุภัณฑ์</label>
          <select id="item_id" name="item_id" class="form-select" required>
            <option value="">-- กรุณาเลือก --</option>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM items");
            while ($item = mysqli_fetch_assoc($res)) {
                echo "<option value='{$item['id']}'>{$item['name']} (คงเหลือ: {$item['quantity']})</option>";
            }
            ?>
          </select>
        </div>
        <div class="d-flex">
          <button name="action" value="borrow" class="btn btn-primary btn-borrow">
            <i class="bi bi-arrow-down-circle"></i> ยืม
          </button>
          <button name="action" value="return" class="btn btn-warning btn-return">
            <i class="bi bi-arrow-up-circle"></i> คืน
          </button>
        </div>
      </form>

      <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): 
        $user_id = $_SESSION['user_id'];
        $item_id = intval($_POST['item_id']);
        $action  = $_POST['action'];
        $date    = date('Y-m-d H:i:s');

        if ($action === 'borrow') {
          mysqli_query($conn, "INSERT INTO transactions (user_id, item_id, action, date) VALUES ($user_id, $item_id, 'borrow', '$date')");
          mysqli_query($conn, "UPDATE items SET quantity = quantity - 1 WHERE id = $item_id");
          $msg = '📥 ทำการยืมเรียบร้อย';
        } else {
          mysqli_query($conn, "INSERT INTO transactions (user_id, item_id, action, date) VALUES ($user_id, $item_id, 'return', '$date')");
          mysqli_query($conn, "UPDATE items SET quantity = quantity + 1 WHERE id = $item_id");
          $msg = '📤 คืนครุภัณฑ์เรียบร้อย';
        }
      ?>
        <div class="alert alert-success text-center mt-4 mb-0">
          <?= htmlspecialchars($msg) ?>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <a href="dashboard.php" class="back-links">
    <i class="bi bi-arrow-left-circle"></i> กลับหน้าหลัก
  </a>
<p><a href="dashboard.php">← กลับแดชบอร์ด</a></p>
</body>
</html>
