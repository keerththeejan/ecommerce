<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Order Templates</h1>
                <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=order">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
            </div>

            <?php if (!empty($orders)) : ?>
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                    <?php 
                                        $row = is_array($o) ? $o : (array)$o;
                                        $orderId = (int)($row['id'] ?? ($row['order_id'] ?? 0));
                                        $orderNo = $row['order_number'] ?? ('#'.$orderId);
                                        $createdAt = $row['created_at'] ?? ($row['order_date'] ?? '');
                                        $total = isset($row['total_amount']) ? (float)$row['total_amount'] : 0;
                                    ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($orderNo); ?></td>
                                        <td><?php echo $createdAt ? date('M d, Y', strtotime($createdAt)) : '-'; ?></td>
                                        <td class="text-end"><?php echo formatCurrency($total); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=order&action=show&id=<?php echo $orderId; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-sm btn-outline-success" href="<?php echo BASE_URL; ?>?controller=order&action=checkout&param=<?php echo $orderId; ?>" title="Start new order based on this">
                                                    <i class="fas fa-cart-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <p class="mb-0">No previous orders found to generate templates.</p>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary mt-3">
                        Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
