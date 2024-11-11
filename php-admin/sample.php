
<?php
// Include your database connection (adjust this according to your setup)
include('../database/config.php');
include('../php/user.php');
include('../php/transaction.php');

$db = new Database();
$conn = $db->getConnection();

// Instantiate the TransacManager
$transacManager = new TransacManager($conn);

// Fetch all transactions
$transactions = $transacManager->getAllTransac();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Table</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Transactions</h1>

<?php
// Check if there are transactions and display the table
if (count($transactions) > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Transaction ID</th>';
    echo '<th>Patient Name</th>';
    echo '<th>Patient Type</th>';
    echo '<th>Purpose</th>';
    echo '<th>Date</th>';
    echo '<th>In Time</th>';
    echo '<th>Out Time</th>';
    echo '<th>Amount Spent</th>';
    echo '<th>Status</th>';
    echo '<th>Patient ID Number</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    // Loop through the transactions and display each one in a row
    foreach ($transactions as $transaction) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($transaction->transac_id) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_patientname) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_patienttype) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_purpose) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_date) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_in) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_out) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_spent) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_status) . '</td>';
        echo '<td>' . htmlspecialchars($transaction->transac_patientidnum) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p>No transactions found.</p>';
}
?>

</body>
</html>