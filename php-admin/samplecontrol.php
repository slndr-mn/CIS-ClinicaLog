
<?php
session_start(); // Start the session at the beginning of your script

    // Include the file containing your class definition
    include('../database/config.php');
    include('../php/patient.php');

    $db = new Database();
    $conn = $db->getConnection();
    $patientManager = new PatientManager($conn);
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Personal Information
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $connum = $_POST['connum'];
    $sex = $_POST['sex'];
    
    // Handle profile picture upload (if any)
    $profile = '';
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $profile_tmp = $_FILES['profile']['tmp_name'];
        $profile_original_name = $_FILES['profile']['name'];
        $profile_hash = md5(uniqid($profile_original_name, true));
        $profile_ext = pathinfo($profile_original_name, PATHINFO_EXTENSION);
        $profile_name = $profile_hash . '.' . strtolower($profile_ext);
        $upload_dir = 'uploads/';
        $profile = $upload_dir . $profile_name;
        
        // Move the uploaded file to the uploads directory
        move_uploaded_file($profile_tmp, $profile);
    }

    // Faculty Details
    $idnum = $_POST['idnum'];
    $college = $_POST['college'];
    $depart = $_POST['depart'];
    $role = $_POST['role'];

    // Address Information
    $region = $_POST['region'];
    $province = $_POST['province'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $prkstrtadd = $_POST['prkstrtadd'];

    // Emergency Contact
    $conname = $_POST['conname'];
    $relationship = $_POST['relationship'];
    $emergency_connum = $_POST['emergency_connum'];

    // Additional details
    $type = 'faculty'; // Assuming 'faculty' is the type for faculty patients
    $dateadded = date('Y-m-d');
    $password = password_hash($idnum, PASSWORD_DEFAULT); // Password hashed from ID number
    $status = 'active'; // Assuming all new faculty patients are 'active'
    $code = rand(100000, 999999); // Random code for new users

    // Call the addFacultyPatient method
    $response = $patientManager->addFacultyPatient(
        $lname, $fname, $mname, $dob, $email, $connum, $sex, $profile, $type, $dateadded, 
        $password, $status, $code, $idnum, $college, $depart, $role, 
        $region, $province, $municipality, $barangay, $prkstrtadd, $conname, 
        $relationship, $emergency_connum
    );

    // Handle the response
    if ($response['status'] === 'success') {
        echo "Faculty patient added successfully!";
    } else {
        echo "Error: " . $response['message'];
    }
}
?>
