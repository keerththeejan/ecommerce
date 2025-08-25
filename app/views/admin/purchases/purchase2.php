<?php
// Expect: $title, $suppliers, $products
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$supplierId = $formData['supplier_id'] ?? '';
// Optional product prefill (when coming from purchase3/banner action)
$prefillProductId = isset($_GET['prefill_product_id']) ? (int)$_GET['prefill_product_id'] : 0;
// One-time submit token to prevent duplicate purchases
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
try { $submitToken = bin2hex(random_bytes(16)); } catch (Exception $e) { $submitToken = bin2hex(openssl_random_pseudo_bytes(16)); }
$_SESSION['purchase_submit_token'] = $submitToken;
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><?php echo htmlspecialchars($title ?? 'Add new purchase'); ?></h1>
    <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-secondary">Back</a>
  </div>

  <?php flash('error'); ?>
  <?php flash('success'); ?>

  <form id="purchaseForm" enctype="multipart/form-data" method="POST" action="<?php echo BASE_URL; ?>?controller=purchase&action=store" onsubmit="submitForm(event)">
    <input type="hidden" name="submit_token" value="<?php echo htmlspecialchars($submitToken); ?>">
    <?php if (!empty($prefillProductId)): ?>
      <input type="hidden" name="prefill_product_id" id="prefill_product_id" value="<?php echo (int)$prefillProductId; ?>">
      <input type="hidden" name="is_return" id="is_return" value="1">
    <?php endif; ?>
    <div class="row">
      <!-- Left column -->
      <div class="col-12">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Supplier <span class="text-danger">*</span> <i class="fas fa-info-circle" title="Select supplier for this purchase"></i></label>
                <select class="form-select" id="supplier_id" name="supplier_id" required>
                  <option value="">Please Select</option>
                  <?php foreach ($suppliers as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo ($supplierId == $s['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Reference No <i class="fas fa-info-circle" title="Auto generated"></i></label>
                <input type="text" class="form-control" id="reference_no" value="" placeholder="Auto" disabled>
              </div>

              <div class="col-md-6">
                <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="pending">Please Select</option>
                  <option value="received">Received</option>
                  <option value="pending">Pending</option>
                  <option value="ordered">Ordered</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Business Location <i class="fas fa-info-circle" title="Optional"></i></label>
                <input type="text" class="form-control" name="business_location" placeholder="Location (optional)">
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="update_stock" name="update_stock" checked>
                  <label class="form-check-label" for="update_stock">
                    Update stock?
                  </label>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="4" placeholder="Purchase description..."></textarea>
              </div>
              
       
          <label class="card-header">Purchase document</label>
          <div class="card-body">
            <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
            <small class="text-muted d-block mt-2">Max file size: 5MB</small>
          </div>
       
              
            </div>
          </div>
        </div>

        <!-- Products section (moved inside left column for proper alignment) -->

        <div class="card shadow-sm">
          <div class="card-header"><strong>Products</strong></div>
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
              <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-import me-1"></i> Import Products</button>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input id="product-search" type="text" class="form-control" placeholder="Enter Product name / SKU / Scan bar code">
              </div>
              <div id="product-suggestions" class="list-group" style="display:none; max-height: 240px; overflow:auto; width: 100%"></div>
              <a href="#" class="ms-auto small text-primary"><i class="fas fa-plus me-1"></i>Add new product</a>
            </div>

            <div class="table-responsive">
              <style>
                /* Narrow price columns without changing font size */
                .price-col { padding-left: .25rem !important; padding-right: .25rem !important; white-space: nowrap; }
                .price-col.text-end { text-align: right !important; }
                /* Narrow quantity column without changing font size */
                .qty-col { padding-left: .25rem !important; padding-right: .25rem !important; }
                .qty-col input { max-width: 72px; }
              </style>
              <table class="table table-bordered align-middle">
                <thead class="table-success">
                  <tr>
                    <th style="width: 3%">#</th>
                    <th style="width: 18%">Product Name</th>
                    <th class="price-col text-end" style="width: 6%">Buying</th>
                    <th class="price-col text-end" style="width: 6%">Incl. Tax</th>
                    <th class="price-col text-end" style="width: 6%">Sales</th>
                    <th class="price-col text-end" style="width: 6%">Wholesale</th>
                    <th class="qty-col" style="width: 7%">Purchase Qty</th>
                    <th style="width: 10%">Unit Cost</th>
                    <th style="width: 7%">Discount %</th>
                    <th style="width: 7%">Cost (After Disc)</th>
                    <th style="width: 7%">Line Total</th>
                    <th style="width: 8%">Profit Margin %</th>
                    <th style="width: 10%">Unit Selling (Inc. tax)</th>
                    <th style="width: 3%"><i class="fas fa-trash"></i></th>
                  </tr>
                </thead>
                <tbody id="product-rows">
                  <tr><td colspan="14" class="text-center text-muted py-4">Select a supplier to load products.</td></tr>
                </tbody>
              </table>
            </div>

            <hr>
            <div class="row">
              <div class="col-6"></div>
              <div class="col-6">
                <div class="d-flex justify-content-end small">
                  <div class="me-4"><strong>Total Items:</strong> <span id="total-items">0.00</span></div>
                  <div><strong>Net Total Amount:</strong> <span id="net-total"><?php echo CURRENCY_SYMBOL; ?>0.00</span></div>
                </div>
              </div>
            </div>

           
          </div>
        </div>

        <!-- Add payment (moved inside left column for proper alignment) -->
        <div class="card shadow-sm mt-4">
          <div class="card-header"><strong>Add payment</strong></div>
          <div class="card-body">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">Advance Balance:</label>
                <div class="form-control-plaintext">0</div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Amount*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                  <input id="pay-amount" type="number" step="0.01" min="0" class="form-control" name="payment[amount]" value="0.00">
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Paid on*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="far fa-calendar"></i></span>
                  <input id="paid-on" type="text" class="form-control bg-light" name="payment[paid_on]" readonly>
                </div>
              </div>
            </div>

            <div class="row g-3 mt-2">
              <div class="col-md-3">
                <label class="form-label">Payment Method*</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                  <select id="payment-method" class="form-select" name="payment[method]">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="cheque">Cheque</option>
                  </select>
                </div>
              </div>
              <div class="col-md-9">
                <label class="form-label">Payment note</label>
                <textarea class="form-control" name="payment[note]" rows="3"></textarea>
              </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
              <div></div>
              <div class="text-end">Payment due: <strong id="payment-due">0.00</strong></div>
            </div>

            <div class="text-center mt-3">
              <button id="saveBtn" type="submit" class="btn btn-primary px-5">Save</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>

<script>
const CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';
const BASE_URL = '<?php echo BASE_URL; ?>';
const PREFILL_PRODUCT_ID = <?php echo (int)($prefillProductId ?: 0); ?>;

function currency(num) { return CURRENCY_SYMBOL + (parseFloat(num||0).toFixed(2)); }

async function loadProducts(supplierId) {
  const tbody = document.getElementById('product-rows');
  if (!supplierId) { tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted py-4">Select a supplier to load products.</td></tr>'; updateTotals(); return; }
  tbody.innerHTML = '<tr><td colspan="14" class="text-center py-4"><div class="spinner-border text-primary"></div><span class="ms-2">Loading...</span></td></tr>';
  try {
    const resp = await fetch(`${BASE_URL}?controller=purchase&action=getProductsBySupplier&supplier_id=${supplierId}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    const data = await resp.json();
    if (!data.success || !Array.isArray(data.products) || data.products.length === 0) {
      tbody.innerHTML = `<tr><td colspan=14 class="text-center text-muted py-4">${data.message || 'No products found for this supplier.'}</td></tr>`;
      updateTotals();
      return;
    }
    let html = '';
    let idx = 1;
    data.products.forEach(p => {
      const unit = parseFloat(p.price || 0); // Buying Price
      const unitStr = unit.toFixed(2);
      const sale = parseFloat(p.sale_price || 0); // Including Tax Price
      const price2 = parseFloat(p.price2 || 0);   // Sales Price
      const price3 = parseFloat(p.price3 || 0);   // Wholesale Price (SP)
      // Prefer selling init: sale_price > price2 > price3 > buying price
      const sellInit = (sale > 0 ? sale : (price2 > 0 ? price2 : (price3 > 0 ? price3 : unit)));
      const sellInitStr = parseFloat(sellInit || 0).toFixed(2);
      const discount = 0;
      const beforeTax = unit; // placeholder: same as unit until discount/tax logic added
      const lineTotal = beforeTax * 1; // qty default 1
      html += `
        <tr data-product-id="${p.id}">
          <td class="row-index">${idx++}</td>
          <td>
            <strong>${p.name || 'Unnamed'}</strong>
            <div class="small text-muted">${p.code ? ('SKU: ' + p.code) : ''}</div>
            <input type="hidden" name="items[${p.id}][product_id]" value="${p.id}">
          </td>
          <td class="price-col text-end text-nowrap">${currency(unit)}</td>
          <td class="price-col text-end text-nowrap">${sale > 0 ? currency(sale) : '-'}</td>
          <td class="price-col text-end text-nowrap">${price2 > 0 ? currency(price2) : '-'}</td>
          <td class="price-col text-end text-nowrap">${price3 > 0 ? currency(price3) : '-'}</td>
          <td class="qty-col">
            <input type="number" min="1" class="form-control form-control-sm qty-input" name="items[${p.id}][quantity]" value="1" required>
          </td>
          <td>
            <div class="input-group input-group-sm">
              <span class="input-group-text">${CURRENCY_SYMBOL}</span>
              <input type="number" step="0.01" min="0" class="form-control price-input" name="items[${p.id}][unit_price]" value="${unitStr}" required>
            </div>
          </td>
          <td>
            <div class="input-group input-group-sm">
              <input type="number" step="0.01" min="0" class="form-control discount-input" name="items[${p.id}][discount_percent]" value="${discount}">
              <span class="input-group-text">%</span>
            </div>
          </td>
          <td class="before-tax">${currency(beforeTax)}</td>
          <td class="row-total">${currency(lineTotal)}</td>
          <td class="margin-display">0.00%</td>
          <td>
            <div class="input-group input-group-sm">
              <span class="input-group-text">${CURRENCY_SYMBOL}</span>
              <input type="number" step="0.01" min="0" class="form-control sell-input" name="items[${p.id}][selling_price]" value="${sellInitStr}">
            </div>
          </td>
          <td class="text-center">
            <button type="button" class="btn btn-link text-danger p-0 remove-row" title="Remove">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>`;
    });
    tbody.innerHTML = html;
    bindRowEvents();
    updateTotals();
    indexProducts();
  } catch (e) {
    tbody.innerHTML = '<tr><td colspan="14" class="text-center text-danger py-4">Error loading products</td></tr>';
  }
}

function bindRowEvents() {
  document.querySelectorAll('#product-rows .price-input, #product-rows .qty-input, #product-rows .discount-input, #product-rows .sell-input').forEach(el => {
    el.addEventListener('input', updateTotals);
  });
  document.querySelectorAll('#product-rows .remove-row').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const tr = e.currentTarget.closest('tr');
      tr?.remove();
      renumberRows();
      updateTotals();
    });
  });
}

function updateTotals() {
  let grand = 0;
  let items = 0;
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    // Skip hidden rows (e.g., when a product filter is applied)
    if (tr.style.display === 'none') { return; }

    const unit = parseFloat(tr.querySelector('.price-input')?.value || 0);
    const qty = parseFloat(tr.querySelector('.qty-input')?.value || 0);
    const disc = parseFloat(tr.querySelector('.discount-input')?.value || 0);
    const unitAfterDiscount = unit - (unit * (disc/100));
    const beforeTaxCell = tr.querySelector('.before-tax');
    if (beforeTaxCell) beforeTaxCell.textContent = currency(unitAfterDiscount);
    const total = unitAfterDiscount * qty;
    grand += total;
    items += qty;
    const rowTotalCell = tr.querySelector('.row-total');
    if (rowTotalCell) rowTotalCell.textContent = currency(total);
    // Read selling price input and compute margin % automatically
    const sell = parseFloat(tr.querySelector('.sell-input')?.value || 0);
    const marginPct = unitAfterDiscount > 0 ? ((sell - unitAfterDiscount) / unitAfterDiscount) * 100 : 0;
    const marginCell = tr.querySelector('.margin-display');
    if (marginCell) marginCell.textContent = `${(isFinite(marginPct)?marginPct:0).toFixed(2)}%`;
  });
  const totalItemsEl = document.getElementById('total-items');
  if (totalItemsEl) totalItemsEl.textContent = items.toFixed(2);
  const netEl = document.getElementById('net-total');
  if (netEl) netEl.textContent = currency(grand);
}

function renumberRows() {
  let i = 1;
  document.querySelectorAll('#product-rows tr[data-product-id] .row-index').forEach(cell => cell.textContent = i++);
}

// Initialize paid-on field with current datetime (readonly display)
(function initPaidOn(){
  const pad = (n) => (n<10? '0'+n : n);
  const d = new Date();
  const fmt = pad(d.getMonth()+1) + '/' + pad(d.getDate()) + '/' + d.getFullYear() + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
  const el = document.getElementById('paid-on');
  if (el) el.value = fmt;
})();

// Update Payment due whenever totals or amount change
function updatePaymentDue(){
  const netText = document.getElementById('net-total')?.textContent || '<?php echo CURRENCY_SYMBOL; ?>0.00';
  const net = parseFloat((netText.replace('<?php echo CURRENCY_SYMBOL; ?>','')||'0')) || 0;
  const amt = parseFloat(document.getElementById('pay-amount')?.value || 0);
  const due = Math.max(net - amt, 0);
  const dueEl = document.getElementById('payment-due');
  if (dueEl) dueEl.textContent = currency(due).replace('<?php echo CURRENCY_SYMBOL; ?>','');
}

// Hook into existing totals update
const _origUpdateTotals = updateTotals;
updateTotals = function(){
  _origUpdateTotals();
  updatePaymentDue();
}

document.getElementById('pay-amount')?.addEventListener('input', updatePaymentDue);

let __submitting = false;
async function submitForm(ev) {
  ev.preventDefault();
  if (__submitting) { return; }
  __submitting = true;
  const form = ev.target;
  const fd = new FormData(form);
  const saveBtn = document.getElementById('saveBtn');
  if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving...'; }
  try {
    // Basic client-side validation
    const supplier = fd.get('supplier_id');
    if (!supplier) {
      alert('Please select a supplier');
      return;
    }
    const anyItem = Array.from(fd.keys()).some(k => k.startsWith('items['));
    if (!anyItem) {
      alert('Please add at least one product item');
      return;
    }

    const res = await fetch(BASE_URL + '?controller=purchase&action=store', {
      method: 'POST',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });
    let out = null;
    const text = await res.text();
    try { out = JSON.parse(text); } catch (_) { out = null; }
    if (out && out.success) {
      alert('Purchase saved successfully');
      if (PREFILL_PRODUCT_ID > 0) {
        window.location.href = BASE_URL + `?controller=ListPurchaseController&highlight_product_id=${PREFILL_PRODUCT_ID}`;
      } else {
        window.location.href = BASE_URL + '?controller=purchase&action=index';
      }
    } else {
      // If server returned HTML (non-AJAX path), fallback to normal form submit
      if (!out) {
        form.submit();
        return;
      }
      alert((out && out.message) || 'Failed to save');
    }
  } catch (e) {
    alert('Error submitting form');
  } finally {
    // Re-enable only if we are not navigating away due to success
    __submitting = false;
    if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const supplierSel = document.getElementById('supplier_id');
  supplierSel.addEventListener('change', () => loadProducts(supplierSel.value));
  if (supplierSel.value) loadProducts(supplierSel.value);
  // Generate a display-only reference number to match UI; actual will be generated server-side
  const ref = document.getElementById('reference_no');
  if (ref) {
    const rand = Math.random().toString(36).substring(2,6).toUpperCase();
    const d = new Date();
    const ymd = d.getFullYear().toString() + String(d.getMonth()+1).padStart(2,'0') + String(d.getDate()).padStart(2,'0');
    ref.value = `PO-${ymd}-${rand}`;
  }
  // Search wiring
  const search = document.getElementById('product-search');
  const suggBox = document.getElementById('product-suggestions');
  search?.addEventListener('input', (e) => {
    const q = (e.target.value || '').trim().toLowerCase();
    if (!q){
      if (suggBox){ suggBox.style.display = 'none'; suggBox.innerHTML=''; }
      clearTableFilter();
      return;
    }
    if (!productsIndex.length) indexProducts();
    const matches = productsIndex.filter(p =>
      (p.name && p.name.toLowerCase().includes(q)) ||
      (p.sku && p.sku.toLowerCase().includes(q))
    ).slice(0, 20);
    showSuggestions(matches);
  });
  // Hide suggestions on outside click
  document.addEventListener('click', (ev) => {
    if (!suggBox) return;
    const target = ev.target;
    if (target.id === 'product-search' || suggBox.contains(target)) return;
    suggBox.style.display = 'none';
  });
});

// In-memory index of products for quick search
let productsIndex = [];

function indexProducts(){
  productsIndex = [];
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    const id = tr.getAttribute('data-product-id');
    const name = tr.querySelector('strong')?.textContent.trim() || '';
    const skuText = tr.querySelector('.small.text-muted')?.textContent || '';
    const sku = skuText.replace('SKU:', '').trim();
    productsIndex.push({ id, name, sku });
  });
}

function showSuggestions(matches){
  const box = document.getElementById('product-suggestions');
  if (!box) return;
  if (!matches.length){ box.style.display = 'none'; box.innerHTML=''; return; }
  box.innerHTML = matches.map(m =>
    `<a href="#" class="list-group-item list-group-item-action" data-id="${m.id}" data-name="${m.name}">${m.name}${m.sku?` <small class="text-muted">(${m.sku})</small>`:''}</a>`
  ).join('');
  box.style.display = 'block';
  box.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      const id = a.getAttribute('data-id');
      const nm = a.getAttribute('data-name') || '';
      applyTableFilter(id);
      const inp = document.getElementById('product-search');
      if (inp) inp.value = nm;
      box.style.display = 'none';
    });
  });
}

function clearTableFilter(){
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => tr.style.display = '');
  renumberRows();
  updateTotals();
}

function applyTableFilter(productId){
  let any = false;
  document.querySelectorAll('#product-rows tr[data-product-id]').forEach(tr => {
    if (tr.getAttribute('data-product-id') === String(productId)){
      tr.style.display = '';
      any = true;
    } else {
      tr.style.display = 'none';
    }
  });
  if (!any){
    // if not found (e.g., table not loaded yet), do nothing
    return;
  }
  renumberRows();
  updateTotals();
}
</script>
