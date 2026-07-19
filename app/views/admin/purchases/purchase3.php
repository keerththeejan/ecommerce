<?php
/**
 * Purchase Return (purchase3) — Enterprise ERP UI (visual layer only)
 * Preserves: GET filters, #purchase3ProductTable, #returnsTable, export button IDs,
 * POST return form fields (is_return, items[...], supplier_id, store action).
 */
$title = $title ?? 'Purchase Return';
$locations = $locations ?? [['id' => 'all', 'name' => 'All']];
$returns = $returns ?? [];
$selectedProduct = $selectedProduct ?? null;
$suppliers = $suppliers ?? [];
$original_purchase_id = $original_purchase_id ?? 0;

$start = $_GET['start_date'] ?? '';
$end   = $_GET['end_date'] ?? '';
$locationId = $_GET['location_id'] ?? 'all';
if ($start && $end) {
    $displayRange = date('m/d/Y', strtotime($start)) . ' - ' . date('m/d/Y', strtotime($end));
} else {
    $displayRange = '01/01/' . date('Y') . ' - 12/31/' . date('Y');
}

$hasProduct = !empty($selectedProduct['id']);
$pid = $hasProduct ? (int)$selectedProduct['id'] : 0;
$buyPrice = $hasProduct && isset($selectedProduct['price']) ? (float)$selectedProduct['price'] : 0;
$stockQty = $hasProduct && isset($selectedProduct['stock_quantity']) ? (float)$selectedProduct['stock_quantity'] : 0;
$lastPurchasedRaw = ($hasProduct && isset($selectedProduct['last_purchase_qty'])) ? (float)$selectedProduct['last_purchase_qty'] : null;
$returnQtyRaw = ($lastPurchasedRaw !== null && $lastPurchasedRaw > 0)
    ? $lastPurchasedRaw
    : ($stockQty > 0 ? $stockQty : 0);
$returnValue = $returnQtyRaw * $buyPrice;
$currencySym = defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'CHF';

$supplierNameDisplay = '';
if ($hasProduct) {
    if (!empty($selectedProduct['supplier'])) {
        $supplierNameDisplay = (string)$selectedProduct['supplier'];
    } elseif (!empty($selectedProduct['supplier_id']) && !empty($suppliers) && is_array($suppliers)) {
        foreach ($suppliers as $s) {
            $sid = isset($s['id']) ? (int)$s['id'] : (int)($s->id ?? 0);
            $sname = isset($s['name']) ? $s['name'] : ($s->name ?? '');
            if ($sid === (int)$selectedProduct['supplier_id']) {
                $supplierNameDisplay = $sname;
                break;
            }
        }
    }
}

$backHref = $hasProduct
    ? (BASE_URL . '?controller=ListPurchaseController&highlight_product_id=' . urlencode((string)$pid))
    : (BASE_URL . '?controller=ListPurchaseController');

