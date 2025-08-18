<?php require APP_PATH . 'views/inc/admin/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo $data['title']; ?></h1>
        <a href="<?php echo URLROOT; ?>/admin/contact-info/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Contact Info
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php flash('contact_info_message'); ?>
            <?php if (empty($data['items'])): ?>
                <div class="alert alert-info">No contact info records. Click "Add" to create one.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Weekdays</th>
                                <th>Weekends</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['items'] as $i => $item): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <?php 
                                    $addr = is_array($item) ? ($item['address'] ?? '') : ($item->address ?? '');
                                    $ph   = is_array($item) ? ($item['phone'] ?? '')   : ($item->phone ?? '');
                                    $em   = is_array($item) ? ($item['email'] ?? '')   : ($item->email ?? '');
                                    $hw   = is_array($item) ? ($item['hours_weekdays'] ?? '') : ($item->hours_weekdays ?? '');
                                    $he   = is_array($item) ? ($item['hours_weekends'] ?? '') : ($item->hours_weekends ?? '');
                                    $upd  = is_array($item) ? ($item['updated_at'] ?? null) : ($item->updated_at ?? null);
                                ?>
                                <td><?php echo htmlspecialchars($addr); ?></td>
                                <td><?php echo htmlspecialchars($ph); ?></td>
                                <td><?php echo htmlspecialchars($em); ?></td>
                                <td><?php echo htmlspecialchars($hw); ?></td>
                                <td><?php echo htmlspecialchars($he); ?></td>
                                <td><?php echo $upd ? date('Y-m-d H:i', strtotime($upd)) : ''; ?></td>
                                <td>
                                    <?php $id = is_array($item) ? ($item['id'] ?? '') : ($item->id ?? ''); ?>
                                    <a href="<?php echo URLROOT; ?>/admin/contact-info/edit/<?php echo $id; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo URLROOT; ?>/admin/contact-info/delete/<?php echo $id; ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APP_PATH . 'views/inc/admin/footer.php'; ?>
