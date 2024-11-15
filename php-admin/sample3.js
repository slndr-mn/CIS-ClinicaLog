let transactionChart;

// Initialize year dropdown and load data for the current year
function initializeYearDropdown() {
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    const yearsToShow = 8; // Show the last 5 years

    for (let i = 0; i < yearsToShow; i++) {
        const year = currentYear - i;
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Set the current year as default and load its data
    yearSelect.value = currentYear;
    loadDataForYear(currentYear);
}

// Fetch data for the selected year
function loadDataForYear(year) {
    fetch(`sample2.php?year=${year}`)
        .then(response => response.json())
        .then(data => {
            updateChart(data);
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Create the chart
function createChart(data) {
    const ctx = document.getElementById('transactionChart').getContext('2d');
    transactionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.months,
            datasets: [
                {
                    label: 'Students',
                    data: data.students,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                },
                {
                    label: 'Faculty',
                    data: data.faculty,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                },
                {
                    label: 'Staff',
                    data: data.staff,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                },
                {
                    label: 'Extension',
                    data: data.extension,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Transactions'
                    }
                }
            }
        }
    });
}


// Update chart data
function updateChart(data) {
    if (transactionChart) {
        transactionChart.data.labels = data.months;
        transactionChart.data.datasets[0].data = data.students;
        transactionChart.data.datasets[1].data = data.faculty;
        transactionChart.data.datasets[2].data = data.staff;
        transactionChart.data.datasets[3].data = data.extension;
        transactionChart.update();
    } else {
        createChart(data);
    }
}

// Initialize dropdown and chart on page load
document.addEventListener('DOMContentLoaded', initializeYearDropdown);
