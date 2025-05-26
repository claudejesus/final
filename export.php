<?php
// ===== delete_farmer.php =====
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn = new mysqli('localhost', 'root', '', 'gate');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM farmers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "farmer deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
// } else {
//     echo "welcome to farmer info";
}
?>

<!-- ===== export_vistor_report.php ===== -->
<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'maize_weevil';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=export.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, [`id`, `username`, `password`, `role`, `created_at`, `role`]);

      $sql = SELECT `id`, `username`, `password`, `role`, `created_at` FROM `users` WHERE `role`= farmer;
    // $sql = "SELECT id, farmer_name, id_number, visit_reason, district, sector, equipment, visit_time FROM farmers";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } else {
        fputcsv($output, ['No records found']);
    }

    fclose($output);
    exit;
}
?>

<style>
h2 {
    text-align: center;
    color: #333;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    background: white;
}
th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}
th {
    background: #3a80cb;
    color: white;
}
tr:nth-child(even) {
    background-color: #f9f9f9;
}
.export-btn:hover {
    background-color: darkred;
}
</style>

<div class="card border-info">
  <div class="card-header bg-info">farmer Reports</div>
  <div class="card-body">
    <p>View farmer information Entry Via Gate.</p>
    <a href="export.php?export=csv" class="btn2">Export as CSV</a>

    <table>
      <tr>
        <th>ID</th>
        <th>username</th>
        <th>role</th>
        <th>Actions</th>
      </tr>
      <?php
      $sql = SELECT `id`, `username`, `password`, `role`, `created_at` FROM `users` WHERE `role`= farmer;
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['username']}</td>
                      <td>{$row['password']}</td>
                      <td>{$row['role']}</td>
                      <td>
                        <button class='btn3' onclick='deletefarmer({$row['id']})'>Delete</button>
                        <a href='edit_farmer.php?id={$row['id']}' class='btn3'>Edit</a>
                      </td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='9'>No farmer records found.</td></tr>";
      }
      $conn->close();
      ?>
    </table>

    <script>
    function deletefarmer(id) {
        if (confirm("Are you sure you want to delete this farmer?")) {
            fetch('delete_farmer.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                alert("Error deleting: " + error);
            });
        }
    }
    </script>
  </div>
</div>