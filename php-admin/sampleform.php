<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Independent Dropdowns</title>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Independent Dropdowns</h1>

    <label for="college">College:</label>
    <select id="college" style="width: 300px;">
        <option value="" disabled selected>Select or add a college</option>
    </select>

    <br><br>

    <label for="department">Department:</label>
    <select id="department" style="width: 300px;">
        <option value="" disabled selected>Select or add a department</option>
    </select>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for both dropdowns
            $('#college').select2({
                tags: true, // Enable adding new colleges
                placeholder: "Select or add a college",
                allowClear: true
            });

            $('#department').select2({
                tags: true, // Enable adding new departments
                placeholder: "Select or add a department",
                allowClear: true
            });

            // Object to map colleges to their departments
            const collegeDepartments = {
                "College of Teacher Education": ["Bachelor of Education", "Master of Education"],
                "College of Engineering": ["Civil Engineering", "Electrical Engineering", "Mechanical Engineering"],
                "School of Medicine": ["Medicine", "Nursing"]
            };

            // Populate college dropdown with some initial values
            Object.keys(collegeDepartments).forEach(college => {
                $('#college').append(new Option(college, college, false, false));
            });

            // Populate department dropdown based on selected college
            $('#college').change(function() {
                // Get selected college
                const selectedCollege = $(this).val();

                // Populate department dropdown with existing departments
                $('#department').empty().append('<option value="" disabled selected>Select or add a department</option>');
                const departments = collegeDepartments[selectedCollege] || [];
                departments.forEach(department => {
                    $('#department').append(new Option(department, department, false, false));
                });
            });

            // Log selected items to the console
            $('#department').on('change', function() {
                const selectedValue = $(this).val();
                console.log('Selected department: ', selectedValue);
            });
        });
    </script>
</body>
</html>
