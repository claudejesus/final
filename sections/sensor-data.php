<?php
require '../db.php';
$result = $conn->query("SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 50");
$sensor_data = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sensor Data</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
         <a href="commands/export_data.php" class="btn btn-outline-primary mb-3">
            <i class="fas fa-file-csv me-1"></i> Export data (CSV)
            </a>
        </div>
    </div>
</div>

<!-- <div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-chart-area me-2"></i>Sensor Data Chart
        </h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="sensorChart"></canvas>
        </div>
    </div>
</div> -->

<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-table me-2"></i>Recent Readings
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Temperature (°C)</th>
                        <th>Humidity (%)</th>
                        <th>Timestamp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sensor_data as $row): ?>
                    <tr>
                        <td><?= $row['temperature'] ?></td>
                        <td><?= $row['humidity'] ?></td>
                        <td><?= $row['timestamp'] ?></td>
                        <td> </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>