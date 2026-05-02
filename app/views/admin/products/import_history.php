<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Import History</h4>
            <p class="text-muted mb-0">View and manage your product import history</p>
        </div>
        <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <?php if (empty($logs)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h5 class="text-muted">No import history found</h5>
                <p class="text-muted mb-3">You haven't imported any products yet.</p>
                <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i> Import Products
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Filename</th>
                            <th>Date</th>
                            <th>Total Rows</th>
                            <th class="text-success">Imported</th>
                            <th class="text-danger">Failed</th>
                            <th>Imported By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): 
                            $log = (array)$log;
                            $isUndone = !empty($log['undone_at']);
                            $successRate = ($log['total_rows'] > 0) 
                                ? round(($log['imported_count'] / $log['total_rows']) * 100, 1) 
                                : 0;
                        ?>
                            <tr class="<?php echo $isUndone ? 'table-secondary' : ''; ?>">
                                <td><code>#<?php echo $log['id']; ?></code></td>
                                <td>
                                    <i class="bi bi-filetype-csv text-success me-1"></i>
                                    <?php echo htmlspecialchars($log['filename']); ?>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="<?php echo $log['created_at']; ?>">
                                        <?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($log['total_rows']); ?></td>
                                <td class="text-success fw-semibold">
                                    <?php echo number_format($log['imported_count']); ?>
                                </td>
                                <td class="text-danger fw-semibold">
                                    <?php echo number_format($log['failed_count']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($log['created_by_name'] ?? 'Unknown'); ?>
                                </td>
                                <td>
                                    <?php if ($isUndone): ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Undone
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            by <?php echo htmlspecialchars($log['undone_by_name'] ?? 'Unknown'); ?><br>
                                            <?php echo date('M d, Y H:i', strtotime($log['undone_at'])); ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="badge bg-success">Active</span>
                                        <div class="progress mt-1" style="height: 4px; width: 60px;">
                                            <div class="progress-bar bg-success" style="width: <?php echo $successRate; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?php echo $successRate; ?>% success</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$isUndone && $log['imported_count'] > 0): ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning undo-import-btn" 
                                                data-import-id="<?php echo $log['id']; ?>"
                                                data-imported-count="<?php echo $log['imported_count']; ?>">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Undo
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-secondary" disabled>
                                            <i class="bi bi-check me-1"></i> <?php echo $isUndone ? 'Undone' : 'No Action'; ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="card-footer bg-light">
                    <nav aria-label="Import history pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?controller=product&action=importHistory&page=<?php echo $pagination['current_page'] - 1; ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=product&action=importHistory&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?controller=product&action=importHistory&page=<?php echo $pagination['current_page'] + 1; ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> - 
                            <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total']); ?> 
                            of <?php echo $pagination['total']; ?> imports
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Undo Confirmation Modal -->
<div class="modal fade" id="undoConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Undo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to undo this import?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    This will soft-delete <strong id="undoProductCount">0</strong> products that were imported in this batch.
                </div>
                <p class="text-muted small mb-0">Products will be marked as inactive, not permanently deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmUndoBtn">
                    <span class="spinner-border spinner-border-sm d-none" id="undoSpinner"></span>
                    <span class="btn-text"><i class="bi bi-arrow-counterclockwise me-1"></i> Yes, Undo Import</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedImportId = null;
    
    // Handle undo button clicks
    document.querySelectorAll('.undo-import-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedImportId = this.dataset.importId;
            document.getElementById('undoProductCount').textContent = this.dataset.importedCount;
            new bootstrap.Modal(document.getElementById('undoConfirmModal')).show();
        });
    });
    
    // Handle confirm undo
    document.getElementById('confirmUndoBtn').addEventListener('click', async function() {
        if (!selectedImportId) return;
        
        const btn = this;
        const spinner = document.getElementById('undoSpinner');
        const btnText = btn.querySelector('.btn-text');
        
        btn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = ' Undoing...';
        
        try {
            const response = await fetch(`<?php echo BASE_URL; ?>?controller=product&action=undoImport&id=${selectedImportId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Reload page to show updated status
                window.location.reload();
            } else {
                alert(result.message || 'Undo failed');
                btn.disabled = false;
                spinner.classList.add('d-none');
                btnText.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Yes, Undo Import';
            }
        } catch (error) {
            alert('Error: ' + error.message);
            btn.disabled = false;
            spinner.classList.add('d-none');
            btnText.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Yes, Undo Import';
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
