<?php
// 1. Connect to MySQL
$conn = new mysqli(
    "rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com", // ← RDS Public Endpoint rm-3nsixe3586321q3dsuo.mysql.rds.aliyuncs.com
    "woozi",                 // ← DB user
    "w@ozi123",                 // ← DB password
    "caratretail",                      // ← Database name
    3306                            // ← Port
);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} else {
    echo "Connected to RDS successfully!";
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

// 4. Call Python script for forecasting
try {
    // Create input data
    $inputData = json_encode([
        'history_sales' => $salesData,
        'forecast_horizon' => 3
    ]);
    
    // Write input data to temporary file
    $tempFile = tempnam(sys_get_temp_dir(), 'qwen_input_');
    file_put_contents($tempFile, $inputData);
    
    // Execute Python script
    $command = "python C:\\wamp64\\www\\HACKOTTO_Alibaba\\testQwen.py";
    
    // Use proc_open for better control
    $descriptorspec = [
        ['pipe', 'r'],  // stdin
        ['pipe', 'w'],  // stdout
        ['pipe', 'w']   // stderr
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Send input data to Python script
        fwrite($pipes[0], $inputData);
        fclose($pipes[0]);
        
        // Read output
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        
        // Read error output
        $errorOutput = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        
        // Close process
        $returnValue = proc_close($process);
        
        // Log error output for debugging
        error_log("Qwen error output: " . $errorOutput);
        
        // Parse and return result
        if (!empty($output)) {
            $result = json_decode($output, true);
            
            if (isset($result['error'])) {
                echo json_encode(['error' => $result['error']]);
            } else {
                echo json_encode([
                    'forecast' => $result['forecast'],
                    'meta' => [
                        'input_data' => $salesData,
                        'forecast_horizon' => 3
                    ]
                ]);
            }
        } else {
            echo json_encode(['error' => 'Empty response from Python script']);
        }
    } else {
        echo json_encode(['error' => 'Failed to start Python process']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error executing forecast: ' . $e->getMessage()]);
}