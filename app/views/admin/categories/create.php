<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    .page-shell {
        width: 100%;
        max-width: none;
        margin: 0;
    }

    .page-title {
        font-weight: 600;
        letter-spacing: -0.02em;
        margin-bottom: 0;
    }
    .page-subtitle {
        color: var(--muted-color);
        font-size: 0.9rem;
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    .form-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .form-section {
        padding: 1rem;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border-color: var(--border-color);
        background: var(--surface-color);
        color: var(--text-color);
        transition: border-color 120ms ease, box-shadow 120ms ease, background-color 120ms ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgba(59, 130, 246, 0.65);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.18);
    }

    .input-help {
        color: var(--muted-color);
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-action.btn-primary {
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.20);
    }

    .btn-action:focus {
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.18);
    }

    .style-guide {
        border-top: 1px dashed var(--border-color);
        margin-top: 1rem;
        padding-top: 1rem;
    }
    .sg-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.55rem;
        border: 1px solid var(--border-color);
        border-radius: 999px;
        background: var(--surface-muted);
        font-size: 0.85rem;
        color: var(--text-color);
        margin-right: 0.4rem;
        margin-bottom: 0.4rem;
        white-space: nowrap;
    }
    .sg-swatch {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        border: 1px solid var(--border-color);
        flex: 0 0 auto;
    }

    @media (min-width: 992px) {
        .form-section { padding: 1.25rem; }
    }
</style>

<div class="container-fluid page-shell">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 0.75rem; margin-bottom: 0.75rem;">
        <div>
            <h1 class="page-title">Add Category</h1>
            <p class="page-subtitle">Create a new category with optional parent and tax configuration.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-outline-secondary btn-action">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm form-card">
        <div class="card-body form-section">
            <?php if(isset($errors['db_error'])): ?>
                <div class="alert alert-danger"><?php echo $errors['db_error']; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo BASE_URL; ?>?controller=category&action=create" method="POST" enctype="multipart/form-data" novalidate>
                <div class="row" style="row-gap: 12px;">
                    <div class="col-12 col-lg-7">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name']; ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select id="parent_id" name="parent_id" class="form-select <?php echo isset($errors['parent_id']) ? 'is-invalid' : ''; ?>">
                                <option value="">None</option>
                                <?php if (!empty($parentCategories)): ?>
                                    <?php foreach ($parentCategories as $p): ?>
                                        <?php 
                                            $pid = is_object($p) ? $p->id : (isset($p['id']) ? $p['id'] : null);
                                            $pname = is_object($p) ? $p->name : (isset($p['name']) ? $p['name'] : '');
                                            $selected = isset($data['parent_id']) && (string)$data['parent_id'] === (string)$pid ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo htmlspecialchars($pid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($pname); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if(isset($errors['parent_id'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['parent_id']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tax Rate -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="tax_id" class="form-label">Tax Rate</label>
                            <select id="tax_id" name="tax_id" class="form-select <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>">
                                <option value="">None</option>
                                <?php if (!empty($taxRates)): ?>
                                    <?php foreach ($taxRates as $t): ?>
                                        <?php 
                                            $tid = is_object($t) ? $t->id : (isset($t['id']) ? $t['id'] : null);
                                            $tname = is_object($t) ? $t->name : (isset($t['name']) ? $t['name'] : '');
                                            $trate = is_object($t) ? $t->rate : (isset($t['rate']) ? $t['rate'] : '');
                                            $label = trim($tname . ' (' . $trate . '%)');
                                            $selected = isset($data['tax_id']) && (string)$data['tax_id'] === (string)$tid ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo htmlspecialchars($tid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if(isset($errors['tax_id'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['tax_id']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?php echo $data['status'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                        
                    <!-- Image Upload -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*">
                            <?php if(isset($errors['image'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <div class="input-help">Recommended size: 800x600px. Max size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP</div>
                        </div>
                    </div>
                        
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-end" style="gap: 0.5rem; flex-wrap: wrap;">
                            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-outline-secondary btn-action">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-action">
                                <i class="fas fa-plus"></i>
                                <span>Add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="style-guide">
                <h6 class="mb-2">Style Guide</h6>
                <div class="mb-2">
                    <span class="sg-chip"><span class="sg-swatch" style="background:#3b82f6"></span>Primary</span>
                    <span class="sg-chip"><span class="sg-swatch" style="background:#0f172a"></span>Sidebar</span>
                    <span class="sg-chip"><span class="sg-swatch" style="background:#198754"></span>Success</span>
                    <span class="sg-chip"><span class="sg-swatch" style="background:#dc3545"></span>Danger</span>
                    <span class="sg-chip"><span class="sg-swatch" style="background:#6b7280"></span>Muted</span>
                </div>
                <div class="small text-muted">
                    Font: Inter (400/500/600). Spacing: 8px grid (8, 16, 24). Corners: 12–14px. Buttons: hover lift + clear focus ring.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
