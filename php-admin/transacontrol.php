<?php
session_start();
header('Content-Type: application/json'); 
include('../database/config.php');
include('../php/transaction.php');

$db = new Database();
$conn = $db->getConnection();
$transac = new TransacManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['addtransac'])) {
        $patientId = $_POST['selected_patient_id'];
        $purpose = $_POST['transac_purpose'];

        $transaction = $transac->addTransaction($patientId, $purpose);

        if ($transaction['status'] == 'success') {    
            $_SESSION['message'] = $transaction['message'];
            $_SESSION['status'] = $transaction['status'];
        
        } else {
           
            $_SESSION['message'] = $transaction['message'];
            $_SESSION['status'] = 'error'; 
        }
        header('Location: transactions.php'); 
        exit();
        
    }

    if (isset($_POST['edittransac'])) {
        $transacid = $_POST['transac_id'];
        $patient_id = $_POST['edit_patient_id'];
        $purpose = $_POST['edit_purpose'];

        $transaction = $transac->updatePatientAndPurpose($transacid, $patient_id, $purpose);

        if ($transaction['status'] == 'success') {    
            $_SESSION['message'] = $transaction['message'];
            $_SESSION['status'] = $transaction['status'];
        
        } else {
           
            $_SESSION['message'] = $transaction['message'];
            $_SESSION['status'] = 'error'; 
        }
        header('Location: transactions.php'); 
        exit();
        
    }

    if (isset($_POST['patient_id']) && isset($_POST['patient_type'])) {
        $_SESSION['id'] = $_POST['patient_id']; 
        $_SESSION['type'] = $_POST['patient_type']; 
    } else {
        echo "Patient data not found.";
    }

    if (isset($_POST['transac_id']) && isset($_POST['status'])) {
        $transac_id = $_POST['transac_id'];
        $status = $_POST['status'];

        switch ($status) {
            case 'Pending':
                $transac->updateStatusToPending($transac_id);
                break;
            case 'Progress':
                $transac->updateStatusToInProgress($transac_id);
                break;
            case 'Done':
                $transac->updateStatusToDone($transac_id);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid status']);
                exit;
        }

        // Return a success message
        echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
    } else {
        // Missing parameters
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }

    

}
?>
