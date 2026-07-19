<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo isset($pageTitle) && $pageTitle !== '' ? htmlspecialchars($pageTitle) : 'Sivakamy'; ?></title>
    <?php if (!empty($pageDescription)): ?>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <?php endif; ?>
    <?php if (!empty($ogType) || !empty($ogImage) || !empty($ogUrl) || !empty($pageTitle)): ?>
    <meta property="og:type" content="<?php echo htmlspecialchars($ogType ?? 'website'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle ?? 'Sivakamy'); ?>">
    <?php if (!empty($pageDescription)): ?>
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <?php endif; ?>
    <?php if (!empty($ogImage)): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <?php endif; ?>
    <?php if (!empty($ogUrl)): ?>
    <meta property="og:url" content="<?php echo htmlspecialchars($ogUrl); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="<?php echo htmlspecialchars($twitterCard ?? 'summary_large_image'); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle ?? 'Sivakamy'); ?>">
    <?php if (!empty($pageDescription)): ?>
    <meta name="twitter:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <?php endif; ?>
    <?php if (!empty($ogImage)): ?>
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($productSchema) && is_array($productSchema)): ?>
    <script type="application/ld+json"><?php echo json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php endif; ?>
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS - Latest Stable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- System UI foundation (shared) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/system.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
    <!-- Premium Theme -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/responsive.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
    <!-- Product cards last so action-row layout wins over theme/mobile -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/product-cards.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

    <script>
        (function() {
            try {
                var saved = localStorage.getItem('theme_mode');
                if (saved === 'dark' || saved === 'light') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="mobile-nav-fixed d-flex flex-column min-vh-100">
    <?php
    // Get site settings
    $settingModel = new Setting();
    $siteLogo = $settingModel->getSetting('site_logo');
    $siteName = $settingModel->getSetting('site_name') ?: 'Sivakamy';
    $headerLogoSize = (int)($settingModel->getSetting('header_logo_size', '80'));
    $headerLogoSize = $headerLogoSize >= 32 && $headerLogoSize <= 150 ? $headerLogoSize : 80;
    $headerLogoSizeMobile = (int)($settingModel->getSetting('header_logo_size_mobile', '52'));
    $headerLogoSizeMobile = $headerLogoSizeMobile >= 28 && $headerLogoSizeMobile <= 100 ? $headerLogoSizeMobile : 52;
    $menuAllProducts = $settingModel->getSetting('menu_all_products', 'All products');
    $menuCountryOrigin = $settingModel->getSetting('menu_country_origin', 'Country of origin');
    $menuAllBrands = $settingModel->getSetting('menu_all_brands', 'All brands');
    $menuNew = $settingModel->getSetting('menu_new', 'New');
    $menuSale = $settingModel->getSetting('menu_sale', 'Sale');
    $menuFontSize = trim($settingModel->getSetting('menu_font_size', '1rem'));
    if (!preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $menuFontSize)) { $menuFontSize = '1rem'; }
    $headerBgColor = $settingModel->getSetting('header_bg_color', '#ffffff');
    $headerWidth = $settingModel->getSetting('header_width', 'boxed');
    $headerBgColor = (!empty($headerBgColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $headerBgColor)) ? $headerBgColor : '#ffffff';
    $headerWidth = in_array($headerWidth, ['boxed', 'full'], true) ? $headerWidth : 'boxed';
    $headerContainerClass = $headerWidth === 'full' ? 'container-fluid' : 'container';

    $themePrimaryColor = $settingModel->getSetting('theme_primary_color', '#0d6efd');
    $themeSecondaryColor = $settingModel->getSetting('theme_secondary_color', '#6c757d');
    $themeBackgroundColor = $settingModel->getSetting('theme_background_color', '#ffffff');
    $themeTextColor = $settingModel->getSetting('theme_text_color', '#212529');
    $themeDefaultMode = $settingModel->getSetting('theme_default_mode', 'light');
    $themeDarkPrimaryColor = $settingModel->getSetting('theme_dark_primary_color', '#4dabf7');
    $themeDarkSecondaryColor = $settingModel->getSetting('theme_dark_secondary_color', '#adb5bd');
    $themeDarkBackgroundColor = $settingModel->getSetting('theme_dark_background_color', '#0b1220');
    $themeDarkTextColor = $settingModel->getSetting('theme_dark_text_color', '#e9ecef');
    $themePrimaryColor = (!empty($themePrimaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themePrimaryColor)) ? $themePrimaryColor : '#0d6efd';
    $themeSecondaryColor = (!empty($themeSecondaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeSecondaryColor)) ? $themeSecondaryColor : '#6c757d';
    $themeBackgroundColor = (!empty($themeBackgroundColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeBackgroundColor)) ? $themeBackgroundColor : '#ffffff';
    $themeTextColor = (!empty($themeTextColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeTextColor)) ? $themeTextColor : '#212529';
    $themeDefaultMode = in_array($themeDefaultMode, ['light', 'dark'], true) ? $themeDefaultMode : 'light';
    $themeDarkPrimaryColor = (!empty($themeDarkPrimaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkPrimaryColor)) ? $themeDarkPrimaryColor : '#4dabf7';
    $themeDarkSecondaryColor = (!empty($themeDarkSecondaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkSecondaryColor)) ? $themeDarkSecondaryColor : '#adb5bd';
    $themeDarkBackgroundColor = (!empty($themeDarkBackgroundColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkBackgroundColor)) ? $themeDarkBackgroundColor : '#0b1220';
    $themeDarkTextColor = (!empty($themeDarkTextColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkTextColor)) ? $themeDarkTextColor : '#e9ecef';
    ?>

    <style>
        :root {
            --theme-primary: <?php echo htmlspecialchars($themePrimaryColor); ?>;
            --theme-secondary: <?php echo htmlspecialchars($themeSecondaryColor); ?>;
            --theme-bg: <?php echo htmlspecialchars($themeBackgroundColor); ?>;
            --theme-text: <?php echo htmlspecialchars($themeTextColor); ?>;
            --header-bg: <?php echo htmlspecialchars($headerBgColor); ?>;
            --header-logo-size: <?php echo (int)$headerLogoSize; ?>px;
            --header-logo-size-mobile: <?php echo (int)$headerLogoSizeMobile; ?>px;
            --menu-font-size: <?php echo htmlspecialchars($menuFontSize); ?>;
            --storefront-search-max: 700px;
            --storefront-header-offset-mobile: 168px;
            --storefront-header-offset-desktop: 128px;

            /* Premium commerce tokens */
            --siva-primary: #2563EB;
            --siva-accent: #10B981;
            --siva-bg: var(--theme-bg);
            --siva-card: #ffffff;
            --siva-text: var(--theme-text);
            --siva-muted: rgba(15, 23, 42, 0.65);
            --siva-border: rgba(15, 23, 42, 0.10);
            --siva-radius: 18px;
            --siva-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --siva-shadow-sm: 0 6px 16px rgba(15, 23, 42, 0.08);
        }

        html[data-theme="light"] {
            --theme-text: #000000;
        }

        html[data-theme="dark"] {
            --theme-primary: <?php echo htmlspecialchars($themeDarkPrimaryColor); ?>;
            --theme-secondary: <?php echo htmlspecialchars($themeDarkSecondaryColor); ?>;
            --theme-bg: <?php echo htmlspecialchars($themeDarkBackgroundColor); ?>;
            --theme-text: <?php echo htmlspecialchars($themeDarkTextColor); ?>;

            /* Sivakamy – requested dark palette */
            --siva-bg: #2c313c;
            --siva-card: #363c48;
            --siva-text: #EAEAEA;
            --siva-muted: rgba(234, 234, 234, 0.75);
            --siva-border: rgba(255, 255, 255, 0.14);
            --siva-accent: #06b6d4;
        }

        body {
            background-color: var(--theme-bg) !important;
            color: var(--theme-text);
        }

        /* Global commerce surfaces */
        body {
            background-color: var(--siva-bg) !important;
            color: var(--siva-text) !important;
        }

        .card {
            border-color: var(--siva-border) !important;
        }

        html[data-theme="dark"] .card,
        html[data-theme="dark"] .modal-content {
            background-color: var(--siva-card) !important;
        }

        /* Add-to-cart button colors (sizing/layout owned by product-cards.css) */
        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--siva-primary) 0%, #7c3aed 100%) !important;
            border: none !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            font-weight: 700;
            transition: box-shadow 160ms ease, background-color 160ms ease, filter 160ms ease;
        }

        @media (hover: hover) {
            .btn-add-to-cart:hover {
                filter: brightness(1.05);
                box-shadow: 0 10px 22px rgba(109, 40, 217, 0.25);
            }

            .product-card .btn-add-to-cart:hover {
                transform: none;
            }
        }

        .btn-add-to-cart:active {
            filter: brightness(0.98);
        }

        .btn-add-to-cart.is-added,
        .btn-add-to-cart[aria-pressed="true"] {
            background: linear-gradient(135deg, var(--siva-accent) 0%, #14b8a6 100%) !important;
            box-shadow: 0 10px 22px rgba(6, 182, 212, 0.22);
        }

        /* Ensure Brands section inherits page background (remove white strip) */
        #brands {
            background: transparent !important;
        }

        html[data-theme="dark"] .navbar,
        html[data-theme="dark"] .navbar[style*="background"],
        html[data-theme="dark"] .mobile-top-bar,
        html[data-theme="dark"] .mobile-top-bar[style*="background"],
        html[data-theme="dark"] .mobile-bottom-nav,
        html[data-theme="dark"] #mobileNavbar,
        html[data-theme="dark"] #mobileNavbar[style*="background"],
        html[data-theme="dark"] .dropdown-menu,
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .list-group-item {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
            color: var(--theme-text) !important;
        }
        
        /* Force navbar border color in dark mode */
        html[data-theme="dark"] .navbar.border-bottom {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        html[data-theme="dark"] .mobile-top-bar.border-bottom {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Ensure mobile bottom nav has dark background */
        html[data-theme="dark"] .mobile-bottom-nav {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Override any inline background styles in dark mode for header elements */
        html[data-theme="dark"] nav[style*="background"],
        html[data-theme="dark"] div[style*="background"].mobile-top-bar,
        html[data-theme="dark"] div[style*="background"]#mobileNavbar {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
        }
        
        /* Ensure body background matches dark theme */
        html[data-theme="dark"] body {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
        }

        /* Product Cards Dark Theme */
        html[data-theme="dark"] .product-card,
        html[data-theme="dark"] .product-card .card-body,
        html[data-theme="dark"] .product-card .card-title,
        html[data-theme="dark"] .product-card .product-title,
        html[data-theme="dark"] .product-card .product-desc,
        html[data-theme="dark"] .product-card .product-price,
        html[data-theme="dark"] .product-card .product-meta,
        html[data-theme="dark"] .product-card .product-meta small {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--theme-text) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        html[data-theme="dark"] .product-card:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: var(--theme-primary) !important;
        }

        /* Category Cards Dark Theme */
        html[data-theme="dark"] .category-card,
        html[data-theme="dark"] .category-card .card-body,
        html[data-theme="dark"] .category-card .category-title {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--theme-text) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        html[data-theme="dark"] .category-card:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* Product Image Box Dark Theme */
        html[data-theme="dark"] .product-image-box,
        html[data-theme="dark"] .category-image-box {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Sections Background Dark Theme */
        html[data-theme="dark"] .featured-products,
        html[data-theme="dark"] .featured-categories,
        html[data-theme="dark"] .brands-showcase,
        html[data-theme="dark"] section[data-theme-aware] {
            background: transparent !important;
        }

        /* Override inline background styles in dark mode */
        html[data-theme="dark"] [style*="background"] {
            background: transparent !important;
        }

        /* But keep specific overrides for cards */
        html[data-theme="dark"] .product-card[style*="background"],
        html[data-theme="dark"] .category-card[style*="background"] {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        /* All text elements in dark mode */
        html[data-theme="dark"] h1,
        html[data-theme="dark"] h2,
        html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,
        html[data-theme="dark"] h5,
        html[data-theme="dark"] h6,
        html[data-theme="dark"] p,
        html[data-theme="dark"] span,
        html[data-theme="dark"] div,
        html[data-theme="dark"] .section-title {
            color: var(--theme-text) !important;
        }

        /* Links in dark mode */
        html[data-theme="dark"] a:not(.btn) {
            color: var(--theme-primary) !important;
        }

        html[data-theme="dark"] a:not(.btn):hover {
            color: var(--theme-secondary) !important;
        }

        html[data-theme="dark"] .nav-link,
        html[data-theme="dark"] .dropdown-item,
        html[data-theme="dark"] .navbar-brand,
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .input-group-text,
        html[data-theme="dark"] .btn,
        html[data-theme="dark"] .table,
        html[data-theme="dark"] .table td,
        html[data-theme="dark"] .table th {
            color: var(--theme-text) !important;
        }

        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .input-group-text {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        html[data-theme="dark"] .dropdown-item:hover,
        html[data-theme="dark"] .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] .text-muted,
        html[data-theme="dark"] .text-secondary {
            color: var(--theme-text) !important;
        }

        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .bg-light {
            background-color: var(--theme-bg) !important;
        }

        html[data-theme="light"] body,
        html[data-theme="light"] h1,
        html[data-theme="light"] h2,
        html[data-theme="light"] h3,
        html[data-theme="light"] h4,
        html[data-theme="light"] h5,
        html[data-theme="light"] h6,
        html[data-theme="light"] p,
        html[data-theme="light"] span,
        html[data-theme="light"] small,
        html[data-theme="light"] li,
        html[data-theme="light"] label,
        html[data-theme="light"] .nav-link,
        html[data-theme="light"] .navbar,
        html[data-theme="light"] .dropdown-item {
            color: var(--theme-text) !important;
        }

        html[data-theme="light"] .text-dark,
        html[data-theme="light"] .text-muted,
        html[data-theme="light"] .text-secondary {
            color: var(--theme-text) !important;
        }

        a {
            color: var(--theme-primary);
        }

        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        html[data-theme="dark"] .theme-toggle-btn {
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-primary {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
        }

        .btn-secondary {
            background-color: var(--theme-secondary) !important;
            border-color: var(--theme-secondary) !important;
        }

        .text-primary { color: var(--theme-primary) !important; }
        .text-secondary { color: var(--theme-secondary) !important; }
        .bg-primary { background-color: var(--theme-primary) !important; }
        .bg-secondary { background-color: var(--theme-secondary) !important; }
    </style>
    
    <!-- Mobile Header -->
    <style>
        /* Searchable dropdowns - scroll when many items */
        .nav-dropdown-searchable {
            max-height: 320px;
            overflow-y: auto;
        }
        .nav-dropdown-searchable .nav-dropdown-search {
            min-width: 180px;
        }

        /* Mobile menu toggle - remove white box around hamburger icon */
        button[data-bs-target="#mobileNavbar"] {
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        .storefront-desktop-nav .navbar-nav .nav-link,
        .storefront-desktop-nav .navbar-nav .dropdown-toggle {
            font-size: var(--menu-font-size) !important;
        }

        /* Critical storefront header layout (beats Bootstrap navbar flex defaults) */
        nav.storefront-desktop-nav.navbar {
            display: block !important;
            padding: 0 !important;
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            width: 100%;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.08);
        }

        /* Amazon/Shopify style: Row1 Logo+Nav+Actions | Row2 Search */
        nav.storefront-desktop-nav.navbar > .container,
        nav.storefront-desktop-nav.navbar > .container-fluid,
        nav.storefront-desktop-nav .storefront-header-shell {
            display: grid !important;
            grid-template-columns: auto minmax(0, 1fr) auto !important;
            grid-template-areas:
                "brand nav actions"
                "search search search" !important;
            align-items: center !important;
            column-gap: 20px !important;
            row-gap: 0.45rem !important;
            width: 100%;
            max-width: 1400px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
        }

        nav.storefront-desktop-nav .storefront-header-primary {
            display: contents !important;
        }

        nav.storefront-desktop-nav .storefront-brand {
            grid-area: brand !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: 200px !important;
            margin: 0 !important;
            padding: 0 !important;
            white-space: nowrap !important;
        }

        nav.storefront-desktop-nav .storefront-header-secondary,
        nav.storefront-desktop-nav #navbarSupportedContent.storefront-header-secondary {
            grid-area: nav !important;
        }

        nav.storefront-desktop-nav .storefront-header-actions {
            grid-area: actions !important;
            width: auto !important;
            justify-content: flex-end !important;
            padding: 0 !important;
        }

        nav.storefront-desktop-nav .storefront-header-search {
            grid-area: search !important;
            width: min(700px, 100%) !important;
            max-width: min(700px, 100%) !important;
            margin: 0.15rem auto 0 !important;
            justify-self: center !important;
        }

        nav.storefront-desktop-nav .storefront-search-shell {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            width: 100% !important;
            min-height: 50px;
            border: 1.5px solid rgba(37, 99, 235, 0.18);
            border-radius: 50px;
            background: #f1f5f9;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.08);
        }

        nav.storefront-desktop-nav .storefront-search-icon {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            flex: 0 0 44px !important;
            width: 44px !important;
            color: rgba(15, 23, 42, 0.45);
        }

        nav.storefront-desktop-nav .storefront-search-input {
            flex: 1 1 auto !important;
            width: auto !important;
            max-width: none !important;
            height: 50px !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0.5rem 0.25rem 0.5rem 0 !important;
        }

        nav.storefront-desktop-nav .storefront-search-submit {
            flex: 0 0 auto !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            height: 50px !important;
            border: 0 !important;
            border-radius: 0 50px 50px 0 !important;
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
            color: #fff !important;
            padding: 0 1.15rem !important;
        }

        nav.storefront-desktop-nav .storefront-header-secondary,
        nav.storefront-desktop-nav #navbarSupportedContent.storefront-header-secondary {
            display: flex !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            min-height: 0;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            overflow: visible !important;
            justify-content: center !important;
            align-items: center !important;
        }

        nav.storefront-desktop-nav .storefront-main-nav {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.05rem 0.1rem !important;
            width: 100%;
            max-width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
        }

        nav.storefront-desktop-nav .storefront-main-nav .dropdown-menu {
            z-index: 1085 !important;
        }

        nav.storefront-desktop-nav .storefront-main-nav .nav-link {
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            padding: 0.35rem 0.45rem !important;
            margin: 0 !important;
            border-radius: 999px;
            font-size: 0.8125rem !important;
            transform: none !important;
        }

        nav.storefront-desktop-nav .storefront-header-actions {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 0.25rem !important;
            flex: 0 0 auto !important;
            margin: 0 !important;
            z-index: 2 !important;
            position: relative !important;
        }

        nav.storefront-desktop-nav .storefront-action-label {
            display: none !important;
        }

        nav.storefront-desktop-nav .storefront-action-link {
            padding: 0.35rem 0.55rem !important;
            min-width: 40px;
            justify-content: center;
        }

        nav.storefront-desktop-nav .storefront-main-nav .nav-link:hover,
        nav.storefront-desktop-nav .storefront-main-nav .nav-link:focus {
            transform: none !important;
            background: rgba(15, 23, 42, 0.06);
        }

        nav.storefront-desktop-nav .storefront-nav-pill--new {
            background: rgba(40, 167, 69, 0.12) !important;
            color: #198754 !important;
        }

        nav.storefront-desktop-nav .storefront-nav-pill--sale {
            background: rgba(172, 104, 26, 0.14) !important;
            color: #a87428 !important;
        }

        nav.storefront-desktop-nav .storefront-nav-pill--offers {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        nav.storefront-desktop-nav .storefront-action-link {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.35rem;
            min-height: 40px;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            text-decoration: none !important;
            white-space: nowrap;
            font-weight: 600;
            font-size: 0.9rem;
            color: inherit !important;
        }

        nav.storefront-desktop-nav .storefront-action-link--primary {
            background: rgba(13, 110, 253, 0.08);
            color: var(--theme-primary, #0d6efd) !important;
        }

        nav.storefront-desktop-nav .storefront-logo--desktop {
            max-height: var(--header-logo-size, 80px);
            width: auto;
        }

        @media (min-width: 768px) and (max-width: 1399.98px) {
            nav.storefront-desktop-nav .storefront-action-link {
                padding: 0.35rem 0.5rem !important;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            nav.storefront-desktop-nav.navbar > .container,
            nav.storefront-desktop-nav.navbar > .container-fluid,
            nav.storefront-desktop-nav .storefront-header-shell {
                grid-template-columns: auto 1fr auto !important;
                grid-template-areas:
                    "brand nav actions"
                    "search search search" !important;
                column-gap: 12px !important;
            }
            nav.storefront-desktop-nav .storefront-header-search {
                width: min(640px, 100%) !important;
                max-width: 100% !important;
            }
            nav.storefront-desktop-nav .storefront-main-nav .nav-link {
                padding: 0.35rem 0.45rem !important;
                font-size: 0.8rem !important;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            nav.storefront-desktop-nav .storefront-header-search {
                width: min(700px, 100%) !important;
            }
            nav.storefront-desktop-nav .storefront-main-nav .nav-link {
                padding: 0.35rem 0.5rem !important;
                font-size: 0.82rem !important;
            }
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: var(--storefront-header-offset-desktop);
        }

        body.mobile-nav-fixed {
            padding-top: var(--storefront-header-offset-mobile) !important;
            overflow-x: hidden;
        }

        @media (min-width: 768px) {
            body.mobile-nav-fixed {
                padding-top: var(--storefront-header-offset-desktop) !important;
            }
        }

        main.storefront-main {
            margin-top: 0;
            padding-top: 0.75rem !important;
        }

        @media (max-width: 767.98px) {
            body.mobile-nav-fixed {
                padding-bottom: 70px !important;
                min-height: 100vh;
            }

            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1050;
                background: var(--header-bg, #fff);
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                padding: 8px 0;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .mobile-top-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1060;
                background: var(--header-bg, rgba(255, 255, 255, 0.98));
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .storefront-mobile-search .storefront-search-shell {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                width: 100% !important;
                min-height: 44px;
                border: 1.5px solid rgba(15, 23, 42, 0.14);
                border-radius: 999px;
                background: #f4f6f8;
                overflow: hidden;
            }

            .storefront-mobile-search .storefront-search-input {
                flex: 1 1 auto !important;
                border: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
                height: 44px !important;
            }

            .storefront-mobile-search .storefront-search-submit {
                flex: 0 0 auto !important;
                height: 44px !important;
                min-width: 44px;
                border: 0 !important;
                background: var(--theme-primary, #0d6efd) !important;
                color: #fff !important;
            }

            .main-content {
                margin-top: 20px;
                margin-bottom: 20px;
            }
        }
    </style>
    
    <!-- Mobile Top Bar — 3-row premium header -->
    <div class="d-md-none border-bottom mobile-top-bar storefront-mobile-header" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
        <div class="<?php echo $headerContainerClass; ?> storefront-topbar-inner mobile-header-inner">
            <!-- Row 1: Logo | Wishlist | Cart | Menu -->
            <div class="storefront-topbar-row mobile-header-row mobile-header-row--top">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none storefront-topbar-brand">
                    <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                        <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($siteLogo); ?>" 
                             alt="<?php echo htmlspecialchars($siteName); ?>" 
                             class="storefront-logo storefront-logo--mobile">
                    <?php else: ?>
                        <span class="h5 mb-0 fw-bold text-dark storefront-brand-text"><?php echo htmlspecialchars($siteName); ?></span>
                    <?php endif; ?>
                </a>
                <div class="storefront-topbar-actions">
                    <a href="<?php echo BASE_URL; ?>?controller=wishlist" class="storefront-icon-btn text-dark position-relative" aria-label="Wishlist">
                        <i class="fas fa-heart"></i>
                        <?php if (isLoggedIn()): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger wishlist-count storefront-badge">
                            <?php echo (int)wishlist_count(); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=cart" class="storefront-icon-btn text-dark position-relative" aria-label="Cart">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if(isLoggedIn()): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count storefront-badge">
                            <?php 
                                $cartModel = new Cart();
                                echo $cartModel->getCartCount($_SESSION['user_id']);
                            ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <?php if(!isLoggedIn()): ?>
                    <div class="dropdown">
                        <button class="btn storefront-icon-btn p-0 border-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=login"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=register"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <button class="btn p-0 border-0 theme-toggle-btn theme-toggle" type="button" aria-label="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <button class="btn storefront-icon-btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar" aria-expanded="false" aria-controls="mobileNavbar" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <!-- Row 2: Search + Voice + Filter -->
            <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="storefront-mobile-search storefront-header-search mobile-header-row mobile-header-row--search" role="search">
                <input type="hidden" name="controller" value="product">
                <input type="hidden" name="action" value="search">
                <div class="storefront-search-shell input-group">
                    <span class="storefront-search-icon" aria-hidden="true"><i class="fas fa-search"></i></span>
                    <input type="search" 
                           class="form-control storefront-search-input" 
                           name="keyword" 
                           placeholder="Search products, brands..."
                           autocomplete="off"
                           aria-label="Search products">
                    <button class="storefront-search-voice" type="button" aria-label="Voice search" title="Voice search">
                        <i class="fas fa-microphone"></i>
                    </button>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="storefront-search-filter" aria-label="Filter products" title="Filter">
                        <i class="fas fa-sliders-h"></i>
                    </a>
                    <button class="btn storefront-search-submit" type="submit" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Row 3: Horizontal scroll chips -->
            <nav class="mobile-chip-nav mobile-header-row" aria-label="Quick navigation">
                <div class="mobile-chip-track">
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=product&action=index"><?php echo htmlspecialchars($menuAllProducts); ?></a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=category">Categories</a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=brand&action=index"><?php echo htmlspecialchars($menuAllBrands); ?></a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=product&action=sale">Offers</a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=product&action=index"><?php echo htmlspecialchars($menuNew); ?></a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>#featured-products">Featured</a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=product&action=sale"><?php echo htmlspecialchars($menuSale); ?></a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>#trending-products">Best Sellers</a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=country&action=index"><?php echo htmlspecialchars($menuCountryOrigin); ?></a>
                    <a class="mobile-chip" href="<?php echo BASE_URL; ?>?controller=contact">Contact</a>
                </div>
            </nav>
        </div>
        
        <!-- Mobile Navigation (collapse drawer) -->
        <div class="collapse storefront-mobile-menu" id="mobileNavbar" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
            <div class="<?php echo $headerContainerClass; ?> py-2">
                <ul class="navbar-nav">
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=index">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-box me-2 text-primary storefront-menu-icon"></i>
                                <span><?php echo htmlspecialchars($menuAllProducts); ?></span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=about&action=index">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-secondary storefront-menu-icon"></i>
                                <span>About Our Store</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <!-- Categories Dropdown for Mobile -->
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" data-bs-toggle="collapse" href="#categoriesCollapse" role="button" aria-expanded="false" aria-controls="categoriesCollapse">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-list me-2 text-info storefront-menu-icon"></i>
                                <span>Categories</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                        <div class="collapse" id="categoriesCollapse">
                            <ul class="nav flex-column ps-5">
                                <?php 
                                // Get active categories
                                $categoryModel = new Category();
                                $categories = $categoryModel->getActiveCategories();
                                
                                if(!empty($categories)) :
                                    foreach($categories as $category) :
                                        $categoryImage = !empty($category['image']) ? 
                                            (strpos($category['image'], 'uploads/') === 0 ? 
                                                BASE_URL . $category['image'] : 
                                                BASE_URL . 'uploads/categories/' . $category['image']) : 
                                            BASE_URL . 'assets/img/no-image.png';
                                ?>
                                    <li class="nav-item border-bottom">
                                        <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $category['id']; ?>">
                                            <img src="<?php echo $categoryImage; ?>" 
                                                 alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                                 class="me-2 storefront-dropdown-thumb">
                                            <span><?php echo htmlspecialchars($category['name']); ?></span>
                                        </a>
                                    </li>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=country&action=index">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-flag me-2 text-info storefront-menu-icon"></i>
                                <span><?php echo htmlspecialchars($menuCountryOrigin); ?></span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=brand&action=index">
                            <span><i class="fas fa-tags me-2 text-warning"></i> <?php echo htmlspecialchars($menuAllBrands); ?></span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=index">
                            <span><i class="fas fa-certificate me-2 text-success"></i> <?php echo htmlspecialchars($menuNew); ?></span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=sale">
                            <span><i class="fas fa-tags me-2 text-danger"></i> <?php echo htmlspecialchars($menuSale); ?></span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=sale">
                            <span><i class="fas fa-gift me-2 text-warning"></i> Offers</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>#featured-products">
                            <span><i class="fas fa-star me-2 text-primary"></i> Featured</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>#trending-products">
                            <span><i class="fas fa-fire me-2 text-danger"></i> Best Sellers</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=sale">
                            <span><i class="fas fa-bolt me-2 text-warning"></i> Flash Deals</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=contact">
                            <span><i class="fas fa-envelope me-2 text-info"></i> Contact</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=country&action=index">
                            <span><i class="fas fa-globe-americas me-2 text-success"></i> Countries</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                </ul>
                
                <!-- Account Section -->
                <div class="border-top mt-2 pt-2">
                    <div class="px-3 mb-2 small fw-bold text-uppercase text-muted">My Account</div>
                    <?php if(isLoggedIn()): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=profile">
                                    <i class="fas fa-tachometer-alt me-3 text-primary storefront-menu-icon"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=order">
                                    <i class="fas fa-shopping-bag me-3 text-primary storefront-menu-icon"></i>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=invoice">
                                    <i class="fas fa-file-invoice me-3 text-primary storefront-menu-icon"></i>
                                    <span>Invoices</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=wishlist">
                                    <i class="fas fa-heart me-3 text-primary storefront-menu-icon"></i>
                                    <span>Wishlist</span>
                                </a>
                            </li>
                            <?php if(isAdmin() || isStaff()): ?>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-2" href="<?php echo isAdmin() ? BASE_URL.'?controller=home&action=admin' : BASE_URL.'?controller=pos' ?>">
                                        <i class="fas <?php echo isAdmin() ? 'fa-tachometer-alt' : 'fa-cash-register'; ?> me-3 text-primary storefront-menu-icon"></i>
                                        <span><?php echo isAdmin() ? 'Admin Dashboard' : 'POS System'; ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2 text-danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout">
                                    <i class="fas fa-sign-out-alt me-3 storefront-menu-icon"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    <?php else: ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=login">
                                    <i class="fas fa-sign-in-alt me-3 text-primary storefront-menu-icon"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=register">
                                <i class="fas fa-user-plus me-3 text-primary storefront-menu-icon"></i>
                                <span>Register</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="d-md-none mobile-bottom-nav" aria-label="Mobile bottom navigation">
        <div class="mobile-bottom-nav-inner">
            <a href="<?php echo BASE_URL; ?>" class="mobile-bottom-link">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=category" class="mobile-bottom-link">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                <span>Categories</span>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=wishlist" class="mobile-bottom-link position-relative">
                <i class="fas fa-heart" aria-hidden="true"></i>
                <span>Wishlist</span>
                <?php if (isLoggedIn()): ?>
                <span class="badge rounded-pill bg-danger wishlist-count mobile-bottom-badge">
                    <?php echo (int)wishlist_count(); ?>
                </span>
                <?php endif; ?>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=cart" class="mobile-bottom-link position-relative">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                <span>Cart</span>
                <?php if(isLoggedIn()): ?>
                <span class="badge rounded-pill bg-danger cart-count mobile-bottom-badge">
                    <?php 
                        $cartModel = new Cart();
                        echo $cartModel->getCartCount($_SESSION['user_id']);
                    ?>
                </span>
                <?php endif; ?>
            </a>
            <?php if(isLoggedIn()): ?>
                <a href="<?php echo BASE_URL; ?>?controller=user&action=dashboard" class="mobile-bottom-link">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <span>Profile</span>
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="mobile-bottom-link">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <span>Profile</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light border-bottom d-none d-md-block storefront-desktop-nav storefront-header" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
        <div class="<?php echo $headerContainerClass; ?> storefront-header-shell">
            <!-- Row 1: Logo | Nav | Actions — Row 2: Search (via CSS grid) -->
            <div class="storefront-header-primary">
                <a class="navbar-brand fw-bold storefront-brand mb-0" href="<?php echo BASE_URL; ?>">
                    <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                        <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteName); ?>" class="storefront-logo storefront-logo--desktop">
                    <?php else: ?>
                        <?php echo htmlspecialchars($siteName); ?>
                    <?php endif; ?>
                </a>

                <form class="desktop-nav-search storefront-header-search" action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" role="search">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <div class="storefront-search-shell">
                        <span class="storefront-search-icon" aria-hidden="true"><i class="fas fa-search"></i></span>
                        <select class="storefront-search-category" aria-label="Search in category" data-base-url="<?php echo rtrim(BASE_URL, '/'); ?>">
                            <option value="">All</option>
                            <?php
                            try {
                                $__searchCatModel = new Category();
                                $__searchCats = $__searchCatModel->getActiveCategories();
                                if (!empty($__searchCats)) {
                                    foreach ($__searchCats as $__sc) {
                                        echo '<option value="' . (int)$__sc['id'] . '">' . htmlspecialchars($__sc['name']) . '</option>';
                                    }
                                }
                            } catch (Exception $e) {}
                            ?>
                        </select>
                        <input class="form-control storefront-search-input" type="search" name="keyword" placeholder="Search for products, brands and more..." aria-label="Search" autocomplete="off">
                        <button class="storefront-search-voice" type="button" aria-label="Voice search" title="Voice search">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <button class="btn storefront-search-submit" type="submit" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <div class="storefront-header-actions">
                    <button class="btn p-0 border-0 theme-toggle-btn theme-toggle" type="button" aria-label="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>

                    <?php if(isLoggedIn()) : ?>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle storefront-action-link d-flex align-items-center gap-1" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                <span class="storefront-action-label"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li class="dropdown-header fw-bold">My Account</li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=invoice"><i class="fas fa-file-invoice me-2"></i>Invoices</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order&action=templates"><i class="fas fa-clipboard-list me-2"></i>Order Templates</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order&action=speed"><i class="fas fa-bolt me-2"></i>Speed Order</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=address"><i class="fas fa-address-book me-2"></i>Addresses</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=settings"><i class="fas fa-user-cog me-2"></i>Personal Settings</a></li>
                                <?php if(isAdmin()) : ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                                <?php elseif(isStaff()) : ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=pos"><i class="fas fa-cash-register me-2"></i>POS System</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a class="storefront-action-link" href="<?php echo BASE_URL; ?>?controller=user&action=login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="storefront-action-label">Login</span>
                        </a>
                        <a class="storefront-action-link storefront-action-link--primary" href="<?php echo BASE_URL; ?>?controller=user&action=register">
                            <i class="fas fa-user-plus"></i>
                            <span class="storefront-action-label">Register</span>
                        </a>
                    <?php endif; ?>

                    <a class="storefront-action-link position-relative" href="<?php echo BASE_URL; ?>?controller=wishlist" aria-label="Wishlist">
                        <i class="fas fa-heart"></i>
                        <span class="storefront-action-label">Wishlist</span>
                        <?php if (isLoggedIn()) : ?>
                            <span class="badge bg-danger wishlist-count storefront-badge">
                                <?php echo (int)wishlist_count(); ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <a class="storefront-action-link storefront-action-link--notify position-relative" href="<?php echo BASE_URL; ?>?controller=order" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="storefront-action-label">Alerts</span>
                    </a>

                    <a class="storefront-action-link storefront-cart-link position-relative" href="<?php echo BASE_URL; ?>?controller=cart" aria-label="Cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="storefront-action-label">Cart</span>
                        <?php if(isLoggedIn()) : ?>
                            <span class="badge bg-danger cart-count storefront-badge">
                                <?php 
                                    $cartModel = new Cart();
                                    echo $cartModel->getCartCount($_SESSION['user_id']);
                                ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Row 2: Horizontal Navigation -->
            <div class="collapse navbar-collapse show storefront-header-secondary" id="navbarSupportedContent">
                <ul class="navbar-nav storefront-main-nav mb-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=product&action=index"><?php echo htmlspecialchars($menuAllProducts); ?></a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu nav-dropdown-searchable storefront-mega-menu" aria-labelledby="categoryDropdown">
                            <li class="px-2 py-1 border-bottom">
                                <input type="text" class="form-control form-control-sm nav-dropdown-search" placeholder="Search categories..." autocomplete="off">
                            </li>
                            <li><a class="dropdown-item nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=product&action=index" data-search-text="all products">All Products</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active categories
                            $categoryModel = new Category();
                            $categories = $categoryModel->getActiveCategories();
                            
                            if(!empty($categories)) :
                                foreach($categories as $category) :
                                    $categoryImage = !empty($category['image']) ? 
                                        (strpos($category['image'], 'uploads/') === 0 ? 
                                            BASE_URL . $category['image'] : 
                                            BASE_URL . 'uploads/categories/' . $category['image']) : 
                                        BASE_URL . 'assets/img/no-image.png';
                            ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $category['id']; ?>" data-search-text="<?php echo htmlspecialchars(strtolower($category['name'])); ?>">
                                        <img src="<?php echo $categoryImage; ?>" 
                                             alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                             class="me-2 storefront-dropdown-thumb storefront-dropdown-thumb--lg">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($menuAllBrands); ?>
                        </a>
                        <ul class="dropdown-menu nav-dropdown-searchable storefront-mega-menu" aria-labelledby="brandDropdown">
                            <li class="px-2 py-1 border-bottom">
                                <input type="text" class="form-control form-control-sm nav-dropdown-search" placeholder="Search brands..." autocomplete="off">
                            </li>
                            <li><a class="dropdown-item nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=brand&action=index" data-search-text="all brands">All Brands</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active brands
                            $brandModel = new Brand();
                            $brands = $brandModel->getActiveBrands();
                            
                            if(!empty($brands)) :
                                foreach($brands as $brand) :
                                    $brandImage = !empty($brand['logo']) ? 
                                        $brand['logo'] : 
                                        BASE_URL . 'assets/img/no-image.png';
                            ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['id']; ?>" data-search-text="<?php echo htmlspecialchars(strtolower($brand['name'])); ?>">
                                        <img src="<?php echo $brandImage; ?>" 
                                             alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                             class="me-2 storefront-dropdown-thumb storefront-dropdown-thumb--lg">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="countryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($menuCountryOrigin); ?>
                        </a>
                        <ul class="dropdown-menu nav-dropdown-searchable storefront-mega-menu" aria-labelledby="countryDropdown">
                            <li class="px-2 py-1 border-bottom">
                                <input type="text" class="form-control form-control-sm nav-dropdown-search" placeholder="Search countries..." autocomplete="off">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=country&action=index" data-search-text="all countries">
                                    <div class="me-2 storefront-flag-fallback">
                                        <i class="fas fa-globe-americas"></i>
                                    </div>
                                    All Countries
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active countries
                            $countryModel = new Country();
                            $countries = $countryModel->getActiveCountries();
                            
                            if(!empty($countries)) :
                                foreach($countries as $countryItem) :
                            ?>
                                <?php 
                                    $countryCode = strtolower(substr($countryItem['name'], 0, 2));
                                    $flagImage = !empty($countryItem['flag_image']) ? 
                                        BASE_URL . 'uploads/flags/' . $countryItem['flag_image'] : 
                                        'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center nav-dropdown-item" href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo (int)$countryItem['id']; ?>" data-search-text="<?php echo htmlspecialchars(strtolower($countryItem['name'])); ?>">
                                        <img src="<?php echo $flagImage; ?>" 
                                             alt="<?php echo htmlspecialchars($countryItem['name']); ?>" 
                                             class="me-2 storefront-flag-thumb">
                                        <?php echo htmlspecialchars($countryItem['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="nav-link storefront-nav-pill storefront-nav-pill--new"><?php echo htmlspecialchars($menuNew); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=sale" class="nav-link storefront-nav-pill storefront-nav-pill--sale"><?php echo htmlspecialchars($menuSale); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=sale" class="nav-link storefront-nav-pill storefront-nav-pill--offers">Offers</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>#featured-products" class="nav-link">Featured</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>#trending-products" class="nav-link">Best Sellers</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=sale" class="nav-link storefront-nav-pill storefront-nav-pill--flash">Flash Deals</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>?controller=contact" class="nav-link">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Navbar dropdown search - inline script (runs before main.js) -->
    <script>
    (function() {
        function initNavDropdownSearch() {
            document.addEventListener('input', function(e) {
                var input = e.target;
                if (!input || !input.classList || !input.classList.contains('nav-dropdown-search')) return;
                var query = (input.value || '').trim().toLowerCase();
                var menu = input.closest('.nav-dropdown-searchable');
                if (!menu) return;
                var items = menu.querySelectorAll('.nav-dropdown-item');
                for (var i = 0; i < items.length; i++) {
                    var item = items[i];
                    var text = (item.getAttribute('data-search-text') || '').toLowerCase();
                    var li = item.closest('li');
                    if (li) li.style.display = (query === '' || text.indexOf(query) !== -1) ? '' : 'none';
                }
            });
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList && e.target.classList.contains('nav-dropdown-search')) {
                    e.stopPropagation();
                }
            }, true);
            document.addEventListener('hidden.bs.dropdown', function(e) {
                var dropdown = (e.target && e.target.closest) ? e.target.closest('.dropdown') : e.target;
                var menu = dropdown ? dropdown.querySelector('.nav-dropdown-searchable') : null;
                if (!menu) return;
                var searchInput = menu.querySelector('.nav-dropdown-search');
                if (searchInput) {
                    searchInput.value = '';
                    var items = menu.querySelectorAll('.nav-dropdown-item');
                    for (var i = 0; i < items.length; i++) {
                        var li = items[i].closest('li');
                        if (li) li.style.display = '';
                    }
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNavDropdownSearch);
        } else {
            initNavDropdownSearch();
        }
    })();
    </script>

    <!-- Flash Messages -->
    <div class="container storefront-flash-wrap">
        <?php flash('register_success'); ?>
        <?php flash('login_success'); ?>
        <?php flash('user_error'); ?>
        <?php flash('cart_success'); ?>
        <?php flash('cart_error'); ?>
        <?php flash('order_success'); ?>
        <?php flash('order_error'); ?>
        <?php flash('product_success'); ?>
        <?php flash('product_error'); ?>
        <?php flash('profile_success'); ?>
        <?php flash('password_success'); ?>
        <?php flash('contact_success'); ?>
        <?php flash('pos_success'); ?>
        <?php flash('pos_error'); ?>
        <?php flash('newsletter_success'); ?>
        <?php flash('newsletter_error'); ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var root = document.documentElement;
            if (!root.getAttribute('data-theme')) {
                root.setAttribute('data-theme', '<?php echo htmlspecialchars($themeDefaultMode); ?>');
            }

            function setIcon(mode) {
                var buttons = document.querySelectorAll('.theme-toggle');
                if (!buttons || !buttons.length) return;
                buttons.forEach(function(btn) {
                    var icon = btn.querySelector('i');
                    if (!icon) return;
                    icon.className = (mode === 'dark') ? 'fas fa-sun' : 'fas fa-moon';
                });
            }

            function setMode(mode) {
                root.setAttribute('data-theme', mode);
                try { localStorage.setItem('theme_mode', mode); } catch (e) {}
                setIcon(mode);
                
                // Force update navbar backgrounds for dark theme
                const navbarElements = document.querySelectorAll('.navbar, .mobile-top-bar, #mobileNavbar, .mobile-bottom-nav');
                navbarElements.forEach(element => {
                    if (mode === 'dark') {
                        // Remove inline background style to let CSS dark mode rules take over
                        element.style.removeProperty('background');
                        element.style.removeProperty('background-color');
                    } else {
                        // Restore light theme background if needed
                        const bgColor = '<?php echo htmlspecialchars($headerBgColor); ?>';
                        if (bgColor && element.classList.contains('navbar')) {
                            element.style.background = bgColor;
                            element.style.backgroundColor = bgColor;
                        }
                    }
                });
                
                // Force update all cards and sections for dark theme
                if (mode === 'dark') {
                    document.querySelectorAll('.product-card, .category-card, .card').forEach(card => {
                        card.style.backgroundColor = '';
                        card.style.color = '';
                    });
                }
            }

            setIcon(root.getAttribute('data-theme'));

            var toggles = document.querySelectorAll('.theme-toggle');
            if (toggles && toggles.length) {
                toggles.forEach(function(toggle) {
                    if (toggle.dataset.themeBound === '1') return;
                    toggle.dataset.themeBound = '1';
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        var current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                        setMode(current === 'dark' ? 'light' : 'dark');
                    });
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var brandsSection = document.querySelector('#brands');
            if (!brandsSection) return;

            // Find current grid row inside brands section
            var gridRow = brandsSection.querySelector('.row.g-2.g-md-3.justify-content-center');
            if (!gridRow) return;

            // Collect brand item columns
            var items = Array.prototype.slice.call(gridRow.children).filter(function (el) {
                return el.classList && el.classList.contains('col-4');
            });

            if (!items.length) return;

            // Build slider container + track
            var slider = document.createElement('div');
            slider.id = 'brandsSlider';
            slider.style.display = 'flex';
            slider.style.justifyContent = items.length <= 8 ? 'center' : 'flex-start';
            slider.style.overflowX = 'auto';
            slider.style.scrollBehavior = 'smooth';
            slider.style.gap = '10px';
            slider.style.padding = '10px 30px';
            slider.style.cursor = 'grab';
            slider.style.msOverflowStyle = 'none';
            slider.style.scrollbarWidth = 'none';

            // Hide scrollbar (webkit)
            var styleEl = document.createElement('style');
            styleEl.textContent = '#brandsSlider::-webkit-scrollbar{display:none;}';
            brandsSection.appendChild(styleEl);

            var track = document.createElement('div');
            track.id = 'brandsTrack';
            track.style.display = 'flex';
            track.style.gap = '10px';

            // Move items into track and normalize tile width/background
            items.forEach(function (col) {
                var tile = document.createElement('div');
                tile.style.flex = '0 0 auto';
                tile.style.width = '160px';

                // Find inner link/tile
                var link = col.querySelector('a');
                if (link) {
                    // Remove grid column padding/structure by moving the existing link
                    tile.appendChild(link);

                    var card = link.querySelector('.brand-card');
                    if (card) {
                        card.style.background = 'transparent';
                    }

                    var logoBox = link.querySelector('.brand-logo-container');
                    if (logoBox) {
                        logoBox.style.width = '100%';
                        logoBox.style.height = '80px';
                        logoBox.style.display = 'flex';
                        logoBox.style.alignItems = 'center';
                        logoBox.style.justifyContent = 'center';
                    }
                }

                track.appendChild(tile);
            });

            // Replace grid with slider
            gridRow.parentNode.replaceChild(slider, gridRow);
            slider.appendChild(track);

            // Auto-scroll behavior with overflow duplication
            var isPaused = false;
            var rafId = null;
            var speedPxPerFrame = 0.6;

            function ensureOverflow() {
                var maxLoops = 3;
                var loops = 0;
                while (slider.scrollWidth <= slider.clientWidth + 1 && track.children.length > 0 && loops < maxLoops) {
                    var children = Array.prototype.slice.call(track.children);
                    children.forEach(function (node) {
                        track.appendChild(node.cloneNode(true));
                    });
                    loops++;
                }

                if (slider.scrollWidth > slider.clientWidth + 1) {
                    slider.style.justifyContent = 'flex-start';
                }
            }

            function maxScrollLeft() {
                return Math.max(0, slider.scrollWidth - slider.clientWidth);
            }

            function tick() {
                if (!isPaused) {
                    var max = maxScrollLeft();
                    if (max > 0) {
                        slider.scrollLeft += speedPxPerFrame;
                        if (slider.scrollLeft >= max - 1) {
                            slider.scrollLeft = 0;
                        }
                    }
                }
                rafId = window.requestAnimationFrame(tick);
            }

            function pause() { isPaused = true; }
            function resume() { isPaused = false; }

            ensureOverflow();
            slider.addEventListener('mouseenter', pause);
            slider.addEventListener('mouseleave', resume);
            slider.addEventListener('touchstart', pause, { passive: true });
            slider.addEventListener('touchend', resume, { passive: true });
            slider.addEventListener('touchcancel', resume, { passive: true });

            window.addEventListener('beforeunload', function () {
                if (rafId) window.cancelAnimationFrame(rafId);
            });

            tick();
        });
    </script>

    <!-- Main Content -->
    <main class="container py-4 storefront-main">
