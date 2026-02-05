<?php 
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(dirname(__DIR__))));
}
require_once APPROOT . '/app/views/admin/layouts/header.php'; 
?>

<style>
/* Payment Due index – trending responsive */
.payment-due-admin .card-body { padding: 1rem; }
@media (min-width: 768px) { .payment-due-admin .card-body { padding: 1.25rem; } }
.payment-due-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.payment-due-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.payment-due-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.75rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.payment-due-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }
.payment-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
@media (max-width: 575.98px) { .payment-due-table-scroll { max-height: 55vh; } }
@media (min-width: 576px) and (max-width: 991.98px) { .payment-due-table-scroll { max-height: 60vh; } }
@media (min-width: 992px) { .payment-due-table-scroll { max-height: 70vh; } }
@media (max-width: 575.98px) {
  .payment-due-admin #paymentDuesTable thead,
  .payment-due-admin #customerDuesTable thead { display: none; }
  .payment-due-admin #paymentDuesTable tbody tr,
  .payment-due-admin #customerDuesTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  .payment-due-admin #paymentDuesTable tbody td,
  .payment-due-admin #customerDuesTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  .payment-due-admin #paymentDuesTable tbody td:last-child,
  .payment-due-admin #customerDuesTable tbody td:last-child { border-bottom: 0; }
  .payment-due-admin #paymentDuesTable tbody td::before,
  .payment-due-admin #customerDuesTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  .payment-due-admin .payment-actions { width: 100%; justify-content: flex-end; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 payment-due-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row flex-wrap justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
                    <div>
                        <h4 class="card-title mb-1 mb-sm-0 h5">Due Details</h4>
                        <nav aria-label="breadcrumb" class="mb-0">
                            <ol class="breadcrumb mb-0 opacity-90 small" style="--bs-breadcrumb-divider: '›';">
                                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>?controller=home&action=admin" class="text-white">Dashboard</a></li>
                                <li class="breadcrumb-item text-white active">Payment Due</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo URLROOT; ?>/paymentdue/report" class="btn btn-light btn-sm">
                            <i class="fas fa-file-export me-1"></i> Generate Report
                        </a>
                        <a href="<?php echo URLROOT; ?>/paymentdue/clearAllDues" 
                           class="btn btn-warning btn-sm" 
                           onclick="return confirm('Are you sure you want to clear ALL payment dues? This action cannot be undone.')">
                            <i class="fas fa-check-double me-1"></i> Clear All Dues
                        </a>
                        <a href="<?php echo URLROOT; ?>/paymentdue?due_only=1" 
                           class="btn btn-<?php echo (isset($_GET['due_only']) && $_GET['due_only'] == 1) ? 'primary' : 'outline-light'; ?> btn-sm" 
                           title="Show only customers with dues">
                            <i class="fas fa-exclamation-triangle me-1"></i> Show Due
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php flash('payment_message'); ?>
                    <?php flash('payment_error', null, 'alert alert-danger'); ?>

                    <!-- Due Type Filter with Custom Styled Checkboxes -->
                    <div class="mb-4 p-2 p-sm-3 border rounded-3" style="background-color: #f8f9fa; border-color: #e9ecef !important;">
                        <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-4">
                            <div class="form-check form-switch p-0 m-0 me-2">
                                <div class="d-flex align-items-center p-2 px-3" style="border-right: 1px solid #dee2e6; height: 100%;">
                                    <input class="form-check-input me-2" type="checkbox" role="switch" id="paymentDueCheck" name="payment_due" value="1" checked style="width: 2.8em; height: 1.4em;">
                                    <label class="form-check-label fw-bold d-flex align-items-center m-0" for="paymentDueCheck" style="color: #0d6efd;">
                                        <i class="fas fa-money-bill-wave me-2"></i> Payment Due
                                    </label>
                                </div>
                            </div>
                            <div class="form-check form-switch p-2 px-3 m-0">
                                <div class="d-flex align-items-center">
                                    <input class="form-check-input me-2" type="checkbox" role="switch" id="customerDueCheck" name="customer_due" value="1" style="width: 2.8em; height: 1.4em;">
                                    <label class="form-check-label fw-bold d-flex align-items-center" for="customerDueCheck" style="color: #fd7e14;">
                                        <i class="fas fa-users me-2"></i> Customer Due
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        /* Custom switch styling */
                        .form-switch .form-check-input {
                            transition: all 0.3s ease;
                            border: 1px solid #dee2e6;
                            /* Prevent negative margin from pushing switch outside left border */
                            margin-left: 0 !important;
                        }
                        .form-switch .form-check-input:checked {
                            background-color: #0d6efd;
                            border-color: #0d6efd;
                        }
                        .form-switch .form-check-input:focus {
                            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
                        }
                        .form-check-label {
                            cursor: pointer;
                            transition: all 0.2s ease;
                            padding: 0.25rem 0;
                        }
                        .form-check-label:hover {
                            opacity: 0.9;
                            transform: translateY(-1px);
                        }
                        /* Ensure the toggle group has slight left padding to align nicely */
                        .due-type-toggle .form-check.form-switch:first-child > div {
                            padding-left: 0.25rem;
                        }
                    </style>
                    
                    <!-- Hidden search field for compatibility with existing code -->
                    <input type="hidden" name="search" id="customerSearch" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    
                    <!-- Payment Due Section -->
                    <div id="paymentDueSection" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Payment Due Details</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                <i class="fas fa-plus me-1"></i> ADD
                            </button>
                        </div>
                    </div>
                    
                    <!-- Customer Due Section -->
                    <div id="customerDueSection" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 d-flex align-items-center"><i class="fas fa-user-clock me-2 text-warning"></i> Customer Due Details</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerPaymentModal">
                                <i class="fas fa-plus me-1"></i> ADD
                            </button>
                        </div>
                    </div>
                    
                    <div class="payment-due-table-scroll table-responsive" id="paymentDueTable">
                        <table class="table table-striped table-hover align-middle mb-0" id="paymentDuesTable">
                            <thead class="table-light">
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
                                        <?php
                                        $pid = is_object($purchase) ? ($purchase->id ?? null) : ($purchase['id'] ?? null);
                                        $invoiceNo = is_object($purchase) ? ($purchase->invoice_no ?? '') : ($purchase['invoice_no'] ?? '');
                                        $supplierName = is_object($purchase) ? ($purchase->supplier_name ?? '') : ($purchase['supplier_name'] ?? '');
                                        $purchaseDate = is_object($purchase) ? ($purchase->purchase_date ?? null) : ($purchase['purchase_date'] ?? null);
                                        $totalAmount = is_object($purchase) ? ($purchase->total_amount ?? 0) : ($purchase['total_amount'] ?? 0);
                                        $paidAmount = is_object($purchase) ? ($purchase->paid_amount ?? 0) : ($purchase['paid_amount'] ?? 0);
                                        $dueAmount = is_object($purchase) ? ($purchase->due_amount ?? 0) : ($purchase['due_amount'] ?? 0);
                                        $paymentStatus = is_object($purchase) ? ($purchase->payment_status ?? 'unpaid') : ($purchase['payment_status'] ?? 'unpaid');
                                        ?>
                                        <tr>
                                            <td data-label="#"><?php echo $index + 1; ?></td>
                                            <td data-label="Invoice No"><?php echo htmlspecialchars($invoiceNo); ?></td>
                                            <td data-label="Supplier"><?php echo htmlspecialchars($supplierName); ?></td>
                                            <td data-label="Purchase Date"><?php echo $purchaseDate ? date('d M Y', strtotime($purchaseDate)) : ''; ?></td>
                                            <td data-label="Total Amount" class="text-end"><?php echo function_exists('formatPrice') ? formatPrice($totalAmount) : number_format((float)$totalAmount, 2); ?></td>
                                            <td data-label="Paid Amount" class="text-end"><?php echo function_exists('formatPrice') ? formatPrice($paidAmount) : number_format((float)$paidAmount, 2); ?></td>
                                            <td data-label="Due Amount" class="text-end fw-bold"><?php echo function_exists('formatPrice') ? formatPrice($dueAmount) : number_format((float)$dueAmount, 2); ?></td>
                                            <td data-label="Status">
                                                <span class="badge bg-<?php 
                                                    echo $paymentStatus === 'paid' ? 'success' : 
                                                        ($paymentStatus === 'partial' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($paymentStatus); ?>
                                                </span>
                                            </td>
                                            <td data-label="Actions" class="text-nowrap">
                                                <div class="payment-actions">
                                                    <a href="<?php echo URLROOT; ?>/paymentdue/view/<?php echo (int)$pid; ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($dueAmount > 0): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success make-payment" 
                                                                data-id="<?php echo (int)$pid; ?>"
                                                                data-due="<?php echo htmlspecialchars((string)$dueAmount); ?>"
                                                                title="Record Payment">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                        <a href="<?php echo URLROOT; ?>/paymentdue/clearDue/<?php echo (int)$pid; ?>" 
                                                           class="btn btn-sm btn-warning clear-due" 
                                                           title="Clear Due"
                                                           onclick="return confirm('Are you sure you want to clear the full due amount of <?php echo number_format((float)$dueAmount, 2); ?>?')">
                                                            <i class="fas fa-check-circle"></i> Clear Due
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
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

                    <!-- Customer Dues Table -->
                    <div class="payment-due-table-scroll table-responsive" id="customerDueTableWrapper" style="display: none;">
                        <table class="table table-striped table-hover align-middle mb-0" id="customerDuesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer Name</th>
                                    <th>Phone No</th>
                                    <th>Purchase Date</th>
                                    <th>Total</th>
                                    <th>Amount Paid</th>
                                    <th>Amount</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['customer_dues'])): ?>
                                    <?php foreach ($data['customer_dues'] as $index => $cd): ?>
                                        <tr>
                                            <td data-label="#"><?php echo $index + 1; ?></td>
                                            <td data-label="Invoice No"><?php echo htmlspecialchars($cd->invoice_no ?? ($cd['invoice_no'] ?? '')); ?></td>
                                            <td data-label="Customer Name"><?php echo htmlspecialchars($cd->customer_name ?? ($cd['customer_name'] ?? '')); ?></td>
                                            <td data-label="Phone No"><?php echo htmlspecialchars($cd->phone ?? ($cd['phone'] ?? '')); ?></td>
                                            <td data-label="Purchase Date"><?php 
                                                $pd = $cd->purchase_date ?? ($cd['purchase_date'] ?? '');
                                                echo $pd ? date('d M Y', strtotime($pd)) : '';
                                            ?></td>
                                            <td data-label="Total" class="text-end"><?php 
                                                $total = $cd->total_amount ?? ($cd['total_amount'] ?? 0);
                                                echo function_exists('formatPrice') ? formatPrice($total) : number_format((float)$total, 2);
                                            ?></td>
                                            <td data-label="Amount Paid" class="text-end"><?php 
                                                $paid = $cd->paid_amount ?? ($cd['paid_amount'] ?? 0);
                                                echo function_exists('formatPrice') ? formatPrice($paid) : number_format((float)$paid, 2);
                                            ?></td>
                                            <td data-label="Amount" class="text-end"><?php 
                                                $amount = $cd->amount ?? ($cd['amount'] ?? 0);
                                                echo function_exists('formatPrice') ? formatPrice($amount) : number_format((float)$amount, 2);
                                            ?></td>
                                            <td data-label="Due Amount" class="text-end fw-bold"><?php 
                                                $due = $cd->due_amount ?? ($cd['due_amount'] ?? 0);
                                                echo function_exists('formatPrice') ? formatPrice($due) : number_format((float)$due, 2);
                                            ?></td>
                                            <td data-label="Status">
                                                <?php $status = ($cd->payment_status ?? ($cd['payment_status'] ?? 'unpaid')); ?>
                                                <span class="badge bg-<?php 
                                                    echo $status === 'paid' ? 'success' : ($status === 'partial' ? 'warning' : 'danger');
                                                ?>"><?php echo ucfirst($status); ?></span>
                                            </td>
                                            <td data-label="Actions" class="text-nowrap">
                                                <?php $pid = $cd->id ?? ($cd['id'] ?? null); ?>
                                                <?php if ($pid): ?>
                                                    <div class="payment-actions">
                                                        <a href="<?php echo URLROOT; ?>/paymentdue/view/<?php echo $pid; ?>" class="btn btn-sm btn-primary" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if (($cd->due_amount ?? ($cd['due_amount'] ?? 0)) > 0): ?>
                                                            <button type="button" class="btn btn-sm btn-success make-payment" data-id="<?php echo $pid; ?>" data-due="<?php echo $cd->due_amount ?? ($cd['due_amount'] ?? 0); ?>" title="Record Payment">
                                                                <i class="fas fa-money-bill-wave"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">No customer dues found.</td>
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

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>/paymentdue/add" method="post">
                <div class="modal-body">
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                <option value="">Select Supplier</option>
                                <?php if (!empty($data['suppliers']) && is_array($data['suppliers'])): ?>
                                    <?php foreach ($data['suppliers'] as $supplier): ?>
                                        <?php if (is_object($supplier)): ?>
                                            <option value="<?php echo $supplier->id; ?>"><?php echo htmlspecialchars($supplier->name); ?></option>
                                        <?php elseif (is_array($supplier)): ?>
                                            <option value="<?php echo $supplier['id']; ?>"><?php echo htmlspecialchars($supplier['name'] ?? 'Unknown'); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No suppliers available</option>
                                <?php endif; ?>
                            </select>
                            <?php if (empty($data['suppliers'])): ?>
                                <div class="text-danger small mt-1">No suppliers found. Please add suppliers first.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="paid_amount" class="form-label">Paid Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="paid_amount" name="paid_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="due_amount" class="form-label">Due Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="due_amount" name="due_amount" step="0.01" min="0" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="payment_status" class="form-label">Status</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Customer Payment Modal -->
