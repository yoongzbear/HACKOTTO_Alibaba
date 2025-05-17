<?php
header("Content-Type: application/json");

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "inventory_db");

// Get product ID from query param
$product_id = intval($_GET['product_id']);

// Fetch monthly sales
$result = $conn->query("
    SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month, SUM(quantity_sold) AS quantity_sold
    FROM sales
    WHERE product_id = $product_id
    GROUP BY month
    ORDER BY month
");

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row['quantity_sold'];
}

// Prepare payload for Qwen Model Studio
$payload = [
    'inputs' => [
        'history_sales' => $salesData,
        'forecast_horizon' => 3 // forecast next 3 months
    ]
];

// Call Model Studio API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://dashscope.aliyuncs.com/api/v1/services/aigc/finetuned-models/YOUR_MODEL_ID/invoke");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer YOUR_API_KEY",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>