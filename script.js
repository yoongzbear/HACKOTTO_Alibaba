// Sales Forecast Chart
const salesCtx = document.getElementById('salesForecastChart').getContext('2d');
new Chart(salesCtx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
    datasets: [
      {
        label: 'Actual Sales',
        data: [12, 19, 3, 5, 2],
        borderColor: 'blue',
        fill: false
      },
      {
        label: 'Forecasted Sales',
        data: [14, 20, 4, 6, 3],
        borderDash: [5, 5],
        borderColor: 'green',
        fill: false
      }
    ]
  },
  options: { responsive: true }
});

// Inventory Optimization Chart
const inventoryCtx = document.getElementById('inventoryOptimizationChart').getContext('2d');
new Chart(inventoryCtx, {
  type: 'bar',
  data: {
    labels: ['Product A', 'Product B', 'Product C'],
    datasets: [
      {
        label: 'Current Stock',
        data: [50, 30, 70],
        backgroundColor: 'orange'
      },
      {
        label: 'Optimal Stock Level',
        data: [60, 40, 80],
        backgroundColor: 'green'
      }
    ]
  },
  options: { responsive: true }
});

// Trend Analysis Chart
const trendCtx = document.getElementById('trendAnalysisChart').getContext('2d');
new Chart(trendCtx, {
  type: 'doughnut',
  data: {
    labels: ['Electronics', 'Clothing', 'Home Goods'],
    datasets: [{
      data: [300, 50, 100],
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
    }]
  },
  options: { responsive: true }
});