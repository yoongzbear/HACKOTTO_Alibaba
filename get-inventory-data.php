<?php
// get-inventory-data.php
require_once 'connection.php';

$result = $conn->query("
    SELECT p.product_id, p.product_name, i.quantity_in_stock
    FROM products p
    JOIN inventory i ON p.product_id = i.product_id
");

$inventoryData = [];

while ($row = $result->fetch_assoc()) {
    $inventoryData[] = $row;
}

$conn->close();

echo json_encode($inventoryData);
?>