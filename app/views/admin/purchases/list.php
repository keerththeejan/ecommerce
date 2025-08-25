<?php
  // Optional: focus/highlight a specific product row when returning
  $highlightId = isset($_GET['highlight_product_id']) ? (int)$_GET['highlight_product_id'] : 0;
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?? 'Products'; ?></h1>
            <small class="text-muted"><?php echo $subtitle ?? ''; ?></small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary">
                <i class="fas fa-history me-2"></i>Recent Purchases
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=PurchaseController&action=create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add
            </a>
            <button type="button" class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Download Excel
            </button>
        </div>
    </div>

    <?php if (!empty($highlightId)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Showing product ID: <strong><?php echo htmlspecialchars($highlightId); ?></strong>
        <a class="ms-2" href="<?php echo BASE_URL; ?>?controller=ListPurchaseController">Clear</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
        </div>
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Product Type</label>
                    <select class="form-select">
                        <option>All</option>
                        <option value="single">Single</option>
                        <option value="variable">Variable</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['id']); ?>">
                                    <?php echo htmlspecialchars($cat['name'] ?? ('Category #' . $cat['id'])); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <select class="form-select">
                        <option>All</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tax</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <?php if (!empty($taxRates)) : ?>
                            <?php foreach ($taxRates as $tax): ?>
                                <option value="<?php echo htmlspecialchars($tax['id'] ?? ''); ?>">
                                    <?php echo htmlspecialchars(($tax['name'] ?? 'Tax') . ' (' . number_format((float)($tax['rate'] ?? 0), 2) . '%)'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Brand</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <?php if (!empty($brands)) : ?>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand['id']); ?>">
                                    <?php echo htmlspecialchars($brand['name'] ?? ('Brand #' . $brand['id'])); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Business Location</label>
                    <select class="form-select">
                        <option>All</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="notForSelling">
                        <label class="form-check-label" for="notForSelling">Not for selling</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-products" type="button" role="tab">All Products</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stock" type="button" role="tab">Stock Report</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-products" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <label class="me-2">Show</label>
                            <select class="form-select form-select-sm" style="width: 80px;">
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary btn-sm">Export CSV</button>
                            <button class="btn btn-outline-secondary btn-sm">Export Excel</button>
                            <button class="btn btn-outline-secondary btn-sm">Print</button>
                            <button class="btn btn-outline-secondary btn-sm">Column visibility</button>
                            <button class="btn btn-outline-secondary btn-sm">Export PDF</button>
                        </div>
                        <div style="width:240px">
                            <input type="text" class="form-control form-control-sm" placeholder="Search ...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="productsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:32px"><input type="checkbox"></th>
                                    <th>Product image</th>
                                    <th>Action</th>
                                    <th>Product</th>
                                    <th>Business Location</th>
                                    <th>Unit Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Current stock</th>
                                    <th>Stock Status</th>
                                    <th>Product Type</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Tax</th>
                                    <th>SKU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)) : ?>
                                    <?php foreach ($products as $p): ?>
                                        <?php
                                            $img = isset($p['image']) && $p['image'] ? $p['image'] : '';
                                            $name = $p['name'] ?? '—';
                                            $sku = $p['sku'] ?? '';
                                            $categoryName = $p['category_name'] ?? ($categoryMap[$p['category_id']]['name'] ?? '—');
                                            $brandName = isset($p['brand_id']) && isset($brandMap[$p['brand_id']]) ? ($brandMap[$p['brand_id']]['name'] ?? '—') : '—';
                                            $purchase = isset($p['price']) ? (float)$p['price'] : null;
                                            $sell = isset($p['sale_price']) && $p['sale_price'] > 0 ? (float)$p['sale_price'] : (isset($p['price']) ? (float)$p['price'] : null);
                                            $stockQty = isset($p['stock_quantity']) ? (float)$p['stock_quantity'] : 0;
                                            $unitLabel = 'Pieces';
                                            $type = isset($p['type']) ? ucfirst($p['type']) : 'Single';
                                            $taxDisplay = '—';
                                        ?>
                                        <tr data-product-id="<?php echo htmlspecialchars($p['id']); ?>">
                                            <td><input type="checkbox"></td>
                                            <td>
                                                <?php if ($img): ?>
                                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="width:48px;height:48px;object-fit:cover" class="rounded border" />
                                                <?php else: ?>
                                                    <div class="bg-light border rounded" style="width:48px;height:48px"></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="#" title="Labels">
                                                                <i class="fas fa-tags me-2 text-muted"></i> Labels
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=ProductController&action=show&id=<?php echo urlencode($p['id']); ?>">
                                                                <i class="far fa-eye me-2 text-muted"></i> View
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=ProductController&action=edit&id=<?php echo urlencode($p['id']); ?>">
                                                                <i class="far fa-edit me-2 text-muted"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase3&product_id=<?php echo urlencode($p['id']); ?>" title="Purchase Return">
                                                                <i class="fas fa-undo-alt me-2 text-muted"></i> Purchase Return
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>?controller=ProductController&action=delete&id=<?php echo urlencode($p['id']); ?>" onclick="return confirm('Delete this product?')">
                                                                <i class="far fa-trash-alt me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" title="Add or edit opening stock">
                                                                <i class="fas fa-database me-2 text-muted"></i> Add or edit opening stock
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" title="Product stock history">
                                                                <i class="fas fa-undo me-2 text-muted"></i> Product stock history
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" title="Duplicate Product">
                                                                <i class="far fa-clone me-2 text-muted"></i> Duplicate Product
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($name); ?>
                                                <?php if (!empty($highlightId) && (int)$highlightId === (int)$p['id']): ?>
                                                    <span class="badge bg-info ms-2">Returned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>S.N PASUMAI KALANJIYAM</td>
                                            <td><?php echo isset($purchase) ? htmlspecialchars(formatPrice($purchase)) : '—'; ?></td>
                                            <td><?php echo isset($sell) ? htmlspecialchars(formatPrice($sell)) : '—'; ?></td>
                                            <td><?php echo number_format($stockQty, 2) . ' ' . $unitLabel; ?></td>
                                            <td>
                                                <?php
                                                    $badge = '<span class="badge bg-success">In Stock</span>';
                                                    if ($stockQty <= 0) {
                                                        $badge = '<span class="badge bg-danger">Out of Stock</span>';
                                                    } elseif ($stockQty <= 5) {
                                                        $badge = '<span class="badge bg-warning text-dark">Low</span>';
                                                    }
                                                    echo $badge;
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($type); ?></td>
                                            <td><?php echo htmlspecialchars($categoryName); ?></td>
                                            <td><?php echo htmlspecialchars($brandName); ?></td>
                                            <td><?php echo htmlspecialchars($taxDisplay); ?></td>
                                            <td><?php echo htmlspecialchars($sku); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-stock" role="tabpanel">
            <div class="alert alert-info">Stock report will appear here.</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        if (window.jQuery && $.fn.DataTable) {
            $('#productsTable').DataTable();
        }

        // If returning with a specific product to show, highlight and focus it
        const highlightId = <?php echo (int)($highlightId ?: 0); ?>;
        if (highlightId > 0) {
            const rows = document.querySelectorAll('#productsTable tbody tr');
            let target = null;
            rows.forEach(tr => {
                const id = tr.getAttribute('data-product-id');
                if (String(id) === String(highlightId)) {
                    target = tr;
                    tr.classList.add('table-warning');
                } else {
                    tr.style.display = 'none';
                }
            });
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
