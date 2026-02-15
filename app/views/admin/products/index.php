<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Summary cards: always light background + dark text (readable in light and dark admin theme) */
.admin-summary-card {
    background: #fff !important;
    border: 1px solid rgba(0,0,0,.1) !important;
}
.admin-summary-card .card-body {
    background: #fff !important;
    color: #212529 !important;
}
.admin-summary-card.admin-summary-products {
    border-left: 4px solid #0d6efd !important;
}
.admin-summary-card.admin-summary-stock {
    border-left: 4px solid #198754 !important;
}
.admin-summary-card .admin-summary-title,
.admin-summary-card .admin-summary-label {
    color: #212529 !important;
}
.admin-summary-card .admin-summary-title {
    opacity: 1;
}
.admin-summary-card.admin-summary-products .h4.text-primary {
    color: #0d6efd !important;
}
.admin-summary-card.admin-summary-stock .h4.text-success,
.admin-summary-card.admin-summary-stock .h6.text-success {
    color: #198754 !important;
}
/* Override dark theme so summary cards stay light with dark text */
[data-theme="dark"] .admin-summary-card,
[data-theme="dark"] .admin-summary-card .card-body {
    background: #f8f9fa !important;
    border-color: rgba(0,0,0,.15) !important;
    color: #212529 !important;
}
[data-theme="dark"] .admin-summary-card .admin-summary-title,
[data-theme="dark"] .admin-summary-card .admin-summary-label,
[data-theme="dark"] .admin-summary-card .card-body,
[data-theme="dark"] .admin-summary-card .card-body p,
[data-theme="dark"] .admin-summary-card .card-body span {
    color: #212529 !important;
}
[data-theme="dark"] .admin-summary-card.admin-summary-products .h4.text-primary {
    color: #0d6efd !important;
}
[data-theme="dark"] .admin-summary-card.admin-summary-stock .h4.text-success,
[data-theme="dark"] .admin-summary-card.admin-summary-stock .h6.text-success {
    color: #198754 !important;
}

/* Admin products – responsive & trending */
.products-table .admin-product-img { width: 50px; height: auto; object-fit: contain; border-radius: 8px; }

.products-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.products-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.products-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.75rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.products-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }

/* Responsive height */
@media (max-width: 575.98px) {
  .products-table-scroll { max-height: 55vh; }
}
@media (min-width: 576px) and (max-width: 991.98px) {
  .products-table-scroll { max-height: 60vh; }
}
@media (min-width: 992px) {
  .products-table-scroll { max-height: 70vh; }
}

/* Tablet: hide less critical columns to reduce scroll */
@media (min-width: 576px) and (max-width: 991.98px) {
  #productsTable th:nth-child(5),
  #productsTable th:nth-child(6),
  #productsTable td:nth-child(5),
  #productsTable td:nth-child(6) { display: none !important; }
  #productsTable th:nth-child(9),
  #productsTable th:nth-child(10),
  #productsTable td:nth-child(9),
  #productsTable td:nth-child(10) { display: none !important; }
}

/* Mobile: card-style rows */
@media (max-width: 575.98px) {
  #productsTable thead { display: none; }
  #productsTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #productsTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #productsTable tbody td:last-child { border-bottom: 0; }
  #productsTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #productsTable tbody td[data-label="Image"] { display: block; padding: 0; }
  #productsTable tbody td[data-label="Image"]::before { content: none; }
  #productsTable tbody td[data-label="Image"] img.admin-product-img {
    width: 100% !important;
    max-height: 180px;
    object-fit: contain;
    display: block;
    border-radius: 0;
  }
  #productsTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #productsTable tbody td[data-label="Actions"] .btn-group { width: 100%; justify-content: flex-end; }
}

/* Pagination responsive */
.products-table-scroll + .mt-3 .pagination { flex-wrap: wrap; gap: 0.25rem; }
.products-table-scroll + .mt-3 .pagination .page-link { padding: 0.4rem 0.6rem; font-size: 0.875rem; }

/* Fix overflow - search and filters don't take excessive space */
.products-admin .input-group { max-width: 100%; }
.products-admin .form-control { max-width: 100%; }
.products-admin .card-body { overflow-x: hidden; }
.products-admin .row.g-3 { overflow: hidden; }
.modal .close { padding: 0.5rem 1rem; font-size: 1.5rem; opacity: 1; }

