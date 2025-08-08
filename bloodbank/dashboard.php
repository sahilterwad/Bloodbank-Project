<?php
// dashboard.php
include_once 'header.php';
include_once 'db_connect.php'; // Database connection

// Queries to get counts
$totalDonorsQuery = "SELECT COUNT(*) AS total_donors FROM donors";
$totalUnitsQuery = "SELECT SUM(available_units) AS available_units FROM blood_inventory"; // Sum of available units
$pendingRequestsQuery = "SELECT COUNT(*) AS pending_requests FROM blood_requests WHERE request_status = 'Pending'";
$successfulMatchesQuery = "SELECT COUNT(*) AS successful_matches FROM successful_matches";

// Prepare and execute the queries
$totalDonorsResult = $conn->query($totalDonorsQuery)->fetch(PDO::FETCH_ASSOC);
$totalUnitsResult = $conn->query($totalUnitsQuery)->fetch(PDO::FETCH_ASSOC);
$pendingRequestsResult = $conn->query($pendingRequestsQuery)->fetch(PDO::FETCH_ASSOC);
$successfulMatchesResult = $conn->query($successfulMatchesQuery)->fetch(PDO::FETCH_ASSOC);

// Assign fetched data to variables
$totalDonors = $totalDonorsResult['total_donors'] ?? 0;
$availableUnits = $totalUnitsResult['available_units'] ?? 0;
$pendingRequests = $pendingRequestsResult['pending_requests'] ?? 0;
$successfulMatches = $successfulMatchesResult['successful_matches'] ?? 0;
?>


<div class="container mt-4">
    <h2>Dashboard</h2>
    
    <!-- Overview Cards (Total Donors, Available Units, Pending Requests, Successful Matches) -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Donors</h5>
                    <h2 class="card-text" id="totalDonors">15</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Available Units</h5>
                    <h2 class="card-text" id="availableUnits">5</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Pending Requests</h5>
                    <h2 class="card-text" id="pendingRequests">5</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Successful Matches</h5>
                    <h2 class="card-text" id="successfulMatches">15</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards for Patient/Donor Management -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Add New Patient</h5>
                    <a href="patient_regs.php" class="btn btn-light">Add Patient</a> <!-- Button to open form -->
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Add New Donor</h5>
                    <a href="donor_regs.php" class="btn btn-light">Add Donor</a> <!-- Button to open form -->
                </div>
            </div>
        </div>

        <!-- Links to View Patient/Donor Database -->
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">View Patients</h5>
                    <a href="view_patient.php" class="btn btn-light">View Patient Database</a> <!-- Link to view patients -->
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">View Donors</h5>
                    <a href="view_donor.php" class="btn btn-light">View Donor Database</a> <!-- Link to view donors -->
                </div>
            </div>
        </div>
    </div>

    <!-- Blood Match Feature Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Blood Match</h5>
                    <a href="find_match.php" class="btn btn-light">Find Blood Match</a> <!-- Link to blood match page -->
                </div>
            </div>
        </div>
    </div>
</div>


