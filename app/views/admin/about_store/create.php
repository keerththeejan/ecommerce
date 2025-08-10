<?php require APPROOT . '/views/admin/includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Add New About Entry</h1>
                <a href="<?php echo URLROOT; ?>/about-store" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/about-store/store" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control <?php echo !empty($data['title_err']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo $data['title'] ?? ''; ?>">
                            <?php if (!empty($data['title_err'])) : ?>
                                <div class="invalid-feedback"><?php echo $data['title_err']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control <?php echo !empty($data['content_err']) ? 'is-invalid' : ''; ?>" 
                                     id="content" name="content" rows="6"><?php echo $data['content'] ?? ''; ?></textarea>
                            <?php if (!empty($data['content_err'])) : ?>
                                <div class="invalid-feedback"><?php echo $data['content_err']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Image</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            <div class="form-text">Max file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</div>
                            <?php if (!empty($data['image_err'])) : ?>
                                <div class="text-danger small"><?php echo $data['image_err']; ?></div>
                            <?php endif; ?>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
const imageInput = document.getElementById('image');
const imagePreview = document.getElementById('imagePreview');
const preview = document.getElementById('preview');

imageInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        imagePreview.style.display = 'none';
    }
});

// Initialize CKEditor for content
ClassicEditor
    .create(document.querySelector('#content'))
    .catch(error => {
        console.error(error);
    });
</script>

<?php require APPROOT . '/views/admin/includes/footer.php'; ?>
