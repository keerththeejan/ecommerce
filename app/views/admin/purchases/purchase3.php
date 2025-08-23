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
  </div>

  <!-- Filters -->
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

  <!-- All Purchase Returns table -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Purchase Returns</h5>
        <a href="#" class="btn btn-primary rounded-pill px-3">
          <i class="fas fa-plus me-2"></i>Add
        </a>
      </div>

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
        <table class="table table-bordered" id="returnsTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Reference No</th>
              <th>Parent Purchase</th>
              <th>Location</th>
              <th>Supplier</th>
              <th>Payment Status</th>
              <th>Grand Total</th>
              <th>Payment due</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($returns)): ?>
              <?php foreach ($returns as $r): ?>
                <tr>
                  <td><?php echo htmlspecialchars($r['date']); ?></td>
                  <td><?php echo htmlspecialchars($r['reference_no']); ?></td>
                  <td><?php echo htmlspecialchars($r['parent_reference'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($r['location'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars($r['supplier'] ?? '-'); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst($r['payment_status'] ?? '')); ?></td>
                  <td><?php echo htmlspecialchars($r['grand_total'] ?? '0.00'); ?></td>
                  <td><?php echo htmlspecialchars($r['payment_due'] ?? '0.00'); ?></td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">View</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="9" class="text-center">No data available in table</td></tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr class="table-active">
              <td colspan="6" class="text-end"><strong>Total:</strong></td>
              <td id="grand-total"><strong>Rs 0.00</strong></td>
              <td id="due-total"><strong>Rs 0.00</strong></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

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
