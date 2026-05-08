<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Mobile Banners</h2>
            <p>Manage mobile-specific banners for optimal mobile experience.</p>
            
            <div class="card">
                <div class="card-body">
                    <h5>Mobile Banner Management</h5>
                    <p>Create and manage banners specifically optimized for mobile devices.</p>
                    
                    <div class="mb-3">
                        <a href="?controller=banner&action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Mobile Banner
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Banner</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No mobile banners found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
