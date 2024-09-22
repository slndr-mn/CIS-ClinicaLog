<?php
session_start();
include('../database/config.php');
include('../php/user.php');
include('../php/medicine.php');

$db = new Database();
$conn = $db->getConnection(); 

$medicine = new Medicine($conn); 

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
                  <div class="card">
                    <div class="card-header">
                      <div class="d-flex align-items-center">
                        <h4 class="card-title">Patient</h4>
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
                                <a href="add-staff.php">
                                  <button type="button" class="btn btn-primary btn-round ms-auto custom-button" id="addbutton">
                                    Staff
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
                              <th>Name</th>
                              <th>Patient ID</th>
                              <th>Phone Number</th>
                              <th>Last Visited Date</th>
                              <th>Clinic Staff</th>
                              <th>Reason</th>
                              <th style="width: 10%">Action</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th>Name</th>
                              <th>Patient ID</th>
                              <th>Phone Number</th>
                              <th>Last Visited Date</th>
                              <th>Clinic Staff</th>
                              <th>Reason</th>
                              <th>Action</th>
                            </tr>
                          </tfoot>
                          <tbody>
                          <tr>
                            <td>Jackilyn M. Furog</td>
                            <td>2022-00473</td>
                            <td>09756066512</td>
                            <td>06/28/2024</td>
                            <td>Nurse Tweet</td>
                            <td>Stomach Ache</td>
                            <td>
                              <div class="form-button-action">
                                <button
                                  id="viewButton"
                                  type="button"
                                  data-bs-toggle="tooltip"
                                  title=""
                                  class="btn btn-link btn-primary btn-lg"
                                >
                                  <i class="fa fa-eye"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <footer class="footer">
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