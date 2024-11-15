let transactionChart; 

function initializeYearDropdown() {
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    const yearsToShow = 8; 

    for (let i = 0; i < yearsToShow; i++) {
        const year = currentYear - i;
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    yearSelect.value = currentYear;
    loadDataForYear(currentYear);
}

function loadDataForYear(year) {
  fetch(`../php-admin/reportscontrol.php?year=${year}`)
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();   
      })
      .then(data => {
          if (data.categoryData) {
              updateLineChart(data.categoryData); 
          }
          if (data.general_transactions) {
            updateChart(data.general_transactions);
          }
          if (data.medical_certificates) {
              updateMedicalCertificatesTable(data.medical_certificates);
          }
          if (data.consultation_treatments) {
              updateMedicalConsultationTable(data.consultation_treatments);
          }

          if (data.dental_checkups) {
            updateDentalCheckupTable(data.dental_checkups);
        }
      })
      .catch(error => {
          console.error('Error fetching data:', error);
      });
}

let lineChart;

function updateLineChart(categoryData) {
    const xValues = [];
    const yValues = [];

    for (const program in categoryData.students) {
        xValues.push(program);
        yValues.push(categoryData.students[program]); 
    }

    xValues.push('Faculty', 'Staff', 'Extension');
    yValues.push(categoryData.faculty, categoryData.staff, categoryData.extension);

    if (lineChart) {
        
        lineChart.data.labels = xValues;
        lineChart.data.datasets[0].data = yValues;
        lineChart.update(); 
    } else {
        lineChart = new Chart("lineChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(0, 0, 255, 0.8)",
                    borderColor: "rgba(0, 0, 255, 0.4)",
                    data: yValues
                }]
            },
            options: {
                legend: { display: true },
                scales: {
                    x: {
                        ticks: { autoSkip: false, maxRotation: 45, minRotation: 45 }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        });
    }
}



function createTranChart(data) {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    transactionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.months,
            datasets: [{
                label: "Faculties",
                borderColor: '#f3545d',
                pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
                pointRadius: 0,
                backgroundColor: 'rgba(243, 84, 93, 0.4)',
                legendColor: '#f3545d',
                fill: true,
                borderWidth: 2,
                data: data.faculty
            }, {
                label: "Students",
                borderColor: '#fdaf4b',
                pointBackgroundColor: 'rgba(253, 175, 75, 0.6)',
                pointRadius: 0,
                backgroundColor: 'rgba(253, 175, 75, 0.4)',
                legendColor: '#fdaf4b',
                fill: true,
                borderWidth: 2,
                data: data.students
            }, {
                label: "Staffs",
                borderColor: '#177dff',
                pointBackgroundColor: 'rgba(23, 125, 255, 0.6)',
                pointRadius: 0,
                backgroundColor: 'rgba(23, 125, 255, 0.4)',
                legendColor: '#177dff',
                fill: true,
                borderWidth: 2, 
                data: data.staff
            }, {
                label: "Extensions",
                borderColor: '#c7f2c4',
                pointBackgroundColor: 'rgba(199, 242, 196, 0.6)',
                pointRadius: 0,
                backgroundColor: 'rgba(199, 242, 196, 0.4)',
                legendColor: '#c7f2c4',
                fill: true,
                borderWidth: 2,
                data: data.extension
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            layout: {
                padding: { left: 5, right: 5, top: 15, bottom: 15 }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        fontStyle: "500",
                        beginAtZero: false,
                        maxTicksLimit: 5,
                        padding: 10
                    },
                    gridLines: {
                        drawTicks: false,
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        padding: 10,
                        fontStyle: "500"
                    }
                }]
            },
            legendCallback: function (chart) {
              var text = [];
              text.push('<ul class="' + chart.id + '-legend html-legend">');
              for (var i = 0; i < chart.data.datasets.length; i++) {
                text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>');
                if (chart.data.datasets[i].label) {
                  text.push(chart.data.datasets[i].label);
                }
                text.push('</li>');
              }
              text.push('</ul>');
              return text.join('');
            }
        }
    });

    var myLegendContainer = document.getElementById("myChart");
    myLegendContainer.innerHTML = transactionChart.generateLegend();

    var legendItems = myLegendContainer.getElementsByTagName('li');
    for (var i = 0; i < legendItems.length; i++) {
        legendItems[i].addEventListener("click", legendClickCallback, false);
    }
}

function updateChart(data) {
    if (transactionChart) {
        transactionChart.data.labels = data.months;
        transactionChart.data.datasets[0].data = data.faculty;
        transactionChart.data.datasets[1].data = data.students;
        transactionChart.data.datasets[2].data = data.staff;
        transactionChart.data.datasets[3].data = data.extensions;
        transactionChart.update();
    } else {
        createTranChart(data); 
    }
}

function legendClickCallback(event) {
    const item = event.target.closest('li');
    const index = Array.from(item.parentElement.children).indexOf(item);  
    const meta = transactionChart.getDatasetMeta(index);

    const currentlyOnlyOneVisible = transactionChart.data.datasets.filter((ds, i) => !transactionChart.getDatasetMeta(i).hidden).length === 1;

    if (currentlyOnlyOneVisible && !meta.hidden) {
        transactionChart.data.datasets.forEach((dataset, i) => {
            transactionChart.getDatasetMeta(i).hidden = false;
        });
    } else {
        transactionChart.data.datasets.forEach((dataset, i) => {
            transactionChart.getDatasetMeta(i).hidden = i !== index;
        });
        meta.hidden = false;
    }

    transactionChart.update();
} 

