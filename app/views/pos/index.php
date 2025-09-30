<?php
// Check if staff
if(!isStaff()) {
    redirect('user/login');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - E-Commerce Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Compact actions in Bill & Invoice */
        .checkout-actions .btn { min-width: 110px; }
        /* Subtotal divider */
        .cart-divider {
            border: 0;
            height: 4px;
            background-color: #198754; /* Bootstrap success green */
            opacity: 1;
            border-radius: 2px;
        }
        .product-item {
            cursor: pointer;
            transition: all 0.3s;
        }
        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 2px 0; /* ultra-compact vertical spacing */
        }
        .cart-container {
            height: calc(105vh - 420px); /* reduced to make Bill & Invoice shorter */
            overflow-y: auto;
        }
        /* Smaller labels inside Bill & Invoice cart */
        .bill-card .cart-item .item-name { font-size: 0.65rem !important; line-height: 1.03; margin: 0; }
        .bill-card .cart-item .price-qty { font-size: 0.6rem !important; margin: 0; }
        .bill-card .cart-item .item-total { font-size: 0.7rem !important; }
        .bill-card .cart-item .remove-item { font-size: 0.7rem !important; }
        /* Reduce divider spacing between items */
        .bill-card .cart-item + hr { margin: 2px 0; }
        /* Quantity controls centered between name and total */
        .bill-card .cart-item { gap: 8px; }
        .bill-card .cart-item .left { flex: 1 1 auto; min-width: 0; }
        .bill-card .cart-item .right { text-align: right; min-width: 72px; }
        .bill-card .cart-item .qty-controls { display: flex; justify-content: center; align-items: center; margin: 0 6px; flex: 0 0 auto; }
        .bill-card .cart-item .qty-controls .btn { padding: 0 6px; line-height: 1; }
        .bill-card .cart-item .qty-controls .qty-value { min-width: 22px; text-align: center; font-size: 0.6rem; }
        .category-filter {
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
        }
        .category-filter .btn {
            margin-right: 5px;
        }
        .product-grid {
            height: calc(102vh - 320px);
            overflow-y: auto;
        }
        /* Manual customer overlay */
        .bill-card { position: relative; border: 1px solid #000; }
        /* Products outer card border */
        .products-card { border: 1px solid #000; }
        .customer-overlay-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(1px);
            z-index: 10;
            display: none;
        }
        .customer-overlay-backdrop.show { display: block; }
        .customer-overlay-panel {
            max-width: 420px;
            margin: 40px auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.05);
        }
        /* ===== New POS UI additions ===== */
        .pos-toolbar { background:#fff; border:1px solid #e9ecef; border-radius:12px; padding:10px 12px; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
        .pos-toolbar .badge-soft{ background:#f4f6f9; border:1px solid #e9ecef; color:#333; padding:10px 12px; border-radius:10px; display:inline-flex; align-items:center; gap:8px; }
        .pos-toolbar .icon-btn{ width:38px; height:38px; border-radius:10px; background:#f4f6f9; border:1px solid #e9ecef; display:inline-flex; align-items:center; justify-content:center; color:#495057; }
        .pos-toolbar .icon-btn:hover{ background:#eef1f6; }
        /* Toolbar row inside navbar */
        .toolbar-row .badge-soft{ background:#f8f9fa; border:1px solid #e9ecef; color:#212529; padding:8px 10px; border-radius:10px; display:inline-flex; align-items:center; gap:8px; }
        .toolbar-row .icon-btn{ width:36px; height:36px; border-radius:10px; background:#f4f6f9; border:1px solid #e9ecef; color:#495057; display:inline-flex; align-items:center; justify-content:center; }
        .toolbar-row .icon-btn:hover{ background:#eef1f6; }
        .brands-pill{ background:linear-gradient(90deg,#3b82f6,#7c3aed); color:#fff; border-radius:10px; padding:8px 14px; display:inline-flex; align-items:center; gap:8px; font-weight:600; }
        .products-card .card-header{ border-bottom:0; }
        .product-tile{ border:1px dashed #e9ecef; border-radius:12px; background:#fff; padding:10px; text-align:center; height:120px; display:flex; flex-direction:column; justify-content:center; gap:6px; }
        .product-tile .thumb{ width:36px; height:36px; background:#f4f6f9; border-radius:8px; margin:0 auto; display:flex; align-items:center; justify-content:center; }
        .product-tile .name{ font-size:0.75rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .product-tile .meta{ font-size:0.68rem; color:#6c757d; }
        .cart-head{ background:#f4f6f9; border:1px solid #e9ecef; border-radius:10px; padding:6px 10px; font-size:0.8rem; font-weight:600; }
        .pos-bottom-bar{ position:sticky; bottom:0; background:#fff; border-top:1px solid #e9ecef; box-shadow:0 -6px 18px rgba(0,0,0,0.04); padding:10px 12px; z-index:5; border-radius:12px 12px 0 0; }
        .pos-bottom-bar .action-btn{ border-radius:10px; padding:8px 12px; display:inline-flex; align-items:center; gap:8px; }
        .pos-bottom-bar .summary{ font-weight:700; font-size:1.1rem; }
        /* Light ash navbar */
        .navbar-ash { background-color: #e0e0e0 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-ash">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>?controller=pos">POS System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>?controller=pos">POS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=report">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=session">Session</a>
                    </li>
                    <?php if(isAdmin()) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=home&action=admin">Admin</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="ms-auto d-flex align-items-center toolbar-row flex-nowrap gap-2">
                    <span class="badge-soft"><i class="fa-solid fa-location-dot"></i> Main Branch</span>
                    <span class="badge-soft"><i class="fa-regular fa-calendar"></i> <span id="posDate"></span></span>
                    <span class="badge-soft"><i class="fa-regular fa-clock"></i> <span id="posTime"></span></span>
                    <button class="icon-btn" title="Previous"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="icon-btn" title="Hold"><i class="fa-regular fa-hand"></i></button>
                    <button class="icon-btn" title="Save"><i class="fa-regular fa-floppy-disk"></i></button>
                    <button class="icon-btn" title="Undo"><i class="fa-solid fa-rotate-left"></i></button>
                    <button class="icon-btn" title="Settings"><i class="fa-solid fa-gear"></i></button>
                    <a href="<?php echo BASE_URL; ?>?controller=expense&action=create" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-circle-plus me-1"></i> Add Expense</a>
                    <div class="dropdown ms-2">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>">Store Front</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-3">
        <!-- Flash Messages -->
        <?php flash('pos_success'); ?>
        <?php flash('pos_error'); ?>
        <div class="row">
    <!-- Products Section (moved to the RIGHT on md+ using order-md-2) -->
    <div class="col-md-5 order-md-2">
        <div class="card products-card">
            <div class="card-header bg-white">
                <div class="row align-items-center g-2">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="searchProduct" class="form-control border-start-0" placeholder="Search products...">
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <span class="brands-pill"><i class="fa-solid fa-tags"></i> Products</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Category Filter -->
                <div class="category-filter mb-3">
                    <button class="btn btn-outline-primary active" data-category="all">All</button>
                    <?php foreach($categories as $category) : ?>
                        <button class="btn btn-outline-primary" data-category="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></button>
                    <?php endforeach; ?>
                </div>

    <!-- Card Payment Modal -->
    <div class="modal fade" id="cardPaymentModal" tabindex="-1" aria-labelledby="cardPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cardPaymentModalLabel">Card transaction details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="Card Number" maxlength="19">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card holder name</label>
                            <input type="text" class="form-control" id="cardHolder" placeholder="Card holder name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card Transaction No.</label>
                            <input type="text" class="form-control" id="cardTxnNo" placeholder="Card Transaction No.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Card Type</label>
                            <select class="form-select" id="cardType">
                                <option value="Visa">Visa</option>
                                <option value="MasterCard">MasterCard</option>
                                <option value="Amex">Amex</option>
                                <option value="Rupay">Rupay</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Month</label>
                            <input type="text" class="form-control" id="cardExpMonth" placeholder="Month">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" id="cardExpYear" placeholder="Year">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Security Code</label>
                            <input type="password" class="form-control" id="cardCvv" placeholder="Security Code">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="finalizeCardPayment">Finalize Payment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Post-Sale Actions Modal -->
    <div class="modal fade" id="postSaleModal" tabindex="-1" aria-labelledby="postSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="postSaleModalLabel">Sale Completed</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" id="postSalePrint"><i class="fa-solid fa-print me-1"></i> Print</button>
                        <button class="btn btn-outline-secondary" id="postSaleMail"><i class="fa-regular fa-envelope me-1"></i> Mail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <!-- Product Grid -->
                <div class="product-grid">
                    <div class="row" id="productContainer">
                        <?php foreach($products as $product) : ?>
                            <div class="col-md-3 mb-3 product-item"
                                 data-category="<?php echo $product['category_id']; ?>"
                                 data-id="<?php echo $product['id']; ?>"
                                 data-name="<?php echo $product['name']; ?>"
                                 data-price="<?php echo $product['sale_price'] ?? $product['price']; ?>"
                                 data-stock="<?php echo $product['stock_quantity']; ?>">
                                <div class="product-tile">
                                    <div class="thumb">
                                        <?php if(!empty($product['image'])) : ?>
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="max-width:100%; max-height:100%; object-fit:cover; border-radius:8px;">
                                        <?php else : ?>
                                            <i class="fas fa-box text-muted"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="name"><?php echo $product['name']; ?></div>
                                    <?php 
                                        $basePrice = isset($product['sale_price']) && $product['sale_price'] > 0 
                                            ? (float)$product['sale_price'] 
                                            : (float)$product['price'];
                                    ?>
                                    <div class="meta fw-semibold"><?php echo formatPrice($basePrice); ?> · <span class="text-muted">Stock: <?php echo (int)$product['stock_quantity']; ?></span></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart / Bill & Invoice (moved to the LEFT on md+ using order-md-1) -->
    <div class="col-md-7 order-md-1">
        <div class="card bill-card">
            <div class="card-header bg-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0">Bill &amp; Invoice</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 620px;">
                        <div class="input-group input-group-sm flex-grow-1">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" placeholder="Enter Product name / SKU / Scan bar code" id="billSearch" />
                            <span class="input-group-text bg-light"><i class="fa-solid fa-circle-plus text-primary"></i></span>
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Customer mode">
                            <input type="radio" class="btn-check" name="customerMode" id="modeRegistered" value="registered" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary" for="modeRegistered">Registered</label>
                            <input type="radio" class="btn-check" name="customerMode" id="modeManual" value="manual" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="modeManual">Manual</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Manual customer overlay (hidden by default) -->
            <div class="customer-overlay-backdrop" id="manualOverlay">
                <div class="card customer-overlay-panel bg-white">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Manual Customer</strong>
                            <button type="button" class="btn-close" aria-label="Close" id="closeManualOverlay"></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label mb-1" for="manualName">Name</label>
                            <input type="text" class="form-control" id="manualName" placeholder="Enter customer name">
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1" for="manualPhone">Phone</label>
                            <input type="text" class="form-control" id="manualPhone" placeholder="Enter phone number">
                        </div>
                        <div class="mb-0">
                            <label class="form-label mb-1" for="manualEmail">Email</label>
                            <input type="email" class="form-control" id="manualEmail" placeholder="Enter email (optional)">
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="saveManualOverlay">Done</button>
                    </div>
                </div>
            </div>
            <!-- Registered customer overlay (hidden by default) -->
            <div class="customer-overlay-backdrop" id="registeredOverlay">
                <div class="card customer-overlay-panel bg-white">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Registered Customer</strong>
                            <button type="button" class="btn-close" aria-label="Close" id="closeRegisteredOverlay"></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" id="customerSearch" placeholder="Search customer by name, email, phone...">
                            <button class="btn btn-outline-secondary" type="button" id="clearCustomer">Clear</button>
                        </div>
                        <input type="hidden" id="customerId" value="">
                        <div id="customerInfo" class="mt-2"></div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="saveRegisteredOverlay">Done</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Customer fields placed right below the header -->
                <div class="mb-3" id="customerSection">
                    <!-- Summary only; searching happens in overlay -->
                    <div id="customerSummary" class="text-muted small"></div>
                </div>
                <div class="cart-head d-none d-md-flex justify-content-between mb-2">
                    <div>Product <i class="fa-solid fa-circle-info text-muted small"></i></div>
                    <div>Quantity</div>
                    <div>Subtotal</div>
                </div>
                <div class="cart-container mb-3" id="cartItems">
                    <!-- Cart items will be added here dynamically -->
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p>No items in cart</p>
                    </div>
                </div>

                <div class="cart-summary">
                    <hr class="my-2 cart-divider">
                    <!-- Items and Total row like first image -->
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-6"><strong>Items:</strong> <span id="itemCount">0</span></div>
                        <div class="col-6 text-end"><strong>Total:</strong> <span id="summaryTotal">CHF0.00</span></div>
                    </div>
                    <hr class="my-2">
                    <!-- Adjustments summary with inline editors -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <strong>Discount</strong> <span class="text-muted">(-)</span>
                                    <a href="#" id="editDiscount" class="ms-1" title="Edit discount"><i class="far fa-edit"></i></a>
                                </div>
                                <div>
                                    <span id="discount">CHF0.00</span>
                                </div>
                            </div>
                            <div id="discountInlineControls" class="d-none mt-2">
                                <div class="input-group input-group-sm">
                                    <select id="discountTypeInline" class="form-select">
                                        <option value="none">None</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                    <input id="discountValueInline" type="number" class="form-control" step="0.01" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <strong>Order Tax</strong> <span class="text-muted">(+)</span>
                                    <a href="#" id="editTax" class="ms-1" title="Edit order tax"><i class="far fa-edit"></i></a>
                                </div>
                                <div>
                                    <span id="orderTax">CHF0.00</span>
                                </div>
                            </div>
                            <div id="manualTaxInlineControls" class="d-none mt-2">
                                <div class="input-group input-group-sm">
                                    <select id="manualTaxTypeInline" class="form-select">
                                        <option value="none">None</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                    <input id="manualTaxValueInline" type="number" class="form-control" step="0.01" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <strong>Shipping</strong> <span class="text-muted">(+)</span>
                                    <a href="#" id="editShipping" class="ms-1" title="Edit shipping"><i class="far fa-edit"></i></a>
                                </div>
                                <div>
                                    <span id="shippingAmount">CHF0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden full controls; toggled by edit buttons -->
                    <div id="discountControlsRow" class="row g-3 d-none mb-2">
                        <div class="col-md-4">
                            <label class="form-label small">Discount Type</label>
                            <select id="discountType" class="form-select form-select-sm">
                                <option value="none">None</option>
                                <option value="percent">Percent (%)</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Discount Value</label>
                            <input id="discountValue" type="number" step="0.01" class="form-control form-control-sm" value="0">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="applyTax" checked>
                                <label class="form-check-label" for="applyTax">Apply category tax</label>
                            </div>
                        </div>
                    </div>

                    <div id="manualTaxControlsRow" class="row g-3 d-none mb-2">
                        <div class="col-md-4">
                            <label class="form-label small">Order Tax Type</label>
                            <select id="manualTaxType" class="form-select form-select-sm">
                                <option value="none">None</option>
                                <option value="percent">Percent (%)</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Order Tax Value</label>
                            <input id="manualTaxValue" type="number" step="0.01" class="form-control form-control-sm" value="0">
                        </div>
                    </div>

                    <div id="shippingControlsRow" class="row g-3 d-none mb-2">
                        <div class="col-md-4">
                            <label class="form-label small">Shipping Amount</label>
                            <input id="shippingValue" type="number" step="0.01" class="form-control form-control-sm" value="0">
                        </div>
                    </div>
                  

                <!-- Top action buttons removed as per request -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Bottom Action Bar -->
<div class="pos-bottom-bar mt-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-secondary action-btn" type="button"><i class="fa-regular fa-file-lines"></i> Draft</button>
            <button class="btn btn-outline-secondary action-btn" type="button"><i class="fa-regular fa-file"></i> Quotation</button>
            <button class="btn btn-outline-secondary action-btn" type="button"><i class="fa-regular fa-circle-pause"></i> Suspend</button>
            <button class="btn btn-outline-secondary action-btn" type="button"><i class="fa-regular fa-user"></i> Credit Sale</button>
            <button class="btn btn-outline-secondary action-btn" type="button" id="cardBtn"><i class="fa-regular fa-credit-card"></i> Card</button>
            <button class="btn btn-outline-secondary action-btn" type="button"><i class="fa-solid fa-layer-group"></i> Multiple Pay</button>
            <button class="btn btn-success action-btn" id="checkoutBtnBottom" type="button" disabled><i class="fa-solid fa-money-bill-wave"></i> Cash</button>
            <button class="btn btn-danger action-btn" id="bottomCancel" type="button" disabled><i class="fa-solid fa-xmark"></i> Cancel</button>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-primary"><i class="fa-solid fa-receipt me-1"></i> Recent Transactions</button>
            <div class="summary">Payable: <span id="bottomTotal">CHF0.00</span></div>
        </div>
    </div>
</div>

    <!-- Quantity Modal (small add-to-cart form) -->
    <div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="quantityModalLabel">Add Item</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2 small text-muted">Stock: <span id="availableStock">0</span></div>
                    <div class="mb-2">
                        <label class="form-label mb-1">Product</label>
                        <input type="text" class="form-control form-control-sm" id="selectedProductName" readonly>
                    </div>
                    <div class="mb-0">
                        <label class="form-label mb-1" for="productQuantity">Quantity</label>
                        <input type="number" class="form-control form-control-sm" id="productQuantity" value="1" min="1">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-primary btn-sm" id="addToCartBtn">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Discount Edit Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="discountModalLabel">Edit Discount</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label small">Discount Type</label>
                        <select id="discountTypeModal" class="form-select form-select-sm">
                            <option value="none">None</option>
                            <option value="percent">Percent (%)</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small">Discount Value</label>
                        <input id="discountValueModal" type="number" step="0.01" class="form-control form-control-sm" value="0">
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="applyTaxModal">
                        <label class="form-check-label small" for="applyTaxModal">Apply category tax</label>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveDiscountModal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Tax Edit Modal -->
    <div class="modal fade" id="orderTaxModal" tabindex="-1" aria-labelledby="orderTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="orderTaxModalLabel">Edit Order Tax</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label small">Order Tax Type</label>
                        <select id="manualTaxTypeModal" class="form-select form-select-sm">
                            <option value="none">None</option>
                            <option value="percent">Percent (%)</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small">Order Tax Value</label>
                        <input id="manualTaxValueModal" type="number" step="0.01" class="form-control form-control-sm" value="0">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveOrderTaxModal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Edit Modal -->
    <div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="shippingModalLabel">Edit Shipping</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label small">Shipping Amount</label>
                    <input id="shippingValueModal" type="number" step="0.01" class="form-control form-control-sm" value="0">
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveShippingModal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Complete Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="small text-muted mb-2">Advance Balance: <strong id="advanceBalance">Rs 0.00</strong></div>
                            <div class="p-3 rounded-3" style="background:#f7f7fb;border:1px solid #e9ecef;">
                                <div class="row g-3 align-items-start">
                                    <div class="col-sm-6">
                                        <label for="amountTendered" class="form-label">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                            <input type="number" class="form-control" id="amountTendered" min="0" step="0.01" value="0">
                                        </div>
                                        <div class="form-text">Total payable: <span id="modalTotal">CHF0.00</span></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select id="paymentMethod" class="form-select">
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="saleNotes" class="form-label">Payment note</label>
                                    <textarea class="form-control" id="saleNotes" rows="2" placeholder="Add a note (optional)"></textarea>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary w-100 mt-3" id="addPaymentRow" disabled>Add Payment Row</button>

                            <div class="row g-3 mt-2">
                                <div class="col-sm-6">
                                    <label class="form-label" for="sellNote">Sell note</label>
                                    <textarea id="sellNote" class="form-control" rows="2" placeholder="Sell note"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="staffNote">Staff note</label>
                                    <textarea id="staffNote" class="form-control" rows="2" placeholder="Staff note"></textarea>
                                </div>
                            </div>

                            <div id="changeAmount" class="alert alert-success d-none mt-3">
                                Change: <span id="changeValue">CHF0.00</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 h-100 rounded-3" style="background:#ff8c00; color:#fff;">
                                <div class="d-flex justify-content-between"><span>Total Items:</span><strong id="sideTotalItems">0</strong></div>
                                <hr class="my-2"/>
                                <div class="d-flex justify-content-between"><span>Total Payable:</span><strong id="sideTotalPayable">Rs 0.00</strong></div>
                                <div class="d-flex justify-content-between"><span>Total Paying:</span><strong id="sideTotalPaying">Rs 0.00</strong></div>
                                <div class="d-flex justify-content-between"><span>Change Return:</span><strong id="sideChangeReturn">Rs 0.00</strong></div>
                                <hr class="my-2"/>
                                <div class="d-flex justify-content-between"><span>Balance:</span><strong id="sideBalance">Rs 0.00</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="completeSaleBtn">Complete Sale</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            const CURRENCY = '<?php echo CURRENCY_SYMBOL; ?>';
            const CATEGORY_TAX = <?php echo json_encode(isset($categoryTaxMap) ? $categoryTaxMap : []); ?>; // {category_id: rate_percent}
            // Preload items from order if provided
            const PRELOAD_ITEMS = <?php echo json_encode(isset($preloadItems) ? $preloadItems : []); ?>;
            let cart = Array.isArray(PRELOAD_ITEMS) && PRELOAD_ITEMS.length > 0 ? PRELOAD_ITEMS : [];
            let selectedProduct = null;
            let customers = [];

            // Toolbar date/time
            function updateClock(){
                const d = new Date();
                const dateStr = d.toLocaleDateString();
                const timeStr = d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                $('#posDate').text(dateStr);
                $('#posTime').text(timeStr);
            }
            updateClock();
            setInterval(updateClock, 30 * 1000);

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // If we preloaded items, render immediately
            if (cart.length > 0) {
                updateCart();
            }

            // Product search
            $('#searchProduct').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.product-item').each(function() {
                    const productName = $(this).data('name').toLowerCase();
                    if (productName.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Category filter
            $('.category-filter .btn').on('click', function() {
                $('.category-filter .btn').removeClass('active');
                $(this).addClass('active');
                
                const categoryId = $(this).data('category');
                
                if (categoryId === 'all') {
                    $('.product-item').show();
                } else {
                    $('.product-item').hide();
                    $(`.product-item[data-category="${categoryId}"]`).show();
                }
            });

            // Product click - show quantity modal
            $(document).on('click', '.product-item', function() {
                selectedProduct = {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    price: $(this).data('price'),
                    stock: $(this).data('stock'),
                    categoryId: $(this).data('category')
                };
                
                $('#availableStock').text(selectedProduct.stock);
                $('#productQuantity').attr('max', selectedProduct.stock);
                $('#productQuantity').val(1);
                $('#selectedProductName').val(selectedProduct.name);
                
                if (selectedProduct.stock > 0) {
                    $('#quantityModal').modal('show');
                } else {
                    alert('This product is out of stock.');
                }
            });

            // Add to cart
            $('#addToCartBtn').on('click', function() {
                const quantity = parseInt($('#productQuantity').val());
                
                if (quantity <= 0 || quantity > selectedProduct.stock) {
                    alert('Invalid quantity.');
                    return;
                }
                
                // Check if product already in cart
                const existingItemIndex = cart.findIndex(item => item.id === selectedProduct.id);
                
                if (existingItemIndex !== -1) {
                    // Update quantity
                    cart[existingItemIndex].quantity += quantity;
                } else {
                    // Add new item
                    cart.push({
                        id: selectedProduct.id,
                        name: selectedProduct.name,
                        price: selectedProduct.price,
                        quantity: quantity,
                        categoryId: selectedProduct.categoryId,
                        stock: selectedProduct.stock
                    });
                }
                
                updateCart();
                $('#quantityModal').modal('hide');
            });

            // Update cart display
            function updateCart() {
                if (cart.length === 0) {
                    $('#cartItems').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p>No items in cart</p>
                        </div>
                    `);
                    $('#checkoutBtn, #clearCartBtn').prop('disabled', true);
                    $('#checkoutBtnBottom, #bottomCancel').prop('disabled', true);
                    $('#bottomTotal').text(formatPrice(0));
                    $('#itemCount').text(0);
                    $('#summaryTotal').text(formatPrice(0));
                    $('#shippingAmount').text(formatPrice(parseFloat($('#shippingValue').val()) || 0));
                } else {
                    let subtotal = 0;
                    let itemCount = 0;
                    let cartHtml = '';
                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        itemCount += item.quantity;
                        cartHtml += `
                            <div class=\"cart-item d-flex justify-content-between align-items-center\">
                                <div class=\"left me-2\">
                                    <div class=\"item-name fw-semibold\">${item.name}</div>
                                    <div class=\"text-muted small price-qty\">${formatPrice(item.price)} x ${item.quantity}</div>
                                </div>
                                <div class=\"qty-controls\">
                                    <div class=\"btn-group btn-group-sm\" role=\"group\" aria-label=\"Quantity controls\">
                                        <button class=\"btn btn-outline-secondary dec-qty\" data-index=\"${index}\" title=\"Decrease\">-</button>
                                        <span class=\"qty-value px-1\">${item.quantity}</span>
                                        <button class=\"btn btn-outline-secondary inc-qty\" data-index=\"${index}\" title=\"Increase\">+</button>
                                    </div>
                                </div>
                                <div class=\"right text-end\">
                                    <div class="fw-semibold item-total">${formatPrice(itemTotal)}</div>
                                    <a href="#" class="text-danger small remove-item" data-index="${index}"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                            <hr>
                        `;
                    });
                    $('#cartItems').html(cartHtml);

                    // Discount
                    const dType = $('#discountType').val();
                    const dVal = parseFloat($('#discountValue').val()) || 0;
                    let discountAmt = 0;
                    if (dType === 'percent') {
                        const pct = Math.max(0, Math.min(100, dVal));
                        discountAmt = subtotal * (pct / 100);
                    } else if (dType === 'fixed') {
                        discountAmt = Math.max(0, Math.min(subtotal, dVal));
                    }

                    const taxableBase = Math.max(0, subtotal - discountAmt);

                    // Category-wise tax (post-discount, proportionally allocated)
                    let tax = 0;
                    if ($('#applyTax').is(':checked') && subtotal > 0) {
                        cart.forEach((item) => {
                            const itemSubtotal = item.price * item.quantity;
                            const share = itemSubtotal / subtotal; // proportion of discount
                            const itemBase = Math.max(0, itemSubtotal - (discountAmt * share));
                            const rate = parseFloat(CATEGORY_TAX[item.categoryId]) || 0; // percent
                            tax += itemBase * (rate / 100);
                        });
                    }

                    // Manual tax based on discounted base (not on category tax)
                    const mType = $('#manualTaxType').val();
                    const mVal = parseFloat($('#manualTaxValue').val()) || 0;
                    let manualTaxAmt = 0;
                    if (mType === 'percent') {
                        const pct = Math.max(0, Math.min(100, mVal));
                        manualTaxAmt = taxableBase * (pct / 100);
                    } else if (mType === 'fixed') {
                        manualTaxAmt = Math.max(0, Math.min(taxableBase, mVal));
                    }

                    // Shipping (+)
                    const shipping = Math.max(0, parseFloat($('#shippingValue').val()) || 0);

                    const total = taxableBase + tax + manualTaxAmt + shipping;

                    $('#subtotal').text(formatPrice(subtotal));
                    $('#discount').text('- ' + formatPrice(discountAmt));
                    $('#tax').text(formatPrice(tax));
                    $('#manualTax').text(formatPrice(manualTaxAmt));
                    // New combined order tax summary (category tax + manual order tax)
                    $('#orderTax').text(formatPrice(tax + manualTaxAmt));
                    $('#shippingAmount').text(formatPrice(shipping));
                    $('#itemCount').text(itemCount);
                    const totalStr = formatPrice(total);
                    $('#total').text(totalStr);
                    $('#modalTotal').text(totalStr);
                    $('#bottomTotal').text(totalStr);
                    $('#summaryTotal').text(totalStr);
                    
                    $('#checkoutBtn, #clearCartBtn, #checkoutBtnBottom, #bottomCancel').prop('disabled', false);
                }
            }

            // Discount/tax controls
            $('#discountType').on('change', function() {
                const type = $(this).val();
                const $val = $('#discountValue');
                if (type === 'none') {
                    $val.prop('disabled', true).val(0);
                } else {
                    $val.prop('disabled', false);
                    if (!$val.val()) $val.val(0);
                }
                updateCart();
            });
            $('#discountValue').on('input', function() { updateCart(); });
            $('#applyTax').on('change', function() { updateCart(); });
            // Manual tax controls
            $('#manualTaxType').on('change', function() {
                const type = $(this).val();
                const $val = $('#manualTaxValue');
                if (type === 'none') {
                    $val.prop('disabled', true).val(0);
                } else {
                    $val.prop('disabled', false);
                    if (!$val.val()) $val.val(0);
                }
                updateCart();
            });
            $('#manualTaxValue').on('input', function() { updateCart(); });
            // Shipping input
            $('#shippingValue').on('input', function(){ updateCart(); });

            // Edit buttons -> open small modals (no inline/row expansion)
            $('#editDiscount').on('click', function(e){
                e.preventDefault();
                // prefill modal with current values
                $('#discountTypeModal').val($('#discountType').val());
                $('#discountValueModal').val($('#discountValue').val());
                $('#applyTaxModal').prop('checked', $('#applyTax').is(':checked'));
                $('#discountModal').modal('show');
            });
            $('#editTax').on('click', function(e){
                e.preventDefault();
                $('#manualTaxTypeModal').val($('#manualTaxType').val());
                $('#manualTaxValueModal').val($('#manualTaxValue').val());
                $('#orderTaxModal').modal('show');
            });
            $('#editShipping').on('click', function(e){
                e.preventDefault();
                $('#shippingValueModal').val($('#shippingValue').val());
                $('#shippingModal').modal('show');
            });

            // Save from modals back to main controls
            $('#saveDiscountModal').on('click', function(){
                $('#discountType').val($('#discountTypeModal').val()).trigger('change');
                $('#discountValue').val($('#discountValueModal').val());
                $('#applyTax').prop('checked', $('#applyTaxModal').is(':checked'));
                updateCart();
                $('#discountModal').modal('hide');
            });
            $('#saveOrderTaxModal').on('click', function(){
                $('#manualTaxType').val($('#manualTaxTypeModal').val()).trigger('change');
                $('#manualTaxValue').val($('#manualTaxValueModal').val());
                updateCart();
                $('#orderTaxModal').modal('hide');
            });
            $('#saveShippingModal').on('click', function(){
                $('#shippingValue').val($('#shippingValueModal').val());
                updateCart();
                $('#shippingModal').modal('hide');
            });

            // Keep inline Discount controls in sync with main controls
            $('#discountTypeInline').on('change', function(){
                $('#discountType').val($(this).val()).trigger('change');
            });
            $('#discountValueInline').on('input', function(){
                $('#discountValue').val($(this).val());
                updateCart();
            });

            // Keep inline Manual Tax controls in sync with main controls
            $('#manualTaxTypeInline').on('change', function(){
                $('#manualTaxType').val($(this).val()).trigger('change');
            });
            $('#manualTaxValueInline').on('input', function(){
                $('#manualTaxValue').val($(this).val());
                updateCart();
            });

            // Format price with dynamic currency
            function formatPrice(price) {
                return CURRENCY + ' ' + parseFloat(price).toFixed(2);
            }

            function parsePrice(text) {
                // Strip all non-numeric/decimal characters
                const n = parseFloat(String(text).replace(/[^0-9.]/g, ''));
                return isNaN(n) ? 0 : n;
            }

            // Remove item from cart
            $(document).on('click', '.remove-item', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
            });

            // Increase quantity
            $(document).on('click', '.inc-qty', function(e) {
                e.preventDefault();
                const index = $(this).data('index');
                const item = cart[index];
                if (!item) return;
                if (item.stock && item.quantity >= item.stock) {
                    alert('Cannot increase. Reached available stock.');
                    return;
                }
                item.quantity += 1;
                updateCart();
            });

            // Decrease quantity (min 1)
            $(document).on('click', '.dec-qty', function(e) {
                e.preventDefault();
                const index = $(this).data('index');
                const item = cart[index];
                if (!item) return;
                if (item.quantity > 1) {
                    item.quantity -= 1;
                    updateCart();
                }
            });

            // Clear cart
            $('#clearCartBtn').on('click', function() {
                if (confirm('Are you sure you want to clear the cart?')) {
                    cart = [];
                    updateCart();
                }
            });

            // Bottom bar button wiring
            $('#cardBtn').on('click', function(){
                $('#cardPaymentModal').modal('show');
            });
            $('#finalizeCardPayment').on('click', function(){
                $('#cardPaymentModal').modal('hide');
                openCheckout('Card');
            });
            $('#checkoutBtnBottom').on('click', function(){ openCheckout('Cash'); });
            $('#bottomCancel').on('click', function(){ $('#clearCartBtn').trigger('click'); });

            // Customer search (debounced)
            let customerSearchTimer = null;
            $('#customerSearch').on('input', function() {
                const searchTerm = $(this).val();
                if (customerSearchTimer) clearTimeout(customerSearchTimer);

                if (searchTerm.length < 2) {
                    $('#customerInfo').html('');
                    return;
                }

                // show loading
                $('#customerInfo').html('<div class="mt-2 small text-muted">Searching...</div>');

                customerSearchTimer = setTimeout(function() {
                    $.ajax({
                        url: '<?php echo BASE_URL; ?>?controller=user&action=search',
                        type: 'GET',
                        data: { keyword: searchTerm, limit: 10 },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                customers = response.users || [];
                                if (customers.length > 0) {
                                    let customersHtml = '<div class="list-group mt-2">';
                                    customers.forEach(customer => {
                                        const phone = customer.phone ? ` | ${customer.phone}` : '';
                                        customersHtml += `
                                            <a href="#" class="list-group-item list-group-item-action select-customer" data-id="${customer.id}">
                                                ${customer.first_name} ${customer.last_name} (${customer.email}${phone})
                                            </a>
                                        `;
                                    });
                                    customersHtml += '</div>';
                                    $('#customerInfo').html(customersHtml);
                                } else {
                                    $('#customerInfo').html('<div class="alert alert-info mt-2">No customers found</div>');
                                }
                            } else {
                                $('#customerInfo').html('<div class="alert alert-danger mt-2">Search failed</div>');
                            }
                        },
                        error: function(xhr) {
                            const msg = xhr && xhr.responseText ? xhr.responseText : 'An error occurred';
                            $('#customerInfo').html('<div class="alert alert-danger mt-2">' + msg + '</div>');
                        }
                    });
                }, 300);
            });

            // Select customer
            $(document).on('click', '.select-customer', function(e) {
                e.preventDefault();
                
                const customerId = $(this).data('id');
                const customer = customers.find(c => c.id == customerId);
                
                $('#customerId').val(customerId);
                $('#customerSearch').val(`${customer.first_name} ${customer.last_name}`);
                $('#customerInfo').html(`
                    <div class="alert alert-success mt-2">
                        Selected: ${customer.first_name} ${customer.last_name} (${customer.email})
                    </div>
                `);
                // Update summary under header and close overlay
                $('#customerSummary').html(`Selected: <strong>${customer.first_name} ${customer.last_name}</strong> <span class="text-muted">(${customer.email})</span>`);
                $('#registeredOverlay').removeClass('show');
            });

            // Clear customer
            $('#clearCustomer').on('click', function() {
                $('#customerId').val('');
                $('#customerSearch').val('');
                $('#customerInfo').html('');
                $('#customerSummary').text('');
            });

            // Customer mode toggle (Registered vs Manual)
            $('input[name="customerMode"]').on('change', function() {
                const mode = $(this).val();
                if (mode === 'registered') {
                    // Hide manual overlay; don't auto-open registered overlay here
                    $('#manualOverlay').removeClass('show');
                    // clear manual fields
                    $('#manualName, #manualPhone, #manualEmail').val('');
                } else {
                    // Show manual overlay, hide registered overlay
                    $('#registeredOverlay').removeClass('show');
                    $('#manualOverlay').addClass('show');
                    // clear registered selection
                    $('#customerId').val('');
                    $('#customerSearch').val('');
                    $('#customerInfo').html('');
                    $('#customerSummary').text('');
                }
            });

            // Open Registered overlay only on explicit user click
            $('#modeRegistered, label[for="modeRegistered"]').on('click', function() {
                // Only show when switching/choosing Registered by user
                $('#registeredOverlay').addClass('show');
                $('#manualOverlay').removeClass('show');
            });

            // Registered overlay controls: close/done -> just close, keep mode Registered
            $('#closeRegisteredOverlay, #saveRegisteredOverlay').on('click', function() {
                $('#registeredOverlay').removeClass('show');
                $('#modeRegistered').prop('checked', true);
            });

            // Manual overlay controls: close/done -> hide and revert to Registered
            $('#closeManualOverlay, #saveManualOverlay').on('click', function() {
                $('#manualOverlay').removeClass('show');
                $('#modeRegistered').prop('checked', true).trigger('change');
            });

            // Helper to open checkout modal, optionally preset payment method
            function openCheckout(presetMethod){
                if (cart.length === 0) {
                    alert('Cart is empty.');
                    return;
                }
                if (presetMethod){
                    $('#paymentMethod').val(presetMethod);
                }
                const total = parsePrice($('#summaryTotal').text());
                $('#amountTendered').val(total);
                $('#modalTotal').text(formatPrice(total));
                // Prime sidebar values
                $('#sideTotalItems').text($('#itemCount').text());
                updatePaymentSummary();
                $('#changeAmount').addClass('d-none');
                $('#checkoutModal').modal('show');
            }

            // Checkout button (top) should NOT open modal; guide user to Cash button
            $('#checkoutBtn').on('click', function(e) {
                e.preventDefault();
                try { document.getElementById('checkoutBtnBottom').scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch(err) {}
                $('#checkoutBtnBottom').addClass('btn-warning');
                setTimeout(function(){ $('#checkoutBtnBottom').removeClass('btn-warning'); }, 1200);
            });

            // When cart becomes empty, reflect disabled state
            function reflectEmpty(){
                const isEmpty = cart.length === 0;
                $('#checkoutBtn, #clearCartBtn, #checkoutBtnBottom, #bottomCancel').prop('disabled', isEmpty);
                if (isEmpty){
                    $('#bottomTotal').text(formatPrice(0));
                }
            }
            // Call after updateCart when empty

            // Calculate change
            $('#amountTendered').on('input', function() {
                updatePaymentSummary();
            });

            // Update right-side payment summary and change/balance alert
            function updatePaymentSummary(){
                const total = parsePrice($('#summaryTotal').text());
                const paying = parseFloat($('#amountTendered').val()) || 0;
                const change = Math.max(0, paying - total);
                const balance = Math.max(0, total - paying);
                $('#sideTotalPayable').text(formatPrice(total));
                $('#sideTotalPaying').text(formatPrice(paying));
                $('#sideChangeReturn').text(formatPrice(change));
                $('#sideBalance').text(formatPrice(balance));
                // legacy alert
                if (change > 0){
                    $('#changeValue').text(formatPrice(change));
                    $('#changeAmount').removeClass('d-none');
                } else {
                    $('#changeAmount').addClass('d-none');
                }
            }

            // Complete sale
            let lastOrderId = null;
            $('#completeSaleBtn').on('click', function() {
                const amountTendered = parseFloat($('#amountTendered').val());
                const total = parsePrice($('#summaryTotal').text());
                const paymentMethod = ($('#paymentMethod').val() || '').toString().toLowerCase();
                const customerMode = $('input[name="customerMode"]:checked').val();
                const customerId = $('#customerId').val();
                const manualName = $('#manualName').val();
                const manualPhone = $('#manualPhone').val();
                const manualEmail = $('#manualEmail').val();
                const notes = $('#saleNotes').val();
                const sellNote = $('#sellNote').val();
                const staffNote = $('#staffNote').val();
                
                if (amountTendered < total) {
                    alert('Amount tendered must be greater than or equal to the total amount.');
                    return;
                }
                
                // Prepare data for submission (map to backend schema)
                const itemsForServer = cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                }));
                // Derive order-level numbers for backend/receipt
                const subtotalForServer = cart.reduce((s, it) => s + (it.price * it.quantity), 0);
                const taxForServer = parsePrice($('#orderTax').text());
                const shippingForServer = parsePrice($('#shippingAmount').text());
                const data = {
                    items: JSON.stringify(itemsForServer),
                    customer_mode: customerMode,
                    customer_id: customerId,
                    manual_name: manualName,
                    manual_phone: manualPhone,
                    manual_email: manualEmail,
                    payment_method: paymentMethod,
                    total_amount: total,
                    amount_tendered: amountTendered,
                    paid_amount: amountTendered,
                    tax: taxForServer,
                    shipping_fee: shippingForServer,
                    subtotal: subtotalForServer,
                    notes: notes,
                    sell_note: sellNote,
                    staff_note: staffNote
                };
                
                // Submit sale
                $.ajax({
                    url: '<?php echo BASE_URL; ?>?controller=pos&action=processSale',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.success) {
                            lastOrderId = response.order_id;
                            // Close checkout and reset cart for next sale
                            $('#checkoutModal').modal('hide');
                            // Open post-sale actions modal
                            $('#postSaleModal').modal('show');
                            cart = [];
                            updateCart();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, err) {
                        let msg = 'An error occurred. Please try again.';
                        if (xhr && xhr.responseText) {
                            try {
                                const j = JSON.parse(xhr.responseText);
                                if (j && j.message) msg = j.message;
                                else msg = xhr.responseText;
                            } catch(e){
                                msg = xhr.responseText;
                            }
                        } else if (err) {
                            msg = err;
                        }
                        alert(msg);
                    }
                });
            });

            // Post-sale modal actions
            $('#postSalePrint').on('click', function(){
                if (!lastOrderId) return;
                const receiptUrl = '<?php echo BASE_URL; ?>?controller=pos&action=receipt&param=' + lastOrderId;
                window.open(receiptUrl, '_blank');
                $('#postSaleModal').modal('hide');
            });
            $('#postSaleMail').on('click', function(){
                // Navigate to Mail page with context so SMTP section appears
                const qs = lastOrderId ? '&from=pos&order_id=' + encodeURIComponent(lastOrderId) : '&from=pos';
                window.location.href = '<?php echo BASE_URL; ?>?controller=mail&action=index' + qs;
            });
        });
    </script>
</body>
</html>
