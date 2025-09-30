<?php include(APP_PATH . 'views/admin/layouts/header.php'); ?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shoe-prints text-primary me-2"></i>
            <?php echo $action === 'add' ? 'Add New' : 'Edit'; ?> Footer Section
        </h1>
        <a href="<?php echo BASE_URL; ?>admin/footer" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Alerts -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="footerSectionForm" method="POST" action="<?php echo BASE_URL . 'admin/footer/' . ($action === 'add' ? 'store' : 'update/' . $section->id); ?>">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Section Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Section Type</label>
                            <select class="form-select" id="type" name="type" <?php echo $action === 'edit' ? 'disabled' : ''; ?>>
                                <option value="">-- Select Section Type --</option>
                                <?php foreach ($sectionTypes as $type => $config): ?>
                                    <option value="<?php echo $type; ?>" 
                                            data-icon="<?php echo $config['icon']; ?>"
                                            <?php echo (isset($section) && $section->type === $type) ? 'selected' : ''; ?>>
                                        <?php echo $config['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="type" value="<?php echo $section->type; ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?php echo $section->title ?? ''; ?>" required>
                        </div>

                        <!-- Dynamic Fields Container -->
                        <div id="dynamicFields">
                            <?php 
                            if (isset($sectionTypeConfig) && $sectionTypeConfig) {
                                $this->renderSectionFields($sectionTypeConfig, $section->fields ?? []);
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <!-- Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Status</h6>
                            </div>
                            <div class="card-body">
                                <select class="form-select" name="status">
                                    <option value="active" <?php echo (isset($section) && $section->status === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo (isset($section) && $section->status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Sort Order -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold">Sort Order</h6>
                            </div>
                            <div class="card-body">
                                <input type="number" class="form-control" name="sort_order" 
                                       value="<?php echo $section->sort_order ?? 0; ?>">
                                <small class="text-muted">Lower numbers display first</small>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                <?php echo $action === 'add' ? 'Create' : 'Update'; ?> Section
                            </button>
                            
                            <?php if ($action === 'edit'): ?>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete(<?php echo $section->id; ?>)">
                                    <i class="fas fa-trash-alt me-1"></i> Delete Section
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this footer section? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
// Delete confirmation
function confirmDelete(sectionId) {
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    var deleteBtn = document.getElementById('confirmDeleteBtn');
    
    deleteBtn.href = '<?php echo BASE_URL; ?>admin/footer/delete/' + sectionId;
    modal.show();
}

// Load dynamic fields when section type changes
$(document).ready(function() {
    $('#type').on('change', function() {
        var type = $(this).val();
        
        if (!type) {
            $('#dynamicFields').html('');
            return;
        }
        
        // Show loading state
        $('#dynamicFields').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        // Load fields via AJAX
        $.ajax({
            url: '<?php echo BASE_URL; ?>admin/footer/getSectionFields',
            type: 'GET',
            data: { type: type },
            success: function(response) {
                $('#dynamicFields').html(response);
                
                // Initialize any datepickers, file uploads, etc.
                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            },
            error: function() {
                $('#dynamicFields').html('<div class="alert alert-danger">Failed to load section fields. Please try again.</div>');
            }
        });
    });
    
    // Handle adding new repeater items
    $(document).on('click', '.add-repeater-item', function(e) {
        e.preventDefault();
        
        var container = $(this).closest('.repeater-container');
        var template = container.find('.repeater-template').first().clone();
        var itemsContainer = container.find('.repeater-items');
        var itemCount = itemsContainer.find('.repeater-item').length;
        
        // Update field names with new index
        template.find('[name]').each(function() {
            var name = $(this).attr('name');
            name = name.replace(/\[\d+\]/, '[' + itemCount + ']');
            $(this).attr('name', name);
        });
        
        // Add the new item
        template.removeClass('repeater-template d-none').addClass('repeater-item');
        itemsContainer.append(template);
        
        // Scroll to the new item
        $('html, body').animate({
            scrollTop: template.offset().top - 100
        }, 300);
    });
    
    // Handle removing repeater items
    $(document).on('click', '.remove-repeater-item', function(e) {
        e.preventDefault();
        
        var item = $(this).closest('.repeater-item');
        var container = item.closest('.repeater-container');
        
        // If this is the last item, just clear the values
        if (container.find('.repeater-item').length === 1) {
            item.find('input, textarea, select').val('');
        } else {
            item.remove();
            // Rename remaining items to maintain array indexes
            container.find('.repeater-item').each(function(index) {
                $(this).find('[name]').each(function() {
                    var name = $(this).attr('name');
                    name = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });
            });
        }
    });
});
</script>

<?php include(APP_PATH . 'views/admin/layouts/footer.php'); ?>
