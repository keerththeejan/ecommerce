<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice['invoice_number']; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        .logo {
            max-width: 180px;
            margin-bottom: 20px;
        }
        .text-primary {
            color: #0d6efd !important;
        }
        .text-end {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
            border: 0;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .text-muted {
            color: #6c757d !important;
        }
        .mb-0 {
            margin-bottom: 0 !important;
        }
        .mb-1 {
            margin-bottom: 0.25rem !important;
        }
        .mb-2 {
            margin-bottom: 0.5rem !important;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
        .mb-5 {
            margin-bottom: 3rem !important;
        }
        .mt-0 {
            margin-top: 0 !important;
        }
        .mt-1 {
            margin-top: 0.25rem !important;
        }
        .mt-2 {
            margin-top: 0.5rem !important;
        }
        .mt-3 {
            margin-top: 1rem !important;
        }
        .mt-4 {
            margin-top: 1.5rem !important;
        }
        .mt-5 {
            margin-top: 3rem !important;
        }
        .p-0 {
            padding: 0 !important;
        }
        .p-1 {
            padding: 0.25rem !important;
        }
        .p-2 {
            padding: 0.5rem !important;
        }
        .p-3 {
            padding: 1rem !important;
        }
        .p-4 {
            padding: 1.5rem !important;
        }
        .p-5 {
            padding: 3rem !important;
        }
        .fw-bold {
            font-weight: 700 !important;
        }
        .text-uppercase {
            text-transform: uppercase !important;
        }
        .text-nowrap {
            white-space: nowrap !important;
        }
        .small {
            font-size: 0.875em;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .text-success {
            color: #198754 !important;
        }
        .text-warning {
            color: #ffc107 !important;
        }
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .bg-success {
            background-color: #198754 !important;
        }
        .bg-danger {
            background-color: #dc3545 !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
        }
        .border-0 {
            border: 0 !important;
        }
        .border-top {
            border-top: 1px solid #dee2e6 !important;
        }
        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
        .w-100 {
            width: 100% !important;
        }
        .mw-100 {
            max-width: 100% !important;
        }
        .h-100 {
            height: 100% !important;
        }
        .mh-100 {
            max-height: 100% !important;
        }
        .d-flex {
            display: flex !important;
        }
        .justify-content-between {
            justify-content: space-between !important;
        }
        .align-items-center {
            align-items: center !important;
        }
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-0.5 * var(--bs-gutter-x));
            margin-left: calc(-0.5 * var(--bs-gutter-x));
        }
        .row > * {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-top: var(--bs-gutter-y);
        }
        .col {
            flex: 1 0 0%;
        }
        .col-1 { flex: 0 0 auto; width: 8.33333333%; }
        .col-2 { flex: 0 0 auto; width: 16.66666667%; }
        .col-3 { flex: 0 0 auto; width: 25%; }
        .col-4 { flex: 0 0 auto; width: 33.33333333%; }
        .col-5 { flex: 0 0 auto; width: 41.66666667%; }
        .col-6 { flex: 0 0 auto; width: 50%; }
        .col-7 { flex: 0 0 auto; width: 58.33333333%; }
        .col-8 { flex: 0 0 auto; width: 66.66666667%; }
        .col-9 { flex: 0 0 auto; width: 75%; }
        .col-10 { flex: 0 0 auto; width: 83.33333333%; }
        .col-11 { flex: 0 0 auto; width: 91.66666667%; }
        .col-12 { flex: 0 0 auto; width: 100%; }
        @media print {
            body {
                padding: 0;
                background: none;
            }
            .invoice-container {
                border: 0;
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-6">
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="<?php echo SITE_NAME; ?>" class="logo">
                <address class="mt-2">
                    <strong><?php echo SITE_NAME; ?></strong><br>
                    <?php echo nl2br(htmlspecialchars(SITE_ADDRESS)); ?><br>
                    <?php if(!empty(SITE_PHONE)): ?>
                        <i class="fas fa-phone me-1"></i> <?php echo SITE_PHONE; ?><br>
                    <?php endif; ?>
                    <?php if(!empty(SITE_EMAIL)): ?>
                        <i class="fas fa-envelope me-1"></i> <?php echo SITE_EMAIL; ?>
                    <?php endif; ?>
                </address>
            </div>
            <div class="col-6 text-end">
                <h1 class="h3 mb-3">INVOICE</h1>
                <p class="mb-1">
                    <strong>Invoice #:</strong> <?php echo $invoice['invoice_number']; ?>
                </p>
                <p class="mb-1">
                    <strong>Order #:</strong> <?php echo $invoice['order_number']; ?>
                </p>
                <p class="mb-1">
                    <strong>Date:</strong> <?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?>
                </p>
                <p class="mb-1">
                    <strong>Due Date:</strong> <?php echo date('M d, Y', strtotime($invoice['due_date'])); ?>
                </p>
                <p class="mb-0">
                    <strong>Status:</strong> 
                    <span class="badge bg-<?php 
                        echo $invoice['status'] === 'paid' ? 'success' : 
                            ($invoice['status'] === 'overdue' ? 'danger' : 
                            ($invoice['status'] === 'partially_paid' ? 'warning' : 'secondary')); 
                    ?>">
                        <?php echo ucwords(str_replace('_', ' ', $invoice['status'])); ?>
                    </span>
                </p>
            </div>
        </div>
        
        <!-- Bill To / Ship To -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card border-0 bg-light p-3 h-100">
                    <h5 class="text-uppercase small fw-bold mb-3">Bill To</h5>
                    <address class="mb-0">
                        <strong><?php echo htmlspecialchars($invoice['billing_name'] ?? $invoice['customer_name']); ?></strong><br>
                        <?php echo nl2br(htmlspecialchars($invoice['billing_address'] ?? $invoice['customer_address'])); ?><br>
                        <?php 
                        $billingCity = $invoice['billing_city'] ?? $invoice['customer_city'];
                        $billingState = $invoice['billing_state'] ?? $invoice['customer_state'];
                        $billingZip = $invoice['billing_zip'] ?? $invoice['customer_zip'];
                        $billingCountry = $invoice['billing_country'] ?? $invoice['customer_country'];
                        
                        echo htmlspecialchars(trim(implode(', ', array_filter([$billingCity, $billingState, $billingZip, $billingCountry]))));
                        ?><br>
                        <?php if(!empty($invoice['billing_phone'] ?? $invoice['customer_phone'])): ?>
                            <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($invoice['billing_phone'] ?? $invoice['customer_phone']); ?><br>
                        <?php endif; ?>
                        <?php if(!empty($invoice['customer_email'])): ?>
                            <i class="fas fa-envelope me-1"></i> <?php echo htmlspecialchars($invoice['customer_email']); ?>
                        <?php endif; ?>
                    </address>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-light p-3 h-100">
                    <h5 class="text-uppercase small fw-bold mb-3">Ship To</h5>
                    <address class="mb-0">
                        <strong><?php echo htmlspecialchars($invoice['shipping_name'] ?? $invoice['customer_name']); ?></strong><br>
                        <?php echo nl2br(htmlspecialchars($invoice['shipping_address'] ?? $invoice['customer_address'])); ?><br>
                        <?php 
                        $shippingCity = $invoice['shipping_city'] ?? $invoice['customer_city'];
                        $shippingState = $invoice['shipping_state'] ?? $invoice['customer_state'];
                        $shippingZip = $invoice['shipping_zip'] ?? $invoice['customer_zip'];
                        $shippingCountry = $invoice['shipping_country'] ?? $invoice['customer_country'];
                        
                        echo htmlspecialchars(trim(implode(', ', array_filter([$shippingCity, $shippingState, $shippingZip, $shippingCountry]))));
                        ?><br>
                        <?php if(!empty($invoice['shipping_phone'] ?? $invoice['customer_phone'])): ?>
                            <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($invoice['shipping_phone'] ?? $invoice['customer_phone']); ?>
                        <?php endif; ?>
                    </address>
                </div>
            </div>
        </div>
        
        <!-- Invoice Items -->
        <div class="mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-uppercase small fw-bold" style="width: 5%;">#</th>
                        <th class="text-uppercase small fw-bold">Description</th>
                        <th class="text-uppercase small fw-bold text-center" style="width: 10%;">Qty</th>
                        <th class="text-uppercase small fw-bold text-end" style="width: 15%;">Unit Price</th>
                        <th class="text-uppercase small fw-bold text-end" style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    foreach($invoice['items'] as $index => $item): 
                        $itemTotal = $item['quantity'] * $item['price'];
                        $subtotal += $itemTotal;
                    ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <?php if(!empty($item['options'])): ?>
                                    <div class="text-muted small"><?php echo htmlspecialchars($item['options']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-end"><?php echo formatCurrency($item['price']); ?></td>
                            <td class="text-end"><?php echo formatCurrency($itemTotal); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <!-- Subtotal -->
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                        <td class="text-end"><?php echo formatCurrency($subtotal); ?></td>
                    </tr>
                    
                    <!-- Shipping -->
                    <?php if($invoice['shipping_fee'] > 0): ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Shipping:</td>
                            <td class="text-end"><?php echo formatCurrency($invoice['shipping_fee']); ?></td>
                        </tr>
                    <?php endif; ?>
                    
                    <!-- Tax -->
                    <?php if($invoice['tax_amount'] > 0): ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">
                                Tax (<?php echo $invoice['tax_rate']; ?>%):
                            </td>
                            <td class="text-end"><?php echo formatCurrency($invoice['tax_amount']); ?></td>
                        </tr>
                    <?php endif; ?>
                    
                    <!-- Total -->
                    <tr class="table-active">
                        <td colspan="4" class="text-end fw-bold">
                            <h5 class="mb-0">Total:</h5>
                        </td>
                        <td class="text-end">
                            <h5 class="mb-0"><?php echo formatCurrency($invoice['total_amount']); ?></h5>
                        </td>
                    </tr>
                    
                    <!-- Amount Paid -->
                    <?php if(($invoice['amount_paid'] ?? 0) > 0): ?>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">
                                <span class="text-success">Amount Paid:</span>
                            </td>
                            <td class="text-end text-success">
                                -<?php echo formatCurrency($invoice['amount_paid']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">
                                <h5 class="mb-0">Balance Due:</h5>
                            </td>
                            <td class="text-end">
                                <h5 class="mb-0">
                                    <?php echo formatCurrency($invoice['total_amount'] - $invoice['amount_paid']); ?>
                                </h5>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Payment Info -->
        <?php if(!empty($invoice['payment_method']) || !empty($invoice['payment_status'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 bg-light p-3">
                        <h5 class="text-uppercase small fw-bold mb-3">Payment Information</h5>
                        <div class="row">
                            <?php if(!empty($invoice['payment_method'])): ?>
                                <div class="col-md-3">
                                    <p class="mb-1">
                                        <strong>Payment Method:</strong><br>
                                        <?php echo ucwords(str_replace('_', ' ', $invoice['payment_method'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['payment_status'])): ?>
                                <div class="col-md-3">
                                    <p class="mb-1">
                                        <strong>Payment Status:</strong><br>
                                        <span class="badge bg-<?php 
                                            echo $invoice['payment_status'] === 'paid' ? 'success' : 
                                                ($invoice['payment_status'] === 'pending' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($invoice['payment_status']); ?>
                                        </span>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['payment_date'])): ?>
                                <div class="col-md-3">
                                    <p class="mb-1">
                                        <strong>Payment Date:</strong><br>
                                        <?php echo date('M d, Y', strtotime($invoice['payment_date'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['transaction_id'])): ?>
                                <div class="col-md-3">
                                    <p class="mb-1">
                                        <strong>Transaction ID:</strong><br>
                                        <?php echo $invoice['transaction_id']; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Order Info -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-light p-3">
                    <h5 class="text-uppercase small fw-bold mb-3">Order Information</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1">
                                <strong>Order Date:</strong><br>
                                <?php echo date('M d, Y h:i A', strtotime($invoice['created_at'])); ?>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1">
                                <strong>Order Status:</strong><br>
                                <span class="badge bg-<?php 
                                    echo $invoice['order_status'] === 'completed' ? 'success' : 
                                        ($invoice['order_status'] === 'processing' ? 'primary' : 
                                        ($invoice['order_status'] === 'shipped' ? 'info' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst($invoice['order_status']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1">
                                <strong>Shipping Method:</strong><br>
                                <?php echo !empty($invoice['shipping_method']) ? ucwords(str_replace('_', ' ', $invoice['shipping_method'])) : 'Standard Shipping'; ?>
                            </p>
                        </div>
                        <?php if(!empty($invoice['tracking_number'])): ?>
                            <div class="col-md-3">
                                <p class="mb-1">
                                    <strong>Tracking Number:</strong><br>
                                    <?php echo $invoice['tracking_number']; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-5 pt-4 border-top text-center text-muted small">
            <p class="mb-1">
                Thank you for your business. Please send payment within 30 days of receiving this invoice.
            </p>
            <?php if(!empty($invoice['notes'])): ?>
                <div class="mt-2">
                    <strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?>
                </div>
            <?php endif; ?>
            <p class="mb-0 mt-3">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </p>
        </div>
        
        <!-- Print Button -->
        <div class="no-print mt-4 text-center">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Print Invoice
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=invoice" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Invoices
            </a>
        </div>
    </div>
    
    <script>
    // Auto-print when the page loads
    window.onload = function() {
        // Uncomment the line below to auto-print the invoice when the page loads
        // window.print();
    };
    </script>
</body>
</html>
