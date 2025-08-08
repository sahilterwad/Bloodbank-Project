<?php
// Include the database connection file
require 'db_connect.php';

// Include the donor module file
require 'donor_module.php';

// Create an instance of DonorModule with the database connection
$donorModule = new DonorModule($conn);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $data = [
        'name' => $_POST['name'],
        'blood_type' => $_POST['blood_type'],
        'contact' => $_POST['contact'],
        'email' => $_POST['email'],
        'location' => $_POST['location'] // Include location field
    ];

    // Register the donor using the DonorModule
    if ($donorModule->registerDonor($data)) {
        // Redirect to view_donor.php on successful registration
        header("Location: view_donor.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        // Registration failed, set an error message and redirect back to the registration form
        $error_message = "Registration failed. Please try again.";
        include 'donor_regs.php'; // Load form with error message
        exit();
    }    
}
?>
