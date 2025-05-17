<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Inventory Optimization Dashboard</title>
    <link rel="stylesheet" href="style.css" />

        <style>
        .dashboard {
            min-height: auto;
            padding: 2rem;
            max-width: 900px;
            margin: 2rem auto; /* ← Added vertical margin */
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        </style>

    </head>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const filterInput = document.getElementById("filterInput");
            const productList = document.getElementById("productList");
            const noResultMessage = document.getElementById("noResultMessage");

            filterInput.addEventListener("input", function () {
                const filterValue = filterInput.value.toLowerCase();

                const items = productList.getElementsByTagName("li");
                let visibleCount = 0;

                Array.from(items).forEach(function (item) {
                    const productName = item.querySelector("strong").textContent.toLowerCase();
                    if (productName.includes(filterValue)) {
                        item.style.display = "";
                        visibleCount++;
                    } else {
                        item.style.display = "none";
                    }
                });
                // Show or hide the "no result" message
                if (filterValue.length > 0 && visibleCount === 0) {
                    noResultMessage.style.display = "block";
                } else {
                    noResultMessage.style.display = "none";
                }
            });
        });
    </script>
    <body>
        <div class="dashboard">
            <div class="back-bar">
                <a href="index.html" class="back-button">← Back to Dashboard</a>
            </div>
            <h1>Inventory Optimization Dashboard</h1>

            <!-- Filter Input -->
            <label for="filterInput">Filter Products:</label>
            <input type="text" id="filterInput" placeholder="Search product..." />
            <p id="noResultMessage" style="display: none; color: red;">No product found</p>
               
        <!-- Product List -->
        <?php
            require_once 'connection.php';
            // Query to fetch all product details
            $sql2 = "SELECT products.product_name, products.price, inventory.quantity_in_stock FROM products JOIN inventory ON products.product_id = inventory.product_id;";
            $result2 = $conn->query($sql2);

            if ($result2->num_rows > 0) {
                echo "<ul id=\"productList\">";
                while ($row = $result2->fetch_assoc()) {
                    echo "<li>";
                        echo "<strong>" . htmlspecialchars($row['product_name']) . " (Stock: " . htmlspecialchars($row['quantity_in_stock']) . ")</strong>";
                    echo "</li><br>";
                }
                echo "</ul>";
            } else {
                echo "<p>No products found.</p>";
            }
        ?>

        <!-- Product Selection -->
        <label for="productSelect">Select a Product:</label>
        <select id="productSelect">
            <option value="">--Select a product--</option>

            <?php

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

        <!-- Restock Info -->
        <div id="restockInfo" class="restock-info"></div>

        <!-- Order Button -->
        <button id="orderButton">Order</button>
    </div>
</body>
</html>