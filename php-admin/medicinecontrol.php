<?php
session_start();

include('../database/config.php');
include('../php/medicine.php');

$db = new Database();
$conn = $db->getConnection();
$medicine = new Medicine($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add medicine logic
    if (isset($_POST['addMedicine'])) {
        // Get form data
        $medicine_category = $_POST['addcategory'];
        $medicine_name = $_POST['addname'];
        $medicine_qty = $_POST['addquantity'];
        $medicine_dosage = $_POST['addDS'];
        $medicine_dateadded = date('Y-m-d H:i:s');
        $medicine_expirationdt = $_POST['addED'];

        // Save medicine to database
        if ($medicine->addMedicine($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt)) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = "Medicine added successfully";
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Failed to add medicine";
        }
        header('Location: medicinetable.php');
        exit();
    }
 
    // Update medicine logic
    if (isset($_POST['updatemedicine'])) {
        // Get form data
        $medicine_id = $_POST['editid'];
        $medicine_category = $_POST['editcategory'];
        $medicine_name = $_POST['editname'];
        $medicine_qty = $_POST['editquantity'];
        $medicine_dosage = $_POST['editDS'];
        $medicine_expirationdt = $_POST['editED'];

        // Update medicine
        if ($medicine->updateMedicine($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_expirationdt)) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = "Medicine updated successfully";
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Failed to update medicine";
        }
        header('Location: medicinetable.php');
        exit();
    }

    // Delete medicine logic
    if (isset($_POST['medicine_id'])) {
        $medicine_id = $_POST['medicine_id']; 

        // Call the appropriate method to delete a medicine
        if ($medicine->deleteMedicine($medicine_id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete medicine']);
        }
        exit(); // Ensure no further code executes after sending the JSON response
    }
}
?>


