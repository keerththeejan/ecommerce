<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Edit Category</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($errors['db_error'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['db_error']; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=category&action=edit&id=<?php echo $category['id']; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $category['name']; ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3"><?php echo $category['description']; ?></textarea>
                            <?php if(isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-select <?php echo isset($errors['parent_id']) ? 'is-invalid' : ''; ?>" id="parent_id" name="parent_id">
                                <option value="">None (Top Level Category)</option>
                                <?php foreach($parentCategories as $parentCategory): ?>
                                    <?php if($parentCategory['id'] != $category['id']): ?>
                                        <option value="<?php echo $parentCategory['id']; ?>" <?php echo ($category['parent_id'] == $parentCategory['id']) ? 'selected' : ''; ?>>
                                            <?php echo $parentCategory['name']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <?php if(isset($errors['parent_id'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['parent_id']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Select a parent category or leave empty for top level category.</div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?php echo $category['status'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            
                            <?php if(!empty($category['image']) && file_exists($category['image'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo BASE_URL . $category['image']; ?>" alt="Current category image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <div class="form-text mt-1">Current image</div>
                                </div>
                            <?php endif; ?>
                            
                            <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*">
                            <?php if(isset($errors['image'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Leave empty to keep current image. Recommended size: 800x600px. Max size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
