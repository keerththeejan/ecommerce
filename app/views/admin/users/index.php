<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Users admin – trending responsive */
.users-admin .card-body { padding: 1rem; }
@media (min-width: 768px) { .users-admin .card-body { padding: 1.25rem; } }
.users-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.users-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.users-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.75rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.users-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }
.status-actions .btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0 !important; border-radius: 6px; }
.status-actions .badge { min-width: 80px; padding: 0.35rem 0.5rem; font-weight: 600; }
@media (max-width: 575.98px) { .users-table-scroll { max-height: 55vh; } }
@media (min-width: 576px) and (max-width: 991.98px) { .users-table-scroll { max-height: 60vh; } }
@media (min-width: 992px) { .users-table-scroll { max-height: 70vh; } }
@media (min-width: 576px) and (max-width: 991.98px) {
  #usersTable th:nth-child(4), #usersTable td:nth-child(4),
  #usersTable th:nth-child(8), #usersTable td:nth-child(8) { display: none !important; }
}
@media (max-width: 575.98px) {
  #usersTable thead { display: none; }
  #usersTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #usersTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #usersTable tbody td:last-child { border-bottom: 0; }
  #usersTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #usersTable tbody td[data-label="Status"] .status-actions { flex-wrap: wrap; }
  #usersTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #usersTable tbody td[data-label="Actions"] .user-actions { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
}
.users-admin .pagination { flex-wrap: wrap; gap: 0.25rem; }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 users-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
                    <h3 class="card-title mb-0 h5">Users</h3>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <label for="userPerPageFilter" class="form-label mb-0 small text-white opacity-90">Show:</label>
                            <select id="userPerPageFilter" class="form-select form-select-sm" style="width: auto; min-width: 4rem;">
                                <?php
                                $currentPerPage = $users['per_page_param'] ?? '20';
                                $baseUrl = BASE_URL . '?controller=user&action=adminIndex';
                                foreach (['20', '50', '100', 'all'] as $opt):
                                    $url = $baseUrl . (strpos($baseUrl, '?') !== false ? '&' : '?') . 'per_page=' . $opt;
                                    $sel = ($currentPerPage === $opt) ? ' selected' : '';
                                ?>
                                    <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <a href="<?php echo BASE_URL; ?>?controller=user&action=adminCreate" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 p-md-3">
                    <?php flash('user_success'); ?>
                    <?php flash('user_error', '', 'alert alert-danger'); ?>

                    <?php if(empty($users['data'])): ?>
                        <div class="alert alert-info mb-0 mx-3 mt-3">No users found.</div>
                    <?php else: ?>
                        <?php
                        $page = (int)($users['current_page'] ?? 1);
                        $perPage = (int)($users['per_page'] ?? 20);
                        ?>
                        <div class="users-table-scroll table-responsive mx-0 mx-md-3 mt-3">
                            <table id="usersTable" class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 90px;">ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th style="width: 90px;">Role</th>
                                        <th style="width: 140px;">Status</th>
                                        <th style="width: 100px;">Created</th>
                                        <th style="width: 140px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users['data'] as $idx => $user): 
                                        $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    ?>
                                        <tr>
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="ID"><?php echo $user['id']; ?></td>
                                            <td data-label="Username"><?php echo htmlspecialchars($user['username'] ?? ''); ?></td>
                                            <td data-label="Email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                            <td data-label="Name"><?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))); ?></td>
                                            <td data-label="Role">
                                                <?php
                                                $roleClass = 'bg-secondary';
                                                if (!empty($user['role'])) {
                                                    if ($user['role'] === 'admin') $roleClass = 'bg-danger';
                                                    elseif ($user['role'] === 'staff') $roleClass = 'bg-warning text-dark';
                                                    else $roleClass = 'bg-info';
                                                }
                                                ?>
                                                <span class="badge <?php echo $roleClass; ?>"><?php echo ucfirst($user['role'] ?? '—'); ?></span>
                                            </td>
                                            <td data-label="Status">
                                                <?php 
                                                $status = $user['status'] ?? '';
                                                $badgeClass = 'bg-secondary';
                                                $label = '—';
                                                $s = strtolower((string)$status);
                                                if ($status) {
                                                    if ($s === 'accepted' || $s === 'approved') { $badgeClass = 'bg-success'; $label = 'Accepted'; }
                                                    elseif ($s === 'pending') { $badgeClass = 'bg-warning text-dark'; $label = 'Pending'; }
                                                    elseif ($s === 'rejected') { $badgeClass = 'bg-danger'; $label = 'Rejected'; }
                                                    else { $label = ucfirst($status); }
                                                }
                                                $canModerate = ($s === '' || $s === 'pending');
                                                ?>
                                                <div class="d-flex align-items-center gap-2 status-actions flex-wrap">
                                                    <?php if (!$canModerate): ?>
                                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $label; ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($canModerate && ($user['id'] ?? 0) != ($_SESSION['user_id'] ?? 0)): ?>
                                                        <a title="Accept" href="<?php echo BASE_URL; ?>?controller=user&action=adminApprove&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-success p-1"><i class="fas fa-check"></i></a>
                                                        <a title="Reject" href="<?php echo BASE_URL; ?>?controller=user&action=adminReject&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger p-1"><i class="fas fa-times"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td data-label="Created"><?php echo !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : '—'; ?></td>
                                            <td data-label="Actions">
                                                <div class="user-actions d-flex flex-wrap gap-1">
                                                    <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <?php if(($user['id'] ?? 0) != ($_SESSION['user_id'] ?? 0)): ?>
                                                        <a href="<?php echo BASE_URL; ?>?controller=user&action=adminDelete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3 mx-3 d-flex flex-wrap justify-content-center justify-content-md-start">
                            <?php
                            $base = BASE_URL . '?controller=user&action=adminIndex';
                            $currentPerPage = $users['per_page_param'] ?? '20';
                            if ($currentPerPage !== '20') $base .= '&per_page=' . urlencode($currentPerPage);
                            echo getPaginationLinks($users['current_page'], $users['total_pages'], $base);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var el = document.getElementById('userPerPageFilter');
    if (el) el.addEventListener('change', function() { if (this.value) window.location.href = this.value; });
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
