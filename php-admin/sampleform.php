<?php
session_start();

include('../database/config.php');
include('../php/user.php');
include('../php/medicine.php');
include('../php/patient.php');

$db = new Database();
$conn = $db->getConnection();

$patient = new PatientManager($db);
$user = new User($conn);
$user_id = $_SESSION['user_id'];
$userData = $user->getUserData($user_id);

$patientId = isset($_GET['id']) ? $_GET['id'] : null;
$patientDetails = $patient->getStudentData($patientId);

// Print the patient_id
if ($patientDetails) {
    // Optional: You could log this or handle it differently.
    // echo "Patient ID: " . $patientDetails['patient_id']; // Debugging line, uncomment if needed
} else {
    echo "No patient details found.";
    exit; // Stop execution if no patient details are found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information Form</title>
    <style>
        /* Basic styles for the form */
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <h2>Patient Information</h2>
    <form id="patientForm">
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName">
        </div>
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName">
        </div>
        <div class="form-group">
            <label for="middleName">Middle Name:</label>
            <input type="text" id="middleName" name="middleName">
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob">
        </div>
        <div class="form-group">
            <label for="sex">Sex:</label>
            <select id="sex" name="sex">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="program">Program:</label>
            <input type="text" id="program" name="program">
        </div>
        <div class="form-group">
            <label for="major">Major:</label>
            <input type="text" id="major" name="major">
        </div>
        <div class="form-group">
            <label for="year">Year:</label>
            <input type="number" id="year" name="year">
        </div>
        <div class="form-group">
            <label for="section">Section:</label>
            <input type="text" id="section" name="section">
        </div>
        <div class="form-group">
            <label for="region">Region:</label>
            <input type="text" id="region" name="region">
        </div>
        <div class="form-group">
            <label for="province">Province:</label>
            <input type="text" id="province" name="province">
        </div>
        <div class="form-group">
            <label for="municipality">Municipality:</label>
            <input type="text" id="municipality" name="municipality">
        </div>
        <div class="form-group">
            <label for="barangay">Barangay:</label>
            <input type="text" id="barangay" name="barangay">
        </div>
        <div class="form-group">
            <label for="street">Street:</label>
            <input type="text" id="street" name="street">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber">
        </div>
        <div class="form-group">
            <label for="emergencyContactName">Emergency Contact Name:</label>
            <input type="text" id="emergencyContactName" name="emergencyContactName">
        </div>
        <div class="form-group">
            <label for="relationship">Relationship:</label>
            <input type="text" id="relationship" name="relationship">
        </div>
        <div class="form-group">
            <label for="emergencyContactNumber">Emergency Contact Number:</label>
            <input type="tel" id="emergencyContactNumber" name="emergencyContactNumber">
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" id="status" name="status">
        </div>
        <button type="submit">Submit</button>
    </form>

    <script>
        // Passing PHP data to JavaScript
        var patientData = <?php echo json_encode($patientDetails); ?>;

        // Function to populate form inputs with patient data
        function populatePatientForm(patientData) {
            if (patientData.error) {
                console.error(patientData.error);
                return; // Exit if there's an error
            }

            // Populate patient details
            document.getElementById('lastName').value = patientData.patient.patient_lname || '';
            document.getElementById('firstName').value = patientData.patient.patient_fname || '';
            document.getElementById('middleName').value = patientData.patient.patient_mname || '';
            document.getElementById('dob').value = patientData.patient.patient_dob || '';
            document.getElementById('sex').value = patientData.patient.patient_sex || 'Male';
            document.getElementById('studentID').value = patientData.student.student_idnum || '';

            document.getElementById('program').value = patientData.student.student_program || '';
            document.getElementById('major').value = patientData.student.student_major || '';
            document.getElementById('year').value = patientData.student.student_year || '';
            document.getElementById('section').value = patientData.student.student_section || '';
            document.getElementById('region').value = patientData.address.address_region || '';
            document.getElementById('province').value = patientData.address.address_province || '';
            document.getElementById('municipality').value = patientData.address.address_municipality || '';
            document.getElementById('barangay').value = patientData.address.address_barangay || '';
            document.getElementById('street').value = patientData.address.address_prkstrtadd || '';
            document.getElementById('email').value = patientData.patient.patient_email || '';
            document.getElementById('contactNumber').value = patientData.patient.patient_connum || '';
            document.getElementById('emergencyContactName').value = patientData.emergencyContact.emcon_conname || '';
            document.getElementById('relationship').value = patientData.emergencyContact.emcon_relationship || '';
            document.getElementById('emergencyContactNumber').value = patientData.emergencyContact.emcon_connum || '';
            document.getElementById('status').value = patientData.patient.patient_status || '';
        }

        // Populate the form when the page loads
        document.addEventListener("DOMContentLoaded", function() {
            populatePatientForm(patientData);
        });
    </script>

</body>
</html>
