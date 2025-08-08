<?php
// find_donor.php
include_once 'header.php';
include_once 'db_connect.php';

// Check if patient_id is provided in the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Query to fetch patient details
    $query = "SELECT * FROM patients WHERE patient_id = $patient_id";
    $patient_result = $conn->query($query);
    
    if ($patient_result->num_rows > 0) {
        // Fetch the patient's details
        $patient = $patient_result->fetch_assoc();
        $patient_blood_type = $patient['blood_type'];
    } else {
        // If no patient found, redirect to patient list
        header('Location: view_patients.php');
        exit();
    }

    // Query to find donors with the same blood type
    $donor_query = "SELECT * FROM donors WHERE blood_type = '$patient_blood_type'";
    $donor_result = $conn->query($donor_query);
    
    // Fetch all donor matches
    $donors = $donor_result->fetch_all(MYSQLI_ASSOC);
}

// Handle blood request
if (isset($_POST['request_blood'])) {
    $donor_id = $_POST['donor_id'];

    // Check if the donor's blood is available in the inventory
    $inventory_query = "SELECT * FROM blood_inventory WHERE blood_type = '$patient_blood_type' AND donor_id = $donor_id";
    $inventory_result = $conn->query($inventory_query);
    
    if ($inventory_result->num_rows > 0) {
        // Blood is available in inventory
        $message = "Blood request sent to donor ID: $donor_id.";
    } else {
        // Check for other matched donors' blood in the inventory
        $available_donor = false;
        foreach ($donors as $donor) {
            $other_donor_query = "SELECT * FROM blood_inventory WHERE blood_type = '$donor[blood_type]' AND donor_id = $donor[donor_id]";
            $other_result = $conn->query($other_donor_query);
            if ($other_result->num_rows > 0) {
                $available_donor = true;
                break;
            }
        }

        if ($available_donor) {
            $message = "Blood from another donor is available.";
        } else {
            // Check for urgent donation possibility
            $urgent_query = "SELECT * FROM donors WHERE donor_id = $donor_id AND can_urgent_donate = 1";
            $urgent_result = $conn->query($urgent_query);
            if ($urgent_result->num_rows > 0) {
                $message = "Urgent donation requested from donor ID: $donor_id.";
            } else {
                $message = "No blood available in inventory. Please check later.";
            }
        }
    }
    
    // Insert blood request into the database
    $request_query = "INSERT INTO blood_requests (patient_id, donor_id, request_status) VALUES ($patient_id, $donor_id, 'Pending')";
    $conn->query($request_query);
}
?>

<div class="container mt-4">
    <h2>Find Donor for <?php echo htmlspecialchars($patient['name']); ?></h2>

    <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($patient_blood_type); ?></p>

    <h4>Available Donors</h4>
    <?php if (count($donors) > 0) { ?>
        <form method="POST">
            <table class="table table-bordered mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Blood Type</th>
                        <th>Contact</th>
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
                            <td>
                                <button type="submit" name="request_blood" class="btn btn-primary btn-sm">
                                    Request Blood
                                </button>
                                <input type="hidden" name="donor_id" value="<?php echo $donor['donor_id']; ?>" />
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
    <?php } else { ?>
        <p>No donors found with the same blood type.</p>
    <?php } ?>

    <p><?php echo isset($message) ? $message : ''; ?></p>
</div>


