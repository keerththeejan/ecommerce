<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Banner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=banner">Banners</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Create New Banner
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>?controller=banner&action=add" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo $data['title'] ?? ''; ?>" required>
                            <?php if (isset($errors['title'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['title']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['subtitle']) ? 'is-invalid' : ''; ?>" 
                                   id="subtitle" name="subtitle" value="<?php echo $data['subtitle'] ?? ''; ?>" required>
                            <?php if (isset($errors['subtitle'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['subtitle']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" 
                              id="description" name="description" rows="3"><?php echo $data['description'] ?? ''; ?></textarea>
                    <?php if (isset($errors['description'])) : ?>
                        <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="primary_button_text" class="form-label">Button Text</label>
                            <input type="text" class="form-control" 
                                   id="primary_button_text" name="primary_button_text" value="<?php echo $data['primary_button_text'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="primary_button_link" class="form-label">Button Link</label>
                            <input type="text" class="form-control" 
                                   id="primary_button_link" name="primary_button_link" value="<?php echo $data['primary_button_link'] ?? ''; ?>">
                            <small class="form-text text-muted">Example: ?controller=product</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control" id="banner_image" name="banner_image">
                            <?php if (isset($errors['image'])) : ?>
                                <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Recommended size: 1920x800 pixels. Leave empty to use background class.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bg_class" class="form-label">Background Style</label>
                            <select class="form-select" id="bg_class" name="bg_class">
                                <option value="hero-slide-1" <?php echo ($data['bg_class'] ?? '') === 'hero-slide-1' ? 'selected' : ''; ?>>
                                    Style 1 (Blue Gradient)
                                </option>
                                <option value="hero-slide-2" <?php echo ($data['bg_class'] ?? '') === 'hero-slide-2' ? 'selected' : ''; ?>>
                                    Style 2 (Green Gradient)
                                </option>
                                <option value="hero-slide-3" <?php echo ($data['bg_class'] ?? '') === 'hero-slide-3' ? 'selected' : ''; ?>>
                                    Style 3 (Dark Blue Gradient)
                                </option>
                            </select>
                            <small class="form-text text-muted">Used when no image is uploaded.</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="alignment" class="form-label">Content Alignment</label>
                            <select class="form-select" id="alignment" name="alignment">
                                <option value="" <?php echo ($data['alignment'] ?? '') === '' ? 'selected' : ''; ?>>Left (Default)</option>
                                <option value="justify-content-end" <?php echo ($data['alignment'] ?? '') === 'justify-content-end' ? 'selected' : ''; ?>>Right</option>
                                <option value="justify-content-center text-center" <?php echo ($data['alignment'] ?? '') === 'justify-content-center text-center' ? 'selected' : ''; ?>>Center</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="<?php echo $data['sort_order'] ?? 0; ?>" min="0">
                            <small class="form-text text-muted">Lower numbers appear first.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" 
                           <?php echo ($data['status'] ?? 0) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="status">Active</label>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Banner</button>
                    <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-secondary">Cancel</a>
                </div>
                
                <!-- Hidden fields to maintain compatibility with the database -->
                <input type="hidden" name="secondary_button_text" value="">
                <input type="hidden" name="secondary_button_link" value="#">
                <input type="hidden" name="animation" value="fadeIn">
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
