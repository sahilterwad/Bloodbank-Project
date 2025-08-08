<?php
// process_patient_form.php

// Include database connection file
include_once 'db_connect.php';
// Include the PatientModule class
include_once 'patient_module.php';

// Create an instance of the PatientModule class
$patientModule = new PatientModule($conn);

// Check if the form is submitted and validate the data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $data = [
        'name'             => $_POST['name'],
        'blood_type'       => $_POST['blood_type'],
        'contact'          => $_POST['contact'],
        'hospital'         => $_POST['hospital'],
        'emergency_status' => $_POST['emergency_status'],
        'diagnosis'        => $_POST['diagnosis']
    ];

   // Register the patient using the PatientModule
   if ($patientModule->registerPatient($data)) {
    // Redirect to view_patient.php on successful registration
    header("Location: view_patient.php");
    exit(); // Ensure no further code is executed after redirection
} else {
    // Registration failed, set an error message
    $error_message = "Registration failed. Please try again.";
}  
}
?>
