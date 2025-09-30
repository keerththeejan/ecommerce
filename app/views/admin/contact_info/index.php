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
      <style>
        /* Mobile-first responsive table styling */
        @media (max-width: 576.98px) {
          table.responsive-table thead { display: none; }
          table.responsive-table,
          table.responsive-table tbody,
          table.responsive-table tr,
          table.responsive-table td { display: block; width: 100%; }
          table.responsive-table tr {
            margin-bottom: 1rem;
            border: 1px solid rgba(0,0,0,.075);
            border-radius: .5rem;
            overflow: hidden;
            background: var(--bg-color, #fff);
          }
          table.responsive-table td {
            padding: .5rem .75rem;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,.05);
          }
          table.responsive-table td:last-child { border-bottom: 0; }
          table.responsive-table td::before {
            content: attr(data-label);
            font-weight: 600;
            display: block;
            margin-bottom: .25rem;
            opacity: .8;
          }
          .contact-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
        }
      </style>
      <table id="contactInfoTable" class="table table-striped align-middle">
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
              <td data-label="#"><?php echo (int)$item['id']; ?></td>
              <td data-label="Address" class="text-break" style="max-width:260px;"><?php echo htmlspecialchars($item['address']); ?></td>
              <td data-label="Phone"><?php echo htmlspecialchars($item['phone']); ?></td>
              <td data-label="Email"><?php echo htmlspecialchars($item['email']); ?></td>
              <td data-label="Weekdays"><?php echo htmlspecialchars($item['hours_weekdays']); ?></td>
              <td data-label="Weekends"><?php echo htmlspecialchars($item['hours_weekends']); ?></td>
              <td data-label="Actions">
                <div class="contact-actions">
                  <a href="<?php echo BASE_URL; ?>?controller=contactinfo&action=edit&id=<?php echo (int)$item['id']; ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="<?php echo BASE_URL; ?>?controller=contactinfo&action=delete&id=<?php echo (int)$item['id']; ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
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
