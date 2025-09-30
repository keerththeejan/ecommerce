<?php
// Set the current page for the active menu highlighting
$current_page = 'banners';
$page_title = isset($banner) && !empty($banner) ? 'Edit Banner' : 'Add New Banner';
$is_edit = isset($banner) && !empty($banner);
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2"><?php echo $is_edit ? 'Edit Banner' : 'Add New Banner'; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>admin/banners" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Banners
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form id="bannerForm" action="<?php echo $is_edit ? BASE_URL . 'admin/banners/update/' . $banner['id'] : BASE_URL . 'admin/banners/store'; ?>" 
                      method="POST" enctype="multipart/form-data">
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo $is_edit ? htmlspecialchars($banner['title']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3"><?php echo $is_edit ? htmlspecialchars($banner['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">
                            <?php echo $is_edit ? 'Change Image' : 'Upload Image *'; ?>
                        </label>
                        <input class="form-control" type="file" id="image" name="image" 
                               <?php echo $is_edit ? '' : 'required'; ?> accept="image/*">
                        <div class="form-text">
                            Recommended size: 1920x600px. Max file size: 2MB. Allowed formats: JPG, PNG, GIF.
                        </div>
                        
                        <?php if ($is_edit && !empty($banner['image_url'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo BASE_URL . ltrim($banner['image_url'], '/'); ?>" 
                                     alt="Current Banner" class="img-thumbnail mt-2" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?php echo ($is_edit && $banner['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($is_edit && $banner['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> 
                            <?php echo $is_edit ? 'Update Banner' : 'Save Banner'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i> Banner Guidelines
            </div>
            <div class="card-body">
                <h6>Image Requirements:</h6>
                <ul class="small">
                    <li>Optimal dimensions: 1920x600 pixels</li>
                    <li>Maximum file size: 2MB</li>
                    <li>Accepted formats: JPG, PNG, GIF</li>
                </ul>
                <h6>Content Tips:</h6>
                <ul class="small">
                    <li>Keep titles short and engaging (max 10 words)</li>
                    <li>Descriptions should be concise (max 50 words)</li>
                    <li>Use high-quality, relevant images</li>
                    <li>Ensure text is readable on the banner image</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// Include the admin layout with sidebar
require_once __DIR__ . '/../../layouts/admin.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.createElement('div');
    imagePreview.className = 'mt-2';
    imageInput.parentNode.insertBefore(imagePreview, imageInput.nextSibling);
    
    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let img = imagePreview.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    img.className = 'img-thumbnail';
                    img.style.maxHeight = '200px';
                    imagePreview.innerHTML = '';
                    imagePreview.appendChild(img);
                }
                img.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Form validation
    const form = document.getElementById('bannerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const image = document.getElementById('image');
            
            if (!title) {
                e.preventDefault();
                alert('Please enter a title for the banner');
                return false;
            }
            
            <?php if (!$is_edit): ?>
            if (!image.files || !image.files[0]) {
                e.preventDefault();
                alert('Please select an image for the banner');
                return false;
            }
            <?php endif; ?>
            
            // Check file size (2MB max)
            if (image.files && image.files[0]) {
                const fileSize = image.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    e.preventDefault();
                    alert('Image size must be less than 2MB');
                    return false;
                }
            }
            
            return true;
        });
    }
});
</script>
