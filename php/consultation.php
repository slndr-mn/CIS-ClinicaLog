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
        // Check if the patient_idnum exists in the patients table
        $checkPatient = $this->db->prepare("SELECT * FROM patients WHERE patient_id = ?");
        $checkPatient->execute([$patient_idnum]);
    
        if ($checkPatient->rowCount() == 0) {
            return ['status' => 'error', 'message' => 'Error: Patient does not exist. Please add the patient before recording a consultation.'];
        }
    
        // Add time_in, time_out, and time_spent into the database
        $sql = "INSERT INTO consultations (patient_id, diagnosis, medstock_id, treatment_medqty, treatment_notes, remark, consult_date, time_in, time_out, time_spent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->execute([$patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent]);
    
            $consultation_id = $this->db->lastInsertId(); // Get last inserted ID
    
            // Create a new Consultation object
            $consultation = new Consultation($consultation_id, $patient_idnum, $diagnosis, $medstock_id, $treatment_medqty, $treatment_notes, $remark, $consult_date, $time_in, $time_out, $time_spent);
    
            $this->consultations->add($consultation); // Add the consultation to the consultations collection
    
            return ['status' => 'success', 'message' => 'Consultation added successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Error adding consultation: ' . $this->db->errorInfo()[2]];
        }
    }

    public function calculateTimeSpent($time_in, $time_out) {
        $start = new DateTime($time_in);
        $end = new DateTime($time_out);

        // Calculate the time difference
        $interval = $start->diff($end);

        // Format the difference (e.g., hours and minutes)
        return $interval->format('%H hours %I minutes');
    }

    public function getAllConsultations() {
        return $this->consultations->getAllNodes();
    }

    public function getConsultations() {
        $consultations = $this->consultations->getAllNodes();
        
        // Create a map to count occurrences per consultation_id
        $consultationsMap = [];
        
        // Count occurrences of each consultation_id in consultations
        foreach ($consultations as $consultation) {
            if (!isset($consultationsMap[$consultation->consultation_id])) {
                $consultationsMap[$consultation->consultation_id] = 0;
            }
            // Increment occurrence for each consultation entry 
            $consultationsMap[$consultation->consultation_id]++;
        }
    
        // Return the entire consultations map with counts
        return $consultationsMap; 
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