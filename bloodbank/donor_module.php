<?php
// donor_module.php

class DonorModule {
    private $conn;

    // Constructor takes an existing database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new donor
    public function registerDonor($data) {
        $query = "INSERT INTO donors (name, blood_type, contact, email) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$data['name'], $data['blood_type'], $data['contact'], $data['email']]);
    }

    // Update donor contact information
    public function updateDonorContact($donor_id, $contact) {
        $query = "UPDATE donors SET contact = ? WHERE donor_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$contact, $donor_id]);
    }

    // Get donor details by ID
    public function getDonor($donor_id) {
        $query = "SELECT * FROM donors WHERE donor_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$donor_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if donor is eligible based on last donation date
    public function checkEligibility($donor_id) {
        $query = "SELECT last_donation_date FROM donors WHERE donor_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$donor_id]);
        
        $donor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($donor) {
            $lastDonation = new DateTime($donor['last_donation_date']);
            $today = new DateTime();
            $daysSinceLastDonation = $lastDonation->diff($today)->days;

            return $daysSinceLastDonation >= 90; // Returns true if eligible
        }
        
        return false; // Donor not found or not eligible
    }
}
?>
