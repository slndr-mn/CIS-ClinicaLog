<?php
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['patient_id']) && isset($_POST['patient_type'])) {
        $_SESSION['id'] = $_POST['patient_id']; 
        $_SESSION['type'] = $_POST['patient_type'];

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Patient data not found']);
    }
}
?>
