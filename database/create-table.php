<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ClinicaLog";

    // Step 1: Create the database if it doesn't exist
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if(mysqli_query($conn, $sql)){
        echo "Database created successfully<br>";
    }else{
        echo "Error creating database: " . mysqli_error($conn) . "<br>";
    }

    // Close the connection
    mysqli_close($conn);

    // Step 2: Connect to the newly created database
    $conn = mysqli_connect($servername, $username, $password, $dbname); 

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error()); 
    } else {
        echo "Connected to database $dbname successfully<br>";
    }

    // Step 3: Create the staffusers table if it doesn't exist
    $sql_staffusers = "CREATE TABLE IF NOT EXISTS staffusers (
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

        // Step 4: Insert default admin account
        $admin_id = 'ADMIN001';
        $admin_fname = 'Admin';
        $admin_lname = 'User';
        $admin_mname = '';
        $admin_email = 'admin@clinicalog.com';
        $admin_position = 'Administrator';
        $admin_status = 'active';
        $admin_dateadded = date('Y-m-d');
        $admin_profile = '35e3a37ab3e5a98b04b63f4b4c3697fd.jpg';
        $admin_password = password_hash('admin123', PASSWORD_BCRYPT); // Hash the password
        $admin_code = 0;

        // Check if admin account exists
        $checkAdmin = "SELECT * FROM staffusers WHERE user_id = '$admin_id' OR user_email = '$admin_email'";
        $result = mysqli_query($conn, $checkAdmin);

        if (mysqli_num_rows($result) == 0) { 
            // Insert admin account
            $insertAdmin = "INSERT INTO staffusers 
            (user_id, user_fname, user_lname, 
            user_mname, user_email, user_position, 
            user_status, user_dateadded, user_profile, 
            user_password, user_code)
            
            VALUES ('$admin_id', '$admin_fname', '$admin_lname', 
            '$admin_mname', '$admin_email', '$admin_position', 
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


    mysqli_close($conn);

    
?>
