<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Tax Management</h2>

            <?php flash('tax_message'); ?>
            <?php flash('tax_error', '', 'alert alert-danger'); ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=tax&action=update" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax1" class="form-label">Tax 1 Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="tax1" name="tax1" value="<?php echo $data['tax1'] ?? 0; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tax2" class="form-label">Tax 2 Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="tax2" name="tax2" value="<?php echo $data['tax2'] ?? 0; ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax3" class="form-label">Tax 3 Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="tax3" name="tax3" value="<?php echo $data['tax3'] ?? 0; ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tax4" class="form-label">Tax 4 Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="tax4" name="tax4" value="<?php echo $data['tax4'] ?? 0; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Tax Rates
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
