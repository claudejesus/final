<?php
require __DIR__ . '/../auth.php';
require __DIR__ . '/../db.php';

// Initialize arrays
$sensor_data = [];
$temperatures = [];
$humidities = [];
$timestamps = [];

// Fetch sensor data (last 50 entries)
$result = $conn->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 50");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sensor_data[] = $row;
        $temperatures[] = $row['temperature'];
        $humidities[] = $row['humidity'];
        $timestamps[] = $row['timestamp'];
    }
}

// Fetch number of farmers
$farmers = $conn->query("SELECT id FROM users WHERE role = 'farmer'");
$farmer_count = $farmers ? $farmers->num_rows : 0;
?>

<!-- Dashboard Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 border-bottom">
    <h2>Welcome, <?= $_SESSION['user']['username'] ?></h2>
    <h1 class="h2">Dashboard Overview</h1> 
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <!-- <button type="button" href="commands/export_farmers.php class="btn btn-sm btn-outline-secondary">Export</button> -->
            <a href="commands/export_farmers.php" class="btn btn-success mb-3">
            <i class="fas fa-file-csv me-1"></i> Export Farmers (CSV)
            </a>

        </div>
        <span class="badge bg-primary">
            <i class="fas fa-user-shield me-1"></i> Admin
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Temperature</h6>
                <h2 class="card-text"><?= !empty($temperatures) ? end($temperatures) . ' °C' : 'N/A' ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Humidity</h6>
                <h2 class="card-text"><?= !empty($humidities) ? end($humidities) . ' %' : 'N/A' ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Farmers</h6>
                <h2 class="card-text"><?= $farmer_count ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="fas fa-chart-line me-2"></i>Sensor Data Chart</h5>
    </div>
    <div class="card-body">
        <canvas id="sensorChart" height="100"></canvas>
    </div>
</div>

<!-- Table Section -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title"><i class="fas fa-table me-2"></i>Recent Sensor Data</h5>
        <span class="badge bg-primary">Last 50 readings</span>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table">
                <tr>
                    <th>Temperature (°C)</th>
                    <th>Humidity (%)</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sensor_data as $row): ?>
                <tr>
                    <td><?= $row['temperature'] ?></td>
                    <td><?= $row['humidity'] ?></td>
                    <td><?= $row['timestamp'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('sensorChart').getContext('2d');
const sensorChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_reverse($timestamps)) ?>,
        datasets: [
            {
                label: 'Temperature (°C)',
                data: <?= json_encode(array_reverse($temperatures)) ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Humidity (%)',
                data: <?= json_encode(array_reverse($humidities)) ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            x: { ticks: { maxRotation: 45, minRotation: 30 } }
        }
    }
});
</script>
