<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4"><?php echo $data['title']; ?></h2>
            
            <!-- Back button -->
            <a href="<?php echo URLROOT; ?>/admin/footer" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left me-1"></i> Back to Footer
            </a>
            
            <!-- Success/Error Messages -->
            <?php flash('footer_message'); ?>
            
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/admin/footer/edit_about" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control <?php echo !empty($data['about']['errors']['title']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo htmlspecialchars($data['about']['title']); ?>">
                            <?php if (!empty($data['about']['errors']['title'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $data['about']['errors']['title']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php echo !empty($data['about']['errors']['description']) ? 'is-invalid' : ''; ?>" 
                                     id="description" name="description" rows="6"><?php echo htmlspecialchars($data['about']['description'] ?? ''); ?></textarea>
                            <?php if (!empty($data['about']['errors']['description'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $data['about']['errors']['description']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>
