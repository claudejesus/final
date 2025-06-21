
   <?php
require '../auth.php';
require '../db.php';

$user = $_SESSION['user'];
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2"><i class="fas fa-user me-2"></i>Your Profile</h1>
</div>

<div class="card mb-4">
  <div class="card-body">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
    <p><strong>Logged in:</strong> <?= date('Y-m-d H:i:s', $_SESSION['login_time'] ?? time()) ?></p>
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
      <i class="fas fa-lock me-1"></i> Change Password
    </button>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="changePasswordForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Update Password</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast Message -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div class="toast bg-dark text-white" id="profileToast" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="profileToastBody"></div>
      <button type="button" class="btn-close btn-close-white m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('commands/change_password.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const toast = new bootstrap.Toast(document.getElementById('profileToast'));
    document.getElementById('profileToastBody').textContent = data.message;
    toast.show();

    if (data.success) {
      this.reset();
      bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
    }
  });
});
</script>
