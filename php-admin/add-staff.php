<?php
session_start();
?>

<!DOCTYPE html> 
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Sample Index</title> 
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /> 
    <link rel="icon" href="../assets/img/ClinicaLog.ico" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

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
            <h2>Add Staff Patient</h2>
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h4 class="card-title">Personal Details</h4>
                </div>
              </div>
              <div class="card-body" id="InputInfo">
                <!-- Form Starts Here -->
                <form action="patientcontrol.php" method="POST" enctype="multipart/form-data">                  <!-- Name Fields -->
                <div class="row">
                  <div class="col-md-3 mb-3">
                        <label for="Profile" class="form-label">Profile Upload</label>
                        <input id="addprofile" name="addprofile" type="file" class="form-control" accept=".png, .jpg, .jpeg" /> 
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label for="lastName" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required />
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="firstName" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required />
                    </div>
                    <div class="col-md-2 mb-3">
                      <label for="middleName" class="form-label">Middle Name</label>
                      <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" />
                    </div>
                    <!-- Date of Birth -->
                    <div class="col-md-2 mb-3">
                      <label for="dob" class="form-label">Date of Birth</label>
                      <input type="date" class="form-control" id="dob" name="dob" required />
                    </div>
                    <div class="col-md-2 mb-3">
                      <label for="sex" class="form-label">Sex</label>
                      <select class="form-select form-control" id="sex" name="sex">
                      <option selected disabled>Select Sex</option>
                      <option value="Female">Female</option>
                      <option value="Male">Male</option>
                      </select>
                    </div>
                  </div>

                  <!-- ID and Work Info -->
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="staffID" class="form-label">ID Number</label>
                      <input type="text" class="form-control" id="staffID" name="staffID" placeholder="Enter ID number" required />
                    </div>

                  <!-- Program Dropdown -->
                    <div class="col-md-4 mb-3">
                      <label for="office" class="form-label">Office</label>
                      <select class="form-select  form-control" id="office" name="office" required>
                        <option selected disabled>Select Office</option>
                        <option value="Office of the Chancellor">Office of the Chancellor</option>
                        <option value="Administrative Office">Administrative Office</option>
                        <option value="Campus Registrar Office">Campus Registrar Office</option>
                        <option value="Office of Student Affairs and Services">Office of Student Affairs and Services</option>
                        <option value="Campus Clinic">Campus Clinic</option>
                        <option value="System and Data Management Division Office (SDMD)">System and Data Management Division Office (SDMD)</option>
                        <option value="CTET Dean's Office">CTET Dean's Office</option>
                        <option value="CARS Dean's Office">CARS Dean's Office</option>
                        <option value="CoE Office">CoE Office</option>
                        <option value="SOM Dean's Office">SOM Dean's Office</option>
                        <option value="University Learning Resource Center Office (ULRC)">University Learning Resource Center Office (ULRC)</option>
                        <option value="Corporate Enterprise Development Unit Office (CEDU)">Corporate Enterprise Development Unit Office (CEDU)</option>
                        <option value="CTET Graduate School">CTET Graduate School</option>
                        <option value="CARS Graduate School">CARS Graduate School</option>
                        <!-- Add more programs as needed -->
                      </select>
                    </div>

                    <div class="col-md-2 mb-3">
                      <label for="role" class="form-label">Role</label>
                      <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role" required />
                    </div>
                  </div>

                  <!-- Address Fields -->
                  <h5>Current Address</h5>
                  <div class="row">
                    <!-- Region Dropdown -->
                    <div class="col-md-2 mb-3">
                      <label for="region" class="form-label">Region</label>
                      <select class="form-select form-control" id="region" name="region" required>
                        <option selected disabled>Select Region</option>
                        <!-- Options for Region will go here -->
                      </select>
                    </div>

                    <!-- Province Dropdown -->
                    <div class="col-md-3 mb-3">
                      <label for="province" class="form-label">Province</label>
                      <select class="form-select form-control" id="province" name="province" required>
                        <option selected disabled>Select Province</option>
                        <!-- Options for Province will go here -->
                      </select>
                    </div>

                    <!-- Municipality Dropdown -->
                    <div class="col-md-3 mb-3">
                      <label for="municipality" class="form-label">Municipality</label>
                      <select class="form-select form-control" id="municipality" name="municipality" required>
                        <option selected disabled>Select Municipality</option>
                        <!-- Options for Municipality will go here -->
                      </select>
                    </div>

                    <!-- Barangay Dropdown -->
                    <div class="col-md-2 mb-3">
                      <label for="barangay" class="form-label">Barangay</label>
                      <select class="form-select form-control" id="barangay" name="barangay" required>
                        <option selected disabled>Select Barangay</option>
                        <!-- Options for Barangay will go here -->
                      </select>
                    </div>

                    <!-- Street Input (Text Field) -->
                    <div class="col-md-2 mb-3">
                      <label for="street" class="form-label">Purok/Block No./Street</label>
                      <input type="text" class="form-control form-control" id="street" name="street" placeholder="Enter street address" required />
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
                      <button type="submit" class="btn btn-primary" id="addstaffpatient" name="addstaffpatient">
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


