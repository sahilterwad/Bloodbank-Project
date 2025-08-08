<?php include_once 'header.php'; ?> <!-- Including header -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Registration</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="container">
        <h2>Register as a Donor</h2>

        <!-- Display error message if registration fails -->
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php } ?>

        <form action="process_form.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="blood_type">Blood Type:</label>
            <select id="blood_type" name="blood_type" required>
                <option value="" disabled selected>Select your blood type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select><br><br>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <!-- New Location Field -->
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required><br><br>

            <button type="submit">Register</button>
        </form>

        <!-- Link to view donors list -->
        <div class="mt-4">
            <a href="view_donor.php" class="btn btn-secondary">Back to Donor List</a>
        </div>
    </div>
</body>
</html>
