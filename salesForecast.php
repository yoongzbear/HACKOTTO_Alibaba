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
        <option value="ProductA">Product A</option>
        <option value="ProductB">Product B</option>
        <option value="ProductC">Product C</option>
    </select>

    <script>
        const dropdown = document.getElementById("productDropdown");

        dropdown.addEventListener("change", function () {
            const selectedProduct = this.value;
            if (selectedProduct) {
                // Change the URL without reloading the page
                const newUrl = `${window.location.pathname}?product=${encodeURIComponent(selectedProduct)}`;
                window.history.pushState({ path: newUrl }, '', newUrl);
            }
        });

    </script>

</body>
</html>
