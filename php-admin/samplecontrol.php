
    <?php
session_start(); // Start the session at the beginning of your script

    // Include the file containing your class definition
    include('../database/config.php');
    include('../php/patient.php');

    $db = new Database();
    $conn = $db->getConnection();
    $patient = new PatientManager($conn); // Correctly instantiate the PatientManager class

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $connum = $_POST['connum'];
        $sex = $_POST['sex'];
        $profile = $_POST['profile'];
        $type = $_POST['type'];
        $dateadded = date("Y-m-d"); // Use the current date or modify as needed
        $password = $_POST['password'];
        $status = $_POST['status'];
        $code = $_POST['code'];
        $idnum = $_POST['idnum'];
        $program = $_POST['program'];
        $major = $_POST['major'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $region = $_POST['region'];
        $province = $_POST['province'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $prkstrtadd = $_POST['prkstrtadd'];
        $conname = $_POST['conname'];
        $relationship = $_POST['relationship'];
        $emergency_connum = $_POST['emergency_connum'];

        // Call the addStudentPatient function
        $response = $patient->addStudentPatient( // Use the correct instance variable here
            $lname, $fname, $mname, $dob, $email, $connum, $sex,
            $profile, $type, $dateadded, $password, $status, $code,
            $idnum, $program, $major, $year, $section, $region,
            $province, $municipality, $barangay, $prkstrtadd,
            $conname, $relationship, $emergency_connum
        );

         // Store the response message in a session variable
            $_SESSION['message'] = $response['message'];
            $_SESSION['status'] = $response['status'];

            // Redirect back to the form page
            header('Location: sampleform.php'); // Replace 'form.php' with the actual form page
            exit(); // Make sure to exit after the header redirect
    }
    ?>

