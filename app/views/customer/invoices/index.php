<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Invoices</h1>
            </div>
            
            <?php if(!empty($invoices)) : ?>
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Due Date</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($invoices as $invoice) : 
                                    // Determine status badge class
                                    $statusClass = 'secondary';
                                    if($invoice['status'] == 'paid') $statusClass = 'success';
                                    if($invoice['status'] == 'overdue') $statusClass = 'danger';
                                    if($invoice['status'] == 'partially_paid') $statusClass = 'warning';
                                ?>
                                    <tr>
                                        <td><?php echo $invoice['invoice_number']; ?></td>
                                        <td>#<?php echo $invoice['order_number']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($invoice['due_date'])); ?></td>
                                        <td class="text-end"><?php echo formatCurrency($invoice['total_amount']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo ucwords(str_replace('_', ' ', $invoice['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>?controller=invoice&action=show&param=<?php echo $invoice['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=invoice&action=download&param=<?php echo $invoice['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=invoice&action=print&param=<?php echo $invoice['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <p class="mb-0">You don't have any invoices yet.</p>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary mt-3">
                        Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
