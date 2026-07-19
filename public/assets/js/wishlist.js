/**
 * Wishlist module — live AJAX toggle, counter, move-to-cart, clear
 * Depends on: jQuery, Bootstrap Toast, window.baseUrl, window.isLoggedIn
 */
(function (window, $) {
    'use strict';

    if (typeof $ === 'undefined') {
        console.error('Wishlist: jQuery is required');
        return;
    }

    function base() {
        return window.baseUrl || '';
    }

    function apiUrl(action, productId) {
        var url = base() + '?controller=wishlist&action=' + encodeURIComponent(action) + '&ajax=1';
        if (productId) {
            url += '&id=' + encodeURIComponent(productId);
        }
        return url;
    }

    function updateWishlistCount(count) {
        var n = parseInt(count, 10);
        if (isNaN(n) || n < 0) n = 0;

        var $badges = $('.wishlist-count');
        if (!$badges.length && window.isLoggedIn) {
            // Ensure header wishlist links get a badge if missing
            $('a[href*="controller=wishlist"]').each(function () {
                var $a = $(this);
                if ($a.find('.wishlist-count').length) return;
                if (!$a.hasClass('position-relative') && !$a.hasClass('storefront-action-link') && !$a.hasClass('mobile-bottom-link')) {
                    return;
                }
                $a.addClass('position-relative');
                $a.append('<span class="badge rounded-pill bg-danger wishlist-count storefront-badge">' + n + '</span>');
            });
            $badges = $('.wishlist-count');
        }

        $badges.text(n);
        if (n <= 0) {
            $badges.addClass('d-none');
        } else {
            $badges.removeClass('d-none');
        }

        var $labelCount = $('#wishlistItemCount');
        if ($labelCount.length) {
            $labelCount.text(n);
        }
    }

    function updateCartCount(count) {
        if (typeof count === 'undefined') return;
        $('.cart-count').text(parseInt(count, 10) || 0);
    }

    function showWishlistToast(message, success) {
        var bg = success ? 'bg-success' : 'bg-danger';
        var icon = success ? 'fa-check-circle' : 'fa-exclamation-circle';
        var html =
            '<div class="toast align-items-center text-white ' + bg + ' border-0 position-fixed top-0 end-0 m-3 wishlist-toast" role="alert" aria-live="assertive" aria-atomic="true" style="z-index:1080;">' +
            '<div class="d-flex"><div class="toast-body"><i class="fas ' + icon + ' me-2"></i>' +
            $('<div>').text(message || '').html() +
            '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div></div>';

        $('body').append(html);
        var toastEl = document.querySelector('.wishlist-toast:last-of-type');
        if (!toastEl || typeof bootstrap === 'undefined') return;
        var toastInstance = new bootstrap.Toast(toastEl, { delay: 2800 });
        toastInstance.show();
        toastEl.addEventListener('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    function setWishlistButtonState($btn, inWishlist) {
        var $icon = $btn.find('i').first();
        $btn.toggleClass('active', !!inWishlist);
        $btn.attr('aria-pressed', inWishlist ? 'true' : 'false');
        $btn.attr('aria-label', inWishlist ? 'Remove from wishlist' : 'Add to wishlist');
        $btn.attr('title', inWishlist ? 'Remove from wishlist' : 'Add to wishlist');
        if ($icon.length) {
            $icon.toggleClass('fas', !!inWishlist);
            $icon.toggleClass('far', !inWishlist);
            $icon.addClass('fa-heart');
        }
        if ($btn.hasClass('btn-wishlist-pdp')) {
            $btn.find('.wishlist-pdp-label').text(inWishlist ? 'In Wishlist' : 'Add to Wishlist');
        }
        $btn.addClass('wishlist-pulse');
        setTimeout(function () { $btn.removeClass('wishlist-pulse'); }, 420);
    }

    function syncWishlistIds(productId, inWishlist, productIds) {
        if (Array.isArray(productIds)) {
            window.wishlistProductIds = productIds.map(Number);
            return;
        }
        window.wishlistProductIds = Array.isArray(window.wishlistProductIds) ? window.wishlistProductIds : [];
        var id = parseInt(productId, 10);
        var idx = window.wishlistProductIds.indexOf(id);
        if (inWishlist && idx === -1) {
            window.wishlistProductIds.push(id);
        } else if (!inWishlist && idx !== -1) {
            window.wishlistProductIds.splice(idx, 1);
        }
    }

    function markWishlistHeartsFromIds() {
        var ids = Array.isArray(window.wishlistProductIds) ? window.wishlistProductIds.map(Number) : [];
        $('.btn-wishlist, .btn-wishlist-pdp').each(function () {
            var $btn = $(this);
            // Skip remove-only controls on wishlist page (always "active")
            if ($btn.data('wishlist-remove') == 1 || $btn.hasClass('wishlist-remove-btn')) {
                return;
            }
            var pid = parseInt($btn.data('product-id'), 10);
            if (!pid) return;
            setWishlistButtonState($btn, ids.indexOf(pid) !== -1);
        });
    }

    function removeWishlistCard(productId) {
        var $card = $('[data-wishlist-item="' + productId + '"]');
        if (!$card.length) return;
        $card.fadeOut(200, function () {
            $(this).remove();
            if (!$('#wishlistGrid [data-wishlist-item]').length) {
                // Reload to show empty state cleanly
                window.location.href = base() + '?controller=wishlist';
            }
        });
    }

    function refreshWishlistState() {
        $.ajax({
            url: apiUrl('count'),
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (response) {
                if (!response) return;
                if (typeof response.count !== 'undefined') {
                    updateWishlistCount(response.count);
                }
                if (Array.isArray(response.product_ids)) {
                    window.wishlistProductIds = response.product_ids.map(Number);
                    markWishlistHeartsFromIds();
                }
            }
        });
    }

    function requireLoginRedirect() {
        var returnUrl = encodeURIComponent(window.location.href);
        var login = (window.loginUrl || (base() + '?controller=user&action=login')) + '&redirect=' + returnUrl;
        showWishlistToast('Please log in to manage your wishlist', false);
        setTimeout(function () { window.location.href = login; }, 500);
    }

    function wishlistRequest(action, productId, extraData) {
        return $.ajax({
            url: apiUrl(action, productId),
            type: 'POST',
            data: $.extend({ product_id: productId, ajax: 1 }, extraData || {}),
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
    }

    $(function () {
        markWishlistHeartsFromIds();
        if (window.isLoggedIn) {
            refreshWishlistState();
        }

        // Heart toggle / remove
        $(document).on('click', '.btn-wishlist, .btn-wishlist-pdp, .wishlist-remove-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            if ($btn.data('wishlistBusy')) return;

            var productId = parseInt($btn.data('product-id'), 10);
            if (!productId) return;

            if (!window.isLoggedIn) {
                requireLoginRedirect();
                return;
            }

            var removeOnly = $btn.is('.wishlist-remove-btn') || $btn.data('wishlist-remove') == 1;
            var action = removeOnly ? 'remove' : 'toggle';

            $btn.data('wishlistBusy', true).addClass('wishlist-loading');

            wishlistRequest(action, productId)
                .done(function (response) {
                    if (response && response.require_login && response.redirect) {
                        window.location.href = response.redirect;
                        return;
                    }
                    if (response && response.success) {
                        var inWishlist = !!response.in_wishlist;
                        syncWishlistIds(productId, removeOnly ? false : inWishlist, response.product_ids);

                        if (removeOnly || !inWishlist) {
                            removeWishlistCard(productId);
                            setWishlistButtonState(
                                $('.btn-wishlist[data-product-id="' + productId + '"], .btn-wishlist-pdp[data-product-id="' + productId + '"]')
                                    .not('[data-wishlist-remove="1"]'),
                                false
                            );
                        } else {
                            setWishlistButtonState(
                                $('.btn-wishlist[data-product-id="' + productId + '"], .btn-wishlist-pdp[data-product-id="' + productId + '"]')
                                    .not('[data-wishlist-remove="1"]'),
                                true
                            );
                        }

                        if (typeof response.count !== 'undefined') {
                            updateWishlistCount(response.count);
                        }
                        showWishlistToast(response.message || (inWishlist ? 'Added to Wishlist' : 'Removed from Wishlist'), true);
                    } else {
                        showWishlistToast((response && response.message) || 'Wishlist update failed', false);
                        if (response && response.redirect) {
                            setTimeout(function () { window.location.href = response.redirect; }, 700);
                        }
                    }
                })
                .fail(function (xhr) {
                    var msg = 'An error occurred. Please try again.';
                    try {
                        var data = xhr.responseJSON || JSON.parse(xhr.responseText);
                        if (data && data.message) msg = data.message;
                        if (data && data.redirect) {
                            showWishlistToast(msg, false);
                            setTimeout(function () { window.location.href = data.redirect; }, 700);
                            return;
                        }
                    } catch (err) {}
                    showWishlistToast(msg, false);
                })
                .always(function () {
                    $btn.data('wishlistBusy', false).removeClass('wishlist-loading');
                });
        });

        // Move to cart
        $(document).on('click', '.wishlist-move-cart-btn', function (e) {
            e.preventDefault();
            var $btn = $(this);
            if ($btn.data('wishlistBusy')) return;

            var productId = parseInt($btn.data('product-id'), 10);
            if (!productId) return;
            if (!window.isLoggedIn) {
                requireLoginRedirect();
                return;
            }

            $btn.data('wishlistBusy', true).prop('disabled', true);

            wishlistRequest('moveToCart', productId, { quantity: 1 })
                .done(function (response) {
                    if (response && response.success) {
                        syncWishlistIds(productId, false, response.product_ids);
                        if (typeof response.count !== 'undefined') updateWishlistCount(response.count);
                        if (typeof response.cartCount !== 'undefined') updateCartCount(response.cartCount);
                        removeWishlistCard(productId);
                        showWishlistToast(response.message || 'Moved to Cart', true);
                    } else {
                        showWishlistToast((response && response.message) || 'Failed to move to cart', false);
                    }
                })
                .fail(function () {
                    showWishlistToast('Failed to move to cart', false);
                })
                .always(function () {
                    $btn.data('wishlistBusy', false).prop('disabled', false);
                });
        });

        // Clear all
        $(document).on('click', '#wishlistClearBtn', function (e) {
            e.preventDefault();
            if (!window.isLoggedIn) {
                requireLoginRedirect();
                return;
            }
            if (!window.confirm('Clear your entire wishlist?')) return;

            var $btn = $(this);
            $btn.prop('disabled', true);

            wishlistRequest('clear', null)
                .done(function (response) {
                    if (response && response.success) {
                        window.wishlistProductIds = [];
                        updateWishlistCount(0);
                        showWishlistToast(response.message || 'Wishlist cleared', true);
                        setTimeout(function () {
                            window.location.href = base() + '?controller=wishlist';
                        }, 400);
                    } else {
                        showWishlistToast((response && response.message) || 'Failed to clear wishlist', false);
                        $btn.prop('disabled', false);
                    }
                })
                .fail(function () {
                    showWishlistToast('Failed to clear wishlist', false);
                    $btn.prop('disabled', false);
                });
        });
    });

    // Expose helpers for other scripts
    window.WishlistUI = {
        refresh: refreshWishlistState,
        updateCount: updateWishlistCount,
        markHearts: markWishlistHeartsFromIds
    };
})(window, window.jQuery);
