<?php
// auth.php should contain your authentication logic
// require 'auth.php';
require 'db.php';

// Ensure only admin can access
// if ($_SESSION['user']['role'] !== 'admin') {
//     header('Location: farmer.php');
//     exit;
// }

// Fetch basic stats for dashboard
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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #212529;
        }
        .chart-container {
            height: 300px;
        }
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: none;
        }
    </style>
</head>
<body>
    <div class="loading-spinner" id="loadingSpinner">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Farm Admin</h4>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let chart;

    // Handle navigation
    document.querySelectorAll('.nav-link[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
            loadSection(this.dataset.section);
        });
    });

    function loadSection(section) {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = 'block';

        fetch(`sections/${section}.php`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('mainContent').innerHTML = html;
                if (section === 'dashboard' || section === 'sensor-data') {
                    initChart();
                }
                spinner.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                spinner.style.display = 'none';
            });
    }

    function initChart() {
        const ctx = document.getElementById('sensorChart')?.getContext('2d');
        if (!ctx) return;

        if (chart) chart.destroy();

        fetch('sensor_data_api.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.timestamp);
                const temps = data.map(item => item.temperature);
                const humids = data.map(item => item.humidity);

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Temperature (°C)',
                                data: temps,
                                borderColor: 'rgba(220, 53, 69, 1)',
                                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Humidity (%)',
                                data: humids,
                                borderColor: 'rgba(13, 110, 253, 1)',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' }
                        }
                    }
                });
            });
    }

    function sendCommand(action) {
        fetch('commands/save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action })
        })
        .then(response => response.json())
        .then(data => {
            const toast = new bootstrap.Toast(document.getElementById('toast'));
            document.getElementById('toastBody').innerHTML = data.success ? 
                '<i class="fas fa-check-circle text-success me-2"></i>Command sent!' :
                '<i class="fas fa-times-circle text-danger me-2"></i>Error: ' + data.error;
            toast.show();
        });
    }
    </script>

    <div class="position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">System Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody"></div>
        </div>
    </div>
</body>
</html>