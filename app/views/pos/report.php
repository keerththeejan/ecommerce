<?php
// Staff only
if(!isStaff()) { redirect('user/login'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>?controller=pos">POS System</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos">POS</a></li>
      <li class="nav-item"><a class="nav-link active" href="<?php echo BASE_URL; ?>?controller=pos&action=report">Reports</a></li>
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=session">Session</a></li>
    </ul>
  </div>
</nav>
<div class="container py-4">
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="get" action="<?php echo BASE_URL; ?>">
        <input type="hidden" name="controller" value="pos">
        <input type="hidden" name="action" value="report">
        <div class="col-sm-6 col-md-4">
          <label class="form-label">Session</label>
          <select name="session_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Sessions</option>
            <?php foreach($sessions as $s): ?>
              <option value="<?php echo $s['id']; ?>" <?php echo (!empty($sessionId) && $sessionId==$s['id'])?'selected':''; ?>>
                #<?php echo $s['id']; ?> — <?php echo htmlspecialchars($s['opened_at'] ?? ''); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Daily Sales Report</h5>
    </div>
    <div class="card-body">
      <?php if(empty($report)): ?>
        <div class="alert alert-info">No sales found for the selected criteria.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-end">Orders</th>
                <th class="text-end">Subtotal</th>
                <th class="text-end">Tax</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $sumOrders=0;$sumSub=0;$sumTax=0;$sumTotal=0; 
                foreach($report as $row):
                  $sumOrders += (int)($row['orders'] ?? 0);
                  $sumSub   += (float)($row['subtotal'] ?? 0);
                  $sumTax   += (float)($row['tax'] ?? 0);
                  $sumTotal += (float)($row['total'] ?? 0);
              ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['date'] ?? $row['day'] ?? ''); ?></td>
                  <td class="text-end"><?php echo (int)($row['orders'] ?? 0); ?></td>
                  <td class="text-end"><?php echo formatPrice($row['subtotal'] ?? 0); ?></td>
                  <td class="text-end"><?php echo formatPrice($row['tax'] ?? 0); ?></td>
                  <td class="text-end"><?php echo formatPrice($row['total'] ?? 0); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Total</th>
                <th class="text-end"><?php echo $sumOrders; ?></th>
                <th class="text-end"><?php echo formatPrice($sumSub); ?></th>
                <th class="text-end"><?php echo formatPrice($sumTax); ?></th>
                <th class="text-end"><?php echo formatPrice($sumTotal); ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
