<?php require APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Newsletter Subscribers</h1>
        <div>
            <a class="btn btn-outline-primary" href="?controller=newsletter&action=adminExport">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>
    </div>

    <?php flash('newsletter_success'); ?>
    <?php flash('newsletter_error'); ?>

    <div class="row">
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">Add Subscriber</div>
                <div class="card-body">
                    <form method="post" action="?controller=newsletter&action=adminStore">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="subscriber@example.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Footer Newsletter Widget</div>
                <div class="card-body">
                    <form method="post" action="?controller=newsletter&action=adminSaveSettings">
                        <div class="mb-3">
                            <label for="newsletter_title" class="form-label">Title</label>
                            <input type="text" name="newsletter_title" id="newsletter_title" class="form-control" required value="<?php echo htmlspecialchars($data['newsletter_title'] ?? 'Newsletter'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="newsletter_description" class="form-label">Description</label>
                            <textarea name="newsletter_description" id="newsletter_description" rows="3" class="form-control" required><?php echo htmlspecialchars($data['newsletter_description'] ?? 'Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.'); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">Subscribers</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <style>
                        /* Mobile-first responsive table styling */
                        @media (max-width: 576.98px) {
                            table.responsive-table thead { display: none; }
                            table.responsive-table,
                            table.responsive-table tbody,
                            table.responsive-table tr,
                            table.responsive-table td { display: block; width: 100%; }
                            table.responsive-table tr {
                                margin-bottom: 1rem;
                                border: 1px solid rgba(0,0,0,.075);
                                border-radius: .5rem;
                                overflow: hidden;
                                background: var(--bg-color, #fff);
                            }
                            table.responsive-table td {
                                padding: .5rem .75rem;
                                border: none;
                                border-bottom: 1px solid rgba(0,0,0,.05);
                            }
                            table.responsive-table td:last-child { border-bottom: 0; }
                            table.responsive-table td::before {
                                content: attr(data-label);
                                font-weight: 600;
                                display: block;
                                margin-bottom: .25rem;
                                opacity: .8;
                            }
                            .newsletter-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
                        }
                        </style>
                        <table class="table table-striped mb-0 align-middle responsive-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th>Email</th>
                                    <th style="width: 110px;">Active</th>
                                    <th style="width: 200px;">Created</th>
                                    <th style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['subscribers'])): ?>
                                    <?php foreach ($data['subscribers'] as $row): ?>
                                        <?php 
                                            $id = is_array($row) ? ($row['id'] ?? '') : ($row->id ?? '');
                                            $email = is_array($row) ? ($row['email'] ?? '') : ($row->email ?? '');
                                            $active = (is_array($row) ? ($row['active'] ?? 0) : ($row->active ?? 0)) ? 'Yes' : 'No';
                                            $created = is_array($row) ? ($row['created_at'] ?? '') : ($row->created_at ?? '');
                                        ?>
                                        <tr>
                                            <td data-label="ID"><?php echo (int)$id; ?></td>
                                            <td data-label="Email"><?php echo htmlspecialchars($email); ?></td>
                                            <td data-label="Active"><?php echo htmlspecialchars($active); ?></td>
                                            <td data-label="Created"><?php echo htmlspecialchars($created); ?></td>
                                            <td data-label="Actions">
                                                <div class="newsletter-actions">
                                                    <a class="btn btn-sm btn-secondary" href="?controller=newsletter&action=adminEdit&id=<?php echo (int)$id; ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="?controller=newsletter&action=adminDelete" method="post" onsubmit="return confirm('Delete subscriber?');">
                                                        <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No subscribers yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . 'views/inc/admin/footer.php'; ?>
