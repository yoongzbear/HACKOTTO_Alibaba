<?php
// get-sales-data.php — now connects to Apsara RDS

require_once 'connection.php';

$product_id = intval($_GET['product_id']) ?: 1;
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;

$query = "
    SELECT DATE_FORMAT(sale_date, '%Y-%m-%d') AS date, SUM(quantity_sold) AS quantity_sold
    FROM sales
    WHERE product_id = $product_id
";

if ($start_date) {
    $query .= " AND sale_date >= '$start_date'";
}
if ($end_date) {
    $query .= " AND sale_date <= '$end_date'";
}

$query .= " GROUP BY sale_date ORDER BY sale_date";

$result = $conn->query($query);

$salesData = [];
$labels = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $salesData[] = $row['quantity_sold'];
}

$conn->close();

echo json_encode([
    'labels' => $labels,
    'data' => $salesData
]);
?>