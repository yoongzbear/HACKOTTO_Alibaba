<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
</head>
<body>
    <label for="productDropdown">Choose a product:</label>
    <select id="productDropdown">
        <option value="">--Select a product--</option>

        <?php
        // Fetch data from database
        $conn = new mysqli("localhost", "root", "", "caratretail");
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
    <button id="searchButton">Search</button>

    <script>
        const dropdown = document.getElementById("productDropdown");
        const searchButton = document.getElementById("searchButton");

        searchButton.addEventListener("click", function () {
            const selectedProductId = dropdown.value;
            if (selectedProductId) {
                const newUrl = `${window.location.pathname}?product_id=${encodeURIComponent(selectedProductId)}`;
                window.history.pushState({ path: newUrl }, '', newUrl);
            }
        });
    </script>

</body>
</html>
