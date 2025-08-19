<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>
<div class="container py-5">
  <h1 class="fs-3 mb-4">Account Settings</h1>

  <?php flash('profile_success'); ?>
  <?php flash('password_success'); ?>
  <?php flash('address_success'); ?>
  <?php flash('address_error', '', 'alert alert-danger'); ?>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Profile</h5>
          <p class="card-text text-muted small">Update your personal information and email.</p>
          <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="btn btn-outline-primary">Manage Profile</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Password</h5>
          <p class="card-text text-muted small">Change your account password.</p>
          <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="btn btn-outline-primary">Change Password</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Addresses</h5>
          <p class="card-text text-muted small">Manage your shipping and billing addresses.</p>
          <a href="<?php echo BASE_URL; ?>?controller=address" class="btn btn-outline-primary">Manage Addresses</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
