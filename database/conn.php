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
        echo "Database created successfully";
    }else{
        echo "Error creating database: " . mysqli_error($conn);
    }

    mysqli_close($conn);

    $conn = mysqli_connect($servername, $username, $password, $dbname);


    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        echo " ";
        echo "Connected to database $dbname successfully";
    }

    mysqli_close($conn);

?>
