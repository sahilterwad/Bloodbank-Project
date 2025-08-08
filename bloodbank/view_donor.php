<?php
// view_donors.php
include_once 'header.php';  // Include the header
include_once 'db_connect.php';  // Include database connection

// Initialize search filter
$search_query = "";
$params = [];

if (isset($_POST['search'])) {
    // Get the search term from the form
    $search_term = "%" . $_POST['search_term'] . "%";
    $search_query = "WHERE name LIKE :search_term OR blood_type LIKE :search_term OR contact LIKE :search_term OR location LIKE :search_term";
    $params = [':search_term' => $search_term];
}

// Query to fetch all donors from the database based on the search filter
$query = "SELECT * FROM donors $search_query ORDER BY name";

$stmt = $conn->prepare($query);
$stmt->execute($params);

// Fetch all donors
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Donor List</h2>

     <!-- Add Donors Button -->
     <a href="donor_regs.php" class="btn btn-success mb-4">Add New Donor</a>
    
    <!-- Search Bar -->
    <form method="POST" class="form-inline mb-4">
        <input type="text" class="form-control" name="search_term" placeholder="Search by Name, Blood Type, Contact, or Location" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>" required>
        <button type="submit" name="search" class="btn btn-primary ml-2">Search</button>
    </form>

    <!-- Table of Donors -->
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
            <?php if (count($donors) > 0) { ?>
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
                            <!-- Request Blood button -->
                            <form action="blood_requests.php" method="POST" style="display:inline;">
    <input type="hidden" name="donor_id" value="<?php echo $donor['donor_id']; ?>" />
    <input type="hidden" name="donor_name" value="<?php echo htmlspecialchars($donor['name']); ?>" />
    <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($donor['blood_type']); ?>" />
    <input type="text" name="patient_name" placeholder="Enter Patient Name" required /> <!-- Input for patient name -->
    <button type="submit" name="request_blood" class="btn btn-danger btn-sm">Request Blood</button>
</form>

<!-- Donate Blood button (add to inventory) -->
<form action="inventory_management.php" method="POST" style="display:inline;">
        <input type="hidden" name="donor_id" value="<?php echo $donor['donor_id']; ?>" />
        <input type="hidden" name="donor_name" value="<?php echo htmlspecialchars($donor['name']); ?>" />
        <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($donor['blood_type']); ?>" />
        <input type="hidden" name="collection_date" value="<?php echo date('Y-m-d'); ?>" /> <!-- Today's date as collection date -->
        <button type="submit" name="donate_blood" class="btn btn-success btn-sm">Donate Blood</button>
    </form>


                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" class="text-center">No donors found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>