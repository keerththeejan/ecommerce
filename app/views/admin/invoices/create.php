<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-md-12">
      <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-success text-white">
          <h3 class="card-title mb-0">Option 1: Manual Bill (From Products)</h3>
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
          <p>Create a new bill by selecting products manually (fast via POS interface).</p>
          <a href="<?php echo BASE_URL; ?>?controller=pos&action=index" class="btn btn-success align-self-start">
            <i class="fas fa-cash-register"></i> Start Manual Billing (POS)
          </a>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title mb-0">Option 2: Create From Order (Add Bill)</h3>
        </div>
        <div class="card-body">
          <?php flash('invoice_success'); ?>
          <?php flash('invoice_error', '', 'alert alert-danger'); ?>

          <form method="post" action="<?php echo BASE_URL; ?>?controller=invoice&action=create">
            <div class="mb-3">
              <label for="order_id" class="form-label">Order ID</label>
              <input type="number" class="form-control" id="order_id" name="order_id" placeholder="Enter Order ID" required>
              <div class="form-text">Enter an existing Order ID to generate its invoice.</div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Create Invoice from Order</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
