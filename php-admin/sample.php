<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Transactions Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Monthly Transactions by Patient Type</h2>
    
    <!-- Dropdown for selecting the year -->
    <label for="yearSelect">Select Year:</label>
    <select id="yearSelect" onchange="loadDataForYear(this.value)">
        <!-- JavaScript will populate the options here -->
    </select>
    
    <!-- Chart canvas -->
    <canvas id="transactionChart" width="400" height="200"></canvas>

    <!-- Link to JavaScript file -->
    <script src="sample3.js"></script>
</body>
</html>
