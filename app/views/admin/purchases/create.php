<?php
// Get form data from session if it exists (for repopulating after validation errors)
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

// Default values
$supplierId = $formData['supplier_id'] ?? '';
$purchaseDate = $formData['purchase_date'] ?? date('Y-m-d');
$status = $formData['status'] ?? 'pending';
$notes = $formData['notes'] ?? '';
$items = $formData['items'] ?? [['product_id' => '', 'quantity' => 1, 'unit_price' => '']];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>?controller=purchase&action=store" method="POST" id="purchaseForm">
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
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" 
                                   value="<?php echo $purchaseDate; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="ordered" <?php echo ($status === 'ordered') ? 'selected' : ''; ?>>Ordered</option>
                                <option value="received" <?php echo ($status === 'received') ? 'selected' : ''; ?>>Received</option>
                                <option value="cancelled" <?php echo ($status === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"><?php echo htmlspecialchars($notes); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Purchase Items</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <style>
                            .item-row {
                                margin-bottom: 1rem;
                            }
                            
                            .product-select-container {
                                position: relative;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            }
                            
                            .product-image-preview {
                                flex-shrink: 0;
                            }
                            
                            .product-select {
                                flex-grow: 1;
                            }
                        </style>
                        <div id="purchaseItems">
                            <?php foreach ($items as $index => $item): ?>
                                <div class="row mb-3 item-row" data-index="<?php echo $index; ?>">
                                    <div class="col-md-5">
                                        <div class="product-select-container">
                                            <select class="form-select product-select" name="items[<?php echo $index; ?>][product_id]" required>
                                                <option value="">Select Product</option>
                                                <?php foreach ($products as $product): 
                                                    // Get product image path
                                                    $imagePath = BASE_URL . 'assets/img/no-image.jpg';
                                                    if (!empty($product['image'])) {
                                                        $possiblePaths = [
                                                            'uploads/products/' . $product['image'],
                                                            $product['image'],
                                                            'public/uploads/products/' . $product['image']
                                                        ];
                                                        
                                                        foreach ($possiblePaths as $path) {
                                                            $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/' . ltrim($path, '/\\'));
                                                            if (file_exists($fullPath)) {
                                                                $imagePath = BASE_URL . ltrim($path, '/\\');
                                                                break;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <option 
                                                    value="<?php echo $product['id']; ?>" 
                                                    data-price="<?php echo $product['purchase_price']; ?>"
                                                    data-image="<?php echo $imagePath; ?>"
                                                    data-sku="<?php echo htmlspecialchars($product['sku']); ?>"
                                                    <?php echo (isset($item['product_id']) && $item['product_id'] == $product['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($product['name'] . ' (' . $product['sku'] . ')'); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="product-image-preview">
                                                <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="Product Image" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;" id="preview-<?php echo $index; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control quantity" name="items[<?php echo $index; ?>][quantity]" 
                                               min="1" value="<?php echo $item['quantity'] ?? 1; ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                                            <input type="number" class="form-control unit-price" name="items[<?php echo $index; ?>][unit_price]" 
                                                   min="0" step="0.01" value="<?php echo format_currency($item['unit_price'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-item" <?php echo ($index === 0) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Subtotal:</strong>
                                    <span id="subtotal"><?php echo CURRENCY_SYMBOL; ?>0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Tax (0%):</strong>
                                    <span id="tax"><?php echo CURRENCY_SYMBOL; ?>0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Shipping:</strong>
                                    <span id="shipping"><?php echo CURRENCY_SYMBOL; ?>0.00</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <strong>Total:</strong>
                                    <strong id="total"><?php echo CURRENCY_SYMBOL; ?>0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Purchase</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for new item row -->
<template id="itemTemplate">
    <div class="row mb-3 item-row" data-index="{{index}}">
        <div class="col-md-5">
            <div class="product-select-container">
            <select class="form-select product-select" name="items[{{index}}][product_id]" required>
                <option value="">Select Product</option>
                <?php foreach ($products as $product): 
                    // Get product image path for template
                    $imagePath = BASE_URL . 'assets/img/no-image.jpg';
                    if (!empty($product['image'])) {
                        $possiblePaths = [
                            'uploads/products/' . $product['image'],
                            $product['image'],
                            'public/uploads/products/' . $product['image']
                        ];
                        
                        foreach ($possiblePaths as $path) {
                            $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/' . ltrim($path, '/\\'));
                            if (file_exists($fullPath)) {
                                $imagePath = BASE_URL . ltrim($path, '/\\');
                                break;
                            }
                        }
                    }
                ?>
                <option 
                    value="<?php echo $product['id']; ?>" 
                    data-price="<?php echo $product['purchase_price']; ?>"
                    data-image="<?php echo $imagePath; ?>"
                    data-sku="<?php echo htmlspecialchars($product['sku']); ?>"
                    <?php echo (isset($item['product_id']) && $item['product_id'] == $product['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($product['name'] . ' (' . $product['sku'] . ')'); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="product-image-preview">
                <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="Product Image" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;" id="preview-{{index}}">
            </div>
        </div>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control quantity" name="items[{{index}}][quantity]" min="1" value="1" required>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                <span class="input-group-text">$</span>
                <input type="number" class="form-control unit-price" name="items[{{index}}][unit_price]" 
                       min="0" step="0.01" required>
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger remove-item">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
// Get currency symbol from PHP
const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';

document.addEventListener('DOMContentLoaded', function() {
    const itemTemplate = document.getElementById('itemTemplate');
    const purchaseItems = document.getElementById('purchaseItems');
    const addItemBtn = document.getElementById('addItemBtn');
    let itemCount = <?php echo !empty($items) ? count($items) : 0; ?>;

    // Format number as currency
    function formatCurrency(amount) {
        return CURRENCY_SYMBOL + parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Add new item row
    function addItemRow() {
        try {
            const newRow = itemTemplate.innerHTML.replace(/\{\{index\}\}/g, itemCount);
            const temp = document.createElement('div');
            temp.innerHTML = newRow.trim();
            
            // Ensure we're adding a proper DOM element
            const newElement = temp.firstElementChild;
            if (newElement) {
                purchaseItems.appendChild(newElement);
                itemCount++;
                updateRemoveButtons();
                calculateTotals();
                
                // Initialize any new select2 elements if needed
                if (typeof $.fn.select2 !== 'undefined') {
                    $(newElement).find('select').select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });
                }
            }
        } catch (error) {
            console.error('Error adding item row:', error);
        }
    }

    // Update remove buttons state
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach((btn) => {
            btn.disabled = removeButtons.length <= 1;
        });
    }

    // Calculate totals
    function calculateTotals() {
        try {
            let subtotal = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const quantityInput = row.querySelector('.quantity');
                const unitPriceInput = row.querySelector('.unit-price');
                
                if (quantityInput && unitPriceInput) {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    subtotal += quantity * unitPrice;
                    
                    // Update row total if there's a display element for it
                    const rowTotal = row.querySelector('.row-total');
                    if (rowTotal) {
                        rowTotal.textContent = formatCurrency(quantity * unitPrice);
                    }
                }
            });
            
            const tax = 0; // You can add tax calculation here
            const shipping = 0; // You can add shipping calculation here
            const total = subtotal + tax + shipping;
            
            // Update summary
            document.getElementById('subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('tax').textContent = formatCurrency(tax);
            document.getElementById('shipping').textContent = formatCurrency(shipping);
            document.getElementById('total').textContent = formatCurrency(total);
        } catch (error) {
            console.error('Error calculating totals:', error);
        }
    }

    // Initialize event delegation for dynamic elements
    // Function to update product image preview
    function updateProductPreview(selectElement) {
        const row = selectElement.closest('.item-row');
        const index = row ? row.getAttribute('data-index') : null;
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const imageUrl = selectedOption ? selectedOption.getAttribute('data-image') : '';
        
        if (index !== null) {
            const previewImg = document.getElementById(`preview-${index}`);
            if (previewImg) {
                previewImg.src = imageUrl || '<?php echo BASE_URL; ?>assets/img/no-image.jpg';
            }
        }
    }
    
    // Initialize product previews for existing items
    function initProductPreviews() {
        document.querySelectorAll('.product-select').forEach(select => {
            updateProductPreview(select);
        });
    }
    
    function initEventDelegation() {
        // Handle product selection
        purchaseItems.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                try {
                    // Update the product image preview
                    updateProductPreview(e.target);
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const unitPrice = selectedOption ? parseFloat(selectedOption.dataset.price) || 0 : 0;
                    const row = e.target.closest('.item-row');
                    if (row) {
                        const unitPriceInput = row.querySelector('.unit-price');
                        if (unitPriceInput) {
                            unitPriceInput.value = unitPrice.toFixed(2);
                            calculateTotals();
                        }
                    }
                } catch (error) {
                    console.error('Error handling product selection:', error);
                }
            }
        });

        // Handle quantity/price changes
        purchaseItems.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
                calculateTotals();
            }
        });

        // Handle remove item
        purchaseItems.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-item');
            if (removeBtn && !removeBtn.disabled) {
                const row = removeBtn.closest('.item-row');
                if (row && document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    updateRemoveButtons();
                    calculateTotals();
                }
            }
        });
    }

    // Initialize the page
    function init() {
        // Initialize event delegation
        initEventDelegation();
        
        // Add event listener for the add item button
        if (addItemBtn) {
            addItemBtn.addEventListener('click', addItemRow);
        }
        
        // Initialize product previews
        initProductPreviews();
        
        // Initialize remove buttons
        updateRemoveButtons();
        
        // Calculate totals
        calculateTotals();
        
        // Initialize date picker if flatpickr is available
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#purchase_date", {
                dateFormat: "Y-m-d",
                defaultDate: "<?php echo $purchaseDate; ?>"
            });
        }
    }

    // Start the initialization
    init();
});
</script>
