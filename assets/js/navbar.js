/**
 * Storefront navbar / Bootstrap UI initializer
 * Frontend only — does not change backend or AJAX payloads.
 */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  ready(function () {
    if (typeof bootstrap === 'undefined') {
      console.warn('Bootstrap JS not loaded');
      return;
    }

    // Initialize all dropdowns (desktop + mobile)
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (el) {
      try {
        bootstrap.Dropdown.getOrCreateInstance(el, {
          autoClose: true,
          display: 'dynamic'
        });
      } catch (err) {
        console.warn('Dropdown init failed', err);
      }
    });

    // Initialize collapses (mobile menu, category accordion)
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (el) {
      var target = el.getAttribute('data-bs-target') || el.getAttribute('href');
      if (!target || target.charAt(0) !== '#') return;
      var pane = document.querySelector(target);
      if (!pane) return;
      try {
        bootstrap.Collapse.getOrCreateInstance(pane, { toggle: false });
      } catch (err) {}
    });

    // Tooltips / popovers if present
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
      try { bootstrap.Tooltip.getOrCreateInstance(el); } catch (e) {}
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
      try { bootstrap.Popover.getOrCreateInstance(el); } catch (e) {}
    });

    // Carousels
    document.querySelectorAll('.carousel').forEach(function (el) {
      try {
        bootstrap.Carousel.getOrCreateInstance(el, {
          interval: 5000,
          ride: false,
          wrap: true,
          touch: true
        });
      } catch (e) {}
    });

    // Keep dropdown search inputs from closing the menu
    document.querySelectorAll('.nav-dropdown-search').forEach(function (input) {
      input.addEventListener('click', function (e) {
        e.stopPropagation();
      });
      input.addEventListener('keydown', function (e) {
        e.stopPropagation();
      });
    });

    // Dark mode (works even if header inline script missed a button)
    var root = document.documentElement;
    function applyTheme(mode) {
      root.setAttribute('data-theme', mode);
      try { localStorage.setItem('theme_mode', mode); } catch (e) {}
      document.querySelectorAll('.theme-toggle').forEach(function (btn) {
        var icon = btn.querySelector('i');
        if (icon) {
          icon.className = mode === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        btn.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false');
      });

      document.querySelectorAll('.navbar, .mobile-top-bar, #mobileNavbar, .mobile-bottom-nav').forEach(function (el) {
        if (mode === 'dark') {
          el.style.removeProperty('background');
          el.style.removeProperty('background-color');
        }
      });
    }

    if (!root.getAttribute('data-theme')) {
      var saved = null;
      try { saved = localStorage.getItem('theme_mode'); } catch (e) {}
      applyTheme(saved === 'dark' || saved === 'light' ? saved : 'light');
    } else {
      applyTheme(root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');
    }

    document.querySelectorAll('.theme-toggle').forEach(function (btn) {
      if (btn.dataset.themeBound === '1') return;
      btn.dataset.themeBound = '1';
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
      });
    });

    // Sticky header shadow on scroll
    var header = document.querySelector('.storefront-desktop-nav, .storefront-mobile-header');
    if (header) {
      var onScroll = function () {
        if (window.scrollY > 8) {
          header.classList.add('is-scrolled');
        } else {
          header.classList.remove('is-scrolled');
        }
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    document.body.classList.add('storefront-ui-ready');
  });
})();
