<?php require __DIR__ . '/../admin/layouts/header.php'; ?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Add About Entry</h1>
    <a href="?controller=aboutStore" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form action="?controller=aboutStore&action=create" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Content *</label>
          <textarea name="content" id="content" rows="6" class="form-control" required></textarea>
        </div>
        <div class="mb-4">
          <label class="form-label">Image</label>
          <input type="file" name="image" class="form-control" accept="image/*" />
          <div class="form-text">Max 5MB. JPG, JPEG, PNG, GIF.</div>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Optional: initialize a rich text editor if available globally
if (window.ClassicEditor) {
  ClassicEditor.create(document.querySelector('#content')).catch(() => {});
}
</script>

<?php require __DIR__ . '/../admin/layouts/footer.php'; ?>
