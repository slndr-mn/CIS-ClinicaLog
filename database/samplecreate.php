<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ClinicaLogSample";

// Step 1: Connect to MySQL server
$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Step 2: Create Database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Step 3: Connect to the new database
mysqli_close($conn); 
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected to database $dbname successfully<br>";
}

// Step 4: Create Tables
$tableQueries = [
    // Create patients table first to avoid foreign key errors in consultations
    "CREATE TABLE IF NOT EXISTS patients (
        patient_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_name VARCHAR(100) NOT NULL,
        patient_age INT,
        patient_gender VARCHAR(10)
    )",
    
    "CREATE TABLE IF NOT EXISTS medicine (
        medicine_id INT AUTO_INCREMENT PRIMARY KEY,
        medicine_name VARCHAR(100) NOT NULL UNIQUE, 
        medicine_category VARCHAR(50) NOT NULL
    )",
    
    "CREATE TABLE IF NOT EXISTS medstock (
        medstock_id INT AUTO_INCREMENT PRIMARY KEY,  
        medicine_id INT NOT NULL,
        medstock_unit VARCHAR(10),
        medstock_qty INT NOT NULL,
        medstock_dosage VARCHAR(50),
        medstock_dateadded DATE,
        medstock_timeadded TIME,
        medstock_expirationdt DATE,
        medstock_disable TINYINT(1) NOT NULL DEFAULT 0,
        FOREIGN KEY (medicine_id) REFERENCES medicine(medicine_id)
    )",
    
    "CREATE TABLE IF NOT EXISTS consultations (
        consult_id INT AUTO_INCREMENT PRIMARY KEY,
        consult_patientid INT NOT NULL, 
        consult_diagnosis VARCHAR(255) NOT NULL,
        consult_trtmentnotes VARCHAR(255) NOT NULL,
        consult_remark VARCHAR(255) NOT NULL,
        consult_date DATE,
        consult_timein VARCHAR(255),
        consult_timeout VARCHAR(255),
        consult_timespent VARCHAR(255),
        FOREIGN KEY (consult_patientid) REFERENCES patients(patient_id)
    )",
    
    "CREATE TABLE IF NOT EXISTS prescribemed (
        pm_id INT AUTO_INCREMENT PRIMARY KEY,
        pm_consultid INT NOT NULL,
        pm_medstockid INT NOT NULL,
        pm_medqty INT NOT NULL,
        FOREIGN KEY (pm_consultid) REFERENCES consultations(consult_id),
        FOREIGN KEY (pm_medstockid) REFERENCES medstock(medstock_id)
    )",
    
    "CREATE TABLE IF NOT EXISTS medissued (
        mi_id INT AUTO_INCREMENT PRIMARY KEY,
        mi_medstockid INT NOT NULL,
        mi_medqty INT NOT NULL,
        mi_date DATE,
        FOREIGN KEY (mi_medstockid) REFERENCES medstock(medstock_id)
    )"
];

// Execute table creation queries
foreach ($tableQueries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . mysqli_error($conn) . "<br>";
    }
}

// Step 5: Insert Sample Data
$insertQueries = [

    "INSERT INTO medicine (medicine_name, medicine_category) VALUES 
    ('Acetylcisteeine', 'Analgesic'),
    ('Ambroxol', 'Anti-inflammatory'),
    ('Amoxicillin', 'Antibiotic'),
    ('Amlodipine', 'Antihistamine');
    ",

   "INSERT INTO medstock (medicine_id, medstock_qty, medstock_dateadded, medstock_dosage, medstock_unit, medstock_expirationdt) VALUES 
    (1, 200, '2023-01-15', '500mg', 'Sachet', '2024-04-25'),  -- Paracetamol
    (1, 100, '2024-10-01', '500mg', 'Sachet', '2024-04-26'),   -- Paracetamol
    (2, 100, '2023-06-10', '400mg', 'Tablets', '2024-06-23'),  -- Ibuprofen
    (3, 100, '2023-05-20', '250mg', 'Tablets', '2024-01-27'),  -- Amoxicillin
    (4, 200, '2023-05-20', '250mg', 'Capsules', '2024-10-25');  -- Amoxicillin
    ",

    "INSERT INTO patients (patient_name, patient_age, patient_gender) VALUES
    ('John Doe', 25, 'Male'),
    ('Jane Smith', 30, 'Female'),
    ('Michael Johnson', 40, 'Male'),
    ('Emily Davis', 22, 'Female'),
    ('David Wilson', 35, 'Male');
    ",

    "INSERT INTO consultations (consult_patientid, consult_diagnosis, consult_trtmentnotes, consult_remark, consult_date, consult_timein, consult_timeout, consult_timespent) VALUES
    (1, 'Severe Headache', 'Prescribe Paracetamol 500mg as needed', 'Rest in a dark room', '2023-04-10', '10:00', '10:20', '20 mins'),
    (2, 'Allergic Reaction', 'Administer Loratadine 10mg', 'Avoid known allergens', '2023-05-15', '14:30', '15:00', '30 mins'),
    (3, 'Gastric Ulcer', 'Prescribe Omeprazole 20mg daily', 'Follow up in 2 weeks', '2024-06-01', '09:30', '09:50', '20 mins'),
    (4, 'High Fever and Pain', 'Ibuprofen 200mg every 6 hours', 'Monitor temperature', '2024-06-15', '11:00', '11:30', '30 mins'),
    (5, 'Bacterial Infection', 'Amoxicillin 250mg for 7 days', 'Complete full course', '2024-07-05', '13:00', '13:25', '25 mins');
    ",

    "INSERT INTO prescribemed (pm_consultid, pm_medstockid, pm_medqty) VALUES
    (1, 1, 20),  -- 15 Paracetamol prescribed for consultation 1
    (2, 1, 20),  -- 10 Cetirizine prescribed for consultation 2
    (3, 3, 70),  -- 14 Amoxicillin prescribed for consultation 3
    (4, 4, 20),  -- 20 Ibuprofen prescribed for consultation 4
    (5, 5, 6);  -- 21 Amoxicillin prescribed for consultation 5
    ",

    "INSERT INTO medissued (mi_medstockid, mi_medqty, mi_date) VALUES 
    (1, 20, '2024-08-26'),  -- Used 20 Paracetamol
    (1, 10, '2024-08-30'),  -- Used another 10 Paracetamol
    (2, 15, '2024-08-29');  -- Used 15 Ibuprofen
    ",

];

// Execute insertion queries
foreach ($insertQueries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "Data inserted successfully<br>";
    } else {
        echo "Error inserting data: " . mysqli_error($conn) . "<br>";
    }
} 

// Close connection
mysqli_close($conn);
?>
