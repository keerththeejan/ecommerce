<?php 
// Set page title
$pageTitle = 'Quick Order';

// Include customer layout header (consistent with other customer views)
require_once APP_PATH . 'views/customer/layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Quick Order</h4>
                </div>
                <div class="card-body">
                    <?php flash('login_required'); ?>
                    <?php flash('order_success'); ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=speed" method="post">
                        <?php if (isAdmin()): ?>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" class="form-select <?php echo (!empty($data['customer_id_error'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Customer</option>
                                <?php foreach ($data['customers'] as $customer): ?>
                                    <?php 
                                        $cust = is_array($customer) ? $customer : (array)$customer;
                                        $custId = $cust['id'] ?? '';
                                        $custName = trim(($cust['first_name'] ?? '') . ' ' . ($cust['last_name'] ?? ''));
                                    ?>
                                    <option value="<?php echo htmlspecialchars($custId); ?>" <?php echo ($data['customer_id'] == $custId) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($custName ?: ('Customer #' . $custId)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['customer_id_error']; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <div class="input-group">
                                <input type="text" id="product_name" name="product_name" list="productSuggestions"
                                       class="form-control <?php echo (!empty($data['product_name_error'])) ? 'is-invalid' : ''; ?>"
                                       value="<?php echo htmlspecialchars($data['product_name']); ?>" placeholder="Enter product name" autocomplete="off" autofocus>
                                <span class="input-group-text" id="availability-badge">
                                    <span class="badge bg-secondary" id="availability-text">-</span>
                                </span>
                                <datalist id="productSuggestions"></datalist>
                            </div>
                            <div class="invalid-feedback"><?php echo $data['product_name_error']; ?></div>
                            <div class="form-text">Enter the name of the product you want to order</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control <?php echo (!empty($data['quantity_error'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($data['quantity']); ?>" min="1">
                            <div class="invalid-feedback"><?php echo $data['quantity_error']; ?></div>
                        </div>
                        
                        <?php if (!empty($data['shipping_methods'])): ?>
                            <div class="mb-3">
                                <label for="shipping_id" class="form-label">Shipping Method</label>
                                <select name="shipping_id" class="form-select <?php echo (!empty($data['shipping_id_error'])) ? 'is-invalid' : ''; ?>">
                                    <option value="">Select Shipping Method</option>
                                    <?php foreach ($data['shipping_methods'] as $method): ?>
                                        <?php 
                                            $m = is_array($method) ? $method : (array)$method;
                                            $mid = $m['id'] ?? '';
                                            $mname = $m['name'] ?? 'Method';
                                            $mprice = isset($m['base_price']) ? (float)$m['base_price'] : 0;
                                            $selected = (isset($_POST['shipping_id']) && $_POST['shipping_id'] == $mid) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo htmlspecialchars($mid); ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars($mname . ' - $' . number_format($mprice, 2)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['shipping_id_error']; ?></span>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                No shipping methods available. Please contact support.
                                <input type="hidden" name="shipping_id" value="">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" 
                                    <?php echo ($data['payment_method'] == 'cod') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card"
                                    <?php echo ($data['payment_method'] == 'card') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="card">
                                    Credit/Debit Card
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-bolt me-2"></i>Place Order Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('product_name');
    const datalist = document.getElementById('productSuggestions');
    const availText = document.getElementById('availability-text');
    const cache = new Map(); // name -> {available, stock}

    function updateBadge(name) {
        const item = cache.get(name && name.trim());
        if (!item) {
            availText.textContent = '-';
            availText.className = 'badge bg-secondary';
            return;
        }
        if (item.available) {
            availText.textContent = 'Available';
            availText.className = 'badge bg-success';
        } else {
            availText.textContent = 'Unavailable';
            availText.className = 'badge bg-danger';
        }
    }

    let lastQuery = '';
    let inflight = null;
    async function fetchSuggestions(q = '') {
        try {
            if (inflight) { /* allow overlapping; browser will abort most recent on nav */ }
            const url = `<?php echo BASE_URL; ?>?controller=product&action=suggest&q=${encodeURIComponent(q)}&limit=15`;
            const res = await fetch(url, {credentials: 'same-origin'});
            if (!res.ok) return;
            const data = await res.json();
            if (!data || !data.success || !Array.isArray(data.data)) return;
            // Clear datalist
            datalist.innerHTML = '';
            data.data.forEach(row => {
                const name = row.name || '';
                if (!name) return;
                cache.set(name, {available: !!row.available, stock: Number(row.stock||0)});
                const opt = document.createElement('option');
                opt.value = name;
                opt.label = row.available ? `${name} — Available` : `${name} — Unavailable`;
                datalist.appendChild(opt);
            });
            // refresh badge for current value
            updateBadge(input.value);
        } catch (e) {
            // ignore network errors
        }
    }

    // Initial fetch on focus to show list
    input.addEventListener('focus', () => {
        if (datalist.options.length === 0) fetchSuggestions('');
    });
    // Fetch on input with debouncing
    let t = null;
    input.addEventListener('input', (e) => {
        const val = e.target.value || '';
        updateBadge(val);
        if (t) clearTimeout(t);
        t = setTimeout(() => {
            if (val !== lastQuery) {
                lastQuery = val;
                fetchSuggestions(val);
            }
        }, 150);
    });
    // Update badge on change as well (e.g., selecting from list)
    input.addEventListener('change', () => updateBadge(input.value));
});
</script>

<?php 
// Include customer layout footer
require_once APP_PATH . 'views/customer/layouts/footer.php'; 
?>
