<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Products</h3>
                    <div>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i> Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Products Summary</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Total Products</h6>
                                            <p class="h3 mb-0">
                                                <?php 
                                                $totalProducts = 0;
                                                if (isset($products['total_records'])) {
                                                    $totalProducts = $products['total_records'];
                                                } elseif (isset($products['total'])) {
                                                    $totalProducts = $products['total'];
                                                } elseif (isset($products['data']) && is_array($products['data'])) {
                                                    $totalProducts = count($products['data']);
                                                }
                                                echo number_format($totalProducts);
                                                ?>
                                            </p>
                                        </div>
                                        <i class="fas fa-boxes fa-3x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Summary</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Total Stock Quantity</h6>
                                            <p class="h3 mb-0">
                                                <?php 
                                                $totalStock = 0;
                                                $totalStockValue = 0;
                                                
                                                if (isset($products['data']) && is_array($products['data'])) {
                                                    foreach ($products['data'] as $product) {
                                                        $quantity = (int)($product['stock_quantity'] ?? 0);
                                                        $price = (float)($product['price'] ?? 0);
                                                        $totalStock += $quantity;
                                                        $totalStockValue += ($quantity * $price);
                                                    }
                                                }
                                                echo number_format($totalStock);
                                                ?>
                                            </p>
                                            <h6 class="mb-0 mt-2">Total Stock Value</h6>
                                            <p class="h4 mb-0 text-success fw-bold">
                                                <?php echo formatPrice($totalStockValue); ?>
                                            </p>
                                        </div>
                                        <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="alert-messages">
                        <?php flash('product_success', '', 'alert alert-success'); ?>
                        <?php flash('product_error', '', 'alert alert-danger'); ?>
                    </div>
                    
                    <?php if(empty($products['data'])): ?>
                        <div class="alert alert-info">No products found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Sale Price</th>
                                        <th>Stock</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products['data'] as $product): ?>
                                        <tr id="product-row-<?php echo $product['id']; ?>">
                                            <td><?php echo $product['id']; ?></td>
                                            <td>
                                                <?php if(!empty($product['image'])): ?>
                                                    <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="50" class="img-thumbnail">
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="No Image" width="50" class="img-thumbnail">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                            <td><?php echo formatPrice($product['price']); ?></td>
                                            <td>
                                                <?php if(!empty($product['sale_price'])): ?>
                                                    <?php echo formatPrice($product['sale_price']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo ($product['stock_quantity'] > 0) ? 'success' : 'danger'; ?>">
                                                    <?php echo $product['stock_quantity']; ?>
                                                </span>
                                            </td>
                                            <td class="text-nowrap">
                                                <?php 
                                                $stockValue = (float)$product['stock_quantity'] * (float)$product['price'];
                                                echo formatPrice($stockValue);
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo ($product['status'] == 'active') ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger delete-product" 
                                                            data-id="<?php echo $product['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo getPaginationLinks($products['current_page'], $products['total_pages'], BASE_URL . '?controller=product&action=adminIndex'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="productName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                <input type="hidden" id="productId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete modal elements
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const productNameEl = document.getElementById('productName');
    const productIdEl = document.getElementById('productId');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const alertMessages = document.getElementById('alert-messages');
    
    // Handle delete button click
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            
            productIdEl.value = productId;
            productNameEl.textContent = '"' + productName + '"';
            
            // Reset modal state
            const spinner = confirmDeleteBtn.querySelector('.spinner-border');
            const btnText = confirmDeleteBtn.querySelector('.btn-text');
            spinner.classList.add('d-none');
            btnText.textContent = 'Delete';
            confirmDeleteBtn.disabled = false;
            
            // Show modal
            deleteModal.show();
        });
    });
    
    // Handle confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
        const productId = productIdEl.value;
        if (!productId) return;
        
        // Show loading state
        const spinner = this.querySelector('.spinner-border');
        const btnText = this.querySelector('.btn-text');
        spinner.classList.remove('d-none');
        btnText.textContent = 'Deleting...';
        this.disabled = true;
        
        // Send delete request
        fetch(`?controller=product&action=delete&id=${productId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the deleted row
                const row = document.getElementById(`product-row-${productId}`);
                if (row) {
                    row.remove();
                    
                    // Show success message
                    showAlert('Product deleted successfully', 'success');
                    
                    // Check if table is empty
                    const tbody = document.querySelector('table tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload(); // Reload if no more products
                    }
                }
            } else {
                throw new Error(data.message || 'Failed to delete product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'An error occurred while deleting the product', 'danger');
        })
        .finally(() => {
            // Hide modal
            deleteModal.hide();
        });
    });
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        // Remove any existing alerts
        const existingAlerts = alertMessages.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to container
        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
    
    // Close button for alerts
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn-close')) {
            const alert = e.target.closest('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
