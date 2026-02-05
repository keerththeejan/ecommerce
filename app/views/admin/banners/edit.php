<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Banner edit – trending responsive */
.banner-edit .card-body { padding: 1rem; }
@media (min-width: 768px) { .banner-edit .card-body { padding: 1.25rem; } }
.banner-edit .form-control, .banner-edit .form-select { max-width: 100%; }
@media (max-width: 575.98px) {
  .banner-edit .breadcrumb { font-size: 0.875rem; flex-wrap: wrap; }
  .banner-edit .img-thumbnail { max-width: 100%; height: auto; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 banner-edit">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1 mb-sm-2">Edit Banner</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=banner">Banners</a></li>
                    <li class="breadcrumb-item active">Edit Banner</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow-sm rounded-3 border-0 mb-4">
        <div class="card-header bg-white py-3">
            <i class="fas fa-edit me-1"></i>
            Edit Banner #<?php echo $banner->id; ?>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>?controller=banner&action=update&id=<?php echo $banner->id; ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-0">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo htmlspecialchars($data->title ?? ''); ?>" required>
                            <?php if (isset($errors['title'])) : ?>
                                <div class="invalid-feedback d-block"><?php echo $errors['title']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-0">
                            <label for="subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['subtitle']) ? 'is-invalid' : ''; ?>" 
                                   id="subtitle" name="subtitle" value="<?php echo htmlspecialchars($data->subtitle ?? ''); ?>" required>
                            <input type="hidden" name="description" value="<?php echo htmlspecialchars($data->subtitle ?? ''); ?>">
                            <?php if (isset($errors['subtitle'])) : ?>
                                <div class="invalid-feedback d-block"><?php echo $errors['subtitle']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-0">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" 
                                   id="image" name="image" accept="image/*">
                            <?php if (isset($errors['image'])) : ?>
                                <div class="invalid-feedback d-block"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted d-block mt-1">Recommended: 1920×800px. Leave empty to keep current image.</small>
                            <?php if (!empty($banner->image)) : 
                                $currentImgPath = (strpos($banner->image, 'uploads/') === 0 || strpos($banner->image, 'public/') === 0) 
                                    ? BASE_URL . ltrim($banner->image, '/') 
                                    : BASE_URL . 'public/uploads/banners/' . basename($banner->image);
                            ?>
                                <div class="mt-2">
                                    <p class="small text-muted mb-1">Current image:</p>
                                    <img src="<?php echo $currentImgPath; ?>" 
                                         alt="Current Banner" class="img-thumbnail rounded" style="max-height: 120px; width: auto;">
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars('uploads/banners/' . basename($banner->image)); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-0">
                            <label for="bg_class" class="form-label">Background Class</label>
                            <select class="form-select" id="bg_class" name="bg_class">
                                <option value="hero-slide-1" <?php echo (isset($data->bg_class) && $data->bg_class === 'hero-slide-1') ? 'selected' : ''; ?>>hero-slide-1 (Blue to Dark)</option>
                                <option value="hero-slide-2" <?php echo (isset($data->bg_class) && $data->bg_class === 'hero-slide-2') ? 'selected' : ''; ?>>hero-slide-2 (Dark to Blue)</option>
                                <option value="hero-slide-3" <?php echo (isset($data->bg_class) && $data->bg_class === 'hero-slide-3') ? 'selected' : ''; ?>>hero-slide-3 (Dark–Blue–Dark)</option>
                            </select>
                            <small class="form-text text-muted d-block mt-1">Used when no image is uploaded.</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="mb-0">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="<?php echo isset($data->sort_order) ? (int)$data->sort_order : 0; ?>" min="0">
                            <small class="form-text text-muted d-block mt-1">Lower numbers appear first.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="hidden" name="status" value="inactive">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="active"
                           <?php echo (!empty($data->status) && $data->status === 'active') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active</label>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                    <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
