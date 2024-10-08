<?php
session_start();
include('../database/config.php');
include('../php/consultation.php');
include('../php/patient.php');

$db = new Database();
$conn = $db->getConnection();
$consultation = new ConsultationManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling adding a consultation
    if (isset($_POST['addcon'])) {
    
        // Retrieve and sanitize input data
        $patient_idnum = $_POST['patient_idnum'];
        $consultation_diagnosis = $_POST['Diagnosis'];
        $medstock_id = $_POST['prescribemed'];
        $treatment_medqty = $_POST['presmedqty'];
        $treatment_notes = $_POST['presmednotes'];
        $consultation_remark = $_POST['Remarks'];
        $consult_date = date('Y-m-d');
        $time_in = date('H:i:s'); // Current time
        // Set time_out to be 1 hour later than time_in
        $time_out = date('H:i:s');
        $time_spent = calculateTimeSpent($time_in, $time_out); // Custom function to calculate time spent
    
        // Insert the consultation
        if ($consultation->insertConsultation($patient_idnum, $consultation_diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $consultation_remark, $consult_date, $time_in, $time_out, $time_spent)) {
            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Failed to add consultation";
        }
        header('Location: addconsultation.php'); 
        exit();
    } 

    // Handling deleting a consultation
    if (isset($_POST['consultation_id'])) {
        $consultation_id = $_POST['consultation_id']; 

        if ($consultation->deleteConsultation($consultation_id)) {
            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Failed to delete";
        }
        exit(); 
    }
}

// Function to calculate time spent
function calculateTimeSpent($time_in, $time_out) {
    $start = new DateTime($time_in);
    $end = new DateTime($time_out);
    // Calculate the time difference
    $interval = $start->diff($end);
    // Format the difference (e.g., hours and minutes)
    return $interval->format('%H hours %I minutes');
}

?>