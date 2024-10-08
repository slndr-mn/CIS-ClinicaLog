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
            echo "<div class='suggestion' data-id='{$p['idnum']}'>
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
                                    <form id="addConsultationForm" action="consultationcontrol.php" method="POST">
                                        <!-- <input type="hidden" id="patient_idnum" name="patient_idnum" value="<?= $patientId ?>" /> -->

                                        <div class="form-group mb-3">
                                            <label for="pname">Search by Name or ID:</label>
                                            <input type="text" id="pname" name="pname" class="form-control" placeholder="Search" autocomplete="off" required>
                                            <div class="form-control" id="suggestions"></div>
                                            <!-- Hidden form field to store selected patient ID -->
                                            <input type="hidden" id="selected_patient_id" name="patient_idnum">
                                            
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
                                                    <th>Diagnosis</th>
                                                    <th>Prescribed Medicine</th>
                                                    <th>Quantity</th>
                                                    <th>Remarks</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $consultationsList = $consultations->getAllConsultations($consultations);
                                                foreach ($consultationsList as $consult) {
                                                    echo "<tr>
                                                        <td>{$consult['diagnosis']}</td>
                                                        <td>{$consult['medicine_name']}</td>
                                                        <td>{$consult['quantity']}</td>
                                                        <td>{$consult['remarks']}</td>
                                                        <td>{$consult['date']}</td>
                                                    </tr>";
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
        pageLength: 7,
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

                                                    // When a suggestion is clicked, fill the input and store patient ID, and auto-submit the form
                                                    $(document).on('click', '.suggestion', function () {
                                                        var name = $(this).text();
                                                        var patientId = $(this).data('id');
                                                        $('#pname').val(name);
                                                        $('#selected_patient_id').val(patientId);
                                                        $('#suggestions').html(''); // Clear suggestions after selection

                                                        // Automatically submit the form once a patient is selected
                                                        //$('#consultationForm').submit();
                                                    });
                                                });
</script>

</body>
</html>