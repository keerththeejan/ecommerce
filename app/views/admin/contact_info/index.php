<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Contact Info</h3>
    <a href="<?php echo BASE_URL; ?>?controller=contactinfo&action=create" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> Add New
    </a>
  </div>

  <?php flash('contact_info_message'); ?>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Weekdays</th>
            <th>Weekends</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($items)): ?>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><?php echo (int)$item['id']; ?></td>
              <td class="text-break" style="max-width:260px;"><?php echo htmlspecialchars($item['address']); ?></td>
              <td><?php echo htmlspecialchars($item['phone']); ?></td>
              <td><?php echo htmlspecialchars($item['email']); ?></td>
              <td><?php echo htmlspecialchars($item['hours_weekdays']); ?></td>
              <td><?php echo htmlspecialchars($item['hours_weekends']); ?></td>
              <td>
                <a href="<?php echo BASE_URL; ?>?controller=contactinfo&action=edit&id=<?php echo (int)$item['id']; ?>" class="btn btn-sm btn-warning">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="<?php echo BASE_URL; ?>?controller=contactinfo&action=delete&id=<?php echo (int)$item['id']; ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">No contact info entries yet</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