function updateMedicalCertificatesTable(medicalData) {
  const rows = document.querySelectorAll('#medcert tbody tr');
  
  rows.forEach((row, index) => {
      const month = row.querySelector('td').innerText.trim();

      const monthIndex = new Date(`${month} 1`).getMonth(); 

      const facultyCount = medicalData.medical_faculty[monthIndex] || 0;
      const staffCount = medicalData.medical_staff[monthIndex] || 0;
      const studentCount = medicalData.medical_students[monthIndex] || 0;
      const extensionCount = medicalData.medical_extension[monthIndex] || 0;

      row.cells[1].innerText = facultyCount;
      row.cells[2].innerText = staffCount;
      row.cells[3].innerText = studentCount;
      row.cells[4].innerText = extensionCount;

      const total = facultyCount + staffCount + studentCount + extensionCount;
      row.cells[5].innerText = total;
  });
}

function updateMedicalConsultationTable(consultData) {
  const rows = document.querySelectorAll('#consult tbody tr');
  
  rows.forEach((row, index) => {
      const month = row.querySelector('td').innerText.trim();

      const monthIndex = new Date(`${month} 1`).getMonth(); 

      const facultyCount = consultData.consultation_faculty[monthIndex] || 0;
      const staffCount = consultData.consultation_staff[monthIndex] || 0;
      const studentCount = consultData.consultation_students[monthIndex] || 0;
      const extensionCount = consultData.consultation_extension[monthIndex] || 0;

      row.cells[1].innerText = facultyCount;
      row.cells[2].innerText = staffCount;
      row.cells[3].innerText = studentCount;
      row.cells[4].innerText = extensionCount;

      const total = facultyCount + staffCount + studentCount + extensionCount;
      row.cells[5].innerText = total;
  });
}

function updateDentalCheckupTable(checkupData) {
  const rows = document.querySelectorAll('#checkup tbody tr');
  
  rows.forEach((row, index) => {
      const month = row.querySelector('td').innerText.trim();

      const monthIndex = new Date(`${month} 1`).getMonth(); 

      const facultyCount = checkupData.dental_faculty[monthIndex] || 0;
      const staffCount = checkupData.dental_staff[monthIndex] || 0;
      const studentCount = checkupData.dental_students[monthIndex] || 0;
      const extensionCount = checkupData.dental_extension[monthIndex] || 0;

      row.cells[1].innerText = facultyCount;
      row.cells[2].innerText = staffCount;
      row.cells[3].innerText = studentCount;
      row.cells[4].innerText = extensionCount;

      const total = facultyCount + staffCount + studentCount + extensionCount;
      row.cells[5].innerText = total;
  });
}


document.addEventListener('DOMContentLoaded', initializeYearDropdown);

async function exportToExcel() {
    const year = document.getElementById("yearSelect").value; // Get the selected year

    // Make an AJAX request to fetch the data from the server
    $.ajax({
        url: `../php-admin/reportexportcategory.php?year=${year}`, // Use template literal for the correct year
        type: 'GET',
        dataType: 'json', // Expecting JSON response
        success: function(response) {
            if (response.error) {
                // Handle any server-side error in the response
                alert("Error: " + response.error);
                return;
            }

            // Call the function to export the data to Excel
            generateExcel(response);
        },
        error: function(xhr, status, error) {
            // Handle AJAX request errors
            console.error('Error fetching data:', error);
            alert('There was an error fetching the data. Please try again.');
        }
    });
}

// Function to generate the Excel file from the server response
function generateExcel(data) {
    // Initialize an array for the Excel data
    let excelData = [];

    // Create the header row
    let header = ['Month', 'Faculty', 'Staff', 'Extension', 'Monthly Total'];

    // Dynamically add program columns based on the response
    if (data && data.length > 0) {
        let programColumns = data[0]; // Take the first row to determine columns
        for (let program in programColumns) {
            // Only add columns for program data (excluding predefined fields)
            if (program !== 'month' && program !== 'faculty' && program !== 'staff' && program !== 'extension' && program !== 'monthly_total') {
                header.push(program);
            }
        }
    }

    // Add the header row to the Excel data array
    excelData.push(header);

    // Iterate through the data and build each row
    data.forEach(row => {
        let rowData = [];
        rowData.push(row.month);
        rowData.push(row.faculty);
        rowData.push(row.staff);
        rowData.push(row.extension);
        rowData.push(row.monthly_total);

        // Add data for each program column
        for (let program in row) {
            if (program !== 'month' && program !== 'faculty' && program !== 'staff' && program !== 'extension' && program !== 'monthly_total') {
                rowData.push(row[program]);
            }
        }

        // Push the row data to the Excel data array
        excelData.push(rowData);
    });

    // Convert the Excel data to a worksheet using the 'aoa_to_sheet' method
    const ws = XLSX.utils.aoa_to_sheet(excelData);

    // Create a new workbook and append the worksheet to it
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Transaction Data');

    // Generate and trigger the Excel file download
    XLSX.writeFile(wb, 'transaction_data.xlsx');
}
