<?php
/**
 * Purchase Create — Enterprise ERP UI (visual layer only)
 * Preserves: #purchaseForm, supplier_id, purchase_date, status, document,
 * #products-container, payment fields, loadProducts/submitForm/updateTotals JS.
 */
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
$supplierId = $formData['supplier_id'] ?? '';
$items = $formData['items'] ?? [['product_id' => '', 'quantity' => 1, 'unit_price' => '']];
$suppliers = $suppliers ?? [];
require_once APP_PATH . 'views/admin/layouts/header.php';

$supplierJson = [];
foreach ($suppliers as $s) {
    $supplierJson[] = [
        'id' => (int)($s['id'] ?? 0),
        'name' => (string)($s['name'] ?? ''),
        'email' => (string)($s['email'] ?? ''),
        'phone' => (string)($s['phone'] ?? ''),
        'address' => (string)($s['address'] ?? ''),
    ];
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/purchase-form.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid purchase-form-page purchase-create py-3 py-md-4 px-2 px-sm-3" id="purchaseCreatePage">
    <div class="pf-toast-host" id="pfToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="pf-header">
        <div>
            <nav class="pf-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span class="sep">›</span>
                <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index">Purchases</a>
                <span class="sep">›</span>
                <span aria-current="page">Create Purchase</span>
            </nav>
            <h1 class="pf-title">Purchase Management</h1>
            <p class="pf-subtitle"><?php echo htmlspecialchars($title ?? 'Create Purchase'); ?> — supplier, products, document, and payment entry.</p>
        </div>
        <div class="pf-actions">
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary pf-btn">
                <i class="bi bi-arrow-left"></i><span>Back</span>
            </a>
            <button type="button" class="btn btn-outline-secondary pf-btn" id="pfSaveDraftBtn">
                <i class="bi bi-file-earmark"></i><span>Save Draft</span>
            </button>
            <button type="button" class="btn btn-outline-secondary pf-btn" onclick="window.print()" title="Print Preview">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-primary pf-btn" data-bs-toggle="modal" data-bs-target="#pfPreviewModal">
                <i class="bi bi-eye"></i><span>Preview</span>
            </button>
            <button type="submit" form="purchaseForm" class="btn pf-btn pf-btn-primary" id="pfSaveTop">
                <i class="bi bi-check2-circle"></i><span>Save Purchase</span>
            </button>
        </div>
    </div>

    <?php flash('error'); ?>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <form id="purchaseForm" onsubmit="submitForm(event)" enctype="multipart/form-data">

                <!-- SECTION 1: Purchase Information -->
                <section class="pf-card mb-3">
                    <div class="pf-card-header">
                        <h2><i class="bi bi-receipt text-primary"></i> Purchase Information</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="pf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?php echo $supplier['id']; ?>"
                                            <?php echo ($supplierId == $supplier['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($supplier['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date" required
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="status" class="form-label">Purchase Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Please select</option>
                                    <option value="received">Received</option>
                                    <option value="pending">Pending</option>
                                    <option value="ordered">Ordered</option>
                                </select>
                            </div>

                            <div class="col-6 col-md-3">
                                <label for="pfPurchaseNo" class="form-label">Purchase Number</label>
                                <input type="text" class="form-control" id="pfPurchaseNo" value="PO-<?php echo date('Ymd'); ?>-NEW" readonly>
                                <div class="form-text"><span class="pf-ui-only">(UI only)</span></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="pfRefNo" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="pfRefNo" placeholder="Supplier invoice #">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="pfExpectedDate" class="form-label">Expected Delivery</label>
                                <input type="date" class="form-control" id="pfExpectedDate">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="pfWarehouse" class="form-label">Warehouse</label>
                                <select class="form-select" id="pfWarehouse">
                                    <option value="main" selected>Main Warehouse</option>
                                    <option value="secondary">Secondary</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="pfBranch" class="form-label">Branch</label>
                                <input type="text" class="form-control" id="pfBranch" placeholder="Head Office">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="pfPayStatus" class="form-label">Payment Status</label>
                                <select class="form-select" id="pfPayStatus">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="pfCurrency" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="pfCurrency" value="<?php echo htmlspecialchars(defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'CHF'); ?>" readonly>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="pfFx" class="form-label">Exchange Rate</label>
                                <input type="number" class="form-control" id="pfFx" value="1" step="0.0001" min="0">
                            </div>
                            <div class="col-12">
                                <label for="pfRemarks" class="form-label">Remarks</label>
                                <textarea class="form-control" id="pfRemarks" rows="2" placeholder="Internal notes for this purchase"></textarea>
                                <div class="form-text"><span class="pf-ui-only">Extra fields above are UI-only and are not posted to the backend.</span></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Document Upload -->
                <section class="pf-card mb-3">
                    <div class="pf-card-header">
                        <h2><i class="bi bi-paperclip text-primary"></i> Attachments</h2>
                    </div>
                    <div class="pf-card-body">
                        <label for="document" class="form-label">Invoice / Document</label>
                        <div class="pf-dropzone" id="pfDocDrop">
                            <div class="fw-semibold mb-1"><i class="bi bi-cloud-arrow-up me-1"></i>Drag &amp; drop or click to upload</div>
                            <div class="small text-muted">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG</div>
                            <input type="file" id="document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        </div>
                        <div class="small text-muted mt-2" id="pfDocMeta">No file selected</div>
                    </div>
                </section>

                <!-- Products -->
                <section class="pf-card mb-3">
                    <div class="pf-card-header">
                        <h2><i class="bi bi-box-seam text-primary"></i> Purchase Items</h2>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="input-group input-group-sm" style="max-width:260px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="search" class="form-control" id="pfProductFilter" placeholder="Filter loaded products…" aria-label="Filter products" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="pf-card-body">
                        <p class="small text-muted mb-2">Select a supplier to load products. Edit price and quantity — totals update live.</p>
                        <div class="pf-products-scroll">
                            <div id="products-container" class="table-responsive">
                                <p class="text-muted mb-0 p-3">Please select a supplier to view available products.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="d-none">
                    <!-- Keep original end-of-form buttons for compatibility; sticky bar is primary UX -->
                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Purchase</button>
                </div>
            </form>

            <!-- Add Payment Section (outside form — preserved from original) -->
            <section class="pf-card mb-3">
                <div class="pf-card-header">
                    <h2><i class="bi bi-cash-coin text-primary"></i> Add Payment</h2>
                </div>
                <div class="pf-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="form-group mb-0">
                                <label for="advance_balance_option" class="form-label">Advance Balance</label>
                                <select class="form-select" id="advance_balance_option" name="advance_balance_option">
                                    <option value="0"><?php echo CURRENCY_SYMBOL; ?> 0 (None)</option>
                                    <option value="100"><?php echo CURRENCY_SYMBOL; ?> 100</option>
                                    <option value="250"><?php echo CURRENCY_SYMBOL; ?> 250</option>
                                    <option value="500"><?php echo CURRENCY_SYMBOL; ?> 500</option>
                                    <option value="1000"><?php echo CURRENCY_SYMBOL; ?> 1,000</option>
                                    <option value="2500"><?php echo CURRENCY_SYMBOL; ?> 2,500</option>
                                    <option value="5000"><?php echo CURRENCY_SYMBOL; ?> 5,000</option>
                                    <option value="custom">Custom amount</option>
                                </select>
                                <div id="advance_balance_custom_wrap" class="mt-2 d-none">
                                    <label for="advance_balance_custom" class="form-label small">Custom amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                                        <input type="number" class="form-control" id="advance_balance_custom" name="advance_balance_custom" step="0.01" min="0" placeholder="0.00" value="">
                                    </div>
                                </div>
                                <div class="small text-muted mt-1">Current: <strong id="advance_balance_display"><?php echo CURRENCY_SYMBOL; ?> 0.00</strong></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="form-group mb-0">
                                <label for="payment_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">CHF</span>
                                    <input type="number" class="form-control" id="payment_amount" name="payment[amount]" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="form-group mb-0">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" id="payment_method" name="payment[method]" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="debit_card">Debit Card</option>
                                    <option value="upi">UPI</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="form-group mb-0">
                                <label for="payment_date" class="form-label">Paid on <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="payment_date" name="payment[date]" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label for="payment_notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="payment_notes" name="payment[notes]" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="add-payment-btn">
                                <i class="fas fa-plus me-1"></i> Add Payment
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="col-12 col-xl-3">
            <aside class="pf-side">
                <div class="pf-card mb-3">
                    <div class="pf-card-header"><h2 style="font-size:0.95rem;">Supplier Summary</h2></div>
                    <div class="pf-card-body">
                        <div class="d-flex gap-2 align-items-center mb-3">
                            <div class="pf-supplier-avatar" id="pfSupAvatar">?</div>
                            <div>
                                <div class="fw-bold" id="pfSupName">Select a supplier</div>
                                <div class="small text-muted" id="pfSupCode">—</div>
                            </div>
                        </div>
                        <ul class="pf-side-list mb-0">
                            <li><span class="k">Phone</span><span class="v" id="pfSupPhone">—</span></li>
                            <li><span class="k">Email</span><span class="v" id="pfSupEmail">—</span></li>
                            <li><span class="k">Address</span><span class="v" id="pfSupAddress">—</span></li>
                        </ul>
                    </div>
                </div>

                <div class="pf-card mb-3">
                    <div class="pf-card-header"><h2 style="font-size:0.95rem;">Purchase Summary</h2></div>
                    <div class="pf-card-body">
                        <div class="pf-summary-line"><span>Lines</span><strong id="pfSumLines">0</strong></div>
                        <div class="pf-summary-line"><span>Total Qty</span><strong id="pfSumQty">0</strong></div>
                        <div class="pf-summary-line"><span>Subtotal</span><strong id="pfSumSub">—</strong></div>
                        <div class="pf-summary-line total"><span>Grand Total</span><strong id="pfSumGrand">—</strong></div>
                        <div class="form-text mt-2 mb-0"><span class="pf-ui-only">Mirrors live product totals.</span></div>
                    </div>
                </div>

                <div class="pf-card mb-3">
                    <div class="pf-card-header"><h2 style="font-size:0.95rem;">Payment Summary</h2></div>
                    <div class="pf-card-body">
                        <div class="pf-summary-line"><span>Advance</span><strong id="pfSumAdvance"><?php echo CURRENCY_SYMBOL; ?> 0.00</strong></div>
                        <div class="pf-summary-line"><span>Status</span><strong id="pfSumPayStatus">Unpaid</strong></div>
                    </div>
                </div>

                <div class="pf-card mb-3">
                    <div class="pf-card-header"><h2 style="font-size:0.95rem;">Quick Tips</h2></div>
                    <div class="pf-card-body">
                        <ul class="small text-muted mb-0 ps-3">
                            <li class="mb-1">Choose supplier to load linked products.</li>
                            <li class="mb-1">Set status to Received when stock should update (backend rules apply).</li>
                            <li class="mb-1">Attach supplier invoice before saving.</li>
                            <li>Payments can be noted via Add Payment.</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="pf-sticky-bar" role="toolbar" aria-label="Purchase actions">
        <div class="pf-sticky-inner">
            <button type="button" class="btn btn-outline-secondary pf-btn" onclick="history.back()">Cancel</button>
            <button type="button" class="btn btn-outline-secondary pf-btn" onclick="document.getElementById('purchaseForm').reset(); location.reload();">Reset</button>
            <button type="button" class="btn btn-outline-primary pf-btn" data-bs-toggle="modal" data-bs-target="#pfPreviewModal">Preview</button>
            <button type="submit" form="purchaseForm" class="btn pf-btn pf-btn-primary">
                <i class="bi bi-check2-circle me-1"></i>Save Purchase
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="pfPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="small text-muted mb-2">Snapshot of current form values</div>
                <ul class="list-unstyled mb-0" id="pfPreviewBody">
                    <li>Select a supplier and products to preview.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="purchaseForm" class="btn btn-primary">Save Purchase</button>
            </div>
        </div>
    </div>
</div>

<script>
// Get currency symbol from PHP
const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';
const BASE_URL = '<?php echo BASE_URL; ?>';
const PF_SUPPLIERS = <?php echo json_encode($supplierJson, JSON_UNESCAPED_UNICODE); ?>;

// Format currency
function formatCurrency(amount) {
    return CURRENCY_SYMBOL + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Handle payment form submission
document.getElementById('add-payment-btn').addEventListener('click', function() {
    const amount = document.getElementById('payment_amount').value;
    const method = document.getElementById('payment_method').value;
    const date = document.getElementById('payment_date').value;
    const notes = document.getElementById('payment_notes').value;
    
    // Basic validation
    if (!amount || !method || !date) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Here you would typically make an AJAX call to save the payment
    // For now, we'll just show a success message
    alert('Payment added successfully!');
    
    // Reset the form
    document.getElementById('payment_amount').value = '';
    document.getElementById('payment_method').selectedIndex = 0;
    document.getElementById('payment_date').value = new Date().toISOString().split('T')[0];
    document.getElementById('payment_notes').value = '';
    
    // Update the advance balance display (running total = current display + new payment)
    const advanceDisplay = document.getElementById('advance_balance_display');
    if (advanceDisplay) {
        const currentBalance = parseFloat(advanceDisplay.textContent.replace(/[^0-9.-]+/g, '')) || 0;
        const newBalance = currentBalance + parseFloat(amount);
        advanceDisplay.textContent = formatCurrency(newBalance);
    }
    if (typeof pfSyncSidebar === 'function') pfSyncSidebar();
});

// Advance balance: get current value from select or custom input
function getAdvanceBalanceValue() {
    const sel = document.getElementById('advance_balance_option');
    const customInput = document.getElementById('advance_balance_custom');
    if (!sel) return 0;
    if (sel.value === 'custom' && customInput) {
        return parseFloat(customInput.value) || 0;
    }
    return parseFloat(sel.value) || 0;
}

// Advance balance: update display when option or custom amount changes
function updateAdvanceBalanceDisplay() {
    const display = document.getElementById('advance_balance_display');
    if (display) display.textContent = formatCurrency(getAdvanceBalanceValue());
    if (typeof pfSyncSidebar === 'function') pfSyncSidebar();
}

document.getElementById('advance_balance_option').addEventListener('change', function() {
    const wrap = document.getElementById('advance_balance_custom_wrap');
    if (wrap) wrap.classList.toggle('d-none', this.value !== 'custom');
    updateAdvanceBalanceDisplay();
});

const advanceCustomInput = document.getElementById('advance_balance_custom');
if (advanceCustomInput) {
    advanceCustomInput.addEventListener('input', updateAdvanceBalanceDisplay);
}

// Initial advance balance display
updateAdvanceBalanceDisplay();

// Function to load products by supplier
async function loadProducts(supplierId) {
    if (!supplierId) {
        $('#products-container').html('<p class="text-muted">Please select a supplier to view products.</p>');
        return;
    }

    try {
        $('#products-container').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> <span class="ms-2">Loading products...</span></div>');
        
        // Get products for the selected supplier
        const response = await fetch(`${BASE_URL}?controller=purchase&action=getProductsBySupplier&supplier_id=${supplierId}`);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server response:', errorText);
            throw new Error(`Server error: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Products response:', result);
        
        if (result && result.success) {
            if (result.products && result.products.length > 0) {
                let html = `
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">`;
                
                // Add all active products
                result.products.forEach(product => {
                    html += `
                            <tr data-product-id="${product.id}">
                                <td>
                                    <strong>${product.name}</strong>`;
                    
                    if (product.status && product.status !== 'active') {
                        html += `
                                    <span class="badge bg-warning text-dark ms-2">${product.status}</span>`;
                    }
                    
                    html += `
                                    <input type="hidden" name="items[${product.id}][product_id]" value="${product.id}">
                                </td>
                                <td>${product.code || 'N/A'}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">${CURRENCY_SYMBOL}</span>
                                        <input type="number" class="form-control price-input" 
                                               name="items[${product.id}][unit_price]" 
                                               value="${parseFloat(product.price || 0).toFixed(2)}" 
                                               min="0" step="0.01" required>
                                    </div>
                                </td>
                                <td style="width: 120px;">
                                    <input type="number" class="form-control form-control-sm quantity-input" 
                                           name="items[${product.id}][quantity]" 
                                           value="1" min="1" required>
                                </td>
                                <td class="total-price" style="width: 120px;">
                                    ${CURRENCY_SYMBOL}${parseFloat(product.price || 0).toFixed(2)}
                                </td>
                            </tr>`;
                });
                
                html += `
                        </tbody>
                    </table>
                </div>`;
                
                $('#products-container').html(html);
                updateTotals();
            } else {
                // No products found
                let message = '<div class="alert alert-info">';
                message += '<i class="fas fa-info-circle me-2"></i>';
                message += result.message || 'No products found for the selected supplier. ';
                message += 'Please add products to this supplier first.';
                message += '</div>';
                
                if (result.debug) {
                    message += '<div class="mt-3 p-3 bg-light rounded">';
                    message += '<h6>Debug Info:</h6>';
                    message += '<pre class="mb-0">' + JSON.stringify(result.debug, null, 2) + '</pre>';
                    message += '</div>';
                }
                
                $('#products-container').html(message);
            }
        } else {
            const errorMsg = result.message || 'No products found for this supplier';
            console.error('Error from server:', errorMsg, result);
            
            let message = '<div class="alert alert-warning">';
            message += '<i class="fas fa-exclamation-triangle me-2"></i>';
            message += errorMsg;
            message += '</div>';
            
            // Add debug info if available
            if (result.debug) {
                message += '<div class="mt-3 small text-muted">';
                message += '<strong>Debug Info:</strong><br>';
                message += `Supplier ID: ${result.debug.supplier_id || 'N/A'}<br>`;
                message += `Products Count: ${result.debug.products_count || 0}<br>`;
                if (result.debug.error) {
                    message += `Error: ${result.debug.error}<br>`;
                }
                message += '</div>';
            }
            
            $('#products-container').html(message);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        
        let errorMessage = '<div class="alert alert-danger">';
        errorMessage += '<i class="fas fa-exclamation-circle me-2"></i>';
        errorMessage += 'Error loading products. ';
        errorMessage += error.message || 'Please try again later.';
        errorMessage += '</div>';
        
        // Add more detailed error information in development
        if (typeof DEBUG_MODE !== 'undefined' && DEBUG_MODE) {
            errorMessage += '<div class="mt-3 small">';
            errorMessage += '<strong>Error Details:</strong><br>';
            errorMessage += error.stack || error.toString();
            errorMessage += '</div>';
        }
        
        $('#products-container').html(errorMessage);
    }
}



// Function to update totals
function updateTotals() {
    let grandTotal = 0;
    
    $('tr[data-product-id]').each(function() {
        const $row = $(this);
        const price = parseFloat($row.find('.price-input').val()) || 0;
        const quantity = parseInt($row.find('.quantity-input').val()) || 0;
        const total = price * quantity;
        
        $row.find('.total-price').text(`${CURRENCY_SYMBOL}${total.toFixed(2)}`);
        grandTotal += total;
    });
    
    // Update grand total row if it exists, otherwise create it
    if ($('#grand-total-row').length) {
        $('#grand-total-amount').text(`${CURRENCY_SYMBOL}${grandTotal.toFixed(2)}`);
    } else if (grandTotal > 0) {
        $('table tbody').append(`
            <tr id="grand-total-row" class="table-active">
                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                <td id="grand-total-amount"><strong>${CURRENCY_SYMBOL}${grandTotal.toFixed(2)}</strong></td>
            </tr>
        `);
    }
    if (typeof pfSyncSidebar === 'function') pfSyncSidebar();
}



// Function to handle form submission
async function submitForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch(BASE_URL + '?controller=purchase&action=store', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message
            alert('Purchase saved successfully!');
            // Optionally reset the form
            form.reset();
        } else {
            // Show error message
            alert(result.message || 'Error saving purchase');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving the purchase');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date picker if flatpickr is available
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#purchase_date", {
            dateFormat: "Y-m-d",
            defaultDate: "<?php echo date('Y-m-d'); ?>"
        });
    }
    
    // Handle supplier change
    $('#supplier_id').on('change', function() {
        const supplierId = $(this).val();
        loadProducts(supplierId);
        if (typeof pfSyncSidebar === 'function') pfSyncSidebar();
    });
    

    
    // Handle price/quantity changes
    $(document).on('input', '.price-input, .quantity-input', function() {
        updateTotals();
    });
    
    // Initialize with current supplier if any
    const currentSupplierId = $('#supplier_id').val();
    if (currentSupplierId) {
        loadProducts(currentSupplierId);
    }

    /* ——— UI-only helpers (do not rename existing functions) ——— */
    window.pfSyncSidebar = function() {
        var sel = document.getElementById('supplier_id');
        var id = sel ? parseInt(sel.value, 10) : 0;
        var s = (PF_SUPPLIERS || []).find(function(x) { return x.id === id; });
        var nameEl = document.getElementById('pfSupName');
        var codeEl = document.getElementById('pfSupCode');
        var phoneEl = document.getElementById('pfSupPhone');
        var emailEl = document.getElementById('pfSupEmail');
        var addrEl = document.getElementById('pfSupAddress');
        var av = document.getElementById('pfSupAvatar');
        if (s) {
            if (nameEl) nameEl.textContent = s.name || 'Supplier';
            if (codeEl) codeEl.textContent = 'ID #' + s.id;
            if (phoneEl) phoneEl.textContent = s.phone || '—';
            if (emailEl) emailEl.textContent = s.email || '—';
            if (addrEl) addrEl.textContent = s.address || '—';
            if (av) av.textContent = (s.name || '?').charAt(0).toUpperCase();
        } else {
            if (nameEl) nameEl.textContent = 'Select a supplier';
            if (codeEl) codeEl.textContent = '—';
            if (phoneEl) phoneEl.textContent = '—';
            if (emailEl) emailEl.textContent = '—';
            if (addrEl) addrEl.textContent = '—';
            if (av) av.textContent = '?';
        }

        var lines = 0, qty = 0, sub = 0;
        $('tr[data-product-id]').each(function() {
            var price = parseFloat($(this).find('.price-input').val()) || 0;
            var q = parseInt($(this).find('.quantity-input').val(), 10) || 0;
            if (q > 0) { lines++; qty += q; sub += price * q; }
        });
        var elLines = document.getElementById('pfSumLines');
        var elQty = document.getElementById('pfSumQty');
        var elSub = document.getElementById('pfSumSub');
        var elGrand = document.getElementById('pfSumGrand');
        if (elLines) elLines.textContent = String(lines);
        if (elQty) elQty.textContent = String(qty);
        if (elSub) elSub.textContent = formatCurrency(sub);
        if (elGrand) elGrand.textContent = formatCurrency(sub);
        var adv = document.getElementById('pfSumAdvance');
        if (adv) adv.textContent = formatCurrency(getAdvanceBalanceValue());
        var paySt = document.getElementById('pfPayStatus');
        var paySum = document.getElementById('pfSumPayStatus');
        if (paySt && paySum) paySum.textContent = paySt.options[paySt.selectedIndex].text;
    };

    var filter = document.getElementById('pfProductFilter');
    if (filter) {
        filter.addEventListener('input', function() {
            var q = (filter.value || '').toLowerCase().trim();
            $('tr[data-product-id]').each(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(!q || text.indexOf(q) !== -1);
            });
        });
    }

    var docInput = document.getElementById('document');
    var docMeta = document.getElementById('pfDocMeta');
    if (docInput && docMeta) {
        docInput.addEventListener('change', function() {
            var f = docInput.files && docInput.files[0];
            docMeta.textContent = f ? (f.name + ' · ' + Math.round(f.size / 1024) + ' KB') : 'No file selected';
        });
    }

    var draftBtn = document.getElementById('pfSaveDraftBtn');
    if (draftBtn) {
        draftBtn.addEventListener('click', function() {
            var st = document.getElementById('status');
            if (st) st.value = 'pending';
            alert('Status set to Pending — click Save Purchase to submit.');
        });
    }

    var previewModal = document.getElementById('pfPreviewModal');
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function() {
            var body = document.getElementById('pfPreviewBody');
            if (!body) return;
            var sel = document.getElementById('supplier_id');
            var supplierText = sel && sel.selectedIndex >= 0 ? sel.options[sel.selectedIndex].text : '—';
            var lines = $('tr[data-product-id]').length;
            body.innerHTML =
                '<li><strong>Supplier:</strong> ' + supplierText + '</li>' +
                '<li><strong>Date:</strong> ' + (document.getElementById('purchase_date') || {}).value + '</li>' +
                '<li><strong>Status:</strong> ' + (document.getElementById('status') || {}).value + '</li>' +
                '<li><strong>Product lines:</strong> ' + lines + '</li>' +
                '<li><strong>Grand total:</strong> ' + (document.getElementById('pfSumGrand') || {}).textContent + '</li>';
        });
    }

    if (typeof pfSyncSidebar === 'function') pfSyncSidebar();
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
