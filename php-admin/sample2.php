<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read input values
    $program = $_POST['program'];
    $major = $_POST['major'];
    $additional = $_POST['additional'];

    // Prepare the response
    if (!empty($program) && !empty($major)) {
        echo "Program: " . htmlspecialchars($program) . "<br>";
        echo "Major: " . htmlspecialchars($major) . "<br>";
        echo "Additional: " . htmlspecialchars($additional);
    } else {
        echo "Please select or enter both a program and a major.";
    }
}
?>
