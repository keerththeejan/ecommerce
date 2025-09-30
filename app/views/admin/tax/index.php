<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Tax Rates</h2>
                <a href="<?php echo BASE_URL; ?>?controller=tax&action=add" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Tax Rate
                </a>
            </div>

            <?php flash('tax_success'); ?>
            <?php flash('tax_error'); ?>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($data['taxRates'])): ?>
                        <div class="text-center p-4">
                            <p class="text-muted mb-0">No tax rates found. Add your first tax rate to get started.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="taxTable" class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-end">Rate (%)</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['taxRates'] as $taxRate): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($taxRate->name); ?></td>
                                            <td class="text-end"><?php echo number_format($taxRate->rate, 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $taxRate->is_active ? 'success' : 'secondary'; ?>">
                                                    <?php echo $taxRate->is_active ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo BASE_URL; ?>?controller=tax&action=edit&id=<?php echo $taxRate->id; ?>" 
                                                   class="btn btn-sm btn-outline-primary me-1" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>?controller=tax&action=delete&id=<?php echo $taxRate->id; ?>" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this tax rate?');">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
