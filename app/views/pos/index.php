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
            padding: 10px 0;
        }
        .cart-container {
            height: calc(100vh - 300px);
            overflow-y: auto;
        }
        .category-filter {
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
        }
        .category-filter .btn {
            margin-right: 5px;
        }
        .product-grid {
            height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                <div class="d-flex">
                    <div class="dropdown">
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
            <!-- Products Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Products</h5>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
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

                        <!-- Product Grid -->
                        <div class="product-grid">
                            <div class="row" id="productContainer">
                                <?php foreach($products as $product) : ?>
                                    <div class="col-md-3 mb-3 product-item" data-category="<?php echo $product['category_id']; ?>" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>" data-price="<?php echo $product['sale_price'] ?? $product['price']; ?>" data-stock="<?php echo $product['stock_quantity']; ?>">
                                        <div class="card h-100">
                                            <?php if(!empty($product['image'])) : ?>
                                                <img src="<?php echo BASE_URL . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 100px; object-fit: cover;">
                                            <?php else : ?>
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body text-center">
                                                <h6 class="card-title"><?php echo $product['name']; ?></h6>
                                                <?php 
                                                    $basePrice = isset($product['sale_price']) && $product['sale_price'] > 0 
                                                        ? (float)$product['sale_price'] 
                                                        : (float)$product['price'];
                                                    $inclTax = $basePrice * 1.10; // match 10% tax used in cart
                                                ?>
                                                <div class="small text-muted">purches Price: <?php echo formatPrice((float)$product['price']); ?></div>
                                                <div class="small <?php echo (isset($product['sale_price']) && $product['sale_price'] > 0) ? 'text-danger fw-semibold' : 'text-muted'; ?>">
                                                    Sale Price: <?php echo (isset($product['sale_price']) && $product['sale_price'] > 0) ? formatPrice((float)$product['sale_price']) : 'N/A'; ?>
                                                </div>
                                                <div class="fw-bold">Incl. Tax (10%): <?php echo formatPrice($inclTax); ?></div>
                                                <small class="text-muted d-block mt-1">Stock: <?php echo (int)$product['stock_quantity']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Bill & Invoice</h5>
                    </div>
                    <div class="card-body">
                        <div class="cart-container mb-3" id="cartItems">
                            <!-- Cart items will be added here dynamically -->
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p>No items in cart</p>
                            </div>
                        </div>

                        <div class="cart-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">CHF0.00</span>
                            </div>
                            <div class="row g-2 align-items-end mb-2">
                                <div class="col-5">
                                    <label class="form-label mb-1">Discount</label>
                                    <select id="discountType" class="form-select form-select-sm">
                                        <option value="none" selected>None</option>
                                        <option value="percent">% Percent</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label mb-1">Value</label>
                                    <input type="number" class="form-control form-control-sm" id="discountValue" min="0" step="0.01" value="0" disabled>
                                </div>
                                <div class="col-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="applyTax" checked>
                                        <label class="form-check-label" for="applyTax">Tax</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span id="discount">CHF0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (category-wise):</span>
                                <span id="tax">CHF0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold" id="total">CHF0.00</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Customer (Optional)</label>
                            <div class="btn-group w-100 mb-2" role="group" aria-label="Customer mode">
                                <input type="radio" class="btn-check" name="customerMode" id="modeRegistered" value="registered" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="modeRegistered">Registered</label>
                                <input type="radio" class="btn-check" name="customerMode" id="modeManual" value="manual" autocomplete="off">
                                <label class="btn btn-outline-primary" for="modeManual">Manual</label>
                            </div>

                            <!-- Registered customer UI -->
                            <div id="registeredCustomerBox">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customerSearch" placeholder="Search customer by name, email, phone...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearCustomer">Clear</button>
                                </div>
                                <input type="hidden" id="customerId" value="">
                                <div id="customerInfo" class="mt-2"></div>
                            </div>

                            <!-- Manual customer UI -->
                            <div id="manualCustomerBox" class="d-none">
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
                        </div>

                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-success" id="checkoutBtn" disabled>
                                <i class="fas fa-cash-register me-2"></i> Checkout
                            </button>
                            <button class="btn btn-danger" id="clearCartBtn" disabled>
                                <i class="fas fa-trash me-2"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Quantity Modal -->
    <div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quantityModalLabel">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="productQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="productQuantity" min="1" value="1">
                        <div class="form-text">Available stock: <span id="availableStock">0</span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="addToCartBtn">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Complete Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amountTendered" class="form-label">Amount Tendered</label>
                        <input type="number" class="form-control" id="amountTendered" min="0" step="0.01">
                        <div class="form-text">Total amount: <span id="modalTotal">CHF0.00</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="saleNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="saleNotes" rows="2"></textarea>
                    </div>
                    <div id="changeAmount" class="alert alert-success d-none">
                        Change: <span id="changeValue">CHF0.00</span>
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
            let cart = [];
            let selectedProduct = null;
            let customers = [];

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

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
                        categoryId: selectedProduct.categoryId
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
                } else {
                    let subtotal = 0;
                    let cartHtml = '';
                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        cartHtml += `
                            <div class="cart-item d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">${item.name}</div>
                                    <div class="text-muted small">${formatPrice(item.price)} x ${item.quantity}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">${formatPrice(itemTotal)}</div>
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

                    const total = taxableBase + tax;

                    $('#subtotal').text(formatPrice(subtotal));
                    $('#discount').text('- ' + formatPrice(discountAmt));
                    $('#tax').text(formatPrice(tax));
                    $('#total').text(formatPrice(total));
                    $('#modalTotal').text(formatPrice(total));
                    
                    $('#checkoutBtn, #clearCartBtn').prop('disabled', false);
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

            // Clear cart
            $('#clearCartBtn').on('click', function() {
                if (confirm('Are you sure you want to clear the cart?')) {
                    cart = [];
                    updateCart();
                }
            });

            // Customer search
            $('#customerSearch').on('input', function() {
                const searchTerm = $(this).val();
                
                if (searchTerm.length < 2) {
                    return;
                }
                
                $.ajax({
                    url: '<?php echo BASE_URL; ?>?controller=pos&action=searchCustomers',
                    type: 'GET',
                    data: { keyword: searchTerm },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            customers = response.customers;
                            
                            if (customers.length > 0) {
                                let customersHtml = '<div class="list-group mt-2">';
                                
                                customers.forEach(customer => {
                                    customersHtml += `
                                        <a href="#" class="list-group-item list-group-item-action select-customer" data-id="${customer.id}">
                                            ${customer.first_name} ${customer.last_name} (${customer.email})
                                        </a>
                                    `;
                                });
                                
                                customersHtml += '</div>';
                                $('#customerInfo').html(customersHtml);
                            } else {
                                $('#customerInfo').html('<div class="alert alert-info mt-2">No customers found</div>');
                            }
                        }
                    }
                });
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
            });

            // Clear customer
            $('#clearCustomer').on('click', function() {
                $('#customerId').val('');
                $('#customerSearch').val('');
                $('#customerInfo').html('');
            });

            // Customer mode toggle (Registered vs Manual)
            $('input[name="customerMode"]').on('change', function() {
                const mode = $(this).val();
                if (mode === 'registered') {
                    $('#registeredCustomerBox').removeClass('d-none');
                    $('#manualCustomerBox').addClass('d-none');
                    // clear manual fields
                    $('#manualName, #manualPhone, #manualEmail').val('');
                } else {
                    $('#registeredCustomerBox').addClass('d-none');
                    $('#manualCustomerBox').removeClass('d-none');
                    // clear registered selection
                    $('#customerId').val('');
                    $('#customerSearch').val('');
                    $('#customerInfo').html('');
                }
            });

            // Checkout button
            $('#checkoutBtn').on('click', function() {
                if (cart.length === 0) {
                    alert('Cart is empty.');
                    return;
                }
                
                const total = parsePrice($('#total').text());
                $('#amountTendered').val(total);
                $('#changeAmount').addClass('d-none');
                $('#checkoutModal').modal('show');
            });

            // Calculate change
            $('#amountTendered').on('input', function() {
                const amountTendered = parseFloat($(this).val());
                const total = parsePrice($('#total').text());
                
                if (amountTendered >= total) {
                    const change = amountTendered - total;
                    $('#changeValue').text(formatPrice(change));
                    $('#changeAmount').removeClass('d-none');
                } else {
                    $('#changeAmount').addClass('d-none');
                }
            });

            // Complete sale
            $('#completeSaleBtn').on('click', function() {
                const amountTendered = parseFloat($('#amountTendered').val());
                const total = parsePrice($('#total').text());
                const paymentMethod = $('#paymentMethod').val();
                const customerMode = $('input[name="customerMode"]:checked').val();
                const customerId = $('#customerId').val();
                const manualName = $('#manualName').val();
                const manualPhone = $('#manualPhone').val();
                const manualEmail = $('#manualEmail').val();
                const notes = $('#saleNotes').val();
                
                if (amountTendered < total) {
                    alert('Amount tendered must be greater than or equal to the total amount.');
                    return;
                }
                
                // Prepare data for submission
                const data = {
                    items: JSON.stringify(cart),
                    customer_mode: customerMode,
                    customer_id: customerId,
                    manual_name: manualName,
                    manual_phone: manualPhone,
                    manual_email: manualEmail,
                    payment_method: paymentMethod,
                    total_amount: total,
                    notes: notes
                };
                
                // Submit sale
                $.ajax({
                    url: '<?php echo BASE_URL; ?>?controller=pos&action=processSale',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Sale completed successfully!');
                            
                            // Redirect to receipt
                            window.location.href = '<?php echo BASE_URL; ?>?controller=pos&action=receipt&param=' + response.order_id;
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>
