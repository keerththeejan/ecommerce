/**
 * Main JavaScript for E-Commerce Store
 */

$(document).ready(function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        $('#msg-flash').fadeOut('slow');
    }, 5000);

    // Product quantity increment/decrement
    $('.quantity-control').on('click', function() {
        const input = $(this).siblings('.quantity-input');
        const currentValue = parseInt(input.val());
        const maxValue = parseInt(input.attr('max'));
        
        if ($(this).hasClass('quantity-increment') && (currentValue < maxValue)) {
            input.val(currentValue + 1);
        } else if ($(this).hasClass('quantity-decrement') && currentValue > 1) {
            input.val(currentValue - 1);
        }
    });

    // Add to cart AJAX
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        const quantity = $('#product-quantity').length ? $('#product-quantity').val() : 1;
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.cartCount);
                    
                    // Show success message
                    const toast = `
                        <div class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="fas fa-check-circle me-2"></i> Product added to cart
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    `;
                    
                    $('body').append(toast);
                    const toastEl = document.querySelector('.toast');
                    const toastInstance = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toastInstance.show();
                    
                    // Remove toast after it's hidden
                    toastEl.addEventListener('hidden.bs.toast', function() {
                        $(this).remove();
                    });
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Add to cart via FORM (prevents full page refresh)
    // Handles forms like the product card in `app/views/customer/home/index.php`
    $(document).on('submit', 'form[action*="controller=cart&action=add"]', function(e) {
        e.preventDefault();

        const $form = $(this);
        const url = $form.attr('action');
        const formData = $form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update cart count if provided; otherwise fetch
                    if (typeof response.cartCount !== 'undefined') {
                        $('.cart-count').text(response.cartCount);
                    } else if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }

                    // Show success toast
                    const toast = `
                        <div class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="fas fa-check-circle me-2"></i> Product added to cart
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    `;
                    $('body').append(toast);
                    const toastEl = document.querySelector('.toast');
                    const toastInstance = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toastInstance.show();
                    toastEl.addEventListener('hidden.bs.toast', function() { $(this).remove(); });
                } else {
                    alert('Failed to add product to cart.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Update cart quantity AJAX
    $('.update-cart-quantity').on('change', function() {
        const cartId = $(this).data('cart-id');
        const quantity = $(this).val();
        
        $.ajax({
            url: baseUrl + '?controller=cart&action=update',
            type: 'POST',
            data: {
                cart_id: cartId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Reload page to update totals
                    location.reload();
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Remove from cart AJAX
    $('.remove-from-cart').on('click', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            const cartId = $(this).data('cart-id');
            
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                data: {
                    cart_id: cartId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Reload page to update cart
                        location.reload();
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    // Same shipping and billing address checkbox
    $('#same_address').on('change', function() {
        if ($(this).is(':checked')) {
            $('#shipping_address_container').hide();
        } else {
            $('#shipping_address_container').show();
        }
    });

    // Payment method selection
    $('input[name="payment_method"]').on('change', function() {
        const paymentMethod = $(this).val();
        
        $('.payment-details').hide();
        $('#' + paymentMethod + '_details').show();
    });

    // Product image gallery
    $('.product-thumbnail').on('click', function() {
        const imageSrc = $(this).data('image');
        $('#main-product-image').attr('src', imageSrc);
    });

    // Product category filter
    $('.category-filter').on('click', function(e) {
        e.preventDefault();
        
        const categoryId = $(this).data('category');
        
        // Add active class
        $('.category-filter').removeClass('active');
        $(this).addClass('active');
        
        // Filter products
        if (categoryId === 'all') {
            $('.product-item').show();
        } else {
            $('.product-item').hide();
            $('.product-item[data-category="' + categoryId + '"]').show();
        }
    });

    // Product search form
    $('#product-search-form').on('submit', function(e) {
        const searchTerm = $('#search-input').val().trim();
        
        if (searchTerm === '') {
            e.preventDefault();
        }
    });

    // Order tracking form
    $('#order-tracking-form').on('submit', function(e) {
        e.preventDefault();
        
        const orderId = $('#order-id').val().trim();
        const email = $('#order-email').val().trim();
        
        if (orderId !== '' && email !== '') {
            $.ajax({
                url: baseUrl + '?controller=order&action=track',
                type: 'POST',
                data: {
                    order_id: orderId,
                    email: email
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#tracking-result').html(`
                            <div class="alert alert-success">
                                <h5>Order #${response.order.id}</h5>
                                <p><strong>Status:</strong> ${response.order.status}</p>
                                <p><strong>Date:</strong> ${response.order.created_at}</p>
                                <p><strong>Shipping Address:</strong> ${response.order.shipping_address}</p>
                            </div>
                        `);
                    } else {
                        $('#tracking-result').html(`
                            <div class="alert alert-danger">
                                ${response.message}
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#tracking-result').html(`
                        <div class="alert alert-danger">
                            An error occurred. Please try again.
                        </div>
                    `);
                }
            });
        }
    });

    // Newsletter subscription
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#newsletter-email').val().trim();
        
        if (email !== '') {
            $.ajax({
                url: baseUrl + '?controller=newsletter&action=subscribe',
                type: 'POST',
                data: {
                    email: email
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#newsletter-form').html(`
                            <div class="alert alert-success">
                                Thank you for subscribing to our newsletter!
                            </div>
                        `);
                    } else {
                        $('#newsletter-result').html(`
                            <div class="alert alert-danger">
                                ${response.message}
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#newsletter-result').html(`
                        <div class="alert alert-danger">
                            An error occurred. Please try again.
                        </div>
                    `);
                }
            });
        }
    });

    // Fetch cart count on page load
    function updateCartCount() {
        $.ajax({
            url: baseUrl + '?controller=cart&action=count',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('.cart-count').text(response.count);
            }
        });
    }

    // Call the function to update cart count
    updateCartCount();
});
