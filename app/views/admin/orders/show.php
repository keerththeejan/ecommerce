<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 no-print">
                    <h3 class="card-title mb-0">Order #<?php echo $order['order']['id']; ?></h3>
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100 w-md-auto">
                        <div class="d-flex flex-wrap align-items-center gap-3 bg-light text-dark rounded p-2">
                            <strong class="me-2">Columns:</strong>
                            <label class="form-check form-check-inline m-0">
                                <input id="col_product" class="form-check-input" type="checkbox" checked> <span class="form-check-label">Product</span>
                            </label>
                            <label class="form-check form-check-inline m-0">
                                <input id="col_sku" class="form-check-input" type="checkbox" checked> <span class="form-check-label">SKU</span>
                            </label>
                            <label class="form-check form-check-inline m-0">
                                <input id="col_price" class="form-check-input" type="checkbox" checked> <span class="form-check-label">Price</span>
                            </label>
                            <label class="form-check form-check-inline m-0">
                                <input id="col_quantity" class="form-check-input" type="checkbox" checked> <span class="form-check-label">Quantity</span>
                            </label>
                            <label class="form-check form-check-inline m-0">
                                <input id="col_subtotal" class="form-check-input" type="checkbox" checked> <span class="form-check-label">Subtotal</span>
                            </label>
                            <button type="button" class="btn btn-sm btn-primary ms-1" onclick="printSelectedColumns()"><i class="fas fa-print"></i> Print Selected</button>
                        </div>
                        <div class="ms-md-2 d-flex gap-2">
                            <button type="button" class="btn btn-outline-light" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['order']['id']; ?>" class="btn btn-light">Update Status</a>
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['order']['id']; ?>" class="btn btn-light">Update Payment</a>
                            <a href="<?php echo BASE_URL; ?>?controller=mail&action=index&from=pos&order_id=<?php echo (int)$order['order']['id']; ?>" class="btn btn-light">
                               <i class="fas fa-envelope me-1"></i> Mail
                            </a>
                            <a href="<?php echo BASE_URL; ?>?controller=invoice&action=create&order_id=<?php echo (int)$order['order']['id']; ?>"
                               class="btn btn-warning text-dark"
                               onclick="return confirm('Create invoice for this order?');">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Make Bill & Invoice
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    <?php flash('invoice_success'); ?>
                    <?php flash('invoice_error', '', 'alert alert-danger'); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>#<?php echo $order['order']['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td><?php echo date('M d, Y H:i', strtotime($order['order']['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch($order['order']['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                break;
                                            case 'processing':
                                                $statusClass = 'bg-info';
                                                break;
                                            case 'shipped':
                                                $statusClass = 'bg-primary';
                                                break;
                                            case 'delivered':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['order']['status']); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td><?php echo ucfirst($order['order']['payment_method']); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <?php
                                        $paymentClass = '';
                                        switch($order['order']['payment_status']) {
                                            case 'pending':
                                                $paymentClass = 'bg-warning';
                                                break;
                                            case 'paid':
                                                $paymentClass = 'bg-success';
                                                break;
                                            case 'failed':
                                                $paymentClass = 'bg-danger';
                                                break;
                                            case 'refunded':
                                                $paymentClass = 'bg-info';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['order']['payment_status']); ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo $order['order']['first_name'] . ' ' . $order['order']['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $order['order']['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address:</th>
                                    <td><?php echo $order['order']['shipping_address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address:</th>
                                    <td><?php echo $order['order']['billing_address']; ?></td>
                                </tr>
                                <?php if(!empty($order['order']['notes'])): ?>
                                <tr>
                                    <th>Notes:</th>
                                    <td><?php echo $order['order']['notes']; ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <h5 class="mt-4">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped order-items-table">
                            <thead>
                                <tr>
                                    <th data-col="product">Product</th>
                                    <th data-col="sku">SKU</th>
                                    <th data-col="price">Price</th>
                                    <th data-col="quantity">Quantity</th>
                                    <th data-col="subtotal" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order['items'] as $item): ?>
                                    <tr>
                                        <td data-col="product"><?php echo $item['product_name']; ?></td>
                                        <td data-col="sku"><?php echo $item['sku']; ?></td>
                                        <td data-col="price"><?php echo formatPrice($item['price']); ?></td>
                                        <td data-col="quantity"><?php echo $item['quantity']; ?></td>
                                        <td data-col="subtotal" class="text-end"><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['total_amount'] - $order['order']['tax'] - $order['order']['shipping_fee']); ?></td>
                                </tr>
                                <?php if($order['order']['shipping_fee'] > 0): ?>
                                <tr>
                                    <th colspan="4" class="text-end">Shipping:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['shipping_fee']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($order['order']['tax'] > 0): ?>
                                <tr>
                                    <th colspan="4" class="text-end">Tax:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['tax']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <td class="text-end"><strong><?php echo formatPrice($order['order']['total_amount']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Print styles */
@media print {
  body * { visibility: hidden; }
  .container-fluid, .container-fluid * { visibility: visible; }
  .no-print, .no-print * { display: none !important; }
  .container-fluid { position: absolute; left: 0; top: 0; width: 100%; }
  a[href]:after { content: "" !important; }
}
</style>

<script>
// Print only selected order item columns
function printSelectedColumns() {
  const cols = ['product','sku','price','quantity','subtotal'];
  const checked = {
    product: document.getElementById('col_product')?.checked !== false,
    sku: document.getElementById('col_sku')?.checked !== false,
    price: document.getElementById('col_price')?.checked !== false,
    quantity: document.getElementById('col_quantity')?.checked !== false,
    subtotal: document.getElementById('col_subtotal')?.checked !== false,
  };

  const unchecked = cols.filter(c => !checked[c]);
  const table = document.querySelector('.order-items-table');
  if (!table) { window.print(); return; }

  // Inject print-only CSS to hide unchecked columns
  const style = document.createElement('style');
  style.id = 'print-selected-cols-style';
  let css = '@media print{';
  unchecked.forEach(c => {
    css += `.order-items-table [data-col="${c}"]{ display:none !important; }`;
  });
  // If subtotal column is hidden, hide the entire footer (Subtotal/Tax/Total)
  if (unchecked.includes('subtotal')) {
    css += `.order-items-table tfoot{ display:none !important; }`;
  }
  css += '}';
  style.textContent = css;
  document.head.appendChild(style);

  // Adjust totals footer colspan only if footer is visible
  let labelCells = [];
  if (!unchecked.includes('subtotal')) {
    const visibleCount = cols.length - unchecked.length; // total visible columns
    labelCells = table.querySelectorAll('tfoot th[colspan]');
    labelCells.forEach(th => {
      th.setAttribute('data-original-colspan', th.getAttribute('colspan'));
      th.setAttribute('colspan', Math.max(visibleCount - 1, 1));
    });
  }

  const cleanup = () => {
    const s = document.getElementById('print-selected-cols-style');
    if (s) s.remove();
    labelCells.forEach(th => {
      const orig = th.getAttribute('data-original-colspan');
      if (orig) {
        th.setAttribute('colspan', orig);
        th.removeAttribute('data-original-colspan');
      }
    });
    window.removeEventListener('afterprint', cleanup);
  };

  window.addEventListener('afterprint', cleanup);
  window.print();
}
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
