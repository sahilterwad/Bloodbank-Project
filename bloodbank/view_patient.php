<?php
// view_patients.php
include_once 'header.php';  // Include the header

// Include database connection
include_once 'db_connect.php';

// Initialize search filter
$search_query = "";
$params = [];
if (isset($_POST['search'])) {
    // Get the search term from the form
    $search_term = $_POST['search_term'];
    $search_query = "WHERE name LIKE :search_term OR blood_type LIKE :search_term OR contact LIKE :search_term";
    $params = [':search_term' => "%$search_term%"];
}

// Query to fetch all patients from the database based on the search filter
$query = "SELECT * FROM patients $search_query ORDER BY name";
$stmt = $conn->prepare($query);
$stmt->execute($params);

// Fetch all patients
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check for delete request
if (isset($_GET['delete_patient_id'])) {
    $patient_id_to_delete = $_GET['delete_patient_id'];
    
    // Prepare and execute delete query
    $delete_query = "DELETE FROM patients WHERE patient_id = :patient_id";
    $stmt = $conn->prepare($delete_query);
    $stmt->execute([':patient_id' => $patient_id_to_delete]);
    
    // Redirect back to the patients page after deletion
    header("Location: view_patients.php");
    exit();
}
?>

<div class="container mt-4">
    <h2>Patient List</h2>
    
    <!-- Add Patient Button -->
    <a href="patient_regs.php" class="btn btn-success mb-4">Add New Patient</a>


    <!-- Search Bar -->
    <form method="POST" class="form-inline mb-4">
        <input type="text" class="form-control" name="search_term" placeholder="Search by Name, Blood Type, or Contact" value="<?php echo isset($search_term) ? $search_term : ''; ?>" required>
        <button type="submit" name="search" class="btn btn-primary ml-2">Search</button>
    </form>

    <!-- Table of Patients -->
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Blood Type</th>
                <th>Contact</th>
                <th>Hospital</th>
                <th>Emergency Status</th>
                <th>Diagnosis</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($patients) > 0) { ?>
                <?php foreach ($patients as $index => $patient) { ?>
                    <tr>
                        <td><?php echo ($index + 1); ?></td>
                        <td><?php echo $patient['name']; ?></td>
                        <td><?php echo $patient['blood_type']; ?></td>
                        <td><?php echo $patient['contact']; ?></td>
                        <td><?php echo $patient['hospital']; ?></td>
                        <td><?php echo $patient['emergency_status']; ?></td>
                        <td><?php echo $patient['diagnosis']; ?></td>
                        <td>
                            <!-- View Full Details Button -->
                            <a href="view_full_details.php?patient_id=<?php echo $patient['patient_id']; ?>" class="btn btn-info btn-sm">View Full Details</a>
                            
                           <!-- Find Donor Button -->
<a href="find_match.php?patient_id=<?php echo $patient['patient_id']; ?>&patient_name=<?php echo urlencode($patient['name']); ?>&blood_type=<?php echo urlencode($patient['blood_type']); ?>" class="btn btn-success btn-sm">Find Match</a>

                            
                            <!-- Delete Patient Button -->
                            <a href="view_patients.php?delete_patient_id=<?php echo $patient['patient_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8" class="text-center">No patients found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>