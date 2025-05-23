<?php
require 'auth.php';

// Only allow admin access
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: farmer.php'); // Create farmer.php for farmer view
    exit;
}

require 'config/database.php';

// Get dashboard stats
$result = $conn->query("SELECT COUNT(*) as farmer_count FROM users WHERE role = 'farmer'");
$farmer_count = $result->fetch_assoc()['farmer_count'];

$result = $conn->query("SELECT temperature, humidity FROM sensor_data ORDER BY timestamp DESC LIMIT 1");
$latest_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Farm Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Farm Monitoring</h4>
                        <div class="text-muted small">Admin Panel</div>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-section="dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-section="sensor-data">
                                <i class="fas fa-thermometer-half me-2"></i>Sensor Data
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-section="farmers">
                                <i class="fas fa-users me-2"></i>Farmers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-section="controls">
                                <i class="fas fa-fan me-2"></i>Controls
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" id="mainContent">
                <?php include 'sections/dashboard.php'; ?>
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">System Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>