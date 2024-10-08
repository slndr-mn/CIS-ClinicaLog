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
        justify-content: center;
        align-items: center;
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
        <div class=row>
        <a href="javascript:history.back()" class="back-nav">
      <i class="fas fa-arrow-left"></i> Back to Patients' Table
    </a>
    </div>
        
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
                <div class="d-flex align-items-center">
                  <h4 class="card-title">Personal Details</h4>
                  <button
                    class="btn btn-primary btn-round ms-auto"
                    id="editbutton"
                    >
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button
                    class="btn btn-primary btn-round ms-auto"
                    id="savebutton" style="display:none;"
                    >
                    <i class="fa fa-save"></i>
                    save
                  </button>
                </div>
              </div>
              <div class="card-body" id="InputInfo">
                <!-- Form Starts Here -->
                <!-- Form Starts Here -->
                <form action="patientcontrol.php" method="POST" enctype="multipart/form-data">   
    
    <!-- Name Fields -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" disabled />
        </div>
        <div class="col-md-3 mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" disabled />
        </div>
        <div class="col-md-2 mb-3">
            <label for="middleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" disabled/>
        </div>
        <div class="col-md-2 mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" disabled />
        </div>
        <div class="col-md-2 mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select class="form-select form-control" id="sex" name="sex" disabled>
                <option selected disabled>Select Sex</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
            </select>
        </div>
    </div>

    <!-- ID and Academic Info -->
    <div class="row">
        <div class="col-md-2 mb-3">
            <label for="studentID" class="form-label">ID Number</label>
            <input type="text" class="form-control" id="studentID" name="studentID" placeholder="Enter ID number" disabled />
        </div>

            <!-- Program Input -->
        <div class="col-md-4 mb-3">
            <label for="program" class="form-label">Program</label>
            <select class="form-select form-control" id="program" name="program" placeholder="Enter Program" disabled>
                <option value="">Select Program</option>
                <option value="Bachelor of Science in Secondary Education">Bachelor of Science in Secondary Education</option>
                <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
                <option value="Bachelor of Science in Agricultural and Biosystems Engineering">Bachelor of Science in Agricultural and Biosystems Engineering</option>
                <option value="Bachelor of Technical-Vocational Education">Bachelor of Technical-Vocational Education</option>
                <option value="Bachelor of Special Needs Education">Bachelor of Special Needs Education</option>
                <option value="Bachelor of Early Childhood Education">Bachelor of Early Childhood Education</option>
                <option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>
            </select>
        </div>

        <!-- Major Input -->
        <div class="col-md-2 mb-3">
            <label for="major" class="form-label">Major</label>
            <select class="form-control form-select" id="major" name="major" placeholder="Enter Major" disabled>
                <option value="">Select Major</option>
            </select>
        </div>

