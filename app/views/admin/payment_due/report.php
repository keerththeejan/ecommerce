<?php require_once APPROOT . '/views/admin/inc/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payment Due Report</h4>
                </div>
                <div class="card-body">
                    <form method="get" class="mb-4">
                        <input type="hidden" name="controller" value="paymentdue">
                        <input type="hidden" name="action" value="report">
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="<?php echo $data['start_date']; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="<?php echo $data['end_date']; ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="<?php echo URLROOT; ?>/paymentdue/export?start_date=<?php echo $data['start_date']; ?>&end_date=<?php echo $data['end_date']; ?>" 
                                   class="btn btn-success">
                                    <i class="fas fa-file-export me-1"></i> Export
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="reportTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end">Paid Amount</th>
                                    <th class="text-end">Due Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['report'])): ?>
                                    <?php 
                                    $totalAmount = 0;
                                    $totalPaid = 0;
                                    $totalDue = 0;
                                    ?>
                                    
                                    <?php foreach ($data['report'] as $index => $item): ?>
                                        <?php 
                                        $totalAmount += $item->total_amount;
                                        $totalPaid += $item->paid_amount;
                                        $totalDue += $item->due_amount;
                                        ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $item->invoice_no; ?></td>
                                            <td><?php echo $item->supplier_name; ?></td>
                                            <td><?php echo date('d M Y', strtotime($item->purchase_date)); ?></td>
                                            <td class="text-end"><?php echo formatPrice($item->total_amount); ?></td>
                                            <td class="text-end"><?php echo formatPrice($item->paid_amount); ?></td>
                                            <td class="text-end fw-bold"><?php echo formatPrice($item->due_amount); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $item->payment_status === 'paid' ? 'success' : 
                                                        ($item->payment_status === 'partial' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($item->payment_status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d M Y', strtotime($item->due_date)); ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/paymentdue/view/<?php echo $item->id; ?>" 
                                                   class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                    <tr class="table-active fw-bold">
                                        <td colspan="4" class="text-end">TOTAL:</td>
                                        <td class="text-end"><?php echo formatPrice($totalAmount); ?></td>
                                        <td class="text-end"><?php echo formatPrice($totalPaid); ?></td>
                                        <td class="text-end"><?php echo formatPrice($totalDue); ?></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No records found for the selected period.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?php echo URLROOT; ?>/paymentdue" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#reportTable').DataTable({
        responsive: true,
        order: [[3, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
    // Set default date range (current month)
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    // Format dates as YYYY-MM-DD
    function formatDate(date) {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        const year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
    
    // Set default dates if not set
    if (!$('#start_date').val()) {
        $('#start_date').val(formatDate(firstDay));
    }
    if (!$('#end_date').val()) {
        $('#end_date').val(formatDate(lastDay));
    }
});
</script>

<?php require_once APPROOT . '/views/admin/inc/footer.php'; ?>
