<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Inventory Optimization Dashboard</title>
    <link rel="stylesheet" href="style.css" />

    <style>
        /* General Dashboard Styles */
        .dashboard {
            min-height: auto;
            padding: 2rem;
            max-width: 900px;
            margin: 2rem auto; /* Added vertical margin */
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .restock-label {
            color: #b30000; /* red */
            font-weight: bold;
            background-color: #ffe5e5; /* Optional light red background */
            padding: 2px 6px;
            margin-left: 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        input[type="text"],
        select,
        .product-list-wrapper {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            background-color: #f9f9f9;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        #orderQuantity {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Success message style */
        #orderStatusMessage {
            display: none;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="back-bar">
            <a href="index.html" class="back-button">← Back to Dashboard</a>
        </div>
        <h1>Inventory Optimization Dashboard</h1>

        <!-- Filter Input -->
        <label for="filterInput">Filter Products:</label>
        <div>
            <input type="text" id="filterInput" placeholder="Search product..." />
        </div>
        <p id="noResultMessage" style="display: none; color: red;">No product found</p>

        <!-- Product List -->
        <?php
        require_once 'connection.php';
        // Query to fetch all product details
        $sql2 = "SELECT products.product_name, products.price, inventory.quantity_in_stock, products.product_id FROM products JOIN inventory ON products.product_id = inventory.product_id;";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
            echo "<div class='product-list-wrapper'><ul id='productList'>";
            while ($row = $result2->fetch_assoc()) {
                $stock = (int)$row['quantity_in_stock'];
                $needsRestock = $stock < 150;

                $icon = $needsRestock
                    ? '<span class="restock-label">🚨 Restock Needed</span>'
                    : '';

                $productInfo = "<strong>" . htmlspecialchars($row['product_name']) . " (Stock: " . htmlspecialchars($stock) . ")</strong>";

                echo "<li data-product-id='" . htmlspecialchars($row['product_id']) . "'>$productInfo $icon</li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p>No products found.</p>";
        }
        ?>

        <!-- Product Selection -->
        <label for="productSelect">Select a Product:</label>
        <select id="productSelect">
            <option value="">--Select a product--</option>

            <?php
            require_once 'connection.php';
            // Query to fetch all product details
            $sql2 = "SELECT products.product_name, products.price, inventory.quantity_in_stock, products.product_id FROM products JOIN inventory ON products.product_id = inventory.product_id;";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row2['product_id']) . "' 
                            data-stock='" . htmlspecialchars($row2['quantity_in_stock']) . "'>" 
                        . htmlspecialchars($row2['product_name']) . 
                        "</option>";
                }
            } else {
                echo "<option disabled>No products found</option>";
            }
            ?>
        </select>

        <!-- Restock Info -->
        <div id="restockInfo" class="restock-info">The restock inforrmation will be provided once a product is selected.</div>

        <!-- Order Button -->
        <button id="orderButton">Order</button>
    </div>

    <!-- Modal for Order Quantity -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <h2>Enter Order Quantity</h2>
            <label for="orderQuantity">Order Quantity:</label>
            <input type="number" id="orderQuantity" style="width:95%; height: 20px;" min="1" placeholder="Enter quantity">
            <button id="confirmOrder">Confirm</button>
            <button id="cancelOrder">Cancel</button>
        </div>
    </div>

    <!-- Success Message Modal -->
    <div id="orderStatusModal" class="modal">
        <div class="modal-content">
            <p>Your order request has been sent to the department!</p>
            <button id="closeOrderStatusModal">Close</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Filter functionality
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

            // Modal and Order Logic
            const orderButton = document.getElementById("orderButton");
            const orderModal = document.getElementById("orderModal");
            const confirmOrder = document.getElementById("confirmOrder");
            const cancelOrder = document.getElementById("cancelOrder");
            const orderQuantityInput = document.getElementById("orderQuantity");
            const orderStatusModal = document.getElementById("orderStatusModal");
            const closeOrderStatusModal = document.getElementById("closeOrderStatusModal");
            const productSelect = document.getElementById("productSelect");
            const restockInfo = document.getElementById("restockInfo");
            const MIN_STOCK = 150;  // Set the minimum stock threshold

            let selectedProduct = null;

            // Handle product selection and restock info display
            productSelect.addEventListener("change", function () {
                const selectedOption = this.options[this.selectedIndex];
                selectedProduct = selectedOption.value;
                const stock = parseInt(selectedOption.getAttribute("data-stock"));
                const productName = selectedOption.textContent;

                if (!isNaN(stock)) {
                    if (stock < MIN_STOCK) {
                        const unitsToRestock = MIN_STOCK - stock;
                        restockInfo.innerHTML = `<span style="color: #b30000; font-weight: bold;">
                            ⚠️ ${productName} is low on stock (${stock} units). We recommend ordering ${unitsToRestock} more units to reach the minimum stock of ${MIN_STOCK} units.
                        </span>`;
                    } else {
                        restockInfo.innerHTML = `<span style="color: green; font-weight: bold;">
                            ✅ ${productName} has sufficient stock (${stock} units).
                        </span>`;
                    }
                } else {
                    restockInfo.innerHTML = '';
                }
            });

            // Open the modal when "Order" button is clicked
            orderButton.addEventListener("click", function () {
                if (!selectedProduct) {
                    alert("Please select a product first.");
                    return;
                }
                orderModal.style.display = "flex";
            });

            // Close the modal when "Cancel" is clicked
            cancelOrder.addEventListener("click", function () {
                orderModal.style.display = "none";
            });

            // Handle order confirmation
            confirmOrder.addEventListener("click", function () {
                const quantity = parseInt(orderQuantityInput.value);

                if (quantity > 0) {
                    // Hide the order modal
                    orderModal.style.display = "none";

                    // Show the success message modal
                    orderStatusModal.style.display = "flex";
                } else {
                    alert("Please enter a valid quantity.");
                }
            });

            // Close the success message modal
            closeOrderStatusModal.addEventListener("click", function () {
                orderStatusModal.style.display = "none";
            });
        });
    </script>
</body>
</html>