/* Force dark text on white/light backgrounds (fix invisible text in dark mode) */
.products-admin .btn-light,
.products-admin .btn-light i { color: #212529 !important; }
.products-admin .form-control,
.products-admin .input-group-text { color: #212529 !important; background-color: #fff !important; }
.products-admin .form-control::placeholder { color: #6c757d !important; }
[data-theme="dark"] .products-admin .btn-light,
[data-theme="dark"] .products-admin .btn-light i { color: #212529 !important; background-color: #f8f9fa !important; }
[data-theme="dark"] .products-admin .form-control,
[data-theme="dark"] .products-admin .input-group-text { color: #212529 !important; background-color: #fff !important; border-color: #dee2e6; }
[data-theme="dark"] .products-admin .form-control::placeholder { color: #6c757d !important; }
</style>

<div class="container-fluid px-2 px-sm-3 products-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
                    <h3 class="card-title mb-0 h5 mb-0">Products</h3>
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=export" class="btn btn-light btn-sm">
                            <i class="fas fa-download mr-1"></i> Export CSV
                        </a>
                        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-upload mr-1"></i> Import CSV
                        </button>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-3 shadow-sm h-100 admin-summary-card admin-summary-products">
                                <div class="card-body py-3 py-md-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small text-uppercase fw-semibold admin-summary-title">Products Summary</h6>
                                        <p class="h4 mb-0 fw-bold text-primary">
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
                                        <span class="small admin-summary-label">Total Products</span>
                                    </div>
                                    <i class="fas fa-boxes fa-2x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card border-0 rounded-3 shadow-sm h-100 admin-summary-card admin-summary-stock">
                                <div class="card-body py-3 py-md-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small text-uppercase fw-semibold admin-summary-title">Stock Summary</h6>
                                        <p class="h4 mb-0 fw-bold text-success">
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
                                        <span class="small admin-summary-label">Total Stock</span>
                                        <p class="h6 mb-0 mt-1 fw-bold text-success"><?php echo formatPrice($totalStockValue); ?></p>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-2x text-success opacity-75"></i>
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
                        <div class="products-table-scroll table-responsive">
                            <table id="productsTable" class="table table-striped table-hover products-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Batch No.</th>
                                        <th>Supplier</th>
                                        <th>Expiry</th>
                                        <th>Buying Price</th>
                                        <th>Including Tax Price</th>
                                        <th>Sales Price</th>
                                        <th>Wholesale Price (SP)</th>
                                        <th>Stock</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $page = isset($products['current_page']) ? (int)$products['current_page'] : 1;
                                    $perPage = isset($products['per_page']) ? (int)$products['per_page'] : 15;
                                    foreach($products['data'] as $idx => $product): 
                                        $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    ?>
                                        <tr id="product-row-<?php echo $product['id']; ?>">
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Image">
                                                <?php if(!empty($product['image'])): ?>
                                                    <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail admin-product-img">
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="No Image" class="img-thumbnail admin-product-img">
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Name"><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td data-label="SKU"><?php echo htmlspecialchars($product['sku']); ?></td>
                                            <td data-label="Batch No."><?php echo !empty($product['batch_number']) ? htmlspecialchars($product['batch_number']) : '<span class="text-muted">-</span>'; ?></td>
                                            <td data-label="Supplier">
                                                <?php 
                                                    $supplierName = '';
                                                    if (!empty($product['supplier'])) {
                                                        $supplierName = $product['supplier'];
                                                    } elseif (!empty($product['supplier_id']) && !empty($supplierMap) && isset($supplierMap[$product['supplier_id']])) {
                                                        $supplierName = $supplierMap[$product['supplier_id']];
                                                    }
                                                ?>
                                                <?php echo $supplierName !== '' ? htmlspecialchars($supplierName) : '<span class="text-muted">-</span>'; ?>
                                            </td>
                                            <td data-label="Expiry">
                                                <?php if(!empty($product['expiry_date'])): ?>
                                                    <?php echo htmlspecialchars($product['expiry_date']); ?>
                                                    <?php 
                                                    // Highlight expired products
                                                    $isExpired = false;
                                                    try { $isExpired = (strtotime($product['expiry_date']) < time()); } catch (Exception $e) { $isExpired = false; }
                                                    ?>
                                                    <?php if($isExpired): ?><span class="badge badge-danger ml-1">Expired</span><?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Buying Price"><?php echo formatPrice($product['price']); ?></td>
                                            <td data-label="Including Tax Price">
                                                <?php if(!empty($product['sale_price'])): ?>
                                                    <?php echo formatPrice($product['sale_price']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Sales Price">
                                                <?php if(!empty($product['price2'])): ?>
                                                    <?php echo formatPrice($product['price2']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Wholesale Price (SP)">
                                                <?php if(!empty($product['price3'])): ?>
                                                    <?php echo formatPrice($product['price3']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Stock">
                                                <?php $qty = (float)($product['stock_quantity'] ?? 0); ?>
                                                <?php if ($qty <= 0): ?>
                                                    <span class="badge bg-info">Out of Stock</span>
                                                <?php else: ?>
                                                    <span class="badge bg-<?php echo ($qty <= 5) ? 'warning text-dark' : 'success'; ?>">
                                                        <?php echo $product['stock_quantity']; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-nowrap" data-label="Stock Value">
                                                <?php 
                                                $stockValue = (float)$product['stock_quantity'] * (float)$product['price'];
                                                echo formatPrice($stockValue);
                                                ?>
                                            </td>
                                            <td data-label="Status">
                                                <span class="badge bg-<?php echo ($product['status'] == 'active') ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td data-label="Actions">
                                                <div class="btn-group" role="group">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info btn-history mr-1"
                                                            data-product-id="<?php echo $product['id']; ?>"
                                                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>">
                                                        <i class="fas fa-history"></i>
                                                    </button>
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
                        
                        <!-- Show per page: 20 / 50 / 100 / All -->
                        <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                            <label for="perPageFilter" class="form-label mb-0 small text-muted">Show:</label>
                            <select id="perPageFilter" class="form-control form-control-sm" style="width: auto; max-width: 100px;">
                                <?php 
                                $currentPerPage = $products['per_page_param'] ?? '20';
                                $currentSearch = $products['search'] ?? '';
                                $baseUrl = BASE_URL . '?controller=product&action=adminIndex';
                                foreach (['20', '50', '100', 'all'] as $opt): 
                                    $sel = ($currentPerPage === $opt) ? ' selected' : '';
                                    $url = $baseUrl . '&per_page=' . $opt;
                                    if ($currentSearch !== '') {
                                        $url .= '&search=' . urlencode($currentSearch);
                                    }
                                ?>
                                    <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="small text-muted">(<?php echo count($products['data']); ?> shown)</span>
                        </div>
                        <script>
                        document.getElementById('perPageFilter').addEventListener('change', function() {
                            window.location.href = this.value;
                        });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="historyModalLabel">Stock History</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="historyContent" class="py-2">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="productName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                <input type="hidden" id="productId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>?controller=product&action=import" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel"><i class="fas fa-upload mr-2"></i>Import Products from CSV</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv" required>
                        <div class="form-text">Upload a CSV with columns: name, description, sku, price, sale_price, price2, price3, stock_quantity, category_id, brand_id, country_id, supplier, batch_number, status, add_date, expiry_date, tax_id. Use Export to download a sample.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload mr-1"></i>Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // History modal elements
    const historyModalEl = document.getElementById('historyModal');
    const historyContent = document.getElementById('historyContent');
    const historyTitle = document.getElementById('historyModalLabel');

    function loadHistory(productId, page = 1) {
        if (!historyContent) return;
        historyContent.innerHTML = '<div class="text-center text-muted">Loading...</div>';
        const url = `?controller=stock&action=history&id=${encodeURIComponent(productId)}&page=${page}&partial=1`;
        fetch(url, { credentials: 'same-origin' })
            .then(r => {
                if (!r.ok) throw new Error('Failed to load history');
                return r.text();
            })
            .then(html => {
                historyContent.innerHTML = html;
            })
            .catch(err => {
                historyContent.innerHTML = `<div class="alert alert-danger mb-0">${err.message}</div>`;
            });
    }

    // Open history modal on button click
        $(document).on('click', '.btn-history', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name') || '';
            if (historyTitle) historyTitle.textContent = 'Stock History - ' + productName;
            $('#historyModal').modal('show');
            loadHistory(productId, 1);
        });

    // Delegate pagination clicks inside history modal
    if (historyModalEl) {
        historyModalEl.addEventListener('click', function(e) {
            const link = e.target.closest('a.history-page-link');
            if (link) {
                e.preventDefault();
                const page = parseInt(link.getAttribute('data-page') || '1', 10);
                // Extract current product id from existing links
                const href = new URL(link.getAttribute('href'), window.location.href);
                const productId = href.searchParams.get('id');
                if (productId) loadHistory(productId, page);
            }
        });
    }

    // Delete modal elements
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
            $('#deleteModal').modal('show');
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
        .finally(function() {
            // Hide modal
            $('#deleteModal').modal('hide');
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        `;
        
        // Add to container
        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $(alertDiv).alert('close');
        }, 5000);
    }
    
    // Close button for alerts (Bootstrap 4 uses data-dismiss)
    $(document).on('click', '[data-dismiss="alert"]', function() {
        var alert = $(this).closest('.alert');
        if (alert.length) alert.alert('close');
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
