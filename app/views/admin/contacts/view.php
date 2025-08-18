<?php require APPROOT . '/views/inc/admin/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo $data['title']; ?></h1>
        <div>
            <a href="<?php echo URLROOT; ?>/admin/contacts" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo htmlspecialchars($data['contact']->subject); ?></h5>
                        <span class="badge bg-<?php 
                            echo $data['contact']->status === 'unread' ? 'danger' : 
                                ($data['contact']->status === 'read' ? 'primary' : 'success'); 
                        ?>">
                            <?php echo ucfirst($data['contact']->status); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <strong>From:</strong> 
                                <?php echo htmlspecialchars($data['contact']->name); ?>
                                &lt;<?php echo htmlspecialchars($data['contact']->email); ?>&gt;
                            </div>
                            <div>
                                <small class="text-muted">
                                    <?php echo date('M d, Y h:i A', strtotime($data['contact']->created_at)); ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if (!empty($data['contact']->phone)): ?>
                            <div class="mb-2">
                                <strong>Phone:</strong> 
                                <?php echo htmlspecialchars($data['contact']->phone); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="border-top pt-3 mt-3">
                            <h6>Message:</h6>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($data['contact']->message)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <form action="<?php echo URLROOT; ?>/admin/contacts/delete/<?php echo $data['contact']->id; ?>" 
                              method="post" 
                              onsubmit="return confirm('Are you sure you want to delete this message?');">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Message
                            </button>
                        </form>
                        
                        <?php if ($data['contact']->status !== 'replied'): ?>
                            <form action="<?php echo URLROOT; ?>/admin/contacts/mark-replied/<?php echo $data['contact']->id; ?>" 
                                  method="post">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-reply"></i> Mark as Replied
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Contact Details</h6>
                        <p class="mb-1">
                            <i class="fas fa-user me-2"></i>
                            <?php echo htmlspecialchars($data['contact']->name); ?>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:<?php echo htmlspecialchars($data['contact']->email); ?>">
                                <?php echo htmlspecialchars($data['contact']->email); ?>
                            </a>
                        </p>
                        <?php if (!empty($data['contact']->phone)): ?>
                            <p class="mb-0">
                                <i class="fas fa-phone me-2"></i>
                                <a href="tel:<?php echo htmlspecialchars($data['contact']->phone); ?>">
                                    <?php echo htmlspecialchars($data['contact']->phone); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="border-top pt-3">
                        <h6>Message Information</h6>
                        <p class="mb-1">
                            <strong>Received:</strong> 
                            <?php echo date('M d, Y h:i A', strtotime($data['contact']->created_at)); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            <span class="badge bg-<?php 
                                echo $data['contact']->status === 'unread' ? 'danger' : 
                                    ($data['contact']->status === 'read' ? 'primary' : 'success'); 
                            ?>">
                                <?php echo ucfirst($data['contact']->status); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="mailto:<?php echo htmlspecialchars($data['contact']->email); ?>?subject=Re: <?php echo urlencode($data['contact']->subject); ?>" 
                       class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-reply"></i> Reply via Email
                    </a>
                    <?php if (!empty($data['contact']->phone)): ?>
                        <a href="tel:<?php echo htmlspecialchars($data['contact']->phone); ?>" 
                           class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-phone"></i> Call Customer
                        </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#notesModal">
                        <i class="fas fa-sticky-note"></i> Add Internal Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notesModalLabel">Add Internal Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Note</label>
                        <textarea class="form-control" id="noteContent" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="noteForm" class="btn btn-primary">Save Note</button>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin/footer.php'; ?>
