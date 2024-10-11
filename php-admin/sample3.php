<?php
// Include the database configuration
include('../database/config.php');
$db = new Database();
$conn = $db->getConnection();

// Insert consultation function
function insertConsultation($conn, $patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent) {
    $params = [];
    
    try {
        $sql = "INSERT INTO consultations (patient_id, diagnosis, medstock_id, treatment_medqty, treatment_notes, remark, consult_date, time_in, time_out, time_spent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $params = [
            $patient_idnum, 
            $diagnosis, 
            $medstock_id,
            $treatment_medqty, 
            $treatment_notes, 
            $remark, 
            $consult_date, 
            $time_in, 
            $time_out, 
            $time_spent
        ];
 
        $stmt->execute($params);
        
        return ['status' => 'success', 'message' => 'Consultation inserted successfully.'];
        
    } catch (PDOException $e) {
        return [
            'status' => 'error',
            'message' => 'Error inserting consultation: ' . $e->getMessage(),
            'details' => [
                'sqlState' => $e->getCode(),
                'params' => json_encode($params) // Output params used in query
            ]
        ];
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve POST data
    $patient_idnum = htmlspecialchars($_POST['patient_idnum']);
    $diagnosis = htmlspecialchars($_POST['diagnosis']);
    $medstock_id = intval($_POST['medstock_id']);
    $treatment_medqty = intval($_POST['treatment_medqty']);
    $treatment_notes = htmlspecialchars($_POST['treatment_notes']);
    $remark = htmlspecialchars($_POST['remark']);
    $consult_date = $_POST['consult_date']; // Date format YYYY-MM-DD
    $time_in = $_POST['time_in']; // Time format HH:MM
    $time_out = $_POST['time_out']; // Time format HH:MM
    $time_spent = intval($_POST['time_spent']); // Time spent in minutes
    
    // Insert the consultation
    $result = insertConsultation($conn, $patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent);
    
    // Handle result
    if ($result['status'] === 'success') {
        echo "<script>alert('Consultation inserted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $result['message'] . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Form</title>
</head>
<body>

<h2>Consultation Form</h2>

<form action="" method="POST">
    <label for="patient_idnum">Patient ID:</label>
    <input type="text" id="patient_idnum" name="patient_idnum" required><br><br>

    <label for="diagnosis">Diagnosis:</label>
    <input type="text" id="diagnosis" name="diagnosis" required><br><br>

    <label for="medstock_id">Medicine Stock ID:</label>
    <input type="number" id="medstock_id" name="medstock_id" required><br><br>

    <label for="treatment_medqty">Treatment Medicine Quantity:</label>
    <input type="number" id="treatment_medqty" name="treatment_medqty" required><br><br>

    <label for="treatment_notes">Treatment Notes:</label>
    <textarea id="treatment_notes" name="treatment_notes" required></textarea><br><br>

    <label for="remark">Remark:</label>
    <input type="text" id="remark" name="remark"><br><br>

    <label for="consult_date">Consultation Date:</label>
    <input type="date" id="consult_date" name="consult_date" required><br><br>

    <label for="time_in">Time In:</label>
    <input type="time" id="time_in" name="time_in" required><br><br>

    <label for="time_out">Time Out:</label>
    <input type="time" id="time_out" name="time_out" required><br><br>

    <label for="time_spent">Time Spent (minutes):</label>
    <input type="number" id="time_spent" name="time_spent" required><br><br>

    <input type="submit" value="Submit Consultation">
</form>

</body>
</html>
