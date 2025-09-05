<?php
// Expect: $title, $locations, $returns (array)
$title = $title ?? 'Purchase Return';
$locations = $locations ?? [['id' => 'all', 'name' => 'All']];
$returns = $returns ?? [];

// Build display-only date range like screenshot
$start = $_GET['start_date'] ?? '';
$end   = $_GET['end_date'] ?? '';
if ($start && $end) {
    $displayRange = date('m/d/Y', strtotime($start)) . ' - ' . date('m/d/Y', strtotime($end));
} else {
    $displayRange = '01/01/' . date('Y') . ' - 12/31/' . date('Y');
}
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><?php echo htmlspecialchars($title); ?></h1>
    <div>
      <?php if (!empty($selectedProduct['id'])): ?>
        <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController&highlight_product_id=<?php echo urlencode($selectedProduct['id']); ?>" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      <?php else: ?>
        <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($selectedProduct)) : ?>
    <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
      <div class="d-flex align-items-center">
        <?php if (!empty($selectedProduct['image'])): ?>
          <img src="<?php echo htmlspecialchars($selectedProduct['image']); ?>" alt="<?php echo htmlspecialchars($selectedProduct['name'] ?? 'Product'); ?>" style="width:48px;height:48px;object-fit:cover" class="rounded border me-3" />
        <?php endif; ?>
        <div>
          <div class="fw-bold">Returning: <?php echo htmlspecialchars($selectedProduct['name'] ?? ('#' . ($selectedProduct['id'] ?? ''))); ?></div>
          <small class="text-muted">SKU: <?php echo htmlspecialchars($selectedProduct['sku'] ?? '-'); ?> | ID: <?php echo htmlspecialchars($selectedProduct['id'] ?? '-'); ?></small>
        </div>
      </div>
      <div class="ms-3">
        <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=purchase&action=index">
          <i class="fas fa-undo-alt me-1"></i>Start Return Entry
        </a>
      </div>
    </div>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-0">
      <strong><i class="fas fa-filter me-2"></i>Filters</strong>
    </div>
    <div class="card-body">
      <form class="row g-3" method="get" action="<?php echo BASE_URL; ?>">
        <input type="hidden" name="controller" value="purchase">
        <input type="hidden" name="action" value="purchase3">
        <div class="col-12 col-md-4">
          <label class="form-label">Business Location</label>
          <select class="form-select" name="location_id">
            <?php foreach ($locations as $loc): ?>
              <option value="<?php echo htmlspecialchars($loc['id']); ?>"><?php echo htmlspecialchars($loc['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 col-md-4">
          <label class="form-label">Date Range:</label>
          <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($displayRange); ?>" readonly>
          <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start); ?>">
          <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end); ?>">
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end justify-content-end">
          <div>
            <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i>Apply</button>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase3" class="btn btn-outline-secondary">Reset</a>
          </div>
        </div>
      </form>
    </div>
  </div>
    <!-- Selected Product Details Table -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        
        <strong><i class="fas fa-box me-2"></i>Selected Product Details</strong>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
        <div></div>
        <div id="tableActions" class="btn-group">
          <button id="btnExportCsv" class="btn btn-outline-secondary btn-sm">Export CSV</button>
          <button id="btnExportExcel" class="btn btn-outline-secondary btn-sm">Export Excel</button>
          <button id="btnPrint" class="btn btn-outline-secondary btn-sm">Print</button>
          <button id="btnColVis" class="btn btn-outline-secondary btn-sm">Column visibility</button>
          <button id="btnExportPdf" class="btn btn-outline-secondary btn-sm">Export PDF</button>
        </div>
      </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:48px">Image</th>
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
                    <img src="<?php echo htmlspecialchars($selectedProduct['image']); ?>" alt="<?php echo htmlspecialchars($selectedProduct['name'] ?? 'Product'); ?>" style="width:48px;height:48px;object-fit:cover" class="rounded border" />
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
      </div>
    </div>
  <?php endif; ?>

  <!-- Filters -->
  

  <!-- All Purchase Returns table -->

</div>

