<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program and Major Selection</title>
    <style>
        .hidden {
            display: none;
        }

        button img {
            width: 24px;
            height: 24px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <form id="programForm" action="sample2.php" method="POST">
        <label for="program">Select Program:</label>
        <select id="program" name="program">
            <option value="Click to type...">Click to type...</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Business Administration">Business Administration</option>
            <option value="Engineering">Engineering</option>
            <option value="Biology">Biology</option>

        </select>

        <!-- Text input for custom program (hidden initially) -->
        <div id="programInputContainer" class="hidden">
            <input type="text" id="programInput" name="programm" placeholder="Enter your program">
        </div>

        <label for="major">Select Major:</label>
        <select id="major" name="major">
        <option value="Other">Other</option>
            <!-- Majors will be populated based on the selected program -->
        </select>

        <!-- Text input for custom major (hidden initially) -->
        <div id="majorInputContainer" class="hidden">
            <input type="text" id="majorInput" name="majorr" placeholder="Enter your major">
        </div>

        <!-- Back to dropdown icon button (hidden initially) -->
        <button type="button" id="backToDropdown" class="hidden">
            <img src="https://img.icons8.com/ios-glyphs/30/000000/undo.png" alt="Back to dropdown" />
        </button>

        <button type="submit">Submit</button>
    </form>

    <!-- jQuery library (optional but simplifies the scripting) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
$(document).ready(function() {
    // Simulate values from the database
    const dbProgram = 'Business Administration'; // Assume value fetched from database
    const dbMajor = 'Marketing';  // Assume value fetched from database

    let currentField = ''; // Track the field (program or major) currently in input mode

    // Define program-major mappings
    const programMajors = {
        'Click to type...': [],
        'Computer Science': ['Click to type...', 'Software Engineering', 'Data Science', ],
        'Business Administration': ['Click to type...', 'Marketing', 'Finance',],
        'Engineering': ['Click to type...', 'Mechanical Engineering', 'Civil Engineering', 'Electrical Engineering', ],
        'Biology': [ 'Click to type...', 'Genetics', 'Microbiology',],
        
    };

    // Function to populate the major dropdown based on selected program
    function updateMajorDropdown(selectedProgram) {
        const majors = programMajors[selectedProgram] || [];
        $('#major').empty(); // Clear existing options

        // Populate the major dropdown
        $.each(majors, function(index, major) {
            $('#major').append(`<option value="${major}">${major}</option>`);
        });

        // Add the 'Other' option if it's not in the list
        if (!majors.includes('Click to type...')) {
            $('#major').append('<option value="" hidden></option>');
        }
    }

    // Function to check if the value exists in a dropdown
    function checkIfExistsInDropdown(dropdown, value) {
        return $(dropdown).find(`option[value='${value}']`).length > 0;
    }

    // Handle initial program value (from database)
    if (dbProgram && !checkIfExistsInDropdown('#program', dbProgram)) {
        // If the value is not in the dropdown, switch to text input
        $('#program').hide();
        $('#programInputContainer').removeClass('hidden');
        $('#programInput').val(dbProgram); // Set the custom program from database
        $('#backToDropdown').removeClass('hidden'); // Show the back button
        currentField = 'program';
    } else {
        $('#program').val(dbProgram); // Select the program from dropdown
        updateMajorDropdown(dbProgram); // Update the major dropdown based on the initial program
    }

    // Handle initial major value (from database)
    if (dbMajor && !checkIfExistsInDropdown('#major', dbMajor)) {
        // If the value is not in the dropdown, switch to text input
        $('#major').hide();
        $('#majorInputContainer').removeClass('hidden');
        $('#majorInput').val(dbMajor); // Set the custom major from database
        $('#backToDropdown').removeClass('hidden'); // Show the back button
        currentField = 'major';
    } else {
        $('#major').val(dbMajor); // Select the major from dropdown
    }

    // Handle dropdown change for program
    $('#program').on('change', function() {
        const selectedProgram = $(this).val();
        updateMajorDropdown(selectedProgram); // Update majors based on the selected program
        $('#majorInputContainer').addClass('hidden'); // Hide the input container
        $('#major').show(); // Show the dropdown

        if (selectedProgram === 'Click to type...') {
            // Hide program dropdown and show the text input for custom program
            $('#program').hide();
            $('#programInputContainer').removeClass('hidden');
            $('#backToDropdown').removeClass('hidden');
            currentField = 'program';

            // Automatically switch major dropdown to input as well
            $('#major').hide();
            $('#majorInputContainer').removeClass('hidden');
            currentField = 'major';
        } else {
            $('#majorInputContainer').addClass('hidden'); // Ensure major input is hidden if not 'Other'
        }
    });

    // Handle major dropdown change
    $('#major').on('change', function() {
        if ($(this).val() === 'Click to type...') {
            // Hide major dropdown and show the text input for custom major
            $('#major').hide();
            $('#majorInputContainer').removeClass('hidden');
            $('#backToDropdown').removeClass('hidden');
            currentField = 'major';
        }
    });

    $('#backToDropdown').on('click', function() {
        // Show the program dropdown and hide its input container
        $('#programInputContainer').addClass('hidden');
        $('#program').show();

        // Show the major dropdown and hide its input container
        $('#majorInputContainer').addClass('hidden');
        $('#major').show();

        // Reset dropdown values
        $('#program').val(dbProgram); // Reset to the value fetched from the database
        updateMajorDropdown(dbProgram); // Update majors based on the program
        $('#major').val(dbMajor); // Reset to the value fetched from the database

        // Hide the back button after switching back
        $(this).addClass('hidden');
    });

    
});

    </script>
</body>
</html>
