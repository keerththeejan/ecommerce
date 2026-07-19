/**
 * Storefront frontend polish (UI only)
 * - Scroll reveal
 * - Lazy image enhancement
 * - Voice search (optional, Web Speech API)
 * - Search suggest shell (ready for existing AJAX endpoints)
 * Does NOT modify cart/auth/AJAX business logic.
 */
(function () {
  'use strict';

  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onReady(function () {
    function markImageLoaded(img) {
      if (!img) return;
      img.classList.add('loaded');
      img.style.opacity = '';
    }

    function bindImageFade(img) {
      if (!img || img.dataset.imgBound === '1') return;
      img.dataset.imgBound = '1';
      if (img.complete && img.naturalWidth > 0) {
        markImageLoaded(img);
      } else {
        img.addEventListener('load', function () { markImageLoaded(img); });
        img.addEventListener('error', function () {
          if (!img.dataset.fallbackApplied) {
            img.dataset.fallbackApplied = '1';
            var fb = (window.baseUrl || '/') + 'assets/img/no-image.png';
            img.src = fb;
          }
          markImageLoaded(img);
        });
      }
    }

    // Do NOT force loading=lazy onto images that lack it — that raced with
    // opacity:0 CSS and left product images invisible. Only enhance existing ones.
    document.querySelectorAll('img').forEach(bindImageFade);

    // Scroll reveal for key sections/cards
    var revealTargets = document.querySelectorAll(
      '.siva-section, .featured-categories, .trending-products, .featured-products, .brands-showcase, .siva-card, .category-card, .product-card'
    );
    revealTargets.forEach(function (el) {
      el.classList.add('premium-reveal');
    });

    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              io.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
      );
      document.querySelectorAll('.premium-reveal').forEach(function (el) {
        io.observe(el);
      });
    } else {
      document.querySelectorAll('.premium-reveal').forEach(function (el) {
        el.classList.add('is-visible');
      });
    }

    // Ensure suggest containers exist under search forms (AJAX-ready shell)
    document.querySelectorAll('.storefront-header-search').forEach(function (form) {
      if (!form.querySelector('.storefront-suggest')) {
        var box = document.createElement('div');
        box.className = 'storefront-suggest';
        box.setAttribute('role', 'listbox');
        box.setAttribute('aria-label', 'Search suggestions');
        form.appendChild(box);
      }
    });

    // Optional voice search (UI only)
    document.querySelectorAll('.storefront-search-voice').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var form = btn.closest('form');
        if (!form) return;
        var input = form.querySelector('input[name="keyword"]');
        if (!input) return;

        var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
          input.focus();
          return;
        }

        var recognition = new SpeechRecognition();
        recognition.lang = document.documentElement.lang || 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        btn.classList.add('is-listening');
        recognition.start();

        recognition.onresult = function (event) {
          var transcript = event.results[0][0].transcript;
          input.value = transcript;
          input.dispatchEvent(new Event('input', { bubbles: true }));
        };

        recognition.onerror = function () {
          btn.classList.remove('is-listening');
        };

        recognition.onend = function () {
          btn.classList.remove('is-listening');
        };
      });
    });

    // Search category select: UI helper — navigates to category without altering keyword field name
    document.querySelectorAll('.storefront-search-category').forEach(function (select) {
      select.addEventListener('change', function () {
        var value = select.value;
        if (!value) return;
        var base = select.getAttribute('data-base-url') || '/';
        window.location.href = base + '?controller=product&action=category&id=' + encodeURIComponent(value);
      });
    });

    // Smooth hover lift class for primary CTAs already handled in CSS
    // Mark page ready for CSS transitions
    document.body.classList.add('premium-ui-ready');
  });
})();
