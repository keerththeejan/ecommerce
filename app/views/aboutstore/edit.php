<?php require __DIR__ . '/../admin/layouts/header.php'; ?>

<style>
/* About Store edit – trending responsive */
.aboutstore-edit .card-body { padding: 1rem; }
@media (min-width: 768px) { .aboutstore-edit .card-body { padding: 1.25rem; } }
.aboutstore-edit .form-control, .aboutstore-edit .form-select { max-width: 100%; }
@media (max-width: 575.98px) {
  .aboutstore-edit .breadcrumb { font-size: 0.875rem; flex-wrap: wrap; }
  .aboutstore-edit .img-thumbnail { max-width: 100%; height: auto; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 aboutstore-edit">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1 mb-sm-2">Edit About Entry</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>?controller=home&action=admin">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>?controller=aboutStore">About Store</a></li>
                    <li class="breadcrumb-item active">Edit #<?= (int)$about['id'] ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="?controller=aboutStore" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-3 border-0 mb-4">
        <div class="card-header bg-white py-3">
            <i class="fas fa-edit me-1"></i>
            Edit About Entry #<?= (int)$about['id'] ?>
        </div>
        <div class="card-body">
            <form action="?controller=aboutStore&action=update&id=<?= (int)$about['id'] ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($about['title']) ?>" required />
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" rows="6" class="form-control" required><?= htmlspecialchars($about['content']) ?></textarea>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="image" class="form-label">Image</label>
                        <?php
                        $currentImgUrl = null;
                        if (!empty($about['image_path'])) {
                            $p = $about['image_path'];
                            $currentImgUrl = (strpos($p, 'uploads/') === 0 || strpos($p, 'public/') === 0)
                                ? (defined('BASE_URL') ? BASE_URL : '') . (strpos($p, 'public/') === 0 ? '' : 'public/') . ltrim($p, '/')
                                : (defined('BASE_URL') ? BASE_URL : '') . 'public/' . ltrim($p, '/');
                        }
                        ?>
                        <?php if ($currentImgUrl): ?>
                            <div class="mb-2">
                                <p class="small text-muted mb-1">Current image:</p>
                                <img src="<?= htmlspecialchars($currentImgUrl) ?>" alt="Current" class="img-thumbnail" style="max-height:200px; max-width:100%;" />
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" />
                        <div class="form-text">Leave blank to keep current image. Max 5MB. JPG, JPEG, PNG, GIF.</div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
if (window.ClassicEditor) {
  ClassicEditor.create(document.querySelector('#content')).catch(function(){});
}
</script>

<?php require __DIR__ . '/../admin/layouts/footer.php'; ?>
