<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shoe-prints text-primary me-2"></i>Footer Management
        </h1>
        <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Section
        </a>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Footer Sections</h6>
            <div class="small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Drag and drop to reorder sections
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($sections)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-window-close fa-4x text-muted mb-3"></i>
                        <h4 class="text-gray-600">No Footer Sections Found</h4>
                        <p class="text-muted mb-4">Get started by adding your first footer section</p>
                        <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add First Section
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="footerSectionsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 15%;">Type</th>
                                <th style="width: 15%;" class="text-center">Status</th>
                                <th style="width: 10%;" class="text-center">Sort Order</th>
                                <th style="width: 15%;" class="text-center">Last Updated</th>
                                <th style="width: 15%;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            <?php foreach ($sections as $index => $section): ?>
                                <tr data-id="<?php echo $section['id']; ?>">
                                    <td class="align-middle">
                                        <div class="text-muted"><?php echo $index + 1; ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($section['icon'])): ?>
                                                <div class="icon-circle bg-primary text-white me-3">
                                                    <i class="<?php echo htmlspecialchars($section['icon']); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h6>
                                                <small class="text-muted">ID: <?php echo $section['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-info text-dark">
                                            <?php echo isset($sectionTypes[$section['type']]) ? $sectionTypes[$section['type']] : ucfirst($section['type']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   data-id="<?php echo $section['id']; ?>"
                                                   <?php echo $section['status'] === 'active' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">
                                                <?php echo ucfirst($section['status']); ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-secondary"><?php echo $section['sort_order']; ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <small class="text-muted">
                                            <?php echo !empty($section['updated_at']) ? date('M d, Y', strtotime($section['updated_at'])) : 'N/A'; ?>
                                        </small>
                                    </td>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sections as $section): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h6>
                                                        <small class="text-muted">
                                                            <?php 
                                                            if ($section['type'] === 'links' || $section['type'] === 'social') {
                                                                $links = is_array($section['content']) ? $section['content'] : [];
                                                                echo count($links) . ' ' . (count($links) === 1 ? 'link' : 'links');
                                                            } else {
                                                                echo !empty($section['content']) ? 
                                                                    htmlspecialchars(substr(strip_tags($section['content']), 0, 60)) . 
                                                                    (strlen(strip_tags($section['content'])) > 60 ? '...' : '') : 
                                                                    'No content';
                                                            }
                                                            ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-<?php 
                                                        switch($section['type']) {
                                                            case 'about': echo 'info-circle'; break;
                                                            case 'links': echo 'link'; break;
                                                            case 'contact': echo 'envelope'; break;
                                                            case 'social': echo 'share-alt'; break;
                                                            case 'newsletter': echo 'newspaper'; break;
                                                            default: echo 'square';
                                                        }
                                                    ?> me-1"></i>
                                                    <?php 
                                                    $sectionTypes = [
                                                        'about' => 'About Us',
                                                        'links' => 'Quick Links',
                                                        'contact' => 'Contact Info',
                                                        'social' => 'Social Media',
                                                        'newsletter' => 'Newsletter'
                                                    ];
                                                    echo $sectionTypes[$section['type']] ?? ucfirst($section['type']); 
                                                    ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-<?php echo $section['status'] === 'active' ? 'success' : 'secondary'; ?> bg-opacity-10 text-<?php echo $section['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" 
                                                       href="<?php echo BASE_URL; ?>admin/footer/delete/<?php echo $section['id']; ?>" 
                                                       onclick="return confirm('Are you sure you want to delete this section? This action cannot be undone.')">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-primary" id="saveOrderBtn">
                        <i class="fas fa-save me-1"></i> Save Order
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Preview Section Modal -->
<div class="modal fade" id="previewSectionModal" tabindex="-1" aria-labelledby="previewSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewSectionModalLabel">Section Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="previewSectionTitle"></h4>
                <div id="previewSectionContent" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery UI for sortable functionality -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
// Initialize tooltips and sortable functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize sortable functionality
    if (document.getElementById('sortable')) {
        $('#sortable').sortable({
            update: function(event, ui) {
                // Update the sort order numbers
                $('#sortable tr').each(function(index) {
                    $(this).find('td:first .text-muted').text(index + 1);
                });
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
                if (data.success) {
                    // Update the status text
                    const statusLabel = this.nextElementSibling;
                    statusLabel.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    
                    // Show success message
                    showAlert('success', 'Status updated successfully');
                } else {
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

    // Save order button click handler
    document.getElementById('saveOrderBtn')?.addEventListener('click', function() {
        const order = [];
        $('#sortable tr').each(function(index) {
            const sectionId = $(this).data('id');
            order.push({
                id: sectionId,
                sort_order: index + 1
            });
        });

        // Show loading state
        const saveBtn = this;
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

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
            if (data.success) {
                showAlert('success', data.message || 'Order updated successfully');
            } else {
                throw new Error(data.message || 'Failed to update order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', error.message || 'An error occurred while saving the order');
        })
        .finally(() => {
            // Restore button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    });

    // Preview section modal
    const previewModal = document.getElementById('previewSectionModal');
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-title');
            const content = button.getAttribute('data-content');
            
            document.getElementById('previewSectionTitle').textContent = title || 'No Title';
            document.getElementById('previewSectionContent').innerHTML = content || '<p class="text-muted">No content available for preview.</p>';
        });
    }

    // Helper function to show alerts
    function showAlert(type, message) {
        // Remove any existing alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            alert.remove();
        });

        // Create and show new alert
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
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert-dismissible');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }
});
</script>

<style>
/* Custom styles for the footer management page */
.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.1rem;
}

#sortable {
    cursor: move; /* Fallback for some browsers */
    cursor: grab;
}

#sortable tr {
    background: white;
    transition: background-color 0.2s ease;
}

#sortable tr.sortable-ghost {
    opacity: 0.5;
    background: #f8f9fa;
}

#sortable tr.sortable-chosen {
    background: #f8f9fa;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    #footerSectionsTable thead {
        display: none;
    }
    
    #footerSectionsTable tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    
    #footerSectionsTable td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        border: none;
        border-bottom: 1px solid #dee2e6;
    }
    
    #footerSectionsTable td:before {
        content: attr(data-label);
        font-weight: bold;
        margin-right: 1rem;
        flex: 0 0 120px;
    }
    
    #footerSectionsTable .btn-group {
        margin-left: auto;
    }
}
</style>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>
