<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// ===== CONFIG =====
$apiKey = "sk-aca8221d88c241d98cd4ad53cda178bf"; // Replace with your real key
$product_id = intval($_POST['product_id'] ?? 0);
$question = $_POST['question'] ?? 'What is the expected sales growth next month?';

// ===== DB Connect =====
$conn = new mysqli("rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com", "woozi", "w@ozi123", "caratretail", 3306);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// ===== Query Data =====
$sql = "
    SELECT DATE_FORMAT(sale_date, '%Y-%m-%d') AS date, 
           SUM(total_price) AS revenue
    FROM sales
    WHERE product_id = $product_id
    GROUP BY date
    ORDER BY date DESC
    LIMIT 30
";
$result = $conn->query($sql);
$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}
$conn->close();

// ===== Prepare Summary =====
$summary = "Revenue trend for Product ID $product_id over last 30 days:\n";
foreach ($salesData as $row) {
    $summary .= "{$row['date']}: \${$row['revenue']}\n";
}

$context = "
You are an expert retail business analyst.

$summary

User's Question: $question
";

// ===== Call Qwen API using cURL =====
$data = [
    "model" => "qwen-plus",
    "input" => [
        "prompt" => $context
    ]
];

$ch = curl_init('https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ← Add this line
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

// Decode and return output
$resJson = json_decode($response, true);
if (!$resJson || !isset($resJson['output']['text'])) {
    echo json_encode(['error' => 'Invalid response from Qwen API: ' . $response]);
    exit;
}

echo json_encode(['answer' => $resJson['output']['text']]);
