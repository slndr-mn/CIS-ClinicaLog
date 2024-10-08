<?php
session_start();

include('../database/config.php');
include('../php/patient.php');

$db = new Database();
$conn = $db->getConnection();
$patient = new PatientManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addstudentpatient'])) {

        // Initialize variables from POST data
        $lname = $_POST['lastName'];
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $dob = $_POST['dob'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $connum = $_POST['contactNumber'];
        $sex = $_POST['sex'];
        $idnum = $_POST['studentID'];
        $program = $_POST['program'];
        $major = $_POST['major'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $prkstrtadd = $_POST['street'];
        $conname = $_POST['emergencyContactName'];
        $relationship = $_POST['relationship'];
        $emergency_connum = $_POST['emergencyContactNumber'];

        $profile = ''; // Default to empty if no profile picture uploaded

        // Handle Profile Upload
        if (isset($_FILES['addprofile']) && $_FILES['addprofile']['error'] === UPLOAD_ERR_OK) {
            $profile = $_FILES['addprofile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $profile_tmp);
            $allowed_mimes = ['image/jpeg', 'image/png'];

            if (in_array($mime, $allowed_mimes)) {
                $profile_hash = md5(uniqid($profile_original_name, true));
                $profile_name = $profile_hash . '.' . strtolower(pathinfo($profile_original_name, PATHINFO_EXTENSION));
                $uploadDir = 'uploads/';
                $profile_destination = $uploadDir . $profile_name;

                // Move uploaded file
                if (move_uploaded_file($profile_tmp, $profile_destination)) {
                    $profile = $profile_name; // Set profile name to save to the database
                } else {
                    $_SESSION['error'] = 'Failed to upload profile picture.';
                    header('Location: add-student.php'); // Redirect to the add student page
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type. Only JPEG and PNG files are allowed.';
                header('Location: add-student.php'); 
                exit();
            }
            finfo_close($finfo);
        }

        // Validate email and proceed with insertion
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Call the addStudentPatient method and store the response
            $response = $patient->addStudentPatient(
                $lname, $fname, $mname, $dob, $email, $connum, $sex, $profile, 'Student', 
                date('Y-m-d H:i:s'), password_hash($idnum, PASSWORD_DEFAULT), 'Active', 0, 
                $idnum, $program, $major, $year, $section, 
                $region, $province, $municipality, $barangay, 
                $prkstrtadd, $conname, $relationship, $emergency_connum
            );

            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid email address.';
        }

        header('Location: add-student.php'); 
        exit();
    }

    if (isset($_POST['addfacultypatient'])) {

        // Initialize variables from POST data
        $lname = $_POST['lastName'];
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $dob = $_POST['dob'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $connum = $_POST['contactNumber'];
        $sex = $_POST['sex'];
        $idnum = $_POST['facultyID'];
        $college = $_POST['college'];
        $department = $_POST['department'];
        $role = $_POST['role'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $prkstrtadd = $_POST['street'];
        $conname = $_POST['emergencyContactName'];
        $relationship = $_POST['relationship'];
        $emergency_connum = $_POST['emergencyContactNumber'];

        $profile = ''; // Default to empty if no profile picture uploaded

        // Handle Profile Upload
        if (isset($_FILES['addprofile']) && $_FILES['addprofile']['error'] === UPLOAD_ERR_OK) {
            $profile = $_FILES['addprofile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $profile_tmp);
            $allowed_mimes = ['image/jpeg', 'image/png'];

            if (in_array($mime, $allowed_mimes)) {
                $profile_hash = md5(uniqid($profile_original_name, true));
                $profile_name = $profile_hash . '.' . strtolower(pathinfo($profile_original_name, PATHINFO_EXTENSION));
                $uploadDir = 'uploads/';
                $profile_destination = $uploadDir . $profile_name;

                // Move uploaded file
                if (move_uploaded_file($profile_tmp, $profile_destination)) {
                    $profile = $profile_name; // Set profile name to save to the database
                } else {
                    $_SESSION['error'] = 'Failed to upload profile picture.';
                    header('Location: add-faculty.php'); // Redirect to the add student page
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type. Only JPEG and PNG files are allowed.';
                header('Location: add-faculty.php'); 
                exit();
            }
            finfo_close($finfo);
        }

        // Validate email and proceed with insertion
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Call the addStudentPatient method and store the response
            $response = $patient->addFacultyPatient(
                $lname, $fname, $mname, $dob, $email, $connum, $sex, $profile, 'faculty', 
                date('Y-m-d'), password_hash($idnum, PASSWORD_DEFAULT), 'Active', 0, 
                $idnum, $college, $department, $role,
                $region, $province, $municipality, $barangay, 
                $prkstrtadd, $conname, $relationship, $emergency_connum
            );

            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid email address.';
        }

        header('Location: add-faculty.php'); 
        exit();
    }

    if (isset($_POST['addstaffpatient'])) {

        // Initialize variables from POST data
        $lname = $_POST['lastName'];
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $dob = $_POST['dob'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $connum = $_POST['contactNumber'];
        $sex = $_POST['sex'];
        $idnum = $_POST['staffID'];
        $office = $_POST['office'];
        $role = $_POST['role'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $prkstrtadd = $_POST['street'];
        $conname = $_POST['emergencyContactName'];
        $relationship = $_POST['relationship'];
        $emergency_connum = $_POST['emergencyContactNumber'];

        $profile = ''; // Default to empty if no profile picture uploaded

        // Handle Profile Upload
        if (isset($_FILES['addprofile']) && $_FILES['addprofile']['error'] === UPLOAD_ERR_OK) {
            $profile = $_FILES['addprofile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $profile_tmp);
            $allowed_mimes = ['image/jpeg', 'image/png'];

            if (in_array($mime, $allowed_mimes)) {
                $profile_hash = md5(uniqid($profile_original_name, true));
                $profile_name = $profile_hash . '.' . strtolower(pathinfo($profile_original_name, PATHINFO_EXTENSION));
                $uploadDir = 'uploads/';
                $profile_destination = $uploadDir . $profile_name;

                // Move uploaded file
                if (move_uploaded_file($profile_tmp, $profile_destination)) {
                    $profile = $profile_name; // Set profile name to save to the database
                } else {
                    $_SESSION['error'] = 'Failed to upload profile picture.';
                    header('Location: add-staff.php'); // Redirect to the add student page
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type. Only JPEG and PNG files are allowed.';
                header('Location: add-staff.php'); 
                exit();
            }
            finfo_close($finfo);
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = $patient->addStaffPatient(
                $lname, $fname, $mname, $dob, $email, $connum, $sex, $profile, 'Staff', 
                date('Y-m-d H:i:s'), password_hash($idnum, PASSWORD_DEFAULT), 'Active', 0, 
                $idnum, $office, $role,
                $region, $province, $municipality, $barangay, 
                $prkstrtadd, $conname, $relationship, $emergency_connum
            );

            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid email address.';
        }

        header('Location: add-staff.php'); 
        exit();
    }
    if (isset($_POST['addextensionpatient'])) {

        // Initialize variables from POST data
        $lname = $_POST['lastName'];
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $dob = $_POST['dob'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $connum = $_POST['contactNumber'];
        $sex = $_POST['sex'];
        $idnum = $_POST['extentID'];
        $role = $_POST['role'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $prkstrtadd = $_POST['street'];
        $conname = $_POST['emergencyContactName'];
        $relationship = $_POST['relationship'];
        $emergency_connum = $_POST['emergencyContactNumber'];

        $profile = ''; // Default to empty if no profile picture uploaded

        // Handle Profile Upload
        if (isset($_FILES['addprofile']) && $_FILES['addprofile']['error'] === UPLOAD_ERR_OK) {
            $profile = $_FILES['addprofile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $profile_tmp);
            $allowed_mimes = ['image/jpeg', 'image/png'];

            if (in_array($mime, $allowed_mimes)) {
                $profile_hash = md5(uniqid($profile_original_name, true));
                $profile_name = $profile_hash . '.' . strtolower(pathinfo($profile_original_name, PATHINFO_EXTENSION));
                $uploadDir = 'uploads/';
                $profile_destination = $uploadDir . $profile_name;

                // Move uploaded file
                if (move_uploaded_file($profile_tmp, $profile_destination)) {
                    $profile = $profile_name; // Set profile name to save to the database
                } else {
                    $_SESSION['error'] = 'Failed to upload profile picture.';
                    header('Location: add-extension.php'); // Redirect to the add student page
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid file type. Only JPEG and PNG files are allowed.';
                header('Location: add-extension.php'); 
                exit();
            } 
            finfo_close($finfo);
        }

        // Validate email and proceed with insertion
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Call the addStudentPatient method and store the response
            $response = $patient->addExtenPatient(
                $lname, $fname, $mname, $dob, $email, $connum, $sex, $profile, 'Extension', 
                date('Y-m-d H:i:s'), password_hash($idnum, PASSWORD_DEFAULT), 'Active', 0, 
                $idnum, $role,
                $region, $province, $municipality, $barangay, 
                $prkstrtadd, $conname, $relationship, $emergency_connum
            );

            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid email address.';
        }

        header('Location: add-extension.php'); 
        exit();
    }
} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';

    header('Location: patient-record.php'); 
    exit();
}
?>