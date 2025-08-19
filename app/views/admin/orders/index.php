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
                    
                    <!-- Page size selector -->
                    <?php 
                        $filters = $data['filters'] ?? []; 
                        $currentLimit = isset($filters['limit']) ? (int)$filters['limit'] : 20; 
                        $allowedLimits = [10,20,50,100];
                    ?>
                    <form class="row g-2 align-items-center mb-3" method="GET" action="<?php echo BASE_URL; ?>">
                        <input type="hidden" name="controller" value="order">
                        <input type="hidden" name="action" value="adminIndex">
                        <?php if (!empty($filters['status'])): ?>
                            <input type="hidden" name="status" value="<?php echo htmlspecialchars($filters['status']); ?>">
                        <?php endif; ?>
                        <?php if (!empty($filters['payment_status'])): ?>
                            <input type="hidden" name="payment_status" value="<?php echo htmlspecialchars($filters['payment_status']); ?>">
                        <?php endif; ?>
                        <?php if (!empty($filters['search'])): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>">
                        <?php endif; ?>
                        <div class="col-auto ms-auto">
                            <label for="limit" class="form-label me-2 mb-0 small text-muted">Show</label>
                            <select id="limit" name="limit" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                <?php foreach ($allowedLimits as $opt): ?>
                                    <option value="<?php echo $opt; ?>" <?php echo $opt === $currentLimit ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="small text-muted ms-1">per page</span>
                        </div>
                    </form>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-hover responsive-table">
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
                                        <td colspan="6" class="text-center py-4">No orders found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders['data'] as $order): ?>
                                        <tr>
                                            <?php 
                                            // Build details URL preserving filters and page size
                                            $detailQuery = [
                                                'controller' => 'order',
                                                'action' => 'adminShow',
                                                'id' => $order['id']
                                            ];
                                            if (!empty($filters['limit']))          $detailQuery['limit'] = (int)$filters['limit'];
                                            if (!empty($filters['status']))         $detailQuery['status'] = $filters['status'];
                                            if (!empty($filters['payment_status'])) $detailQuery['payment_status'] = $filters['payment_status'];
                                            if (!empty($filters['search']))         $detailQuery['search'] = $filters['search'];
                                            $detailUrl = BASE_URL . '?' . http_build_query($detailQuery);
                                        ?>
                                        <td data-label="Order #">
                                            <a href="<?php echo $detailUrl; ?>" class="text-decoration-none">#<?php echo htmlspecialchars($order['order_number'] ?? $order['id']); ?></a>
                                        </td>
                                            <td data-label="Date"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td data-label="Product Name">
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
                                            <td data-label="Price">
                                                <?php 
                                                if (!empty($order['items'])) {
                                                    foreach ($order['items'] as $item) {
                                                        echo '<div class="mb-2">₹' . number_format($item['price'] ?? 0, 2) . '</div>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td data-label="Quantity">
                                                <?php 
                                                if (!empty($order['items'])) {
                                                    foreach ($order['items'] as $item) {
                                                        echo '<div class="mb-2">' . ($item['quantity'] ?? 1) . '</div>';
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td data-label="Actions">
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

                    <?php 
                    // Pagination (Next/Prev) — expects $orders from controller paginate()
                    $currentPage = isset($orders['current_page']) ? (int)$orders['current_page'] : 1;
                    $totalPages  = isset($orders['total_pages']) ? (int)$orders['total_pages'] : 1;
                    $totalItems  = isset($orders['total']) ? (int)$orders['total'] : (isset($orders['data']) ? count($orders['data']) : 0);

                    // Build base URL preserving filters
                    $filters = $data['filters'] ?? [];
                    $query = [
                        'controller' => 'order',
                        'action' => 'adminIndex',
                    ];
                    if (!empty($filters['status']))         $query['status'] = $filters['status'];
                    if (!empty($filters['payment_status'])) $query['payment_status'] = $filters['payment_status'];
                    if (!empty($filters['search']))         $query['search'] = $filters['search'];
                    if (!empty($filters['limit']))          $query['limit'] = (int)$filters['limit'];

                    // Helpers
                    $makeUrl = function($page) use ($query) {
                        $q = http_build_query(array_merge($query, ['page' => max(1, (int)$page)]));
                        return BASE_URL . '?' . $q;
                    };
                    ?>

                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mt-3 gap-2">
                            <div class="text-muted small order-2 order-md-1">
                                <?php 
                                $perPage = isset($filters['limit']) ? (int)$filters['limit'] : 20; 
                                $from = ($currentPage - 1) * $perPage + 1; 
                                $to   = min($currentPage * $perPage, $totalItems);
                                if ($totalItems > 0) {
                                    echo "Showing {$from}–{$to} of {$totalItems} orders";
                                }
                                ?>
                            </div>
                            <nav aria-label="Orders pagination" class="order-1 order-md-2 w-100 w-md-auto">
                                <ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center">
                                    <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="<?php echo $currentPage <= 1 ? '#' : $makeUrl($currentPage - 1); ?>" aria-label="Previous">
                                            &laquo; Prev
                                        </a>
                                    </li>
                                    <li class="page-item disabled d-none d-md-block"><span class="page-link">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span></li>
                                    <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="<?php echo $currentPage >= $totalPages ? '#' : $makeUrl($currentPage + 1); ?>" aria-label="Next">
                                            Next &raquo;
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
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
            
            // Set form action (use query style to match other routes)
            document.getElementById('editOrderForm').action = '<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=' + encodeURIComponent(id);
            
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        if (csrfToken) {
            formData.append('csrf_token', csrfToken);
        }
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
            },
            body: formData
        })
        .then(async response => {
            // Try to parse JSON; if not JSON but OK, treat as success
            let data = null;
            try { data = await response.json(); } catch (_) {}
            if (!response.ok) {
                const message = data?.message || `HTTP ${response.status}`;
                throw new Error(message);
            }
            return data || { success: true, message: 'Order updated successfully' };
        })
        .then(data => {
            if (data && data.success) {
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editOrderModal'));
                if (modal) modal.hide();

                // Reload to reflect updated statuses and pagination
                window.location.reload();
            } else {
                alert(data?.message || 'Failed to update order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while updating the order');
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
