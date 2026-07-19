<?php
/**
 * Purchase Entry (purchase2) — Enterprise ERP UI (visual layer only)
 * Preserves: #purchaseForm, field names/IDs, #product-rows, payment IDs,
 * quickAdd modal, and all existing JS functions (loadProducts, submitForm, etc.).
 */
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$supplierId = $formData['supplier_id'] ?? '';
if ($supplierId === '' && isset($_GET['prefill_supplier_id'])) {
  $supplierId = (string)(int)$_GET['prefill_supplier_id'];
}
$prefillProductId = isset($_GET['prefill_product_id']) ? (int)$_GET['prefill_product_id'] : 0;
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
try { $submitToken = bin2hex(random_bytes(16)); } catch (Exception $e) { $submitToken = bin2hex(openssl_random_pseudo_bytes(16)); }
$_SESSION['purchase_submit_token'] = $submitToken;

$suppliers = $suppliers ?? [];
$currencySym = defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'CHF';

$supplierJson = [];
foreach ($suppliers as $s) {
    $supplierJson[] = [
        'id' => (int)($s['id'] ?? 0),
        'name' => (string)($s['name'] ?? ''),
        'email' => (string)($s['email'] ?? ''),
        'phone' => (string)($s['phone'] ?? ''),
        'address' => (string)($s['address'] ?? ''),
    ];
}
$selectedSupplierName = '';
foreach ($suppliers as $s) {
    if ((string)($s['id'] ?? '') === (string)$supplierId) {
        $selectedSupplierName = (string)($s['name'] ?? '');
        break;
    }
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/purchase2.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid purchase2-page py-2 py-md-3" id="purchase2Page">
  <div class="p2-toast-host" id="p2ToastHost" aria-live="polite" aria-atomic="true"></div>

  <div class="p2-header">
    <div>
      <nav class="p2-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
        <span class="sep">›</span>
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index">Purchases</a>
        <span class="sep">›</span>
        <span aria-current="page">Purchase Entry</span>
      </nav>
      <h1 class="p2-title">Purchase Entry</h1>
      <p class="p2-subtitle"><?php echo htmlspecialchars($title ?? 'Purchase Invoice'); ?> — supplier, products, document, and payment.</p>
    </div>
    <div class="p2-actions">
      <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary p2-btn" title="Back">
        <i class="bi bi-arrow-left"></i><span>Back</span>
      </a>
      <button type="button" class="btn btn-outline-secondary p2-btn" id="p2SaveDraftBtn" title="Save Draft (UI)">
        <i class="bi bi-file-earmark"></i><span class="d-none d-lg-inline">Save Draft</span>
      </button>
      <button type="button" class="btn btn-outline-secondary p2-btn" data-bs-toggle="modal" data-bs-target="#p2PreviewModal" title="Preview">
        <i class="bi bi-eye"></i><span class="d-none d-lg-inline">Preview</span>
      </button>
      <button type="button" class="btn btn-outline-secondary p2-btn" onclick="window.print()" title="Print Preview">
        <i class="bi bi-printer"></i><span class="d-none d-xl-inline">Print Preview</span>
      </button>
      <a href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase2" class="btn btn-outline-primary p2-btn" title="Save and New">
        <i class="bi bi-plus-lg"></i><span class="d-none d-lg-inline">Save &amp; New</span>
      </a>
      <button type="submit" form="purchaseForm" class="btn p2-btn p2-btn-primary" id="p2CompleteTop">
        <i class="bi bi-check2-circle"></i><span>Complete Purchase</span>
      </button>
    </div>
  </div>

  <?php flash('error'); ?>
  <?php flash('success'); ?>

  <div class="row g-3 mb-3">
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s1">
        <div class="icon"><i class="bi bi-building"></i></div>
        <div>
          <div class="label">Supplier</div>
          <div class="value" id="p2KpiSupplier"><?php echo $selectedSupplierName !== '' ? htmlspecialchars($selectedSupplierName) : '—'; ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s2">
        <div class="icon"><i class="bi bi-box-seam"></i></div>
        <div>
          <div class="label">Products</div>
          <div class="value" id="p2KpiProducts">0</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s3">
        <div class="icon"><i class="bi bi-stack"></i></div>
        <div>
          <div class="label">Quantity</div>
          <div class="value" id="p2KpiQty">0</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s4">
        <div class="icon"><i class="bi bi-currency-exchange"></i></div>
        <div>
          <div class="label">Purchase Value</div>
          <div class="value" id="p2KpiValue"><?php echo htmlspecialchars($currencySym); ?>0.00</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s5">
        <div class="icon"><i class="bi bi-truck"></i></div>
        <div>
          <div class="label">Expected Delivery</div>
          <div class="value" id="p2KpiDelivery"><?php echo date('d M Y', strtotime('+3 days')); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s6">
        <div class="icon"><i class="bi bi-credit-card"></i></div>
        <div>
          <div class="label">Payment Status</div>
          <div class="value" id="p2KpiPayStatus">Unpaid</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s7">
        <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
          <div class="label">Outstanding</div>
          <div class="value" id="p2KpiDue"><?php echo htmlspecialchars($currencySym); ?>0.00</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p2-stat s8">
        <div class="icon"><i class="bi bi-receipt"></i></div>
        <div>
          <div class="label">Purchase Number</div>
          <div class="value" id="p2KpiPo">PO-<?php echo date('Ymd'); ?>-NEW</div>
        </div>
      </div>
    </div>
  </div>

  <form id="purchaseForm" enctype="multipart/form-data" method="POST" action="<?php echo BASE_URL; ?>?controller=purchase&action=store" onsubmit="submitForm(event)">
    <input type="hidden" name="submit_token" value="<?php echo htmlspecialchars($submitToken); ?>">
    <?php if (!empty($prefillProductId)): ?>
      <input type="hidden" name="prefill_product_id" id="prefill_product_id" value="<?php echo (int)$prefillProductId; ?>">
      <input type="hidden" name="is_return" id="is_return" value="1">
    <?php endif; ?>

    <div class="row g-3">
      <div class="col-12 col-xl-9">

        <section class="p2-card">
          <div class="p2-card-header">
            <h2><i class="bi bi-receipt text-primary"></i> Purchase Details</h2>
            <span class="p2-badge p2-badge-soft">Required</span>
          </div>
          <div class="p2-card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="supplier_id">Supplier <span class="text-danger">*</span> <i class="fas fa-info-circle" title="Select supplier for this purchase"></i></label>
                <select class="form-select" id="supplier_id" name="supplier_id" required aria-label="Supplier">
                  <option value="">Please Select</option>
                  <?php foreach ($suppliers as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo ($supplierId == $s['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="reference_no">Reference No <i class="fas fa-info-circle" title="Auto generated"></i></label>
                <input type="text" class="form-control" id="reference_no" value="" placeholder="Auto" disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="pending">Please Select</option>
                  <option value="received">Received</option>
                  <option value="pending">Pending</option>
                  <option value="ordered">Ordered</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="business_location">Business Location <i class="fas fa-info-circle" title="Optional"></i></label>
                <input type="text" class="form-control" id="business_location" name="business_location" placeholder="Location (optional)">
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="update_stock" name="update_stock" checked>
                  <label class="form-check-label" for="update_stock">Update stock?</label>
                </div>
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label" for="p2ExpectedDate">Expected Delivery</label>
                <input type="date" class="form-control" id="p2ExpectedDate" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                <div class="form-text"><span class="p2-ui-only">UI only</span></div>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Warehouse">Warehouse</label>
                <select class="form-select" id="p2Warehouse">
                  <option value="main" selected>Main Warehouse</option>
                  <option value="secondary">Secondary</option>
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Branch">Branch</label>
                <input type="text" class="form-control" id="p2Branch" placeholder="Head Office">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2PayStatus">Payment Status</label>
                <select class="form-select" id="p2PayStatus">
                  <option value="unpaid">Unpaid</option>
                  <option value="partial">Partial</option>
                  <option value="paid">Paid</option>
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Currency">Currency</label>
                <input type="text" class="form-control" id="p2Currency" value="<?php echo htmlspecialchars($currencySym); ?>" readonly>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Fx">Exchange Rate</label>
                <input type="number" class="form-control" id="p2Fx" value="1" step="0.0001" min="0">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Priority">Priority</label>
                <select class="form-select" id="p2Priority">
                  <option value="normal">Normal</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p2Template">Template</label>
                <select class="form-select" id="p2Template">
                  <option value="">None</option>
                  <option value="standard">Standard PO</option>
                  <option value="recurring">Recurring</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label" for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Purchase description..."></textarea>
              </div>
            </div>
          </div>
        </section>

        <section class="p2-card">
          <div class="p2-card-header">
            <h2><i class="bi bi-paperclip text-primary"></i> Purchase Document</h2>
            <span class="p2-badge p2-badge-soft">Max 5MB</span>
          </div>
          <div class="p2-card-body">
            <div class="p2-attach-zone mb-3" aria-hidden="true">
              <i class="bi bi-cloud-arrow-up"></i>
              Supplier invoice · Quotation · PO · Delivery note · Warranty
            </div>
            <input type="file" class="form-control" name="document" id="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" aria-label="Purchase document">
            <small class="text-muted d-block mt-2">Max file size: 5MB</small>
          </div>
        </section>

        <section class="p2-card">
          <div class="p2-card-header">
            <h2><i class="bi bi-grid-3x3-gap text-primary"></i> Product Entry</h2>
            <span class="p2-badge p2-badge-ok">Live search · Barcode</span>
          </div>
          <div class="p2-card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
              <button type="button" class="btn btn-outline-primary btn-sm p2-btn"><i class="fas fa-file-import me-1"></i> Import Products</button>
              <div class="p2-search-wrap flex-grow-1" style="max-width:520px; position:relative">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                  <input id="product-search" type="text" class="form-control" placeholder="Enter Product name / SKU / Scan bar code" aria-label="Product search" autocomplete="off">
                </div>
                <div id="product-suggestions" class="list-group" style="display:none; max-height: 240px; overflow:auto; width: 100%"></div>
              </div>
              <a href="#" id="btn-quick-add" class="ms-auto small text-primary"><i class="fas fa-plus me-1"></i>Add new product</a>
            </div>

            <div class="table-responsive p2-table-wrap">
              <table class="table table-bordered align-middle purchase-items-table responsive-table">
                <thead class="table-success">
                  <tr>
                    <th style="width: 3%">#</th>
                    <th style="width: 18%">Product Name</th>
                    <th class="price-col text-end" style="width: 6%">Buying</th>
                    <th class="price-col text-end" style="width: 6%">Incl. Tax</th>
                    <th class="price-col text-end" style="width: 6%">Sales</th>
                    <th class="price-col text-end" style="width: 6%">Wholesale</th>
                    <th class="qty-col" style="width: 7%">Purchase Qty</th>
                    <th style="width: 10%">Unit Cost</th>
                    <th style="width: 7%">Discount %</th>
                    <th style="width: 7%">Cost (After Disc)</th>
                    <th style="width: 7%">Line Total</th>
                    <th style="width: 8%">Profit Margin %</th>
                    <th style="width: 10%">Unit Selling (Inc. tax)</th>
                    <th style="width: 3%"><i class="fas fa-trash"></i></th>
                  </tr>
                </thead>
                <tbody id="product-rows">
                  <tr><td colspan="14" class="text-center text-muted py-4">Select a supplier to load products.</td></tr>
                </tbody>
              </table>
            </div>

            <div class="p2-totals mt-3">
              <div><strong>Total Items:</strong> <span id="total-items">0.00</span></div>
              <div><strong>Net Total Amount:</strong> <span id="net-total"><?php echo CURRENCY_SYMBOL; ?>0.00</span></div>
            </div>
          </div>
        </section>

        <section class="p2-card">
          <div class="p2-card-header">
            <h2><i class="bi bi-wallet2 text-primary"></i> Add Payment</h2>
            <span class="p2-badge p2-badge-soft">Settlement</span>
          </div>
          <div class="p2-card-body">
            <div class="row g-2 mb-3 p2-pay-methods" aria-label="Payment method shortcuts">
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="cash">Cash</button></div>
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="card">Card</button></div>
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="bank">Bank</button></div>
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="cheque">Cheque</button></div>
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="credit">Credit</button></div>
              <div class="col-4 col-md-2"><button type="button" class="btn btn-outline-secondary w-100" data-p2-pay="online">Online</button></div>
            </div>

            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">Advance Balance:</label>
                <div class="form-control-plaintext">0</div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="pay-amount">Amount*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                  <input id="pay-amount" type="number" step="0.01" min="0" class="form-control" name="payment[amount]" value="0.00">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="paid-on">Paid on*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="far fa-calendar"></i></span>
                  <input id="paid-on" type="text" class="form-control bg-light" name="payment[paid_on]" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="p2TxnNo">Transaction No.</label>
                <input type="text" class="form-control" id="p2TxnNo" placeholder="Optional">
                <div class="form-text"><span class="p2-ui-only">UI only</span></div>
              </div>
            </div>

            <div class="row g-3 mt-2">
              <div class="col-md-3">
                <label class="form-label" for="payment-method">Payment Method*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                  <select id="payment-method" class="form-select" name="payment[method]">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                  </select>
                </div>
              </div>
              <div class="col-md-9">
                <label class="form-label" for="payment_note">Payment note</label>
                <textarea class="form-control" id="payment_note" name="payment[note]" rows="3"></textarea>
              </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="text-muted small">Balance updates live with line totals.</div>
              <div class="text-end">Payment due: <strong id="payment-due">0.00</strong></div>
            </div>

            <div class="text-center mt-3 d-none d-md-block">
              <button id="saveBtn" type="submit" class="btn p2-btn p2-btn-primary px-5">Save</button>
            </div>
          </div>
        </section>

        <section class="p2-card">
          <div class="p2-card-header">
            <h2><i class="bi bi-bar-chart-line text-primary"></i> Purchase Analytics</h2>
            <span class="p2-badge p2-badge-soft">Chart.js · illustrative</span>
          </div>
          <div class="p2-card-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <h3 class="h6 text-muted mb-2">Purchase Trend</h3>
                <div class="p2-chart-box"><canvas id="p2TrendChart" aria-label="Purchase trend"></canvas></div>
              </div>
              <div class="col-12 col-md-6">
                <h3 class="h6 text-muted mb-2">Cost Analysis</h3>
                <div class="p2-chart-box"><canvas id="p2CostChart" aria-label="Cost analysis"></canvas></div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <div class="col-12 col-xl-3">
        <div class="accordion d-xl-none mb-3" id="p2SideAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#p2AccSummary">Purchase Summary</button>
            </h2>
            <div id="p2AccSummary" class="accordion-collapse collapse show" data-bs-parent="#p2SideAccordion">
              <div class="accordion-body" id="p2SummaryMobile"></div>
            </div>
          </div>
        </div>

        <aside class="d-none d-xl-block" aria-label="Purchase sidebar">
          <section class="p2-card">
            <div class="p2-card-header"><h3><i class="bi bi-calculator text-primary"></i> Purchase Summary</h3></div>
            <div class="p2-card-body" id="p2SummaryPanel">
              <div class="p2-summary-row"><span class="muted">Items</span><span id="p2SumItems">0</span></div>
              <div class="p2-summary-row"><span class="muted">Quantity</span><span id="p2SumQty">0.00</span></div>
              <div class="p2-summary-row"><span class="muted">Subtotal</span><span id="p2SumSub"><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span class="muted">Discount</span><span><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span class="muted">Tax</span><span><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span class="muted">Shipping</span><span><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span>Grand Total</span><span id="p2SumGrand"><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span class="muted">Paid</span><span id="p2SumPaid"><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
              <div class="p2-summary-row"><span>Balance Due</span><span id="p2SumDue"><?php echo htmlspecialchars($currencySym); ?>0.00</span></div>
            </div>
          </section>

          <section class="p2-card">
            <div class="p2-card-header"><h3><i class="bi bi-building text-primary"></i> Supplier</h3></div>
            <div class="p2-card-body">
              <div class="d-flex gap-2 align-items-start">
                <div class="p2-supplier-avatar" id="p2SupAvatar" aria-hidden="true">S</div>
                <div class="flex-grow-1">
                  <strong id="p2SupName"><?php echo $selectedSupplierName !== '' ? htmlspecialchars($selectedSupplierName) : 'Not selected'; ?></strong>
                  <div class="p2-meta-grid mt-2">
                    <div><div class="k">Code</div><div class="v" id="p2SupCode">—</div></div>
                    <div><div class="k">Rating</div><div class="v">★★★★☆</div></div>
                    <div><div class="k">Phone</div><div class="v" id="p2SupPhone">—</div></div>
                    <div><div class="k">Email</div><div class="v" id="p2SupEmail">—</div></div>
                  </div>
                  <div class="small text-muted mt-2" id="p2SupAddress">—</div>
                  <div class="d-flex flex-wrap gap-1 mt-2">
                    <a class="btn btn-sm btn-outline-secondary" id="p2SupCall" href="#" title="Call"><i class="bi bi-telephone"></i></a>
                    <a class="btn btn-sm btn-outline-secondary" id="p2SupMail" href="#" title="Email"><i class="bi bi-envelope"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="p2-card">
            <div class="p2-card-header"><h3><i class="bi bi-warehouse text-primary"></i> Warehouse</h3></div>
            <div class="p2-card-body">
              <div class="p2-summary-row"><span class="muted">Location</span><span>Main</span></div>
              <div class="p2-summary-row"><span class="muted">Update Stock</span><span id="p2WhStock">Yes</span></div>
            </div>
          </section>

          <section class="p2-card">
            <div class="p2-card-header"><h3><i class="bi bi-sticky text-primary"></i> Quick Notes</h3></div>
            <div class="p2-card-body">
              <textarea class="form-control" id="p2QuickNotes" rows="3" placeholder="Internal notes (UI only)"></textarea>
            </div>
          </section>
        </aside>
      </div>
    </div>
  </form>

  <div class="p2-actionbar" role="region" aria-label="Purchase actions">
    <div class="p2-actionbar-inner">
      <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary p2-btn">Cancel</a>
        <button type="reset" form="purchaseForm" class="btn btn-outline-secondary p2-btn">Reset</button>
        <button type="button" class="btn btn-outline-secondary p2-btn" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-outline-secondary p2-btn" data-bs-toggle="modal" data-bs-target="#p2PreviewModal">Preview</button>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-secondary p2-btn" id="p2DraftBar">Save Draft</button>
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase2" class="btn btn-outline-primary p2-btn">Save &amp; New</a>
        <button type="submit" form="purchaseForm" class="btn p2-btn p2-btn-primary" id="p2SaveBar">
          <i class="bi bi-check2-circle"></i> Save Purchase
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="p2PreviewModal" tabindex="-1" aria-labelledby="p2PreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius:20px">
      <div class="modal-header">
        <h5 class="modal-title" id="p2PreviewLabel">Purchase Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-1"><strong>Supplier:</strong> <span id="p2PrevSupplier">—</span></p>
        <p class="mb-1"><strong>Date:</strong> <span id="p2PrevDate"><?php echo date('Y-m-d'); ?></span></p>
        <p class="mb-1"><strong>Items:</strong> <span id="p2PrevItems">0</span></p>
        <p class="mb-0"><strong>Net Total:</strong> <span id="p2PrevTotal"><?php echo htmlspecialchars($currencySym); ?>0.00</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="purchaseForm" class="btn btn-primary">Confirm &amp; Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Quick Add Product Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:20px">
      <div class="modal-header">
        <h5 class="modal-title">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info small">Product will be linked to the selected supplier.</div>
        <form id="quickAddForm">
          <div class="mb-3">
            <label class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Buying Price <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" class="form-control" name="price" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Initial Stock</label>
              <input type="number" step="0.01" min="0" class="form-control" name="stock_quantity" value="0">
            </div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-md-6">
              <label class="form-label">SKU</label>
              <input type="text" class="form-control" name="sku" placeholder="Auto if empty">
            </div>
            <div class="col-md-6">
              <label class="form-label">Sale Price (Inc. Tax)</label>
              <input type="number" step="0.01" min="0" class="form-control" name="sale_price">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="quickAddSubmit" class="btn btn-primary">Save Product</button>
      </div>
    </div>
  </div>
</div>

<script>
window.P2_SUPPLIERS = <?php echo json_encode($supplierJson, JSON_UNESCAPED_UNICODE); ?>;
</script>
<script>
const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';
const BASE_URL = '<?php echo BASE_URL; ?>';
const PREFILL_PRODUCT_ID = <?php echo (int)($prefillProductId ?: 0); ?>;
const PREFILL_QTY = <?php echo isset($_GET['prefill_qty']) ? (int)$_GET['prefill_qty'] : 0; ?>;

function currency(num) { return CURRENCY_SYMBOL + (parseFloat(num||0).toFixed(2)); }

// Cache of products for the selected supplier; used by search to add rows
let productsCache = [];

async function loadProducts(supplierId) {
  const tbody = document.getElementById('product-rows');
  productsCache = [];
  if (!supplierId) {
    tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted py-4">Select a supplier to load products.</td></tr>';
    updateTotals();
    return;
  }
  tbody.innerHTML = '<tr><td colspan="14" class="text-center py-4"><div class="spinner-border text-primary"></div><span class="ms-2">Loading products for search...</span></td></tr>';
  try {
    const resp = await fetch(`${BASE_URL}?controller=purchase&action=getProductsBySupplier&supplier_id=${supplierId}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    const data = await resp.json();
    if (!data.success || !Array.isArray(data.products) || data.products.length === 0) {
      tbody.innerHTML = `<tr><td colspan=14 class="text-center text-muted py-4">${data.message || 'No products found for this supplier.'}</td></tr>`;
      updateTotals();
      return;
    }
    productsCache = data.products;
    // Do not auto-add rows. Prompt user to search and add products.
    tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted py-4">Type in search box to add products.</td></tr>';
    updateTotals();
  } catch (e) {
    tbody.innerHTML = '<tr><td colspan="14" class="text-center text-danger py-4">Error loading products</td></tr>';
  }
}

function addProductRow(p) {
  if (!p) return;
  const tbody = document.getElementById('product-rows');
  // If row already exists, just reveal and focus qty
  const existing = tbody.querySelector(`tr[data-product-id="${p.id}"]`);
  if (existing) {
    existing.style.display = '';
    const qtyInput = existing.querySelector('.qty-input');
    if (qtyInput) { qtyInput.focus(); qtyInput.select(); }
    renumberRows();
    updateTotals();
    return;
  }
  const unit = parseFloat(p.price || 0); // Buying Price
  const unitStr = unit.toFixed(2);
  const sale = parseFloat(p.sale_price || 0); // Including Tax Price
  const price2 = parseFloat(p.price2 || 0);   // Sales Price
  const price3 = parseFloat(p.price3 || 0);   // Wholesale Price (SP)
  const sellInit = (sale > 0 ? sale : (price2 > 0 ? price2 : (price3 > 0 ? price3 : unit)));
  const sellInitStr = parseFloat(sellInit || 0).toFixed(2);
  const discount = 0;
  const beforeTax = unit;
  const lineTotal = beforeTax * 1;
  const baseStock = (p.stock_quantity !== undefined && p.stock_quantity !== null) ? parseFloat(p.stock_quantity) : 0;
  const projected = baseStock + 1;
  const tr = document.createElement('tr');
  tr.setAttribute('data-product-id', p.id);
  tr.setAttribute('data-base-stock', isFinite(baseStock)?baseStock:0);
  tr.innerHTML = `
    <td class="row-index" data-label="#" data-col="index"></td>
    <td data-label="Product Name" data-col="name">
      <strong>${p.name || 'Unnamed'}</strong>
      <div class="small text-muted">${p.code ? ('SKU: ' + p.code) : ''}</div>
      <div class="small">
        Stock:
        <span class="badge bg-info text-dark base-stock">${(p.stock_quantity !== undefined && p.stock_quantity !== null) ? p.stock_quantity : '-'}</span>
        +
        <span class="badge bg-warning text-dark qty-stock">1</span>
        =
        <span class="badge bg-secondary projected-stock">${isFinite(projected)?projected.toFixed(2):'-'}</span>
      </div>
      <input type="hidden" name="items[${p.id}][product_id]" value="${p.id}">
      <input type="hidden" name="items[${p.id}][base_stock]" value="${isFinite(baseStock)?baseStock:0}">
    </td>
    <td class="price-col text-end text-nowrap" data-label="Buying" data-col="buying">${currency(unit)}</td>
    <td class="price-col text-end text-nowrap" data-label="Incl. Tax" data-col="incl_tax">${sale > 0 ? currency(sale) : '-'}</td>
    <td class="price-col text-end text-nowrap" data-label="Sales" data-col="sales">${price2 > 0 ? currency(price2) : '-'}</td>
    <td class="price-col text-end text-nowrap" data-label="Wholesale" data-col="wholesale">${price3 > 0 ? currency(price3) : '-'}</td>
    <td class="qty-col" data-label="Purchase Qty" data-col="qty">
      <input type="number" min="1" class="form-control form-control-sm qty-input" name="items[${p.id}][quantity]" value="1" required>
    </td>
    <td data-label="Unit Cost" data-col="unit_cost">
      <div class="input-group input-group-sm">
        <span class="input-group-text">${CURRENCY_SYMBOL}</span>
        <input type="number" step="0.01" min="0" class="form-control price-input" name="items[${p.id}][unit_price]" value="${unitStr}" required>
      </div>
    </td>
    <td data-label="Discount %" data-col="discount">
      <div class="input-group input-group-sm">
        <input type="number" step="0.01" min="0" class="form-control discount-input" name="items[${p.id}][discount_percent]" value="${discount}">
        <span class="input-group-text">%</span>
      </div>
    </td>
    <td class="before-tax" data-label="Cost (After Disc)" data-col="cost_after_disc">${currency(beforeTax)}</td>
    <td class="row-total" data-label="Line Total" data-col="line_total">${currency(lineTotal)}</td>
    <td class="margin-display" data-label="Profit Margin %" data-col="profit_margin">0.00%</td>
    <td data-label="Unit Selling (Inc. tax)" data-col="unit_selling">
      <div class="input-group input-group-sm">
        <span class="input-group-text">${CURRENCY_SYMBOL}</span>
        <input type="number" step="0.01" min="0" class="form-control sell-input" name="items[${p.id}][selling_price]" value="${sellInitStr}">
      </div>
    </td>
    <td class="text-center" data-label="Remove" data-col="actions">
      <button type="button" class="btn btn-link text-danger p-0 remove-row" title="Remove">
        <i class="fas fa-trash"></i>
      </button>
    </td>`;
  // If placeholder row present, remove it first
  if (tbody.children.length === 1 && !tbody.querySelector('tr[data-product-id]')) {
    tbody.innerHTML = '';
  }
  tbody.appendChild(tr);
  bindRowEvents();
  renumberRows();
  updateTotals();
  const qtyInput = tr.querySelector('.qty-input');
  if (qtyInput) { qtyInput.focus(); qtyInput.select(); }
}

function bindRowEvents() {
  document.querySelectorAll('#product-rows .price-input, #product-rows .qty-input, #product-rows .discount-input, #product-rows .sell-input').forEach(el => {
    el.addEventListener('input', updateTotals);
  });
  document.querySelectorAll('#product-rows .remove-row').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const tr = e.currentTarget.closest('tr');
      tr?.remove();
      renumberRows();
      updateTotals();
    });
  });
}

function updateTotals() {
  let grand = 0;
  let items = 0;
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    // Skip hidden rows (e.g., when a product filter is applied)
    if (tr.style.display === 'none') { return; }

    const unit = parseFloat(tr.querySelector('.price-input')?.value || 0);
    const qty = parseFloat(tr.querySelector('.qty-input')?.value || 0);
    const disc = parseFloat(tr.querySelector('.discount-input')?.value || 0);
    const unitAfterDiscount = unit - (unit * (disc/100));
    const beforeTaxCell = tr.querySelector('.before-tax');
    if (beforeTaxCell) beforeTaxCell.textContent = currency(unitAfterDiscount);
    const total = unitAfterDiscount * qty;
    grand += total;
    items += qty;
    const rowTotalCell = tr.querySelector('.row-total');
    if (rowTotalCell) rowTotalCell.textContent = currency(total);
    // Read selling price input and compute margin % automatically
    const sell = parseFloat(tr.querySelector('.sell-input')?.value || 0);
    const marginPct = unitAfterDiscount > 0 ? ((sell - unitAfterDiscount) / unitAfterDiscount) * 100 : 0;
    const marginCell = tr.querySelector('.margin-display');
    if (marginCell) marginCell.textContent = `${(isFinite(marginPct)?marginPct:0).toFixed(2)}%`;
    // Update projected stock display (current stock + qty)
    const baseStock = parseFloat(tr.getAttribute('data-base-stock') || '0');
    const projEl = tr.querySelector('.projected-stock');
    const qtyEl = tr.querySelector('.qty-stock');
    if (qtyEl) qtyEl.textContent = isFinite(qty) ? qty.toString() : '0';
    if (projEl) {
      const projected = (isFinite(baseStock) ? baseStock : 0) + (isFinite(qty) ? qty : 0);
      projEl.textContent = isFinite(projected) ? projected.toFixed(2) : '-';
    }
  });
  const totalItemsEl = document.getElementById('total-items');
  if (totalItemsEl) totalItemsEl.textContent = items.toFixed(2);
  const netEl = document.getElementById('net-total');
  if (netEl) netEl.textContent = currency(grand);
}

function renumberRows() {
  let i = 1;
  document.querySelectorAll('#product-rows tr[data-product-id] .row-index').forEach(cell => cell.textContent = i++);
}

// Initialize paid-on field with current datetime (readonly display)
(function initPaidOn(){
  const pad = (n) => (n<10? '0'+n : n);
  const d = new Date();
  const fmt = pad(d.getMonth()+1) + '/' + pad(d.getDate()) + '/' + d.getFullYear() + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
  const el = document.getElementById('paid-on');
  if (el) el.value = fmt;
})();

// Update Payment due whenever totals or amount change
function updatePaymentDue(){
  const netText = document.getElementById('net-total')?.textContent || '<?php echo CURRENCY_SYMBOL; ?>0.00';
  const net = parseFloat((netText.replace('<?php echo CURRENCY_SYMBOL; ?>','')||'0')) || 0;
  const amt = parseFloat(document.getElementById('pay-amount')?.value || 0);
  const due = Math.max(net - amt, 0);
  const dueEl = document.getElementById('payment-due');
  if (dueEl) dueEl.textContent = currency(due).replace('<?php echo CURRENCY_SYMBOL; ?>','');
}

// Hook into existing totals update
const _origUpdateTotals = updateTotals;
updateTotals = function(){
  _origUpdateTotals();
  updatePaymentDue();
}

document.getElementById('pay-amount')?.addEventListener('input', updatePaymentDue);

let __submitting = false;
async function submitForm(ev) {
  ev.preventDefault();
  if (__submitting) { return; }
  __submitting = true;
  const form = ev.target;
  const fd = new FormData(form);
  const saveBtn = document.getElementById('saveBtn');
  if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving...'; }
  try {
    // Basic client-side validation
    const supplier = fd.get('supplier_id');
    if (!supplier) {
      alert('Please select a supplier');
      return;
    }
    const anyItem = Array.from(fd.keys()).some(k => k.startsWith('items['));
    if (!anyItem) {
      alert('Please add at least one product item');
      return;
    }

    const res = await fetch(BASE_URL + '?controller=purchase&action=store', {
      method: 'POST',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    let out = null;
    const text = await res.text();
    try { out = JSON.parse(text); } catch (_) { out = null; }
    if (out && out.success) {
      alert('Purchase saved successfully');
      if (PREFILL_PRODUCT_ID > 0) {
        window.location.href = BASE_URL + `?controller=ListPurchaseController&highlight_product_id=${PREFILL_PRODUCT_ID}`;
      } else {
        window.location.href = BASE_URL + '?controller=purchase&action=index';
      }
    } else {
      // If server returned HTML (non-AJAX path), fallback to normal form submit
      if (!out) {
        form.submit();
        return;
      }
      alert((out && out.message) || 'Failed to save');
    }
  } catch (e) {
    alert('Error submitting form');
  } finally {
    // Re-enable only if we are not navigating away due to success
    __submitting = false;
    if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const supplierSel = document.getElementById('supplier_id');
  supplierSel.addEventListener('change', () => loadProducts(supplierSel.value));
  if (supplierSel.value) loadProducts(supplierSel.value);
  // Generate a display-only reference number to match UI; actual will be generated server-side
  const ref = document.getElementById('reference_no');
  if (ref) {
    const rand = Math.random().toString(36).substring(2,6).toUpperCase();
    const d = new Date();
    const ymd = d.getFullYear().toString() + String(d.getMonth()+1).padStart(2,'0') + String(d.getDate()).padStart(2,'0');
    ref.value = `PO-${ymd}-${rand}`;
  }
  // Search wiring (search within productsCache)
  const search = document.getElementById('product-search');
  const suggBox = document.getElementById('product-suggestions');
  search?.addEventListener('input', (e) => {
    const q = (e.target.value || '').trim().toLowerCase();
    if (!q){
      // Show first 20 products for current supplier when query is empty
      const initial = (productsCache || []).slice(0, 20);
      showSuggestions(initial);
      return;
    }
    const matches = (productsCache || []).filter(p =>
      ((p.name || '').toLowerCase().includes(q)) ||
      ((p.code || '').toLowerCase().includes(q)) ||
      ((p.sku || '').toLowerCase().includes(q))
    ).slice(0, 20);
    showSuggestions(matches);
  });
  // Show suggestions on focus even before typing
  search?.addEventListener('focus', () => {
    const list = (productsCache || []).slice(0, 20);
    if (list.length) showSuggestions(list);
  });
  // Quick add on Enter: add best match or first item
  search?.addEventListener('keydown', (ev) => {
    if (ev.key === 'Enter'){
      ev.preventDefault();
      const q = (search.value || '').trim().toLowerCase();
      let list = (productsCache || []);
      if (q){
        list = list.filter(p => ((p.name||'').toLowerCase().includes(q)) || ((p.code||'').toLowerCase().includes(q)) || ((p.sku||'').toLowerCase().includes(q)));
      }
      const pick = list[0];
      if (pick){ addProductRow(pick); }
      if (suggBox){ suggBox.style.display = 'none'; }
    }
  });
  // Hide suggestions on outside click
  document.addEventListener('click', (ev) => {
    if (!suggBox) return;
    const target = ev.target;
    if (target.id === 'product-search' || suggBox.contains(target)) return;
    suggBox.style.display = 'none';
  });
});

// In-memory index of products for quick search
let productsIndex = [];

function indexProducts(){
  productsIndex = [];
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    const id = tr.getAttribute('data-product-id');
    const name = tr.querySelector('strong')?.textContent.trim() || '';
    const skuText = tr.querySelector('.small.text-muted')?.textContent || '';
    const sku = skuText.replace('SKU:', '').trim();
    productsIndex.push({ id, name, sku });
  });
}

function showSuggestions(matches){
  const box = document.getElementById('product-suggestions');
  if (!box) return;
  if (!matches.length){ box.style.display = 'none'; box.innerHTML=''; return; }
  box.innerHTML = matches.map(m =>
    `<a href="#" class="list-group-item list-group-item-action" data-id="${m.id}" data-name="${m.name}">${m.name}${(m.sku||m.code)?` <small class=\"text-muted\">(${m.sku||m.code})</small>`:''}</a>`
  ).join('');
  box.style.display = 'block';
  box.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      const id = a.getAttribute('data-id');
      const nm = a.getAttribute('data-name') || '';
      const prod = (productsCache || []).find(p => String(p.id) === String(id));
      if (prod) { addProductRow(prod); }
      const inp = document.getElementById('product-search');
      if (inp) inp.value = nm;
      box.style.display = 'none';
    });
  });
}

function clearTableFilter(){
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => tr.style.display = '');
  renumberRows();
  updateTotals();
}

function applyTableFilter(productId){
  let any = false;
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    if (tr.getAttribute('data-product-id') === String(productId)){
      tr.style.display = '';
      any = true;
    } else {
      tr.style.display = 'none';
    }
  });
  if (!any){
    // if not found (e.g., table not loaded yet), do nothing
    return;
  }
  renumberRows();
  updateTotals();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
  function p2Toast(msg, type) {
    type = type || 'info';
    var host = document.getElementById('p2ToastHost');
    if (!host) return;
    var el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : type) + ' border-0 show';
    el.setAttribute('role', 'alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    host.appendChild(el);
    setTimeout(function(){ el.remove(); }, 3200);
  }

  function syncSupplierUi() {
    var sel = document.getElementById('supplier_id');
    if (!sel) return;
    var id = sel.value;
    var opt = sel.options[sel.selectedIndex];
    var name = opt && opt.value ? opt.text : 'Not selected';
    var kpi = document.getElementById('p2KpiSupplier');
    if (kpi) kpi.textContent = opt && opt.value ? name : '—';
    var sn = document.getElementById('p2SupName');
    if (sn) sn.textContent = name;
    var avatar = document.getElementById('p2SupAvatar');
    if (avatar) avatar.textContent = (name && name !== 'Not selected' && name !== 'Please Select') ? name.charAt(0).toUpperCase() : 'S';
    var list = window.P2_SUPPLIERS || [];
    var found = list.find(function(s){ return String(s.id) === String(id); });
    var code = document.getElementById('p2SupCode');
    var phone = document.getElementById('p2SupPhone');
    var email = document.getElementById('p2SupEmail');
    var addr = document.getElementById('p2SupAddress');
    var call = document.getElementById('p2SupCall');
    var mail = document.getElementById('p2SupMail');
    if (found) {
      if (code) code.textContent = 'SUP-' + found.id;
      if (phone) phone.textContent = found.phone || '—';
      if (email) email.textContent = found.email || '—';
      if (addr) addr.textContent = found.address || '—';
      if (call) call.href = found.phone ? ('tel:' + found.phone) : '#';
      if (mail) mail.href = found.email ? ('mailto:' + found.email) : '#';
    } else {
      if (code) code.textContent = '—';
      if (phone) phone.textContent = '—';
      if (email) email.textContent = '—';
      if (addr) addr.textContent = '—';
    }
  }

  function syncKpisFromDom() {
    var rows = document.querySelectorAll('#product-rows tr[data-product-id]');
    var visible = 0;
    rows.forEach(function(tr){ if (tr.style.display !== 'none') visible++; });
    var qtyEl = document.getElementById('total-items');
    var netEl = document.getElementById('net-total');
    var dueEl = document.getElementById('payment-due');
    var payAmt = document.getElementById('pay-amount');
    var ref = document.getElementById('reference_no');
    if (document.getElementById('p2KpiProducts')) document.getElementById('p2KpiProducts').textContent = String(visible);
    if (document.getElementById('p2KpiQty') && qtyEl) document.getElementById('p2KpiQty').textContent = qtyEl.textContent;
    if (document.getElementById('p2KpiValue') && netEl) document.getElementById('p2KpiValue').textContent = netEl.textContent;
    if (document.getElementById('p2SumItems')) document.getElementById('p2SumItems').textContent = String(visible);
    if (document.getElementById('p2SumQty') && qtyEl) document.getElementById('p2SumQty').textContent = qtyEl.textContent;
    if (document.getElementById('p2SumSub') && netEl) document.getElementById('p2SumSub').textContent = netEl.textContent;
    if (document.getElementById('p2SumGrand') && netEl) document.getElementById('p2SumGrand').textContent = netEl.textContent;
    if (document.getElementById('p2SumPaid') && payAmt) {
      var sym = (typeof CURRENCY_SYMBOL !== 'undefined') ? CURRENCY_SYMBOL : '';
      document.getElementById('p2SumPaid').textContent = sym + (parseFloat(payAmt.value||0).toFixed(2));
    }
    if (document.getElementById('p2SumDue') && dueEl) {
      var sym2 = (typeof CURRENCY_SYMBOL !== 'undefined') ? CURRENCY_SYMBOL : '';
      document.getElementById('p2SumDue').textContent = sym2 + (dueEl.textContent || '0.00');
      if (document.getElementById('p2KpiDue')) document.getElementById('p2KpiDue').textContent = sym2 + (dueEl.textContent || '0.00');
    }
    if (document.getElementById('p2KpiPo') && ref && ref.value) document.getElementById('p2KpiPo').textContent = ref.value;
    var amt = parseFloat(payAmt && payAmt.value || 0);
    var due = parseFloat((dueEl && dueEl.textContent) || 0);
    var statusLabel = 'Unpaid';
    if (amt > 0 && due <= 0) statusLabel = 'Paid';
    else if (amt > 0) statusLabel = 'Partial';
    if (document.getElementById('p2KpiPayStatus')) document.getElementById('p2KpiPayStatus').textContent = statusLabel;
    var prevS = document.getElementById('p2PrevSupplier');
    var prevI = document.getElementById('p2PrevItems');
    var prevT = document.getElementById('p2PrevTotal');
    var prevD = document.getElementById('p2PrevDate');
    if (prevS) prevS.textContent = (document.getElementById('p2KpiSupplier') || {}).textContent || '—';
    if (prevI) prevI.textContent = String(visible);
    if (prevT && netEl) prevT.textContent = netEl.textContent;
    if (prevD) {
      var pd = document.getElementById('purchase_date');
      if (pd) prevD.textContent = pd.value;
    }
    var panel = document.getElementById('p2SummaryPanel');
    var mobile = document.getElementById('p2SummaryMobile');
    if (panel && mobile) mobile.innerHTML = panel.innerHTML;
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (typeof updateTotals === 'function') {
      var _ut = updateTotals;
      updateTotals = function(){ _ut(); syncKpisFromDom(); };
    }
    if (typeof updatePaymentDue === 'function') {
      var _upd = updatePaymentDue;
      updatePaymentDue = function(){ _upd(); syncKpisFromDom(); };
    }
    var supplierSel = document.getElementById('supplier_id');
    if (supplierSel) supplierSel.addEventListener('change', syncSupplierUi);
    syncSupplierUi();
    syncKpisFromDom();

    var draftTop = document.getElementById('p2SaveDraftBtn');
    if (draftTop) draftTop.addEventListener('click', function(){
      p2Toast('Draft saved locally (UI only). Use Save to post purchase.', 'warning');
    });
    var draftBar = document.getElementById('p2DraftBar');
    if (draftBar) draftBar.addEventListener('click', function(){
      p2Toast('Draft saved locally (UI only). Use Save to post purchase.', 'warning');
    });

    document.querySelectorAll('[data-p2-pay]').forEach(function(btn){
      btn.addEventListener('click', function(){
        var v = btn.getAttribute('data-p2-pay');
        var pm = document.getElementById('payment-method');
        if (!pm) return;
        var map = { cash:'cash', card:'card', bank:'bank', cheque:'cheque', credit:'cash', online:'bank' };
        if (map[v] && pm.querySelector('option[value="'+map[v]+'"]')) {
          pm.value = map[v];
          p2Toast('Payment method set to ' + pm.options[pm.selectedIndex].text, 'info');
        } else {
          p2Toast('Method "' + v + '" is UI-only; select from dropdown.', 'warning');
        }
      });
    });

    var exp = document.getElementById('p2ExpectedDate');
    if (exp) exp.addEventListener('change', function(){
      var kpi = document.getElementById('p2KpiDelivery');
      if (!kpi || !exp.value) return;
      var d = new Date(exp.value + 'T00:00:00');
      kpi.textContent = d.toLocaleDateString(undefined, { day:'2-digit', month:'short', year:'numeric' });
    });

    var stockCb = document.getElementById('update_stock');
    if (stockCb) stockCb.addEventListener('change', function(){
      var el = document.getElementById('p2WhStock');
      if (el) el.textContent = stockCb.checked ? 'Yes' : 'No';
    });

    var payAmount = document.getElementById('pay-amount');
    if (payAmount) payAmount.addEventListener('input', syncKpisFromDom);

    var qa = document.getElementById('btn-quick-add');
    if (qa && window.bootstrap) {
      qa.addEventListener('click', function(e){
        e.preventDefault();
        var modalEl = document.getElementById('quickAddModal');
        if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
      });
    }

    if (window.Chart) {
      var t = document.getElementById('p2TrendChart');
      if (t) new Chart(t, {
        type: 'line',
        data: { labels: ['Jan','Feb','Mar','Apr','May','Jun'], datasets: [{ label: 'Purchases', data: [12,18,9,22,15,20], borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,0.12)', fill:true, tension:0.35 }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } } }
      });
      var c = document.getElementById('p2CostChart');
      if (c) new Chart(c, {
        type: 'doughnut',
        data: { labels: ['Products','Tax','Shipping'], datasets: [{ data: [70,20,10], backgroundColor:['#2563eb','#059669','#d97706'] }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
      });
    }
  });
})();
</script>
