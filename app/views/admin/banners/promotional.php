<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Promotional Banners</h2>
            <p>Manage your promotional banners and campaigns here.</p>
            
            <div class="card">
                <div class="card-body">
                    <h5>Promotional Banner Management</h5>
                    <p>Create and manage promotional banners for special offers and campaigns.</p>
                    
                    <div class="mb-3">
                        <a href="?controller=banner&action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Promotional Banner
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
                                    <td colspan="4" class="text-center">No promotional banners found</td>
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
