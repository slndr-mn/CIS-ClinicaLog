<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../database/config.php');
include('../php/medicalrecords.php');

$db = new Database();
$conn = $db->getConnection();
$medicalrecords = new MedRecManager($conn);

$patienttype = $_POST['patienttype']; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addmedicalrecs'])) {
        $patientid = $_POST['patientid'];
        $medrecid = $_POST['medrecid'];
        $comment = 'No Comment';
        $dateadded = date('Y-m-d');
        $timeadded = date('H:i:s');
        $files = $_FILES['uploadfile'];   
        $patienttype = $_POST['patienttype']; 

        $filenames = [];
        $hashedFiles = []; 
        $duplicateFilenames = [];

        for ($i = 0; $i < count($files['name']); $i++) {
            $originalName = $files['name'][$i];
            $tmpFilePath = $files['tmp_name'][$i];

            $hashedName = md5(uniqid($originalName, true));

            if ($medicalrecords->getDuplicateFilenames($patientid, $originalName)) {
                $duplicateFilenames[] = $originalName;
            } else {
                $filenames[] = $originalName;
                $hashedFiles[] = $hashedName;
            } 
        }

        if (!empty($duplicateFilenames)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Duplicate files found: ' . implode(', ', $duplicateFilenames);

            switch ($patienttype) {
                case 'Student':
                    $redirectUrl = 'patient-studprofile.php';
                    break;
                case 'Faculty':
                    $redirectUrl = 'patient-facultyprofile.php';
                    break;
                case 'Staff':
                    $redirectUrl = 'patient-staffprofile.php';
                    break;
                case 'Extension':
                    $redirectUrl = 'patient-extensionprofile.php';
                    break;
                default:
                    $redirectUrl = 'patient-record.php';
                    break;
            }
            header("Location: $redirectUrl");
            exit();
        }
    
        $response = $medicalrecords->insertMedicalRecord($patientid, $filenames, $hashedFiles, $comment, $dateadded, $timeadded);
    
        if ($response['status'] === 'success') {
            for ($i = 0; $i < count($hashedFiles); $i++) {
                $originalTmpPath = $files['tmp_name'][$i];
                $hashedName = $hashedFiles[$i];
                $destination = 'uploadmedrecs/' . $hashedName;
    
                if (move_uploaded_file($originalTmpPath, $destination)) {

                } else {
                    
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Failed to upload file: ' . $filenames[$i];
                    // Redirect based on patient type
                    switch ($patienttype) {
                        case 'Student':
                            $redirectUrl = 'patient-studprofile.php';
                            break;
                        case 'Faculty':
                            $redirectUrl = 'patient-facultyprofile.php';
                            break;
                        case 'Staff':
                            $redirectUrl = 'patient-staffprofile.php';
                            break;
                        case 'Extension':
                            $redirectUrl = 'patient-extensionprofile.php';
                            break;
                        default:
                            $redirectUrl = 'patient-record.php';
                            break;
                    }
                    header("Location: $redirectUrl");
                    exit();
                        }
            }
            
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Medical record inserted and files uploaded successfully.';
            // Redirect based on patient type
            switch ($patienttype) {
                case 'Student':
                    $redirectUrl = 'patient-studprofile.php';
                    break;
                case 'Faculty':
                    $redirectUrl = 'patient-facultyprofile.php';
                    break;
                case 'Staff':
                    $redirectUrl = 'patient-staffprofile.php';
                    break;
                case 'Extension':
                    $redirectUrl = 'patient-extensionprofile.php';
                    break;
                default:
                    $redirectUrl = 'patient-record.php';
                    break;
            }
            header("Location: $redirectUrl");
            exit();
        } else {
            // Error during insert
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = $response['message'];
            // Redirect based on patient type
            switch ($patienttype) {
                case 'Student':
                    $redirectUrl = 'patient-studprofile.php';
                    break;
                case 'Faculty':
                    $redirectUrl = 'patient-facultyprofile.php';
                    break;
                case 'Staff':
                    $redirectUrl = 'patient-staffprofile.php';
                    break;
                case 'Extension':
                    $redirectUrl = 'patient-extensionprofile.php';
                    break;
                default:
                    $redirectUrl = 'patient-record.php';
                    break;
            }
            header("Location: $redirectUrl");
            exit();
        }
    }
    if (isset($_POST['editmedrecs'])) {
        $patientid = $_POST['patientid'];
        $id = $_POST['editid'];        
        $filename = $_POST['editfilename'];  
        $comment = $_POST['editcomment'];
        $patienttype = $_POST['patienttype']; 

            $response = $medicalrecords->updateMedicalRecord($id, $patientid, $filename, $comment);

            if ($response['status'] === 'success') {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = $response['message'];
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = $response['message'];
            }

            switch ($patienttype) {
                case 'Student':
                    $redirectUrl = 'patient-studprofile.php';
                    break;
                case 'Faculty':
                    $redirectUrl = 'patient-facultyprofile.php';
                    break;
                case 'Staff':
                    $redirectUrl = 'patient-staffprofile.php';
                    break;
                case 'Extension':
                    $redirectUrl = 'patient-extensionprofile.php';
                    break;
                default:
                    $redirectUrl = 'patient-record.php';
                    break;
            }
            header("Location: $redirectUrl");
            exit();
        
    }
    if (isset($_POST['medrec_id'])) {
        $medicalrecId = $_POST['medrec_id'];
    
        $filePath = $medicalrecords->getFilePathByMedicalRecId($medicalrecId);
    
        if ($medicalrecords->deleteMedicalRecord($medicalrecId)) {
            if ($filePath && file_exists("uploadmedrecs/" . $filePath)) {
                if (!unlink("uploadmedrecs/" . $filePath)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete the associated file.']);
                    exit();
                }
            }
            echo json_encode(['success' => true, 'message' => 'Record and associated file deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the medical record.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid medical record ID.']);
    }
    
    
    
    
}
else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';

    switch ($patienttype) {
        case 'Student':
            $redirectUrl = 'patient-studprofile.php';
            break;
        case 'Faculty':
            $redirectUrl = 'patient-facultyprofile.php';
            break;
        case 'Staff':
            $redirectUrl = 'patient-staffprofile.php';
            break;
        case 'Extension':
            $redirectUrl = 'patient-extensionprofile.php';
            break;
        default:
            $redirectUrl = 'patient-record.php';
            break;
    }
    header("Location: $redirectUrl");
    exit();
}

?>