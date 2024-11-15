<?php
class MedstockNode {
    public $medstock_id;
    public $item;
    public $unit;
    public $expiry_date;
    public $medicine_balance_month;
    public $medstock_added;
    public $total_start_balance;
    public $total_prescribed;
    public $total_issued;
    public $end_balance;
    public $next;

    public function __construct($medstock_id, $item, $unit, $expiry_date) {
        $this->medstock_id = $medstock_id;
        $this->item = $item;
        $this->unit = $unit;
        $this->expiry_date = $expiry_date;
        $this->next = null;
    }
}


class MedicineManager{
    private $conn;
    private $head;

    public function __construct($conn) {  
        $this->conn = $conn;
        $this->head = null;
    }

    public function calculateTotalPrescribed($medstock_id, $quarterStart, $quarterEnd) {
        $query = "SELECT COALESCE(SUM(pm.pm_medqty), 0) AS total_prescribed
                  FROM prescribemed pm
                  JOIN consultations c ON pm.pm_consultid = c.consult_id
                  WHERE pm.pm_medstockid = ?
                  AND c.consult_date BETWEEN ? AND ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$medstock_id, $quarterStart, $quarterEnd]);
        return $stmt->fetchColumn();
    }

    public function calculateTotalIssued($medstock_id, $quarterStart, $quarterEnd) {
        $query = "SELECT COALESCE(SUM(mi.mi_medqty), 0) AS total_issued
                  FROM medissued mi
                  WHERE mi.mi_medstockid = ? 
                  AND mi.mi_date BETWEEN ? AND ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$medstock_id, $quarterStart, $quarterEnd]);
        return $stmt->fetchColumn();
    }

    public function calculateMedicineBalanceMonth($medstock_id, $cutoffDate) {
        $query = "SELECT COALESCE(
                        (SELECT ms_qty.medstock_qty 
                         FROM medstock ms_qty
                         WHERE ms_qty.medstock_id = ? AND ms_qty.medstock_dateadded < ?) 
                        - COALESCE((SELECT SUM(pm.pm_medqty) 
                                     FROM prescribemed pm
                                     JOIN consultations c ON pm.pm_consultid = c.consult_id
                                     WHERE pm.pm_medstockid = ? AND c.consult_date < ?), 0)
                        - COALESCE((SELECT SUM(mi.mi_medqty) 
                                     FROM medissued mi
                                     WHERE mi.mi_medstockid = ? AND mi.mi_date < ?), 0), 
                        0) AS medicine_balance_month";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$medstock_id, $cutoffDate, $medstock_id, $cutoffDate, $medstock_id, $cutoffDate]);
        return $stmt->fetchColumn();
    }

    public function calculateMedstockAdded($medstock_id, $startDate, $endDate) {
        $query = "SELECT COALESCE(
                        (SELECT ms2.medstock_qty 
                         FROM medstock ms2 
                         WHERE ms2.medstock_id = ? 
                         AND ms2.medstock_dateadded BETWEEN ? AND ?), 
                        0) AS medstock_added";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$medstock_id, $startDate, $endDate]);
        return $stmt->fetchColumn();
    }

    public function addMedstockToList($medstock_id, $item, $unit, $expiry_date, $selectedDate, $quarterStart, $quarterEnd) {
        // Calculate each column value
        $medicine_balance_month = $this->calculateMedicineBalanceMonth($medstock_id, $selectedDate);
        $medstock_added = $this->calculateMedstockAdded($medstock_id, $quarterStart, $quarterEnd);
        $total_start_balance = $medicine_balance_month + $medstock_added;
        $total_prescribed = $this->calculateTotalPrescribed($medstock_id, $quarterStart, $quarterEnd);
        $total_issued = $this->calculateTotalIssued($medstock_id, $quarterStart, $quarterEnd);
    
        // Calculate end_balance as total_start_balance minus (total_prescribed + total_issued)
        $end_balance = $total_start_balance - ($total_prescribed + $total_issued);
    
        // Create new node with calculated values
        $newNode = new MedstockNode($medstock_id, $item, $unit, $expiry_date);
        $newNode->medicine_balance_month = $medicine_balance_month;
        $newNode->medstock_added = $medstock_added;
        $newNode->total_start_balance = $total_start_balance;
        $newNode->total_prescribed = $total_prescribed;
        $newNode->total_issued = $total_issued;
        $newNode->end_balance = $end_balance;
    
        // Insert into linked list
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

    public function fetchAndStoreMedstocks($selectedDate, $quarterStart, $quarterEnd) {
        $query = "SELECT ms.medstock_id, CONCAT(m.medicine_name, ' ', ms.medstock_dosage) AS item, ms.medstock_unit, ms.medstock_expirationdt AS expiry_date
                  FROM medstock ms
                  JOIN medicine m ON ms.medicine_id = m.medicine_id
                  WHERE ms.medstock_dateadded < ? 
                  OR (ms.medstock_dateadded BETWEEN ? AND ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$selectedDate, $quarterStart, $quarterEnd]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->addMedstockToList(
                $row['medstock_id'], 
                $row['item'], 
                $row['medstock_unit'], 
                $row['expiry_date'],
                $selectedDate,
                $quarterStart,
                $quarterEnd
            );
        }
    }

    public function getAllMedstocksAsArray() {
        $data = [];
        $current = $this->head;

        while ($current !== null) {
            $data[] = [
                'medstock_id' => $current->medstock_id,
                'item' => $current->item,
                'unit' => $current->unit,
                'expiry_date' => $current->expiry_date,
                'medicine_balance_month' => $current->medicine_balance_month,
                'medstock_added' => $current->medstock_added,
                'total_start_balance' => $current->total_start_balance,
                'total_prescribed' => $current->total_prescribed,
                'total_issued' => $current->total_issued,
                'end_balance' => $current->end_balance
            ];
            $current = $current->next;
        }
        return $data;
    }

}

?>
