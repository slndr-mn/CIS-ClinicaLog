<?php
class MedicalRecords {
    public $medicalrec_id;
    public $medicalrec_patientid;
    public $medicalrec_filename;
    public $medicalrec_file;
    public $medicalrec_comment;
    public $medicalrec_dateadded;
    public $medicalrec_timeadded;

    public function __construct($id, $patientid, $filename, $file, $comment, $dateadded, $timeadded) {
        $this->medicalrec_id = $id;
        $this->medicalrec_patientid = $patientid;
        $this->medicalrec_filename = $filename;
        $this->medicalrec_file = $file;
        $this->medicalrec_comment = $comment;
        $this->medicalrec_dateadded = $dateadded;
        $this->medicalrec_timeadded = $timeadded;
    }
}

class MedRecNode {
    public $item;
    public $next;

    public function __construct($item) {
        $this->item = $item;
        $this->next = null;
    }
}

class MedRecordsList {
    public $head;

    public function __construct() {
        $this->head = null;
    }

    public function add($item) {
        $newNode = new MedRecNode($item);
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

    public function MedRecExists($patientid, $filename) {
        $current = $this->head;
        while ($current !== null) {
            if ($current->item->medicalrec_patientid === $patientid && 
                strcasecmp($current->item->medicalrec_filename, $filename) === 0) {
                return true;
            }
            $current = $current->next;
        }
        return false;
    }

    public function getDuplicateFilenames($patientid, $filenames) {
        $current = $this->head;
        $duplicateFilenames = [];
    
        while ($current !== null) {
            foreach ($filenames as $filename) {
                if ($current->item->medicalrec_patientid === $patientid && 
                    strcasecmp($current->item->medicalrec_filename, $filename) === 0) {
                    $duplicateFilenames[] = $filename;
                }
            }
            $current = $current->next;
        }
            return $duplicateFilenames;
    }

    public function isDuplicateFilename($patientid, $filename) {
        $current = $this->head;
        while ($current !== null) {
            if ($current->item->medicalrec_patientid === $patientid && 
                    strcasecmp($current->item->medicalrec_filename, $filename) === 0) { {
                return true;  
            }
            $current = $current->next;
        }
        return false; 
        }
    }   
    
    

    public function findMedicalRecordById($medicalrec_id) {
        $current = $this->head;
        while ($current !== null) {
            if ($current->item->medicalrec_id === $medicalrec_id) {
                return $current->item; 
            }
            $current = $current->next; 
        }
        return null; 
    }
    
    
}

class MedRecManager {
    private $db;
    public $medicalrecs;

    public function __construct($db) {
        $this->db = $db; 
        $this->medicalrecs = new MedRecordsList();
        $this->loadMedicalRecords();
    }

    private function loadMedicalRecords() {
        $sql = "SELECT * FROM medicalrec"; 
        $stmt = $this->db->query($sql); 
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $medicalrec = new MedicalRecords(
                $row['medicalrec_id'], $row['medicalrec_patientid'], $row['medicalrec_filename'], 
                $row['medicalrec_file'], $row['medicalrec_comment'], $row['medicalrec_dateadded'], 
                $row['medicalrec_timeadded']
            );        
            $this->medicalrecs->add($medicalrec); 
        }
    }

    public function getDuplicateFilenames($patientid, $filenames) {
        $duplicateFilenames = [];
    
        if (!is_array($filenames)) {
            $filenames = [$filenames]; 
        }
    
        foreach ($filenames as $filename) {
            if ($this->medicalrecs->MedRecExists($patientid, $filename)) {
                $duplicateFilenames[] = $filename;
            }
        }
    
        return $duplicateFilenames;
    }
    

    
    
    public function insertMedicalRecord($patientid, $filenames, $files, $comment, $dateadded, $timeadded) {
        try {    
            $sql = "INSERT INTO medicalrec (medicalrec_patientid, medicalrec_filename, medicalrec_file, medicalrec_comment, medicalrec_dateadded, medicalrec_timeadded) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            foreach ($filenames as $index => $filename) {
                $file = $files[$index]; 
                
                if ($stmt->execute([$patientid, $filename, $file, $comment, $dateadded, $timeadded])) {
                    $medicalrec_id = $this->db->lastInsertId();
                    $newRecord = new MedicalRecords($medicalrec_id, $patientid, $filename, $file, $comment, $dateadded, $timeadded);
                    $this->medicalrecs->add($newRecord);
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Failed to insert one or more medical records.'
                    ];
                }
            }

            return [
                'status' => 'success',
                'message' => 'All medical records inserted successfully.'
            ];
    
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    

    public function updateMedicalRecord($medicalrec_id, $patientid, $filename, $comment) {
        try {

            if ($this->medicalrecs->MedRecExists($patientid, $filename)) {
                $existingRecord = $this->medicalrecs->findMedicalRecordById($medicalrec_id);
                if ($existingRecord && 
                    ($existingRecord->medicalrec_patientid !== $patientid || 
                     strcasecmp($existingRecord->medicalrec_filename, $filename) !== 0)) {
                    return [
                        'status' => 'error',
                        'message' => 'A medical record with this patient ID and filename already exists.'
                    ];
                }
            }
    
            $sql = "UPDATE medicalrec 
                    SET medicalrec_filename = ?,  medicalrec_comment = ?
                    WHERE medicalrec_id = ? AND medicalrec_patientid = ?";
            $stmt = $this->db->prepare($sql);
    
            if ($stmt->execute([$filename, $comment, $medicalrec_id, $patientid ])) {
                return [
                    'status' => 'success',
                    'message' => 'Medical record updated successfully.',
                    'medicalrec_id' => $medicalrec_id
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to update medical record.'
                ];
            }
    
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    

    public function deleteMedicalRecord($medicalrec_id) {
        try {
            $sql = "DELETE FROM medicalrec WHERE medicalrec_id = ?";
            $stmt = $this->db->prepare($sql);
    
            if ($stmt->execute([$medicalrec_id])) {
                return [
                    'status' => 'success',
                    'message' => 'Medical record deleted successfully.',
                    'medicalrec_id' => $medicalrec_id
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to delete medical record.'
                ];
            }
    
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    public function getMedicalRecords($patientid) {
        $records = [];
        $current = $this->medicalrecs->head;

        while ($current !== null) {
            if (strcasecmp($current->item->medicalrec_patientid, $patientid) === 0) {
                $records[] = $current->item; 
            }
            $current = $current->next; 
        }

        return $records; 
    }

    public function getFilePathByMedicalRecId($medicalrecId) {
        $filePath = null; 
        $current = $this->medicalrecs->head; 
    
        while ($current !== null) {
            if (strcasecmp($current->item->medrec_id, $medicalrecId) === 0) {
                $filePath = $current->item->medicalrec_file; 
                break; 
            }
            $current = $current->next; 
        }
    
        return $filePath;
    }
    

    public function isDuplicateFilename($patientid, $filename) {
        return $this->medicalrecs->isDuplicateFilename($patientid, $filename);
    }

}
?>
