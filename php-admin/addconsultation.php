<?php
session_start();
include('../database/config.php');
include('../php/user.php');
include('../php/medicine.php');
include('../php/patient.php');
@include('../php/patient-studprofile.php');
@include('../php/patient-staffprofile.php');
@include('../php/patient-facultyprofile.php');
@include('../php/patient-extensionprofile.php');
include('../php/consultation.php');


$db = new Database();
$conn = $db->getConnection();
$conn2 = $db->getConnection();

$consultations = new ConsultationManager($conn);
$medicine = new MedicineManager($conn2);

$patientId = isset($_GET['id']) ? $_GET['id'] : null;
$patientType = isset($_GET['patient_patienttype']) ? $_GET['patient_patienttype'] : null;

$patientDetails = null;

// Handle the AJAX request to fetch suggestions
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
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Clinic Staff User</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /> 
    <link rel="icon" href="../assets/img/ClinicaLog.ico" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({ 
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["../css/fonts.min.css"], 
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script> 

    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/plugins.min.css" />
    <link rel="stylesheet" href="../css/kaiadmin.min.css" />

    <!-- ICONS -->
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <style>
      .sidebar {
          transition: background 0.3s ease;
          /* Initial background */
          background: linear-gradient(to bottom, #DB6079, #DA6F65, #E29AB4);
      }
      .logo-header {
          transition: background 0.3s ease;
      }
      .nav-item.active {
            background-color: rgba(0, 0, 0, 0.1); 
            color: #fff; 
        }

        .nav-item.active i {
            color: #fff;
        }

  </style>  
</head>
<body>
<div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar"></div>
        <!-- End Sidebar -->
        <div class="main-panel">
            <!-- Header -->
            <div class="main-header" id="header"></div>
            <!-- Main Content -->
            <div class="container" id="content">
            <div class="page-inner">
                    <div class="row">
  

                        <!-- Add Consultation Form -->
                        <div class="col-md-12">
                            <div class="card card-equal-height">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Add Consultation</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="consultationcontrol.php" method="POST">
                                        <div class="form-group mb-3">
                                            <label for="pname">Search by Name or ID:</label>
                                            <input type="text" id="pname" name="pname" class="form-control" placeholder="Search" autocomplete="off" required>
                                            <div class="form-control" id="suggestions"></div>
                                            <!-- Hidden form field to store selected patient ID -->
                                            <input type="hidden" id="selected_patient_id" name="selected_patient_id">
                                            
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
                                            <button type="submit" class="btn btn-primary" name="addcon" id="addcon">Submit</button>
                                            <button type="reset" class="btn btn-secondary ms-2">Clear</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- List of Consultations -->
                        <div class="col-md-12">
                            <div class="card card-equal-height">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title">Consultations List</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table id="add-con" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name:</th>
                                    <th>Diagnosis</th>
                                    <th>Prescribed Medicine:</th>
                                    <th>Quantity:</th>
                                    <th>Remark</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Time Spent</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name:</th>
                                    <th>Diagnosis</th>
                                    <th>Prescribed Medicine:</th>
                                    <th>Quantity:</th>
                                    <th>Remark</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Time Spent</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            var_dump($_POST); // Show all POST data to confirm what is being submitted
$items = $consultations->getConsultations();
// echo '<pre>';
// var_dump($items);
// echo '</pre>';
foreach ($items as $item) {
    if (is_array($item)) {
        // Convert the time_in and time_out into DateTime objects
        $time_in = new DateTime($item['time_in'] ?? '');  // Change 'in' to 'time_in'
        $time_out = new DateTime($item['time_out'] ?? ''); // Change 'out' to 'time_out'
        
        // Calculate the difference between the two times
        $interval = $time_in->diff($time_out);
        
        // Format the difference (e.g., hours and minutes)
        $time_spent = $interval->format('%H hours %I minutes');
        
        // Display the data in the table
        echo "<tr data-id='{$item['consultation_id']}' 
                data-stock='{$item['patient_idnum']}'>
                <td>{$item['name']}</td>
                <td>{$item['diagnosis']}</td>
                <td>{$item['treatment_medname']}</td>
                <td>{$item['treatment_medqty']}</td>
                <td>{$item['remark']}</td>
                <td>{$item['consult_date']}</td>
                <td>{$item['time_in']}</td>
                <td>{$item['time_out']}</td>
                <td>{$time_spent}</td> 
                <td>
                <div class='form-button-action'>
                    <button type='button' class='btn btn-link btn-primary btn-lg editConButton'>
                        <i class='fa fa-edit'></i>
                    </button>
                </div>
            </td>
            </tr>";
    } else {
        // Handle unexpected data type
        echo "<tr><td colspan='9'>Invalid data format</td></tr>";
    }
}
?>

                        </tbody>
                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
        </div>
    </div>
</div>

    
<!-- Include JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).ready(function() {

        $("#add-con").DataTable({
        pageLength: 10,
    });
       
    <?php if (isset($_SESSION['status']) && isset($_SESSION['message'])): ?>
        var status = '<?php echo $_SESSION['status']; ?>';
        var message = '<?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES); ?>';
        Swal.fire({
            title: status === 'success' ? "Success!" : "Error!",
            text: message,
            icon: status,
            confirmButtonText: "OK",
            confirmButtonColor: status === 'success' ? "#77dd77" : "#ff6961"
        }).then(() => {
            if (status === 'success') {
              sessionStorage.clear();
                window.location.href = "add-student.php";
            }
            <?php unset($_SESSION['status'], $_SESSION['message']); ?>
        });
    <?php endif; ?>
    
        $("#sidebar").load("sidebar.php", function(response, status, xhr) {
            if (status == "error") {
                console.log("Error loading sidebar: " + xhr.status + " " + xhr.statusText);
            } else {
                
                var currentPage = window.location.pathname.split('/').pop(); 

                $('.nav-item').removeClass('active');

                $('.nav-item').each(function() {
                    var href = $(this).find('a').attr('href');
                    if (href.indexOf(currentPage) !== -1) {
                        $(this).addClass('active');
                    }
                });
            }
        });

        $("#header").load("header.php", function(response, status, xhr) {
            if (status == "error") {
                console.log("Error loading header: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
</script>

<script>
$(document).ready(function () {
    // Initialize DataTable for consultations
    $("#consultation-table").DataTable({
        pageLength: 3, // Set the default number of rows per page
        responsive: true, // Make the table responsive
    });

    // Handle form submission to add consultation
    $("#addConsultationForm").on("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    // Get form data
    var consultationData = {
        patientId: $("#selected_patient_id").val(), // Get the selected patient ID
        diagnosis: $("#Diagnosis").val(),
        medName: $("#prescribemed").val(),
        medQty: $("#presmedqty").val(),
        remark: $("#Remarks").val(),
        consultDate: $("#date").val(), // Ensure you're fetching the correct date field
        timeIn: $("#in").val(), // Ensure you're fetching the correct time in field
        timeOut: $("#out").val(), // Ensure you're fetching the correct time out field
    };

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



</body>
</html>