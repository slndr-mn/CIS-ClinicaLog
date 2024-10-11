<?php
// Include the necessary files for database connection and classes
include('../database/config.php');
include('../php/consultation.php');
include('../php/patient.php');
include('../php/medicine.php');

$db = new Database();
$conn = $db->getConnection();
$conn2 = $db->getConnection();


$consultations = new ConsultationManager($conn);
$medicine = new MedicineManager($conn2);

if (isset($_POST['pname'])) {
    $searchQuery = "%" . $_POST['pname'] . "%"; // Add wildcard for partial matching

    // SQL Query to search patient names and ID numbers across different types
    $sql = "SELECT 
                p.patient_id, 
                CONCAT(p.patient_fname, ' ', p.patient_lname) AS name, 
                CASE 
                    WHEN p.patient_patienttype = 'Student' THEN ps.student_idnum
                    WHEN p.patient_patienttype = 'Faculty' THEN pf.faculty_idnum
                    WHEN p.patient_patienttype = 'Staff' THEN pst.staff_idnum
                    WHEN p.patient_patienttype = 'Extension' THEN pe.exten_idnum
                    ELSE NULL 
                END AS idnum
            FROM 
                patients p
            LEFT JOIN patstudents ps ON p.patient_id = ps.student_patientid
            LEFT JOIN patfaculties pf ON p.patient_id = pf.faculty_patientid
            LEFT JOIN patstaffs pst ON p.patient_id = pst.staff_patientid
            LEFT JOIN patextensions pe ON p.patient_id = pe.exten_patientid
            WHERE 
                p.patient_lname LIKE ? OR p.patient_fname LIKE ? OR 
                ps.student_idnum LIKE ? OR pf.faculty_idnum LIKE ? OR pst.staff_idnum LIKE ? OR pe.exten_idnum LIKE ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery, $searchQuery]);

    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results as suggestions
    if ($patients) {
        foreach ($patients as $p) {
            echo "<div class='suggestion' data-id='{$p['patient_id']}'>
                    {$p['name']} ({$p['idnum']})
                  </div>";
        }
    } else {
        echo "<div>No results found</div>";
    }
    exit(); // End script execution for the AJAX call
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<form id="addConsultationForm" action="consultationcontrol.php" method="POST">
    <div class="form-group mb-3">
        <label for="pname">Search by Name or ID:</label>
        <input type="text" id="pname" name="pname" class="form-control" placeholder="Search" autocomplete="off" required>
        <div class="form-control" id="suggestions"></div>
        <!-- Hidden form field to store selected patient ID -->
        <input type="hidden" id="selected_patient_id" name="selected_patient_id" required>
    </div>

    <div class="form-group mb-3">
        <label for="Diagnosis">Diagnosis:</label>
        <input type="text" id="Diagnosis" name="Diagnosis" class="form-control" placeholder="Enter diagnosis" required />
    </div>

    <div class="form-group mb-3">
        <label for="prescribemed">Treatment:</label>
        <select name="prescribemed" id="prescribemed" class="form-control" required>
    <option value="" disabled selected>Select Medicine</option>
    <?php
                                                $medicines = $medicine->getAllMedicines();
                                                foreach ($medicines as $med) {
                                                    echo "<option value='" . $med->medstock_id . "'>" . $med->medicine_name . "</option>";
                                                }
                                                ?>
</select>

        <br>
        <label for="presmedqty">Quantity:</label>
        <input type="number" id="presmedqty" name="presmedqty" class="form-control" placeholder="Enter Quantity" required>
        <br>
        <label for="presmednotes">Notes:</label>
        <input type="text" id="presmednotes" name="presmednotes" class="form-control" placeholder="Enter notes" />
    </div>

    <div class="form-group mb-3">
        <label for="Remarks">Remarks:</label>
        <input type="text" id="Remarks" name="Remarks" class="form-control" placeholder="Enter Remarks" required />
    </div>

    <div class="form-group mb-3">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" class="form-control" />
    </div>

    <script>
        // Set the current date as the default value for the date input
        window.onload = function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd; // Format as YYYY-MM-DD
            document.getElementById('date').value = today;
        };
    </script>

    <div class="form-group mb-3">
        <label for="in">Time In:</label>
        <input type="time" id="in" name="in" class="form-control" />
    </div>

    <div class="form-group mb-3">
        <label for="out">Time Out:</label>
        <input type="time" id="out" name="out" class="form-control" />
    </div>

    <div class="modal-footer border-0 mt-auto">
        <button type="submit" class="btn btn-primary" name="addcon">Submit</button>
        <button type="reset" class="btn btn-secondary ms-2">Clear</button>
    </div>
</form>

<script>
// Live search implementation
$(document).ready(function() {
    $('#pname').keyup(function() {
        var query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: "searchpatient.php",
                method: "POST",
                data: {query: query},
                success: function(data) {
                    $('#suggestions').fadeIn();
                    $('#suggestions').html(data);
                }
            });
        } else {
            $('#suggestions').fadeOut();
        }
    });

    $(document).on('click', 'li', function() {
        $('#pname').val($(this).text());
        $('#selected_patient_id').val($(this).attr('data-id'));
        $('#suggestions').fadeOut();
    });
});
</script>

<script>
$(document).ready(function () {
    $('#pname').on('keyup', function () {
        var query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: 'addconsultation.php', // Send request to the same file
                method: 'POST',
                data: { pname: query },
                success: function (data) {
                    $('#suggestions').html(data);
                }
            });
        } else {
            $('#suggestions').html(''); // Clear suggestions if query is too short
        }
    });

    $(document).on('click', '.suggestion', function () {
        var name = $(this).text().split(' (')[0]; // Extract only the name part before " ("
        var patientId = $(this).data('id'); // Get the ID from data-id attribute
        alert("Selected patient ID: " + patientId); // Show the fetched patient ID in an alert
        $('#selected_patient_id').val(patientId); // Store patient ID in hidden field
        $('#pname').val(name); // Set the patient name in the input field
        $('#suggestions').html(''); // Clear suggestions after selection
    });
});

</script>

<script>
    $(document).ready(function() {

        $("#add-con").DataTable({
        pageLength: 10,
    });
    <?php
session_start();

// Check for messages
if (isset($_SESSION['message'])) {
    $status = $_SESSION['status'] === 'success' ? 'success' : 'error';
    echo "<script>alert('{$_SESSION['message']}');</script>";
    // Unset the message after displaying
    unset($_SESSION['message']);
    unset($_SESSION['status']);
}
?>

     
    });
</script>

</body>
</html>
