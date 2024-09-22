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
            <h2>Add Student Patient</h2>
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="card-title">Personal Details</h4>
                </div>
              </div>
              <div class="card-body" id="InputInfo">
                <!-- Form Starts Here -->
                <form>
                  <!-- Name Fields -->
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="lastName" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="firstName" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required />
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="middleName" class="form-label">Middle Name</label>
                      <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" />
                    </div>
                  </div>

                  <!-- ID and Academic Info -->
                  <div class="row">
                    <div class="col-md-2 mb-3">
                      <label for="studentID" class="form-label">ID Number</label>
                      <input type="text" class="form-control" id="studentID" name="studentID" placeholder="Enter ID number" required />
                    </div>

                    <!-- Program Dropdown -->
                    <div class="col-md-4 mb-3">
                      <label for="program" class="form-label">Program</label>
                      <select class="form-select" id="program" name="program" required onchange="updateMajorOptions()">
                        <option selected disabled>Select Program</option>
                        <option value="Bachelor of Science in Secondary Education">Bachelor of Science in Secondary Education</option>
                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                        <option value="Bachelor of Science in Agricultural and Biosystems Engineering">Bachelor of Science in Agricultural and Biosystems Engineering</option>
                        <option value="Bachelor of Technical-Vocational Education">Bachelor of Technical-Vocational Education</option>
                        <option value="Bachelor of Special Needs Education">Bachelor of Special Needs Education</option>
                        <option value="Bachelor of Early Childhood Education">Bachelor of Early Childhood Education</option>
                        <option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>
                        <!-- Add more programs as needed -->
                      </select>
                    </div>

                    <!-- Major Dropdown -->
                    <div class="col-md-2 mb-3">
                      <label for="major" class="form-label">Major</label>
                      <select class="form-select" id="major" name="major" required>
                        <option selected disabled>Select Major</option>
                        <!-- Major options will be dynamically populated based on the Program -->
                      </select>
                    </div>

                    <!-- Section Dropdown -->
                     
                    <div class="col-md-2 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year" required>
                            <option selected disabled>Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                      <label for="section" class="form-label">Section</label>
                      <input type="text" class="form-control" id="section" name="section" placeholder="e.g., 3A" required />
                    </div>
                  </div>

                  <!-- Date of Birth -->
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="dob" class="form-label">Date of Birth</label>
                      <input type="date" class="form-control" id="dob" name="dob" required />
                    </div>
                  </div>

                  <!-- Address Fields -->
                  <h5>Current Address</h5>
                  <div class="row">
                    <div class="col-md-2 mb-3">
                      <label for="street" class="form-label">Purok/Block No./Street</label>
                      <input type="text" class="form-control" id="street" name="street" placeholder="Enter street address" required />
                    </div>
                    <div class="col-md-2 mb-3">
                      <label for="barangay" class="form-label">Barangay</label>
                      <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Enter barangay" required />
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="municipality" class="form-label">Municipality</label>
                      <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Enter municipality" required />
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="province" class="form-label">Province</label>
                      <input type="text" class="form-control" id="province" name="province" placeholder="Enter province" required />
                    </div>
                    <div class="col-md-2 mb-3">
                      <label for="region" class="form-label">Region</label>
                      <input type="text" class="form-control" id="region" name="region" placeholder="Enter region" required />
                    </div>
                  </div>

                  <!-- Contact Information -->
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required />
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="contactNumber" class="form-label">Contact Number</label>
                      <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" required />
                    </div>
                  </div>

                  <!-- Emergency Contact Information -->
                  <h5>Emergency Contact Information</h5>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
                      <input type="text" class="form-control" id="emergencyContactName" name="emergencyContactName" placeholder="Enter emergency contact name" required />
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="relationship" class="form-label">Relationship</label>
                      <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Enter relationship" required />
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="emergencyContactNumber" class="form-label">Emergency Contact Number</label>
                      <input type="tel" class="form-control" id="emergencyContactNumber" name="emergencyContactNumber" placeholder="Enter emergency contact number" required />
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12 text-center">
                      <button type="button" class="btn btn-primary" id="addpatient">
                        Submit
                      </button>
                      
                      <button type="button" class="btn btn-primary ms-3" id="canceladdpatient">
                        Cancel
                      </button>
                    </div>
                  </div>
                </form>
                <!-- End of Form -->
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

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="../assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Dynamically load the sidebar
            $("#sidebar").load("sidebar.php", function(response, status, xhr) {
                if (status == "error") {
                    console.log("Error loading sidebar: " + xhr.status + " " + xhr.statusText);
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

<script>
  function updateMajorOptions() {
    const program = document.getElementById("program").value;
    const major = document.getElementById("major");

    // Clear existing major options
    major.innerHTML = "<option selected disabled>Select Major</option>";

    // Define majors based on program selection
    const majorOptions = {
      "Bachelor of Science in Secondary Education": ["Filipino", "English", "Mathematics"],
      "Bachelor of Science in Information Technology": ["Information Security"],
      "Bachelor of Science in Agricultural and Biosystems Engineering": ["None"],
      "Bachelor of Technical-Vocational Education": ["Agricultural Crop Production", "Animal Production"],
      "Bachelor of Special Needs Education": ["None"],
      "Bachelor of Early Childhood Education": ["None"],
      "Bachelor of Elementary Education": ["None"],
    };

    // Get the relevant majors for the selected program
    if (majorOptions[program]) {
      majorOptions[program].forEach(function (majorName) {
        const option = document.createElement("option");
        option.value = majorName;
        option.textContent = majorName;
        major.appendChild(option);
      });
    }
  }
</script>

<script>
  $(document).ready(function () {
    $("#addpatient").click(function (e) {
      e.preventDefault(); // Prevent default form submission

      // Check if all required fields are filled
      let isValid = true;
      $("form [required]").each(function () {
        if (!$(this).val()) {
          isValid = false;
          $(this).addClass("is-invalid"); // Optionally add a class for styling
        } else {
          $(this).removeClass("is-invalid"); // Remove the invalid class if filled
        }
      });

      if (isValid) {
        // Show SweetAlert
        swal({
          title: "Success!",
          text: "Patient added successfully!",
          icon: "success",
          buttons: false,
          timer: 2000,
        }).then(() => {
          // Submit the form after the alert
          $("form").submit();
        });
      } else {
        // Optionally, show an alert or message if not valid
        swal({
          title: "Error!",
          text: "Please fill out all required fields.",
          icon: "error",
          buttons: true,
        });
      }
    });
  });

  $("#canceladdpatient").click(function (e) {
    swal({
        title: "Are you sure?",
        text: "Do you really want to cancel adding this patient? Unsaved information will be lost.",
        icon: "warning",
        buttons: {
            confirm: {
                text: "Yes, cancel it!",
                className: "btn btn-success",
            },
            cancel: {
                visible: true,
                className: "btn btn-danger",
            },
        },
    }).then((willCancel) => {
        if (willCancel) {
            // Redirect to patient-record.php
            window.location.href = "patient-record.php";
        } else {
            swal.close();
        }
    });
  });
</script>