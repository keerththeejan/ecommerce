<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 mb-0">My Addresses</h1>
    <a href="<?php echo BASE_URL; ?>?controller=address&action=create" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> Add Address
    </a>
  </div>

  <?php flash('address_success'); ?>
  <?php flash('address_error', '', 'alert alert-danger'); ?>

  <?php if (empty($addresses)): ?>
    <div class="alert alert-info">You have not added any addresses yet.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($addresses as $addr): ?>
        <div class="col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-secondary text-uppercase"><?php echo htmlspecialchars($addr['type']); ?></span>
                <?php if ((int)$addr['is_default'] === 1): ?>
                  <span class="badge bg-success">Default</span>
                <?php endif; ?>
              </div>
              <div class="mb-2 fw-semibold"><?php echo htmlspecialchars($addr['first_name'] . ' ' . $addr['last_name']); ?></div>
              <?php if(!empty($addr['company'])): ?>
                <div class="text-muted small">Company: <?php echo htmlspecialchars($addr['company']); ?></div>
              <?php endif; ?>
              <div class="small mt-2">
                <div><?php echo nl2br(htmlspecialchars($addr['address1'] . (!empty($addr['address2']) ? "\n" . $addr['address2'] : ''))); ?></div>
                <div><?php echo htmlspecialchars($addr['city'] . ', ' . $addr['state'] . ' ' . $addr['postal_code']); ?></div>
                <div><?php echo htmlspecialchars($addr['country']); ?></div>
                <?php if(!empty($addr['phone'])): ?>
                  <div>Phone: <?php echo htmlspecialchars($addr['phone']); ?></div>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
              <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=address&action=edit&id=<?php echo (int)$addr['id']; ?>">
                <i class="fas fa-edit me-1"></i> Edit
              </a>
              <a class="btn btn-sm btn-outline-danger" href="<?php echo BASE_URL; ?>?controller=address&action=delete&id=<?php echo (int)$addr['id']; ?>" onclick="return confirm('Delete this address?');">
                <i class="fas fa-trash me-1"></i> Delete
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
