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
      .print-container { display:block; }
      /* Clean bill look: remove boxes, borders, stripes */
      .card { border: 0 !important; box-shadow: none !important; }
      .table { width: 100%; border-collapse: collapse !important; }
      .table > :not(caption) > * > * { box-shadow: none !important; }
      .table th, .table td { border: 0 !important; background: transparent !important; }
      .table-striped tbody tr:nth-of-type(odd) { --bs-table-accent-bg: transparent !important; background-color: transparent !important; }
      /* Typography and spacing for bill */
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      .table th { font-weight: 600; }
      .table td, .table th { padding: 6px 0 !important; }
      tfoot th, tfoot td { padding-top: 4px !important; }
      tfoot tr th { font-weight: 600; }
      tfoot tr:last-child td { font-weight: 700; }
      /* Remove outer card look around sections */
      .card.shadow-sm { box-shadow: none !important; }
      .card-body { padding: 0 !important; }
      .mb-3 { margin-bottom: .5rem !important; }
      /* Single separator line between header and items */
      .bill-separator { border: 0; border-top: 1px solid #999; margin: 8px 0 12px; }
    }
    @media screen {
      /* Hide the receipt content on screen; show only in print */
      .print-container { display:none; }
    }
  </style>
</head>
<body>
<div class="container py-4 print-container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Receipt</h3>
    <!-- Controls intentionally hidden on screen; printing triggered automatically -->
    <div class="no-print"></div>
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

  <hr class="bill-separator" />

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
            <!-- Subtotal/Discount/Tax/Shipping intentionally hidden in receipt -->
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
<script>
  // Auto-print on load and close this tab afterwards
  window.addEventListener('load', function(){
    setTimeout(function(){
      window.print();
      // Give the print dialog a moment; closing will work when opened in a new tab/window
      setTimeout(function(){ window.close(); }, 500);
    }, 300);
  });
  // Optional: close on afterprint as a fallback
  window.addEventListener('afterprint', function(){
    setTimeout(function(){ window.close(); }, 200);
  });
</script>
</body>
</html>