$returnsCount = is_array($returns) ? count($returns) : 0;
$supplierInitial = $supplierNameDisplay !== '' ? strtoupper(substr($supplierNameDisplay, 0, 1)) : 'S';
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/purchase3.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid purchase3-page py-2 py-md-3" id="purchase3Page">
  <div class="p3-toast-host" id="p3ToastHost" aria-live="polite" aria-atomic="true"></div>

  <div class="p3-header">
    <div>
      <nav class="p3-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
        <span class="sep">›</span>
        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index">Procurement</a>
        <span class="sep">›</span>
        <span aria-current="page">Purchase Return</span>
      </nav>
      <h1 class="p3-title"><?php echo htmlspecialchars($title); ?></h1>
      <p class="p3-subtitle">Advanced procurement return entry — supplier, stock, and return settlement.</p>
    </div>
    <div class="p3-actions">
      <a href="<?php echo htmlspecialchars($backHref); ?>" class="btn btn-outline-secondary p3-btn" title="Back">
        <i class="bi bi-arrow-left"></i><span>Back</span>
      </a>
      <button type="button" class="btn btn-outline-secondary p3-btn" id="p3SaveDraftBtn" title="Save Draft (UI)">
        <i class="bi bi-file-earmark"></i><span class="d-none d-lg-inline">Save Draft</span>
      </button>
      <button type="button" class="btn btn-outline-secondary p3-btn" data-bs-toggle="modal" data-bs-target="#p3PreviewModal" title="Preview">
        <i class="bi bi-eye"></i><span class="d-none d-lg-inline">Preview</span>
      </button>
      <button type="button" class="btn btn-outline-secondary p3-btn" onclick="window.print()" title="Print">
        <i class="bi bi-printer"></i><span class="d-none d-xl-inline">Print</span>
      </button>
      <a href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase3<?php echo $hasProduct ? '&product_id=' . urlencode((string)$pid) : ''; ?>" class="btn btn-outline-primary p3-btn" title="Save and New">
        <i class="bi bi-plus-lg"></i><span class="d-none d-lg-inline">Save &amp; New</span>
      </a>
      <?php if ($hasProduct): ?>
        <button type="submit" form="p3ReturnForm" class="btn p3-btn p3-btn-primary" id="p3CompleteTop" <?php echo ($returnQtyRaw <= 0 ? 'disabled' : ''); ?>>
          <i class="bi bi-check2-circle"></i><span>Complete Return</span>
        </button>
      <?php else: ?>
        <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn p3-btn p3-btn-primary">
          <i class="bi bi-box-seam"></i><span>Select Product</span>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s1">
        <div class="icon"><i class="bi bi-building"></i></div>
        <div>
          <div class="label">Selected Supplier</div>
          <div class="value"><?php echo $supplierNameDisplay !== '' ? htmlspecialchars($supplierNameDisplay) : '—'; ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s2">
        <div class="icon"><i class="bi bi-box-seam"></i></div>
        <div>
          <div class="label">Products</div>
          <div class="value" data-counter="<?php echo $hasProduct ? 1 : 0; ?>">0</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s3">
        <div class="icon"><i class="bi bi-currency-exchange"></i></div>
        <div>
          <div class="label">Grand Total</div>
          <div class="value"><?php echo htmlspecialchars($currencySym); ?> <span data-counter="<?php echo (int)round($returnValue); ?>">0</span></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s4">
        <div class="icon"><i class="bi bi-stack"></i></div>
        <div>
          <div class="label">Total Quantity</div>
          <div class="value" data-counter="<?php echo (int)round($returnQtyRaw); ?>">0</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s5">
        <div class="icon"><i class="bi bi-truck"></i></div>
        <div>
          <div class="label">Expected Delivery</div>
          <div class="value"><?php echo date('d M Y'); ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s6">
        <div class="icon"><i class="bi bi-credit-card"></i></div>
        <div>
          <div class="label">Payment Status</div>
          <div class="value"><?php echo $hasProduct ? 'Credit Note' : '—'; ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s7">
        <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
          <div class="label">Outstanding</div>
          <div class="value"><?php echo htmlspecialchars($currencySym); ?> <span data-counter="<?php echo (int)round($returnValue); ?>">0</span></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="p3-stat s8">
        <div class="icon"><i class="bi bi-receipt"></i></div>
        <div>
          <div class="label">Return Number</div>
          <div class="value">PR-<?php echo date('Ymd'); ?><?php echo $hasProduct ? '-' . $pid : '-NEW'; ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-xl-9">

      <section class="p3-card">
        <div class="p3-card-header">
          <h2><i class="bi bi-funnel text-primary"></i> Search &amp; Filters</h2>
          <span class="p3-badge p3-badge-soft">Location · Date</span>
        </div>
        <div class="p3-card-body">
          <form class="row g-3" method="get" action="<?php echo BASE_URL; ?>" id="p3FilterForm">
            <input type="hidden" name="controller" value="purchase">
            <input type="hidden" name="action" value="purchase3">
            <?php if ($hasProduct): ?>
              <input type="hidden" name="product_id" value="<?php echo (int)$pid; ?>">
            <?php endif; ?>
            <div class="col-12 col-md-4">
              <label class="form-label" for="p3Location">Business Location</label>
              <select class="form-select" name="location_id" id="p3Location" aria-label="Business Location">
                <?php foreach ($locations as $loc): ?>
                  <option value="<?php echo htmlspecialchars($loc['id']); ?>"<?php echo ((string)$locationId === (string)$loc['id']) ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($loc['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12 col-md-4">
              <label class="form-label" for="p3DateRange">Date Range</label>
              <input type="text" class="form-control bg-light" id="p3DateRange" value="<?php echo htmlspecialchars($displayRange); ?>" readonly aria-label="Date Range">
              <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start); ?>">
              <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end); ?>">
            </div>
            <div class="col-12 col-md-4 d-flex align-items-end justify-content-md-end gap-2 flex-wrap">
              <button type="submit" class="btn btn-primary p3-btn"><i class="bi bi-search"></i> Apply</button>
              <a href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase3<?php echo $hasProduct ? '&product_id=' . urlencode((string)$pid) : ''; ?>" class="btn btn-outline-secondary p3-btn">Reset</a>
              <button type="button" class="btn btn-outline-secondary p3-btn" data-bs-toggle="offcanvas" data-bs-target="#p3AdvFilter" aria-controls="p3AdvFilter">
                <i class="bi bi-sliders"></i><span class="d-none d-sm-inline"> Advanced</span>
              </button>
            </div>
          </form>
        </div>
      </section>

      <?php if ($hasProduct): ?>

        <div class="p3-card">
          <div class="p3-card-body">
            <div class="p3-product-hero">
              <?php if (!empty($selectedProduct['image'])): ?>
                <img src="<?php echo htmlspecialchars($selectedProduct['image']); ?>" alt="<?php echo htmlspecialchars($selectedProduct['name'] ?? 'Product'); ?>" class="purchase3-product-img" />
              <?php else: ?>
                <div class="p3-supplier-avatar" aria-hidden="true"><i class="bi bi-box"></i></div>
              <?php endif; ?>
              <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                  <strong class="fs-5"><?php echo htmlspecialchars($selectedProduct['name'] ?? ('#' . $pid)); ?></strong>
                  <span class="p3-badge p3-badge-warn"><i class="bi bi-arrow-return-left"></i> Returning</span>
                  <?php if ($stockQty <= 5): ?>
                    <span class="p3-badge p3-badge-danger">Low Stock</span>
                  <?php else: ?>
                    <span class="p3-badge p3-badge-ok">In Stock</span>
                  <?php endif; ?>
                </div>
                <div class="text-muted small">
                  SKU: <?php echo htmlspecialchars($selectedProduct['sku'] ?? '—'); ?>
                  · ID: <?php echo (int)$pid; ?>
                  · Batch: <?php echo htmlspecialchars($selectedProduct['batch_number'] ?? '—'); ?>
                </div>
              </div>
              <a class="btn btn-outline-primary p3-btn" href="<?php echo BASE_URL; ?>?controller=purchase&action=index">
                <i class="bi bi-clock-history"></i> Purchase History
              </a>
            </div>
          </div>
        </div>

        <section class="p3-card">
          <div class="p3-card-header">
            <h2><i class="bi bi-grid-3x3-gap text-primary"></i> Product Entry Grid</h2>
            <div id="tableActions" class="btn-group flex-wrap">
              <button type="button" id="btnExportCsv" class="btn btn-outline-secondary btn-sm">Export CSV</button>
              <button type="button" id="btnExportExcel" class="btn btn-outline-secondary btn-sm">Export Excel</button>
              <button type="button" id="btnPrint" class="btn btn-outline-secondary btn-sm">Print</button>
              <button type="button" id="btnColVis" class="btn btn-outline-secondary btn-sm">Column visibility</button>
              <button type="button" id="btnExportPdf" class="btn btn-outline-secondary btn-sm">Export PDF</button>
            </div>
          </div>
          <div class="p3-card-body">
            <div class="table-responsive p3-table-wrap p3-desktop-table">
              <table id="purchase3ProductTable" class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th style="width:56px">Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Buying Price</th>
                    <th>Incl. Tax Price</th>
                    <th>Selling Price</th>
                    <th>Wholesale Price</th>
                    <th>Stock Qty</th>
                    <th>Supplier</th>
                    <th>Batch</th>
                    <th>Expiry Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <?php if (!empty($selectedProduct['image'])): ?>
                        <img src="<?php echo htmlspecialchars($selectedProduct['image']); ?>" alt="<?php echo htmlspecialchars($selectedProduct['name'] ?? 'Product'); ?>" class="rounded border purchase3-product-img" style="width:48px;height:48px;object-fit:cover" />
                      <?php else: ?>
                        <div class="bg-light border rounded" style="width:48px;height:48px"></div>
                      <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($selectedProduct['name'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($selectedProduct['sku'] ?? '—'); ?></td>
                    <td><?php echo isset($selectedProduct['price']) ? htmlspecialchars(number_format((float)$selectedProduct['price'], 2)) : '—'; ?></td>
                    <td><?php echo isset($selectedProduct['sale_price']) ? htmlspecialchars(number_format((float)$selectedProduct['sale_price'], 2)) : '—'; ?></td>
                    <td><?php echo isset($selectedProduct['price2']) ? htmlspecialchars(number_format((float)$selectedProduct['price2'], 2)) : '—'; ?></td>
                    <td><?php echo isset($selectedProduct['price3']) ? htmlspecialchars(number_format((float)$selectedProduct['price3'], 2)) : '—'; ?></td>
                    <td><?php echo isset($selectedProduct['stock_quantity']) ? htmlspecialchars(number_format((float)$selectedProduct['stock_quantity'], 2)) : '—'; ?></td>
                    <td><?php echo htmlspecialchars($selectedProduct['supplier'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($selectedProduct['batch_number'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($selectedProduct['expiry_date'] ?? '—'); ?></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="p3-mobile-cards" aria-label="Product details cards">
              <div class="p3-mcard">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <?php if (!empty($selectedProduct['image'])): ?>
                    <img src="<?php echo htmlspecialchars($selectedProduct['image']); ?>" alt="" class="purchase3-product-img" style="width:48px;height:48px" />
                  <?php endif; ?>
                  <div>
                    <strong><?php echo htmlspecialchars($selectedProduct['name'] ?? '—'); ?></strong>
                    <div class="small text-muted"><?php echo htmlspecialchars($selectedProduct['sku'] ?? '—'); ?></div>
                  </div>
                </div>
                <div class="row-line"><span class="k">Buying</span><span><?php echo isset($selectedProduct['price']) ? number_format((float)$selectedProduct['price'], 2) : '—'; ?></span></div>
                <div class="row-line"><span class="k">Incl. Tax</span><span><?php echo isset($selectedProduct['sale_price']) ? number_format((float)$selectedProduct['sale_price'], 2) : '—'; ?></span></div>
                <div class="row-line"><span class="k">Selling</span><span><?php echo isset($selectedProduct['price2']) ? number_format((float)$selectedProduct['price2'], 2) : '—'; ?></span></div>
                <div class="row-line"><span class="k">Wholesale</span><span><?php echo isset($selectedProduct['price3']) ? number_format((float)$selectedProduct['price3'], 2) : '—'; ?></span></div>
                <div class="row-line"><span class="k">Stock</span><span><?php echo isset($selectedProduct['stock_quantity']) ? number_format((float)$selectedProduct['stock_quantity'], 2) : '—'; ?></span></div>
                <div class="row-line"><span class="k">Supplier</span><span><?php echo htmlspecialchars($selectedProduct['supplier'] ?? '—'); ?></span></div>
                <div class="row-line"><span class="k">Batch</span><span><?php echo htmlspecialchars($selectedProduct['batch_number'] ?? '—'); ?></span></div>
                <div class="row-line"><span class="k">Expiry</span><span><?php echo htmlspecialchars($selectedProduct['expiry_date'] ?? '—'); ?></span></div>
              </div>
            </div>
          </div>
        </section>

        <section class="p3-card" id="p3ReturnSection">
          <div class="p3-card-header">
            <h2><i class="bi bi-arrow-return-left text-danger"></i> Return Entry</h2>
            <span class="p3-badge p3-badge-danger">Stock will decrease</span>
          </div>
          <div class="p3-card-body">
            <form class="row g-3 align-items-end" method="post" action="<?php echo BASE_URL; ?>?controller=purchase&action=store" id="p3ReturnForm">
              <input type="hidden" name="is_return" value="1">
              <input type="hidden" name="purchase_date" value="<?php echo date('Y-m-d'); ?>">
              <input type="hidden" name="status" value="received">
              <input type="hidden" name="redirect_to" value="?controller=ListPurchaseController&highlight_product_id=<?php echo urlencode((string)$pid); ?>&returned=1">
              <?php if (!empty($original_purchase_id)): ?>
                <input type="hidden" name="original_purchase_id" value="<?php echo (int)$original_purchase_id; ?>">
              <?php endif; ?>

              <div class="col-6 col-md-3">
                <label class="form-label" for="p3RefNo">Reference Number</label>
                <input type="text" class="form-control" id="p3RefNo" placeholder="Supplier DN / Invoice">
                <div class="form-text"><span class="p3-ui-only">UI only</span></div>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p3WarehouseUi">Warehouse</label>
                <select class="form-select" id="p3WarehouseUi">
                  <option value="main" selected>Main Warehouse</option>
                  <option value="secondary">Secondary</option>
                </select>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p3CurrencyUi">Currency</label>
                <input type="text" class="form-control" id="p3CurrencyUi" value="<?php echo htmlspecialchars($currencySym); ?>" readonly>
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label" for="p3PriorityUi">Priority</label>
                <select class="form-select" id="p3PriorityUi">
                  <option value="normal">Normal</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>

              <?php if (!empty($selectedProduct['supplier_id'])): ?>
                <input type="hidden" name="supplier_id" value="<?php echo (int)$selectedProduct['supplier_id']; ?>">
                <div class="col-12 col-sm-4">
                  <label class="form-label">Supplier</label>
                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($supplierNameDisplay ?: ('#' . (int)$selectedProduct['supplier_id'])); ?>" readonly>
                </div>
              <?php else: ?>
                <div class="col-12 col-sm-4">
                  <label class="form-label" for="supplier_id">Supplier</label>
                  <select class="form-select" name="supplier_id" id="supplier_id" required aria-label="Supplier">
                    <option value="">Select supplier</option>
                    <?php if (!empty($suppliers) && is_array($suppliers)): ?>
                      <?php foreach ($suppliers as $s):
                        $sid = isset($s['id']) ? (int)$s['id'] : (int)($s->id ?? 0);
                        $sname = isset($s['name']) ? $s['name'] : ($s->name ?? '');
                        $isSel = (!empty($selectedProduct['supplier']) && strcasecmp(trim((string)$selectedProduct['supplier']), trim((string)$sname)) === 0) ? ' selected' : '';
                      ?>
                        <option value="<?php echo $sid; ?>"<?php echo $isSel; ?>><?php echo htmlspecialchars($sname); ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              <?php endif; ?>

              <input type="hidden" name="items[<?php echo $pid; ?>][product_id]" value="<?php echo $pid; ?>">
              <input type="hidden" name="items[<?php echo $pid; ?>][unit_price]" value="<?php echo $buyPrice; ?>">
              <input type="hidden" name="items[<?php echo $pid; ?>][base_stock]" value="<?php echo $stockQty; ?>">

              <div class="col-12 col-sm-4">
                <label class="form-label" for="p3ReturnQty">Return Qty</label>
                <input type="number"
                       class="form-control"
                       id="p3ReturnQty"
                       name="items[<?php echo $pid; ?>][quantity]"
                       value="<?php echo $returnQtyRaw; ?>"
                       min="1"
                       max="<?php echo $returnQtyRaw; ?>"
                       step="1"
                       <?php echo ($returnQtyRaw <= 0 ? 'disabled' : ''); ?>
                       required
                       aria-describedby="p3QtyHelp">
                <div class="form-text" id="p3QtyHelp">Max: <?php echo htmlspecialchars((string)$returnQtyRaw); ?></div>
              </div>

              <div class="col-12 col-sm-4 text-sm-end">
                <button type="submit" class="btn p3-btn p3-btn-primary mt-2" <?php echo ($returnQtyRaw <= 0 ? 'disabled' : ''); ?>>
                  <i class="bi bi-check2-circle"></i> Submit Return
                </button>
              </div>
            </form>
            <div class="form-text mt-2">Submitting will decrease the stock and return you to the Products list.</div>
          </div>
        </section>

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <section class="p3-card">
              <div class="p3-card-header">
                <h2><i class="bi bi-wallet2 text-primary"></i> Settlement</h2>
                <span class="p3-badge p3-badge-soft">UI only</span>
              </div>
              <div class="p3-card-body">
                <div class="row g-2">
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-secondary w-100 p3-btn">Cash</button></div>
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-secondary w-100 p3-btn">Card</button></div>
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-secondary w-100 p3-btn">Cheque</button></div>
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-secondary w-100 p3-btn">Bank</button></div>
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-primary w-100 p3-btn">Credit</button></div>
                  <div class="col-6 col-md-4"><button type="button" class="btn btn-outline-secondary w-100 p3-btn">Online</button></div>
                </div>
                <div class="row g-2 mt-2">
                  <div class="col-6">
                    <label class="form-label" for="p3TxnNo">Transaction No.</label>
                    <input type="text" class="form-control" id="p3TxnNo" placeholder="Optional">
                  </div>
                  <div class="col-6">
                    <label class="form-label" for="p3PayDate">Payment Date</label>
                    <input type="date" class="form-control" id="p3PayDate" value="<?php echo date('Y-m-d'); ?>">
                  </div>
                  <div class="col-12">
                    <label class="form-label" for="p3PayNotes">Payment Notes</label>
                    <textarea class="form-control" id="p3PayNotes" rows="2" placeholder="Credit note / adjustment notes"></textarea>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="col-12 col-lg-6">
            <section class="p3-card">
              <div class="p3-card-header">
                <h2><i class="bi bi-paperclip text-primary"></i> Attachments</h2>
                <span class="p3-badge p3-badge-soft">UI only</span>
              </div>
              <div class="p3-card-body">
                <div class="p3-attach-zone" role="region" aria-label="Attachment drop zone">
                  <i class="bi bi-cloud-arrow-up"></i>
                  Drag &amp; drop supplier invoice, delivery note, or quotation
                  <div class="small mt-1">PDF · Excel · Image — preview / replace / delete (UI)</div>
                </div>
              </div>
            </section>
          </div>
        </div>

      <?php else: ?>

        <section class="p3-card">
          <div class="p3-empty">
            <div class="icon-wrap"><i class="bi bi-arrow-return-left"></i></div>
            <h2 class="h5 mb-2">No product selected for return</h2>
            <p class="text-muted mb-3">Open Purchase Products, choose an item, then use <strong>Purchase Return</strong> to start this workflow.</p>
            <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn p3-btn p3-btn-primary">
              <i class="bi bi-box-seam"></i> Browse Products
            </a>
          </div>
        </section>

      <?php endif; ?>

      <section class="p3-card">
        <div class="p3-card-header">
          <h2><i class="bi bi-clock-history text-primary"></i> Return History</h2>
          <span class="p3-badge p3-badge-soft"><?php echo (int)$returnsCount; ?> records</span>
        </div>
        <div class="p3-card-body">
          <div class="table-responsive p3-table-wrap">
            <table id="returnsTable" class="table table-hover align-middle mb-0 w-100">
              <thead>
                <tr>
                  <th>Return #</th>
                  <th>Date</th>
                  <th>Supplier</th>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($returns) && is_array($returns)): ?>
                  <?php foreach ($returns as $ret): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($ret['return_no'] ?? $ret['id'] ?? '—'); ?></td>
                      <td><?php echo htmlspecialchars($ret['date'] ?? $ret['purchase_date'] ?? '—'); ?></td>
                      <td><?php echo htmlspecialchars($ret['supplier'] ?? $ret['supplier_name'] ?? '—'); ?></td>
                      <td><?php echo htmlspecialchars($ret['product'] ?? $ret['product_name'] ?? '—'); ?></td>
                      <td><?php echo htmlspecialchars($ret['qty'] ?? $ret['quantity'] ?? '—'); ?></td>
                      <td><?php echo htmlspecialchars($ret['amount'] ?? $ret['total_amount'] ?? '—'); ?></td>
                      <td><span class="badge bg-secondary"><?php echo htmlspecialchars($ret['status'] ?? '—'); ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php if ($returnsCount === 0): ?>
            <p class="text-muted small mt-2 mb-0">No return history loaded for this filter. Submit a return to update stock.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="p3-card">
        <div class="p3-card-header">
          <h2><i class="bi bi-bar-chart-line text-primary"></i> Return Analytics</h2>
          <span class="p3-badge p3-badge-soft">Chart.js · illustrative</span>
        </div>
        <div class="p3-card-body">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <h3 class="h6 text-muted mb-2">Return Trend</h3>
              <div class="p3-chart-box"><canvas id="p3TrendChart" aria-label="Return trend chart"></canvas></div>
            </div>
            <div class="col-12 col-md-6">
              <h3 class="h6 text-muted mb-2">Cost Distribution</h3>
              <div class="p3-chart-box"><canvas id="p3DistChart" aria-label="Cost distribution chart"></canvas></div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-12 col-xl-3">
      <div class="accordion d-xl-none mb-3" id="p3SideAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#p3AccSummary">Purchase Summary</button></h2>
          <div id="p3AccSummary" class="accordion-collapse collapse show" data-bs-parent="#p3SideAccordion">
            <div class="accordion-body" id="p3SummaryMobile"></div>
          </div>
        </div>
      </div>

      <aside class="d-none d-xl-block" aria-label="Return sidebar">
        <section class="p3-card">
          <div class="p3-card-header"><h3><i class="bi bi-calculator text-primary"></i> Return Summary</h3></div>
          <div class="p3-card-body" id="p3SummaryPanel">
            <div class="p3-summary-row"><span class="muted">Items</span><span><?php echo $hasProduct ? '1' : '0'; ?></span></div>
            <div class="p3-summary-row"><span class="muted">Quantity</span><span id="p3SumQty"><?php echo htmlspecialchars((string)$returnQtyRaw); ?></span></div>
            <div class="p3-summary-row"><span class="muted">Subtotal</span><span><?php echo htmlspecialchars($currencySym); ?> <?php echo number_format($returnValue, 2); ?></span></div>
            <div class="p3-summary-row"><span class="muted">Discount</span><span><?php echo htmlspecialchars($currencySym); ?> 0.00</span></div>
            <div class="p3-summary-row"><span class="muted">Tax</span><span><?php echo htmlspecialchars($currencySym); ?> 0.00</span></div>
            <div class="p3-summary-row"><span class="muted">Shipping</span><span><?php echo htmlspecialchars($currencySym); ?> 0.00</span></div>
            <div class="p3-summary-row"><span>Grand Total</span><span><?php echo htmlspecialchars($currencySym); ?> <?php echo number_format($returnValue, 2); ?></span></div>
          </div>
        </section>

        <section class="p3-card">
          <div class="p3-card-header"><h3><i class="bi bi-building text-primary"></i> Supplier</h3></div>
          <div class="p3-card-body">
            <div class="p3-supplier-card">
              <div class="p3-supplier-avatar" aria-hidden="true"><?php echo htmlspecialchars($supplierInitial); ?></div>
              <div>
                <strong><?php echo $supplierNameDisplay !== '' ? htmlspecialchars($supplierNameDisplay) : 'Not selected'; ?></strong>
                <div class="p3-meta-grid mt-2">
                  <div><div class="k">Code</div><div class="v"><?php echo !empty($selectedProduct['supplier_id']) ? 'SUP-' . (int)$selectedProduct['supplier_id'] : '—'; ?></div></div>
                  <div><div class="k">Rating</div><div class="v">★★★★☆</div></div>
                  <div><div class="k">Credit Limit</div><div class="v"><?php echo htmlspecialchars($currencySym); ?> —</div></div>
                  <div><div class="k">Outstanding</div><div class="v"><?php echo htmlspecialchars($currencySym); ?> <?php echo number_format($returnValue, 2); ?></div></div>
                </div>
                <div class="d-flex flex-wrap gap-1 mt-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="UI only"><i class="bi bi-telephone"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="UI only"><i class="bi bi-envelope"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="UI only"><i class="bi bi-geo-alt"></i></button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="p3-card">
          <div class="p3-card-header"><h3><i class="bi bi-warehouse text-primary"></i> Warehouse</h3></div>
          <div class="p3-card-body">
            <div class="p3-summary-row"><span class="muted">Location</span><span>Main</span></div>
            <div class="p3-summary-row"><span class="muted">Current Stock</span><span><?php echo number_format($stockQty, 2); ?></span></div>
            <div class="p3-summary-row"><span class="muted">After Return</span><span><?php echo number_format(max($stockQty - $returnQtyRaw, 0), 2); ?></span></div>
          </div>
        </section>

        <section class="p3-card">
          <div class="p3-card-header"><h3><i class="bi bi-sticky text-primary"></i> Quick Notes</h3></div>
          <div class="p3-card-body">
            <textarea class="form-control" id="p3QuickNotes" rows="3" placeholder="Internal notes (UI only)"></textarea>
          </div>
        </section>

        <section class="p3-card">
          <div class="p3-card-header"><h3><i class="bi bi-stars text-primary"></i> Top Returned</h3></div>
          <div class="p3-card-body">
            <ul class="p3-side-list">
              <li><span><?php echo $hasProduct ? htmlspecialchars($selectedProduct['name'] ?? 'Product') : '—'; ?></span><span class="text-muted"><?php echo (int)round($returnQtyRaw); ?></span></li>
            </ul>
          </div>
        </section>
      </aside>
    </div>
  </div>

  <div class="p3-actionbar" role="region" aria-label="Return actions">
    <div class="p3-actionbar-inner">
      <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo htmlspecialchars($backHref); ?>" class="btn btn-outline-secondary p3-btn">Cancel</a>
        <button type="button" class="btn btn-outline-secondary p3-btn" onclick="document.getElementById('p3FilterForm').reset();">Reset</button>
        <button type="button" class="btn btn-outline-secondary p3-btn" onclick="window.print()">Print</button>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-secondary p3-btn" id="p3DraftBar">Save Draft</button>
        <?php if ($hasProduct): ?>
          <button type="submit" form="p3ReturnForm" class="btn p3-btn p3-btn-primary" <?php echo ($returnQtyRaw <= 0 ? 'disabled' : ''); ?>>
            <i class="bi bi-check2-circle"></i> Complete Return
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="p3PreviewModal" tabindex="-1" aria-labelledby="p3PreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:20px">
      <div class="modal-header">
        <h5 class="modal-title" id="p3PreviewLabel">Return Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-1"><strong>Product:</strong> <?php echo $hasProduct ? htmlspecialchars($selectedProduct['name'] ?? '—') : '—'; ?></p>
        <p class="mb-1"><strong>Supplier:</strong> <?php echo htmlspecialchars($supplierNameDisplay ?: '—'); ?></p>
        <p class="mb-1"><strong>Qty:</strong> <?php echo htmlspecialchars((string)$returnQtyRaw); ?></p>
        <p class="mb-0"><strong>Total:</strong> <?php echo htmlspecialchars($currencySym); ?> <?php echo number_format($returnValue, 2); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <?php if ($hasProduct && $returnQtyRaw > 0): ?>
          <button type="submit" form="p3ReturnForm" class="btn btn-primary">Confirm &amp; Submit</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="p3AdvFilter" aria-labelledby="p3AdvFilterLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="p3AdvFilterLabel">Advanced Filters</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <p class="text-muted small">UI-only filters. Apply uses the main location &amp; date form above.</p>
    <div class="mb-3">
      <label class="form-label" for="p3AdvSupplier">Supplier</label>
      <input type="text" class="form-control" id="p3AdvSupplier" placeholder="Search supplier">
    </div>
    <div class="mb-3">
      <label class="form-label" for="p3AdvStatus">Status</label>
      <select class="form-select" id="p3AdvStatus">
        <option value="">All</option>
        <option value="received">Received</option>
        <option value="pending">Pending</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label" for="p3AdvWarehouse">Warehouse</label>
      <select class="form-select" id="p3AdvWarehouse">
        <option>Main Warehouse</option>
        <option>Secondary</option>
      </select>
    </div>
    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-bs-dismiss="offcanvas">Reset Filters</button>
    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="offcanvas" onclick="document.getElementById('p3FilterForm').requestSubmit();">Apply</button>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
  $(function(){
    var $exportTable = $('#purchase3ProductTable').length ? $('#purchase3ProductTable') : $('#returnsTable');
    var dt = null;
    if ($exportTable.length) {
      dt = $exportTable.DataTable({
        dom: 'lfrtip',
        order: [],
        pageLength: 25,
        paging: $exportTable.attr('id') === 'returnsTable',
        searching: $exportTable.attr('id') === 'returnsTable',
        info: $exportTable.attr('id') === 'returnsTable',
        language: { search: 'Search ... ' }
      });

      new $.fn.dataTable.Buttons(dt, {
        buttons: [
          { extend: 'csv', className: 'd-none', title: 'purchase_returns' },
          { extend: 'excel', className: 'd-none', title: 'purchase_returns' },
          { extend: 'print', className: 'd-none', title: 'Purchase Returns' },
          { extend: 'colvis', className: 'd-none' },
          { extend: 'pdf', className: 'd-none', title: 'Purchase Returns' }
        ]
      });

      $('#btnExportCsv').on('click', function(){ dt.button(0).trigger(); });
      $('#btnExportExcel').on('click', function(){ dt.button(1).trigger(); });
      $('#btnPrint').on('click', function(){ dt.button(2).trigger(); });
      $('#btnColVis').on('click', function(){ dt.button(3).trigger(); });
      $('#btnExportPdf').on('click', function(){ dt.button(4).trigger(); });
    }

    if ($('#returnsTable').length && $exportTable.attr('id') !== 'returnsTable') {
      $('#returnsTable').DataTable({
        dom: 'lfrtip',
        order: [],
        pageLength: 25,
        language: { search: 'Search ... ' }
      });
    }

    document.querySelectorAll('[data-counter]').forEach(function(el){
      var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
      var cur = 0;
      var step = Math.max(1, Math.ceil(target / 28));
      var timer = setInterval(function(){
        cur += step;
        if (cur >= target) { cur = target; clearInterval(timer); }
        el.textContent = cur.toLocaleString();
      }, 30);
    });

    function p3Toast(msg, type) {
      type = type || 'info';
      var host = document.getElementById('p3ToastHost');
      if (!host || !window.bootstrap) return;
      var el = document.createElement('div');
      el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : type) + ' border-0 show';
      el.setAttribute('role', 'alert');
      el.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
      host.appendChild(el);
      setTimeout(function(){ el.remove(); }, 3200);
    }

    $('#p3SaveDraftBtn, #p3DraftBar').on('click', function(){
      p3Toast('Draft saved locally (UI only). Submit Return to post to the server.', 'warning');
    });

    var panel = document.getElementById('p3SummaryPanel');
    var mobile = document.getElementById('p3SummaryMobile');
    if (panel && mobile) mobile.innerHTML = panel.innerHTML;

    var qtyInput = document.getElementById('p3ReturnQty');
    var sumQty = document.getElementById('p3SumQty');
    if (qtyInput && sumQty) {
      qtyInput.addEventListener('input', function(){
        sumQty.textContent = qtyInput.value || '0';
      });
    }

    if (window.Chart) {
      var trend = document.getElementById('p3TrendChart');
      if (trend) {
        new Chart(trend, {
          type: 'line',
          data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
              label: 'Returns',
              data: [2, 3, 1, 4, 2, <?php echo $hasProduct ? 1 : 0; ?>],
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37,99,235,0.12)',
              fill: true,
              tension: 0.35
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
      }
      var dist = document.getElementById('p3DistChart');
      if (dist) {
        new Chart(dist, {
          type: 'doughnut',
          data: {
            labels: ['Product Cost', 'Tax', 'Other'],
            datasets: [{
              data: [Math.max(<?php echo (int)round($returnValue); ?>, 1), 0, 0],
              backgroundColor: ['#2563eb', '#059669', '#d97706']
            }]
          },
          options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
      }
    }
  });
</script>
