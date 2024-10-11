<?php
session_start();
include('../database/config.php');
include('../php/consultation.php');
include('../php/patient.php');

$db = new Database();
$conn = $db->getConnection();
$consultation = new ConsultationManager($conn);

function calculateTimeSpent($time_in, $time_out) {
    if (strtotime($time_in) !== false && strtotime($time_out) !== false) {
        $start = new DateTime($time_in);
        $end = new DateTime($time_out);
        
        if ($start < $end) {
            $interval = $start->diff($end);
            return $interval->format('%H:%I'); 
        } else {
            return 'Time out must be later than time in';
        }
    } else {
        return 'Invalid time'; 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addcon'])) {
        $patient_idnum = $_POST['selected_patient_id'];
        $diagnosis = $_POST['Diagnosis'];
        $medstock_id = $_POST['prescribemed'];
        $treatment_medqty = $_POST['presmedqty'];
        $treatment_notes = $_POST['presmednotes'];
        $remark = $_POST['Remarks'];
        $consult_date = $_POST['date'];
        $time_in = $_POST['in']; 
        $time_out = $_POST['out']; 

        
        $time_spent = calculateTimeSpent($time_in, $time_out);

        
        if (strpos($time_spent, 'Time out must be later') !== false || strpos($time_spent, 'Invalid time') !== false) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = $time_spent; // Set the error message
            header('Location: addconsultation.php'); 
            exit();
        }

        // Insert the consultation
       if( $response = $consultation->insertConsultation($patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent)){          
        $_SESSION['message'] = $response['message'];
        $_SESSION['status'] = $response['status'];
       }else{
        $_SESSION['message'] = 'Failed to add consultation';
        $_SESSION['status'] = 'error';
       }

        header('Location: addconsultation.php'); 
        exit();
    }
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';

    header('Location: addconsultation.php'); 
    exit();
}
?>
