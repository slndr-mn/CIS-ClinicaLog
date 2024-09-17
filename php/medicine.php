<?php

class MedicineListNode {
    public $medicine_id;
    public $medicine_category;
    public $medicine_name;
    public $medicine_qty;
    public $medicine_dosage;
    public $medicine_dateadded;
    public $medicine_expirationdt;
    public $next;

    public function __construct($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt, $next = null) {
        $this->medicine_id = $medicine_id;
        $this->medicine_category = $medicine_category;
        $this->medicine_name = $medicine_name;
        $this->medicine_qty = $medicine_qty;
        $this->medicine_dosage = $medicine_dosage;
        $this->medicine_dateadded = $medicine_dateadded;
        $this->medicine_expirationdt = $medicine_expirationdt;
        $this->next = $next;
    } 
}

class MedLinkedList {
    private $head;

    public function __construct() {
        $this->head = null;
    }

    public function getHead() {
        return $this->head;
    }

    public function addNode($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt) {
        $newNode = new MedicineListNode($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt, $this->head);
        $this->head = $newNode;
    }

    public function findNode($medicine_id) {
        $current = $this->head;
        while ($current !== null) {
            if ($current->medicine_id === $medicine_id) {
                return $current;
            }
            $current = $current->next;
        }
        return null;
    }        

    public function getAllNodes() {
        $nodes = [];
        $current = $this->head;
        while ($current !== null) {
            $nodes[] = $current;
            $current = $current->next;
        }
        return $nodes;
    }

    public function removeNode($medicine_id) {
        $current = $this->head;
        $prev = null;

        while ($current !== null) {
            if ($current->medicine_id === $medicine_id) {
                if ($prev === null) {
                    $this->head = $current->next; 
                } else {
                    $prev->next = $current->next;
                }
                return true;
            }
            $prev = $current;
            $current = $current->next;
        }
        return false;
    }
}

class Medicine {
    private $conn;
    private $linkedList;

    public function __construct($db) {
        $this->conn = $db;
        $this->linkedList = new MedLinkedList();
        $this->loadMedicines();
    }

    public function getAllMedicines() {
        return $this->linkedList->getAllNodes();
    }

    private function loadMedicines() {
        $query = "SELECT * FROM medicine";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->linkedList->addNode(
                $row['medicine_id'],
                $row['medicine_category'],
                $row['medicine_name'],
                $row['medicine_qty'],
                $row['medicine_dosage'],
                $row['medicine_dateadded'],
                $row['medicine_expirationdt']
            );
        }
    }

    public function getMedicineDataByID($medicine_id) {
        $node = $this->linkedList->findNode($medicine_id);
        
        if ($node) {
            return [
                'medicine_id' => $node->medicine_id,
                'medicine_category' => $node->medicine_category,
                'medicine_name' => $node->medicine_name,
                'medicine_qty' => $node->medicine_qty,
                'medicine_dosage' => $node->medicine_dosage,
                'medicine_dateadded' => $node->medicine_dateadded,
                'medicine_expirationdt' => $node->medicine_expirationdt,
            ];
        } else {
            $query = "SELECT medicine_id, medicine_category, medicine_name, medicine_qty, medicine_dosage, medicine_dateadded, medicine_expirationdt FROM medicine WHERE medicine_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(1, $medicine_id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->linkedList->addNode(
                    $row['medicine_id'],
                    $row['medicine_category'],
                    $row['medicine_name'],
                    $row['medicine_qty'],
                    $row['medicine_dosage'],
                    $row['medicine_dateadded'],
                    $row['medicine_expirationdt']
                );
                
                return $row;
            } else {
                return null;
            }
        }
    }        

    public function medicineExists($medicine_id) {
        return $this->linkedList->findNode($medicine_id) !== null;
    }
    
    public function addMedicine($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt) {
        if ($this->medicineExists($medicine_id)) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Medicine already exists.';
            return false;
        }
    
        $query = "INSERT INTO medicine (medicine_category, medicine_name, medicine_qty, medicine_dosage, medicine_dateadded, medicine_expirationdt) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $stmt->bindValue(1, $medicine_category);
            $stmt->bindValue(2, $medicine_name);
            $stmt->bindValue(3, $medicine_qty);
            $stmt->bindValue(4, $medicine_dosage);
            $stmt->bindValue(5, $medicine_dateadded);
            $stmt->bindValue(6, $medicine_expirationdt);
            if ($stmt->execute()) {
                $this->linkedList->addNode($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_dateadded, $medicine_expirationdt);
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Medicine added successfully!';
                header('Location: medicinetable.php');
                exit();
            } else {
                $errorInfo = $stmt->errorInfo();
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error executing query: ' . $errorInfo[2];
                error_log("Error executing query: " . $errorInfo[2]);
                return false;
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error preparing statement: ' . $this->conn->errorInfo()[2];
            error_log("Error preparing statement: " . $this->conn->errorInfo()[2]);
            return false;
        }
    }
    

    public function deleteMedicine($medicine_id) {
        $sql_delete = "DELETE FROM medicine WHERE medicine_id = ?";
        $stmt = $this->conn->prepare($sql_delete);

        if ($stmt) {
            $stmt->bindValue(1, $medicine_id);

            if ($stmt->execute()) {
                $this->linkedList->removeNode($medicine_id);
                return true;
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error executing delete query: ' . $stmt->errorInfo()[2];
                error_log("Error executing delete query: " . $stmt->errorInfo()[2]);
                return false;
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error preparing delete statement: ' . $this->conn->errorInfo()[2];
            error_log("Error preparing delete statement: " . $this->conn->errorInfo()[2]);
            return false;
        }
    }

    public function updateMedicine($medicine_id, $medicine_category, $medicine_name, $medicine_qty, $medicine_dosage, $medicine_expirationdt) {
        $sql_update_statement = "UPDATE medicine SET
            medicine_category = ?,  
            medicine_name = ?, 
            medicine_qty = ?, 
            medicine_dosage = ?,  
            medicine_expirationdt = ?
            WHERE medicine_id = ?"; 
        
        $stmt = $this->conn->prepare($sql_update_statement);
    
        if ($stmt) {
            $stmt->bindParam(1, $medicine_category); 
            $stmt->bindParam(2, $medicine_name); 
            $stmt->bindParam(3, $medicine_qty);
            $stmt->bindParam(4, $medicine_dosage);
            $stmt->bindParam(5, $medicine_expirationdt);
            $stmt->bindParam(6, $medicine_id);
    
            if ($stmt->execute()) {
                $node = $this->linkedList->findNode($medicine_id);
                if ($node) {
                    $node->medicine_category = $medicine_category;
                    $node->medicine_name = $medicine_name;
                    $node->medicine_qty = $medicine_qty;
                    $node->medicine_dosage = $medicine_dosage;
                    $node->medicine_expirationdt = $medicine_expirationdt;
                }
    
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Medicine updated successfully!';
                return true;
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Error updating medicine in the database: ' . $stmt->errorInfo()[2];
                error_log("Error updating medicine in the database: " . $stmt->errorInfo()[2]);
                return false;
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error preparing update statement: ' . $this->conn->errorInfo()[2];
            error_log("Error preparing update statement: " . $this->conn->errorInfo()[2]);
            return false;
        }
    }
}
?>
