<?php require APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Subscriber</h1>
        <div>
            <a class="btn btn-outline-secondary" href="?controller=newsletter&action=adminIndex">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <?php flash('newsletter_success'); ?>
    <?php flash('newsletter_error'); ?>

    <?php 
        // $data['item'] may be array or object
        $item = $data['item'] ?? null;
        $id = is_array($item) ? ($item['id'] ?? 0) : ($item->id ?? 0);
        $email = is_array($item) ? ($item['email'] ?? '') : ($item->email ?? '');
        $active = (is_array($item) ? ($item['active'] ?? 0) : ($item->active ?? 0)) ? 1 : 0;
    ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">Subscriber Details</div>
                <div class="card-body">
                    <form method="post" action="?controller=newsletter&action=adminUpdate">
                        <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" <?php echo $active ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="?controller=newsletter&action=adminIndex" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . 'views/inc/admin/footer.php'; ?>
