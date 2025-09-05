<?php
// Partial: product history table (expects $movements, $product, $currentPage, $totalPages)
?>
<div class="table-responsive">
  <table class="table table-striped table-hover align-middle mb-2">
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
      <?php if (empty($movements)): ?>
        <tr><td colspan="7" class="text-center text-muted">No stock movements found.</td></tr>
      <?php else: ?>
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
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php if (!empty($totalPages) && $totalPages > 1 && !empty($product['id'])): ?>
  <nav aria-label="Stock history pages" class="mt-2">
    <ul class="pagination pagination-sm mb-0">
      <?php $pid = urlencode($product['id']); ?>
      <?php for ($p = 1; $p <= (int)$totalPages; $p++): ?>
        <li class="page-item <?php echo ($p == (int)$currentPage) ? 'active' : ''; ?>">
          <a class="page-link history-page-link" data-page="<?php echo $p; ?>" href="<?php echo BASE_URL; ?>?controller=stock&action=history&id=<?php echo $pid; ?>&page=<?php echo $p; ?>&partial=1"><?php echo $p; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>
