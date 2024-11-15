<?php
header('Content-Type: application/json');

// Enable error reporting to help with debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the necessary files
include('../database/config.php');
include('../php/medicalrecords.php');

$db = new Database();
$conn = $db->getConnection();

try {
    // Check if the connection is successful
    if (!$conn) {
        echo json_encode(["error" => "Database connection failed"]);
        exit;
    }

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

    // List of all months in the year for correct ordering
    $allMonths = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    // Query to fetch distinct student programs
    $programStmt = $conn->prepare("SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(student_program, '(', -1), ')', 1) AS program FROM patstudents WHERE student_program LIKE '%('");
    $programStmt->execute();
    $programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the program names for result processing
    $programColumns = [];
    foreach ($programs as $program) {
        $programColumns[] = $program['program'];
    }

    // Query to fetch transaction counts per month and per program
    $query = "
    SELECT 
        MONTH(t.transac_date) AS month, 
        SUBSTRING_INDEX(SUBSTRING_INDEX(s.student_program, '(', -1), ')', 1) AS program,
        COUNT(t.transac_id) AS transaction_count,
        COUNT(CASE WHEN f.faculty_patientid IS NOT NULL THEN t.transac_id END) AS faculty,
        COUNT(CASE WHEN st.staff_patientid IS NOT NULL THEN t.transac_id END) AS staff,
        COUNT(CASE WHEN e.exten_patientid IS NOT NULL THEN t.transac_id END) AS extension,
        COUNT(t.transac_id) AS monthly_total
    FROM 
        transactions t
    LEFT JOIN 
        patstudents s ON s.student_patientid = t.transac_patientid
    LEFT JOIN 
        patfaculties f ON f.faculty_patientid = t.transac_patientid
    LEFT JOIN 
        patstaffs st ON st.staff_patientid = t.transac_patientid
    LEFT JOIN 
        patextensions e ON e.exten_patientid = t.transac_patientid
    WHERE 
        t.transac_status = 'Done' 
        AND YEAR(t.transac_date) = :year
    GROUP BY 
        MONTH(t.transac_date), program
    ORDER BY 
        MONTH(t.transac_date), program;
    ";

    // Execute the query
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any data is returned
    if (empty($result)) {
        echo json_encode(["error" => "No data found"]);
        exit;
    }

    // Prepare the final data structure with month as keys (1-12)
    $data = [];
    $overall_total = [
        'month' => 'Total',
        'faculty' => 0,
        'staff' => 0,
        'extension' => 0
    ];

    // Initialize the overall total for each program
    foreach ($programColumns as $program) {
        $overall_total[$program] = 0;
    }

    // Populate monthly data and calculate overall totals for each program
    foreach ($allMonths as $monthNumber => $monthName) {
        $monthlyData = [
            'month' => $monthName, 
            'monthly_total' => 0, 
            'faculty' => 0, 
            'staff' => 0, 
            'extension' => 0
        ];
        
        // Initialize counts for each program to 0
        foreach ($programColumns as $program) {
            $monthlyData[$program] = 0;
        }

        // Populate monthly data with actual counts and update overall totals
        foreach ($result as $row) {
            if ($row['month'] == $monthNumber) {
                if ($row['program']) {
                    $monthlyData[$row['program']] = $row['transaction_count'];
                }
                $monthlyData['faculty'] += $row['faculty'];
                $monthlyData['staff'] += $row['staff'];
                $monthlyData['extension'] += $row['extension'];
                $monthlyData['monthly_total'] += $row['monthly_total'];

                // Update the overall totals for each program
                if ($row['program']) {
                    $overall_total[$row['program']] += $row['transaction_count'];
                }
                $overall_total['faculty'] += $row['faculty'];
                $overall_total['staff'] += $row['staff'];
                $overall_total['extension'] += $row['extension'];
            }
        }

        $data[] = $monthlyData;
    }

    // Append the overall total row at the top
    array_unshift($data, $overall_total);

    // Encode data into JSON and check if encoding is successful
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        echo json_encode(["error" => "Failed to encode data as JSON"]);
    } else {
        echo $jsonData;
    }

} catch (PDOException $e) {
    // Handle any PDO exceptions
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
