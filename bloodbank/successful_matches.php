<?php
include_once 'db_connect.php';  // Include the database connection
include_once 'header.php';      // Include the header

// Query to fetch successful matches from the successful_matches table
$query = "SELECT * FROM successful_matches";
$stmt = $conn->prepare($query);
$stmt->execute();
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Successful Blood Matches</h2>
    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Donor Name</th>
                <th>Blood Type</th>
                <th>Match Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matches as $index => $match) { ?>
                <tr>
                    <td><?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($match['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($match['donor_name']); ?></td>
                    <td><?php echo htmlspecialchars($match['blood_type']); ?></td>
                    <td><?php echo htmlspecialchars($match['match_date']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
