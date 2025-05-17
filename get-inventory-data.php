<?php
// get-inventory-data.php

$conn = new mysqli(
    "rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com", // ← RDS Public Endpoint rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com
    "woozi",                 // ← DB user
    "w@ozi123",                 // ← DB password
    "caratretail",                      // ← Database name
    3306                                // ← Port
);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    echo "Connected to RDS successfully!";
}

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