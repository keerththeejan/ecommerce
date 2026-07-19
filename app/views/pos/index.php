<?php
// Check if staff
if(!isStaff()) {
    redirect('user/login');
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Sivakamy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Enterprise POS CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/pos.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('pos_theme');
                if (t === 'dark' || t === 'light') {
                    document.documentElement.setAttribute('data-theme', t);
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="pos-app">
    <!-- Hidden stubs required by existing POS JS (do not remove) -->
    <div class="pos-hidden-stubs" aria-hidden="true">
        <button type="button" id="checkoutBtn" disabled></button>
        <button type="button" id="clearCartBtn" disabled></button>
        <span id="subtotal"></span>
        <span id="tax"></span>
        <span id="manualTax"></span>
        <span id="total"></span>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light navbar-ash pos-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>?controller=pos">
                <span class="pos-brand-mark" aria-hidden="true">P</span>
                <span>POS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>?controller=pos"><i class="bi bi-shop-window me-1"></i>POS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=report"><i class="bi bi-graph-up me-1"></i>Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=session"><i class="bi bi-clock-history me-1"></i>Session</a>
                    </li>
                    <?php if(isAdmin()) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="bi bi-speedometer2 me-1"></i>Admin</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="ms-auto d-flex align-items-center toolbar-row flex-nowrap gap-2">
                    <select class="form-select form-select-sm pos-branch-select" id="posBranchSelect" title="Branch" aria-label="Branch">
                        <option value="main" selected>Main Branch</option>
                        <option value="warehouse">Warehouse</option>
                        <option value="online">Online Counter</option>
                    </select>
                    <span class="badge-soft d-none d-md-inline-flex"><i class="bi bi-person-badge"></i> <span class="hide-xs"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cashier'); ?></span></span>
                    <span class="badge-soft"><i class="bi bi-calendar3"></i> <span id="posDate"></span></span>
                    <span class="badge-soft"><i class="bi bi-clock"></i> <span id="posTime"></span></span>
                    <div class="dropdown">
                        <button type="button" class="icon-btn" id="posNotificationsBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications"><i class="bi bi-bell"></i><span class="pos-notif-dot" aria-hidden="true"></span></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:260px">
                            <li class="dropdown-header">Notifications</li>
                            <li><span class="dropdown-item-text small text-muted">Low stock alerts appear in Reports.</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=pos&action=report"><i class="bi bi-graph-up me-2"></i>Open reports</a></li>
                        </ul>
                    </div>
                    <button type="button" class="icon-btn" id="posThemeToggle" title="Toggle theme" aria-label="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
                    <button type="button" class="icon-btn" id="posFullscreenBtn" title="Fullscreen" aria-label="Toggle fullscreen"><i class="bi bi-fullscreen"></i></button>
                    <button type="button" class="icon-btn" id="posCalculatorBtn" title="Calculator" aria-label="Calculator" data-bs-toggle="modal" data-bs-target="#posCalculatorModal"><i class="bi bi-calculator"></i></button>
                    <button type="button" class="icon-btn" id="posHoldQuickBtn" title="Hold bill" aria-label="Hold bill"><i class="bi bi-pause-circle"></i></button>
                    <button type="button" class="icon-btn" id="posSettingsBtn" title="Settings" aria-label="Settings" data-bs-toggle="offcanvas" data-bs-target="#posSettingsOffcanvas"><i class="bi bi-gear"></i></button>
                    <a href="<?php echo BASE_URL; ?>?controller=expense&action=create" class="btn btn-outline-primary btn-sm d-none d-xl-inline-flex"><i class="bi bi-plus-circle me-1"></i>Expense</a>
                    <div class="dropdown ms-1">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cashier'); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>">Store Front</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=pos&action=session">Cash Session</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid pos-main">
        <!-- Flash Messages -->
        <?php flash('pos_success'); ?>
        <?php flash('pos_error'); ?>
        <div class="row g-3">
    <!-- Products Section (RIGHT ~35%) -->
    <div class="col-12 col-lg-4 order-2 order-lg-2">
        <div class="card products-card">
            <div class="card-header bg-white">
                <div class="row align-items-center g-2">
                    <div class="col-md-9">
                        <div class="input-group pos-search-wrap">
                            <span class="input-group-text border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                            <input type="text" id="searchProduct" class="form-control border-start-0 border-end-0" placeholder="Name · SKU · Barcode · Category…" aria-label="Search products" autocomplete="off">
                            <button type="button" class="input-group-text btn btn-light border-start-0" id="posVoiceSearchBtn" title="Voice search" aria-label="Voice search"><i class="bi bi-mic"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <span class="brands-pill"><i class="bi bi-grid-3x3-gap"></i> Catalog</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Category Filter -->
                <div class="category-filter mb-3">
                    <button type="button" class="btn btn-outline-primary active" data-category="all">All</button>
                    <?php foreach($categories as $category) : ?>
                        <button type="button" class="btn btn-outline-primary" data-category="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></button>
                    <?php endforeach; ?>
                </div>

                <div class="product-grid">
                    <div class="row g-2" id="productContainer">
                        <?php foreach($products as $product) : ?>
                            <?php
                                $stockQty = (int)($product['stock_quantity'] ?? 0);
                                $stockClass = $stockQty <= 0 ? 'is-out' : ($stockQty <= 5 ? 'is-low' : '');
                                $stockLabel = $stockQty <= 0 ? 'Out' : ($stockQty <= 5 ? 'Low' : 'In stock');
                                $productSku = (string)($product['sku'] ?? '');
                                $productImage = !empty($product['image']) ? (BASE_URL . $product['image']) : '';
                            ?>
                            <div class="col-6 col-md-4 col-xl-3 mb-2 product-item"
                                 data-category="<?php echo htmlspecialchars((string)($product['category_id'] ?? '')); ?>"
                                 data-category-name="<?php echo htmlspecialchars((string)($product['category_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                 data-id="<?php echo (int)$product['id']; ?>"
                                 data-name="<?php echo htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                 data-sku="<?php echo htmlspecialchars($productSku, ENT_QUOTES, 'UTF-8'); ?>"
                                 data-image="<?php echo htmlspecialchars($productImage, ENT_QUOTES, 'UTF-8'); ?>"
                                 data-price="<?php echo htmlspecialchars((string)($product['sale_price'] ?? $product['price'] ?? 0)); ?>"
                                 data-stock="<?php echo (int)$product['stock_quantity']; ?>">
                                <div class="product-tile">
                                    <span class="stock-badge <?php echo $stockClass; ?>"><?php echo $stockLabel; ?></span>
                                    <button type="button" class="fav-btn" data-fav-id="<?php echo (int)$product['id']; ?>" title="Favorite" aria-label="Favorite"><i class="bi bi-star"></i></button>
                                    <div class="thumb">
                                        <?php if($productImage !== '') : ?>
                                            <img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                        <?php else : ?>
                                            <i class="bi bi-box-seam text-muted fs-3"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="name"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <?php if ($productSku !== '') : ?>
                                        <div class="sku-line"><?php echo htmlspecialchars($productSku); ?></div>
                                    <?php endif; ?>
                                    <?php 
                                        $basePrice = isset($product['sale_price']) && $product['sale_price'] > 0 
                                            ? (float)$product['sale_price'] 
                                            : (float)$product['price'];
                                    ?>
                                    <div class="meta"><?php echo formatPrice($basePrice); ?></div>
                                    <button type="button" class="quick-add" tabindex="-1" aria-hidden="true"><i class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart / Current Sale (LEFT ~65%) -->
    <div class="col-12 col-lg-8 order-1 order-lg-1">
        <div class="card bill-card">
            <div class="card-header bg-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="pos-panel-title mb-0"><i class="bi bi-receipt me-1 text-primary"></i>Current Sale</h5>
                        <div class="pos-panel-meta">Cashier: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Staff'); ?> · Walk-in ready</div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 720px;">
                        <div class="input-group input-group-sm flex-grow-1">
                            <span class="input-group-text border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0" placeholder="Product name / SKU / barcode" id="billSearch" autocomplete="off" />
                            <span class="input-group-text"><i class="bi bi-plus-circle text-primary"></i></span>
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Customer mode">
                            <input type="radio" class="btn-check" name="customerMode" id="modeRegistered" value="registered" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary" for="modeRegistered">Customer</label>
                            <input type="radio" class="btn-check" name="customerMode" id="modeManual" value="manual" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="modeManual">Walk-in</label>
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
                <!-- Sale meta + order type (UI only; notes sync into existing sellNote) -->
                <div class="pos-sale-meta">
                    <div class="pos-meta-chip"><i class="bi bi-hash"></i> Invoice <strong id="posInvoiceNo">POS-<?php echo date('ymd'); ?>-DRAFT</strong></div>
                    <div class="pos-meta-chip"><i class="bi bi-person"></i> <span id="posCashierMeta"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Cashier'); ?></span></div>
                    <div class="pos-meta-chip">
                        <label class="mb-0 me-1" for="posTableNo">Table</label>
                        <input type="text" id="posTableNo" class="form-control form-control-sm border-0 bg-transparent p-0" style="width:56px;font-weight:700" placeholder="—" maxlength="8">
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="btn-group pos-order-types" role="group" aria-label="Order type">
                        <input type="radio" class="btn-check" name="posOrderType" id="orderDineIn" value="dine_in" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary" for="orderDineIn">Dine In</label>
                        <input type="radio" class="btn-check" name="posOrderType" id="orderTakeAway" value="take_away" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="orderTakeAway">Take Away</label>
                        <input type="radio" class="btn-check" name="posOrderType" id="orderDelivery" value="delivery" autocomplete="off">
                        <label class="btn btn-outline-secondary" for="orderDelivery">Delivery</label>
                    </div>
                    <input type="text" id="posCustomerNotes" class="form-control form-control-sm" style="max-width:280px" placeholder="Customer notes (optional)">
                </div>
                <!-- Customer fields placed right below the header -->
                <div class="mb-3" id="customerSection">
                    <!-- Summary only; searching happens in overlay -->
                    <div id="customerSummary" class="text-muted small"></div>
                </div>
                <div class="cart-head d-none d-md-flex justify-content-between mb-2">
                    <div>Product</div>
                    <div>Qty</div>
                    <div>Subtotal</div>
                    <div></div>
                </div>
                <div class="cart-container mb-3" id="cartItems">
                    <div class="pos-empty-cart">
                        <div class="pos-empty-icon" aria-hidden="true"><i class="bi bi-cart3"></i></div>
                        <h6>Cart is Empty</h6>
                        <p>Search, scan, or tap a product to begin</p>
                    </div>
                </div>

                <div class="cart-summary">
                    <hr class="my-2 cart-divider">
                    <div class="pos-summary-grid">
                        <div class="pos-summary-chip">
                            <div class="label">Items</div>
                            <div class="value"><span id="itemCount">0</span></div>
                        </div>
                        <div class="pos-summary-chip">
                            <div class="label">Discount <a href="#" id="editDiscount" class="ms-1" title="Edit discount"><i class="bi bi-pencil-square"></i></a></div>
                            <div class="value"><span id="discount">CHF0.00</span></div>
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
                        <div class="pos-summary-chip">
                            <div class="label">Tax <a href="#" id="editTax" class="ms-1" title="Edit order tax"><i class="bi bi-pencil-square"></i></a></div>
                            <div class="value"><span id="orderTax">CHF0.00</span></div>
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
                        <div class="pos-summary-chip">
                            <div class="label">Shipping <a href="#" id="editShipping" class="ms-1" title="Edit shipping"><i class="bi bi-pencil-square"></i></a></div>
                            <div class="value"><span id="shippingAmount">CHF0.00</span></div>
                        </div>
                    </div>
                    <div class="pos-grand-total mb-2">
                        <span class="label">Grand Total</span>
                        <span class="amount" id="summaryTotal">CHF0.00</span>
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
        </div><!-- /.row -->
    </div><!-- /.pos-main -->

<!-- Sticky Bottom Action Bar -->
<div class="pos-bottom-bar mt-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button class="btn btn-outline-secondary action-btn" type="button" id="draftBtn"><i class="bi bi-file-earmark"></i> <span class="label">Draft</span></button>
            <button class="btn btn-outline-secondary action-btn" type="button" id="quotationBtn"><i class="bi bi-file-text"></i> <span class="label">Quotation</span></button>
            <button class="btn btn-outline-secondary action-btn" type="button" id="suspendBtn"><i class="bi bi-pause-circle"></i> <span class="label">Hold</span></button>
            <button class="btn btn-outline-primary action-btn" type="button" id="resumeBtn"><i class="bi bi-play-circle"></i> <span class="label">Resume</span></button>
            <button class="btn btn-outline-secondary action-btn" type="button" id="creditBtn"><i class="bi bi-person-badge"></i> <span class="label">Credit</span></button>
            <div class="pos-pay-extra">
                <button class="btn btn-outline-secondary" type="button" id="qrPayBtn" title="QR / UPI"><i class="bi bi-qr-code"></i></button>
                <button class="btn btn-outline-secondary" type="button" id="walletPayBtn" title="Wallet"><i class="bi bi-wallet2"></i></button>
                <button class="btn btn-outline-secondary" type="button" id="transferPayBtn" title="Bank Transfer"><i class="bi bi-bank"></i></button>
            </div>
            <button class="btn btn-outline-secondary action-btn pay-upi" type="button" id="upiBtn" title="UPI"><i class="bi bi-phone"></i> <span class="label">UPI</span></button>
            <button class="btn action-btn pay-card" type="button" id="cardBtn"><i class="bi bi-credit-card"></i> <span class="label">Card</span></button>
            <button class="btn btn-outline-secondary action-btn" type="button" id="splitBtn"><i class="bi bi-layers"></i> <span class="label">Split</span></button>
            <button class="btn btn-success action-btn pay-cash" id="checkoutBtnBottom" type="button" disabled><i class="bi bi-cash-stack"></i> <span class="label">Cash</span></button>
            <button class="btn btn-danger action-btn pay-cancel" id="bottomCancel" type="button" disabled><i class="bi bi-x-lg"></i> <span class="label">Cancel</span></button>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo BASE_URL; ?>?controller=pos&action=report" class="btn btn-outline-primary btn-sm" id="recentTransactionsBtn"><i class="bi bi-clock-history me-1"></i> Recent</a>
            <div class="summary">Payable: <span id="bottomTotal">CHF0.00</span></div>
        </div>
    </div>
</div>

<div class="pos-shortcut-bar d-none d-xl-flex" aria-hidden="true">
    <span><kbd>F2</kbd> New · <kbd>F3</kbd> Customer · <kbd>F4</kbd> Hold · <kbd>F5</kbd> Pay · <kbd>F6</kbd> Search · <kbd>F8</kbd> Discount · <kbd>F9</kbd> Cash · <kbd>Esc</kbd> Cancel</span>
</div>

<nav class="pos-mobile-nav" aria-label="POS mobile navigation">
    <button type="button" class="nav-item-btn active" id="posMobCart" data-pos-panel="cart"><i class="bi bi-cart3"></i>Sale</button>
    <button type="button" class="nav-item-btn" id="posMobProducts" data-pos-panel="products"><i class="bi bi-grid"></i>Products</button>
    <button type="button" class="nav-item-btn" id="posMobPay"><i class="bi bi-cash"></i>Pay</button>
    <a class="nav-item-btn" href="<?php echo BASE_URL; ?>?controller=pos&action=report"><i class="bi bi-graph-up"></i>Reports</a>
    <button type="button" class="nav-item-btn" data-bs-toggle="offcanvas" data-bs-target="#posSettingsOffcanvas"><i class="bi bi-gear"></i>More</button>
</nav>

<div class="pos-fab-stack" aria-label="Quick actions">
    <a class="pos-fab" href="<?php echo BASE_URL; ?>?controller=pos&action=report" title="Reports" id="posFabReports"><i class="bi bi-graph-up"></i></a>
    <a class="pos-fab" href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" title="Products" id="posFabProducts"><i class="bi bi-box-seam"></i></a>
    <a class="pos-fab" href="<?php echo BASE_URL; ?>?controller=user&action=customers" title="Customers" id="posFabCustomers"><i class="bi bi-people"></i></a>
    <button type="button" class="pos-fab" id="posFocusSearch" title="Focus search"><i class="bi bi-search"></i></button>
</div>

    <!-- Card Payment Modal (must be outside overflow cards) -->
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
                        <button class="btn btn-primary" id="postSalePrint"><i class="fa-solid fa-print me-1"></i> Print Receipt</button>
                        <button class="btn btn-outline-secondary" id="postSaleMail"><i class="fa-regular fa-envelope me-1"></i> Email</button>
                        <button class="btn btn-outline-success" type="button" id="postSaleWhatsApp"><i class="bi bi-whatsapp me-1"></i> WhatsApp</button>
                        <button class="btn btn-outline-secondary" type="button" id="postSaleSms"><i class="bi bi-chat-dots me-1"></i> SMS</button>
                    </div>
                </div>
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
                    <h5 class="modal-title" id="checkoutModalLabel"><i class="bi bi-shield-check me-2 text-success"></i>Complete Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="small text-muted mb-2">Advance Balance: <strong id="advanceBalance">Rs 0.00</strong></div>
                            <div class="pos-payment-chips" id="posPaymentChips">
                                <button type="button" class="btn btn-outline-secondary btn-sm active" data-pay="Cash">Cash</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-pay="Card">Card</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-pay="UPI">UPI / QR</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-pay="Other">Transfer / Wallet</button>
                            </div>
                            <div class="p-3 rounded-3 pos-checkout-panel">
                                <div class="row g-3 align-items-start">
                                    <div class="col-sm-6">
                                        <label for="amountTendered" class="form-label">Amount Tendered</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-money-bill"></i></span>
                                            <input type="number" class="form-control form-control-lg" id="amountTendered" min="0" step="0.01" value="0">
                                        </div>
                                        <div class="form-text">Total payable: <span id="modalTotal">CHF0.00</span></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select id="paymentMethod" class="form-select form-select-lg">
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
                            <div class="p-3 h-100 pos-pay-summary-card">
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
                    <button type="button" class="btn btn-success" id="completeSaleBtn"><i class="bi bi-check2-circle me-1"></i>Complete Sale</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculator Modal (UI only) -->
    <div class="modal fade" id="posCalculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title mb-0">Calculator</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="posCalcDisplay" class="form-control mb-2" value="0" readonly>
                    <div class="pos-calc-grid" id="posCalcPad">
                        <button type="button" class="btn btn-outline-secondary" data-calc="7">7</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="8">8</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="9">9</button>
                        <button type="button" class="btn btn-outline-primary" data-calc="/">÷</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="4">4</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="5">5</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="6">6</button>
                        <button type="button" class="btn btn-outline-primary" data-calc="*">×</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="1">1</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="2">2</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="3">3</button>
                        <button type="button" class="btn btn-outline-primary" data-calc="-">−</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc="0">0</button>
                        <button type="button" class="btn btn-outline-secondary" data-calc=".">.</button>
                        <button type="button" class="btn btn-success" data-calc="=">=</button>
                        <button type="button" class="btn btn-outline-primary" data-calc="+">+</button>
                        <button type="button" class="btn btn-outline-danger" data-calc="C" style="grid-column: span 4;">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Offcanvas (UI prefs only) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="posSettingsOffcanvas" aria-labelledby="posSettingsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="posSettingsLabel">POS Settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Theme</label>
                <select id="posSettingTheme" class="form-select">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Receipt size preference</label>
                <select id="posSettingReceipt" class="form-select">
                    <option value="thermal">Thermal 80mm</option>
                    <option value="a4">A4 Invoice</option>
                </select>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="posSettingSound" checked>
                <label class="form-check-label" for="posSettingSound">Beep on barcode scan</label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="posSettingShortcuts" checked>
                <label class="form-check-label" for="posSettingShortcuts">Show shortcut bar</label>
            </div>
            <p class="small text-muted mb-0">Preferences are saved on this device only. Payment, tax, and inventory rules remain server-side.</p>
            <hr>
            <div class="d-grid gap-2">
                <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=pos&action=session">Cash Drawer / Session</a>
                <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>?controller=pos&action=report">Sales Reports</a>
                <?php if(isAdmin()) : ?>
                <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>?controller=home&action=admin">Admin Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Bootstrap 5 does not ship jQuery $.fn.modal — bridge so existing POS calls keep working
    (function ($) {
        if (!$ || !window.bootstrap || !bootstrap.Modal) return;
        if (typeof $.fn.modal === 'function') return;
        $.fn.modal = function (action) {
            return this.each(function () {
                var instance = bootstrap.Modal.getOrCreateInstance(this);
                if (action === 'show') instance.show();
                else if (action === 'hide') instance.hide();
                else if (action === 'toggle') instance.toggle();
                else if (action && typeof action === 'object') {
                    instance = bootstrap.Modal.getOrCreateInstance(this, action);
                }
            });
        };
    })(window.jQuery);
    </script>
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

            // Initialize Bootstrap tooltips (safe if none present)
            try {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            } catch (e) { /* ignore */ }

            // If we preloaded items, render immediately
            if (cart.length > 0) {
                updateCart();
            }

            function productAttr($el, key) {
                // Prefer attr() so data-* stays as string (jQuery .data() coerces types)
                var v = $el.attr('data-' + key);
                if (typeof v === 'undefined' || v === null) {
                    v = $el.data(key);
                }
                return v;
            }

            function filterProducts(term) {
                const searchTerm = String(term || '').toLowerCase().trim();
                $('.product-item').each(function() {
                    const $item = $(this);
                    const productName = String(productAttr($item, 'name') || '').toLowerCase();
                    const productSku = String(productAttr($item, 'sku') || '').toLowerCase();
                    const productId = String(productAttr($item, 'id') || '').toLowerCase();
                    const categoryName = String(productAttr($item, 'category-name') || '').toLowerCase();
                    const match = !searchTerm
                        || productName.indexOf(searchTerm) !== -1
                        || productSku.indexOf(searchTerm) !== -1
                        || productId === searchTerm
                        || categoryName.indexOf(searchTerm) !== -1;
                    $item.toggleClass('d-none', !match);
                    if (match) $item.show();
                    else $item.hide();
                });
            }

            function findProductByScan(term) {
                const t = String(term || '').toLowerCase().trim();
                if (!t) return null;
                let $match = null;
                $('.product-item').each(function() {
                    const $item = $(this);
                    const name = String(productAttr($item, 'name') || '').toLowerCase();
                    const sku = String(productAttr($item, 'sku') || '').toLowerCase();
                    const id = String(productAttr($item, 'id') || '').toLowerCase();
                    if (sku === t || id === t || name === t) {
                        $match = $item;
                        return false;
                    }
                });
                // Partial barcode/sku contains match as fallback
                if (!$match) {
                    $('.product-item').each(function() {
                        const $item = $(this);
                        const sku = String(productAttr($item, 'sku') || '').toLowerCase();
                        if (sku && sku.indexOf(t) !== -1) {
                            $match = $item;
                            return false;
                        }
                    });
                }
                return $match;
            }

            function openProductFromElement($el) {
                if (!$el || !$el.length) return;
                $('.product-item').removeClass('is-selected');
                $el.addClass('is-selected');
                selectedProduct = {
                    id: parseInt(productAttr($el, 'id'), 10),
                    name: String(productAttr($el, 'name') || ''),
                    price: parseFloat(productAttr($el, 'price')) || 0,
                    stock: parseInt(productAttr($el, 'stock'), 10) || 0,
                    categoryId: productAttr($el, 'category'),
                    sku: String(productAttr($el, 'sku') || ''),
                    image: String(productAttr($el, 'image') || '')
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
            }

            // Product search (name / sku / id)
            $('#searchProduct').on('input', function() {
                filterProducts($(this).val());
            });

            // Bill barcode / quick search mirrors product search
            $('#billSearch').on('input', function() {
                filterProducts($(this).val());
                $('#searchProduct').val($(this).val());
            });

            // Barcode scanner: Enter adds matching product
            $('#searchProduct, #billSearch').on('keydown', function(e) {
                if (e.key !== 'Enter') return;
                e.preventDefault();
                const term = $(this).val();
                const $match = findProductByScan(term);
                if ($match) {
                    openProductFromElement($match);
                    $(this).val('');
                    $('#searchProduct').val('');
                    filterProducts('');
                    setTimeout(function() { $('#searchProduct').trigger('focus'); }, 50);
                } else if (String(term || '').trim() !== '') {
                    alert('No product found for: ' + term);
                }
            });

            // Category filter
            $('.category-filter .btn').on('click', function() {
                $('.category-filter .btn').removeClass('active');
                $(this).addClass('active');
                
                const categoryId = String($(this).attr('data-category') || $(this).data('category') || 'all');
                $('#searchProduct, #billSearch').val('');
                
                if (categoryId === 'all') {
                    $('.product-item').removeClass('d-none').show();
                } else {
                    $('.product-item').each(function() {
                        const cat = String($(this).attr('data-category') || '');
                        const match = cat === categoryId;
                        $(this).toggleClass('d-none', !match);
                        if (match) $(this).show();
                        else $(this).hide();
                    });
                }
            });

            // Product click - show quantity modal
            $(document).on('click', '.product-item', function(e) {
                if ($(e.target).closest('.fav-btn').length) return;
                e.preventDefault();
                openProductFromElement($(this));
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
                        stock: selectedProduct.stock,
                        sku: selectedProduct.sku || '',
                        image: selectedProduct.image || '',
                        note: ''
                    });
                }
                
                updateCart();
                $('#quantityModal').modal('hide');
            });

            $('#productQuantity').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#addToCartBtn').trigger('click');
                }
            });

            // Update cart display
            function updateCart() {
                if (cart.length === 0) {
                    $('#cartItems').html(`
                        <div class="pos-empty-cart">
                            <div class="pos-empty-icon" aria-hidden="true"><i class="bi bi-cart3"></i></div>
                            <h6>Cart is Empty</h6>
                            <p>Search or scan a product to begin</p>
                        </div>
                    `);
                    $('#checkoutBtn, #clearCartBtn').prop('disabled', true);
                    $('#checkoutBtnBottom, #bottomCancel').prop('disabled', true);
                    $('#bottomTotal').text(formatPrice(0));
                    $('#itemCount').text(0);
                    $('#summaryTotal').text(formatPrice(0));
                    $('#discount').text(formatPrice(0));
                    $('#orderTax').text(formatPrice(0));
                    $('#shippingAmount').text(formatPrice(parseFloat($('#shippingValue').val()) || 0));
                    $('.product-item').removeClass('is-selected');
                } else {
                    let subtotal = 0;
                    let itemCount = 0;
                    let cartHtml = '';
                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        itemCount += item.quantity;
                        const skuHtml = item.sku ? `<div class=\"sku-tag\">SKU: ${String(item.sku).replace(/</g,'&lt;')}</div>` : '';
                        const thumbHtml = item.image
                            ? `<img class=\"cart-item-thumb\" src=\"${String(item.image).replace(/\"/g,'&quot;')}\" alt=\"\">`
                            : `<span class=\"cart-item-thumb placeholder\"><i class=\"bi bi-box-seam\"></i></span>`;
                        cartHtml += `
                            <div class=\"cart-item d-flex justify-content-between align-items-center flex-wrap\">
                                <div class=\"left me-2 d-flex align-items-center gap-2 flex-grow-1\" style=\"min-width:160px\">
                                    ${thumbHtml}
                                    <div>
                                        <div class=\"item-name fw-semibold\">${item.name}</div>
                                        ${skuHtml}
                                        <div class=\"text-muted small price-qty\">${formatPrice(item.price)} × ${item.quantity}</div>
                                    </div>
                                </div>
                                <div class=\"qty-controls\">
                                    <div class=\"btn-group btn-group-sm\" role=\"group\" aria-label=\"Quantity controls\">
                                        <button type=\"button\" class=\"btn btn-outline-secondary dec-qty\" data-index=\"${index}\" title=\"Decrease\">-</button>
                                        <input type=\"number\" class=\"form-control form-control-sm qty-input qty-value\" data-index=\"${index}\" value=\"${item.quantity}\" min=\"1\" max=\"${item.stock || ''}\" inputmode=\"numeric\" aria-label=\"Quantity\">
                                        <button type=\"button\" class=\"btn btn-outline-secondary inc-qty\" data-index=\"${index}\" title=\"Increase\">+</button>
                                    </div>
                                </div>
                                <div class=\"right text-end\">
                                    <div class="fw-semibold item-total">${formatPrice(itemTotal)}</div>
                                    <div class="cart-item-actions">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dup-item" data-index="${index}" title="Duplicate"><i class="bi bi-copy"></i></button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm note-item" data-index="${index}" title="Line note"><i class="bi bi-sticky"></i></button>
                                        <a href="#" class="btn btn-outline-danger btn-sm remove-item" data-index="${index}" title="Remove"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
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
            $(document).on('click', '.remove-item', function(e) {
                e.preventDefault();
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
            });

            // Duplicate line (frontend cart only)
            $(document).on('click', '.dup-item', function(e) {
                e.preventDefault();
                const index = parseInt($(this).data('index'), 10);
                const item = cart[index];
                if (!item) return;
                if (item.stock && item.quantity >= item.stock) {
                    alert('Cannot duplicate — stock limit reached.');
                    return;
                }
                const copy = Object.assign({}, item, { quantity: 1, note: item.note || '' });
                cart.splice(index + 1, 0, copy);
                updateCart();
            });

            // Line note (stored on cart item; appended into sellNote at checkout)
            $(document).on('click', '.note-item', function(e) {
                e.preventDefault();
                const index = parseInt($(this).data('index'), 10);
                const item = cart[index];
                if (!item) return;
                const note = prompt('Line note for ' + item.name + ':', item.note || '');
                if (note === null) return;
                item.note = String(note).trim();
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

            // Manual quantity input in cart
            $(document).on('change blur', '.qty-input', function() {
                const index = parseInt($(this).data('index'), 10);
                const item = cart[index];
                if (!item) return;
                let qty = parseInt($(this).val(), 10);
                if (isNaN(qty) || qty < 1) qty = 1;
                if (item.stock && qty > item.stock) {
                    qty = item.stock;
                    alert('Quantity limited to available stock (' + item.stock + ').');
                }
                item.quantity = qty;
                updateCart();
            });
            $(document).on('keydown', '.qty-input', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $(this).trigger('blur');
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
                if (cart.length === 0) { alert('Cart is empty.'); return; }
                $('#cardPaymentModal').modal('show');
            });
            $('#finalizeCardPayment').on('click', function(){
                $('#cardPaymentModal').modal('hide');
                openCheckout('Card');
            });
            $('#checkoutBtnBottom').on('click', function(){ openCheckout('Cash'); });
            $('#upiBtn, #qrPayBtn').on('click', function(){ openCheckout('UPI'); });
            $('#splitBtn, #walletPayBtn, #transferPayBtn').on('click', function(){ openCheckout('Other'); });
            $('#creditBtn').on('click', function(){ openCheckout('Other'); });
            $('#bottomCancel').on('click', function(){ $('#clearCartBtn').trigger('click'); });
            $('#posHoldQuickBtn').on('click', function(){ $('#suspendBtn').trigger('click'); });

            // Hold / draft / quotation — frontend localStorage (no backend change)
            function saveCartSnapshot(key, label) {
                if (cart.length === 0) { alert('Cart is empty.'); return; }
                try {
                    localStorage.setItem(key, JSON.stringify({
                        cart: cart,
                        discountType: $('#discountType').val(),
                        discountValue: $('#discountValue').val(),
                        manualTaxType: $('#manualTaxType').val(),
                        manualTaxValue: $('#manualTaxValue').val(),
                        shippingValue: $('#shippingValue').val(),
                        orderType: $('input[name="posOrderType"]:checked').val() || 'dine_in',
                        tableNo: $('#posTableNo').val() || '',
                        customerNotes: $('#posCustomerNotes').val() || '',
                        savedAt: new Date().toISOString()
                    }));
                    alert(label + ' saved on this device.');
                } catch (err) {
                    alert('Could not save ' + label.toLowerCase() + '.');
                }
            }
            function loadCartSnapshot(key, label) {
                try {
                    const raw = localStorage.getItem(key);
                    if (!raw) { alert('No ' + label.toLowerCase() + ' found on this device.'); return; }
                    const data = JSON.parse(raw);
                    if (!data || !Array.isArray(data.cart) || data.cart.length === 0) {
                        alert('No ' + label.toLowerCase() + ' found on this device.');
                        return;
                    }
                    cart = data.cart;
                    if (data.discountType) $('#discountType').val(data.discountType);
                    if (typeof data.discountValue !== 'undefined') $('#discountValue').val(data.discountValue);
                    if (data.manualTaxType) $('#manualTaxType').val(data.manualTaxType);
                    if (typeof data.manualTaxValue !== 'undefined') $('#manualTaxValue').val(data.manualTaxValue);
                    if (typeof data.shippingValue !== 'undefined') $('#shippingValue').val(data.shippingValue);
                    if (data.orderType) {
                        $('input[name="posOrderType"][value="' + data.orderType + '"]').prop('checked', true);
                    }
                    if (typeof data.tableNo !== 'undefined') $('#posTableNo').val(data.tableNo);
                    if (typeof data.customerNotes !== 'undefined') $('#posCustomerNotes').val(data.customerNotes);
                    updateCart();
                    alert(label + ' restored.');
                } catch (err) {
                    alert('Could not restore ' + label.toLowerCase() + '.');
                }
            }
            $('#suspendBtn').on('click', function(){ saveCartSnapshot('pos_suspended_cart', 'Held bill'); });
            $('#draftBtn').on('click', function(){ saveCartSnapshot('pos_draft_cart', 'Draft'); });
            $('#quotationBtn').on('click', function(){ saveCartSnapshot('pos_quotation_cart', 'Quotation'); });
            $('#resumeBtn').on('click', function(){ loadCartSnapshot('pos_suspended_cart', 'Held bill'); });

            // Keyboard shortcuts (UI only) — keep existing F2/F4/Esc behavior and extend
            $(document).on('keydown', function(e) {
                const isInput = e.target && /INPUT|TEXTAREA|SELECT/.test(e.target.tagName);
                const allowFn = /^F\d+$/.test(e.key) || e.key === 'Escape' || ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S' || e.key === 'p' || e.key === 'P'));
                if (isInput && !allowFn) return;

                if (e.key === 'F2') {
                    e.preventDefault();
                    if (cart.length > 0 && !confirm('Start a new sale? Current cart will be cleared.')) return;
                    cart = [];
                    updateCart();
                    $('#searchProduct').trigger('focus').trigger('select');
                } else if (e.key === 'F3') {
                    e.preventDefault();
                    $('#modeRegistered').prop('checked', true);
                    $('#registeredOverlay').addClass('show');
                    $('#customerSearch').trigger('focus');
                } else if (e.key === 'F4') {
                    e.preventDefault();
                    $('#suspendBtn').trigger('click');
                } else if (e.key === 'F5') {
                    e.preventDefault();
                    if (!$('#checkoutBtnBottom').prop('disabled')) openCheckout($('#paymentMethod').val() || 'Cash');
                } else if (e.key === 'F6') {
                    e.preventDefault();
                    $('#searchProduct').trigger('focus').trigger('select');
                } else if (e.key === 'F7') {
                    e.preventDefault();
                    $('#billSearch').trigger('focus').trigger('select');
                } else if (e.key === 'F8') {
                    e.preventDefault();
                    $('#editDiscount').trigger('click');
                } else if (e.key === 'F9') {
                    e.preventDefault();
                    if (!$('#checkoutBtnBottom').prop('disabled')) {
                        $('#checkoutBtnBottom').trigger('click');
                    }
                } else if (e.key === 'F10') {
                    e.preventDefault();
                    if (typeof lastOrderId !== 'undefined' && lastOrderId) {
                        $('#postSalePrint').trigger('click');
                    } else {
                        window.print();
                    }
                } else if (e.key === 'Escape') {
                    $('.customer-overlay-backdrop.show').removeClass('show');
                } else if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
                    e.preventDefault();
                    $('#draftBtn').trigger('click');
                } else if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 'P')) {
                    e.preventDefault();
                    if (typeof lastOrderId !== 'undefined' && lastOrderId) {
                        $('#postSalePrint').trigger('click');
                    }
                }
            });

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
                // Sync UI-only order meta into existing sellNote (backend unchanged)
                const orderType = ($('input[name="posOrderType"]:checked').val() || 'dine_in').replace(/_/g, ' ');
                const tableNo = ($('#posTableNo').val() || '').trim();
                const custNotes = ($('#posCustomerNotes').val() || '').trim();
                const lineNotes = cart.filter(function(it){ return it.note; }).map(function(it){ return it.name + ': ' + it.note; }).join('; ');
                const metaParts = [];
                metaParts.push('Order: ' + orderType);
                if (tableNo) metaParts.push('Table: ' + tableNo);
                if (custNotes) metaParts.push(custNotes);
                if (lineNotes) metaParts.push(lineNotes);
                const existingSell = ($('#sellNote').val() || '').trim();
                if (!existingSell || existingSell.indexOf('Order:') === 0) {
                    $('#sellNote').val(metaParts.join(' · '));
                }
                $('#posPaymentChips .btn').removeClass('active');
                $('#posPaymentChips .btn[data-pay="' + ($('#paymentMethod').val() || 'Cash') + '"]').addClass('active');
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
            $('#postSaleWhatsApp').on('click', function(){
                if (!lastOrderId) return;
                const receiptUrl = '<?php echo BASE_URL; ?>?controller=pos&action=receipt&param=' + lastOrderId;
                const text = encodeURIComponent('Your receipt: ' + receiptUrl);
                window.open('https://wa.me/?text=' + text, '_blank');
            });
            $('#postSaleSms').on('click', function(){
                if (!lastOrderId) return;
                const receiptUrl = '<?php echo BASE_URL; ?>?controller=pos&action=receipt&param=' + lastOrderId;
                window.location.href = 'sms:?body=' + encodeURIComponent('Your receipt: ' + receiptUrl);
            });

            // Payment chips (map to existing #paymentMethod values only)
            $(document).on('click', '#posPaymentChips .btn', function(){
                const pay = $(this).data('pay');
                $('#posPaymentChips .btn').removeClass('active');
                $(this).addClass('active');
                if (pay) $('#paymentMethod').val(pay);
            });
            $('#paymentMethod').on('change', function(){
                const v = $(this).val();
                $('#posPaymentChips .btn').removeClass('active');
                $('#posPaymentChips .btn[data-pay="' + v + '"]').addClass('active');
            });
        });
    </script>
    <script>
    // UI-only helpers (do not alter cart/payment logic)
    (function () {
        var themeBtn = document.getElementById('posThemeToggle');
        function applyTheme(next) {
            document.documentElement.setAttribute('data-theme', next);
            try { localStorage.setItem('pos_theme', next); } catch (e) {}
            if (themeBtn) {
                var icon = themeBtn.querySelector('i');
                if (icon) icon.className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
            var themeSel = document.getElementById('posSettingTheme');
            if (themeSel) themeSel.value = next;
        }
        if (themeBtn) {
            themeBtn.addEventListener('click', function () {
                var cur = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                applyTheme(cur === 'dark' ? 'light' : 'dark');
            });
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                applyTheme('dark');
            }
        }
        var fsBtn = document.getElementById('posFullscreenBtn');
        if (fsBtn) {
            fsBtn.addEventListener('click', function () {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen && document.documentElement.requestFullscreen();
                } else if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            });
        }
        var focusSearch = document.getElementById('posFocusSearch');
        if (focusSearch) {
            focusSearch.addEventListener('click', function () {
                var input = document.getElementById('searchProduct');
                if (input) { input.focus(); input.select(); }
            });
        }

        // Favorites (localStorage)
        var favKey = 'pos_favorite_products';
        function getFavs() {
            try { return JSON.parse(localStorage.getItem(favKey) || '[]') || []; } catch (e) { return []; }
        }
        function setFavs(arr) {
            try { localStorage.setItem(favKey, JSON.stringify(arr)); } catch (e) {}
        }
        function paintFavs() {
            var favs = getFavs();
            document.querySelectorAll('.product-item').forEach(function (el) {
                var id = String(el.getAttribute('data-id') || '');
                var on = favs.indexOf(id) !== -1;
                el.classList.toggle('is-favorite', on);
                var btn = el.querySelector('.fav-btn i');
                if (btn) btn.className = on ? 'bi bi-star-fill' : 'bi bi-star';
            });
        }
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.fav-btn');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            var id = String(btn.getAttribute('data-fav-id') || '');
            if (!id) return;
            var favs = getFavs();
            var ix = favs.indexOf(id);
            if (ix === -1) favs.push(id); else favs.splice(ix, 1);
            setFavs(favs);
            paintFavs();
        });
        paintFavs();

        // Voice search (Web Speech API when available)
        var voiceBtn = document.getElementById('posVoiceSearchBtn');
        if (voiceBtn) {
            voiceBtn.addEventListener('click', function () {
                var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                if (!SR) { alert('Voice search is not supported in this browser.'); return; }
                var rec = new SR();
                rec.lang = 'en-US';
                rec.onresult = function (ev) {
                    var text = ev.results[0][0].transcript || '';
                    var input = document.getElementById('searchProduct');
                    if (input) {
                        input.value = text;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        input.focus();
                    }
                };
                rec.start();
            });
        }

        // Calculator
        var display = document.getElementById('posCalcDisplay');
        var expr = '';
        var pad = document.getElementById('posCalcPad');
        if (pad && display) {
            pad.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-calc]');
                if (!btn) return;
                var v = btn.getAttribute('data-calc');
                if (v === 'C') { expr = ''; display.value = '0'; return; }
                if (v === '=') {
                    try {
                        if (!/^[\d.\s+\-*/]+$/.test(expr)) throw new Error('bad');
                        // eslint-disable-next-line no-new-func
                        var result = Function('"use strict"; return (' + expr + ')')();
                        display.value = String(result);
                        expr = String(result);
                    } catch (err) {
                        display.value = 'Error';
                        expr = '';
                    }
                    return;
                }
                expr += v;
                display.value = expr;
            });
        }

        // Settings prefs
        try {
            var receipt = localStorage.getItem('pos_receipt_pref');
            var sound = localStorage.getItem('pos_scan_sound');
            var shortcuts = localStorage.getItem('pos_show_shortcuts');
            var rs = document.getElementById('posSettingReceipt');
            var ss = document.getElementById('posSettingSound');
            var sh = document.getElementById('posSettingShortcuts');
            var ts = document.getElementById('posSettingTheme');
            if (rs && receipt) rs.value = receipt;
            if (ss && sound !== null) ss.checked = sound !== '0';
            if (sh && shortcuts !== null) sh.checked = shortcuts !== '0';
            if (ts) ts.value = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            var bar = document.querySelector('.pos-shortcut-bar');
            if (bar && shortcuts === '0') bar.classList.add('d-none');
            if (ts) ts.addEventListener('change', function () { applyTheme(ts.value); });
            if (rs) rs.addEventListener('change', function () { localStorage.setItem('pos_receipt_pref', rs.value); });
            if (ss) ss.addEventListener('change', function () { localStorage.setItem('pos_scan_sound', ss.checked ? '1' : '0'); });
            if (sh) sh.addEventListener('change', function () {
                localStorage.setItem('pos_show_shortcuts', sh.checked ? '1' : '0');
                if (bar) bar.classList.toggle('d-none', !sh.checked);
            });
        } catch (e) {}

        // Branch preference
        var branch = document.getElementById('posBranchSelect');
        if (branch) {
            try {
                var saved = localStorage.getItem('pos_branch');
                if (saved) branch.value = saved;
            } catch (e) {}
            branch.addEventListener('change', function () {
                try { localStorage.setItem('pos_branch', branch.value); } catch (e) {}
            });
        }

        // Mobile panel switcher
        function showPanel(which) {
            var cartCol = document.querySelector('.bill-card') && document.querySelector('.bill-card').closest('.col-12');
            var prodCol = document.querySelector('.products-card') && document.querySelector('.products-card').closest('.col-12');
            if (!cartCol || !prodCol) return;
            if (window.innerWidth > 991) {
                cartCol.style.display = '';
                prodCol.style.display = '';
                return;
            }
            if (which === 'products') {
                cartCol.style.display = 'none';
                prodCol.style.display = '';
            } else {
                cartCol.style.display = '';
                prodCol.style.display = 'none';
            }
            document.querySelectorAll('.pos-mobile-nav .nav-item-btn').forEach(function (b) {
                b.classList.toggle('active', b.getAttribute('data-pos-panel') === which);
            });
        }
        var mobCart = document.getElementById('posMobCart');
        var mobProd = document.getElementById('posMobProducts');
        var mobPay = document.getElementById('posMobPay');
        if (mobCart) mobCart.addEventListener('click', function () { showPanel('cart'); });
        if (mobProd) mobProd.addEventListener('click', function () { showPanel('products'); });
        if (mobPay) mobPay.addEventListener('click', function () {
            var cash = document.getElementById('checkoutBtnBottom');
            if (cash && !cash.disabled) cash.click();
        });
        if (window.innerWidth <= 991) showPanel('cart');
        window.addEventListener('resize', function () {
            if (window.innerWidth > 991) showPanel('cart');
        });
    })();
    </script>
</body>
</html>
