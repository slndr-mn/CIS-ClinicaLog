<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program and Major Form with Custom Styling</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .custom-input {
            border: 2px solid orange; /* Custom border for user-defined values */
            background-color: #f9f9f9; /* Light background for better visibility */
        }
        .default-input {
            border: 2px solid #ccc; /* Default border for standard options */
            background-color: white; /* Standard background color */
        }
        #submittedData {
            margin-top: 20px;
        }
        button {
            padding: 10px 15px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff8c00;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form id="patientForm">
        <label for="program">Program:</label>
        <input list="programs" id="program" name="program" placeholder="Select or add a Program" required class="default-input">
        <datalist id="programs">
            <option value="Bachelor of Science in Secondary Education">
            <option value="Bachelor of Science in Information Technology">
            <option value="Bachelor of Science in Agricultural and Biosystems Engineering">
            <option value="Bachelor of Technical-Vocational Education">
            <option value="Bachelor of Special Needs Education">
            <option value="Bachelor of Early Childhood Education">
            <option value="Bachelor of Elementary Education">
        </datalist>

        <label for="major">Major:</label>
        <input list="majors" id="major" name="major" placeholder="Select or add a Major" required class="default-input">
        <datalist id="majors"></datalist>

        <button type="submit">Submit</button>
    </form>
</div>

<!-- Display Submitted Data -->
<div id="submittedData">
    <h3>Submitted Data:</h3>
    <p id="displayProgram"></p>
    <p id="displayMajor"></p>
</div>

<script>
    var patientData = {
        student: {
            student_program: "Bachelor of Science in Secondary Education", // Example of a program not in the list
            student_major: "Unknown Major" // Example of a major not in the list
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

    // Function to populate majors based on the selected program
    function populateMajors(selectedProgram) {
        const majorInput = document.getElementById('major');
        const majorsList = document.getElementById('majors');
        majorsList.innerHTML = ''; // Clear existing options

        // Check if the selected program is in the predefined list
        if (majorOptions[selectedProgram]) {
            majorOptions[selectedProgram].forEach(function(major) {
                majorsList.innerHTML += `<option value="${major}">`;
            });
        }

        // Append the major from patientData if it's not in the list
        const selectedMajor = patientData.student.student_major;
        if (selectedMajor && !majorsList.querySelector(`option[value="${selectedMajor}"]`)) {
            majorsList.innerHTML += `<option value="${selectedMajor}">`;
        }

        // Set the major input field's value
        majorInput.value = selectedMajor; 

        // Style major input based on whether it's a predefined value or not
        styleInput(majorInput, selectedMajor, majorsList);
    }

    // Function to style input based on its value
    function styleInput(inputElement, inputValue, optionsList) {
        const isDefaultValue = optionsList.querySelector(`option[value="${inputValue}"]`);
        if (isDefaultValue) {
            inputElement.classList.remove('custom-input');
            inputElement.classList.add('default-input');
        } else {
            inputElement.classList.remove('default-input');
            inputElement.classList.add('custom-input');
        }
    }

    // Pre-populate the form fields with data from patientData
    document.addEventListener('DOMContentLoaded', function () {
        const selectedProgram = patientData.student.student_program || '';
        
        // Check if the program is in the predefined list
        const programsList = document.getElementById('programs');
        if (![...programsList.options].some(option => option.value === selectedProgram) && selectedProgram !== '') {
            programsList.innerHTML += `<option value="${selectedProgram}">`;
        }

        document.getElementById('program').value = selectedProgram; // Preselect program
        
        // Populate majors based on the initial program selection
        populateMajors(selectedProgram);

        // Event handler to populate majors when a program is selected
        document.getElementById('program').addEventListener('input', function() {
            populateMajors(this.value);
            styleInput(this, this.value, programsList);
        });

        // Handle form submission
        document.getElementById('patientForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get the selected values
            const selectedProgram = document.getElementById('program').value;
            const selectedMajor = document.getElementById('major').value;

            // Display the submitted data
            document.getElementById('displayProgram').textContent = 'Program: ' + selectedProgram;
            document.getElementById('displayMajor').textContent = 'Major: ' + selectedMajor;
        });
    });
</script>

</body>
</html>
