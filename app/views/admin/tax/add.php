<?php 
require_once APP_PATH . 'views/admin/layouts/header.php';

// Get flash messages from session
$error_message = '';
$success_message = '';

if (isset($_SESSION['flash_messages'])) {
    if (isset($_SESSION['flash_messages']['tax_error'])) {
        $error_message = $_SESSION['flash_messages']['tax_error'];
        unset($_SESSION['flash_messages']['tax_error']);
    }
    if (isset($_SESSION['flash_messages']['tax_success'])) {
        $success_message = $_SESSION['flash_messages']['tax_success'];
        unset($_SESSION['flash_messages']['tax_success']);
    }
    
    // Clean up if no more messages
    if (empty($_SESSION['flash_messages'])) {
        unset($_SESSION['flash_messages']);
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Add New Tax Rate</h2>
                <a href="<?php echo BASE_URL; ?>?controller=tax" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=tax&action=add" method="POST" id="taxForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tax Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" 
                                   value="<?php echo htmlspecialchars($data['name']); ?>" 
                                   required>
                            <div class="form-text">Enter a descriptive name for this tax rate (e.g., GST, VAT, Sales Tax).</div>
                        </div>

                        <div class="mb-3">
                            <label for="rate" class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" 
                                       class="form-control" 
                                       id="rate" name="rate" 
                                       value="<?php echo htmlspecialchars($data['rate']); ?>" 
                                       required>
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
                            <button type="button" class="btn btn-outline-secondary me-md-2" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Tax Rate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('taxForm').reset();
    // Reset any invalid states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

// Client-side validation
window.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taxForm');
    const nameInput = document.getElementById('name');
    const rateInput = document.getElementById('rate');
    
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate name
        if (!nameInput.value.trim()) {
            nameInput.classList.add('is-invalid');
            isValid = false;
        } else {
            nameInput.classList.remove('is-invalid');
        }
        
        // Validate rate
        const rate = parseFloat(rateInput.value);
        if (isNaN(rate) || rate < 0 || rate > 100) {
            rateInput.classList.add('is-invalid');
            isValid = false;
        } else {
            rateInput.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
