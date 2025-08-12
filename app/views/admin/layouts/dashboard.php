<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <style>
    :root{ --sidebar-w:280px; }

    html,body{ height:100%; }

    /* Sidebar */
    .sidebar{
      position:fixed; top:0; left:0; bottom:0;
      width:var(--sidebar-w);
      background:#343a40; color:#fff;
      z-index:1040; overflow-y:auto; -webkit-overflow-scrolling:touch;
      transition:transform .3s ease, box-shadow .3s ease;
      transform:translateX(-100%);
      will-change:transform;
    }
    .sidebar.show{
      transform:translateX(0);
      box-shadow:5px 0 15px rgba(0,0,0,.15);
    }
    .sidebar .nav-link{
      color:rgba(255,255,255,.85);
      padding:12px 20px; margin:3px 10px; border-radius:6px;
      transition:all .2s; white-space:nowrap;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active{
      background:rgba(255,255,255,.15); color:#fff;
      padding-left:26px;
    }
    .sidebar .nav-link i{ margin-right:12px; width:20px; text-align:center; }

    /* Backdrop (mobile) */
    .sidebar-backdrop{
      position:fixed; inset:0; background:rgba(0,0,0,.5);
      z-index:1039; opacity:0; visibility:hidden;
      transition:opacity .25s ease, visibility .25s ease;
    }
    .sidebar-backdrop.active{ opacity:1; visibility:visible; }

    /* Top bar */
    .topbar{
      position:sticky; top:0; z-index:1020;
      background:#f8f9fa; border-bottom:1px solid rgba(0,0,0,.08);
    }

    /* Main content */
    .main-content{
      padding:20px; min-height:calc(100vh - 56px);
      transition:transform .3s ease, margin-left .3s ease;
      background:#fff;
    }

    /* Mobile */
    @media (max-width: 991.98px){
      body.sidebar-open{ overflow:hidden; }
      body.sidebar-open .main-content{ transform:translateX(var(--sidebar-w)); }
      #sidebarToggle{
        display:inline-flex !important; align-items:center; justify-content:center;
        width:44px; height:44px; border:0; background:transparent;
      }
    }

    /* Desktop */
    @media (min-width: 992px){
      .sidebar{ transform:translateX(0); position:sticky; top:0; }
      .layout-shell{ margin-left:var(--sidebar-w); }
      #sidebarToggle{ display:none !important; }
      .sidebar-backdrop{ display:none !important; }
    }

    /* Optional scrollbar style */
    .sidebar::-webkit-scrollbar{ width:8px; }
    .sidebar::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.2); border-radius:8px; }
    .sidebar::-webkit-scrollbar-track{ background:transparent; }
  </style>
</head>
<body>
  <!-- Backdrop (mobile only) -->
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- Sidebar (off-canvas on mobile, sticky on desktop) -->
  <aside class="sidebar" id="sidebar" aria-hidden="true" aria-label="Sidebar Navigation">
    <div class="d-flex flex-column p-3">
      <a href="<?php echo BASE_URL; ?>admin" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <span class="fs-4">Admin Panel</span>
      </a>
      <hr class="border-secondary">
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a href="<?php echo BASE_URL; ?>admin" class="nav-link <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/banners" class="nav-link <?php echo $current_page == 'banners' ? 'active' : ''; ?>">
            <i class="fas fa-images"></i> Banners
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/products" class="nav-link <?php echo $current_page == 'products' ? 'active' : ''; ?>">
            <i class="fas fa-box"></i> Products
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/categories" class="nav-link <?php echo $current_page == 'categories' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> Categories
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/orders" class="nav-link <?php echo $current_page == 'orders' ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i> Orders
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/users" class="nav-link <?php echo $current_page == 'users' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Users
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/settings" class="nav-link <?php echo $current_page == 'settings' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL; ?>admin/tax" class="nav-link <?php echo $current_page == 'tax' ? 'active' : ''; ?>">
            <i class="fas fa-percentage"></i> Tax Management
          </a>
        </li>
      </ul>
      <hr class="border-secondary">
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user-circle fs-4 me-2"></i>
          <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">Sign out</a></li>
        </ul>
      </div>
    </div>
  </aside>

  <!-- Content shell (desktop shifts right; mobile full-width) -->
  <div class="layout-shell">
    <!-- Top navigation -->
    <nav class="navbar topbar px-3">
      <button class="btn d-lg-none" id="sidebarToggle" aria-label="Open sidebar" aria-controls="sidebar" aria-expanded="false">
        <i class="fas fa-bars"></i>
      </button>
      <div class="ms-auto d-flex align-items-center">
        <span class="me-3">Welcome, Admin</span>
        <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Profile">
      </div>
    </nav>

    <!-- Page content -->
    <main class="main-content">
      <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
      <?php endif; ?>
      <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
      <?php endif; ?>

      <?php echo $content; ?>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const body = document.body;
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('sidebarToggle');
      const backdrop = document.getElementById('sidebarBackdrop');

      function openSidebar(){
        sidebar.classList.add('show');
        body.classList.add('sidebar-open');
        sidebar.setAttribute('aria-hidden','false');
        if(backdrop) backdrop.classList.add('active');
        if(toggleBtn) toggleBtn.setAttribute('aria-expanded','true');
      }
      function closeSidebar(){
        sidebar.classList.remove('show');
        body.classList.remove('sidebar-open');
        sidebar.setAttribute('aria-hidden','true');
        if(backdrop) backdrop.classList.remove('active');
        if(toggleBtn) toggleBtn.setAttribute('aria-expanded','false');
      }
      function toggleSidebar(){
        if(sidebar.classList.contains('show')) closeSidebar();
        else openSidebar();
      }

      // Mobile toggle
      if (toggleBtn){
        toggleBtn.addEventListener('click', function(e){
          e.preventDefault();
          toggleSidebar();
        });
      }

      // Backdrop click to close
      if (backdrop){
        backdrop.addEventListener('click', closeSidebar);
      }

      // ESC to close
      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){ closeSidebar(); }
      });

      // Close after clicking a nav link on mobile
      document.querySelectorAll('.sidebar .nav-link').forEach(link=>{
        link.addEventListener('click', function(){
          if (window.innerWidth < 992){ closeSidebar(); }
        });
      });

      // Mark active by URL (in addition to PHP)
      const current = location.href;
      document.querySelectorAll('.sidebar .nav-link').forEach(a=>{
        if (a.href === current){ a.classList.add('active'); }
      });

      // Ensure correct initial state for mobile/desktop
      function applyLayout(){
        if (window.innerWidth >= 992){
          // Desktop: sidebar visible, no backdrop, content not shifted by JS
          sidebar.classList.add('show');
          body.classList.remove('sidebar-open');
          if (backdrop) backdrop.classList.remove('active');
          sidebar.setAttribute('aria-hidden','false');
        }else{
          // Mobile: sidebar hidden by default; content full width and visible
          sidebar.classList.remove('show');
          body.classList.remove('sidebar-open');
          if (backdrop) backdrop.classList.remove('active');
          sidebar.setAttribute('aria-hidden','true');
        }
      }
      window.addEventListener('resize', applyLayout);
      applyLayout(); // run once on load
    });
  </script>
</body>
</html>
