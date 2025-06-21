<?php
require '../db.php';
$farmers = $conn->query("SELECT id, username, created_at FROM users WHERE role = 'farmer'");
$farmer_list = $farmers->fetch_all(MYSQLI_ASSOC);
?>

<!-- Farmer Management Section -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Farmer Management</h1>
</div>

<div class="row">
  <div class="col-md-6">
    <input type="text" id="farmerSearch" class="form-control mb-3" placeholder="Search farmers by username...">

    <!-- Register Form -->
    <div class="card mb-4">
      <div class="card-header"><h5><i class="fas fa-user-plus me-2"></i>Register New Farmer</h5></div>
      <div class="card-body">
        <form id="registerFarmerForm">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" class="form-control" name="username" required />
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required />
          </div>
          <button class="btn btn-primary" type="submit"><i class="fas fa-user-plus me-1"></i>Register</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Farmers Table -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5><i class="fas fa-users me-2"></i>Registered Farmers</h5>
        <a href="commands/export_farmers.php" class="btn btn-outline-primary btn-sm">
          <i class="fas fa-file-csv me-1"></i> Export CSV
        </a>
      </div>
      <div class="card-body">
        <table class="table table-sm table-hover" id="farmerList">
          <thead class="table-black">
            <tr><th>ID</th><th>Username</th><th>Registered</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($farmer_list as $f): ?>
              <tr>
                <td><?= $f['id'] ?></td>
                <td class="farmer-name"><?= htmlspecialchars($f['username']) ?></td>
                <td><?= $f['created_at'] ?></td>
                <td>
                  <button class="btn btn-sm btn-primary edit-farmer" data-id="<?= $f['id'] ?>" data-username="<?= htmlspecialchars($f['username']) ?>"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-sm btn-danger delete-farmer" data-username="<?= $f['username'] ?>"><i class="fas fa-trash-alt"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editFarmerModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editFarmerForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Farmer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editFarmerId" />
        <div class="mb-3">
          <label for="editUsername">Username</label>
          <input type="text" class="form-control" name="username" id="editUsername" required />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toast" class="toast text-bg-dark" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastBody"></div>
      <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
