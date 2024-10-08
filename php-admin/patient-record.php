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
?>
<!DOCTYPE html> 
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Sample Index</title> 
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
                <div class="col-md-12">
                  <div>

                  <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                    <li>
                      <a class="nav-link active" href="" role="tab">All</a>
                    </li>
                    <li>
                      <a class="nav-link" href="" role="tab">Student</a>
                    </li>
                    <li>
                      <a class="nav-link" href="" role="tab">Faculty</a>
                    </li>
                    <li>
                      <a class="nav-link" href="" role="tab">Staff</a>
                    </li>
                    <li>
                      <a class="nav-link" href="" role="tab">Extension</a>
                    </li>
                  </ul>

                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="d-flex align-items-center">
                        <h4 class="card-title">Patients</h4>
                        <button
                          class="btn btn-primary btn-round ms-auto"
                          data-bs-toggle="modal"
                          data-bs-target="#addPatientModal"
                          id="addbutton"
                        >
                          <i class="fa fa-plus"></i>
                          Add Patient
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <!-- Modal -->
                      <div
                        class="modal fade"
                        id="addPatientModal"
                        tabindex="-1"
                        role="dialog"
                        aria-hidden="true"
                      >
                        <div class="modal-dialog modal-dialog-centered" role="document" id="AddPatient">
                          <div class="modal-content">
                            <div class="modal-header border-0">
                              <h5 class="modal-title">
                                <span class="fw-mediumbold">Add Patient</span>
                              </h5>
                              <button
                                type="button"
                                class="close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                                id="edit-exit"
                              >
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <form class="modalButton">
                                <!-- Button for Student Patient -->
                                <a href="add-student.php">
                                  <button type="button" class="btn btn-primary btn-round ms-auto custom-button" id="addbutton">
                                    Student
                                  </button>
                                </a> 
                                <!-- Button for Staff Patient -->
                                <a href="add-faculty.php">
                                  <button type="button" class="btn btn-primary btn-round ms-auto custom-button" id="addbutton">
                                    Faculty
                                  </button>
                                </a>
                                <a href="add-staff.php">
                                  <button type="button" class="btn btn-primary btn-round ms-auto custom-button" id="addbutton">
                                    Staff
                                  </button>
                                </a>
                                <a href="add-extension.php">
                                  <button type="button" class="btn btn-primary btn-round ms-auto custom-button" id="addbutton">
                                    Extension
                                  </button>
                                </a>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="table-responsive">
                      <table id="add-patient" class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>ID Number</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Sex</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th style="width: 10%">Action</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>No.</th>
                            <th>ID Number</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Sex</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        <?php
                        // Assuming you have called getAllPatientsTable() somewhere in your code
                        $nodes = $patient->getAllPatientsTable();
                        $index = 0; // Initialize the index outside the loop

                        foreach ($nodes as $node) {
                            // Check for patient_status property
                            $disableStatus = isset($node->status) && $node->status == 'Inactive' ? 'Disabled' : 'Enabled';
                            $statusColor = isset($node->status) && $node->status == 'Inactive' ? '#ff6961' : '#77dd77';

                            // Increment index for each node
                            $index++;

                            echo "<tr data-id='{$node->idnum}' 
                                        data-name='{$node->name}' 
                                        data-email='{$node->email}' 
                                        data-sex='{$node->sex}' 
                                        data-type='{$node->type}' 
                                        data-status='{$node->status}' class='patient-row'>
                                  <td>{$index}</td> <!-- For No. column -->
                                  <td>{$node->idnum}</td>
                                  <td>{$node->name}</td>
                                  <td>{$node->email}</td>
                                  <td>{$node->sex}</td>
                                  <td>{$node->type}</td>
                                  <td>
                                      <span style='
                                          display: inline-block;
                                          padding: 5px 10px;
                                          border-radius: 50px;
                                          background-color: {$statusColor}; /* Determine color based on status if needed */
                                          color: white; 
                                          text-align: center;
                                          min-width: 60px;'>
                                          {$node->status}
                                      </span>
                                  </td>
                                  <td>
                                      <div class='form-button-action'>
                                          <button type='button' class='btn btn-link btn-primary btn-lg viewButton' 
                                                  data-id='{$node->idnum}' data-type='{$node->type}'>
                                              <i class='fa fa-eye'></i>
                                          </button>
                                      </div>
                                  </td>
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


    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>


    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    
    <script>
        $(document).ready(function() {
        $('#add-patient').DataTable({
            responsive: true
        });
    });
    </script>
    <script>
      $(document).ready(function() {
          $('#dynamic-table').DataTable();
          loadDefaultTable(); 
      });
      function loadDefaultTable() {
        $("#tablechange").load("studentstable.php", function(response, status, xhr) {
            if (status === "error") {
                console.log("Error loading studentstable: " + xhr.status + " " + xhr.statusText);
            }
        });
      }

        // Function to dynamically load content into the tablechange div based on the selected type
        function changeTableContent(type) {
            let data = [];

            // Load different tables based on the type selected
            if (type === 'students') {
                $("#tablechange").load("studentstable.php", function(response, status, xhr) {
                    if (status === "error") {
                        console.log("Error loading studentstable: " + xhr.status + " " + xhr.statusText);
                    }
                });
            } else if (type === 'faculties') {
                $("#tablechange").load("faculty.php", function(response, status, xhr) {
                    if (status === "error") {
                        console.log("Error loading faculty table: " + xhr.status + " " + xhr.statusText);
                    }
                });
            } else if (type === 'staffs') {
                $("#tablechange").load("stafftable.php", function(response, status, xhr) {
                    if (status === "error") {
                        console.log("Error loading staff table: " + xhr.status + " " + xhr.statusText);
                    }
                });
            }

            // Optionally populate the table with empty data for default behavior
            populateTable(data);
        }
    </script>
      
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Add event listener for view buttons
        document.querySelectorAll('.viewButton').forEach(function (button) {
            button.addEventListener('click', function () {
                // Retrieve the data-id and data-type from the button
                const patientId = this.getAttribute('data-id');
                const patientType = this.getAttribute('data-type');

                // Determine the file to redirect to based on the patient type
                let fileName = '';
                switch (patientType.toLowerCase()) {
                    case 'student':
                        fileName = 'patient-studprofile.php';
                        break;
                    case 'faculty':
                        fileName = 'patient-facultyprofile.php';
                        break;
                    case 'staff':
                        fileName = 'patient-staffprofile.php';
                        break;
                    case 'extension':
                        fileName = 'patient-extensionprofile.php';
                        break;
                    default:
                        fileName = 'patient-record.php'; // Optional: Handle unknown types
                        break;
                }

                // Redirect to the appropriate page, passing the patient ID as a query parameter
                const url = `${fileName}?id=${patientId}`;
                window.location.href = url;
            });
        });
    });

    </script>
    <script>
    $(document).ready(function() {
       
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
</body>
</html>