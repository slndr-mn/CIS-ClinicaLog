<?php
// Consultation class
class Consultation {
    public $consultation_id;
    public $patient_idnum;
    public $consultation_diagnosis;
    public $medstock_id; // Corrected to store medicine name
    public $treatment_medqty;
    public $treatment_notes;
    public $consultation_remark;
    public $consult_date;
    public $time_in;
    public $time_out;
    public $time_spent; // Add time spent property

    public function __construct($id, $idnum, $diagnosis, $medname, $medqty, $notes, $remark, $date, $in, $out, $time_spent = null) {
        $this->consultation_id = $id;
        $this->patient_idnum = $idnum;
        $this->consultation_diagnosis = $diagnosis;
        $this->medstock_id = $medname; // Assign medicine name correctly
        $this->treatment_medqty = $medqty;
        $this->treatment_notes = $notes;
        $this->consultation_remark = $remark;
        $this->consult_date = $date;
        $this->time_in = $in;
        $this->time_out = $out;
        $this->time_spent = $time_spent; // Store time spent
    }
}


// ConsultListNode class
class ConsultListNode {
    public $item;
    public $next;

    public function __construct($item) {
        $this->item = $item;
        $this->next = null;
    }
}

// ConsultationLinkedList class
class ConsultationLinkedList {
    public $head;

    public function __construct() {
        $this->head = null;
    }

    public function add($item) {
        $newNode = new ConsultListNode($item);
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

    public function find($id) {
        $current = $this->head;
        while ($current !== null) {
            if ($current->item->consultation_id == $id) {
                return $current->item;
            }
            $current = $current->next;
        }
        return null;
    }

    public function remove($id) {
        if ($this->head === null) return false;

        if ($this->head->item->consultation_id == $id) {
            $this->head = $this->head->next;
            return true;
        }

        $current = $this->head;
        while ($current->next !== null) {
            if ($current->next->item->consultation_id == $id) {
                $current->next = $current->next->next;
                return true;
            }
            $current = $current->next;
        }
        return false;
    }
}

// ConsultationManager class
class ConsultationManager { 
    private $db;
    public $consultations;

    public function __construct($db) {
        $this->db = $db;
        $this->consultations = new ConsultationLinkedList();
        $this->loadConsultations();
    }

    private function loadConsultations() {
        $sql = "SELECT *, TIMEDIFF(time_out, time_in) AS time_spent FROM consultations";
        $stmt = $this->db->query($sql); // Use PDO query method
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $consultation = new Consultation(
                $row['consultation_id'],
                $row['patient_idnum'],
                $row['diagnosis'],
                $row['medstock_id'], // Load the medicine name from the database
                $row['treatment_medqty'],
                $row['treatment_notes'],
                $row['remark'],
                $row['consult_date'],
                $row['time_in'],
                $row['time_out'],
                $row['time_spent'] // Add time spent here
            );

            $this->consultations->add($consultation);
        }
    }

    public function insertConsultation($patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent) {
        try {
            // Add time_in, time_out, and time_spent into the database
            $sql = "INSERT INTO consultations (patient_id, diagnosis, medstock_id, treatment_medqty, treatment_notes, remark, consult_date, time_in, time_out, time_spent)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            $params = [
                $patient_idnum, 
                $diagnosis, 
                $medstock_id,
                $treatment_medqty, 
                $treatment_notes, 
                $remark, 
                $consult_date, 
                $time_in, 
                $time_out, 
                $time_spent
            ];

            $stmt->execute($params);

            $consultation_id = $this->db->lastInsertId(); 
    
                // Create a new Consultation object
                $consultation = new Consultation($consultation_id, $patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent);
    
                $this->consultations->add($consultation); // Add the consultation to the consultations collection
    
                return ['status' => 'success', 'message' => 'Consultation inserted successfully.', 'consultation_id' => $consultation_id];
      
        } catch (PDOException $e) {
            // Catch and return any PDOExceptions
            error_log("Error inserting consultation: " . $e->getMessage());
            
            // Show sanitized error response
            return [
                'status' => 'error',
                'message' => 'Error inserting patient: ' . $e->getMessage(),  // Include SQL error message
                'details' => [
                    'sqlState' => $e->getCode(),  // SQL State for reference
                    'params' => json_encode($params)  // Log the parameters passed for debugging
                ]
            ];
        }
    }
    
    

    public function getAllConsultations() {
        return $this->consultations->getAllNodes();
    }

    public function getConsultations() {
        $sql = "SELECT consultations.*, patients.patient_fname, patients.patient_lname 
                FROM consultations 
                JOIN patients ON consultations.patient_idnum = patients.patient_id";
        
        $consultations = $this->db->query($sql)->fetchAll();
    
        $consultationDetails = [];
    
        foreach ($consultations as $consultation) {
            $consultationDetails[] = [
                'consultation_id' => $consultation['consultation_id'],
                'name' => $consultation['patient_lname'] . ' ' . $consultation['patient_fname'],
                'patient_idnum' => $consultation['patient_idnum'],
                'diagnosis' => $consultation['diagnosis'],
                'treatment_medname' => $consultation['medstock_id'],
                'treatment_medqty' => $consultation['treatment_medqty'],
                'remark' => $consultation['remark'],
                'consult_date' => $consultation['consult_date'],
                'time_in' => $consultation['time_in'],
                'time_out' => $consultation['time_out'],
                'time_spent' => $consultation['time_spent'],
            ];
        }
    
        return $consultationDetails;
    }
    
    

    public function deleteConsultation($consultation_id) {
        // Remove from the database
        $sql = "DELETE FROM consultations WHERE consultation_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->execute([$consultation_id]);
            // Also remove from the linked list
            if ($this->consultations->remove($consultation_id)) {
                return ['status' => 'success', 'message' => 'Consultation deleted successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Error deleting consultation from linked list.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Error deleting consultation from database.'];
        }
    }

    public function searchPatientByNameOrId($query) {
        $sql = "SELECT * FROM patients WHERE name LIKE ? OR student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $query . '%', $query]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    
}
?>