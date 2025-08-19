<?php
// Get form data from session if it exists (for repopulating after validation errors)
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

// Default values from the purchase record
$supplierId = $formData['supplier_id'] ?? $purchase['supplier_id'];
$purchaseDate = $formData['purchase_date'] ?? $purchase['purchase_date'];
$status = $formData['status'] ?? $purchase['status'];
$notes = $formData['notes'] ?? $purchase['notes'];
$items = $formData['items'] ?? $purchase['items'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>?controller=purchase&action=update&id=<?php echo $purchase['id']; ?>" method="POST" id="purchaseForm">
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
                        <div id="purchaseItems">
                            <?php foreach ($items as $index => $item): ?>
                                <?php 
                                    $productId = $item['product_id'] ?? '';
                                    $quantity = $item['quantity'] ?? 1;
                                    $unitPrice = $item['unit_price'] ?? '';
                                    $productName = '';
                                    $productSku = '';
                                    
                                    // Find product details for display
                                    foreach ($products as $product) {
                                        if ($product['id'] == $productId) {
                                            $productName = $product['name'];
                                            $productSku = $product['sku'];
                                            break;
                                        }
                                    }
                                ?>
                                <div class="row mb-3 item-row" data-index="<?php echo $index; ?>">
                                    <div class="col-md-5">
                                        <select class="form-select product-select" name="items[<?php echo $index; ?>][product_id]" required>
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $product): ?>
                                                <option value="<?php echo $product['id']; ?>" 
                                                    data-price="<?php echo $product['purchase_price']; ?>"
                                                    <?php echo ($productId == $product['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($product['name'] . ' (' . $product['sku'] . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control quantity" name="items[<?php echo $index; ?>][quantity]" 
                                               min="1" value="<?php echo $quantity; ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-text"><?php echo CURRENCY_SYMBOL; ?></span>
                                            <input type="number" class="form-control unit-price" name="items[<?php echo $index; ?>][unit_price]" 
                                                   min="0" step="0.01" value="<?php echo $unitPrice; ?>" required>
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
                    <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Purchase</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for new item row -->
<template id="itemTemplate">
    <div class="row mb-3 item-row" data-index="{{index}}">
        <div class="col-md-5">
            <select class="form-select product-select" name="items[{{index}}][product_id]" required>
                <option value="">Select Product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" 
                            data-price="<?php echo $product['purchase_price']; ?>">
                        <?php echo htmlspecialchars($product['name'] . ' (' . $product['sku'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control quantity" name="items[{{index}}][quantity]" min="1" value="1" required>
        </div>
        <div class="col-md-3">
            <div class="input-group">
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

// Format number as currency
function formatCurrency(amount) {
    return CURRENCY_SYMBOL + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

document.addEventListener('DOMContentLoaded', function() {
    const itemTemplate = document.getElementById('itemTemplate');
    const purchaseItems = document.getElementById('purchaseItems');
    const addItemBtn = document.getElementById('addItemBtn');
    let itemCount = <?php echo count($items); ?>;

    // Add new item row
    function addItemRow() {
        const newRow = itemTemplate.innerHTML.replace(/\{\{index\}\}/g, itemCount);
        const temp = document.createElement('div');
        temp.innerHTML = newRow;
        purchaseItems.appendChild(temp.firstElementChild);
        itemCount++;
        updateRemoveButtons();
        calculateTotals();
    }

    // Update remove buttons state
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-item');
        removeButtons.forEach((btn, index) => {
            btn.disabled = removeButtons.length <= 1;
        });
    }

    // Calculate totals
    function calculateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            subtotal += quantity * unitPrice;
        });
        
        const tax = 0; // You can add tax calculation here
        const shipping = 0; // You can add shipping calculation here
        const total = subtotal + tax + shipping;
        
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('shipping').textContent = '$' + shipping.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }

    // Set unit price when product is selected
    purchaseItems.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const unitPrice = selectedOption ? parseFloat(selectedOption.dataset.price) || 0 : 0;
            const row = e.target.closest('.item-row');
            const unitPriceInput = row.querySelector('.unit-price');
            
            // Only set the price if it's not already set
            if (!unitPriceInput.value) {
                unitPriceInput.value = unitPrice.toFixed(2);
            }
            
            calculateTotals();
        }
    });

    // Recalculate when quantity or unit price changes
    purchaseItems.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
            calculateTotals();
        }
    });

    // Remove item row
    purchaseItems.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                updateRemoveButtons();
                calculateTotals();
            }
        }
    });

    // Add new item
    addItemBtn.addEventListener('click', addItemRow);

    // Initialize
    updateRemoveButtons();
    calculateTotals();
    
    // Initialize date picker
    flatpickr("#purchase_date", {
        dateFormat: "Y-m-d",
        defaultDate: "<?php echo $purchaseDate; ?>"
    });
});
</script>
