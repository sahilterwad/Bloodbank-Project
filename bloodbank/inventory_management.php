<?php
include_once 'header.php'; // Include header file
include_once 'db_connect.php'; // Database connection
include_once 'inventory_module.php'; // Inventory module file

// Instantiate InventoryModule
$inventoryModule = new InventoryModule($conn);

// Handle blood donation
if (isset($_POST['donate_blood'])) {
    $data = [
        'blood_type' => $_POST['blood_type'],
        'donor_id' => $_POST['donor_id'],
        'collection_date' => $_POST['collection_date']
    ];
    $result = $inventoryModule->addBloodUnit($data);
    $message = $result['status'] === 'success' ? "Blood unit donated successfully." : "Error: {$result['message']}";
}

// Handle removal of expired units
if (isset($_POST['remove_expired_units'])) {
    $result = $inventoryModule->removeExpiredUnits();
    $message = $result ? "Expired units removed successfully." : "Error in removing expired units.";
}

// Get inventory status and low inventory alerts
$inventoryStatus = $inventoryModule->getInventoryStatus();
$lowInventory = $inventoryModule->checkLowInventory();
?>

<div class="container mt-4">
    <h2>Blood Inventory Management</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Blood Donation Form -->
    <h4>Donate Blood</h4>
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label>Blood Type</label>
            <input type="text" name="blood_type" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Donor ID</label>
            <input type="number" name="donor_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Collection Date</label>
            <input type="date" name="collection_date" class="form-control" required>
        </div>
        <button type="submit" name="donate_blood" class="btn btn-primary">Donate Blood</button>
    </form>

    <!-- Inventory Status Table -->
    <h4>Current Inventory Status</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Blood Type</th>
                <th>Available Units</th>
                <th>Reserved Units</th>
                <th>Expired Units</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventoryStatus as $status): ?>
                <tr>
                    <td><?= $status['blood_type']; ?></td>
                    <td><?= $status['available_units']; ?></td>
                    <td><?= $status['reserved_units']; ?></td>
                    <td><?= $status['expired_units']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Low Inventory Alerts -->
    <h4>Low Inventory Alerts</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Blood Type</th>
                <th>Available Units</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lowInventory as $alert): ?>
                <tr>
                    <td><?= $alert['blood_type']; ?></td>
                    <td><?= $alert['available_units']; ?></td>
                    <td><?= isset($alert['status']) ? $alert['status'] : 'Normal'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Remove Expired Units Button -->
    <form method="POST">
        <button type="submit" name="remove_expired_units" class="btn btn-danger mt-4">Remove Expired Units</button>
    </form>
</div>