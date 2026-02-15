/**
 * Main JavaScript for E-Commerce Store
 */

$(document).ready(function() {
    // Wait for jQuery to be fully loaded
    if (typeof $ === 'undefined' || typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded! Add to cart functionality will not work.');
        return;
    }
    
    console.log('✅ main.js loaded - Add to cart handlers will be attached');
    
    // Navbar dropdown search - filter options as user types
    document.querySelectorAll('.nav-dropdown-search').forEach(function(input) {
        input.addEventListener('input', function() {
            var query = this.value.trim().toLowerCase();
            var dropdown = this.closest('.nav-dropdown-searchable');
            if (!dropdown) return;
            dropdown.querySelectorAll('.nav-dropdown-item').forEach(function(item) {
                var text = (item.getAttribute('data-search-text') || '').toLowerCase();
                item.closest('li').style.display = (query === '' || text.indexOf(query) !== -1) ? '' : 'none';
            });
        });
        input.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    // Reset search and show all items when dropdown closes
    document.querySelectorAll('.nav-dropdown-searchable').forEach(function(menu) {
        var dropdownEl = menu.closest('.dropdown');
        if (dropdownEl) {
            dropdownEl.addEventListener('hidden.bs.dropdown', function() {
                var searchInput = menu.querySelector('.nav-dropdown-search');
                if (searchInput) {
                    searchInput.value = '';
                    menu.querySelectorAll('.nav-dropdown-item').forEach(function(item) {
                        var li = item.closest('li');
                        if (li) li.style.display = '';
                    });
                }
            });
        }
    });

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
    // Primary handler - intercepts form submission
    $(document).on('submit', '.add-to-cart-form, form[action*="controller=cart"][action*="action=add"], form[action*="cart&action=add"]', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $form = $(this);
        let url = $form.attr('action');
        
        // Validate form has required fields
        const productId = $form.find('input[name="product_id"]').val();
        if (!productId) {
            console.error('Add to cart: Product ID is missing!');
            alert('Error: Product ID is missing. Please refresh the page and try again.');
            return false;
        }
        
        // Fallback: construct URL from baseUrl if needed
        if (!url && window.baseUrl) {
            url = window.baseUrl + '?controller=cart&action=add';
        }
        
        // Ensure quantity input is included even if readonly
        const quantityInput = $form.find('.quantity-input, input[name="quantity"]');
        let quantity = 1;
        
        if (quantityInput.length) {
            if (quantityInput.attr('readonly')) {
                // Temporarily remove readonly for serialization
                quantityInput.removeAttr('readonly');
            }
            quantity = quantityInput.val() || 1;
        }
        
        const formData = $form.serialize();
        
        // Restore readonly if it was there
        if (quantityInput.length && quantityInput.attr('readonly') === undefined) {
            quantityInput.attr('readonly', 'readonly');
        }
        
        // Find submit button
        const $submitBtn = $form.find('.add-to-cart-btn, .btn-add-to-cart, button[type="submit"]');
        
        // Debug logging
        console.log('Add to cart form submitted:', {
            url: url,
            formData: formData,
            productId: productId,
            quantity: quantity,
            formClass: $form.attr('class'),
            buttonClass: $submitBtn.attr('class'),
            formExists: $form.length > 0,
            buttonExists: $submitBtn.length > 0
        });
        
        // Disable button to prevent double submission
        const originalBtnHtml = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response, textStatus, jqXHR) {
                console.log('Add to cart AJAX success:', {
                    response: response,
                    status: textStatus,
                    statusCode: jqXHR.status
                });
                
                // Handle redirect if needed (e.g., login required)
                if (response.redirect) {
                    window.location.href = response.redirect;
                    return;
                }
                
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
                                    <i class="fas fa-check-circle me-2"></i> ${response.message || 'Product added to cart'}
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
                    alert(response.message || 'Failed to add product to cart.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Add to cart AJAX error:', {
                    status: status,
                    error: error,
                    statusCode: xhr.status,
                    responseText: xhr.responseText.substring(0, 200),
                    readyState: xhr.readyState
                });
                
                // Try to parse error message if available
                try {
                    if (xhr.responseText) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            alert(response.message);
                        } else {
                            alert('Failed to add product to cart. Please try again.');
                        }
                    } else {
                        alert('No response from server. Please check your connection and try again.');
                    }
                } catch(e) {
                    console.error('Failed to parse error response:', e);
                    // If JSON parse fails, server might have returned HTML (redirect)
                    if (xhr.status === 0) {
                        alert('Network error. Please check your internet connection.');
                    } else if (xhr.status >= 500) {
                        alert('Server error. Please try again later.');
                    } else {
                        alert('An error occurred. Please refresh the page and try again. Status: ' + xhr.status);
                    }
                }
            },
            complete: function() {
                // Re-enable button after request completes
                $submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
        
        return false; // Prevent form submission
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
