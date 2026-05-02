<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* ===== CSS Variables & Theme Support ===== */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #818cf8;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --surface: #ffffff;
    --surface-elevated: #ffffff;
    --background: #f8fafc;
    --text-primary: #111827;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;
    --border: #e5e7eb;
    --border-focus: #6366f1;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.25rem;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

[data-theme="dark"] {
    --surface: #1f2937;
    --surface-elevated: #111827;
    --background: #0f172a;
    --text-primary: #f9fafb;
    --text-secondary: #d1d5db;
    --text-muted: #9ca3af;
    --border: #374151;
    --border-focus: #6366f1;
}

/* ===== Layout ===== */
.pm-shell {
    min-height: 100vh;
    background: var(--background);
    padding-bottom: 80px;
}

.pm-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    padding: 1rem 0;
}

[data-theme="dark"] .pm-header {
    background: rgba(31, 41, 55, 0.8);
}

.pm-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.pm-header-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.pm-header-subtitle {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0.25rem 0 0;
}

.pm-header-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ===== Buttons ===== */
.pm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: var(--radius-md);
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
}

.pm-btn-primary {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pm-btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.pm-btn-secondary {
    background: white;
    color: var(--gray-700);
    border-color: var(--border);
}

.pm-btn-secondary:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.pm-btn-ghost {
    background: transparent;
    color: var(--text-secondary);
    border-color: transparent;
}

.pm-btn-ghost:hover {
    background: var(--gray-100);
    color: var(--text-primary);
}

[data-theme="dark"] .pm-btn-ghost:hover {
    background: var(--gray-800);
}

/* ===== Cards ===== */
.pm-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: var(--transition);
}

.pm-card:hover {
    box-shadow: var(--shadow);
}

.pm-card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(to right, var(--surface), var(--gray-50));
}

[data-theme="dark"] .pm-card-header {
    background: linear-gradient(to right, var(--surface), rgba(55, 65, 81, 0.5));
}

.pm-card-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pm-card-subtitle {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    margin: 0.375rem 0 0;
}

.pm-card-body {
    padding: 1.5rem;
}

/* ===== Form Components ===== */
.pm-form-group {
    margin-bottom: 1.25rem;
}

.pm-form-label {
    display: block;
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.375rem;
}

.pm-form-label-required::after {
    content: "*";
    color: var(--danger);
    margin-left: 0.25rem;
}

.pm-input-wrapper {
    position: relative;
}

.pm-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: var(--text-primary);
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.pm-input:focus {
    outline: none;
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.pm-input::placeholder {
    color: var(--text-muted);
}

.pm-input-group {
    display: flex;
    align-items: stretch;
}

.pm-input-group .pm-input {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.pm-input-group-text {
    display: flex;
    align-items: center;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--gray-50);
    border: 1px solid var(--border);
    border-right: none;
    border-radius: var(--radius-md) 0 0 var(--radius-md);
}

.pm-textarea {
    min-height: 100px;
    resize: vertical;
}

/* ===== Floating Label Input ===== */
.pm-floating {
    position: relative;
}

.pm-floating .pm-input {
    padding-top: 1.125rem;
    padding-bottom: 0.375rem;
}

.pm-floating-label {
    position: absolute;
    left: 0.875rem;
    top: 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-muted);
    pointer-events: none;
    transition: var(--transition);
}

.pm-floating .pm-input:focus + .pm-floating-label,
.pm-floating .pm-input:not(:placeholder-shown) + .pm-floating-label {
    top: 0.25rem;
    font-size: 0.6875rem;
    color: var(--primary);
}

/* ===== Select ===== */
.pm-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}

/* ===== Toggle Switch ===== */
.pm-toggle {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.pm-toggle-input {
    appearance: none;
    width: 2.75rem;
    height: 1.5rem;
    background: var(--gray-300);
    border-radius: 9999px;
    position: relative;
    cursor: pointer;
    transition: var(--transition);
}

.pm-toggle-input:checked {
    background: var(--primary);
}

.pm-toggle-input::after {
    content: "";
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    background: white;
    border-radius: 50%;
    top: 0.125rem;
    left: 0.125rem;
    transition: transform 0.2s;
    box-shadow: var(--shadow-sm);
}

.pm-toggle-input:checked::after {
    transform: translateX(1.25rem);
}

.pm-toggle-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
}

/* ===== Image Upload ===== */
.pm-upload {
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    background: var(--gray-50);
}

