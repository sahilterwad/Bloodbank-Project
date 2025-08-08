<?php include_once 'header.php'; ?> <!-- Including header -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank - Patient Registration</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <h2>Register as a Patient in Need of Blood</h2>
    
    <form action="process_patient_form.php" method="post">
        <!-- Patient Information Section -->
        <h3>Patient Information</h3>
        
        <label for="name">Patient Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="contact">Contact Number:</label>
        <input type="text" id="contact" name="contact" required><br><br>

        <label for="hospital">Hospital Name:</label>
        <input type="text" id="hospital" name="hospital" required><br><br>

        <label for="emergency_status">Emergency Status:</label>
        <select id="emergency_status" name="emergency_status" required>
            <option value="Normal">Normal</option>
            <option value="Emergency">Emergency</option>
        </select><br><br>

        <label for="diagnosis">Diagnosis:</label>
        <input type="text" id="diagnosis" name="diagnosis" required><br><br>
        
        <!-- Blood Request Information Section -->
        <h3>Blood Request Information</h3>
        
        <label for="blood_type">Required Blood Type:</label>
        <select id="blood_type" name="blood_type" required>
            <option value="O-">O-</option>
            <option value="O+">O+</option>
            <option value="A-">A-</option>
            <option value="A+">A+</option>
            <option value="B-">B-</option>
            <option value="B+">B+</option>
            <option value="AB-">AB-</option>
            <option value="AB+">AB+</option>
        </select><br><br>

        <label for="units_required">Units of Blood Required:</label>
        <input type="number" id="units_required" name="units_required" min="1" required><br><br>

        <label for="urgency_level">Urgency Level:</label>
        <select id="urgency_level" name="urgency_level" required>
            <option value="Normal">Normal</option>
            <option value="Emergency">Emergency</option>
        </select><br><br>

        <!-- Submit Button -->
        <button type="submit">Register Patient</button>
    </form>
</body>
</html>
<?php if (isset($error_message)) { ?>
    <div class="alert alert-danger">
        <?php echo $error_message; ?>
    </div>
<?php } ?>