<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Edit Tax Rate</h2>
                <a href="<?php echo BASE_URL; ?>?controller=tax" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <?php flash('tax_error', '', 'alert alert-danger'); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=tax&action=edit&id=<?php echo $data['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tax Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($data['name']); ?>" required>
                            <div class="form-text">Enter a descriptive name for this tax rate (e.g., GST, VAT, Sales Tax).</div>
                        </div>

                        <div class="mb-3">
                            <label for="rate" class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="rate" 
                                       name="rate" value="<?php echo number_format($data['rate'], 2); ?>" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Enter the tax rate as a percentage (e.g., 18.00 for 18%).</div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                    <?php echo $data['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <div class="form-text">Inactive tax rates won't be available for selection in other parts of the system.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>?controller=tax" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Tax Rate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
