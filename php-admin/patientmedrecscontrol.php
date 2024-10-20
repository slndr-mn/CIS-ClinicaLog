<?php
session_start();

include('../database/config.php');
include('../php/medicalrecords.php');

$db = new Database();
$conn = $db->getConnection();
$medicalrecords = new MedRecManager($conn);
$patienttype = $_POST['patienttype']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addmedicalrecs'])) {
        $patientid = $_POST['patientid'];
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
    
        $response = $medicalrecords->insertMedicalRecord($patientid, $filenames, $hashedFiles, $comment, $dateadded, $timeadded);
    
        if ($response['status'] === 'success') {
            for ($i = 0; $i < count($hashedFiles); $i++) {
                $originalTmpPath = $files['tmp_name'][$i];
                $hashedName = $hashedFiles[$i];
                $destination = 'uploadmedrecs/' . $hashedName;
    
                if (move_uploaded_file($originalTmpPath, $destination)) {

                } else {
                    // Handle file move failure
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
}
else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';

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

?>