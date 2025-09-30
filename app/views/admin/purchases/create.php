<?php
// Get form data from session if it exists (for repopulating after validation errors)
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

// Default values
$supplierId = $formData['supplier_id'] ?? '';
$items = $formData['items'] ?? [['product_id' => '', 'quantity' => 1, 'unit_price' => '']];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Admin
        </a>
    </div>

    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="purchaseForm" onsubmit="submitForm(event)" enctype="multipart/form-data">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
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
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" required 
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Please select</option>
                                <option value="received">Received</option>
                                <option value="pending">Pending</option>
                                <option value="ordered">Ordered</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="document" class="form-label">Attach Document</label>
                            <input type="file" class="form-control" id="document" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG</small>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Products</h5>
                            </div>
                            <div class="card-body">
                                <div id="products-container">
                                    <p class="text-muted">Please select a supplier to view available products.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Purchase</button>
                </div>

                <!-- Payment Form Section -->
                
            </form>

<form >
<div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Add Payment</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">Advance Balance:</label>
                                    <div class="form-control-plaintext">CHF 0.00</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="payment_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="number" class="form-control" id="payment_amount" name="payment[amount]" step="0.01" min="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
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
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="payment_date" class="form-label">Paid on <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="payment_date" name="payment[date]" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="payment_notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="payment_notes" name="payment[notes]" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="add-payment-btn">
                                <i class="fas fa-plus me-1"></i> Add Payment
                            </button>
                        </div>
                    </div>
                </div>
</form>


        </div>


        
    </div>

    
</div>




<script>
// Get currency symbol from PHP
const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';
const BASE_URL = '<?php echo BASE_URL; ?>';

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
    
    // Update the advance balance (in a real app, this would come from the server)
    const advanceBalance = document.querySelector('.form-control-plaintext');
    const currentBalance = parseFloat(advanceBalance.textContent.replace(/[^0-9.-]+/g,"")) || 0;
    const newBalance = currentBalance + parseFloat(amount);
    advanceBalance.textContent = formatCurrency(newBalance);
});

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
});
</script>
