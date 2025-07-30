<?php
// Set page title
$pageTitle = 'Stock Management';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $pageTitle; ?></h1>
        <div>
            <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-secondary me-2">
                <i class="fas fa-boxes me-1"></i> Manage Products
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStockModal">
                <i class="fas fa-plus me-1"></i> Update Stock
            </button>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Current Stock Levels</h6>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control form-control-sm me-2" placeholder="Search products...">
                <select id="categoryFilter" class="form-select form-select-sm" style="width: auto;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category->name); ?>">
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="stockTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No products found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr data-category="<?php echo htmlspecialchars($product->category_name ?? 'Uncategorized'); ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($product->image)): ?>
                                                <?php 
                                            $imagePath = BASE_URL . 'assets/img/no-image.jpg'; // Default image
                                            
                                            if (!empty($product->image)) {
                                                // Try different possible paths
                                                $possiblePaths = [
                                                    'uploads/products/' . $product->image,
                                                    $product->image,
                                                    'public/uploads/products/' . $product->image
                                                ];
                                                
                                                // Check each possible path
                                                foreach ($possiblePaths as $path) {
                                                    $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/' . ltrim($path, '/\\'));
                                                    if (file_exists($fullPath)) {
                                                        $imagePath = BASE_URL . ltrim($path, '/\\');
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" 
                                                 alt="<?php echo htmlspecialchars($product->name); ?>" 
                                                 class="img-thumbnail me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/img/no-image.jpg';">
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($product->name); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($product->sku); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($product->sku); ?></td>
                                    <td><?php echo htmlspecialchars($product->category_name ?? 'Uncategorized'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($product->stock_quantity > 0) ? 'success' : 'danger'; ?>">
                                            <?php echo $product->stock_quantity; ?> in stock
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo ($product->status === 'active') ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($product->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo !empty($product->updated_at) ? date('M d, Y H:i', strtotime($product->updated_at)) : 'N/A'; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary update-stock-btn" 
                                                data-id="<?php echo $product->id; ?>"
                                                data-name="<?php echo htmlspecialchars($product->name); ?>"
                                                data-stock="<?php echo $product->stock_quantity; ?>">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStockForm" action="<?php echo BASE_URL; ?>?controller=stock&action=update" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStockModalLabel">Update Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="productId">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product</label>
                        <input type="text" class="form-control" id="productName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="currentStock" class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="currentStock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="action" class="form-label">Action</label>
                        <select class="form-select" id="action" name="action" required>
                            <option value="set">Set to specific amount</option>
                            <option value="add">Add to current stock</option>
                            <option value="subtract">Subtract from current stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<!-- Add jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const table = $('#stockTable').DataTable({
        responsive: true,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting on Actions column
        ]
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        const category = this.value;
        if (category === '') {
            table.column(2).search('').draw();
        } else {
            table.column(2).search('^' + $.fn.dataTable.util.escapeRegex(category) + '$', true, false).draw();
        }
    });

    // Update stock modal
    $(document).on('click', '.update-stock-btn', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        const currentStock = $(this).data('stock');
        
        $('#productId').val(productId);
        $('#productName').val(productName);
        $('#currentStock').val(currentStock);
        $('#quantity').val('');
        $('#action').val('set');
        
        const modal = new bootstrap.Modal(document.getElementById('updateStockModal'));
        modal.show();
    });

    // Handle form submission
    $('#updateStockForm').on('submit', function(e) {
        const action = $('#action').val();
        const quantity = parseFloat($('#quantity').val());
        const currentStock = parseFloat($('#currentStock').val());
        
        if (isNaN(quantity) || quantity < 0) {
            e.preventDefault();
            alert('Please enter a valid quantity');
            return false;
        }
        
        if (action === 'subtract' && quantity > currentStock) {
            e.preventDefault();
            alert('Cannot subtract more than current stock');
            return false;
        }
        
        return true;
    });
});
</script>
