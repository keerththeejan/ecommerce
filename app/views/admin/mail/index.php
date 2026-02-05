<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mail</h1>
        <div>
            <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>?controller=home&action=admin">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['mail_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['mail_success']); unset($_SESSION['mail_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['mail_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['mail_error']); unset($_SESSION['mail_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
        $fromPos = isset($_GET['from']) && $_GET['from'] === 'pos';
        $prefill = isset($prefill) && is_array($prefill) ? $prefill : ['email'=>'','subject'=>'','message'=>'','from_email'=>'','from_name'=>'Store'];
        $mailNotConfigured = empty($prefill['from_email']);
    ?>
    <?php if ($mailNotConfigured): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            To send email, set <code>MAIL_FROM_ADDRESS</code>, <code>SMTP_USERNAME</code>, and <code>SMTP_PASSWORD</code> in <code>config/config.php</code>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    

    <?php if (!empty($order) && isset($_GET['show_details'])): ?>
    <div class="card mb-3">
        <div class="card-header">Order Details</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th>Order ID</th>
                            <td>#<?php echo (int)$order['order']['id']; ?></td>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <td><?php echo htmlspecialchars(($order['order']['first_name'] ?? '') . ' ' . ($order['order']['last_name'] ?? '')); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($order['order']['email'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo htmlspecialchars(ucfirst($order['order']['status'] ?? '')); ?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td><?php echo isset($order['order']['total_amount']) ? formatPrice($order['order']['total_amount']) : ''; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th>Payment</th>
                            <td><?php echo htmlspecialchars(ucfirst($order['order']['payment_status'] ?? '')); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td><?php echo htmlspecialchars(ucfirst($order['order']['payment_method'] ?? '')); ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo !empty($order['order']['created_at']) ? date('M d, Y H:i', strtotime($order['order']['created_at'])) : ''; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php if (!empty($order['items'])): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $it): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($it['product_name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($it['sku'] ?? ''); ?></td>
                            <td><?php echo isset($it['price']) ? formatPrice($it['price']) : ''; ?></td>
                            <td><?php echo (int)($it['quantity'] ?? 0); ?></td>
                            <td class="text-end"><?php echo (isset($it['price']) && isset($it['quantity'])) ? formatPrice($it['price'] * $it['quantity']) : ''; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">Compose and Send</div>
        <div class="card-body">
            <form method="post" action="<?php echo BASE_URL; ?>?controller=mail&action=send">
                <?php if ($fromPos && !empty($_GET['order_id'])): ?>
                    <input type="hidden" name="order_id" value="<?php echo (int)$_GET['order_id']; ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">From (Email)</label>
                        <input type="email" name="from_email" class="form-control" placeholder="sender@example.com" value="<?php echo htmlspecialchars($prefill['from_email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From (Name)</label>
                        <input type="text" name="from_name" class="form-control" placeholder="Store / Admin" value="<?php echo htmlspecialchars($prefill['from_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">To (Email)</label>
                        <input type="email" name="email" class="form-control" placeholder="customer@example.com" value="<?php echo htmlspecialchars($prefill['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Invoice / Receipt" value="<?php echo htmlspecialchars($prefill['subject'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="10" placeholder="Write your message here..." required><?php echo htmlspecialchars($prefill['message'] ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
