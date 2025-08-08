<?php
// inventory_module.php

class InventoryModule {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a new blood unit to the inventory
    public function addBloodUnit($data) {
        $query = "INSERT INTO blood_inventory 
                  (blood_type, donor_id, collection_date, expiry_date, status) 
                  VALUES 
                  (:blood_type, :donor_id, :collection_date, 
                   DATE_ADD(:collection_date, INTERVAL 42 DAY), 'Available')";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":blood_type" => $data['blood_type'],
                ":donor_id" => $data['donor_id'],
                ":collection_date" => $data['collection_date']
            ]);
            return ["status" => "success", "unit_id" => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    // Retrieve current inventory status, grouped by blood type
    public function getInventoryStatus() {
        $query = "SELECT blood_type, 
                         COUNT(CASE WHEN status = 'Available' AND expiry_date > CURDATE() THEN 1 END) AS available_units,
                         COUNT(CASE WHEN status = 'Reserved' THEN 1 END) AS reserved_units,
                         COUNT(CASE WHEN expiry_date <= CURDATE() THEN 1 END) AS expired_units
                  FROM blood_inventory
                  GROUP BY blood_type";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Check for low inventory based on thresholds
    public function checkLowInventory() {
        $query = "SELECT blood_type, 
                         COUNT(*) AS available_units,
                         CASE 
                             WHEN COUNT(*) < 10 THEN 'Critical'
                             WHEN COUNT(*) < 20 THEN 'Low'
                             ELSE 'Normal'
                         END AS status
                  FROM blood_inventory
                  WHERE status = 'Available' 
                  AND expiry_date > CURDATE()
                  GROUP BY blood_type
                  HAVING available_units < 20";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as &$row) {
                if (!isset($row['status'])) {
                    $row['status'] = 'Normal';
                }
            }
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Update the status of a specific blood unit
    public function updateUnitStatus($unit_id, $status) {
        $query = "UPDATE blood_inventory 
                  SET status = :status 
                  WHERE unit_id = :unit_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ":unit_id" => $unit_id,
                ":status" => $status
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Mark expired units as 'Expired' based on expiry date
    public function removeExpiredUnits() {
        $query = "UPDATE blood_inventory 
                  SET status = 'Expired' 
                  WHERE expiry_date <= CURDATE() 
                  AND status = 'Available'";
        
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
