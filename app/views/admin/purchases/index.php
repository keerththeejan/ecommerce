<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title; ?></h1>
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Purchase
        </a>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="purchasesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchases)): ?>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr>
                                    <td>#<?php echo $purchase['id']; ?></td>
                                    <td><?php echo htmlspecialchars($purchase['supplier_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                                    <td><?php echo formatPrice($purchase['total_amount']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $purchase['status'] === 'received' ? 'success' : 
                                                ($purchase['status'] === 'pending' ? 'warning' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($purchase['status']); ?>
                                        </span>
                                    </td>
                                    <td>
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
                                <td colspan="6" class="text-center">No purchases found.</td>
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
        "order": [[2, "desc"]] // Sort by date by default
    });
});
</script>
