<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Add New Category</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($errors['db_error'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['db_error']; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=category&action=create" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name']; ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Parent Category -->
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

                        <!-- Tax Rate -->
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

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?php echo $data['status'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*">
                            <?php if(isset($errors['image'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Recommended size: 800x600px. Max size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Back to Categories
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
