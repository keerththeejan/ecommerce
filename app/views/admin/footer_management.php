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
                                <th style="width: 20%;">Type</th>
                                <th class="text-center" style="width: 15%;">Status</th>
                                <th class="text-center" style="width: 15%;">Sort Order</th>
                                <th class="text-end" style="width: 20%;">Actions</th>
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
                                            <div class="me-3">
                                                <?php 
                                                $icon = 'fa-square';
                                                $bgClass = 'bg-light';
                                                
                                                switch($section['type']) {
                                                    case 'about': 
                                                        $icon = 'fa-info-circle';
                                                        $bgClass = 'bg-info bg-opacity-10 text-info';
                                                        break;
                                                    case 'links': 
                                                        $icon = 'fa-link';
                                                        $bgClass = 'bg-primary bg-opacity-10 text-primary';
                                                        break;
                                                    case 'contact': 
                                                        $icon = 'fa-envelope';
                                                        $bgClass = 'bg-success bg-opacity-10 text-success';
                                                        break;
                                                    case 'social': 
                                                        $icon = 'fa-share-alt';
                                                        $bgClass = 'bg-warning bg-opacity-10 text-warning';
                                                        break;
                                                    case 'newsletter': 
                                                        $icon = 'fa-newspaper';
                                                        $bgClass = 'bg-danger bg-opacity-10 text-danger';
                                                        break;
                                                }
                                                ?>
                                                <div class="icon-circle <?php echo $bgClass; ?>" style="width: 40px; height: 40px; line-height: 40px; text-align: center; border-radius: 50%;">
                                                    <i class="fas <?php echo $icon; ?> fa-fw"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($section['title']); ?></h6>
                                                <small class="text-muted">
                                                    <?php 
                                                    if ($section['type'] === 'links' || $section['type'] === 'social') {
                                                        $links = is_array($section['content']) ? $section['content'] : [];
                                                        echo count($links) . ' ' . (count($links) === 1 ? 'link' : 'links');
                                                    } else {
                                                        echo !empty($section['content']) ? 
                                                            htmlspecialchars(substr(strip_tags($section['content']), 0, 30)) . 
                                                            (strlen(strip_tags($section['content'])) > 30 ? '...' : '') : 
                                                            'No content';
                                                    }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark">
                                            <?php echo $sectionTypes[$section['type']] ?? ucfirst($section['type']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   data-id="<?php echo $section['id']; ?>"
                                                   <?php echo $section['status'] === 'active' ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <?php echo $section['sort_order']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end align-middle">
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
                                               onclick="return confirm('Are you sure you want to delete this section? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <span class="btn btn-sm btn-outline-secondary handle" style="cursor: move;">
                                                <i class="fas fa-arrows-alt"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($sections)): ?>
            <div class="card-footer bg-light">
                <div class="text-end">
                    <button type="button" class="btn btn-primary" id="saveOrderBtn">
                        <i class="fas fa-save me-1"></i> Save Order
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include jQuery UI for sortable functionality -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize sortable
    $("#sortable").sortable({
        items: 'tr',
        cursor: 'move',
        opacity: 0.8,
        update: function(event, ui) {
            saveNewOrder();
        }
    });

    // Function to save the new order
    function saveNewOrder() {
        var itemOrder = [];
        $('#sortable tr').each(function(index) {
            itemOrder.push($(this).data('id'));
        });

        // Show loading state
        var saveBtn = $('#saveOrderBtn');
        var originalText = saveBtn.html();
        saveBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

        // Send AJAX request
        $.ajax({
            url: '<?php echo BASE_URL; ?>footer/updateOrder',
            type: 'POST',
            dataType: 'json',
            data: {
                orders: itemOrder
            },
            success: function(response) {
                if (response.success) {
                    // Update the order numbers in the UI
                    $('#sortable tr').each(function(index) {
                        $(this).find('td:first .text-muted').text(index + 1);
                    });
                    
                    // Show success message
                    showAlert('success', 'Section order updated successfully');
                } else {
                    showAlert('danger', response.message || 'Failed to update order');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while updating the order');
            },
            complete: function() {
                saveBtn.html(originalText).prop('disabled', false);
            }
        });
    }

    // Function to show alert messages
    function showAlert(type, message) {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove any existing alerts
        $('.alert').alert('close');
        
        // Add new alert
        $('.container-fluid').prepend(alertHtml);
        
        // Auto-close after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Handle status toggle
    $('.status-toggle').on('change', function() {
        var sectionId = $(this).data('id');
        var isActive = $(this).prop('checked') ? 1 : 0;
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>footer/toggleStatus',
            type: 'POST',
            dataType: 'json',
            data: {
                id: sectionId,
                status: isActive
            },
            success: function(response) {
                if (!response.success) {
                    // Revert the toggle if the request fails
                    $('.status-toggle[data-id="' + sectionId + '"]').prop('checked', !isActive);
                    showAlert('danger', response.message || 'Failed to update status');
                }
            },
            error: function() {
                // Revert the toggle on error
                $('.status-toggle[data-id="' + sectionId + '"]').prop('checked', !isActive);
                showAlert('danger', 'An error occurred while updating the status');
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Make table rows sortable
    $("#sortable").sortable({
        handle: ".handle",
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            // Update the sort order numbers
            $('tbody tr').each(function(index) {
                $(this).find('td:first .text-muted').text(index + 1);
            });
        }
    }).disableSelection();

    // Toggle section status
    $('.status-toggle').change(function() {
        const sectionId = $(this).data('id');
        const isActive = $(this).is(':checked') ? 'active' : 'inactive';
        
        // Send AJAX request to update status
        $.ajax({
            url: '<?php echo BASE_URL; ?>admin/footer/update-status/' + sectionId,
            type: 'POST',
            data: { status: isActive },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    const alert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('.container-fluid').prepend(alert);
                    
                    // Auto-hide alert after 3 seconds
                    setTimeout(() => {
                        $('.alert').fadeOut('slow');
                    }, 3000);
                } else {
                    alert('Error: ' + (response.message || 'Failed to update status'));
                    // Revert the toggle if there was an error
                    $('.status-toggle[data-id=' + sectionId + ']').prop('checked', !isActive);
                }
            },
            error: function() {
                alert('Error: Failed to update status');
                // Revert the toggle if there was an error
                $('.status-toggle[data-id=' + sectionId + ']').prop('checked', !isActive);
            }
        });
    });

    // Save the new order
    $('#saveOrderBtn').click(function() {
        const order = [];
        $('tbody tr').each(function(index) {
            order.push({
                id: $(this).data('id'),
                sort_order: index + 1
            });
        });

        // Show loading state
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...');

        // Send AJAX request to save order
        $.ajax({
            url: '<?php echo BASE_URL; ?>admin/footer/update-order',
            type: 'POST',
            data: { order: order },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    const alert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('.container-fluid').prepend(alert);
                    
                    // Auto-hide alert after 3 seconds
                    setTimeout(() => {
                        $('.alert').fadeOut('slow');
                    }, 3000);
                    
                    // Reload the page to update the table with new sort orders
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Error: ' + (response.message || 'Failed to save order'));
                }
            },
            error: function() {
                alert('Error: Failed to save order');
            },
            complete: function() {
                // Restore button state
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
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

.handle {
    cursor: move;
    cursor: -webkit-grabbing;
}

.ui-state-highlight {
    height: 60px;
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    margin: 10px 0;
}

/* Custom scrollbar for the table */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animation for status toggle */
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .icon-circle {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
}
</style>
