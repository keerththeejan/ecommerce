<?php if (!empty($sections)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Footer Sections</h6>
            <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Section
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="footerSectionsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Order</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php foreach ($sections as $index => $section): ?>
                            <tr data-id="<?php echo $section['id']; ?>">
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($section['icon'])): ?>
                                            <i class="<?php echo htmlspecialchars($section['icon']); ?> me-2"></i>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($section['title']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?php echo isset($sectionTypes[$section['type']]) ? $sectionTypes[$section['type']] : ucfirst($section['type']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="<?php echo $section['id']; ?>"
                                               <?php echo $section['status'] === 'active' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><?php echo $section['sort_order']; ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>admin/footer/edit/<?php echo $section['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>admin/footer/delete/<?php echo $section['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           data-bs-toggle="tooltip" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this section?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <div class="text-muted mb-4">
                <i class="fas fa-window-close fa-4x mb-3"></i>
                <h4>No Footer Sections Found</h4>
                <p class="mb-0">Get started by adding your first footer section</p>
            </div>
            <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add First Section
            </a>
        </div>
    </div>
<?php endif; ?>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Make table rows sortable
    if (document.getElementById('sortable')) {
        $('#sortable').sortable({
            update: function(event, ui) {
                updateSectionOrder();
            }
        });
    }

    // Handle status toggle
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const sectionId = this.getAttribute('data-id');
            const status = this.checked ? 'active' : 'inactive';
            
            fetch(`<?php echo BASE_URL; ?>admin/footer/update-status/${sectionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Revert the toggle if update failed
                    this.checked = !this.checked;
                    showAlert('danger', data.message || 'Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revert on error
                showAlert('danger', 'An error occurred. Please try again.');
            });
        });
    });
});

// Update section order after drag and drop
function updateSectionOrder() {
    const order = [];
    $('#sortable tr').each(function(index) {
        const sectionId = $(this).data('id');
        order.push({
            id: sectionId,
            sort_order: index + 1
        });
    });

    // Show loading state
    const saveBtn = document.getElementById('saveOrderBtn');
    const originalText = saveBtn ? saveBtn.innerHTML : '';
    
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';
    }

    // Send AJAX request to update order
    fetch('<?php echo BASE_URL; ?>admin/footer/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ order: order })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            showAlert('danger', data.message || 'Failed to update order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred while saving the order');
    })
    .finally(() => {
        // Reset button state
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    });
}

// Show alert message
function showAlert(type, message) {
    // Remove any existing alerts
    const existingAlerts = document.querySelectorAll('.alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert after the page header
    const header = document.querySelector('.card-header');
    if (header) {
        header.insertAdjacentHTML('afterend', alertHtml);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}
</script>
