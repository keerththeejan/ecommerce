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
          <table class="table table-striped align-middle">
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
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                  <?php if (!empty($row['image_path'])): ?>
                    <img src="<?= htmlspecialchars($row['image_path']) ?>" style="max-width:100px;max-height:60px;object-fit:cover"/>
                  <?php else: ?>
                    <span class="text-muted">No image</span>
                  <?php endif; ?>
                </td>
                <td><?= isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '-' ?></td>
                <td>
                  <a class="btn btn-sm btn-info" href="?controller=aboutStore&action=edit&id=<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="?controller=aboutStore&action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this entry?');"><i class="fas fa-trash"></i> Delete</a>
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
