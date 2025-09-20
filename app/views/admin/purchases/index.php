<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Purchase
            </a>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered responsive-table" id="purchasesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Products</th>
                            <th>Date</th>
                            <th>Stock</th>
                            <th>Total Amount</th>
                            <th>Advance</th>
                            <th class="text-danger">Due</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchases)): ?>
                            <?php foreach ($purchases as $purchase): ?>
                                <?php 
                                    $po = isset($purchase['purchase_no']) ? (string)$purchase['purchase_no'] : '';
                                    $nt = isset($purchase['notes']) ? (string)$purchase['notes'] : '';
                                    $isReturn = (strpos($po, 'PR-') === 0) || (stripos($nt, '[RETURN]') !== false);
                                ?>
                                <tr>
                                    <td data-label="ID">#<?php echo $purchase['id']; ?></td>
                                    <td data-label="Supplier"><?php echo htmlspecialchars($purchase['supplier_name']); ?></td>
                                    <td class="text-truncate" style="max-width: 260px;" data-label="Products" title="<?php echo htmlspecialchars($purchase['product_names'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($purchase['product_names'] ?? ''); ?>
                                    </td>
                                    <td data-label="Date"><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                                    <td data-label="Stock"><?php echo (int)($purchase['total_items'] ?? 0); ?></td>
                                    <?php 
                                        $total = (float)($purchase['total_amount'] ?? 0);
                                        $advance = (float)($purchase['paid_amount'] ?? 0);
                                        $balance = max($total - $advance, 0);
                                        $refund = max($advance - $total, 0);
                                    ?>
                                    <td data-label="Total Amount"><?php echo formatPrice($total); ?></td>
                                    <td data-label="Advance"><?php echo formatPrice($advance); ?></td>
                                    <td class="text-danger fw-bold" data-label="Due">
                                        <?php echo formatPrice($balance); ?>
                                        <?php if ($refund > 0): ?>
                                            <div class="small mt-1"><span class="badge bg-success">Refund <?php echo formatPrice($refund); ?></span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Payment">
                                        <?php 
                                            $payStatus = strtolower((string)($purchase['payment_status'] ?? ''));
                                            $payBadge = 'secondary';
                                            $payLabel = 'N/A';
                                            if ($payStatus === 'paid') { $payBadge = 'success'; $payLabel = 'Paid'; }
                                            else if ($payStatus === 'partial') { $payBadge = 'warning'; $payLabel = 'Partial'; }
                                            else if ($payStatus === 'unpaid' || $balance > 0) { $payBadge = 'danger'; $payLabel = 'Unpaid'; }
                                        ?>
                                        <span class="badge bg-<?php echo $payBadge; ?>"><?php echo $payLabel; ?></span>
                                    </td>
                                    <td data-label="Status">
                                        <?php 
                                            $statusRaw = strtolower((string)($purchase['status'] ?? ''));
                                            if ($isReturn) { 
                                                $stBadge = 'danger'; 
                                                $stLabel = 'Return'; 
                                            } else if ($statusRaw === 'received') { 
                                                $stBadge = 'success'; 
                                                $stLabel = 'Received'; 
                                            } else if ($statusRaw === 'pending') { 
                                                $stBadge = 'warning'; 
                                                $stLabel = 'Pending'; 
                                            } else { 
                                                $stBadge = 'secondary'; 
                                                $stLabel = ucfirst($statusRaw ?: 'Unknown'); 
                                            }
                                        ?>
                                        <span class="badge bg-<?php echo $stBadge; ?>"><?php echo $stLabel; ?></span>
                                    </td>
                                    <td data-label="Actions">
                                        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=show&id=<?php echo $purchase['id']; ?>" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=edit&id=<?php echo $purchase['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo BASE_URL; ?>?controller=purchase&action=delete&id=<?php echo $purchase['id']; ?>" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">No purchases found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#purchasesTable').DataTable({
        "order": [[3, "desc"]] // Sort by date by default (index shifted after adding Products column)
    });
});
</script>