<?php
  if (isset($_SESSION['message'])) {
    echo "<script>
            swal({
                title: 'Message',
                text: '" . htmlspecialchars($_SESSION['message'], ENT_QUOTES) . "',
                icon: '" . ($_SESSION['status'] === 'success' ? 'success' : 'error') . "',
                button: 'OK',
            });
          </script>";

    unset($_SESSION['message']);
    unset($_SESSION['status']);

    }
    ?>

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

<script>
  const addressData = {
  regions: {
    "Region XI": {
      provinces: {
        "Davao del Norte": {
          municipalities: ["Tagum City", "Sto. Tomas"],
          barangays: {
            "Tagum City": ["Apokon", "Pagsabangan"],
            "Sto. Tomas": ["Kinamayan", "Poblacion"]
          }
        },
        "Davao de Oro": {
          municipalities: ["Pantukan", "Nabunturan"],
          barangays: {
            "Pantukan": ["Kingking", "Magnaga"],
            "Nabunturan": ["Anislagan", "Poblacion"]
          }
        }
      }
    },
    "Region XII": {
      provinces: {
        "Cotabato": {
          municipalities: ["Alamada", "Carmen"],
          barangays: {
            "Alamada": ["Camansi", "Macabasa"],
            "Carmen": ["Bentangan", "General Luna"]
          }
        }
      }
    }
  }
};

function populateDropdown(dropdown, options) {
  dropdown.innerHTML = '<option selected disabled>Select</option>';
  options.forEach(option => {
    const opt = document.createElement("option");
    opt.value = option;
    opt.textContent = option;
    dropdown.appendChild(opt);
  });
}

// Populate Regions
const regionSelect = document.getElementById("region");
populateDropdown(regionSelect, Object.keys(addressData.regions));

// Handle region change
regionSelect.addEventListener("change", function() {
  const selectedRegion = this.value;
  const provinces = Object.keys(addressData.regions[selectedRegion].provinces);
  populateDropdown(document.getElementById("province"), provinces);
});

// Handle province change
document.getElementById("province").addEventListener("change", function() {
  const selectedRegion = regionSelect.value;
  const selectedProvince = this.value;
  const municipalities = addressData.regions[selectedRegion].provinces[selectedProvince].municipalities;
  populateDropdown(document.getElementById("municipality"), municipalities);
});

// Handle municipality change
document.getElementById("municipality").addEventListener("change", function() {
  const selectedRegion = regionSelect.value;
  const selectedProvince = document.getElementById("province").value;
  const selectedMunicipality = this.value;
  const barangays = addressData.regions[selectedRegion].provinces[selectedProvince].barangays[selectedMunicipality];
  populateDropdown(document.getElementById("barangay"), barangays);
});
</script>
</body>
</html>