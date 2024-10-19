<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Selection</title>
    <style>
        .form-control {
            width: 300px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <!-- Dropdown to select a program -->
    <label for="program">Select a Program:</label>
    <select class="form-select form-control" id="program" name="program">
        <option value="Click to type...">Click to type...</option>
        <option value="Bachelor of Science in Secondary Education">Bachelor of Science in Secondary Education</option>
        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology</option>
        <option value="Bachelor of Science in Agricultural and Biosystems Engineering">Bachelor of Science in Agricultural and Biosystems Engineering</option>
        <option value="Bachelor of Technical-Vocational Education">Bachelor of Technical-Vocational Education</option>
        <option value="Bachelor of Special Needs Education">Bachelor of Special Needs Education</option>
        <option value="Bachelor of Early Childhood Education">Bachelor of Early Childhood Education</option>
        <option value="Bachelor of Elementary Education">Bachelor of Elementary Education</option>
    </select>

    <!-- Optional: JavaScript to handle dropdown selection -->
    <script>
        document.getElementById('program').addEventListener('change', function() {
            const selectedProgram = this.value;
            if (selectedProgram === 'Click to type...') {
                alert('Please enter your own program manually.');
            } else {
                alert('You selected: ' + selectedProgram);
            }
        });
    </script>

</body>
</html>
