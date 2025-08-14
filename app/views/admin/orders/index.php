<?php 
require_once APP_PATH . 'views/admin/layouts/header.php';

// Get orders data from controller
$orders = $data['orders'] ?? [];

// Function to get status badge class
function getStatusBadgeClass($status) {
    $statusClasses = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    
    return $statusClasses[strtolower($status)] ?? 'secondary';
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Orders List</h3>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders['data'])): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No orders found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders['data'] as $order): ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($order['items'])) {
                                                    foreach ($order['items'] as $item) {
                                                        echo '<div class="mb-2">' . htmlspecialchars($item['product_name'] ?? 'Product') . '</div>';
                                                    }
                                                } else {
                                                    echo 'No items';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if (!empty($order['items'])) {
                                                    foreach ($order['items'] as $item) {
                                                        echo '<div class="mb-2">₹' . number_format($item['price'] ?? 0, 2) . '</div>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if (!empty($order['items'])) {
                                                    foreach ($order['items'] as $item) {
                                                        echo '<div class="mb-2">' . ($item['quantity'] ?? 1) . '</div>';
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="#" class="btn btn-sm btn-info text-white edit-order" 
                                                       data-id="<?php echo $order['id']; ?>"
                                                       data-status="<?php echo htmlspecialchars($order['status']); ?>"
                                                       data-payment-status="<?php echo htmlspecialchars($order['payment_status'] ?? 'pending'); ?>"
                                                       title="Edit Order">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger delete-order" 
                                                            data-order-id="<?php echo $order['id']; ?>"
                                                            title="Delete Order">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Update Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editOrderForm" method="POST" action="">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="order_id" id="editOrderId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Order Status</label>
                        <select class="form-select" id="orderStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentStatus" class="form-label">Payment Status</label>
                        <select class="form-select" id="paymentStatus" name="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="refunded">Refunded</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit order button click
    document.querySelectorAll('.edit-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const id = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            const paymentStatus = this.getAttribute('data-payment-status');
            
            // Set form values
            document.getElementById('editOrderId').value = id;
            document.getElementById('orderStatus').value = status;
            document.getElementById('paymentStatus').value = paymentStatus;
            
            // Set form action
            document.getElementById('editOrderForm').action = '<?php echo BASE_URL; ?>order/updateStatus/' + id;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
            modal.show();
        });
    });

    // Handle delete order button click
    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get order ID and row element
            const orderId = this.getAttribute('data-order-id');
            const row = this.closest('tr');
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Show confirmation dialog
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Disable button and show loading state
                const deleteButton = this;
                deleteButton.disabled = true;
                const originalHtml = deleteButton.innerHTML;
                deleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                
                // Create form data
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('csrf_token', csrfToken);
                
                // Submit the delete request
                fetch(`<?php echo BASE_URL; ?>?controller=order&action=delete&id=${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `_method=DELETE&csrf_token=${encodeURIComponent(csrfToken)}`
                })
                .then(response => {
                    console.log('Raw response:', response);
                    if (!response.ok) {
                        return response.json().then(err => {
                            console.error('Error response:', err);
                            const error = new Error(err.message || 'Failed to delete order');
                            error.response = response;
                            throw error;
                        }).catch(() => {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json().catch(() => {
                        // If response is not JSON, but request was successful
                        return { success: true };
                    });
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data && data.success) {
                        // Remove the row from the table with animation
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        
                        setTimeout(() => {
                            row.remove();
                            showAlert('Order deleted successfully!', 'success');
                            
                            // If no more rows, reload the page to refresh the list
                            if (!document.querySelector('table tbody tr')) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        }, 300);
                    } else {
                        throw new Error(data?.message || 'Failed to delete order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'An error occurred while deleting the order';
                    
                    if (error.response) {
                        try {
                            const contentType = error.response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return error.response.json().then(errData => {
                                    errorMessage = errData.message || errorMessage;
                                    showAlert(`Error: ${errorMessage}`, 'danger');
                                });
                            } else {
                                errorMessage = `Server error: ${error.response.status} ${error.response.statusText}`;
                            }
                        } catch (e) {
                            console.error('Error parsing error response:', e);
                            errorMessage = 'Failed to process server response';
                        }
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    
                    showAlert(`Error: ${errorMessage}`, 'danger');
                    
                    // Reset button state
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = originalHtml;
                });
            }
            
            // Helper function to handle response
            function handleResponse(response) {
                if (!response.ok) {
                    return response.json().then(err => {
                        const error = new Error(err.message || 'Network response was not ok');
                        error.response = response;
                        throw error;
                    }).catch(() => {
                        throw new Error('Network response was not ok');
                    });
                }
                return response.json().catch(() => ({}));
            }
            
            // Helper function to show alerts
            function showAlert(message, type = 'info') {
                // Remove any existing alerts
                document.querySelectorAll('.alert-dismissible').forEach(alert => {
                    alert.remove();
                });
                
                // Create new alert
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.role = 'alert';
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Insert alert before the table
                const table = document.querySelector('.table');
                if (table && table.parentNode) {
                    table.parentNode.insertBefore(alert, table);
                    
                    // Auto-hide alert after 5 seconds
                    setTimeout(() => {
                        if (alert) {
                            alert.classList.remove('show');
                            setTimeout(() => alert.remove(), 150);
                        }
                    }, 5000);
                }
            }
        });
    });
    
    // Handle edit order form submission
    document.getElementById('editOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const orderId = formData.get('order_id');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.role = 'alert';
                alert.innerHTML = `
                    ${data.message || 'Product updated successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Insert alert before the table
                const table = document.querySelector('.table');
                table.parentNode.insertBefore(alert, table);
                
                // Update the row with new data
                const row = document.querySelector(`button[data-id="${data.product.id}"]`).closest('tr');
                if (row) {
                    row.cells[0].textContent = data.product.name;
                    row.cells[1].textContent = '₹' + parseFloat(data.product.price).toFixed(2);
                    row.cells[2].textContent = data.product.stock;
                    
                    // Update the edit button data attributes
                    const editButton = row.querySelector('.edit-product');
                    if (editButton) {
                        editButton.setAttribute('data-name', data.product.name);
                        editButton.setAttribute('data-price', data.product.price);
                        editButton.setAttribute('data-stock', data.product.stock);
                    }
                }
                
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                modal.hide();
            } else {
                // Show error message
                alert(data.message || 'Failed to update product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the product');
        })
        .finally(() => {
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
