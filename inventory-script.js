
// DOM Elements
const productSelect = document.getElementById('productSelect');
const filterInput = document.getElementById('filterInput');
const productList = document.getElementById('productList');
const orderButton = document.getElementById('orderButton');
const restockInfo = document.getElementById('restockInfo');

// Populate Select Dropdown
function populateSelect() {
    productSelect.innerHTML = '';
    products.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = product.name;
        productSelect.appendChild(option);
    });
}

// Render Product List
function renderProducts(filter = '') {
    productList.innerHTML = '';
    const filteredProducts = products.filter(p =>
        p.name.toLowerCase().includes(filter.toLowerCase())
    );

    filteredProducts.forEach(product => {
        const li = document.createElement('li');
        li.className = 'product-item';
        li.innerHTML = `
            ${product.name} (Stock: ${product.stock})
            ${product.stock < product.threshold ? '<span class="restock-flag">⚠️ Needs Restock</span>' : ''}
        `;
        productList.appendChild(li);
    });
}

// Show Restock Recommendation
function showRestockRecommendation(productId) {
    const product = products.find(p => p.id === parseInt(productId));
    if (!product) return;

    const needsRestock = product.stock < product.threshold;
    const recommendedAmount = needsRestock ? product.threshold - product.stock : 0;

    restockInfo.innerHTML = recommendedAmount > 0
        ? `<p>Recommended restock for next month: <strong>${recommendedAmount}</strong> units of "${product.name}"</p>`
        : `<p>No restock needed for "${product.name}".</p>`;
}

// Handle Filter
filterInput.addEventListener('input', () => {
    renderProducts(filterInput.value);
});

// Handle Product Selection
productSelect.addEventListener('change', (e) => {
    const productId = e.target.value;
    showRestockRecommendation(productId);
});

// Handle Order Button Click
orderButton.addEventListener('click', () => {
    const selectedProductId = parseInt(productSelect.value);
    const product = products.find(p => p.id === selectedProductId);

    if (!product) {
        alert("Please select a product.");
        return;
    }

    const needsRestock = product.stock < product.threshold;
    const recommendedAmount = needsRestock ? product.threshold - product.stock : 0;

    if (recommendedAmount > 0) {
        alert(`Ordered ${recommendedAmount} units of "${product.name}" for next month.`);
    } else {
        alert(`No restock needed for "${product.name}".`);
    }
});

// Initialize
populateSelect();
renderProducts();