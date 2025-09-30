<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<section class="py-5 bg-light">
  <div class="container">
    <style>
      .policy-content { white-space: pre-wrap; font-size: 1rem; line-height: 1.6; }
      .policy-content * { font-size: inherit !important; line-height: inherit !important; }
      .policy-content h1 { font-size: 1.5rem !important; }
      .policy-content h2 { font-size: 1.25rem !important; }
      .policy-content h3 { font-size: 1.125rem !important; }
      .policy-content h4 { font-size: 1.05rem !important; }
      .policy-content p, .policy-content li { margin-bottom: 0.5rem; }
      .policy-content img, .policy-content video { max-width: 100%; height: auto; }
    </style>
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($title ?? 'FAQ'); ?></li>
      </ol>
    </nav>

    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h3 mb-4"><?php echo htmlspecialchars($title ?? 'Frequently Asked Questions'); ?></h1>
        <div class="policy-content">
          <?php if (!empty($content)) { echo $content; } else { ?>
            <div class="alert alert-info mb-0">No FAQ content available.</div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
