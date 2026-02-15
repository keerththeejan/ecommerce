<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4 px-2 px-sm-3 px-md-4">
  <div class="row mb-3 mb-md-4">
    <div class="col-12">
      <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
      </a>
    </div>
  </div>

  <div class="row g-3 g-md-4 justify-content-center">
    <div class="col-12 col-md-6 col-lg-5 col-xl-5">
      <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-success text-white py-3 px-3 px-md-4">
          <h5 class="card-title mb-0 font-weight-bold">Option 1: Manual Bill (From Products)</h5>
        </div>
        <div class="card-body d-flex flex-column justify-content-between py-4 px-3 px-md-4">
          <p class="text-muted mb-4 mb-md-3">Create a new bill by selecting products manually (fast via POS interface).</p>
          <a href="<?php echo BASE_URL; ?>?controller=pos&action=index" class="btn btn-success align-self-start">
            <i class="fas fa-cash-register mr-2"></i> Start Manual Billing (POS)
          </a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-5 col-xl-5">
      <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-primary text-white py-3 px-3 px-md-4">
          <h5 class="card-title mb-0 font-weight-bold">Option 2: Create From Order (Add Bill)</h5>
        </div>
        <div class="card-body py-4 px-3 px-md-4">
          <?php flash('invoice_success'); ?>
          <?php flash('invoice_error', '', 'alert alert-danger'); ?>

          <form method="post" action="<?php echo BASE_URL; ?>?controller=invoice&action=create">
            <div class="mb-3 mb-md-4 position-relative">
              <label for="order_id" class="form-label font-weight-bold">Order ID</label>
              <input type="text" 
                     class="form-control form-control-lg" 
                     id="order_id" 
                     name="order_id" 
                     placeholder="Search by Order ID or customer name..." 
                     required 
                     autocomplete="off"
                     data-search-url="<?php echo BASE_URL; ?>?controller=invoice&action=searchOrders">
              <div id="orderDropdown" class="list-group position-absolute w-100 shadow-sm" style="top: 100%; left: 0; z-index: 1050; max-height: 260px; overflow-y: auto; display: none;"></div>
              <div class="form-text mt-1">Type to search or select from recent orders.</div>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-file-invoice mr-2"></i> Create Invoice from Order
            </button>
          </form>

          <script>
          (function() {
            var input = document.getElementById('order_id');
            var dropdown = document.getElementById('orderDropdown');
            var searchUrl = input.getAttribute('data-search-url');
            var recentOrders = <?php echo json_encode(isset($recentOrders) ? $recentOrders : []); ?>;
            var debounceTimer;

            function showOrders(orders) {
              if (!orders || orders.length === 0) {
                dropdown.innerHTML = '<div class="list-group-item text-muted">No orders found.</div>';
              } else {
                dropdown.innerHTML = orders.map(function(o) {
                  var name = (o.first_name || '') + ' ' + (o.last_name || '');
                  var amt = o.total_amount ? parseFloat(o.total_amount).toFixed(2) : '0';
                  return '<a href="#" class="list-group-item list-group-item-action" data-id="' + o.id + '">' +
                    '<strong>#' + o.id + '</strong> - ' + name.trim() + ' (' + amt + ')</a>';
                }).join('');
              }
              dropdown.style.display = 'block';
            }

            function fetchOrders(q) {
              var url = searchUrl + (q ? '&q=' + encodeURIComponent(q) : '');
              fetch(url)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                  if (data.success && data.orders) showOrders(data.orders);
                })
                .catch(function() { showOrders([]); });
            }

            input.addEventListener('focus', function() {
              var val = input.value.trim();
              if (val) fetchOrders(val);
              else showOrders(recentOrders);
            });

            input.addEventListener('input', function() {
              clearTimeout(debounceTimer);
              var val = input.value.trim();
              debounceTimer = setTimeout(function() {
                if (val) fetchOrders(val);
                else showOrders(recentOrders);
              }, 250);
            });

            dropdown.addEventListener('click', function(e) {
              e.preventDefault();
              var item = e.target.closest('[data-id]');
              if (item) {
                input.value = item.getAttribute('data-id');
                dropdown.style.display = 'none';
              }
            });

            document.addEventListener('click', function(e) {
              if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
              }
            });
          })();
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
