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
$patientDetails = $patient->getStaffData($patientId);

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
      .profile-image {
        display: flex;
        justify-content: center ;
        align-items: center ;
        flex-direction: column;
        margin-bottom: 20px;
      }
  
      .profile-image img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        margin-bottom: 10px;
      }
  
      .upload-btn {
        margin-top: 10px;
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
    <?php
    // Assuming you have fetched the profile picture URL from the database
    //$profilePic = $user['profile_picture']; // Replace with your database field for the profile pic
    //$defaultPic = "default-picture.png"; // Path to your default image

    // Check if the profile picture is empty or null and assign default picture if needed
    //$displayPic = !empty($profilePic) ? $profilePic : $defaultPic;
    ?>
        <div class="row">
        <div class="profile-image">
            <img id="profilePic" src="default-image.jpg" alt="Profile Image" />
            <button class="btn btn-primary" id="downloadBtn">Download Image</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center ">
                  <h4 class="card-title">Personal Details</h4>
                </div>
              </div>
              <div class="card-body" id="InputInfo">
                <!-- Form Starts Here -->
                <form action="patientcontrol.php" method="POST" enctype="multipart/form-data">   
    
    <!-- Name Fields -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="  last name" readonly />
        </div>
        <div class="col-md-3 mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="  first name" readonly />
        </div>
        <div class="col-md-2 mb-3">
            <label for="middleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="  middle name" readonly/>
        </div>
        <div class="col-md-2 mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" readonly />
        </div>
        <div class="col-md-2 mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select class="form-select form-control" id="sex" name="sex" readonly>
                <option selected disabled>Select Sex</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
            </select>
        </div>
    </div>

    <!-- ID and Academic Info -->
    <div class="row">
        <div class="col-md-2 mb-3">
            <label for="staffID" class="form-label">ID Number</label>
            <input type="text" class="form-control" id="staffID" name="staffID" placeholder="ID number" readonly />
        </div>

        <!--  Office Input -->
        <div class="col-md-2 mb-3">
            <label for="Office" class="form-label"> Office</label>
            <input type="text" class="form-control" id="Office" name="Office" placeholder="Office" readonly />
        </div>

        <div class="col-md-2 mb-3">
            <label for="role" class="form-label">role</label>
            <input type="text" class="form-control" id="role" name="role" placeholder="Role" readonly />
        </div>
    </div>

    <!-- Address Fields -->
    <h5>Current Address</h5>
    <div class="row">
        <!-- Region Input -->
        <div class="col-md-2 mb-3">
            <label for="region" class="form-label">Region</label>
            <input type="text" class="form-control" id="region" name="region" placeholder=" Region" readonly />
        </div>

        <!-- Province Input -->
        <div class="col-md-3 mb-3">
            <label for="province" class="form-label">Province</label>
            <input type="text" class="form-control" id="province" name="province" placeholder="Province" readonly />
        </div>

        <!-- Municipality Input -->
        <div class="col-md-3 mb-3">
            <label for="municipality" class="form-label">Municipality</label>
            <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Municipality" readonly />
        </div>

        <!-- Barangay Input -->
        <div class="col-md-2 mb-3">
            <label for="barangay" class="form-label">Barangay</label>
            <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay" readonly />
        </div>

        <!-- Street Input (Text Field) -->
        <div class="col-md-2 mb-3">
            <label for="street" class="form-label">Purok/Block No./Street</label>
            <input type="text" class="form-control" id="street" name="street" placeholder="Street address" readonly />
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" readonly />
        </div>
        <div class="col-md-6 mb-3">
            <label for="contactNumber" class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Contact number" readonly />
        </div>
    </div>

    <!-- Emergency Contact Information -->
    <h5>Emergency Contact Information</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
            <input type="text" class="form-control" id="emergencyContactName" name="emergencyContactName" placeholder="Emergency contact name" readonly />
        </div>
        <div class="col-md-3 mb-3">
            <label for="relationship" class="form-label">Relationship</label>
            <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Relationship" readonly/>
        </div>
        <div class="col-md-3 mb-3">
            <label for="emergencyContactNumber" class="form-label">Emergency Contact Number</label>
            <input type="tel" class="form-control" id="emergencyContactNumber" name="emergencyContactNumber" placeholder="Emergency contact number" readonly />
        </div>
    </div>

    <div class="row">
        <h5>Patient's Account Status</h5>
        <div class="col-md-2 mb-3">
            <label for="Status" class="form-label">Status</label>
            <select class="form-select form-control" id="Status" name="Status" readonly>
                <option selected disabled>Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>
    <div class="row">
                    <div class="col-md-12 text-center ">
                      
                      <button type="button" class="btn btn-primary ms-3" id="canceladdpatient">
                        Back
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

    
<script>
    // Passing PHP data to JavaScript
    var patientData = <?php echo json_encode($patientDetails); ?>;

    // Function to populate form inputs with patient data
    function populatePatientForm(patientData) {
        // Populate patient details
       // Populate patient details
       document.getElementById('lastName').value = patientData.patient.patient_lname || '';
            document.getElementById('firstName').value = patientData.patient.patient_fname || '';
            document.getElementById('middleName').value = patientData.patient.patient_mname || '';
            document.getElementById('dob').value = patientData.patient.patient_dob || '';
            document.getElementById('sex').value = patientData.patient.patient_sex || 'Male';
            document.getElementById('staffID').value = patientData.staff.staff_idnum || '';

            document.getElementById('Office').value = patientData.staff.staff_office || '';
            document.getElementById('role').value = patientData.staff.staff_role || '';
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
            document.getElementById('Status').value = patientData.patient.patient_status || '';
            document.getElementById('profilePic').src = `uploads/${patientData.patient.patient_profile}` || 'default-image.jpg';

        // Populate other fields as needed
    }

    // Populate the form when the page loads
    document.addEventListener("DOMContentLoaded", function() {
        populatePatientForm(patientData);
    });
    </script>

<script>
// Function to download the image
document.getElementById('downloadBtn').addEventListener('click', function () {
    // Get the source of the image
    const imageSrc = document.getElementById('profilePic').src;

    // Create a temporary link element
    const link = document.createElement('a');
    link.href = imageSrc;  // Set the link's href to the image's source
    link.download = 'profile-image.jpg';  // Set a default filename for the download

    // Append the link to the body (required for Firefox)
    document.body.appendChild(link);

    // Trigger the download by simulating a click
    link.click();

    // Remove the link from the document
    document.body.removeChild(link);
});
</script>

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



<script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<!-- Ensure you include these in your HTML -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
        $(document).ready(function () {
            // Confirmation before canceling the addition of a patient
            $("#canceladdpatient").click(function () {
    // Go back to the previous page
    window.history.back();
});


            // Address Data Logic
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

            // Function to populate dropdowns
            function populateDropdown(dropdown, options) {
                dropdown.innerHTML = '<option selected disabled>Select</option>';
                options.forEach(option => {
                    const opt = document.createElement("option");
                    opt.value = option;
                    opt.textContent = option;
                    dropdown.appendChild(opt);
                });
            }

            // Populate Regions dropdown
            const regionSelect = document.getElementById("region");
            populateDropdown(regionSelect, Object.keys(addressData.regions));

            // Handle region change
            regionSelect.addEventListener("change", function () {
                const selectedRegion = this.value;
                const provinces = Object.keys(addressData.regions[selectedRegion].provinces);
                populateDropdown(document.getElementById("province"), provinces);
            });

            // Handle province change
            document.getElementById("province").addEventListener("change", function () {
                const selectedRegion = regionSelect.value;
                const selectedProvince = this.value;
                const municipalities = addressData.regions[selectedRegion].provinces[selectedProvince].municipalities;
                populateDropdown(document.getElementById("municipality"), municipalities);
            });

            // Handle municipality change
            document.getElementById("municipality").addEventListener("change", function () {
                const selectedRegion = regionSelect.value;
                const selectedProvince = document.getElementById("province").value;
                const selectedMunicipality = this.value;
                const barangays = addressData.regions[selectedRegion].provinces[selectedProvince].barangays[selectedMunicipality];
                populateDropdown(document.getElementById("barangay"), barangays);
            });
        });
    </script>



</body>
</html>