<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
    <link rel="stylesheet" href="style.css" />

    <style>
    .dashboard {
      min-height: auto;
      padding: 2rem;
      max-width: 90%;
      margin: 2rem auto;
      /* ← Added vertical margin */
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    #productDropdown{
        width: auto;
        max-width: 300px;
        display: inline-block;
        margin-top: 0.3rem;
    }

    #generateButton {
        width: auto;
        max-width: 200px;
        display: inline-block;
        margin-top: 0.3rem;
    }

    /* Optional: Align dropdown and button on same line */
    .label-input-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .label-input-group label {
        flex: 1 1 100%;
        margin-bottom: 0.3rem;
    }

    .label-input-group select,
    .label-input-group button {
        flex: 1;
        min-width: 150px;
    }

    .text-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.2);
    }

    .text-button:hover {
        background-color: #0056b3;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        transform: translateY(-1px);
    }

    .text-button:active {
        transform: translateY(0);
    }

    .spinner {
        display: inline-block;
        width: 32px;
        height: 32px;
        border: 4px solid #007bff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
        box-sizing: border-box;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    </style>
  
</head>

<body>
    <div class="dashboard">

    <!-- Navigation -->
    <div class="back-bar">
      <a href="index.html" class="back-button">← Back to Dashboard</a>
    </div>

    <div class="label-input-group">
        <label for="productDropdown">Choose a product:</label>
        <select id="productDropdown">
            <option value="">--Select a product--</option>

            <?php
                require_once 'connection.php';
                $sql = "SELECT product_id, product_name FROM products";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['product_id']) . "'>" 
                            . htmlspecialchars($row['product_name']) . 
                            "</option>";
                    }
                } else {
                    echo "<option disabled>No products found</option>";
                }
            ?>
        </select>
        
        <button id="generateButton" class="text-button" title="Generate">Generate</button>
    </div>

    <!-- Inline Loading Message -->
    <div id="loadingMessage" style="display:none; margin-top:1rem; text-align:center;">
        <div class="spinner"></div>
        <p style="margin-top: 0.5rem; font-size:1rem;">Generating...</p>
    </div>

    </div>

    <script>
        const dropdown = document.getElementById("productDropdown");
        const generateButton = document.getElementById("generateButton");
        const loadingMessage = document.getElementById("loadingMessage");

        generateButton.addEventListener("click", function () {
            const selectedProductId = dropdown.value;
            if (selectedProductId) {
                // Disable button during processing
                generateButton.disabled = true;

                // Show inline loading message
                loadingMessage.style.display = "block";

                // Simulate generation delay (replace with real logic)
                setTimeout(() => {
                    // Update URL
                    const newUrl = `?product_id=${encodeURIComponent(selectedProductId)}`;
                    window.history.pushState(null, '', newUrl);

                    // Hide loading message
                    loadingMessage.style.display = "none";
                    generateButton.disabled = false;

                    console.log("Product ID in URL:", selectedProductId);
                }, 1000); // Simulated 1s load time
            }
        });
    </script>

</body>
</html>
