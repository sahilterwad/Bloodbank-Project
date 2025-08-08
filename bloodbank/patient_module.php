<?php
// patient_module.php

class PatientModule {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Register new patient
    public function registerPatient($data) {
        $query = "INSERT INTO patients (name, blood_type, contact, hospital, emergency_status, diagnosis) 
                  VALUES ('{$data['name']}', '{$data['blood_type']}', '{$data['contact']}', '{$data['hospital']}', '{$data['emergency_status']}', '{$data['diagnosis']}')";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()) {
                return ["status" => "success", "message" => "Patient registered successfully"];
            }
        } catch(PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
    
    // Create blood request
    public function createBloodRequest($data) {
        $query = "INSERT INTO blood_requests (patient_id, blood_type, units_required, urgency_level) 
                  VALUES ('{$data['patient_id']}', '{$data['blood_type']}', '{$data['units_required']}', '{$data['urgency_level']}')";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            if($stmt->execute()) {
                $request_id = $this->conn->lastInsertId();
                // If emergency, trigger immediate matching
                if($data['urgency_level'] == 'Emergency') {
                    $this->processEmergencyRequest($request_id);
                }
                return ["status" => "success", "request_id" => $request_id];
            }
        } catch(PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
    
    // Get request status
    public function getRequestStatus($request_id) {
        $query = "SELECT br.*, p.name as patient_name, p.hospital,
                        COUNT(bm.match_id) as matched_units
                 FROM blood_requests br
                 JOIN patients p ON br.patient_id = p.patient_id
                 LEFT JOIN blood_matches bm ON br.request_id = bm.request_id
                 WHERE br.request_id = '$request_id'
                 GROUP BY br.request_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Process emergency request
    private function processEmergencyRequest($request_id) {
        $request = $this->getRequestStatus($request_id);
        if(!$request) return false;
        
        // Find compatible blood types
        $compatible_types = $this->getCompatibleBloodTypes($request['blood_type']);
        
        // Search for available units
        $available_units = $this->findAvailableUnits($compatible_types, $request['units_required']);
        
        if($available_units) {
            foreach($available_units as $unit) {
                $this->createMatch($request_id, $unit['unit_id']);
            }
            return true;
        }
        return false;
    }
    
    // Get compatible blood types
    private function getCompatibleBloodTypes($blood_type) {
        $compatibility = [
            'O-'  => ['O-'],
            'O+'  => ['O-', 'O+'],
            'A-'  => ['O-', 'A-'],
            'A+'  => ['O-', 'O+', 'A-', 'A+'],
            'B-'  => ['O-', 'B-'],
            'B+'  => ['O-', 'O+', 'B-', 'B+'],
            'AB-' => ['O-', 'A-', 'B-', 'AB-'],
            'AB+' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+']
        ];
        
        return $compatibility[$blood_type] ?? [];
    }
}
?>
