<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Banner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=banner">Banners</a></li>
        <li class="breadcrumb-item active">Edit Banner</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit Banner #<?php echo $banner->id; ?>
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>?controller=banner&action=update&id=<?php echo $banner->id; ?>" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo $data->title; ?>" required>
                            <?php if (isset($errors['title'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['title']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['subtitle']) ? 'is-invalid' : ''; ?>" 
                                   id="subtitle" name="subtitle" value="<?php echo $data->subtitle; ?>" required>
                            <!-- Controller expects 'description'; map subtitle to description -->
                            <input type="hidden" name="description" value="<?php echo htmlspecialchars($data->subtitle); ?>">
                            <?php if (isset($errors['subtitle'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['subtitle']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Description field removed as it doesn't exist in the database -->
                
                <!-- Button fields removed as they don't exist in the database -->
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" 
                                   id="image" name="image">
                            <?php if (isset($errors['image'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Recommended size: 1920x800 pixels. Leave empty to keep current image or use background class.</small>
                            
                            <?php if (!empty($banner->image)) : ?>
                                <div class="mt-2">
                                    <p>Current image:</p>
                                    <img src="<?php echo BASE_URL; ?>public/assets/images/banners/<?php echo $banner->image; ?>" 
                                         alt="Current Banner" class="img-thumbnail" style="max-height: 100px;">
                                    <!-- Provide current_image for controller update() -->
                                    <input type="hidden" name="current_image" value="<?php echo 'uploads/banners/' . $banner->image; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bg_class" class="form-label">Background Class</label>
                            <select class="form-select" id="bg_class" name="bg_class">
                                <option value="hero-slide-1" <?php echo $data->bg_class === 'hero-slide-1' ? 'selected' : ''; ?>>
                                    hero-slide-1 (Blue to Dark Gradient)
                                </option>
                                <option value="hero-slide-2" <?php echo $data->bg_class === 'hero-slide-2' ? 'selected' : ''; ?>>
                                    hero-slide-2 (Dark to Blue Gradient)
                                </option>
                                <option value="hero-slide-3" <?php echo $data->bg_class === 'hero-slide-3' ? 'selected' : ''; ?>>
                                    hero-slide-3 (Dark to Blue to Dark Gradient)
                                </option>
                            </select>
                            <small class="form-text text-muted">Used when no image is uploaded.</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="<?php echo isset($data->sort_order) ? $data->sort_order : (isset($data['sort_order']) ? $data['sort_order'] : 0); ?>" min="0">
                            <small class="form-text text-muted">Lower numbers appear first.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <!-- Hidden default 'inactive' so unchecked sends inactive -->
                    <input type="hidden" name="status" value="inactive">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="active"
                           <?php echo (!empty($data->status) && $data->status === 'active') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active</label>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                    <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
