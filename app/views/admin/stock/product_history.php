<?php
// Product Stock History View
?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">
      Stock History - <?php echo htmlspecialchars($product['name'] ?? ''); ?>
      <small class="text-muted">(SKU: <?php echo htmlspecialchars($product['sku'] ?? ''); ?>)</small>
    </h1>
    <div class="btn-group">
      <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-list me-1"></i> Products
      </a>
      <a href="<?php echo BASE_URL; ?>?controller=stock&action=index" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-boxes me-1"></i> Stock
      </a>
    </div>
  </div>

  <?php flash('success'); ?>
  <?php flash('error'); ?>

  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="fw-bold">Movement Details</span>
      <span class="badge bg-info">Total: <?php echo !empty($movements) ? count($movements) : 0; ?></span>
    </div>
    <div class="card-body">
      <?php if (empty($movements)): ?>
        <div class="alert alert-info mb-0">No stock movements found for this product.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead>
              <tr>
                <th>Date</th>
                <th>Action</th>
                <th class="text-end">Previous Qty</th>
                <th class="text-end">Adjustment</th>
                <th class="text-end">New Qty</th>
                <th>Notes</th>
                <th>By</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($movements as $m): ?>
                <tr>
                  <td><?php echo !empty($m->created_at) ? date('Y-m-d H:i', strtotime($m->created_at)) : '-'; ?></td>
                  <td>
                    <?php
                      $action = strtolower($m->action ?? '');
                      $badge = 'secondary';
                      if ($action === 'add') $badge = 'success';
                      elseif ($action === 'subtract') $badge = 'danger';
                      elseif ($action === 'set') $badge = 'warning text-dark';
                    ?>
                    <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($action); ?></span>
                  </td>
                  <td class="text-end"><?php echo number_format((float)($m->previous_quantity ?? 0), 2); ?></td>
                  <td class="text-end <?php echo ((float)($m->adjustment ?? 0) < 0) ? 'text-danger' : 'text-success'; ?>">
                    <?php
                      $adj = (float)($m->adjustment ?? 0);
                      echo ($adj > 0 ? '+' : '') . number_format($adj, 2);
                    ?>
                  </td>
                  <td class="text-end"><?php echo number_format((float)($m->new_quantity ?? 0), 2); ?></td>
                  <td><?php echo !empty($m->notes) ? htmlspecialchars($m->notes) : '<span class="text-muted">-</span>'; ?></td>
                  <td><?php echo !empty($m->user_name) ? htmlspecialchars($m->user_name) : '<span class="text-muted">System</span>'; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if (!empty($totalPages) && $totalPages > 1): ?>
          <nav aria-label="Stock history pages" class="mt-3">
            <ul class="pagination pagination-sm mb-0">
              <?php $pid = urlencode($product['id']); ?>
              <?php for ($p = 1; $p <= (int)$totalPages; $p++): ?>
                <li class="page-item <?php echo ($p == (int)$currentPage) ? 'active' : ''; ?>">
                  <a class="page-link" href="<?php echo BASE_URL; ?>?controller=stock&action=history&id=<?php echo $pid; ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