<!-- Other elements... -->


        <!-- Year Dropdown -->
        <div class="col-md-2 mb-3">
            <label for="year" class="form-label">Year</label>
            <select class="form-select form-control" id="year" name="year" disabled>
                <option selected disabled>Select Year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
        </div>

        <div class="col-md-2 mb-3">
            <label for="section" class="form-label">Section</label>
            <input type="text" class="form-control" id="section" name="section" placeholder="e.g., 3A" disabled />
        </div>
    </div>

    <!-- Address Fields -->
    <h5>Current Address</h5>
    <div class="row">
        <!-- Region Input -->
        <div class="col-md-2 mb-3">
            <label for="region" class="form-label">Region</label>
            <select class="form-select form-control" id="region" name="region" placeholder="Enter Region" disabled>
            <option value="">Select Region</option>
            <option value="Region XI">Region XI</option>
            <option value="Region XII">Region XII</option>
            </select>
        </div>
        <!-- Province Input -->
        <div class="col-md-3 mb-3">
            <label for="province" class="form-label">Province</label>
            <select class="form-control" id="province" name="province" placeholder="Enter Province" disabled>
                <option value="">Select Province</option>
            </select>
        </div>

        <!-- Municipality Input -->
        <div class="col-md-3 mb-3">
            <label for="municipality" class="form-label">Municipality</label>
            <select class="form-select form-control" id="municipality" name="municipality" placeholder="Enter Municipality" disabled>
                <option value="">Select Municipality</option>
            </select>
        </div>

        <!-- Barangay Input -->
        <div class="col-md-2 mb-3">
            <label for="barangay" class="form-label">Barangay</label>
            <select class="form-select form-control" id="barangay" name="barangay" placeholder="Enter Barangay" disabled>
                <option value="">Select Barangay</option>
            </select>
        </div>


        <!-- Street Input (Text Field) -->
        <div class="col-md-2 mb-3">
            <label for="street" class="form-label">Purok/Block No./Street</label>
            <input type="text" class="form-control" id="street" name="street" placeholder="Enter street address" disabled />
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" disabled />
        </div>
        <div class="col-md-6 mb-3">
            <label for="contactNumber" class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" disabled />
        </div>
    </div>

    <!-- Emergency Contact Information -->
    <h5>Emergency Contact Information</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
            <input type="text" class="form-control" id="emergencyContactName" name="emergencyContactName" placeholder="Enter emergency contact name" disabled />
        </div>
        <div class="col-md-3 mb-3">
            <label for="relationship" class="form-label">Relationship</label>
            <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Enter relationship" disabled/>
        </div>
        <div class="col-md-3 mb-3">
            <label for="emergencyContactNumber" class="form-label">Emergency Contact Number</label>
            <input type="tel" class="form-control" id="emergencyContactNumber" name="emergencyContactNumber" placeholder="Enter emergency contact number" disabled />
        </div>
    </div>

    <div class="row">
        <h5>Patient's Account Status</h5>
        <div class="col-md-2 mb-3">
            <label for="Status" class="form-label">Status</label>
            <select class="form-select form-control" id="Status" name="Status" disabled>
                <option selected disabled>Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>
    <div class="row">
                    <div class="col-md-12 text-center">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Dynamically load the sidebar and header
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

        // Data from PHP passed to JavaScript for populating form fields
        var patientData = <?php echo json_encode($patientDetails); ?>;

        function populatePatientForm(data) {
            $('#lastName').val(data.patient.patient_lname || '');
            $('#firstName').val(data.patient.patient_fname || '');
            $('#middleName').val(data.patient.patient_mname || '');
            $('#dob').val(data.patient.patient_dob || '');
            $('#sex').val(data.patient.patient_sex || 'Male');
            $('#studentID').val(data.student.student_idnum || '');
            $('#program').val(data.student.student_program || '').trigger('change'); // Trigger change for majors
            $('#year').val(data.student.student_year || '');
            $('#section').val(data.student.student_section || '');
            $('#region').val(data.address.address_region || '').trigger('change'); // Trigger change for provinces
            $('#province').val(data.address.address_province || '').trigger('change'); // Trigger change for municipalities
            $('#municipality').val(data.address.address_municipality || '').trigger('change'); // Trigger change for barangays
            $('#barangay').val(data.address.address_barangay || '');
            $('#street').val(data.address.address_prkstrtadd || '');
            $('#email').val(data.patient.patient_email || '');
            $('#contactNumber').val(data.patient.patient_connum || '');
            $('#emergencyContactName').val(data.emergencyContact.emcon_conname || '');
            $('#relationship').val(data.emergencyContact.emcon_relationship || '');
            $('#emergencyContactNumber').val(data.emergencyContact.emcon_connum || '');
            $('#Status').val(data.patient.patient_status || '');
            $('#profilePic').attr('src', `uploads/${data.patient.patient_profile}` || 'default-image.jpg');
        }

        // Download profile picture functionality
        $('#downloadBtn').on('click', function () {
            const imageSrc = $('#profilePic').attr('src');
            const link = document.createElement('a');
            link.href = imageSrc;
            link.download = 'profile-image.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // Populate the form on document ready
        populatePatientForm(patientData);

        // Address dropdown functionality
        const addressOptions = {
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

        const majorOptions = {
            "Bachelor of Science in Secondary Education": ["Filipino", "English", "Mathematics"],
            "Bachelor of Science in Information Technology": ["Information Security"],
            "Bachelor of Science in Agricultural and Biosystems Engineering": ["None"],
            "Bachelor of Technical-Vocational Education": ["Agricultural Crop Production", "Animal Production"],
            "Bachelor of Special Needs Education": ["None"],
            "Bachelor of Early Childhood Education": ["None"],
            "Bachelor of Elementary Education": ["None"]
        };

        function populateMajors(selectedProgram) {
            const majorSelect = $('#major');
            majorSelect.empty().append('<option selected disabled>Select Major</option>');
            if (majorOptions[selectedProgram]) {
                majorOptions[selectedProgram].forEach(function(major) {
                    majorSelect.append(`<option value="${major}">${major}</option>`);
                });
            }
            majorSelect.val(patientData.student.student_major).trigger('change'); // Set the selected major
        }

        function populateProvinces(selectedRegion) {
            const provinceSelect = $('#province');
            provinceSelect.empty().append('<option selected disabled>Select Province</option>');
            if (selectedRegion && addressOptions.regions[selectedRegion]) {
                Object.keys(addressOptions.regions[selectedRegion].provinces).forEach(function(province) {
                    provinceSelect.append(`<option value="${province}">${province}</option>`);
                });
            }
            provinceSelect.val(patientData.address.address_province).trigger('change'); // Set the selected province
        }

        function populateMunicipalities(selectedRegion, selectedProvince) {
            const municipalitySelect = $('#municipality');
            municipalitySelect.empty().append('<option selected disabled>Select Municipality</option>');
            if (selectedProvince && addressOptions.regions[selectedRegion].provinces[selectedProvince]) {
                const municipalities = addressOptions.regions[selectedRegion].provinces[selectedProvince].municipalities;
                municipalities.forEach(function(municipality) {
                    municipalitySelect.append(`<option value="${municipality}">${municipality}</option>`);
                });
            }
            municipalitySelect.val(patientData.address.address_municipality).trigger('change'); // Set the selected municipality
        }

        function populateBarangays(selectedRegion, selectedProvince, selectedMunicipality) {
            const barangaySelect = $('#barangay');
            barangaySelect.empty().append('<option selected disabled>Select Barangay</option>');
            if (selectedMunicipality && addressOptions.regions[selectedRegion].provinces[selectedProvince]) {
                const barangays = addressOptions.regions[selectedRegion].provinces[selectedProvince].barangays[selectedMunicipality];
                barangays.forEach(function(barangay) {
                    barangaySelect.append(`<option value="${barangay}">${barangay}</option>`);
                });
            }
            barangaySelect.val(patientData.address.address_barangay).trigger('change'); // Set the selected barangay
        }

        // Event handlers for cascading dropdowns
        $('#program').on('change', function() {
            populateMajors($(this).val());
        });

        $('#region').on('change', function() {
            populateProvinces($(this).val());
        });

        $('#province').on('change', function() {
            populateMunicipalities($('#region').val(), $(this).val());
        });

        $('#municipality').on('change', function() {
            populateBarangays($('#region').val(), $('#province').val(), $(this).val());
        });

        // Edit button functionality
        $('#editButton').on('click', function() {
            $('#patientForm input, #patientForm select').removeClass('readonly').prop('disabled', false);
            $(this).hide();
            $('#saveButton').show();
        });

        // Save button functionality
        $('#saveButton').on('click', function() {
            // Add your save logic here
            alert('Patient information saved!');
            $('#patientForm input, #patientForm select').addClass('readonly').prop('disabled', true);
            $(this).hide();
            $('#editButton').show();
        });

        // Initial population of fields based on existing data
        if (patientData.student.student_program) {
            $('#program').val(patientData.student.student_program).trigger('change');
        }

        if (patientData.address.address_region) {
            $('#region').val(patientData.address.address_region).trigger('change');
        }

        if (patientData.address.address_province) {
            $('#province').val(patientData.address.address_province).trigger('change');
        }

        if (patientData.address.address_municipality) {
            $('#municipality').val(patientData.address.address_municipality).trigger('change');
        }

        if (patientData.address.address_barangay) {
            $('#barangay').val(patientData.address.address_barangay);
        }
    });
</script>






</body>
</html>