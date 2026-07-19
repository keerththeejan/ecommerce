<?php
/**
 * User Create — Enterprise Admin UI (visual layer only)
 * Preserves: username, email, password, confirm_password, first_name, last_name, role
 * POST ?controller=user&action=adminCreate
 */
require_once APP_PATH . 'views/admin/layouts/header.php';

$data = is_array($data ?? null) ? $data : [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    'first_name' => '',
    'last_name' => '',
    'role' => 'customer'
];
$errors = is_array($errors ?? null) ? $errors : [];

$username = htmlspecialchars((string)($data['username'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars((string)($data['email'] ?? ''), ENT_QUOTES, 'UTF-8');
$firstName = htmlspecialchars((string)($data['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$lastName = htmlspecialchars((string)($data['last_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$role = (string)($data['role'] ?? 'customer');
if (!in_array($role, ['customer', 'staff', 'admin'], true)) {
    $role = 'customer';
}

$initials = strtoupper(substr($firstName !== '' ? $firstName : 'U', 0, 1) . substr($lastName !== '' ? $lastName : '', 0, 1));
if (trim($initials) === '' || $initials === 'U') {
    $initials = 'U';
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/user-form.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid user-form-page py-3 py-md-4 px-2 px-sm-3" id="userCreatePage">
    <div class="uf-toast-host" id="ufToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="uf-header">
        <div>
            <nav class="uf-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span class="sep">›</span>
                <span>Administration</span>
                <span class="sep">›</span>
                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex">Users</a>
                <span class="sep">›</span>
                <span aria-current="page">Create User</span>
            </nav>
            <h1 class="uf-title">User Management</h1>
            <p class="uf-subtitle">Create a staff or customer account with role-based access and secure credentials.</p>
        </div>
        <div class="uf-actions">
            <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="btn btn-outline-secondary uf-btn" id="ufBackTop">
                <i class="bi bi-arrow-left" aria-hidden="true"></i><span>Back</span>
            </a>
            <button type="button" class="btn btn-outline-secondary uf-btn" id="ufSaveDraftBtn">
                <i class="bi bi-file-earmark" aria-hidden="true"></i><span>Save Draft</span>
            </button>
            <button type="button" class="btn btn-outline-primary uf-btn" id="ufPreviewBtn" data-bs-toggle="modal" data-bs-target="#ufPreviewModal">
                <i class="bi bi-eye" aria-hidden="true"></i><span>Preview</span>
            </button>
            <button type="submit" form="userCreateForm" class="btn uf-btn uf-btn-primary" id="ufSaveTop">
                <i class="bi bi-check2-circle" aria-hidden="true"></i><span>Save User</span>
            </button>
        </div>
    </div>

    <div class="uf-progress" aria-hidden="true"><span id="ufFormProgress"></span></div>

    <?php flash('user_success'); ?>
    <?php if (isset($errors['db_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($errors['db_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form id="userCreateForm" action="<?php echo BASE_URL; ?>?controller=user&action=adminCreate" method="POST" novalidate>
        <div class="row g-3">
            <div class="col-12 col-xl-9">

                <!-- SECTION 1: Account Information -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-person-vcard text-primary" aria-hidden="true"></i> Account Information</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="req">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?php echo $firstName; ?>" required maxlength="50" autocomplete="given-name">
                                <?php if (isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['first_name']); ?></div>
                                <?php else: ?>
                                    <div class="invalid-feedback">First name is required.</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="req">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?php echo $lastName; ?>" required maxlength="50" autocomplete="family-name">
                                <?php if (isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['last_name']); ?></div>
                                <?php else: ?>
                                    <div class="invalid-feedback">Last name is required.</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufDisplayName" class="form-label">Display Name</label>
                                <input type="text" class="form-control" id="ufDisplayName" value="<?php echo trim($firstName . ' ' . $lastName); ?>" maxlength="100" autocomplete="off">
                                <div class="form-text"><span class="uf-ui-only">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email <span class="req">*</span></label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" required maxlength="100" autocomplete="email" inputmode="email">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                                <?php else: ?>
                                    <div class="invalid-feedback">Enter a valid email address.</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="ufPhone" placeholder="+91 …" autocomplete="tel" inputmode="tel">
                                <div class="form-text"><span class="uf-ui-only">(UI only)</span></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="ufEmployeeId" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="ufEmployeeId" maxlength="40">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="ufNationalId" class="form-label">National ID</label>
                                <input type="text" class="form-control" id="ufNationalId" maxlength="40">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="ufDob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="ufDob">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="ufGender" class="form-label">Gender</label>
                                <select class="form-select" id="ufGender">
                                    <option value="">Prefer not to say</option>
                                    <option value="female">Female</option>
                                    <option value="male">Male</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 2: Login Details -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-shield-lock text-primary" aria-hidden="true"></i> Login Details</h2>
                        <span class="badge-soft">Security</span>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="username" class="form-label">Username <span class="req">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo $username; ?>" required minlength="3" maxlength="50" autocomplete="username">
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['username']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback">Username must be at least 3 characters.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text">Min 3 characters. Must be unique.</div>
                            </div>
                            <div class="col-12 col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary uf-btn w-100" id="ufGenUsername">
                                    <i class="bi bi-magic me-1"></i>Suggest from name
                                </button>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label">Password <span class="req">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required minlength="6" maxlength="255" autocomplete="new-password" aria-describedby="passwordHelp ufStrengthLabel">
                                    <button type="button" class="btn btn-outline-secondary" id="ufTogglePassword" title="Show password" aria-label="Show password"><i class="bi bi-eye"></i></button>
                                    <button type="button" class="btn btn-outline-secondary" id="ufGenPassword" title="Generate password" aria-label="Generate password"><i class="bi bi-key"></i></button>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['password']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="uf-strength" id="ufStrength" aria-hidden="true"><span></span></div>
                                <div class="d-flex justify-content-between">
                                    <div class="form-text" id="passwordHelp">Min 6 characters (hashed on save).</div>
                                    <div class="form-text" id="ufStrengthLabel">Strength: —</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="confirm_password" class="form-label">Confirm Password <span class="req">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required minlength="6" maxlength="255" autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" id="ufToggleConfirm" title="Show password" aria-label="Show confirm password"><i class="bi bi-eye"></i></button>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?php echo htmlspecialchars($errors['confirm_password']); ?></div>
                                    <?php else: ?>
                                        <div class="invalid-feedback">Passwords must match.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text" id="ufMatchHint"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufRequireChange">
                                    <label class="form-check-label" for="ufRequireChange">Require password change on first login</label>
                                </div>
                                <div class="form-text"><span class="uf-ui-only">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufTwoFactor">
                                    <label class="form-check-label" for="ufTwoFactor">Two-Factor Authentication</label>
                                </div>
                                <div class="form-text"><span class="uf-ui-only">(UI only)</span></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 3: Profile -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-camera text-primary" aria-hidden="true"></i> Profile Photo</h2>
                        <span class="badge-soft">Optional</span>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <div class="uf-avatar-preview" id="ufAvatarBox" aria-hidden="true">
                                    <span id="ufAvatarInitials"><?php echo htmlspecialchars($initials); ?></span>
                                    <img id="ufAvatarImg" src="" alt="" style="display:none;">
                                </div>
                            </div>
                            <div class="col">
                                <label for="ufProfilePhoto" class="form-label">Upload Profile Photo</label>
                                <input type="file" class="form-control" id="ufProfilePhoto" accept="image/*">
                                <div class="form-text">JPG, PNG, WEBP. Drag &amp; drop supported in modern browsers. <span class="uf-ui-only">(UI only — not saved by current backend)</span></div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="ufReplacePhoto"><i class="bi bi-arrow-repeat me-1"></i>Replace</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="ufClearPhoto"><i class="bi bi-trash me-1"></i>Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 4: Role & Access -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-person-badge text-primary" aria-hidden="true"></i> Role &amp; Access</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="role" class="form-label">User Role <span class="req">*</span></label>
                                <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>" id="role" name="role" required>
                                    <option value="customer" <?php echo $role === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    <option value="staff" <?php echo $role === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                </select>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['role']); ?></div>
                                <?php endif; ?>
                                <div class="form-text">Saved roles: customer, staff, admin.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufJobTitle" class="form-label">Mapped Title</label>
                                <select class="form-select" id="ufJobTitle" aria-describedby="jobHelp">
                                    <option value="">— Optional mapping —</option>
                                    <option value="manager">Manager</option>
                                    <option value="sales">Sales</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="inventory">Inventory</option>
                                    <option value="support">Support</option>
                                    <option value="cs">Customer Service</option>
                                    <option value="custom">Custom Role</option>
                                </select>
                                <div class="form-text" id="jobHelp"><span class="uf-ui-only">(UI only — does not change saved role)</span></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="ufBranch" class="form-label">Branch</label>
                                <input type="text" class="form-control" id="ufBranch" placeholder="Main Branch">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="ufDepartment" class="form-label">Department</label>
                                <input type="text" class="form-control" id="ufDepartment" placeholder="Operations">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="ufDashboard" class="form-label">Default Dashboard</label>
                                <select class="form-select" id="ufDashboard">
                                    <option value="admin">Admin Dashboard</option>
                                    <option value="sales">Sales Overview</option>
                                    <option value="inventory">Inventory</option>
                                    <option value="pos">POS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 5: Permissions (UI) -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-key text-primary" aria-hidden="true"></i> Permissions</h2>
                        <span class="badge-soft">Preview</span>
                    </div>
                    <div class="uf-card-body">
                        <p class="small text-muted mb-3">Permission cards reflect typical access for the selected role. <span class="uf-ui-only">UI only — actual access follows existing role logic.</span></p>
                        <div class="uf-perm-grid" id="ufPermGrid">
                            <?php
                            $modules = ['Dashboard', 'Products', 'Categories', 'Brands', 'Customers', 'Suppliers', 'Inventory', 'Purchases', 'POS', 'Orders', 'Reports', 'Users', 'Settings'];
                            foreach ($modules as $i => $mod):
                                $id = 'ufPerm' . $i;
                            ?>
                            <div class="uf-perm-card" data-module="<?php echo htmlspecialchars(strtolower($mod)); ?>">
                                <div class="title"><?php echo htmlspecialchars($mod); ?></div>
                                <div class="form-check">
                                    <input class="form-check-input uf-perm-read" type="checkbox" id="<?php echo $id; ?>R">
                                    <label class="form-check-label" for="<?php echo $id; ?>R">Read</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input uf-perm-write" type="checkbox" id="<?php echo $id; ?>W">
                                    <label class="form-check-label" for="<?php echo $id; ?>W">Write</label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- SECTION 6: Status -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-toggle2-on text-primary" aria-hidden="true"></i> Status</h2>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="ufAccountStatus" class="form-label">Account Status</label>
                                <select class="form-select" id="ufAccountStatus">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <div class="form-text"><span class="uf-ui-only">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-8 d-flex flex-column justify-content-end gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufEmailVerified" checked>
                                    <label class="form-check-label" for="ufEmailVerified">Email Verified</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufPhoneVerified">
                                    <label class="form-check-label" for="ufPhoneVerified">Phone Verified</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufNotify" checked>
                                    <label class="form-check-label" for="ufNotify">Receive Notifications</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ufReports">
                                    <label class="form-check-label" for="ufReports">Receive Reports</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 7: Security -->
                <section class="uf-card">
                    <div class="uf-card-header">
                        <h2><i class="bi bi-lock text-primary" aria-hidden="true"></i> Security</h2>
                    </div>
                    <div class="uf-card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="ufLoginAttempts" class="form-label">Max Login Attempts</label>
                                <input type="number" class="form-control" id="ufLoginAttempts" value="5" min="1" max="20">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="ufSessionTimeout" class="form-label">Session Timeout (min)</label>
                                <input type="number" class="form-control" id="ufSessionTimeout" value="60" min="5" max="1440">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufIpRestrict" class="form-label">IP Restriction</label>
                                <input type="text" class="form-control" id="ufIpRestrict" placeholder="e.g. 192.168.1.0/24 (optional)">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufExpiry" class="form-label">Account Expiry Date</label>
                                <input type="date" class="form-control" id="ufExpiry">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="ufDevices" class="form-label">Allowed Devices</label>
                                <select class="form-select" id="ufDevices">
                                    <option value="any" selected>Any device</option>
                                    <option value="desktop">Desktop only</option>
                                    <option value="mobile">Mobile only</option>
                                </select>
                            </div>
                        </div>
                        <p class="uf-ui-only mt-2 mb-0">Security extras are visual aids; authentication still uses existing backend logic.</p>
                    </div>
                </section>
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-12 col-xl-3">
                <aside class="uf-side">
                    <div class="uf-card">
                        <div class="uf-card-header"><h2 style="font-size:0.95rem;">Profile Preview</h2></div>
                        <div class="uf-card-body text-center">
                            <div class="uf-avatar-preview" id="ufSideAvatar">
                                <span id="ufSideInitials"><?php echo htmlspecialchars($initials); ?></span>
                                <img id="ufSideAvatarImg" src="" alt="" style="display:none;">
                            </div>
                            <div class="fw-bold" id="ufSideName"><?php echo trim($firstName . ' ' . $lastName) ?: 'New User'; ?></div>
                            <div class="small text-muted" id="ufSideEmail"><?php echo $email !== '' ? $email : 'email@example.com'; ?></div>
                            <div class="mt-2"><span class="uf-role-badge" id="ufSideRoleBadge"><i class="bi bi-shield-check"></i> <span id="ufSideRole"><?php echo ucfirst($role); ?></span></span></div>
                        </div>
                    </div>

                    <div class="uf-card">
                        <div class="uf-card-header"><h2 style="font-size:0.95rem;">Account Status</h2></div>
                        <div class="uf-card-body">
                            <ul class="uf-side-list mb-0">
                                <li><span class="k">Status</span><span class="v" id="ufSideStatus">Active</span></li>
                                <li><span class="k">Username</span><span class="v" id="ufSideUser"><?php echo $username !== '' ? $username : '—'; ?></span></li>
                                <li><span class="k">Last Login</span><span class="v">Never</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="uf-card">
                        <div class="uf-card-header"><h2 style="font-size:0.95rem;">Permissions Summary</h2></div>
                        <div class="uf-card-body">
                            <p class="small mb-0" id="ufPermSummary">Select a role to preview typical module access.</p>
                        </div>
                    </div>

                    <div class="uf-card">
                        <div class="uf-card-header"><h2 style="font-size:0.95rem;">Security Score</h2></div>
                        <div class="uf-card-body text-center">
                            <div class="uf-score-ring" id="ufScoreRing" style="--score: 0%;"><span id="ufScoreVal">0</span></div>
                            <div class="small text-muted" id="ufScoreHint">Add a strong password to improve score.</div>
                            <div class="small mt-2">Password: <strong id="ufSidePwdStrength">—</strong></div>
                        </div>
                    </div>

                    <div class="uf-card">
                        <div class="uf-card-header"><h2 style="font-size:0.95rem;">Quick Tips</h2></div>
                        <div class="uf-card-body">
                            <ul class="small text-muted mb-0 ps-3">
                                <li class="mb-1">Username and email must be unique.</li>
                                <li class="mb-1">Password is hashed by existing backend logic.</li>
                                <li class="mb-1">Use Admin only for trusted operators.</li>
                                <li>Staff suits POS / warehouse operators.</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </form>

    <div class="uf-sticky-bar" role="toolbar" aria-label="User form actions">
        <div class="uf-sticky-inner">
            <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="btn btn-outline-secondary uf-btn">Cancel</a>
            <button type="reset" form="userCreateForm" class="btn btn-outline-secondary uf-btn" id="ufResetBtn">Reset</button>
            <button type="button" class="btn btn-outline-primary uf-btn" data-bs-toggle="modal" data-bs-target="#ufPreviewModal">Preview</button>
            <button type="button" class="btn btn-outline-secondary uf-btn" id="ufSaveAndNew">Save &amp; New</button>
            <button type="submit" form="userCreateForm" class="btn uf-btn uf-btn-primary" id="ufSaveClose">
                <span class="spinner-border spinner-border-sm d-none" id="ufSaveSpinner" role="status" aria-hidden="true"></span>
                <i class="bi bi-check2-circle me-1"></i>Save User
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="ufPreviewModal" tabindex="-1" aria-labelledby="ufPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="ufPreviewModalLabel">User Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="uf-avatar-preview mx-auto" id="ufModalAvatar">
                    <span id="ufModalInitials"><?php echo htmlspecialchars($initials); ?></span>
                </div>
                <h5 class="mb-1" id="ufModalName"><?php echo trim($firstName . ' ' . $lastName) ?: 'New User'; ?></h5>
                <p class="text-muted mb-2" id="ufModalEmail"><?php echo $email !== '' ? $email : 'email@example.com'; ?></p>
                <span class="uf-role-badge" id="ufModalRole"><?php echo ucfirst($role); ?></span>
                <hr>
                <div class="text-start small">
                    <div><strong>Username:</strong> <span id="ufModalUser"><?php echo $username !== '' ? $username : '—'; ?></span></div>
                    <div><strong>Role value:</strong> <span id="ufModalRoleVal"><?php echo htmlspecialchars($role); ?></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="userCreateForm" class="btn btn-primary">Save User</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var form = document.getElementById('userCreateForm');
    var firstName = document.getElementById('first_name');
    var lastName = document.getElementById('last_name');
    var username = document.getElementById('username');
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirm_password');
    var role = document.getElementById('role');
    var progressBar = document.getElementById('ufFormProgress');

    function showToast(msg, type) {
        var host = document.getElementById('ufToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'uf-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
    }

    function initialsFrom() {
        var f = (firstName && firstName.value || '').trim();
        var l = (lastName && lastName.value || '').trim();
        var ini = ((f.charAt(0) || '') + (l.charAt(0) || '')).toUpperCase() || 'U';
        return ini;
    }

    function syncPreview() {
        var name = ((firstName && firstName.value) || '') + ' ' + ((lastName && lastName.value) || '');
        name = name.trim() || 'New User';
        var em = (email && email.value) || 'email@example.com';
        var un = (username && username.value) || '—';
        var roleText = role ? role.options[role.selectedIndex].text : 'Customer';
        var roleVal = role ? role.value : 'customer';
        var ini = initialsFrom();

        ['ufSideName', 'ufModalName'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.textContent = name;
        });
        ['ufSideEmail', 'ufModalEmail'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.textContent = em;
        });
        ['ufSideUser', 'ufModalUser'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.textContent = un;
        });
        ['ufSideRole', 'ufModalRole'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.textContent = roleText;
        });
        var rv = document.getElementById('ufModalRoleVal');
        if (rv) rv.textContent = roleVal;
        ['ufAvatarInitials', 'ufSideInitials', 'ufModalInitials'].forEach(function(id) {
            var el = document.getElementById(id); if (el) el.textContent = ini;
        });
        var display = document.getElementById('ufDisplayName');
        if (display && document.activeElement !== display) {
            display.value = name === 'New User' ? '' : name;
        }
        var st = document.getElementById('ufAccountStatus');
        var sideSt = document.getElementById('ufSideStatus');
        if (st && sideSt) sideSt.textContent = st.options[st.selectedIndex].text;
    }

    function passwordScore(pw) {
        var s = 0;
        if (!pw) return 0;
        if (pw.length >= 6) s += 1;
        if (pw.length >= 10) s += 1;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) s += 1;
        if (/\d/.test(pw)) s += 1;
        if (/[^A-Za-z0-9]/.test(pw)) s += 1;
        return Math.min(s, 4);
    }

    function updateStrength() {
        var pw = password ? password.value : '';
        var score = passwordScore(pw);
        var bar = document.getElementById('ufStrength');
        var label = document.getElementById('ufStrengthLabel');
        var side = document.getElementById('ufSidePwdStrength');
        var classes = ['', 'is-weak', 'is-fair', 'is-good', 'is-strong'];
        var names = ['—', 'Weak', 'Fair', 'Good', 'Strong'];
        if (bar) {
            bar.className = 'uf-strength ' + (classes[score] || '');
        }
        if (label) label.textContent = 'Strength: ' + names[score];
        if (side) side.textContent = names[score];

        var match = document.getElementById('ufMatchHint');
        if (match && confirmPassword) {
            if (!confirmPassword.value) match.textContent = '';
            else if (confirmPassword.value === pw) {
                match.textContent = 'Passwords match';
                match.className = 'form-text text-success';
            } else {
                match.textContent = 'Passwords do not match';
                match.className = 'form-text text-danger';
            }
        }
        updateSecurityScore();
    }

    function updateSecurityScore() {
        var score = 0;
        if (password && password.value.length >= 6) score += 25;
        if (passwordScore(password ? password.value : '') >= 3) score += 25;
        if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) score += 15;
        if (username && username.value.length >= 3) score += 15;
        if (document.getElementById('ufTwoFactor') && document.getElementById('ufTwoFactor').checked) score += 10;
        if (document.getElementById('ufRequireChange') && document.getElementById('ufRequireChange').checked) score += 10;
        score = Math.min(100, score);
        var ring = document.getElementById('ufScoreRing');
        var val = document.getElementById('ufScoreVal');
        var hint = document.getElementById('ufScoreHint');
        if (ring) ring.style.setProperty('--score', score + '%');
        if (val) val.textContent = String(score);
        if (hint) {
            if (score >= 80) hint.textContent = 'Strong account readiness.';
            else if (score >= 50) hint.textContent = 'Good — consider 2FA and a longer password.';
            else hint.textContent = 'Add a strong password to improve score.';
        }
    }

    function applyRolePermissions() {
        var r = role ? role.value : 'customer';
        var summary = document.getElementById('ufPermSummary');
        var cards = document.querySelectorAll('.uf-perm-card');
        var presets = {
            customer: { read: ['dashboard', 'orders'], write: [] },
            staff: { read: ['dashboard', 'products', 'categories', 'brands', 'customers', 'inventory', 'pos', 'orders'], write: ['products', 'inventory', 'pos', 'orders'] },
            admin: { read: 'all', write: 'all' }
        };
        var p = presets[r] || presets.customer;
        cards.forEach(function(card) {
            var mod = card.getAttribute('data-module') || '';
            var read = card.querySelector('.uf-perm-read');
            var write = card.querySelector('.uf-perm-write');
            var canRead = p.read === 'all' || (p.read && p.read.indexOf(mod) !== -1);
            var canWrite = p.write === 'all' || (p.write && p.write.indexOf(mod) !== -1);
            if (read) read.checked = !!canRead;
            if (write) write.checked = !!canWrite;
        });
        if (summary) {
            if (r === 'admin') summary.textContent = 'Administrator: full module access (existing role logic).';
            else if (r === 'staff') summary.textContent = 'Staff: catalog, inventory, POS, and orders (typical).';
            else summary.textContent = 'Customer: storefront account access (typical).';
        }
    }

    function updateProgress() {
        var filled = 0;
        var total = 7;
        if (firstName && firstName.value.trim()) filled++;
        if (lastName && lastName.value.trim()) filled++;
        if (username && username.value.trim().length >= 3) filled++;
        if (email && email.value.trim()) filled++;
        if (password && password.value.length >= 6) filled++;
        if (confirmPassword && confirmPassword.value && confirmPassword.value === (password ? password.value : '')) filled++;
        if (role && role.value) filled++;
        var pct = Math.round((filled / total) * 100);
        if (progressBar) progressBar.style.width = pct + '%';
        syncPreview();
        updateStrength();
    }

    function toggleVisibility(input, btn) {
        if (!input || !btn) return;
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    }

    var togglePw = document.getElementById('ufTogglePassword');
    var toggleCf = document.getElementById('ufToggleConfirm');
    if (togglePw) togglePw.addEventListener('click', function() { toggleVisibility(password, togglePw); });
    if (toggleCf) toggleCf.addEventListener('click', function() { toggleVisibility(confirmPassword, toggleCf); });

    var genPw = document.getElementById('ufGenPassword');
    if (genPw) {
        genPw.addEventListener('click', function() {
            var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
            var out = '';
            for (var i = 0; i < 12; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
            if (password) password.value = out;
            if (confirmPassword) confirmPassword.value = out;
            if (password) password.dispatchEvent(new Event('input'));
            showToast('Strong password generated — copy it before saving.', 'success');
            updateProgress();
        });
    }

    var genUser = document.getElementById('ufGenUsername');
    if (genUser) {
        genUser.addEventListener('click', function() {
            var f = (firstName && firstName.value || 'user').toLowerCase().replace(/[^a-z0-9]/g, '');
            var l = (lastName && lastName.value || '').toLowerCase().replace(/[^a-z0-9]/g, '');
            var base = (f + (l ? '.' + l : '')).substring(0, 40) || 'user';
            if (username) {
                username.value = base + Math.floor(Math.random() * 90 + 10);
                username.dispatchEvent(new Event('input'));
            }
            showToast('Username suggested', 'info');
            updateProgress();
        });
    }

    function setAvatar(file) {
        var imgIds = ['ufAvatarImg', 'ufSideAvatarImg'];
        var iniIds = ['ufAvatarInitials', 'ufSideInitials'];
        if (!file) {
            imgIds.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) { el.style.display = 'none'; el.removeAttribute('src'); }
            });
            iniIds.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.style.display = '';
            });
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            showToast('Image larger than 2MB — consider compressing.', 'warning');
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            imgIds.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) { el.src = e.target.result; el.style.display = 'block'; }
            });
            iniIds.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
        };
        reader.readAsDataURL(file);
    }

    var photo = document.getElementById('ufProfilePhoto');
    if (photo) photo.addEventListener('change', function() {
        setAvatar(photo.files && photo.files[0] ? photo.files[0] : null);
    });
    var replacePhoto = document.getElementById('ufReplacePhoto');
    if (replacePhoto) replacePhoto.addEventListener('click', function() { if (photo) photo.click(); });
    var clearPhoto = document.getElementById('ufClearPhoto');
    if (clearPhoto) clearPhoto.addEventListener('click', function() {
        if (photo) photo.value = '';
        setAvatar(null);
        showToast('Photo cleared', 'info');
    });

    ['first_name', 'last_name', 'username', 'email', 'password', 'confirm_password', 'role', 'ufAccountStatus', 'ufTwoFactor', 'ufRequireChange'].forEach(function(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', updateProgress);
        el.addEventListener('change', function() {
            updateProgress();
            if (id === 'role') applyRolePermissions();
        });
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            var ok = true;
            function need(el, cond) {
                if (!el) return;
                if (!cond) { el.classList.add('is-invalid'); ok = false; }
                else el.classList.remove('is-invalid');
            }
            need(firstName, firstName && firstName.value.trim());
            need(lastName, lastName && lastName.value.trim());
            need(username, username && username.value.trim().length >= 3);
            need(email, email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value));
            need(password, password && password.value.length >= 6);
            need(confirmPassword, confirmPassword && password && confirmPassword.value === password.value && confirmPassword.value.length >= 6);
            need(role, role && role.value);
            if (!ok) {
                e.preventDefault();
                showToast('Please fix the highlighted fields.', 'error');
                return false;
            }
            var spinner = document.getElementById('ufSaveSpinner');
            if (spinner) spinner.classList.remove('d-none');
            showToast('Creating user…', 'info');
        });
        form.addEventListener('reset', function() {
            setTimeout(function() {
                setAvatar(null);
                applyRolePermissions();
                updateProgress();
                showToast('Form reset', 'info');
            }, 0);
        });
    }

    var draftBtn = document.getElementById('ufSaveDraftBtn');
    if (draftBtn) draftBtn.addEventListener('click', function() {
        var st = document.getElementById('ufAccountStatus');
        if (st) st.value = 'inactive';
        syncPreview();
        showToast('Marked as Inactive in preview — click Save User to submit (role/status still follow backend fields).', 'warning');
    });

    var saveAndNew = document.getElementById('ufSaveAndNew');
    if (saveAndNew) saveAndNew.addEventListener('click', function() {
        showToast('Save & New: submit this user, then create another.', 'info');
        if (form) form.requestSubmit ? form.requestSubmit() : form.submit();
    });

    applyRolePermissions();
    updateProgress();
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
