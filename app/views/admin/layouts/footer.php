            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      (function () {
        // data-toggle/data-target/data-dismiss compatibility (BS4 -> BS5)
        document.querySelectorAll('[data-toggle]').forEach(function (el) {
          if (!el.hasAttribute('data-bs-toggle')) el.setAttribute('data-bs-toggle', el.getAttribute('data-toggle'));
        });
        document.querySelectorAll('[data-target]').forEach(function (el) {
          if (!el.hasAttribute('data-bs-target')) el.setAttribute('data-bs-target', el.getAttribute('data-target'));
        });
        document.querySelectorAll('[data-dismiss]').forEach(function (el) {
          if (!el.hasAttribute('data-bs-dismiss')) el.setAttribute('data-bs-dismiss', el.getAttribute('data-dismiss'));
        });

        // Minimal jQuery bridge for legacy modal/tooltip/alert calls
        if (window.jQuery && window.bootstrap) {
          var $ = window.jQuery;
          if (!$.fn.modal) {
            $.fn.modal = function (action) {
              return this.each(function () {
                var instance = bootstrap.Modal.getOrCreateInstance(this);
                if (action === 'show') instance.show();
                else if (action === 'hide') instance.hide();
                else if (action === 'toggle') instance.toggle();
              });
            };
          }
          if (!$.fn.tooltip) {
            $.fn.tooltip = function () {
              return this.each(function () { bootstrap.Tooltip.getOrCreateInstance(this); });
            };
          }
          if (!$.fn.alert) {
            $.fn.alert = function (action) {
              return this.each(function () {
                var inst = bootstrap.Alert.getOrCreateInstance(this);
                if (action === 'close') inst.close();
              });
            };
          }
          $(document).on('click', '[data-dismiss="alert"]', function () {
            var alertEl = this.closest('.alert');
            if (alertEl) bootstrap.Alert.getOrCreateInstance(alertEl).close();
          });
        }
      })();
    </script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script>
      // Expose base URL to frontend scripts
      window.baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : '1'; ?>" defer></script>
</body>
</html>