.pm-upload:hover,
.pm-upload.dragover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.05);
}

.pm-upload-icon {
    width: 3rem;
    height: 3rem;
    margin: 0 auto 1rem;
    color: var(--primary);
}

.pm-upload-text {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin: 0;
}

.pm-upload-hint {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin: 0.5rem 0 0;
}

.pm-upload-preview {
    position: relative;
    display: inline-block;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.pm-upload-preview img {
    max-width: 200px;
    max-height: 200px;
    object-fit: cover;
}

.pm-upload-remove {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 2rem;
    height: 2rem;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.pm-upload-remove:hover {
    background: var(--danger);
}

/* ===== Calculations Card ===== */
.pm-calc-card {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(16, 185, 129, 0.05));
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-md);
    padding: 1rem;
}

.pm-calc-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--border);
}

.pm-calc-row:last-child {
    border-bottom: none;
}

.pm-calc-label {
    font-size: 0.8125rem;
    color: var(--text-secondary);
}

.pm-calc-value {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-primary);
}

.pm-calc-value.positive {
    color: var(--success);
}

.pm-calc-value.negative {
    color: var(--danger);
}

/* ===== Status Badge ===== */
.pm-status {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 9999px;
}

.pm-status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.pm-status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}

/* ===== Toast Notifications ===== */
.pm-toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.pm-toast {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    min-width: 300px;
    max-width: 400px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.pm-toast-icon {
    width: 1.5rem;
    height: 1.5rem;
    flex-shrink: 0;
}

.pm-toast-success .pm-toast-icon {
    color: var(--success);
}

.pm-toast-error .pm-toast-icon {
    color: var(--danger);
}

.pm-toast-content {
    flex: 1;
}

.pm-toast-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.pm-toast-message {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    margin: 0.125rem 0 0;
}

.pm-toast-close {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.25rem;
    transition: var(--transition);
}

.pm-toast-close:hover {
    color: var(--text-primary);
}

/* ===== Validation States ===== */
.pm-input.is-valid {
    border-color: var(--success);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2310b981' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}

.pm-input.is-invalid {
    border-color: var(--danger);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ef4444' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'%3E%3C/circle%3E%3Cline x1='15' y1='9' x2='9' y2='15'%3E%3C/line%3E%3Cline x1='9' y1='9' x2='15' y2='15'%3E%3C/line%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}

.pm-invalid-feedback {
    font-size: 0.75rem;
    color: var(--danger);
    margin-top: 0.375rem;
}

/* ===== Bottom Action Bar ===== */
.pm-action-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 90;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    border-top: 1px solid var(--border);
    padding: 1rem 0;
}

[data-theme="dark"] .pm-action-bar {
    background: rgba(31, 41, 55, 0.9);
}

.pm-action-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

/* ===== Mobile Responsive ===== */
@media (max-width: 991.98px) {
    .pm-card-body {
        padding: 1.25rem;
    }
    
    .pm-header-title {
        font-size: 1.125rem;
    }
    
    .pm-header-actions .pm-btn span {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .pm-shell {
        padding-bottom: 100px;
    }
    
    .pm-header {
        padding: 0.75rem 0;
    }
    
    .pm-header-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .pm-header-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .pm-card {
        margin-bottom: 1rem;
    }
    
    .pm-card-header {
        padding: 1rem 1.25rem;
    }
    
    .pm-card-body {
        padding: 1rem;
    }
    
    .pm-action-bar-inner {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .pm-action-bar .pm-btn {
        width: 100%;
    }
}

/* ===== Select2 Customization ===== */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 42px;
    border-color: var(--border);
    border-radius: var(--radius-md);
}

.select2-container--bootstrap-5 .select2-selection:focus {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* ===== Loading States ===== */
.pm-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.pm-loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 1.5rem;
    height: 1.5rem;
    margin: -0.75rem 0 0 -0.75rem;
    border: 2px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ===== Auto-generate Button ===== */
.pm-btn-auto {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
}

.pm-btn-auto:hover {
    background: rgba(99, 102, 241, 0.2);
}
</style>

<div class="pm-shell">
    <!-- Header -->
    <header class="pm-header">
        <div class="container-fluid px-3 px-lg-4">
            <div class="pm-header-inner">
                <div>
                    <h1 class="pm-header-title">Add Product</h1>
                    <p class="pm-header-subtitle">Create a new product with complete details</p>
                </div>
                <div class="pm-header-actions">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="pm-btn pm-btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back</span>
                    </a>
                    <button type="button" class="pm-btn pm-btn-secondary" id="previewBtn">
                        <i class="fas fa-eye"></i>
                        <span>Preview</span>
                    </button>
                    <button type="button" class="pm-btn pm-btn-secondary" id="saveAndNewBtn">
                        <i class="fas fa-plus"></i>
                        <span>Save & New</span>
                    </button>
                    <button type="submit" class="pm-btn pm-btn-primary" id="saveBtn" form="productForm">
                        <i class="fas fa-save"></i>
                        <span>Save Product</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container-fluid px-3 px-lg-4 py-4">
        <form id="productForm" action="<?php echo BASE_URL; ?>?controller=product&action=create" method="POST" enctype="multipart/form-data" novalidate>
            <div class="row g-4">
                <!-- Left Column: Product Details & Pricing -->
                <div class="col-lg-8">
                    <!-- Basic Info Card -->
                    <div class="pm-card mb-4">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-box text-primary"></i>
                                Basic Information
                            </h2>
                            <p class="pm-card-subtitle">Core product details that customers will see</p>
                        </div>
                        <div class="pm-card-body">
                            <div class="row g-3">
                                <!-- Product Name -->
                                <div class="col-12">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label pm-form-label-required" for="name">Product Name</label>
                                        <div class="pm-input-wrapper">
                                            <input type="text" 
                                                   class="pm-input" 
                                                   id="name" 
                                                   name="name" 
                                                   placeholder="Enter product name"
                                                   value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>"
                                                   required>
                                        </div>
                                        <div class="pm-invalid-feedback" id="nameError"></div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="description">Description</label>
                                        <textarea class="pm-input pm-textarea" 
                                                  id="description" 
                                                  name="description" 
                                                  placeholder="Enter product description..."><?php echo htmlspecialchars($data['description'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <!-- Category & Brand -->
                                <div class="col-md-6">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label pm-form-label-required" for="category_id">Category</label>
                                        <div class="pm-input-group">
                                            <select class="pm-input pm-select" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php 
                                                $categoryModel = new Category();
                                                $categories = method_exists($categoryModel, 'getActiveCategoriesWithTaxRate')
                                                    ? $categoryModel->getActiveCategoriesWithTaxRate()
                                                    : $categoryModel->getActiveCategories();
                                                foreach($categories as $cat): 
                                                    $selected = (isset($data['category_id']) && $data['category_id'] == $cat['id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $cat['id']; ?>" data-tax-rate="<?php echo $cat['tax_rate'] ?? 0; ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($cat['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="<?php echo BASE_URL; ?>?controller=category&action=create" 
                                               class="pm-btn pm-btn-secondary" 
                                               style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                               target="_blank">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                        <div class="pm-invalid-feedback" id="categoryError"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="brand_id">Brand</label>
                                        <div class="pm-input-group">
                                            <select class="pm-input pm-select" id="brand_id" name="brand_id">
                                                <option value="">Select Brand</option>
                                                <?php 
                                                $brandModel = new Brand();
                                                $brands = $brandModel->getActiveBrands();
                                                foreach($brands as $brand): 
                                                    $selected = (isset($data['brand_id']) && $data['brand_id'] == $brand['id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($brand['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" 
                                               class="pm-btn pm-btn-secondary" 
                                               style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                               target="_blank">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="pm-card">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-tag text-success"></i>
                                Pricing & Tax
                            </h2>
                            <p class="pm-card-subtitle">Set pricing, margins, and tax configuration</p>
                        </div>
                        <div class="pm-card-body">
                            <div class="row g-3">
                                <!-- Buying Price -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="buying_price">Buying Price</label>
                                        <div class="pm-input-group">
                                            <span class="pm-input-group-text">CHF</span>
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="buying_price" 
                                                   name="buying_price"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $data['buying_price'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Selling Price -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label pm-form-label-required" for="selling_price">Selling Price</label>
                                        <div class="pm-input-group">
                                            <span class="pm-input-group-text">CHF</span>
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="selling_price" 
                                                   name="selling_price"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $data['selling_price'] ?? ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tax -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="tax_percent">Tax %</label>
                                        <div class="pm-input-group">
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="tax_percent" 
                                                   name="tax_percent"
                                                   placeholder="0"
                                                   step="0.01"
                                                   min="0"
                                                   max="100"
                                                   value="<?php echo $data['tax_percent'] ?? ''; ?>">
                                            <span class="pm-input-group-text" style="border-left: none; border-right: 1px solid var(--border);">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Wholesale Price -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="wholesale_price">Wholesale Price</label>
                                        <div class="pm-input-group">
                                            <span class="pm-input-group-text">CHF</span>
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="wholesale_price" 
                                                   name="wholesale_price"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $data['wholesale_price'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Transport -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="transport_charge">Transport</label>
                                        <div class="pm-input-group">
                                            <span class="pm-input-group-text">CHF</span>
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="transport_charge" 
                                                   name="transport_charge"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $data['transport_charge'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Customs -->
                                <div class="col-md-4">
                                    <div class="pm-form-group">
                                        <label class="pm-form-label" for="customs_charge">Customs</label>
                                        <div class="pm-input-group">
                                            <span class="pm-input-group-text">CHF</span>
                                            <input type="number" 
                                                   class="pm-input" 
                                                   id="customs_charge" 
                                                   name="customs_charge"
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $data['customs_charge'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Calculations Summary -->
                            <div class="pm-calc-card mt-4">
                                <div class="pm-calc-row">
                                    <span class="pm-calc-label">Total Cost (Buy + Transport + Customs)</span>
                                    <span class="pm-calc-value" id="totalCost">CHF 0.00</span>
                                </div>
                                <div class="pm-calc-row">
                                    <span class="pm-calc-label">Profit (Sell - Total Cost)</span>
                                    <span class="pm-calc-value positive" id="profit">CHF 0.00</span>
                                </div>
                                <div class="pm-calc-row">
                                    <span class="pm-calc-label">Margin %</span>
                                    <span class="pm-calc-value positive" id="margin">0.00%</span>
                                </div>
                                <div class="pm-calc-row">
                                    <span class="pm-calc-label">Price with Tax</span>
                                    <span class="pm-calc-value" id="priceWithTax">CHF 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Inventory, Status & Image -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="pm-card mb-4">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-toggle-on text-info"></i>
                                Status
                            </h2>
                        </div>
                        <div class="pm-card-body">
                            <label class="pm-toggle">
                                <input type="checkbox" 
                                       class="pm-toggle-input" 
                                       id="status" 
                                       name="status" 
                                       value="active"
                                       checked>
                                <span class="pm-toggle-label">Product is active</span>
                            </label>
                            <p class="text-muted small mt-2 mb-0">Inactive products won't be visible to customers</p>
                        </div>
                    </div>

                    <!-- Inventory Card -->
                    <div class="pm-card mb-4">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-warehouse text-warning"></i>
                                Inventory
                            </h2>
                        </div>
                        <div class="pm-card-body">
                            <!-- SKU -->
                            <div class="pm-form-group">
                                <label class="pm-form-label pm-form-label-required" for="sku">SKU</label>
                                <div class="pm-input-wrapper" style="position: relative;">
                                    <input type="text" 
                                           class="pm-input" 
                                           id="sku" 
                                           name="sku"
                                           placeholder="AUTO-GENERATE"
                                           value="<?php echo htmlspecialchars($data['sku'] ?? ''); ?>"
                                           required>
                                    <button type="button" class="pm-btn-auto" id="generateSku">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                                <div class="pm-invalid-feedback" id="skuError"></div>
                            </div>

                            <!-- Stock -->
                            <div class="pm-form-group">
                                <label class="pm-form-label" for="stock_quantity">Stock Quantity</label>
                                <input type="number" 
                                       class="pm-input" 
                                       id="stock_quantity" 
                                       name="stock_quantity"
                                       placeholder="0"
                                       min="0"
                                       value="<?php echo $data['stock_quantity'] ?? '0'; ?>">
                            </div>

                            <!-- Unit -->
                            <div class="pm-form-group">
                                <label class="pm-form-label" for="unit_id">Unit</label>
                                <div class="pm-input-group">
                                    <select class="pm-input pm-select" id="unit_id" name="unit_id">
                                        <option value="">Select Unit</option>
                                        <?php 
                                        $unitModel = new Unit();
                                        $units = method_exists($unitModel, 'getActiveUnits') ? $unitModel->getActiveUnits() : [];
                                        foreach($units as $unit): 
                                            $selected = (isset($data['unit_id']) && $data['unit_id'] == $unit['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $unit['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($unit['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="pm-btn pm-btn-secondary" id="addUnitBtn" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="pm-form-group mb-0">
                                <label class="pm-form-label" for="expiry_date">Expiry Date</label>
                                <input type="date" 
                                       class="pm-input" 
                                       id="expiry_date" 
                                       name="expiry_date"
                                       value="<?php echo $data['expiry_date'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Supplier & Location -->
                    <div class="pm-card mb-4">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-truck text-secondary"></i>
                                Supplier & Location
                            </h2>
                        </div>
                        <div class="pm-card-body">
                            <!-- Supplier -->
                            <div class="pm-form-group">
                                <label class="pm-form-label" for="supplier">Supplier</label>
                                <input type="text" 
                                       class="pm-input" 
                                       id="supplier" 
                                       name="supplier"
                                       placeholder="Enter supplier name"
                                       list="supplierList"
                                       value="<?php echo htmlspecialchars($data['supplier'] ?? ''); ?>">
                                <datalist id="supplierList">
                                    <?php foreach($suppliers ?? [] as $s): ?>
                                        <option value="<?php echo htmlspecialchars($s['name']); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>

                            <!-- Country -->
                            <div class="pm-form-group mb-0">
                                <label class="pm-form-label" for="country_id">Country</label>
                                <select class="pm-input pm-select" id="country_id" name="country_id">
                                    <option value="">Select Country</option>
                                    <?php 
                                    $countryModel = new Country();
                                    $countries = method_exists($countryModel, 'getAllCountries') ? $countryModel->getAllCountries() : [];
                                    foreach($countries as $country): 
                                        $selected = (isset($data['country_id']) && $data['country_id'] == $country['id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $country['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($country['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="pm-card">
                        <div class="pm-card-header">
                            <h2 class="pm-card-title">
                                <i class="fas fa-image text-purple"></i>
                                Product Image
                            </h2>
                        </div>
                        <div class="pm-card-body">
                            <div class="pm-upload" id="imageUpload">
                                <input type="file" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*" 
                                       style="display: none;">
                                <div class="pm-upload-content">
                                    <div class="pm-upload-icon">
                                        <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                    </div>
                                    <p class="pm-upload-text">Drag & drop or click to upload</p>
                                    <p class="pm-upload-hint">JPG, PNG, WebP up to 5MB</p>
                                </div>
                                <div class="pm-upload-preview d-none" id="imagePreview">
                                    <img src="" alt="Preview" id="previewImg">
                                    <button type="button" class="pm-upload-remove" id="removeImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Bottom Action Bar -->
    <div class="pm-action-bar">
        <div class="container-fluid px-3 px-lg-4">
            <div class="pm-action-bar-inner">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Last saved: <span id="lastSaved">Never</span></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="pm-btn pm-btn-ghost" id="cancelBtn">
                        Cancel
                    </button>
                    <button type="submit" class="pm-btn pm-btn-primary" id="saveBottomBtn" form="productForm">
                        <i class="fas fa-save"></i>
                        Save Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="pm-toast-container" id="toastContainer"></div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: var(--radius-lg); border: 1px solid var(--border);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title">Add New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="pm-form-group">
                    <label class="pm-form-label pm-form-label-required">Unit Name</label>
                    <input type="text" class="pm-input" id="newUnitName" name="unit_name" placeholder="e.g., Kilogram, Piece, Box">
                </div>
                <div class="pm-form-group mb-0">
                    <label class="pm-form-label pm-form-label-required">Short Name</label>
                    <input type="text" class="pm-input" id="newUnitCode" name="short_name" placeholder="e.g., kg, pc, box">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border);">
                <button type="button" class="pm-btn pm-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="pm-btn pm-btn-primary" id="saveUnitBtn">Save Unit</button>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Product Form Manager
 * Handles form validation, calculations, AJAX submission, and UX enhancements
 */
class ProductFormManager {
    constructor() {
        this.form = document.getElementById('productForm');
        this.toastContainer = document.getElementById('toastContainer');
        this.init();
    }

    init() {
        this.bindEvents();
        this.initCalculations();
        this.initImageUpload();
        this.initSkuGenerator();
        this.initUnitModal();
    }

    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Save buttons
        document.getElementById('saveBtn').addEventListener('click', () => this.form.setAttribute('data-action', 'save'));
        document.getElementById('saveBottomBtn').addEventListener('click', () => this.form.setAttribute('data-action', 'save'));
        document.getElementById('saveAndNewBtn').addEventListener('click', () => {
            this.form.setAttribute('data-action', 'saveAndNew');
            this.form.dispatchEvent(new Event('submit'));
        });

        // Cancel button
        document.getElementById('cancelBtn').addEventListener('click', () => {
            if (confirm('Discard changes?')) {
                window.location.href = '<?php echo BASE_URL; ?>?controller=product&action=adminIndex';
            }
        });

        // Preview button
        document.getElementById('previewBtn').addEventListener('click', () => this.showPreview());

        // Real-time validation
        const inputs = this.form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearError(input));
        });

        // Category change - auto-fill tax
        document.getElementById('category_id').addEventListener('change', (e) => {
            const option = e.target.selectedOptions[0];
            if (option && option.dataset.taxRate) {
                document.getElementById('tax_percent').value = option.dataset.taxRate;
                this.calculate();
            }
        });
    }

    initCalculations() {
        const calcFields = ['buying_price', 'selling_price', 'wholesale_price', 'transport_charge', 'customs_charge', 'tax_percent'];
        calcFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) {
                field.addEventListener('input', () => this.calculate());
            }
        });
        this.calculate();
    }

    calculate() {
        const buyingPrice = parseFloat(document.getElementById('buying_price').value) || 0;
        const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
        const transport = parseFloat(document.getElementById('transport_charge').value) || 0;
        const customs = parseFloat(document.getElementById('customs_charge').value) || 0;
        const taxPercent = parseFloat(document.getElementById('tax_percent').value) || 0;

        const totalCost = buyingPrice + transport + customs;
        const profit = sellingPrice - totalCost;
        const margin = sellingPrice > 0 ? (profit / sellingPrice) * 100 : 0;
        const priceWithTax = sellingPrice * (1 + taxPercent / 100);

        // Update display
        document.getElementById('totalCost').textContent = `CHF ${totalCost.toFixed(2)}`;
        document.getElementById('profit').textContent = `CHF ${profit.toFixed(2)}`;
        document.getElementById('profit').className = `pm-calc-value ${profit >= 0 ? 'positive' : 'negative'}`;
        document.getElementById('margin').textContent = `${margin.toFixed(2)}%`;
        document.getElementById('margin').className = `pm-calc-value ${margin >= 0 ? 'positive' : 'negative'}`;
        document.getElementById('priceWithTax').textContent = `CHF ${priceWithTax.toFixed(2)}`;
    }

    initImageUpload() {
        const upload = document.getElementById('imageUpload');
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeBtn = document.getElementById('removeImage');
        const content = upload.querySelector('.pm-upload-content');

        upload.addEventListener('click', () => input.click());

        upload.addEventListener('dragover', (e) => {
            e.preventDefault();
            upload.classList.add('dragover');
        });

        upload.addEventListener('dragleave', () => {
            upload.classList.remove('dragover');
        });

        upload.addEventListener('drop', (e) => {
            e.preventDefault();
            upload.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length) {
                input.files = files;
                this.showImagePreview(files[0], previewImg, preview, content);
            }
        });

        input.addEventListener('change', () => {
            if (input.files.length) {
                this.showImagePreview(input.files[0], previewImg, preview, content);
            }
        });

        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            input.value = '';
            preview.classList.add('d-none');
            content.classList.remove('d-none');
        });
    }

    showImagePreview(file, img, preview, content) {
        if (!file.type.startsWith('image/')) {
            this.showToast('Error', 'Please upload an image file', 'error');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            this.showToast('Error', 'Image size should be less than 5MB', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
            preview.classList.remove('d-none');
            content.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    initSkuGenerator() {
        document.getElementById('generateSku').addEventListener('click', () => {
            const name = document.getElementById('name').value;
            if (!name) {
                this.showToast('Error', 'Please enter a product name first', 'error');
                return;
            }

            // Generate SKU from name + timestamp
            const prefix = name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');
            const timestamp = Date.now().toString(36).toUpperCase();
            const sku = `${prefix}-${timestamp}`;
            
            document.getElementById('sku').value = sku;
            this.showToast('Success', 'SKU generated automatically', 'success');
        });
    }

    initUnitModal() {
        const modal = new bootstrap.Modal(document.getElementById('unitModal'));
        
        document.getElementById('addUnitBtn').addEventListener('click', () => modal.show());
        
        document.getElementById('saveUnitBtn').addEventListener('click', async () => {
            const unitName = document.getElementById('newUnitName').value;
            const shortName = document.getElementById('newUnitCode').value;
            
            if (!unitName || !shortName) {
                this.showToast('Error', 'Unit name and short name are required', 'error');
                return;
            }

            try {
                const response = await fetch('<?php echo BASE_URL; ?>?controller=unit&action=ajaxCreate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `unit_name=${encodeURIComponent(unitName)}&short_name=${encodeURIComponent(shortName)}`
                });

                const result = await response.json();
                
                if (result.success) {
                    // Add to select
                    const select = document.getElementById('unit_id');
                    const option = new Option(unitName, result.id, true, true);
                    select.add(option);
                    
                    modal.hide();
                    document.getElementById('newUnitName').value = '';
                    document.getElementById('newUnitCode').value = '';
                    this.showToast('Success', 'Unit added successfully', 'success');
                } else {
                    this.showToast('Error', result.message || 'Failed to add unit', 'error');
                }
            } catch (error) {
                this.showToast('Error', 'Network error', 'error');
            }
        });
    }

    validateField(field) {
        const errorEl = document.getElementById(field.id + 'Error');
        
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            if (errorEl) errorEl.textContent = 'This field is required';
            return false;
        }

        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        if (errorEl) errorEl.textContent = '';
        return true;
    }

    clearError(field) {
        field.classList.remove('is-invalid');
        const errorEl = document.getElementById(field.id + 'Error');
        if (errorEl) errorEl.textContent = '';
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        // Validate all required fields
        const required = this.form.querySelectorAll('[required]');
        let isValid = true;
        required.forEach(field => {
            if (!this.validateField(field)) isValid = false;
        });

        if (!isValid) {
            this.showToast('Error', 'Please fill in all required fields', 'error');
            return;
        }

        // Show loading state
        const submitBtns = document.querySelectorAll('button[type="submit"]');
        submitBtns.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('pm-loading');
        });

        try {
            const formData = new FormData(this.form);
            
            // Handle status checkbox
            const statusToggle = document.getElementById('status');
            formData.set('status', statusToggle.checked ? 'active' : 'inactive');

            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('Success', 'Product saved successfully', 'success');
                document.getElementById('lastSaved').textContent = new Date().toLocaleTimeString();
                
                const action = this.form.getAttribute('data-action');
                if (action === 'saveAndNew') {
                    this.form.reset();
                    document.getElementById('imagePreview').classList.add('d-none');
                    document.querySelector('.pm-upload-content').classList.remove('d-none');
                } else {
                    setTimeout(() => {
                        window.location.href = '<?php echo BASE_URL; ?>?controller=product&action=adminIndex';
                    }, 1000);
                }
            } else {
                this.showToast('Error', result.message || 'Failed to save product', 'error');
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        const field = document.getElementById(key);
                        if (field) {
                            field.classList.add('is-invalid');
                            const errorEl = document.getElementById(key + 'Error');
                            if (errorEl) errorEl.textContent = result.errors[key];
                        }
                    });
                }
            }
        } catch (error) {
            this.showToast('Error', 'Network error. Please try again.', 'error');
        } finally {
            submitBtns.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('pm-loading');
            });
        }
    }

    showToast(title, message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `pm-toast pm-toast-${type}`;
        toast.innerHTML = `
            <svg class="pm-toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                }
            </svg>
            <div class="pm-toast-content">
                <h4 class="pm-toast-title">${title}</h4>
                <p class="pm-toast-message">${message}</p>
            </div>
            <button class="pm-toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        this.toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    showPreview() {
        const name = document.getElementById('name').value || 'Untitled Product';
        const sellingPrice = document.getElementById('selling_price').value || '0.00';
        const stock = document.getElementById('stock_quantity').value || '0';
        
        const previewHTML = `
            <div style="padding: 2rem; text-align: center;">
                <h3 style="margin-bottom: 1rem;">${name}</h3>
                <p style="font-size: 1.5rem; color: var(--primary); font-weight: 600;">CHF ${sellingPrice}</p>
                <p style="color: var(--text-secondary);">Stock: ${stock} units</p>
            </div>
        `;
        
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: var(--radius-lg);">
                    <div class="modal-header">
                        <h5 class="modal-title">Product Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${previewHTML}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ProductFormManager();
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
