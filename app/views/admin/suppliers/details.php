<?php 
// Check if this is an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'supplier' => [
            'id' => $data['supplier']['id'],
            'name' => htmlspecialchars($data['supplier']['name']),
            'email' => $data['supplier']['email'] ? htmlspecialchars($data['supplier']['email']) : null,
            'phone' => $data['supplier']['phone'] ? htmlspecialchars($data['supplier']['phone']) : null,
            'address' => $data['supplier']['address'] ? nl2br(htmlspecialchars($data['supplier']['address'])) : null
        ]
    ]);
    exit;
}

// Full page view
require_once APP_PATH . 'views/admin/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Supplier Details</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Suppliers
                    </a>
                    <a href="#" class="btn btn-sm btn-warning me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="#" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><?php echo htmlspecialchars($data['supplier']['name']); ?></h4>
                            <hr>
                            <div class="mb-3">
                                <h6>Contact Information</h6>
                                <p class="mb-1">
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    <?php echo $data['supplier']['email'] ? htmlspecialchars($data['supplier']['email']) : '<span class="text-muted">No email provided</span>'; ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-phone me-2 text-muted"></i>
                                    <?php echo $data['supplier']['phone'] ? htmlspecialchars($data['supplier']['phone']) : '<span class="text-muted">No phone provided</span>'; ?>
                                </p>
                            </div>
                            
                            <?php if (!empty($data['supplier']['address'])): ?>
                            <div class="mb-3">
                                <h6>Address</h6>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['supplier']['address'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Supplier Stats</h6>
                                    <p class="mb-2">
                                        <i class="fas fa-boxes me-2 text-muted"></i>
                                        <strong>Products Supplied:</strong> 0
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-shopping-cart me-2 text-muted"></i>
                                        <strong>Total Orders:</strong> 0
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar me-2 text-muted"></i>
                                        <strong>Member Since:</strong> 
                                        <?php 
                                        $date = new DateTime($data['supplier']['created_at']);
                                        echo $date->format('M d, Y');
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Products from this Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                        No products found for this supplier
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <h5>Supplier Actions</h5>
                </div>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i> Add Product
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice me-2"></i> Create Purchase Order
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope me-2"></i> Send Email
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-export me-2"></i> Export Details
                    </a>
                </div>
                
                <div class="mt-4">
                    <h6>Quick Stats</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <small class="text-muted">Products:</small>
                            <div class="d-flex justify-content-between">
                                <span>Active</span>
                                <span>0</span>
                            </div>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">Last Order:</small>
                            <div>Never</div>
                        </li>
                        <li>
                            <small class="text-muted">Total Spent:</small>
                            <div>₹0.00</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
