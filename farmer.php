<?php
require 'auth.php';
require 'db.php';

// Ensure only farmers
if ($_SESSION['user']['role'] !== 'farmer') {
    header('Location: farmer.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// Fetch last 50 sensor records for this farmer
$stmt = $conn->prepare("SELECT * FROM sensor_data WHERE id = ? ORDER BY timestamp DESC LIMIT 50");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$sensor_data = [];
while ($row = $result->fetch_assoc()) {
    $sensor_data[] = $row;
}
$stmt->close();

// Summary stats for current farmer
$statsQuery = $conn->prepare("
    SELECT 
        AVG(temperature) AS avg_temp,
        AVG(humidity) AS avg_hum,
        MAX(temperature) AS max_temp,
        MAX(humidity) AS max_hum,
        MIN(temperature) AS min_temp,
        MIN(humidity) AS min_hum
    FROM sensor_data
    WHERE id = ?
");
$statsQuery->bind_param("i", $userId);
$statsQuery->execute();
$statsResult = $statsQuery->get_result()->fetch_assoc();
$statsQuery->close();

// Prepare alerts based on latest reading
$latest = $sensor_data[0] ?? null;
$alerts = [];
if ($latest) {
    if ($latest['temperature'] > 30) {
        $alerts[] = "⚠️ High Temperature: {$latest['temperature']}°C";
    }
    if ($latest['humidity'] > 70) {
        $alerts[] = "⚠️ High Humidity: {$latest['humidity']}%";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
            background: #f4f4f4;
        }
        header {
            background-color: #006400;
            color: white;
            padding: 15px 20px;
        }
        nav {
            background-color: #004d00;
            padding: 10px 20px;
            display: flex;
            gap: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 30px;
        }
        footer {
            background-color: #006400;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #006400;
            color: white;
        }
        .stats-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }
        .stats-card {
            flex: 1;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px #ccc;
            min-width: 180px;
            text-align: center;
        }
        .actions a {
            text-decoration: none;
            margin-right: 15px;
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            display: inline-block;
        }
        .actions a.logout {
            background-color: #dc3545;
        }
        .actions a.profile {
            background-color: #007bff;
        }
        #alertBox {
            background-color: #ffcccc;
            padding: 15px;
            border: 1px solid red;
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?> (Farmer)</h1>
</header>

<nav>
    <a href="farm_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="commands/export_data.php"><i class="fas fa-file-csv"></i> Export Data</a>
    <a href="sections/profileFarmer.php" class="profile"><i class="fas fa-user"></i> Profile</a>
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<main>

    <!-- Summary Cards -->
    <div class="stats-cards">
        <div class="stats-card">
            <h4>🌡️ Avg Temperature</h4>
            <p><?= number_format($statsResult['avg_temp'], 1) ?> °C</p>
        </div>
        <div class="stats-card">
            <h4>💧 Avg Humidity</h4>
            <p><?= number_format($statsResult['avg_hum'], 1) ?> %</p>
        </div>
        <div class="stats-card">
            <h4>🔥 Max Temp</h4>
            <p><?= $statsResult['max_temp'] ?> °C</p>
        </div>
        <div class="stats-card">
            <h4>💦 Max Humidity</h4>
            <p><?= $statsResult['max_hum'] ?> %</p>
        </div>
    </div>

    <!-- Alerts -->
    <?php if (!empty($alerts)): ?>
    <div id="alertBox" style="display:block;">
        <h3 style="color: red;">Alerts:</h3>
        <ul>
            <?php foreach ($alerts as $alert): ?>
                <li><?= $alert ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php else: ?>
    <div id="alertBox"></div>
    <?php endif; ?>

    <h2>Sensor Data</h2>
    <table>
        <thead>
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

    <h3>Sensor Chart</h3>
    <canvas id="sensorChart" height="100"></canvas>

</main>

<footer>
    &copy; <?= date("Y") ?> Smart Farm Monitoring System
</footer>

<script>
let chart;
let ctx = document.getElementById('sensorChart').getContext('2d');

fetchAndRender();
setInterval(fetchAndRender, 15000); // refresh every 15 seconds

function fetchAndRender() {
    fetch('sensor_data_api.php')
        .then(response => response.json())
        .then(data => {
            const timestamps = data.map(item => item.timestamp);
            const temperatures = data.map(item => parseFloat(item.temperature));
            const humidities = data.map(item => parseFloat(item.humidity));

            updateTable(data);
            updateAlerts(data[0]); // latest data for alert box

            if (chart) {
                chart.data.labels = timestamps;
                chart.data.datasets[0].data = temperatures;
                chart.data.datasets[1].data = humidities;
                chart.update();
            } else {
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: timestamps,
                        datasets: [
                            {
                                label: 'Temperature (°C)',
                                data: temperatures,
                                borderColor: 'red',
                                backgroundColor: 'rgba(255,0,0,0.2)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Humidity (%)',
                                data: humidities,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(0,0,255,0.2)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            x: { ticks: { maxRotation: 60, minRotation: 30 } }
                        }
                    }
                });
            }
        });
}

function updateTable(data) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';
    data.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td>${row.temperature}</td>
                <td>${row.humidity}</td>
                <td>${row.timestamp}</td>
            </tr>`;
    });
}

function updateAlerts(latest) {
    const alertBox = document.getElementById('alertBox');
    if (!alertBox) return;

    let alerts = [];
    if (latest && latest.temperature > 30) alerts.push(`⚠️ High Temperature: ${latest.temperature}°C`);
    if (latest && latest.humidity > 70) alerts.push(`⚠️ High Humidity: ${latest.humidity}%`);

    if (alerts.length > 0) {
        alertBox.innerHTML = `<h3 style="color:red;">Alerts:</h3><ul>${alerts.map(a => `<li>${a}</li>`).join('')}</ul>`;
        alertBox.style.display = 'block';
    } else {
        alertBox.style.display = 'none';
    }
}
</script>

</body>
</html>
