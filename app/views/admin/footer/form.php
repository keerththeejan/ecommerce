<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo $formTitle ?? $title; ?></h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo BASE_URL; ?>admin/footer" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($data['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($data['errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo $action; ?>" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($data['section']['title'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Section Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <?php foreach ($data['sectionTypes'] as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" 
                                        <?php echo (isset($data['section']['type']) && $data['section']['type'] === $key) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="6" required><?php 
                                echo htmlspecialchars($data['section']['content'] ?? ''); 
                            ?></textarea>
                            <small class="form-text text-muted">
                                For links/social media, enter one per line or as a JSON array.
                            </small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?php echo (isset($data['section']['status']) && $data['section']['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($data['section']['status']) && $data['section']['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                           value="<?php echo $data['section']['sort_order'] ?? 0; ?>" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Section
                            </button>
                            <a href="<?php echo BASE_URL; ?>admin/footer" class="btn btn-light">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>
