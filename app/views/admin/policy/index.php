<?php require APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Policy Management</h1>
        <div>
            <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>?controller=home&action=admin">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['policy_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['policy_success']); unset($_SESSION['policy_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['policy_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['policy_error']); unset($_SESSION['policy_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo BASE_URL; ?>?controller=policy&action=save">
        <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user-shield me-2"></i> Privacy Policy</div>
            <div class="card-body">
                <textarea name="privacy" class="form-control" rows="8" placeholder="Write your Privacy Policy here..."><?php echo htmlspecialchars($privacy ?? ''); ?></textarea>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-file-signature me-2"></i> Terms of Service</div>
            <div class="card-body">
                <textarea name="terms" class="form-control" rows="8" placeholder="Write your Terms of Service here..."><?php echo htmlspecialchars($terms ?? ''); ?></textarea>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-question-circle me-2"></i> FAQ</div>
            <div class="card-body">
                <textarea name="faq" class="form-control" rows="8" placeholder="Write your FAQs here..."><?php echo htmlspecialchars($faq ?? ''); ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save</button>
        </div>
    </form>
</div>

<?php require APP_PATH . 'views/admin/layouts/footer.php'; ?>
