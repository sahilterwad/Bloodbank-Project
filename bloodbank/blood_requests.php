<?php
include_once 'db_connect.php';
include_once 'header.php';

// Handle new blood request creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_blood'])) {
    $patient_name = $_POST['patient_name'];
    $donor_name = $_POST['donor_name'];
    $blood_type = $_POST['blood_type'];

    // Insert the new request as pending
    $insert_query = "INSERT INTO blood_requests (patient_name, donor_name, blood_type, request_status) 
                     VALUES (:patient_name, :donor_name, :blood_type, 'Pending')";
    $stmt = $conn->prepare($insert_query);
    $stmt->execute([
        ':patient_name' => $patient_name,
        ':donor_name' => $donor_name,
        ':blood_type' => $blood_type,
    ]);
}

// Fetch all pending requests
$query = "SELECT * FROM blood_requests WHERE request_status = 'Pending'";
$stmt = $conn->prepare($query);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cancel or accept request based on button clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];

    if (isset($_POST['cancel_request'])) {
        $cancel_query = "UPDATE blood_requests SET request_status = 'Cancelled' WHERE request_id = :request_id";
        $stmt = $conn->prepare($cancel_query);
        $stmt->execute([':request_id' => $request_id]);
    }

    if (isset($_POST['accept_request'])) {
        // Move to successful matches and update status
        $accept_query = "SELECT * FROM blood_requests WHERE request_id = :request_id";
        $stmt = $conn->prepare($accept_query);
        $stmt->execute([':request_id' => $request_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($request) {
            $insert_match_query = "INSERT INTO successful_matches (patient_name, donor_name, blood_type, match_date) 
                                   VALUES (:patient_name, :donor_name, :blood_type, NOW())";
            $stmt = $conn->prepare($insert_match_query);
            $stmt->execute([
                ':patient_name' => $request['patient_name'],
                ':donor_name' => $request['donor_name'],
                ':blood_type' => $request['blood_type'],
            ]);

            $update_request_status = "UPDATE blood_requests SET request_status = 'Accepted' WHERE request_id = :request_id";
            $stmt = $conn->prepare($update_request_status);
            $stmt->execute([':request_id' => $request_id]);
        }
    }

    // Refresh the page after action
    header("Location: blood_requests.php");
    exit();
}
?>

<div class="container mt-4">
    <h2>Pending Blood Requests</h2>
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Donor Name</th>
                <th>Blood Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $index => $request) { ?>
                <tr>
                    <td><?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($request['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['donor_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['blood_type']); ?></td>
                    <td><?php echo htmlspecialchars($request['request_status']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>" />
                            <button type="submit" name="cancel_request" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>" />
                            <button type="submit" name="accept_request" class="btn btn-success btn-sm">Accept</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