<div class="modal fade" id="addCustomerPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add Customer Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>/paymentdue/addCustomerDue" method="post" id="customerDueForm">
                <div class="modal-body">
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="cd_invoice_no" class="form-label">Invoice No</label>
                            <input type="text" class="form-control" id="cd_invoice_no" name="invoice_no" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cd_customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="cd_customer_name" name="customer_name" required>
                        </div>
                    </div>
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="cd_phone" class="form-label">Phone No</label>
                            <input type="text" class="form-control" id="cd_phone" name="phone">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="cd_purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="cd_purchase_date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="cd_payment_date" class="form-label">Payment Date</label>
                            <input type="date" class="form-control" id="cd_payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="cd_total_amount" class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="cd_total_amount" name="total_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cd_paid_amount" class="form-label">Amount Paid</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="cd_paid_amount" name="paid_amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cd_amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="cd_amount" name="amount" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cd_due_amount" class="form-label">Due Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="cd_due_amount" name="due_amount" step="0.01" min="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 g-md-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="cd_payment_status_select" class="form-label">Status</label>
                            <select class="form-select" id="cd_payment_status_select" name="payment_status" required>
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="cd_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="cd_notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
    </div>

<script>
// Calculate due amount when total or paid amount changes
$('#total_amount, #paid_amount').on('input', function() {
    const total = parseFloat($('#total_amount').val()) || 0;
    const paid = parseFloat($('#paid_amount').val()) || 0;
    const due = total - paid;
    
    $('#due_amount').val(due.toFixed(2));
    
    // Update status based on amounts
    if (due <= 0) {
        $('#payment_status').val('paid');
    } else if (paid > 0) {
        $('#payment_status').val('partial');
    } else {
        $('#payment_status').val('unpaid');
    }
});

