<?php include '../includes/admin_auth.php'; include '../includes/db.php';
header('Content-Type: application/json');
$data = [];
$labels = [];
$counts = [];
$res = mysqli_query($conn, "SELECT name, (SELECT COUNT(*) FROM transactions WHERE transactions.item_id = items.id) AS total FROM items");
while ($row = mysqli_fetch_assoc($res)) {
    $labels[] = $row['name'];
    $counts[] = $row['total'];
}
echo json_encode(['labels' => $labels, 'counts' => $counts]);
?>