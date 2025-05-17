document.addEventListener('DOMContentLoaded', () => {
    const revenueForecast = new Chart(document.getElementById('revenueForecast'), {
        type: 'pie',
        data: {
            labels: ['Hardware', 'Software', 'Application'],
            datasets: [{
                data: [50, 30, 20],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const unitsSoldForecast = new Chart(document.getElementById('unitsSoldForecast'), {
        type: 'bar',
        data: {
            labels: ['October', 'November', 'December'],
            datasets: [{
                label: 'Units Sold',
                data: [2, 6, 3],
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const forecastingAccuracy = new Chart(document.getElementById('forecastingAccuracy'), {
        type: 'doughnut',
        data: {
            labels: ['Accurate', 'Inaccurate'],
            datasets: [{
                data: [90, 10],
                backgroundColor: ['#36A2EB', '#FF6384']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const salesByType = new Chart(document.getElementById('salesByType'), {
        type: 'bar',
        data: {
            labels: ['October', 'November', 'December'],
            datasets: [{
                label: 'Hardware',
                data: [2, 6, 3],
                backgroundColor: '#FF6384'
            }, {
                label: 'Software',
                data: [10, 15, 10],
                backgroundColor: '#36A2EB'
            }, {
                label: 'Application',
                data: [50, 55, 60],
                backgroundColor: '#FFCE56'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const averageSalesPriceTrend = new Chart(document.getElementById('averageSalesPriceTrend'), {
        type: 'line',
        data: {
            labels: ['October', 'November', 'December'],
            datasets: [{
                label: 'Average Sales Price',
                data: [84.63, 85.51, 84.55],
                borderColor: '#36A2EB',
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const revenueTrend = new Chart(document.getElementById('revenueTrend'), {
        type: 'line',
        data: {
            labels: ['October', 'November', 'December'],
            datasets: [{
                label: 'Revenue',
                data: [95.5, 95.5, 95.5],
                borderColor: '#36A2EB',
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});