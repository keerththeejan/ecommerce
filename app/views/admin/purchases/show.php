<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <div>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=edit&id=<?php echo $purchase['id']; ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Details</h6>
                    <span class="badge bg-<?php 
                        echo $purchase['status'] === 'received' ? 'success' : 
                            ($purchase['status'] === 'ordered' ? 'primary' : 
                            ($purchase['status'] === 'cancelled' ? 'danger' : 'warning')); 
                    ?>">
                        <?php echo ucfirst($purchase['status']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Supplier</h6>
                            <p class="mb-0"><?php echo htmlspecialchars($purchase['supplier_name']); ?></p>
                            <?php if (!empty($purchase['supplier_email'])): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($purchase['supplier_email']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($purchase['supplier_phone'])): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($purchase['supplier_phone']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Purchase #</h6>
                            <p class="mb-0"><?php echo $purchase['id']; ?></p>
                            <h6 class="text-muted mt-3">Purchase Date</h6>
                            <p class="mb-0"><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $items = isset($purchase['items']) && is_array($purchase['items']) ? $purchase['items'] : [];
                                $subtotal = 0;
                                if (!empty($items)):
                                    foreach ($items as $item): 
                                        $qty = isset($item['quantity']) ? (float)$item['quantity'] : 0;
                                        $price = isset($item['unit_price']) ? (float)$item['unit_price'] : 0;
                                        $itemTotal = $qty * $price;
                                        $subtotal += $itemTotal;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                            <div class="text-muted small">SKU: <?php echo htmlspecialchars($item['product_sku']); ?></div>
                                        </td>
                                        <td class="text-end"><?php echo $qty; ?></td>
                                        <td class="text-end"><?php echo formatPrice($price); ?></td>
                                        <td class="text-end"><?php echo formatPrice($itemTotal); ?></td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No items found for this purchase.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end"><?php echo formatPrice($subtotal); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tax (0%)</td>
                                    <td class="text-end">$0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Shipping</td>
                                    <td class="text-end">$0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold"><?php echo formatPrice($subtotal); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <?php if (!empty($purchase['notes'])): ?>
                        <div class="mt-4">
                            <h6 class="text-muted">Notes</h6>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($purchase['notes'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Update Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=purchase&action=updateStatus&id=<?php echo $purchase['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" <?php echo ($purchase['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="ordered" <?php echo ($purchase['status'] === 'ordered') ? 'selected' : ''; ?>>Ordered</option>
                                <option value="received" <?php echo ($purchase['status'] === 'received') ? 'selected' : ''; ?>>Received</option>
                                <option value="cancelled" <?php echo ($purchase['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Purchase Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Created At</h6>
                        <p class="mb-0"><?php echo date('M d, Y h:i A', strtotime($purchase['created_at'])); ?></p>
                    </div>
                    <?php if (!empty($purchase['updated_at']) && $purchase['updated_at'] !== $purchase['created_at']): ?>
                        <div class="mb-3">
                            <h6 class="text-muted small mb-1">Last Updated</h6>
                            <p class="mb-0"><?php echo date('M d, Y h:i A', strtotime($purchase['updated_at'])); ?></p>
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
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this purchase? This action cannot be undone.</p>
                <p class="fw-bold">Purchase #<?php echo $purchase['id']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?php echo BASE_URL; ?>?controller=purchase&action=delete&id=<?php echo $purchase['id']; ?>" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Purchase
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any JavaScript functionality here
});
</script>
