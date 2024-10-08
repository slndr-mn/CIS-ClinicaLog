<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <style>
        .readonly {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h2>Patient Information Form</h2>
    <form id="patientForm">
        <label>ID Number:</label>
        <input type="text" id="idNumber" class="readonly" disabled>
        <br>
        <label>First Name:</label>
        <input type="text" id="firstName" class="readonly" disabled>
        <br>
        <label>Middle Name:</label>
        <input type="text" id="middleName" class="readonly" disabled>
        <br>
        <label>Last Name:</label>
        <input type="text" id="lastName" class="readonly" disabled>
        <br>
        <label>Date of Birth:</label>
        <input type="date" id="dob" class="readonly" disabled>
        <br>
        <label>Program:</label>
        <select id="program" class="readonly" disabled>
            <option value="">Select Program</option>
            <option value="Program 1">Program 1</option>
            <option value="Program 2">Program 2</option>
            <!-- Add more programs as needed -->
        </select>
        <br>
        <label>Major:</label>
        <select id="major" class="readonly" disabled>
            <option value="">Select Major</option>
        </select>
        <br>
        <label>Region:</label>
        <select id="region" class="readonly" disabled>
            <option value="">Select Region</option>
            <option value="Region XI">Region XI</option>
            <option value="Region XII">Region XII</option>
        </select>
        <br>
        <label>Province:</label>
        <select id="province" class="readonly" disabled>
            <option value="">Select Province</option>
        </select>
        <br>
        <label>Municipality:</label>
        <select id="municipality" class="readonly" disabled>
            <option value="">Select Municipality</option>
        </select>
        <br>
        <label>Barangay:</label>
        <select id="barangay" class="readonly" disabled>
            <option value="">Select Barangay</option>
        </select>
        <br>
        <label>Current Address:</label>
        <input type="text" id="currentAddress" class="readonly" disabled>
        <br>
        <label>Emergency Contact Name:</label>
        <input type="text" id="emergencyContactName" class="readonly" disabled>
        <br>
        <label>Relationship:</label>
        <input type="text" id="relationship" class="readonly" disabled>
        <br>
        <label>Emergency Contact Number:</label>
        <input type="text" id="emergencyContactNumber" class="readonly" disabled>
        <br>
        <button type="button" id="editButton">Edit</button>
        <button type="button" id="saveButton" style="display:none;">Save</button>
        <button type="button" id="cancelButton">Cancel</button>
    </form>

    <script>
        $(document).ready(function() {
            // Sample patient data for demonstration
            const patientData = {
                idNumber: "123456",
                firstName: "John",
                middleName: "A.",
                lastName: "Doe",
                dob: "2000-01-01",
                program: "Program 1",
                major: "Major 1",
                region: "Region XI",
                province: "Davao del Norte",
                municipality: "Tagum City",
                barangay: "Apokon",
                currentAddress: "123 Main St.",
                emergencyContactName: "Jane Doe",
                relationship: "Sister",
                emergencyContactNumber: "9876543210"
            };

            // Updated address options based on your structure
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

            // Sample program and major options
            const programOptions = {
                "Program 1": [
                    { value: "Major 1", text: "Major 1" },
                    { value: "Major 2", text: "Major 2" }
                ],
                "Program 2": [
                    { value: "Major 3", text: "Major 3" },
                    { value: "Major 4", text: "Major 4" }
                ]
            };

            // Function to populate patient form
            function populatePatientForm(data) {
                $('#idNumber').val(data.idNumber);
                $('#firstName').val(data.firstName);
                $('#middleName').val(data.middleName);
                $('#lastName').val(data.lastName);
                $('#dob').val(data.dob);
                $('#program').val(data.program).trigger('change'); // Trigger change to update majors
                $('#region').val(data.region).trigger('change'); // Trigger change to update provinces
                $('#currentAddress').val(data.currentAddress);
                $('#emergencyContactName').val(data.emergencyContactName);
                $('#relationship').val(data.relationship);
                $('#emergencyContactNumber').val(data.emergencyContactNumber);
            }

            function populateMajors(program) {
                $('#major').empty().append('<option value="">Select Major</option>');
                if (program && programOptions[program]) {
                    programOptions[program].forEach(function(major) {
                        $('#major').append(`<option value="${major.value}">${major.text}</option>`);
                    });
                }
                $('#major').val(patientData.major).trigger('change'); // Update to the major value
            }

            function populateProvinces(region) {
                $('#province').empty().append('<option value="">Select Province</option>');
                if (region && addressOptions.regions[region]) {
                    const provinces = addressOptions.regions[region].provinces;
                    for (let province in provinces) {
                        $('#province').append(`<option value="${province}">${province}</option>`);
                    }
                }
                $('#province').val(patientData.province).trigger('change'); // Update to the province value
            }

            function populateMunicipalities(region, province) {
                $('#municipality').empty().append('<option value="">Select Municipality</option>');
                if (province && addressOptions.regions[region].provinces[province]) {
                    const municipalities = addressOptions.regions[region].provinces[province].municipalities;
                    municipalities.forEach(function(municipality) {
                        $('#municipality').append(`<option value="${municipality}">${municipality}</option>`);
                    });
                }
                $('#municipality').val(patientData.municipality).trigger('change'); // Update to the municipality value
            }

            function populateBarangays(region, municipality) {
                $('#barangay').empty().append('<option value="">Select Barangay</option>');
                if (municipality && addressOptions.regions[region].provinces) {
                    const barangays = addressOptions.regions[region].provinces[patientData.province].barangays[municipality];
                    barangays.forEach(function(barangay) {
                        $('#barangay').append(`<option value="${barangay}">${barangay}</option>`);
                    });
                }
                $('#barangay').val(patientData.barangay); // Set the selected barangay
            }

            // Initialize Select2 for program and major dropdowns
            $('#program').select2();
            $('#major').select2();

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
                populateBarangays($('#region').val(), $(this).val());
            });

            // Load patient data into the form
            populatePatientForm(patientData);

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

            // Cancel button functionality
            $('#cancelButton').on('click', function() {
                $('#patientForm input, #patientForm select').addClass('readonly').prop('disabled', true);
                $('#saveButton').hide();
                $('#editButton').show();
            });
        });
    </script>
</body>
</html>
