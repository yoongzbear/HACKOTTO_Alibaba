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

  const select = document.getElementById("timelineSelect");
  const iframe = document.getElementById("chartFrame");

  const dashboardUrls = {
    "7days": "https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=43211323-707f-4ff8-adfd-64e477527807&accessTicket=56f046d9-e444-4ca2-b0fe-ba8d91bfabf6&dd_orientation=auto",
    "30days": "https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=3bef6f5c-4440-4367-8b9b-0ebfe852f522&accessTicket=9f98ede8-f814-481f-bc2d-6e2d8096a6e9&dd_orientation=auto",
    "120days": "https://bi-ap-southeast-3.data.aliyun.com/token3rd/dashboard/view/pc.htm?pageId=d055c1f0-e3f8-429f-901a-52e83619d945&accessTicket=dc351068-3950-40eb-9baf-95bdbc9b7d16&dd_orientation=auto"
  };

  // Set default view
  iframe.src = dashboardUrls["7days"];

  // Listen to dropdown changes
  select.addEventListener("change", () => {
    const selected = select.value;
    iframe.src = dashboardUrls[selected];
  });
