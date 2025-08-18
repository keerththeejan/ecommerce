<?php require_once APPROOT . '/views/admin/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Payment Details - <?php echo $data['purchase']->invoice_no; ?></h4>
                    <div>
                        <a href="<?php echo URLROOT; ?>/paymentdue" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php flash('payment_message'); ?>
                    <?php flash('payment_error', null, 'alert alert-danger'); ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Purchase Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Invoice No:</th>
                                    <td><?php echo $data['purchase']->invoice_no; ?></td>
                                </tr>
                                <tr>
                                    <th>Supplier:</th>
                                    <td><?php echo $data['purchase']->supplier_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Purchase Date:</th>
                                    <td><?php echo date('d M Y', strtotime($data['purchase']->purchase_date)); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td class="fw-bold"><?php echo formatPrice($data['purchase']->total_amount); ?></td>
                                </tr>
                                <tr>
                                    <th>Paid Amount:</th>
                                    <td class="text-success fw-bold"><?php echo formatPrice($data['purchase']->paid_amount); ?></td>
                                </tr>
                                <tr>
                                    <th>Due Amount:</th>
                                    <td class="text-danger fw-bold"><?php echo formatPrice($data['purchase']->due_amount); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $data['purchase']->payment_status === 'paid' ? 'success' : 
                                                ($data['purchase']->payment_status === 'partial' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($data['purchase']->payment_status); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <?php if ($data['purchase']->due_amount > 0): ?>
                        <div class="col-md-6">
                            <h5>Record Payment</h5>
                            <form action="<?php echo URLROOT; ?>/paymentdue/updateStatus/<?php echo $data['purchase']->id; ?>" method="post">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount to Pay</label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           step="0.01" min="0.01" max="<?php echo $data['purchase']->due_amount; ?>" 
                                           value="<?php echo $data['purchase']->due_amount; ?>" required>
                                    <div class="form-text">Due Amount: <?php echo formatPrice($data['purchase']->due_amount); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="check">Check</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label">Transaction/Reference No</label>
                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                                </div>
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date" 
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                                </div>
                                <input type="hidden" name="payment_status" id="payment_status" value="paid">
                                <button type="submit" class="btn btn-primary">Record Payment</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h5>Payment History</h5>
                            <?php if (!empty($data['purchase']->payment_history)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Transaction ID</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['purchase']->payment_history as $index => $payment): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo date('d M Y', strtotime($payment->payment_date)); ?></td>
                                                    <td class="text-end"><?php echo formatPrice($payment->amount); ?></td>
                                                    <td><?php echo ucfirst(str_replace('_', ' ', $payment->payment_method)); ?></td>
                                                    <td><?php echo $payment->transaction_id; ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo $payment->status === 'paid' ? 'success' : 
                                                                ($payment->status === 'partial' ? 'warning' : 'danger'); 
                                                        ?>">
                                                            <?php echo ucfirst($payment->status); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $payment->notes; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">No payment history found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Update payment status based on amount
    $('#amount').on('change', function() {
        const amount = parseFloat($(this).val()) || 0;
        const dueAmount = parseFloat('<?php echo $data['purchase']->due_amount; ?>');
        
        if (amount >= dueAmount) {
            $('#payment_status').val('paid');
            $(this).val(dueAmount);
        } else if (amount > 0) {
            $('#payment_status').val('partial');
        } else {
            $('#payment_status').val('unpaid');
        }
    });
});
</script>

<?php require_once APPROOT . '/views/admin/inc/footer.php'; ?>
