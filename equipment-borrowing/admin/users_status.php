<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}
include '../includes/db.php';

// ดึงรายการการยืม-คืนของผู้ใช้ทุกคน
$sql = "
    SELECT 
        br.id AS borrow_id,
        u.name AS user_name,
        i.name AS item_name,
        br.borrow_date,
        br.return_date,
        br.confirmed_by_admin
    FROM borrow_return br
    JOIN users u ON br.user_id = u.id
    JOIN items i ON br.item_id = i.id
    ORDER BY br.borrow_date DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สถานะการยืมของสมาชิก</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
      body {
        background: #f4f7fa;
        font-family: 'Kanit', sans-serif;
      }
      .container {
        margin-top: 2rem;
      }
      h2 {
        text-align: center;
        color: #343a40;
        margin-bottom: 1.5rem;
      }
      .table thead {
        background-color: #343a40;
        color: #fff;
      }
      .status-borrow { color: #0d6efd; }
      .status-pending { color: #fd7e14; }
      .status-returned { color: #198754; }
      .btn-confirm {
        font-size: 0.9rem;
        padding: 0.25rem 0.75rem;
      }
    </style>
</head>
<body>

<div class="container">
  <h2><i class="bi bi-clipboard-check"></i> สถานะการยืมของสมาชิก</h2>

  <?php if ($result->num_rows > 0): ?>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>ผู้ใช้</th>
          <th>อุปกรณ์</th>
          <th>วันที่ยืม</th>
          <th>วันที่คืน</th>
          <th>สถานะ</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $result->fetch_assoc()): 
        $borrowDate = date('d/m/Y H:i', strtotime($row['borrow_date']));
        $returnDate = $row['return_date'] ? date('d/m/Y H:i', strtotime($row['return_date'])) : '-';
        if (is_null($row["return_date"])) {
          $statusText = '📦 กำลังยืมอยู่';
          $statusClass = 'status-borrow';
        } elseif ($row["confirmed_by_admin"] == 0) {
          $statusText = '⏳ รอแอดมินยืนยัน';
          $statusClass = 'status-pending';
        } else {
          $statusText = '✅ คืนเรียบร้อย';
          $statusClass = 'status-returned';
        }
      ?>
        <tr>
          <td><?= htmlspecialchars($row['user_name']) ?></td>
          <td><?= htmlspecialchars($row['item_name']) ?></td>
          <td><?= $borrowDate ?></td>
          <td><?= $returnDate ?></td>
          <td class="<?= $statusClass ?>"><?= $statusText ?></td>
          <td>
            <?php if (is_null($row["return_date"]) && $row["confirmed_by_admin"] == 0): ?>
              <a href="confirm_return.php?confirm=<?= $row["borrow_id"] ?>"
                 class="btn btn-sm btn-success btn-confirm"
                 onclick="return confirm('ยืนยันการคืนอุปกรณ์นี้?')">
                 <i class="bi bi-check-circle"></i> ยืนยันคืน
              </a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      ยังไม่มีการยืมอุปกรณ์จากสมาชิก
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left-circle"></i> กลับแดชบอร์ด
    </a>
  </div>
</div>

</body>
</html>
