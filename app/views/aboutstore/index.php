<?php require __DIR__ . '/../admin/layouts/header.php'; ?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">About Store</h1>
    <a href="?controller=aboutStore&action=create" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add New
    </a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <?php if (!empty($about_entries)): ?>
        <div class="table-responsive">
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
              .about-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
              .about-thumb { width: 100px; height: 60px; object-fit: cover; border-radius: 4px; }
            }
          </style>
          <table class="table table-striped align-middle responsive-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Image</th>
                <th>Created</th>
                <th style="width:160px">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($about_entries as $i => $row): ?>
              <tr>
                <td data-label="#"><?= $i+1 ?></td>
                <td data-label="Title"><?= htmlspecialchars($row['title']) ?></td>
                <td data-label="Image">
                  <?php if (!empty($row['image_path'])): ?>
                    <img class="about-thumb" src="<?= htmlspecialchars($row['image_path']) ?>" style="max-width:100px;max-height:60px;object-fit:cover"/>
                  <?php else: ?>
                    <span class="text-muted">No image</span>
                  <?php endif; ?>
                </td>
                <td data-label="Created"><?= isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '-' ?></td>
                <td data-label="Actions">
                  <div class="about-actions">
                    <a class="btn btn-sm btn-info" href="?controller=aboutStore&action=edit&id=<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</a>
                    <a class="btn btn-sm btn-danger" href="?controller=aboutStore&action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this entry?');"><i class="fas fa-trash"></i> Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info mb-0">No about entries yet. Click "Add New" to create one.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../admin/layouts/footer.php'; ?>
