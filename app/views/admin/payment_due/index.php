<?php 
// Define APPROOT if not defined
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__DIR__))));
}
require_once APPROOT . '/app/views/admin/layouts/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Customer Payment Dues</h4>
                    <div class="d-flex gap-2">
                        <a href="<?php echo URLROOT; ?>/paymentdue/report" class="btn btn-info btn-sm">
                            <i class="fas fa-file-export me-1"></i> Generate Report
                        </a>
                        <a href="<?php echo URLROOT; ?>/paymentdue/clearAllDues" 
                           class="btn btn-warning btn-sm" 
                           onclick="return confirm('Are you sure you want to clear ALL payment dues? This action cannot be undone.')">
                            <i class="fas fa-check-double me-1"></i> Clear All Dues
                        </a>
                        <a href="<?php echo URLROOT; ?>/paymentdue?due_only=1" 
                           class="btn btn-<?php echo (isset($_GET['due_only']) && $_GET['due_only'] == 1) ? 'primary' : 'outline-primary'; ?> btn-sm" 
                           title="Show only customers with dues">
                            <i class="fas fa-exclamation-triangle me-1"></i> Show Due Customers Only
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php flash('payment_message'); ?>
                    <?php flash('payment_error', null, 'alert alert-danger'); ?>
                    
                    <!-- Customer Selection Form with Search -->
                    <div class="mb-3">
                        <form action="" method="get" class="row g-3" id="customerSearchForm">
                            <input type="hidden" name="controller" value="PaymentDue">
                            <input type="hidden" name="action" value="index">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" 
                                           id="customerSearchInput" 
                                           class="form-control" 
                                           placeholder="Search customer by name or email..." 
                                           onkeyup="filterCustomers()"
                                           autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                                <select name="customer_id" 
                                        id="customerSelect" 
                                        class="form-select mt-2" 
                                        size="5" 
                                        onchange="this.form.submit()"
                                        style="display: none;">
                                    <option value="">-- Select a Customer --</option>
                                    <?php foreach($data['customers'] as $customer): 
                                        $customerText = htmlspecialchars($customer['name'] . ' (' . $customer['email'] . ')');
                                        $isSelected = (isset($_GET['customer_id']) && $_GET['customer_id'] == $customer['id']);
                                    ?>
                                        <option value="<?php echo $customer['id']; ?>" 
                                                data-search="<?php echo strtolower($customerText); ?>"
                                                <?php echo $isSelected ? 'selected' : ''; ?>>
                                            <?php echo $customerText; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($_GET['customer_id']) && !empty($_GET['customer_id'])): ?>
                                    <div class="mt-2">
                                        <a href="<?php echo URLROOT; ?>/paymentdue" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-times"></i> Clear Filter
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                        
                        <script>
                        function filterCustomers() {
                            const input = document.getElementById('customerSearchInput');
                            const filter = input.value.toLowerCase();
                            const select = document.getElementById('customerSelect');
                            const options = select.getElementsByTagName('option');
                            
                            // Show the select element when user starts typing
                            if (filter.length > 0) {
                                select.style.display = 'block';
                            } else {
                                select.style.display = 'none';
                            }
                            
                            for (let i = 0; i < options.length; i++) {
                                const option = options[i];
                                const text = option.textContent || option.innerText;
                                const searchText = option.getAttribute('data-search') || text.toLowerCase();
                                
                                if (text === '-- Select a Customer --') {
                                    option.style.display = 'block';
                                    continue;
                                }
                                
                                if (searchText.includes(filter)) {
                                    option.style.display = 'block';
                                } else {
                                    option.style.display = 'none';
                                }
                            }
                        }
                        
                        function clearSearch() {
                            // Clear the search input
                            const searchInput = document.getElementById('customerSearchInput');
                            searchInput.value = '';
                            
                            // Hide the dropdown
                            const select = document.getElementById('customerSelect');
                            select.style.display = 'none';
                            
                            // Reset the form and submit to clear filters
                            const form = document.getElementById('customerSearchForm');
                            const customerIdInput = form.querySelector('select[name="customer_id"]');
                            customerIdInput.value = '';
                            
                            // Remove customer_id from URL parameters
                            const url = new URL(window.location.href);
                            url.searchParams.delete('customer_id');
                            
                            // Submit the form with cleared parameters
                            window.location.href = url.toString();
                        }
                        
                        // Initialize - show select if a customer is already selected
                        document.addEventListener('DOMContentLoaded', function() {
                            const select = document.getElementById('customerSelect');
                            if (select.value !== '') {
                                select.style.display = 'block';
                            }
                        });
                        </script>
                        
                        <style>
                        #customerSelect {
                            width: 100%;
                            max-height: 200px;
                            overflow-y: auto;
                        }
                        #customerSelect option {
                            padding: 8px;
                            border-bottom: 1px solid #eee;
                        }
                        #customerSelect option:last-child {
                            border-bottom: none;
                        }
                        </style>
                    </div>
                    
                    <!-- Hidden search field for compatibility with existing code -->
                    <input type="hidden" name="search" id="customerSearch" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="paymentDuesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['purchases'])): ?>
                                    <?php foreach ($data['purchases'] as $index => $purchase): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $purchase->invoice_no; ?></td>
                                            <td><?php echo $purchase->supplier_name; ?></td>
                                            <td><?php echo date('d M Y', strtotime($purchase->purchase_date)); ?></td>
                                            <td class="text-end"><?php echo formatPrice($purchase->total_amount); ?></td>
                                            <td class="text-end"><?php echo formatPrice($purchase->paid_amount); ?></td>
                                            <td class="text-end fw-bold"><?php echo formatPrice($purchase->due_amount); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $purchase->payment_status === 'paid' ? 'success' : 
                                                        ($purchase->payment_status === 'partial' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($purchase->payment_status); ?>
                                                </span>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="<?php echo URLROOT; ?>/paymentdue/view/<?php echo $purchase->id; ?>" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($purchase->due_amount > 0): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success make-payment" 
                                                            data-id="<?php echo $purchase->id; ?>"
                                                            data-due="<?php echo $purchase->due_amount; ?>"
                                                            title="Record Payment">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                    <a href="<?php echo URLROOT; ?>/paymentdue/clearDue/<?php echo $purchase->id; ?>" 
                                                       class="btn btn-sm btn-warning clear-due" 
                                                       title="Clear Due"
                                                       onclick="return confirm('Are you sure you want to clear the full due amount of <?php echo number_format($purchase->due_amount, 2); ?>?')">
                                                        <i class="fas fa-check-circle"></i> Clear Due
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No payment dues found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>/paymentdue/updateStatus/" method="post" id="paymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount to Pay</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                        <div class="form-text">Due Amount: <span id="dueAmount">0.00</span></div>
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
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="payment_status" id="payment_status" value="partial">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/admin/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#paymentDuesTable').DataTable({
        responsive: true,
        order: [[3, 'desc']]
    });

    // Handle make payment button click
    $('.make-payment').on('click', function() {
        const purchaseId = $(this).data('id');
        const dueAmount = parseFloat($(this).data('due'));
        
        // Update form action URL
        $('#paymentForm').attr('action', `<?php echo URLROOT; ?>/paymentdue/updateStatus/${purchaseId}`);
        
        // Set max amount and update display
        $('#amount').attr('max', dueAmount).val(dueAmount);
        $('#dueAmount').text(dueAmount.toFixed(2));
        
        // Show modal
        $('#paymentModal').modal('show');
    });

    // Handle amount input change
    $('#amount').on('change', function() {
        const amount = parseFloat($(this).val()) || 0;
        const dueAmount = parseFloat($('#dueAmount').text());
        
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
