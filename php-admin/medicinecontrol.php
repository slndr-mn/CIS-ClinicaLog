<?php
session_start();

include('../database/config.php');
include('../php/medicine.php');

$db = new Database();
$conn = $db->getConnection();
$medicine = new MedicineManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add medicine logic
    if (isset($_POST['addMedicine'])) {
        // Get form data
        $medicine_id = $_POST['addname'];
        $medicine_qty = $_POST['addquantity'];
        $medicine_dosage = $_POST['addDS'];
        $medicine_dateadded = date('Y-m-d');
        $medicine_timeadded = date('h:i:s');
        $medicine_expirationdt = $_POST['addED'];
        $medicine_disable = "0";

        // Add medicine to the linked list and database
        if ($medicine->insertMedstock($medicine_id, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_timeadded,$medicine_expirationdt, $medicine_disable )) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = "Medicine added successfully";
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Failed to add medicine";
        } 
        header('Location: medicinetable.php');
        exit();
    }

    if (isset($_POST['updatemedicine'])) {
        // Get form data
        $medstock_id = $_POST['editid'];
        $medicine_name = $_POST['editname'];
        $medicine_qty = $_POST['editquantity'];
        $medicine_dosage = $_POST['editDS'];
        $medicine_expirationdt = $_POST['editED'];
        $medicine_disable = $_POST['editDisable'];
    
        // Update medstock in both linked list and database
        $result = $medicine->updateMedstock($medstock_id, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_expirationdt, $medicine_disable);
    
        // Check the result and set session messages
        if ($result['status'] === 'success') {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = $result['message'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = $result['message'];
        }
    
        // Redirect to the medicinetable page
        header('Location: medicinetable.php');
        exit();
    }
    
    
 
// Update medicine logic
if (isset($_POST['addmed'])) {
    // Get form data
    $medicine_id = $_POST['medicineId'];
    $medicine_name = $_POST['medicineName'];
    $medicine_category = $_POST['medicineCategory'];

    if (empty($medicine_id)) {
        // Insert new medicine
        if ($medicine->medicines->medicineExists($medicine_name)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Medicine with this name already exists.";
        } else {
            if ($medicine->insertMedicine($medicine_name, $medicine_category)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = "Medicine added successfully";
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = "Failed to add medicine";
            }
        }
    } else {
        // Update existing medicine
        $existingMedicine = $medicine->medicines->find($medicine_id);
        if ($existingMedicine) {
            // Check if the name is changing
            if ($existingMedicine->medicine_name !== $medicine_name) {
                // Only check for existence if the name is changing
                if ($medicine->medicines->medicineExists($medicine_name)) {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = "Medicine with this name already exists.";
                } else {
                    if ($medicine->updateMedicine($medicine_id, $medicine_name, $medicine_category)) {
                        $_SESSION['status'] = 'success';
                        $_SESSION['message'] = "Medicine updated successfully";
                    } else {
                        $_SESSION['status'] = 'error';
                        $_SESSION['message'] = "Failed to update medicine";
                    }
                }
            } else {
                // If the name hasn't changed, just update without checking for existence
                if ($medicine->updateMedicine($medicine_id, $medicine_name, $medicine_category)) {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = "Medicine updated successfully";
                } else {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = "Failed to update medicine";
                }
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = "Medicine not found.";
        }
    }

    header('Location: medicinetable.php');
    exit();
}


    

    // Delete medicine logic 
    if (isset($_POST['medicine_id'])) {
        $medicine_id = $_POST['medicine_id']; 

        // Call the method to delete medicine from both the linked list and the database
        if ($medicine->deleteMedicine($medicine_id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete medicine']);
        }
        exit(); // Ensure no further code executes after sending the JSON response
    }
}
?>


