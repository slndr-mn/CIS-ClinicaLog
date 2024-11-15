<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'clinicalog';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the year from the URL parameter
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    // Query to get monthly transaction counts by patient type
    $stmt = $pdo->prepare("
        SELECT 
            MONTH(t.transac_date) AS month,
            COUNT(CASE WHEN p.patient_patienttype = 'Student' THEN 1 END) AS students,
            COUNT(CASE WHEN p.patient_patienttype = 'Faculty' THEN 1 END) AS faculty,
            COUNT(CASE WHEN p.patient_patienttype = 'Staff' THEN 1 END) AS staff,
            COUNT(CASE WHEN p.patient_patienttype = 'Extension' THEN 1 END) AS extension
        FROM 
            transactions t
        INNER JOIN 
            patients p ON t.transac_patientid = p.patient_id
        WHERE 
            YEAR(t.transac_date) = :year AND t.transac_status = 'Done'
        GROUP BY 
            MONTH(t.transac_date)
        ORDER BY 
            month ASC
    ");
    $stmt->execute(['year' => $year]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize arrays for each patient type
    $months = range(1, 12);
    $data = [
        'months' => array_map(function($month) { return date('F', mktime(0, 0, 0, $month, 10)); }, $months),
        'students' => array_fill(0, 12, 0),
        'faculty' => array_fill(0, 12, 0),
        'staff' => array_fill(0, 12, 0),
        'extension' => array_fill(0, 12, 0),
    ];

    // Populate data arrays with results from the query
    foreach ($results as $row) {
        $monthIndex = $row['month'] - 1;
        $data['students'][$monthIndex] = (int)$row['students'];
        $data['faculty'][$monthIndex] = (int)$row['faculty'];
        $data['staff'][$monthIndex] = (int)$row['staff'];
        $data['extension'][$monthIndex] = (int)$row['extension'];
    }

    // Output data as JSON
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
