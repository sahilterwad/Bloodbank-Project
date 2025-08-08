<?php
// find_match.php
include_once 'header.php';
include_once 'db_connect.php';

// Initialize variables
$patient_name = "";
$patient_blood_type = "";
$patient_id = "";
$donors = [];

// Check if patient details are passed in the query string (from view_patients.php)
if (isset($_GET['patient_id']) && isset($_GET['patient_name']) && isset($_GET['blood_type'])) {
    $patient_id = $_GET['patient_id'];
    $patient_name = $_GET['patient_name'];
    $patient_blood_type = $_GET['blood_type'];

    // Query to fetch donors with matching blood type
    $query = "SELECT * FROM donors WHERE blood_type = :blood_type ORDER BY name";
    $stmt = $conn->prepare($query);
    $stmt->execute([':blood_type' => $patient_blood_type]);

    // Fetch matching donors
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If the form is submitted
    $patient_name = $_POST['patient_name'];
    $patient_blood_type = $_POST['blood_type'];
    
    // Query to fetch donors with matching blood type
    $query = "SELECT * FROM donors WHERE blood_type = :blood_type ORDER BY name";
    $stmt = $conn->prepare($query);
    $stmt->execute([':blood_type' => $patient_blood_type]);

    // Fetch matching donors
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container mt-4">
    <h2>Find Blood Match</h2>

    <!-- Form for entering patient details if not passed in URL -->
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" id="patient_name" class="form-control" value="<?php echo htmlspecialchars($patient_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="blood_type">Blood Type:</label>
            <input type="text" name="blood_type" id="blood_type" class="form-control" value="<?php echo htmlspecialchars($patient_blood_type); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Find Blood Match</button>
    </form>

    <!-- Display the patient details -->
    <?php if ($patient_name && $patient_blood_type) { ?>
        <h3>Searching for Donors for:</h3>
        <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($patient_name); ?></p>
        <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($patient_blood_type); ?></p>
    <?php } ?>

    <!-- Display Matched Donors -->
    <?php if (count($donors) > 0) { ?>
        <h3>Matched Donors</h3>
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Blood Type</th>
                    <th>Contact</th>
                    <th>Last Donation Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donors as $index => $donor) { ?>
                    <tr>
                        <td><?php echo ($index + 1); ?></td>
                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                        <td><?php echo htmlspecialchars($donor['contact']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date']); ?></td>
                        <td><?php echo htmlspecialchars($donor['location']); ?></td>
                        <td>
                            <a href="view_donor_details.php?id=<?php echo $donor['donor_id']; ?>" class="btn btn-info btn-sm">View Details</a>
                            <a href="edit_donor.php?id=<?php echo $donor['donor_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="blood_requests.php" method="POST" style="display:inline;">
    <input type="hidden" name="donor_id" value="<?php echo $donor['donor_id']; ?>" />
    <input type="hidden" name="donor_name" value="<?php echo htmlspecialchars($donor['name']); ?>" />
    <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($donor['blood_type']); ?>" />
    <input type="text" name="patient_name" placeholder="Enter Patient Name" required /> <!-- Input for patient name -->
    <button type="submit" name="request_blood" class="btn btn-danger btn-sm">Request Blood</button>
</form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No matching donors found for this patient.</p>
    <?php } ?>
</div>
