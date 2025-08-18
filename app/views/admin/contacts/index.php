<?php require APPROOT . '/views/inc/admin/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo $data['title']; ?></h1>
    </div>

    <!-- Status Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo !isset($data['status']) ? 'active' : ''; ?>" 
               href="<?php echo URLROOT; ?>/admin/contacts">
                All Messages
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo isset($data['status']) && $data['status'] === 'unread' ? 'active' : ''; ?>" 
               href="<?php echo URLROOT; ?>/admin/contacts/status/unread">
                Unread <span class="badge bg-danger"><?php echo $data['unreadCount']; ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo isset($data['status']) && $data['status'] === 'read' ? 'active' : ''; ?>" 
               href="<?php echo URLROOT; ?>/admin/contacts/status/read">
                Read <span class="badge bg-primary"><?php echo $data['readCount']; ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo isset($data['status']) && $data['status'] === 'replied' ? 'active' : ''; ?>" 
               href="<?php echo URLROOT; ?>/admin/contacts/status/replied">
                Replied <span class="badge bg-success"><?php echo $data['repliedCount']; ?></span>
            </a>
        </li>
    </ul>

    <!-- Messages Table -->
    <div class="card">
        <div class="card-body">
            <?php flash('contact_message'); ?>
            
            <?php if (empty($data['contacts'])): ?>
                <div class="alert alert-info">No messages found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['contacts'] as $index => $contact): ?>
                                <tr class="<?php echo $contact->status === 'unread' ? 'table-primary' : ''; ?>">
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($contact->name); ?></td>
                                    <td><?php echo htmlspecialchars($contact->email); ?></td>
                                    <td><?php echo htmlspecialchars($contact->subject); ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($contact->created_at)); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $contact->status === 'unread' ? 'danger' : 
                                                ($contact->status === 'read' ? 'primary' : 'success'); 
                                        ?>">
                                            <?php echo ucfirst($contact->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/admin/contacts/view/<?php echo $contact->id; ?>" 
                                           class="btn btn-sm btn-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="<?php echo URLROOT; ?>/admin/contacts/delete/<?php echo $contact->id; ?>" 
                                              method="post" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

<?php require APPROOT . '/views/inc/admin/footer.php'; ?>