// Initialize due amount on page load
$(document).ready(function() {
    $('#total_amount, #paid_amount').trigger('input');
});
</script>

<?php require_once APPROOT . '/app/views/admin/layouts/footer.php'; ?>

<script>
$(document).ready(function() {
    // Toggle Payment Due section
    function togglePaymentDueSection() {
        const isChecked = $('#paymentDueCheck').is(':checked');
        if (isChecked) {
            $('#paymentDueSection, #paymentDueTable').fadeIn();
        } else {
            $('#paymentDueSection, #paymentDueTable').fadeOut();
        }
    }

    // Toggle Customer Due section
    function toggleCustomerDueSection() {
        const isChecked = $('#customerDueCheck').is(':checked');
        if (isChecked) {
            $('#customerDueSection, #customerDueTableWrapper').fadeIn();
        } else {
            $('#customerDueSection, #customerDueTableWrapper').fadeOut();
        }
    }

    // Initialize toggle state (default to Payment Due if both off)
    if (!$('#paymentDueCheck').is(':checked') && !$('#customerDueCheck').is(':checked')) {
        $('#paymentDueCheck').prop('checked', true);
    }
    togglePaymentDueSection();
    toggleCustomerDueSection();
    
    // Handle switch toggle
    $('#paymentDueCheck').on('change', function() {
        if ($(this).is(':checked')) {
            // Make mutually exclusive
            $('#customerDueCheck').prop('checked', false);
        } else if (!$('#customerDueCheck').is(':checked')) {
            // Ensure at least one is on
            $(this).prop('checked', true);
        }
        togglePaymentDueSection();
        toggleCustomerDueSection();
    });
    $('#customerDueCheck').on('change', function() {
        if ($(this).is(':checked')) {
            // Make mutually exclusive
            $('#paymentDueCheck').prop('checked', false);
        } else if (!$('#paymentDueCheck').is(':checked')) {
            // Ensure at least one is on
            $(this).prop('checked', true);
        }
        togglePaymentDueSection();
        toggleCustomerDueSection();
    });

    // Initialize DataTables (responsive: false so our custom mobile card layout works)
    $('#paymentDuesTable').DataTable({
        responsive: false,
        order: [[3, 'desc']]
    });
    $('#customerDuesTable').DataTable({
        responsive: false,
        order: [[4, 'desc']]
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
            $('#paymentModal #payment_status').val('paid');
            $(this).val(dueAmount);
        } else if (amount > 0) {
            $('#paymentModal #payment_status').val('partial');
        } else {
            $('#paymentModal #payment_status').val('unpaid');
        }
    });

    // Customer Due modal auto-calc
    function updateCustomerDueStatus() {
        const total = parseFloat($('#cd_total_amount').val()) || 0;
        const paid = parseFloat($('#cd_paid_amount').val()) || 0;
        const due = Math.max(total - paid, 0);
        $('#cd_due_amount').val(due.toFixed(2));
        if (due <= 0 && total > 0) {
            $('#cd_payment_status_select').val('paid');
        } else if (paid > 0) {
            $('#cd_payment_status_select').val('partial');
        } else {
            $('#cd_payment_status_select').val('unpaid');
        }
    }
    $('#cd_total_amount, #cd_paid_amount').on('input', updateCustomerDueStatus);
    // Initialize on modal show
    $('#addCustomerPaymentModal').on('shown.bs.modal', function(){
        updateCustomerDueStatus();
    });
});
</script>
