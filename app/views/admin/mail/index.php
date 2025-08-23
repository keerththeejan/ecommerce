<?php require APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
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

    <?php $fromPos = isset($_GET['from']) && $_GET['from'] === 'pos'; ?>

    

    <div class="card">
        <div class="card-header">Compose and Send</div>
        <div class="card-body">
            <form method="post" action="<?php echo BASE_URL; ?>?controller=mail&action=send">
                <?php if ($fromPos && !empty($_GET['order_id'])): ?>
                    <input type="hidden" name="order_id" value="<?php echo (int)$_GET['order_id']; ?>">
                <?php endif; ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">To (Email)</label>
                        <input type="email" name="email" class="form-control" placeholder="customer@example.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Invoice / Receipt" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="6" placeholder="Write your message here..." required></textarea>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_PATH . 'views/admin/layouts/footer.php'; ?>
