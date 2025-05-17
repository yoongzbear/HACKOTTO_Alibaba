<?php
// 1. Connect to MySQL
$conn = new mysqli("localhost", "root", "", "caratretail");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 2. Get product_id from request
$product_id = intval($_GET['product_id']); // e.g., ?product_id=1

// 3. Fetch historical sales for this product
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

$conn->close();

// 4. Call Model Studio Forecasting API
$modelId = 'YOUR_MODEL_ID'; // Replace with your actual model ID
$apiKey = 'YOUR_API_KEY';   // Replace with your actual DASHSCOPE API key

$url = "https://dashscope.aliyuncs.com/api/v1/services/aigc/finetuned-models/{$modelId}/invoke";

$data = [
    "inputs" => [
        "history_sales" => $salesData,
        "forecast_horizon" => 3 // forecast next 3 months
    ]
];

$headers = [
    "Authorization: Bearer {$apiKey}",
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'API request failed: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// 5. Return the response to frontend (JavaScript)
echo $response;
?>