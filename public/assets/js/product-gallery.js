/**
 * Product Details — gallery zoom, thumbs, lightbox, Buy Now, share
 * Does not alter cart/wishlist AJAX endpoints.
 */
(function () {
  'use strict';

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }

  function moveSeoMeta() {
    var pdp = qs('.pdp');
    if (!pdp) return;

    var titleEl = qs('.pdp-title');
    if (titleEl && titleEl.textContent) {
      document.title = titleEl.textContent.trim() + ' | Sivakamy';
    }

    qsa('meta[property^="og:"], meta[name^="twitter:"]').forEach(function (meta) {
      if (meta.parentElement === document.head) return;
      document.head.appendChild(meta);
    });

    qsa('script[type="application/ld+json"]').forEach(function (script) {
      if (script.parentElement === document.head) return;
      // JSON-LD is valid in body; leave it — SEO crawlers accept both
    });
  }

  function initRipples() {
    qsa('.pdp-btn').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        var rect = btn.getBoundingClientRect();
        var size = Math.max(rect.width, rect.height);
        var ripple = document.createElement('span');
        ripple.className = 'pdp-ripple';
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = e.clientX - rect.left - size / 2 + 'px';
        ripple.style.top = e.clientY - rect.top - size / 2 + 'px';
        btn.appendChild(ripple);
        setTimeout(function () {
          if (ripple.parentNode) ripple.parentNode.removeChild(ripple);
        }, 600);
      });
    });
  }

  function initGallery() {
    var mainImg = qs('#pdpMainImage');
    var wrap = qs('#pdpZoomWrap');
    var lens = qs('#pdpLens');
    var result = qs('#pdpZoomResult');
    var thumbs = qsa('.pdp-thumb');
    if (!mainImg || !wrap) return;

    var zoom = 2.4;

    function setMain(src, fade) {
      if (!src) return;
      if (fade) {
        mainImg.classList.add('is-fading');
        setTimeout(function () {
          mainImg.src = src;
          mainImg.setAttribute('data-zoom-src', src);
          mainImg.classList.remove('is-fading');
        }, 180);
      } else {
        mainImg.src = src;
        mainImg.setAttribute('data-zoom-src', src);
      }
    }

    thumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        var full = thumb.getAttribute('data-full');
        thumbs.forEach(function (t) {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        thumb.classList.add('is-active');
        thumb.setAttribute('aria-selected', 'true');
        setMain(full, true);
      });

      thumb.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          thumb.click();
        }
      });
    });

    function moveLens(e) {
      if (window.matchMedia('(max-width: 1199px)').matches) return;
      if (!lens || !result) return;

      var rect = wrap.getBoundingClientRect();
      var x = e.clientX - rect.left;
      var y = e.clientY - rect.top;
      var lensW = lens.offsetWidth || 140;
      var lensH = lens.offsetHeight || 140;

      var left = Math.max(0, Math.min(x - lensW / 2, rect.width - lensW));
      var top = Math.max(0, Math.min(y - lensH / 2, rect.height - lensH));

      lens.hidden = false;
      lens.style.left = left + 'px';
      lens.style.top = top + 'px';

      var zoomSrc = mainImg.getAttribute('data-zoom-src') || mainImg.src;
      result.hidden = false;
      result.classList.add('is-visible');
      result.style.backgroundImage = 'url("' + zoomSrc + '")';
      result.style.backgroundSize = rect.width * zoom + 'px ' + rect.height * zoom + 'px';
      result.style.backgroundPosition =
        '-' + left * zoom + 'px -' + top * zoom + 'px';
    }

    function hideLens() {
      if (lens) lens.hidden = true;
      if (result) {
        result.hidden = true;
        result.classList.remove('is-visible');
      }
    }

    wrap.addEventListener('mousemove', moveLens);
    wrap.addEventListener('mouseenter', moveLens);
    wrap.addEventListener('mouseleave', hideLens);

    // Keyboard: arrow thumbs
    document.addEventListener('keydown', function (e) {
      if (!document.body.contains(mainImg)) return;
      var active = qs('.pdp-thumb.is-active');
      if (!active || thumbs.length < 2) return;
      var idx = thumbs.indexOf(active);
      if (e.key === 'ArrowRight' && idx < thumbs.length - 1) {
        thumbs[idx + 1].click();
      } else if (e.key === 'ArrowLeft' && idx > 0) {
        thumbs[idx - 1].click();
      }
    });
  }

  function initLightbox() {
    var lightbox = qs('#pdpLightbox');
    var img = qs('#pdpLightboxImg');
    var openBtn = qs('#pdpFullscreenBtn');
    var closeBtn = qs('#pdpLightboxClose');
    var mainImg = qs('#pdpMainImage');
    if (!lightbox || !img || !mainImg) return;

    function open() {
      img.src = mainImg.src;
      img.alt = mainImg.alt || '';
      lightbox.hidden = false;
      document.body.style.overflow = 'hidden';
      if (closeBtn) closeBtn.focus();
    }

    function close() {
      lightbox.hidden = true;
      document.body.style.overflow = '';
    }

    if (openBtn) openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) close();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !lightbox.hidden) close();
    });
  }

  function toast(message, type) {
    type = type || 'success';
    var el = document.createElement('div');
    el.className =
      'toast align-items-center text-white border-0 position-fixed top-0 end-0 m-3 bg-' +
      (type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'primary');
    el.setAttribute('role', 'alert');
    el.innerHTML =
      '<div class="d-flex"><div class="toast-body">' +
      message +
      '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
    document.body.appendChild(el);
    if (window.bootstrap && bootstrap.Toast) {
      var t = new bootstrap.Toast(el, { delay: 2800 });
      t.show();
      el.addEventListener('hidden.bs.toast', function () {
        el.remove();
      });
    } else {
      setTimeout(function () {
        el.remove();
      }, 2800);
    }
  }

  function baseUrl() {
    if (window.baseUrl) return window.baseUrl;
    var path = window.location.pathname || '';
    var m = path.match(/^(.*?\/ecommerce\/)/i);
    return m ? (window.location.origin + m[1]) : (window.location.origin + '/');
  }

  function addToCartThen(redirect) {
    var form = qs('#pdpAddToCartForm');
    if (!form || !window.jQuery) {
      if (form) form.requestSubmit ? form.requestSubmit() : form.submit();
      return;
    }

    var $ = window.jQuery;
    var $form = $(form);
    var qtyInput = form.querySelector('.quantity-input, input[name="quantity"]');
    if (qtyInput) {
      var min = parseInt(qtyInput.getAttribute('min'), 10) || 1;
      var max = parseInt(qtyInput.getAttribute('max'), 10) || 9999;
      var q = parseInt(qtyInput.value, 10) || 1;
      if (q < min) q = min;
      if (q > max) q = max;
      qtyInput.value = q;
    }

    var url = $form.attr('action') || baseUrl() + '?controller=cart&action=add';
    var $btn = $('#pdpBuyNowBtn');
    var original = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

    $.ajax({
      url: url,
      type: 'POST',
      data: $form.serialize(),
      dataType: 'json',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .done(function (response) {
        if (response && response.redirect) {
          window.location.href = response.redirect;
          return;
        }
        if (response && response.success) {
          if (typeof response.cartCount !== 'undefined') {
            $('.cart-count').text(response.cartCount);
          }
          if (redirect) {
            window.location.href = baseUrl() + '?controller=cart&action=index';
            return;
          }
          toast(response.message || 'Product added to cart');
        } else {
          toast((response && response.message) || 'Could not add to cart', 'danger');
        }
      })
      .fail(function () {
        toast('Could not add to cart. Please try again.', 'danger');
      })
      .always(function () {
        $btn.prop('disabled', false).html(original);
      });
  }

  function initPurchase() {
    var buyNow = qs('#pdpBuyNowBtn');
    if (buyNow) {
      buyNow.addEventListener('click', function (e) {
        e.preventDefault();
        addToCartThen(true);
      });
    }

    var mobileAdd = qs('#pdpMobileAddBtn');
    if (mobileAdd) {
      mobileAdd.addEventListener('click', function () {
        var form = qs('#pdpAddToCartForm');
        if (form) {
          if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
          } else {
            $(form).trigger('submit');
          }
        }
      });
    }
  }

  function initShareCompare() {
    var shareBtn = qs('#pdpShareBtn');
    if (shareBtn) {
      shareBtn.addEventListener('click', function () {
        var url = window.location.href;
        var title = (qs('.pdp-title') || {}).textContent || document.title;
        if (navigator.share) {
          navigator.share({ title: title, url: url }).catch(function () {});
          return;
        }
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(function () {
            toast('Link copied to clipboard');
          });
        } else {
          toast(url, 'primary');
        }
      });
    }

    var compareBtn = qs('#pdpCompareBtn');
    if (compareBtn) {
      compareBtn.addEventListener('click', function () {
        var id = compareBtn.getAttribute('data-product-id');
        if (!id) return;
        var key = 'siva_compare_ids';
        var list = [];
        try {
          list = JSON.parse(localStorage.getItem(key) || '[]');
        } catch (e) {
          list = [];
        }
        if (!Array.isArray(list)) list = [];
        id = String(id);
        if (list.indexOf(id) === -1) {
          list.push(id);
          if (list.length > 4) list = list.slice(-4);
          localStorage.setItem(key, JSON.stringify(list));
          toast('Added to compare list');
        } else {
          toast('Already in compare list');
        }
      });
    }
  }

  function openReviewsFromHash() {
    if (window.location.hash !== '#pdpReviews') return;
    var collapse = qs('#pdpReviews');
    if (!collapse) return;
    if (window.bootstrap && bootstrap.Collapse) {
      bootstrap.Collapse.getOrCreateInstance(collapse, { toggle: false }).show();
    } else {
      collapse.classList.add('show');
    }
    setTimeout(function () {
      collapse.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 120);
  }

  function init() {
    if (!qs('.pdp')) return;
    moveSeoMeta();
    initRipples();
    initGallery();
    initLightbox();
    initPurchase();
    initShareCompare();
    openReviewsFromHash();
    window.addEventListener('hashchange', openReviewsFromHash);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
