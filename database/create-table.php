<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ClinicaLog";

    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if(mysqli_query($conn, $sql)){
        echo "Database created successfully<br>";
    }else{ 
        echo "Error creating database: " . mysqli_error($conn) . "<br>";
    }

    mysqli_close($conn);

    $conn = mysqli_connect($servername, $username, $password, $dbname); 

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error()); 
    } else {
        echo "Connected to database $dbname successfully<br>";
    }

    $sql_staffusers = "CREATE TABLE IF NOT EXISTS staffusers (
        user_id CHAR(15) PRIMARY KEY,            
        user_fname VARCHAR(30) NOT NULL,         
        user_lname VARCHAR(30) NOT NULL,         
        user_mname VARCHAR(30),                  
        user_email VARCHAR(100) NOT NULL UNIQUE,  
        user_position VARCHAR(50) NOT NULL,       
        user_role ENUM('Super Admin', 'Admin') NOT NULL, 
        user_status ENUM('Active', 'Inactive') NOT NULL,    
        user_dateadded DATE,                     
        user_profile VARCHAR(255),               
        user_password CHAR(60) NOT NULL,         
        user_code MEDIUMINT UNSIGNED NOT NULL     
    );";

    if (mysqli_query($conn, $sql_staffusers)) { 
        echo "Table 'staffusers' created successfully<br>";

        $admin_id = 'ADMIN001';
        $admin_fname = 'Admin';
        $admin_lname = 'User';
        $admin_mname = '';
        $admin_email = 'admin@clinicalog.com';
        $admin_position = 'Administrator';
        $admin_role = 'Super Admin'; 
        $admin_status = 'Active'; 
        $admin_dateadded = date('Y-m-d');
        $admin_profile = '35e3a37ab3e5a98b04b63f4b4c3697fd.jpg';  
        $admin_password = password_hash('admin123', PASSWORD_BCRYPT); 
        $admin_code = 0; 

        $checkAdmin = "SELECT * FROM staffusers WHERE user_id = '$admin_id' OR user_email = '$admin_email'";
        $result = mysqli_query($conn, $checkAdmin);

        if (mysqli_num_rows($result) == 0) {  
            $insertAdmin = "INSERT INTO staffusers 
            (user_id, user_fname, user_lname, user_mname, user_email, user_position, 
             user_role, user_status, user_dateadded, user_profile, user_password, user_code)
            VALUES ('$admin_id', '$admin_fname', '$admin_lname', '$admin_mname', 
                    '$admin_email', '$admin_position', '$admin_role', 
                    '$admin_status', '$admin_dateadded', '$admin_profile', 
                    '$admin_password', '$admin_code')";

            if (mysqli_query($conn, $insertAdmin)) {
                echo "Default admin account created successfully<br>";
            } else {
                echo "Error creating admin account: " . mysqli_error($conn) . "<br>";
            }
        } else {
            echo "Admin account already exists<br>";
        }
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


    mysqli_close($conn);
?>
