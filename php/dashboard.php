<?php
class AllPatients {
    public $id;
    public $faculty;
    public $student;
    public $staff;
    public $extension;

    public function __construct($patient_id, $patienttype_faculty, $patienttype_student, $patienttype_staff, $patienttype_extension) {
        $this->id = $patient_id;
        $this->faculty = $patienttype_faculty;
        $this->student = $patienttype_student;
        $this->staff = $patienttype_staff;
        $this->extension = $patienttype_extension;
    }
}

class StaffUser {
    public $user_id;
    public $status;

    public function __construct($staffuser_id, $user_status) {
        $this->user_id = $staffuser_id;
        $this->status = $user_status;
    }
}

class Medicines {
    public $medstock_id;
    public $name;
    public $expiration_date;
    public $status;

    public function __construct($medstock_id, $med_name, $expiration_date, $expiration_status) {
        $this->medstock_id = $medstock_id;
        $this->name = $med_name;
        $this->expiration_date = $expiration_date;
        $this->status = $expiration_status;
    }
}

class Transactions {
    public $transaction_id;

    public function __construct($transaction_id) {
        $this->transaction_id = $transaction_id;
    }
}

class DashboardNode {
    public $item;
    public $next;

    public function __construct($item) {
        $this->item = $item;
        $this->next = null;
    }
}

class DashboardLinkedList {
    public $head;

    public function __construct() {
        $this->head = null;
    }

    public function add($item) {
        $newNode = new DashboardNode($item);
        if ($this->head === null) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
    }

    public function getAllNodes() {
        $nodes = [];
        $current = $this->head;
        while ($current !== null) {
            $nodes[] = $current->item;
            $current = $current->next;
        }
        return $nodes;
    }
}

class Dashboard {
    private $db;
    private $allpat;
    private $students;
    private $faculties;
    private $staffs;
    private $extens;

    public function __construct($db) {
        $this->db = $db;
        $this->allpat = new DashboardLinkedList();
        $this->students = new DashboardLinkedList();
        $this->faculties = new DashboardLinkedList();
        $this->staffs = new DashboardLinkedList();
        $this->extens = new DashboardLinkedList();
        $this->countActivePatients();
        $this->countAllConsultationsPerMonth();
        $this->countActiveStaffUsers();
        $this->countAvailableMedstocks();
        $this->getAlmostExpiredMedstocks();
        $this->countTransactions();
    }

    public function countActivePatients() {
        try {
            // Prepare the SQL query to count active patients
            $query = "SELECT COUNT(*) AS active_count FROM patients WHERE patient_status = 'Active'";
    
            // Execute the query
            $stmt = $this->db->prepare($query);
            $stmt->execute();
    
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row['active_count'];
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    

    public function countAllConsultationsPerMonth() {
        try {
            // SQL query to count consultations classified by patient type
            $query = "
                SELECT 
                    YEAR(c.consult_date) AS year,
                    MONTH(c.consult_date) AS month,
                    'Faculties' AS type,
                    COUNT(*) AS count
                FROM consultations c
                INNER JOIN patfaculties pf ON c.consult_patientid = pf.faculty_id
                GROUP BY YEAR(c.consult_date), MONTH(c.consult_date)
                UNION
                SELECT 
                    YEAR(c.consult_date) AS year,
                    MONTH(c.consult_date) AS month,
                    'Students' AS type,
                    COUNT(*) AS count
                FROM consultations c
                INNER JOIN patstudents ps ON c.consult_patientid = ps.student_id
                GROUP BY YEAR(c.consult_date), MONTH(c.consult_date)
                UNION
                SELECT 
                    YEAR(c.consult_date) AS year,
                    MONTH(c.consult_date) AS month,
                    'Staffs' AS type,
                    COUNT(*) AS count
                FROM consultations c
                INNER JOIN patstaffs pst ON c.consult_patientid = pst.staff_id
                GROUP BY YEAR(c.consult_date), MONTH(c.consult_date)
                UNION
                SELECT 
                    YEAR(c.consult_date) AS year,
                    MONTH(c.consult_date) AS month,
                    'Extensions' AS type,
                    COUNT(*) AS count
                FROM consultations c
                INNER JOIN patextensions pe ON c.consult_patientid = pe.exten_id
                GROUP BY YEAR(c.consult_date), MONTH(c.consult_date);
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->execute();
    
            // Process results into a structured array
            $monthlyCounts = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $yearMonth = $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT);
                $type = $row['type'];
                $count = $row['count'];
    
                if (!isset($monthlyCounts[$yearMonth])) {
                    $monthlyCounts[$yearMonth] = [];
                }
                $monthlyCounts[$yearMonth][$type] = $count;
            }
    
            return $monthlyCounts;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    
    

    public function countActiveStaffUsers() {
        try {
            // Prepare the SQL query to count active staff users with role 'Admin'
            $query = "SELECT COUNT(*) AS active_staff_count FROM staffusers WHERE user_status = 'Active' AND user_role = 'Admin'";
    
            // Execute the query
            $stmt = $this->db->prepare($query);
            $stmt->execute();
    
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row['active_staff_count'];
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    

    public function countAvailableMedstocks() {
        try {
            // Prepare the SQL query to count medstocks that are available (not disabled, not expired, and in stock)
            $query = "SELECT COUNT(*) AS available_medstock_count
                      FROM medstock
                      WHERE medstock_disable = 0
                      AND medstock_expirationdt >= CURDATE()
                      AND medstock_qty > 0";
    
            // Execute the query
            $stmt = $this->db->prepare($query);
            $stmt->execute();
    
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row['available_medstock_count'];
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }    

    public function getAlmostExpiredMedstocks($daysThreshold = 30) {
        try {
            // Prepare the SQL query to get medstock details that are almost expired
            $query = "
                SELECT m.medicine_name, ms.medstock_id, ms.medstock_expirationdt
                FROM medstock ms
                JOIN medicine m ON ms.medicine_id = m.medicine_id
                WHERE ms.medstock_disable = 0
                  AND ms.medstock_expirationdt >= CURDATE()
                  AND ms.medstock_expirationdt <= CURDATE() + INTERVAL :daysThreshold DAY
                ORDER BY ms.medstock_expirationdt ASC
                LIMIT 5
            ";
    
            // Prepare and execute the statement with the threshold parameter
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':daysThreshold', $daysThreshold, PDO::PARAM_INT);
            $stmt->execute();
    
            // Fetch the results and store them in an array
            $almostExpiredMedstocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $almostExpiredMedstocks;
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return array();
        }
    }    

    public function countTransactions() {
        try {
            // Prepare the SQL query to count the total number of transactions
            $query = "SELECT COUNT(*) AS total_transactions FROM transactions";
    
            // Execute the query
            $stmt = $this->db->prepare($query);
            $stmt->execute();
    
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row['total_transactions'];
        } catch (PDOException $e) {
            // Handle any errors
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
    

    public function getAllTable() {
        return $this->allpat->getAllNodes();
    }

    public function getAllStudents() {
        return $this->students->getAllNodes();
    }

    public function getAllFaculties() {
        return $this->faculties->getAllNodes();
    }

    public function getAllStaffs() {
        return $this->staffs->getAllNodes();
    }

    public function getAllExtensions() {
        return $this->extens->getAllNodes();
    }
}
?>
