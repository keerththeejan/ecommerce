<?php
// Staff only
if(!isStaff()) { redirect('user/login'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Receipt #<?php echo (int)$order['order']['id']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display:none !important; }
    }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Receipt</h3>
    <div class="no-print">
      <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>?controller=pos"><i class="fas fa-arrow-left"></i> Back to POS</a>
      <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
    </div>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <p class="mb-1"><strong>Order ID:</strong> <?php echo (int)$order['order']['id']; ?></p>
          <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($order['order']['created_at'] ?? date('Y-m-d H:i')); ?></p>
        </div>
        <div class="col-md-6 text-md-end">
          <p class="mb-1"><strong>Payment:</strong> <?php echo htmlspecialchars($order['order']['payment_method'] ?? 'cash'); ?></p>
          <p class="mb-1"><strong>Status:</strong> <?php echo htmlspecialchars($order['order']['payment_status'] ?? 'paid'); ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Product</th>
              <th class="text-end">Price</th>
              <th class="text-end">Qty</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($order['items'] as $item): ?>
              <tr>
                <td><?php echo htmlspecialchars($item['product_name'] ?? ($item['name'] ?? 'Item')); ?></td>
                <td class="text-end"><?php echo formatPrice($item['price']); ?></td>
                <td class="text-end"><?php echo (int)$item['quantity']; ?></td>
                <td class="text-end"><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Subtotal:</th>
              <td class="text-end"><?php echo formatPrice($summary['subtotal'] ?? (($order['order']['total_amount'] ?? 0) - ($order['order']['tax'] ?? 0))); ?></td>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Discount:</th>
              <td class="text-end">- <?php echo formatPrice($summary['discount'] ?? 0); ?></td>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Tax:</th>
              <td class="text-end"><?php echo formatPrice($summary['tax'] ?? ($order['order']['tax'] ?? 0)); ?></td>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Shipping:</th>
              <td class="text-end"><?php echo formatPrice($summary['shipping'] ?? 0); ?></td>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Total:</th>
              <td class="text-end fw-bold"><?php echo formatPrice($summary['total'] ?? ($order['order']['total_amount'] ?? 0)); ?></td>
            </tr>
            <tr>
              <th colspan="3" class="text-end">Paid:</th>
              <td class="text-end"><?php echo formatPrice($summary['paid'] ?? 0); ?></td>
            </tr>
            <?php if (!empty($summary['balance']) && ($summary['balance'] > 0)): ?>
            <tr>
              <th colspan="3" class="text-end">Balance Due:</th>
              <td class="text-end text-danger"><?php echo formatPrice($summary['balance']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($summary['change']) && ($summary['change'] > 0)): ?>
            <tr>
              <th colspan="3" class="text-end">Change:</th>
              <td class="text-end text-success"><?php echo formatPrice($summary['change']); ?></td>
            </tr>
            <?php endif; ?>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
