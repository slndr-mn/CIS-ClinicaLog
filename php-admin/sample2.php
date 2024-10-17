<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read input values
    $program = $_POST['program'];
    $major = $_POST['major'];
    $programm = $_POST['programm'];
    $majorr = $_POST['majorr'];

    $finalprogram = (!empty($_POST['program']) &&  $_POST['program'] !== 'Click to type...') ? 
                        $_POST['program'] : $_POST['programm'];
    $finalmajor = (!empty($_POST['major']) &&  $_POST['major'] !== 'Click to type...' && empty($_POST['programm']) ) ? 
                        $_POST['major'] : $_POST['majorr'];

    echo "Program: " . htmlspecialchars($program) . "<br>";
    echo "Major: " . htmlspecialchars($major) . "<br>";
      echo "Program: " . htmlspecialchars($programm) . "<br>";
    echo "Major: " . htmlspecialchars($majorr) . "<br>";
      echo "Program: " . htmlspecialchars($finalprogram) . "<br>";
    echo "Major: " . htmlspecialchars($finalmajor) . "<br>";

}   
?>
 