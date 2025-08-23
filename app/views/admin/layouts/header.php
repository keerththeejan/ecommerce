<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Track user activity
if (isset($_SESSION['user_id'])) {
    // Get user's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Get user agent
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Update last activity time in session
    $_SESSION['last_activity'] = date('Y-m-d H:i:s');

    // Update last activity in database
    try {
        $userModel = new User();
        $userModel->updateActivity($_SESSION['user_id'], $ip, $userAgent);
    } catch (Exception $e) {
        // Log error but don't show to user
        error_log('Error updating user activity: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sivakamy</title>
    <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
    <style>
        /* Ensure last items in sidebar are reachable */
        #sidebar .position-sticky { padding-bottom: 80px; box-sizing: border-box; }

        /* Scrollbar styling - Sidebar and Main */
        /* Firefox */
        #sidebar .position-sticky { scrollbar-width: thin; scrollbar-color: #6c757d rgba(255,255,255,0.06); }
        main { scrollbar-width: thin; scrollbar-color: #adb5bd rgba(0,0,0,0.06); }

        /* WebKit (Chrome, Edge, Safari) */
        #sidebar .position-sticky::-webkit-scrollbar,
        main::-webkit-scrollbar { width: 10px; height: 10px; }

        #sidebar .position-sticky::-webkit-scrollbar-track { background: #1f2429; border-radius: 8px; }
        #sidebar .position-sticky::-webkit-scrollbar-thumb {
            background: #5a6b7a; border-radius: 8px; border: 2px solid #1f2429;
        }
        #sidebar .position-sticky::-webkit-scrollbar-thumb:hover { background: #7b8ea1; }

        main::-webkit-scrollbar-track { background: #e9ecef; border-radius: 8px; }
        main::-webkit-scrollbar-thumb {
            background: #adb5bd; border-radius: 8px; border: 2px solid #e9ecef;
        }
        main::-webkit-scrollbar-thumb:hover { background: #9099a2; }

        /* Mobile sidebar behavior */
        @media (max-width: 767.98px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 260px;
                z-index: 1050;
                overflow-y: auto;
                background-color: #212529;
                /* match bg-dark */
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            /* Prevent body from shifting when sidebar opens */
            body.sidebar-open {
                overflow: hidden;
            }

            /* Add a simple backdrop */
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1049;
                display: none;
            }

            .sidebar-backdrop.active {
                display: block;
            }
        }

        /* Desktop: fix sidebar; scroll only main content */
        @media (min-width: 768px) {
            html, body { height: 100%; }
            body { overflow: hidden; }
            #sidebar {
                position: fixed;
                top: 0; left: 0;
                height: 100vh;
                width: 260px; /* match mobile width for consistency */
                overflow: hidden; /* prevent sidebar from scrolling with page */
                overscroll-behavior: contain;
            }
            #sidebar .position-sticky { height: 100vh; overflow: auto; overscroll-behavior: contain; }
            main {
                margin-left: 260px; /* create space for fixed sidebar */
                height: 100vh;
                overflow: auto;
                overscroll-behavior: contain;
                padding-bottom: 32px; /* ensure last field is reachable */
                box-sizing: border-box;
                scrollbar-gutter: stable both-edges;
            }
        }

        /* Policy slide-in panel (covers white main area) */
        .policy-panel {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0; /* default for mobile: full width */
            background: #fff;
            transform: translateX(100%);
            transition: transform .3s ease;
            z-index: 1060; /* above main, below modal backdrop (1050-1060 area) */
            box-shadow: -2px 0 12px rgba(0,0,0,.15);
            display: flex;
            flex-direction: column;
        }
        .policy-panel.show { transform: translateX(0); }
        .policy-panel .policy-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
            display: flex; align-items: center; justify-content: space-between;
        }
        .policy-panel .policy-body { padding: 1rem 1.25rem; overflow: auto; }

        /* On desktop, offset panel to start after fixed sidebar */
        @media (min-width: 768px) {
            .policy-panel { left: 260px; }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Admin Dashboard</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=home&action=admin">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex">
                                <i class="fas fa-box me-2"></i>
                                Products
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex">
                                <i class="fas fa-tags me-2"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex">
                                <i class="fas fa-building me-2"></i>
                                Brands
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex">
                                <i class="fas fa-globe-americas me-2"></i>
                                Country of Origin
                            </a>
                        </li>

                        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-white" href="#" id="purchaseDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-money-bill-wave me-2"></i> Purchase
    </a>
    <ul class="dropdown-menu" aria-labelledby="purchaseDropdown">
        <li><a class="dropdown-item" href="?controller=purchase&action=create">Purchase with customer</a></li>
        <li><a class="dropdown-item" href="?controller=ListPurchaseController">List Purchase </a></li>
        <li><a class="dropdown-item" href="?controller=purchase&action=purchase2">Add Purchase</a></li>
        <li><a class="dropdown-item" href="?controller=purchase&action=purchase3">List Purchase Return</a></li>
        
    </ul>
</li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=banner">
                                <i class="fas fa-images me-2"></i>
                                Hero Banners
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=supplier&action=index" id="suppliersLink">
                                <i class="fas fa-truck me-2"></i>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex">
                                <i class="fas fa-users me-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=report&action=index">
                                <i class="fas fa-chart-bar me-2"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=newsletter&action=adminIndex">
                                <i class="fas fa-envelope-open-text me-2"></i>
                                Newsletter
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=mail&action=index">
                                <i class="fas fa-envelope me-2"></i>
                                Mail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=active">
                                <i class="fas fa-user-check me-2"></i>
                                Active Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=invoice&action=create">
                                <i class="fas fa-user-check me-2"></i>
                                Billing & Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=setting&action=index">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=tax&action=index">
                                <i class="fas fa-percentage me-2"></i>
                                Tax Management
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-store me-2"></i>
                                View Store
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=aboutStore">
                                <i class="fas fa-globe-americas me-2"></i>
                                Manage About Store
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=contactinfo&action=index">
                                <i class="fas fa-address-book me-2"></i>
                                Contact Info
                            </a>
                        </li>
                           
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=PaymentDue&action=index">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Payment Dues
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" id="policyLink" href="<?php echo BASE_URL; ?>?controller=policy&action=index">
                                <i class="fas fa-file-contract me-2"></i>
                                Policy
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" id="clearCookiesBtn">
                                <i class="fas fa-cookie-bite me-2"></i>
                                Clear Cookies
                            </a>
                            <script>
                                document.getElementById('clearCookiesBtn').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    if (confirm('Are you sure you want to clear all cookies? This will log out all users.')) {
                                        fetch('<?php echo BASE_URL; ?>?controller=home&action=clearCookies', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                },
                                                credentials: 'same-origin'
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    // Show success message
                                                    const alertDiv = document.createElement('div');
                                                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                                    alertDiv.role = 'alert';
                                                    alertDiv.innerHTML = `
                                                ${data.message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            `;

                                                    // Insert the alert at the top of the main content
                                                    const mainContent = document.querySelector('main');
                                                    if (mainContent) {
                                                        mainContent.insertBefore(alertDiv, mainContent.firstChild);

                                                        // Auto-dismiss after 5 seconds
                                                        setTimeout(() => {
                                                            const bsAlert = new bootstrap.Alert(alertDiv);
                                                            bsAlert.close();
                                                        }, 5000);
                                                    }
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('An error occurred while clearing cookies.');
                                            });
                                    }
                                });
                            </script>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=logout">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Backdrop for mobile -->
            <div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary me-3 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation" id="sidebarToggleBtn">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="h2 mb-0">Admin Dashboard</h1>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile">My Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Policy slide-in panel -->
                <div id="policyPanel" class="policy-panel" aria-hidden="true">
                    <div class="policy-header">
                        <h5 class="mb-0">Policy</h5>
                        <button type="button" class="btn-close" id="policyCloseBtn" aria-label="Close"></button>
                    </div>
                    <div class="policy-body">
                        <ul class="nav nav-pills mb-3" id="policyTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-privacy" data-policy-target="#section-privacy" type="button" role="tab">Privacy Policy</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-terms" data-policy-target="#section-terms" type="button" role="tab">Terms of Service</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-faq" data-policy-target="#section-faq" type="button" role="tab">FAQ</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="section-privacy" role="tabpanel" aria-labelledby="tab-privacy">
                                <div class="mb-3">
                                    <label for="privacyText" class="form-label">Privacy Policy</label>
                                    <textarea id="privacyText" class="form-control" rows="8" placeholder="Write your Privacy Policy here..."></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-primary policy-save-btn" type="button" data-key="privacy">Save</button>
                                    <small class="text-muted" id="privacyStatus"></small>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="section-terms" role="tabpanel" aria-labelledby="tab-terms">
                                <div class="mb-3">
                                    <label for="termsText" class="form-label">Terms of Service</label>
                                    <textarea id="termsText" class="form-control" rows="8" placeholder="Write your Terms of Service here..."></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-primary policy-save-btn" type="button" data-key="terms">Save</button>
                                    <small class="text-muted" id="termsStatus"></small>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="section-faq" role="tabpanel" aria-labelledby="tab-faq">
                                <div class="mb-3">
                                    <label for="faqText" class="form-label">FAQ</label>
                                    <textarea id="faqText" class="form-control" rows="8" placeholder="Write your FAQs here..."></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-primary policy-save-btn" type="button" data-key="faq">Save</button>
                                    <small class="text-muted" id="faqStatus"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php flash('product_success'); ?>
                <?php flash('product_error'); ?>
                <?php flash('category_success'); ?>
                <?php flash('category_error'); ?>
                <?php flash('order_success'); ?>
                <?php flash('order_error'); ?>
                <?php flash('user_success'); ?>
                <?php flash('user_error'); ?>
                <?php flash('setting_success'); ?>
                <?php flash('setting_error'); ?>
                <?php flash('brand_success'); ?>
                <?php flash('brand_error'); ?>
                <?php flash('banner_success'); ?>
                <?php flash('banner_error'); ?>

                <script>
                    // Enhance mobile sidebar UX: manage backdrop and body scroll
                    (function() {
                        const sidebar = document.getElementById('sidebar');
                        const backdrop = document.getElementById('sidebarBackdrop');
                        const toggleBtn = document.getElementById('sidebarToggleBtn');

                        function updateState() {
                            const isOpen = sidebar.classList.contains('show');
                            document.body.classList.toggle('sidebar-open', isOpen);
                            if (backdrop) backdrop.classList.toggle('active', isOpen);
                        }

                        document.addEventListener('shown.bs.collapse', function(e) {
                            if (e.target === sidebar) updateState();
                        });
                        document.addEventListener('hidden.bs.collapse', function(e) {
                            if (e.target === sidebar) updateState();
                        });
                        if (backdrop) {
                            backdrop.addEventListener('click', function() {
                                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(sidebar);
                                bsCollapse.hide();
                            });
                        }
                        // Close sidebar after clicking a link on mobile
                        sidebar.addEventListener('click', function(e) {
                            if (window.innerWidth < 768 && e.target.closest('a.nav-link')) {
                                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(sidebar);
                                bsCollapse.hide();
                            }
                        });
                    })();
                </script>

                <script>
                    // Policy panel open/close handlers
                    (function(){
                        const policyLink = document.getElementById('policyLink');
                        const policyPanel = document.getElementById('policyPanel');
                        const policyCloseBtn = document.getElementById('policyCloseBtn');

                        if (!policyLink || !policyPanel) return;

                        function openPolicy(){
                            policyPanel.classList.add('show');
                            policyPanel.setAttribute('aria-hidden', 'false');
                            // Load existing content once panel opens
                            try {
                                fetch('<?php echo BASE_URL; ?>?controller=policy&action=get', {
                                    credentials: 'same-origin',
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                })
                                .then(r => r.json())
                                .then(d => {
                                    if (d && d.success && d.data){
                                        const { privacy, terms, faq } = d.data;
                                        const privacyEl = document.getElementById('privacyText');
                                        const termsEl = document.getElementById('termsText');
                                        const faqEl = document.getElementById('faqText');
                                        if (privacyEl) privacyEl.value = privacy || '';
                                        if (termsEl) termsEl.value = terms || '';
                                        if (faqEl) faqEl.value = faq || '';
                                    }
                                })
                                .catch(() => {});
                            } catch(_){}
                        }
                        function closePolicy(){
                            policyPanel.classList.remove('show');
                            policyPanel.setAttribute('aria-hidden', 'true');
                        }

                        policyLink.addEventListener('click', function(e){
                            // prevent navigating away; use panel instead
                            e.preventDefault();
                            openPolicy();
                        });

                        if (policyCloseBtn){
                            policyCloseBtn.addEventListener('click', function(){
                                closePolicy();
                            });
                        }

                        // ESC to close
                        document.addEventListener('keydown', function(e){
                            if (e.key === 'Escape') closePolicy();
                        });
                    })();
                </script>

                <script>
                    // Simple tabs for Policy panel
                    (function(){
                        const tabs = document.querySelectorAll('#policyTabs .nav-link');
                        const panes = document.querySelectorAll('.policy-body .tab-pane');
                        if (!tabs.length) return;
                        tabs.forEach(btn => {
                            btn.addEventListener('click', function(){
                                // activate button
                                tabs.forEach(b => b.classList.remove('active'));
                                this.classList.add('active');
                                // show target pane
                                const target = document.querySelector(this.getAttribute('data-policy-target'));
                                panes.forEach(p => p.classList.remove('show','active'));
                                if (target){
                                    target.classList.add('show','active');
                                }
                            });
                        });
                    })();
                </script>

                <script>
                    // Save buttons for policy sections
                    (function(){
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        function setStatus(id, msg, ok){
                            const el = document.getElementById(id);
                            if (!el) return;
                            el.textContent = msg;
                            el.className = ok ? 'text-success' : 'text-danger';
                            if (msg) setTimeout(()=>{ el.textContent=''; el.className='text-muted'; }, 3000);
                        }
                        function saveSection(key, content){
                            return fetch('<?php echo BASE_URL; ?>?controller=policy&action=save', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-Token': csrf
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify({ key, content })
                            }).then(r=>r.json());
                        }

                        document.addEventListener('click', function(e){
                            const btn = e.target.closest('.policy-save-btn');
                            if (!btn) return;
                            const key = btn.getAttribute('data-key');
                            const map = { privacy: ['privacyText','privacyStatus'], terms: ['termsText','termsStatus'], faq: ['faqText','faqStatus'] };
                            if (!map[key]) return;
                            const [taId, statusId] = map[key];
                            const ta = document.getElementById(taId);
                            if (!ta) return;
                            const original = btn.innerHTML;
                            btn.disabled = true;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving';
                            setStatus(statusId, '', true);
                            saveSection(key, ta.value)
                                .then(d=>{
                                    if (d && d.success){
                                        setStatus(statusId, 'Saved', true);
                                    } else {
                                        setStatus(statusId, d?.message || 'Save failed', false);
                                    }
                                })
                                .catch(()=> setStatus(statusId, 'Error saving', false))
                                .finally(()=>{
                                    btn.disabled = false;
                                    btn.innerHTML = original;
                                });
                        });
                    })();
                </script>