<?php if (!empty($selectedProduct)) : ?>
  <div class="container-fluid mt-3">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <strong><i class="fas fa-undo-alt me-2"></i>Return</strong>
      </div>
      <div class="card-body">
        <form class="row g-3 align-items-end" method="post" action="<?php echo BASE_URL; ?>?controller=purchase&action=store">
          <!-- Required core fields for store() -->
          <input type="hidden" name="is_return" value="1">
          <input type="hidden" name="purchase_date" value="<?php echo date('Y-m-d'); ?>">
          <input type="hidden" name="status" value="received">
          <input type="hidden" name="redirect_to" value="?controller=ListPurchaseController&highlight_product_id=<?php echo urlencode((int)($selectedProduct['id'] ?? 0)); ?>&returned=1">
          <?php if (!empty($original_purchase_id)): ?>
            <input type="hidden" name="original_purchase_id" value="<?php echo (int)$original_purchase_id; ?>">
          <?php endif; ?>
          <?php if (!empty($selectedProduct['supplier_id'])): ?>
            <?php
              // Resolve supplier display name
              $supplierNameDisplay = '';
              if (!empty($selectedProduct['supplier'])) {
                $supplierNameDisplay = (string)$selectedProduct['supplier'];
              } elseif (!empty($suppliers) && is_array($suppliers)) {
                foreach ($suppliers as $s) {
                  $sid = isset($s['id']) ? (int)$s['id'] : (int)($s->id ?? 0);
                  $sname = isset($s['name']) ? $s['name'] : ($s->name ?? '');
                  if ($sid === (int)$selectedProduct['supplier_id']) { $supplierNameDisplay = $sname; break; }
                }
              }
            ?>
            <input type="hidden" name="supplier_id" value="<?php echo (int)$selectedProduct['supplier_id']; ?>">
            <div class="col-12 col-sm-4">
              <label class="form-label">Supplier</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($supplierNameDisplay ?: ('#'.(int)$selectedProduct['supplier_id'])); ?>" readonly>
            </div>
          <?php else: ?>
            <div class="col-12 col-sm-4">
              <label class="form-label">Supplier</label>
              <select class="form-select" name="supplier_id" required>
                <option value="">Select supplier</option>
                <?php if (!empty($suppliers) && is_array($suppliers)): ?>
                  <?php foreach ($suppliers as $s): $sid = isset($s['id']) ? (int)$s['id'] : (int)($s->id ?? 0); $sname = isset($s['name']) ? $s['name'] : ($s->name ?? ''); $isSel = (!empty($selectedProduct['supplier']) && strcasecmp(trim((string)$selectedProduct['supplier']), trim((string)$sname)) === 0) ? ' selected' : ''; ?>
                    <option value="<?php echo $sid; ?>"<?php echo $isSel; ?>><?php echo htmlspecialchars($sname); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          <?php endif; ?>

          <!-- Item payload -->
          <input type="hidden" name="items[<?php echo (int)($selectedProduct['id'] ?? 0); ?>][product_id]" value="<?php echo (int)($selectedProduct['id'] ?? 0); ?>">
          <input type="hidden" name="items[<?php echo (int)($selectedProduct['id'] ?? 0); ?>][unit_price]" value="<?php echo isset($selectedProduct['price']) ? (float)$selectedProduct['price'] : 0; ?>">
          <input type="hidden" name="items[<?php echo (int)($selectedProduct['id'] ?? 0); ?>][base_stock]" value="<?php echo isset($selectedProduct['stock_quantity']) ? (float)$selectedProduct['stock_quantity'] : 0; ?>">

          <?php
            $__lastPurchasedRaw = isset($selectedProduct['last_purchase_qty']) ? (float)$selectedProduct['last_purchase_qty'] : null;
            $__stockQtyRaw = isset($selectedProduct['stock_quantity']) ? (float)$selectedProduct['stock_quantity'] : 0;
            $__returnQtyRaw = ($__lastPurchasedRaw !== null && $__lastPurchasedRaw > 0)
              ? $__lastPurchasedRaw
              : ($__stockQtyRaw > 0 ? $__stockQtyRaw : 0);
          ?>
          <div class="col-12 col-sm-4">
            <label class="form-label">Return Qty</label>
            <input type="number" class="form-control" name="items[<?php echo (int)($selectedProduct['id'] ?? 0); ?>][quantity]" value="<?php echo $__returnQtyRaw; ?>" min="1" max="<?php echo $__returnQtyRaw; ?>" step="1" <?php echo ($__returnQtyRaw <= 0 ? 'disabled' : ''); ?> required>
          </div>
          
          
          <div class="col-12 col-sm-4 text-sm-end">
            <button type="submit" class="btn btn-primary mt-2" <?php echo ($__returnQtyRaw <= 0 ? 'disabled' : ''); ?>>Submit Return</button>
          </div>
        </form>
        <div class="form-text mt-2">Submitting will decrease the stock and return you to the Products list.</div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- DataTables assets -->
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

<script>
  $(function(){
    const dt = $('#returnsTable').DataTable({
      dom: 'lfrtip',
      order: [],
      pageLength: 25,
      language: { search: 'Search ... ' }
    });

    // Attach export/print/colvis buttons
    new $.fn.dataTable.Buttons(dt, {
      buttons: [
        { extend: 'csv', className: 'd-none', title: 'purchase_returns' },
        { extend: 'excel', className: 'd-none', title: 'purchase_returns' },
        { extend: 'print', className: 'd-none', title: 'Purchase Returns' },
        { extend: 'colvis', className: 'd-none' },
        { extend: 'pdf', className: 'd-none', title: 'Purchase Returns' }
      ]
    });

    $('#btnExportCsv').on('click', () => dt.button(0).trigger());
    $('#btnExportExcel').on('click', () => dt.button(1).trigger());
    $('#btnPrint').on('click', () => dt.button(2).trigger());
    $('#btnColVis').on('click', () => dt.button(3).trigger());
    $('#btnExportPdf').on('click', () => dt.button(4).trigger());
  });
</script>
