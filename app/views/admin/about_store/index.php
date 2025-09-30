<?php require APPROOT . '/views/admin/includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">About Store</h1>
                <a href="<?php echo URLROOT; ?>/about-store/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>

            <?php flash('success'); ?>
            <?php flash('error'); ?>

            <div class="card">
                <div class="card-body">
                    <?php if (!empty($data['about_entries'])) : ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['about_entries'] as $index => $about) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($about->title); ?></td>
                                            <td>
                                                <?php if (!empty($about->image_path)) : ?>
                                                    <img src="<?php echo URLROOT . $about->image_path; ?>" alt="<?php echo htmlspecialchars($about->title); ?>" style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                                <?php else : ?>
                                                    <span class="text-muted">No image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($about->created_at)); ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/about-store/edit/<?php echo $about->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteAbout(<?php echo $about->id; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">No about entries found. <a href="<?php echo URLROOT; ?>/about-store/create">Create one now</a>.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteAbout(id) {
    if (confirm('Are you sure you want to delete this about entry? This action cannot be undone.')) {
        window.location.href = '<?php echo URLROOT; ?>/about-store/delete/' + id;
    }
}
</script>

<?php require APPROOT . '/views/admin/includes/footer.php'; ?>
