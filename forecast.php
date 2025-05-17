<?php
// 1. Connect to MySQL
$conn = new mysqli(
    "rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com",
    "woozi",
    "w@ozi123",
    "caratretail",
    3306
);

$product_id = intval($_GET['product_id']); // e.g., ?product_id=1

// 2. Fetch historical sales for this product
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
echo json_encode(['salesData' => $salesData]);

$conn->close();

// 3. Call Python script for forecasting
try {
    $inputData = json_encode([
        'history_sales' => $salesData,
        'forecast_horizon' => 3
    ]);

    // ✅ Debug log: data being sent to Python
    error_log("Sending to Python: " . $inputData);

    $command = "C:\\Users\\ASUS\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe C:\\wamp64\\www\\HACKOTTO_Alibaba\\testQwen.py";

    $descriptorspec = [
        ['pipe', 'r'],  // stdin
        ['pipe', 'w'],  // stdout
        ['pipe', 'w']   // stderr
    ];

    // 🔁 Define $cwd or remove it if not needed
    $cwd = __DIR__;  // current script directory

    $process = proc_open($command, $descriptorspec, $pipes, $cwd);

    if (is_resource($process)) {
        // ✍️ Send input to Python
        fwrite($pipes[0], $inputData);
        fclose($pipes[0]);  // close stdin pipe after sending data

        // 📤 Read outputs BEFORE closing the process
        $output = isset($pipes[1]) ? stream_get_contents($pipes[1]) : '';
        fclose($pipes[1]);

        $errorOutput = isset($pipes[2]) ? stream_get_contents($pipes[2]) : '';
        fclose($pipes[2]);

        // ⏳ Now close the process AFTER reading output
        $returnValue = proc_close($process);

        // ✅ Log what came back
        error_log("Python STDOUT: " . $output);
        error_log("Python STDERR: " . $errorOutput);

        if (!empty($output)) {
            $result = json_decode($output, true);

            if (isset($result['error'])) {
                echo json_encode(['error' => $result['error']]);
            } else {
                echo $output; // Return raw JSON
            }
        } else {
            echo json_encode([
                'error' => 'Empty response from Python script',
                'debug' => [
                    'input' => $inputData,
                    'stdout' => $output,
                    'stderr' => $errorOutput
                ]
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error executing forecast: ' . $e->getMessage()]);
}