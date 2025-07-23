<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">Product Report</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="<?php echo BASE_URL; ?>?controller=report&action=products" method="GET" class="mb-4">
                        <input type="hidden" name="controller" value="report">
                        <input type="hidden" name="action" value="products">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="0" <?php echo $categoryId == 0 ? 'selected' : ''; ?>>All Categories</option>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo $categoryId == $category['id'] ? 'selected' : ''; ?>>
                                                <?php echo $category['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_by">Sort By</label>
                                    <select class="form-control" id="sort_by" name="sort_by">
                                        <option value="sales" <?php echo $sortBy == 'sales' ? 'selected' : ''; ?>>Sales (Highest First)</option>
                                        <option value="stock" <?php echo $sortBy == 'stock' ? 'selected' : ''; ?>>Stock (Lowest First)</option>
                                        <option value="price" <?php echo $sortBy == 'price' ? 'selected' : ''; ?>>Price (Highest First)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="<?php echo BASE_URL; ?>?controller=report&action=products" class="btn btn-secondary ml-2">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(empty($productData)): ?>
                        <div class="alert alert-info">No product data available.</div>
                    <?php else: ?>
                        <!-- Product Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Units Sold</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($productData as $product): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=adminEdit&id=<?php echo $product['id']; ?>">
                                                    <?php echo $product['name']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $product['category_name']; ?></td>
                                            <td><?php echo formatCurrency($product['price']); ?></td>
                                            <td>
                                                <?php if($product['stock_quantity'] <= 5): ?>
                                                    <span class="badge bg-danger"><?php echo $product['stock_quantity']; ?></span>
                                                <?php elseif($product['stock_quantity'] <= 10): ?>
                                                    <span class="badge bg-warning"><?php echo $product['stock_quantity']; ?></span>
                                                <?php else: ?>
                                                    <?php echo $product['stock_quantity']; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $product['total_sold'] ?? 0; ?></td>
                                            <td><?php echo formatCurrency($product['revenue'] ?? 0); ?></td>
                                            <td><?php echo $product['order_count'] ?? 0; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
