<?php
// Set page title
$pageTitle = 'Adjust Stock - ' . $product['name'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $pageTitle; ?></h1>
        <a href="<?php echo BASE_URL; ?>?controller=stock" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Stock
        </a>
    </div>

    <?php flash('error'); ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Adjust Stock</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Product:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
                            <p class="mb-1"><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></p>
                            <p class="mb-1"><strong>Current Stock:</strong> 
                                <span class="badge bg-<?php echo ($product['stock_quantity'] > 0) ? 'success' : 'danger'; ?>">
                                    <?php echo $product['stock_quantity']; ?> in stock
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-<?php echo ($product['status'] === 'active') ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($product['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=stock&action=update" method="POST" id="adjustStockForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="action" class="form-label">Action</label>
                            <select class="form-select" id="action" name="action" required>
                                <option value="add">Add to stock</option>
                                <option value="subtract">Remove from stock</option>
                                <option value="set">Set stock to specific amount</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   min="0" step="0.01" required>
                            <div class="form-text">Enter the quantity to add, remove, or set</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                     placeholder="Reason for stock adjustment"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentMovements)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentMovements as $movement): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <?php 
                                            $actionText = '';
                                            switch ($movement['action']) {
                                                case 'add':
                                                    $actionText = 'Added';
                                                    $textClass = 'text-success';
                                                    $icon = 'plus';
                                                    break;
                                                case 'subtract':
                                                    $actionText = 'Removed';
                                                    $textClass = 'text-danger';
                                                    $icon = 'minus';
                                                    break;
                                                case 'set':
                                                    $actionText = 'Set';
                                                    $textClass = 'text-info';
                                                    $icon = 'equals';
                                                    break;
                                            }
                                            ?>
                                            <i class="fas fa-<?php echo $icon; ?> me-1 <?php echo $textClass; ?>"></i>
                                            <?php echo $actionText; ?> 
                                            <strong class="<?php echo $textClass; ?>"><?php echo abs($movement['adjustment']); ?></strong> 
                                            units
                                        </h6>
                                        <small class="text-muted">
                                            <?php echo timeAgo($movement['created_at']); ?>
                                        </small>
                                    </div>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <?php if (!empty($movement['notes'])): ?>
                                                <?php echo htmlspecialchars($movement['notes']); ?>
                                            <?php else: ?>
                                                <em>No notes provided</em>
                                            <?php endif; ?>
                                        </small>
                                    </p>
                                    <small class="text-muted">
                                        By: <?php echo !empty($movement['user_name']) ? htmlspecialchars($movement['user_name']) : 'System'; ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="<?php echo BASE_URL; ?>?controller=stock&action=history&id=<?php echo $product['id']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                View Full History
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No recent stock movements found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actionSelect = document.getElementById('action');
    const quantityInput = document.getElementById('quantity');
    
    // Set minimum value based on action
    function updateQuantityMin() {
        if (actionSelect.value === 'subtract') {
            quantityInput.min = 0.01;
            quantityInput.max = <?php echo $product['stock_quantity']; ?>;
            quantityInput.placeholder = 'Max: ' + <?php echo $product['stock_quantity']; ?>;
        } else {
            quantityInput.min = 0.01;
            quantityInput.max = '';
            quantityInput.placeholder = 'Enter quantity';
        }
    }
    
    // Update on page load
    updateQuantityMin();
    
    // Update when action changes
    actionSelect.addEventListener('change', updateQuantityMin);
    
    // Form validation
    document.getElementById('adjustStockForm').addEventListener('submit', function(e) {
        const quantity = parseFloat(quantityInput.value);
        
        if (isNaN(quantity) || quantity <= 0) {
            e.preventDefault();
            alert('Please enter a valid quantity greater than 0');
            return false;
        }
        
        if (actionSelect.value === 'subtract' && quantity > <?php echo $product['stock_quantity']; ?>) {
            e.preventDefault();
            alert('Cannot remove more than the current stock quantity');
            return false;
        }
        
        return true;
    });
});
</script>
