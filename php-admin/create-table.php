<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ClinicaLog";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
 
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // SQL to create table
    $sql_staffusers = "CREATE TABLE staffusers (
    user_id VARCHAR(15) PRIMARY KEY,
    user_fname VARCHAR(50) NOT NULL,
    user_lname VARCHAR(50) NOT NULL,
    user_mname VARCHAR(50),
    user_email VARCHAR(255) NOT NULL UNIQUE,
    user_position VARCHAR(50) NOT NULL,
    user_status VARCHAR(10) NOT NULL,
    user_dateadded DATE,
    user_profile TEXT, 
    user_password VARCHAR(255) NOT NULL,
    user_code MEDIUMINT NOT NULL
    )";

    if (mysqli_query($conn, $sql_staffusers)) {
        echo "Table 'staffusers' created successfully<br>";
    } else {
        echo "Error creating table 'staffusers': " . mysqli_error($conn) . "<br>";
    }

    // SQL to create table 'medicine'
    $sql_medicine = "CREATE TABLE medicine (
        medicine_id INT AUTO_INCREMENT PRIMARY KEY,
        medicine_category VARCHAR(50) NOT NULL,
        medicine_name VARCHAR(100) NOT NULL,
        medicine_qty INT NOT NULL,
        medicine_dosage VARCHAR(50),
        medicine_dateadded TIMESTAMP,
        medicine_expirationdt DATE
        )";
    
        if (mysqli_query($conn, $sql_medicine)) {
            echo "Table 'medicine' created successfully<br>";
        } else {
            echo "Error creating table 'medicine': " . mysqli_error($conn) . "<br>";
        }

    // Close connection
    mysqli_close($conn);
?>

