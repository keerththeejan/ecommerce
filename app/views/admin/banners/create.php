<?php
/**
 * Banner Create — Enterprise DAM form (visual layer only)
 * Backend contracts: name="title", name="description", name="status", name="image"
 * POST ?controller=banner&action=create multipart
 */
require_once APP_PATH . 'views/admin/layouts/header.php';
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/banner-form.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid banner-form-page py-3 py-md-4 px-2 px-sm-3" id="bannerCreatePage">
    <div class="bcf-toast-host" id="bcfToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="bcf-header">
        <div>
            <nav class="bcf-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span class="sep">›</span>
                <span>Website</span>
                <span class="sep">›</span>
                <a href="<?php echo BASE_URL; ?>?controller=banner">Banner Management</a>
                <span class="sep">›</span>
                <span aria-current="page">Create</span>
            </nav>
            <h1 class="bcf-title">Banner Management</h1>
            <p class="bcf-subtitle">Create a storefront-ready banner with image upload, live preview, and publishing controls.</p>
        </div>
        <div class="bcf-actions">
            <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-outline-secondary bcf-btn" id="bcfBackTop">
                <i class="bi bi-arrow-left" aria-hidden="true"></i><span>Back</span>
            </a>
            <button type="button" class="btn btn-outline-secondary bcf-btn" id="bcfSaveDraftBtn">
                <i class="bi bi-file-earmark" aria-hidden="true"></i><span>Save Draft</span>
            </button>
            <button type="button" class="btn btn-outline-primary bcf-btn" id="bcfPreviewBtn" data-bs-toggle="modal" data-bs-target="#bcfPreviewModal">
                <i class="bi bi-eye" aria-hidden="true"></i><span>Preview</span>
            </button>
            <button type="submit" form="bannerCreateForm" class="btn bcf-btn bcf-btn-primary" id="bcfSaveTop">
                <i class="bi bi-check2-circle" aria-hidden="true"></i><span>Save Banner</span>
            </button>
        </div>
    </div>

    <div class="bcf-progress" aria-hidden="true"><span id="bcfFormProgress"></span></div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Keep field names matching BannerController::create() -->
    <form id="bannerCreateForm" method="POST" action="<?php echo BASE_URL; ?>?controller=banner&action=create" enctype="multipart/form-data" novalidate>
        <div class="row g-3">
            <div class="col-12 col-xl-9">

                <!-- SECTION 1: General -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-info-circle text-primary" aria-hidden="true"></i> General Information</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-lg-7">
                                <label for="title" class="form-label">Banner Title <span class="req">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255" autocomplete="off" aria-describedby="titleHelp" placeholder="e.g. Summer Sale Hero">
                                <div class="invalid-feedback">Please enter a banner title.</div>
                                <div class="form-text" id="titleHelp">Customer-facing title for admin reference and SEO.</div>
                            </div>
                            <div class="col-12 col-lg-5">
                                <label for="bcfBannerCode" class="form-label">Banner Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bcfBannerCode" value="" autocomplete="off" aria-describedby="codeHelp">
                                    <button type="button" class="btn btn-outline-secondary" id="bcfRegenCode" title="Auto generate" aria-label="Auto generate banner code"><i class="bi bi-arrow-repeat"></i></button>
                                </div>
                                <div class="form-text" id="codeHelp">Auto-generated from title. <span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <label for="bcfBannerType" class="form-label">Banner Type</label>
                                <select class="form-select" id="bcfBannerType" aria-describedby="typeHelp">
                                    <option value="hero">Hero Banner</option>
                                    <option value="slider" selected>Homepage Slider</option>
                                    <option value="popup">Popup Banner</option>
                                    <option value="sidebar">Sidebar Banner</option>
                                    <option value="category">Category Banner</option>
                                    <option value="brand">Brand Banner</option>
                                    <option value="offer">Offer Banner</option>
                                    <option value="flash">Flash Sale Banner</option>
                                    <option value="footer">Footer Banner</option>
                                </select>
                                <div class="form-text" id="typeHelp"><span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <label for="bcfPage" class="form-label">Page</label>
                                <select class="form-select" id="bcfPage">
                                    <option value="homepage" selected>Homepage</option>
                                    <option value="products">Products</option>
                                    <option value="categories">Categories</option>
                                    <option value="brands">Brands</option>
                                    <option value="checkout">Checkout</option>
                                    <option value="contact">Contact</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-6 col-lg-2">
                                <label for="bcfPosition" class="form-label">Position</label>
                                <input type="number" class="form-control" id="bcfPosition" value="1" min="1" max="99">
                            </div>
                            <div class="col-6 col-md-6 col-lg-2">
                                <label for="bcfDisplayOrder" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="bcfDisplayOrder" value="10" min="0" max="999">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 2: Image -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-image text-primary" aria-hidden="true"></i> Image Management</h2>
                        <span class="badge-soft">Upload</span>
                    </div>
                    <div class="bcf-card-body">
                        <label for="image" class="form-label">Desktop Banner <span class="req">*</span></label>
                        <div class="bcf-dropzone" id="bcfDropzone" role="button" tabindex="0" aria-label="Upload banner image">
                            <div class="bcf-drop-icon" aria-hidden="true"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="fw-semibold" id="bcfDropLabel">Drag &amp; drop image here, or click to browse</div>
                            <div class="form-text mb-0">WEBP, PNG, JPG, JPEG · Recommended 1920×800 · Max ~5MB</div>
                            <input type="file" id="image" name="image" accept="image/*" required aria-describedby="imageHelp">
                        </div>
                        <div class="invalid-feedback d-block d-none" id="imageFeedback">Please select a banner image.</div>
                        <div class="form-text" id="imageHelp">This file is saved by the existing upload logic. Mobile/tablet slots below are visual aids only.</div>

                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Mobile Banner</label>
                                <input type="file" class="form-control" id="bcfMobileImage" accept="image/*">
                                <div class="form-text"><span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Tablet Banner</label>
                                <input type="file" class="form-control" id="bcfTabletImage" accept="image/*">
                                <div class="form-text"><span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" class="form-control" id="bcfThumbImage" accept="image/*">
                                <div class="form-text"><span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="bcfReplaceImage"><i class="bi bi-arrow-repeat me-1"></i>Replace</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="bcfClearImage"><i class="bi bi-trash me-1"></i>Delete</button>
                            <span class="small text-muted align-self-center" id="bcfFileMeta">No file selected</span>
                        </div>
                    </div>
                </section>

                <!-- SECTION 3: Content -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-card-text text-primary" aria-hidden="true"></i> Content</h2>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Short Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" maxlength="2000" aria-describedby="descHelp descCount" placeholder="Optional banner description"></textarea>
                                <div class="d-flex justify-content-between">
                                    <div class="form-text" id="descHelp">Saved with the banner record.</div>
                                    <div class="bcf-char-count" id="descCount">0 / 2000</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfHeading" class="form-label">Banner Heading</label>
                                <input type="text" class="form-control" id="bcfHeading" maxlength="120" placeholder="Headline on the creative">
                                <div class="form-text"><span class="bcf-ui-only-hint">(UI only — live preview)</span></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfSubHeading" class="form-label">Sub Heading</label>
                                <input type="text" class="form-control" id="bcfSubHeading" maxlength="160" placeholder="Supporting line">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bcfBtnText" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="bcfBtnText" maxlength="40" placeholder="Shop Now" value="Shop Now">
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="bcfBtnUrl" class="form-label">Button URL</label>
                                <input type="url" class="form-control" id="bcfBtnUrl" placeholder="https://…" inputmode="url">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="bcfBtnTarget" class="form-label">Open In</label>
                                <select class="form-select" id="bcfBtnTarget">
                                    <option value="_self">Same Tab</option>
                                    <option value="_blank">New Tab</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 4: Display -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-sliders text-primary" aria-hidden="true"></i> Display Settings</h2>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bcfAnimation" class="form-label">Display Animation</label>
                                <select class="form-select" id="bcfAnimation">
                                    <option value="fade" selected>Fade</option>
                                    <option value="slide">Slide</option>
                                    <option value="zoom">Zoom</option>
                                    <option value="bounce">Bounce</option>
                                </select>
                                <div class="form-text"><span class="bcf-ui-only-hint">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bcfAlign" class="form-label">Text Alignment</label>
                                <select class="form-select" id="bcfAlign">
                                    <option value="left" selected>Left</option>
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfOverlay" class="form-label">Overlay Opacity <span id="bcfOverlayVal">40%</span></label>
                                <input type="range" class="form-range" id="bcfOverlay" min="0" max="80" value="40">
                            </div>
                            <div class="col-12 col-md-6 d-flex flex-column justify-content-end gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bcfFeatured" checked>
                                    <label class="form-check-label" for="bcfFeatured">Featured Banner</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bcfShowHome" checked>
                                    <label class="form-check-label" for="bcfShowHome">Show On Homepage</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 5: Scheduling -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-calendar-event text-primary" aria-hidden="true"></i> Scheduling</h2>
                        <span class="badge-soft">Optional</span>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="bcfStartDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="bcfStartDate">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="bcfStartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="bcfStartTime" value="00:00">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="bcfEndDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="bcfEndDate">
                            </div>
                            <div class="col-6 col-md-3">
                                <label for="bcfEndTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="bcfEndTime" value="23:59">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfTimezone" class="form-label">Time Zone</label>
                                <select class="form-select" id="bcfTimezone">
                                    <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">America/New_York</option>
                                    <option value="Europe/London">Europe/London</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bcfAutoExpire" checked>
                                    <label class="form-check-label" for="bcfAutoExpire">Auto Expire after end date</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-light border mb-0 small" id="bcfCountdownPreview">
                                    <i class="bi bi-hourglass-split me-1"></i> Set start/end dates to preview schedule status.
                                    <span class="bcf-ui-only-hint">(UI only)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 6: SEO -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-search text-primary" aria-hidden="true"></i> SEO</h2>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="bcfAltText" class="form-label">Alt Text</label>
                                <input type="text" class="form-control" id="bcfAltText" maxlength="125">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfImageTitle" class="form-label">Image Title</label>
                                <input type="text" class="form-control" id="bcfImageTitle" maxlength="125">
                            </div>
                            <div class="col-12">
                                <label for="bcfMetaTitle" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="bcfMetaTitle" maxlength="70">
                                <div class="bcf-char-count" id="metaTitleCount">0 / 70</div>
                            </div>
                            <div class="col-12">
                                <label for="bcfMetaDesc" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="bcfMetaDesc" rows="2" maxlength="160"></textarea>
                                <div class="bcf-char-count" id="metaDescCount">0 / 160</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfCanonical" class="form-label">Canonical URL</label>
                                <input type="url" class="form-control" id="bcfCanonical" placeholder="https://…">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Open Graph / Twitter</label>
                                <div class="form-text mb-0"><span class="bcf-ui-only-hint">Uses the uploaded banner image as OG/Twitter preview (UI only).</span></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 7: Advanced -->
                <section class="bcf-card">
                    <div class="bcf-card-header">
                        <h2><i class="bi bi-gear text-primary" aria-hidden="true"></i> Advanced</h2>
                    </div>
                    <div class="bcf-card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label for="bcfPriority" class="form-label">Priority</label>
                                <input type="number" class="form-control" id="bcfPriority" value="5" min="1" max="10">
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label">Display Device</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="bcfDevDesktop" checked>
                                        <label class="form-check-label" for="bcfDevDesktop">Desktop</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="bcfDevTablet" checked>
                                        <label class="form-check-label" for="bcfDevTablet">Tablet</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="bcfDevMobile" checked>
                                        <label class="form-check-label" for="bcfDevMobile">Mobile</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bcfAudience" class="form-label">Target Audience</label>
                                <select class="form-select" id="bcfAudience">
                                    <option value="all" selected>All Visitors</option>
                                    <option value="guest">Guest</option>
                                    <option value="registered">Registered Users</option>
                                    <option value="vip">VIP Customers</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfCountry" class="form-label">Country</label>
                                <input type="text" class="form-control" id="bcfCountry" placeholder="All countries">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bcfLanguage" class="form-label">Language</label>
                                <select class="form-select" id="bcfLanguage">
                                    <option value="en" selected>English</option>
                                    <option value="hi">Hindi</option>
                                    <option value="ta">Tamil</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>
                        <p class="bcf-ui-only-hint mt-2 mb-0">Advanced targeting fields are UI-only and are not saved by the current backend.</p>
                    </div>
                </section>
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-12 col-xl-3">
                <aside class="bcf-side">
                    <div class="bcf-card">
                        <div class="bcf-card-header">
                            <h2 style="font-size:0.95rem;"><i class="bi bi-eye text-primary"></i> Live Preview</h2>
                        </div>
                        <div class="bcf-card-body">
                            <div class="bcf-device-tabs" role="tablist">
                                <button type="button" class="active" data-aspect="16/9" aria-pressed="true"><i class="bi bi-display"></i> Desktop</button>
                                <button type="button" data-aspect="4/3" aria-pressed="false"><i class="bi bi-tablet"></i> Tablet</button>
                                <button type="button" data-aspect="9/16" aria-pressed="false"><i class="bi bi-phone"></i> Mobile</button>
                            </div>
                            <div class="bcf-live-preview" id="bcfLivePreview" aria-live="polite">
                                <img id="bcfPreviewImg" src="" alt="Banner preview">
                                <div class="bcf-preview-placeholder"><i class="bi bi-image d-block mb-1" style="font-size:1.5rem;"></i>Upload an image to preview</div>
                                <div class="bcf-preview-overlay">
                                    <p class="bcf-preview-heading" id="bcfPrevHeading"></p>
                                    <p class="bcf-preview-sub" id="bcfPrevSub"></p>
                                    <span class="bcf-preview-cta" id="bcfPrevCta">Shop Now</span>
                                </div>
                            </div>
                            <div class="small text-muted mt-2" id="bcfPreviewTitle">Untitled banner</div>
                        </div>
                    </div>

                    <div class="bcf-card">
                        <div class="bcf-card-header"><h2 style="font-size:0.95rem;">Publishing</h2></div>
                        <div class="bcf-card-body">
                            <ul class="bcf-side-list mb-0">
                                <li><span class="k">Banner Status</span><span class="v" id="bcfSideStatus">Active</span></li>
                                <li><span class="k">Publishing</span><span class="v" id="bcfSidePublish">Ready</span></li>
                                <li><span class="k">Type</span><span class="v" id="bcfSideType">Homepage Slider</span></li>
                                <li><span class="k">Page</span><span class="v" id="bcfSidePage">Homepage</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bcf-card">
                        <div class="bcf-card-header"><h2 style="font-size:0.95rem;">SEO Score</h2></div>
                        <div class="bcf-card-body">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Completeness</span>
                                <strong id="bcfSeoScoreLabel">0%</strong>
                            </div>
                            <div class="bcf-seo-meter" aria-hidden="true"><span id="bcfSeoMeter"></span></div>
                            <div class="small text-muted mt-2" id="bcfSeoHint">Add title, alt text, and meta description.</div>
                        </div>
                    </div>

                    <div class="bcf-card">
                        <div class="bcf-card-header"><h2 style="font-size:0.95rem;">Image Quality</h2></div>
                        <div class="bcf-card-body">
                            <ul class="bcf-side-list mb-0">
                                <li><span class="k">File Size</span><span class="v" id="bcfSideSize">—</span></li>
                                <li><span class="k">Type</span><span class="v" id="bcfSideMime">—</span></li>
                                <li><span class="k">Dimensions</span><span class="v" id="bcfSideDims">—</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bcf-card">
                        <div class="bcf-card-header"><h2 style="font-size:0.95rem;">Quick Tips</h2></div>
                        <div class="bcf-card-body">
                            <ul class="small text-muted mb-0 ps-3">
                                <li class="mb-1">Use 1920×800 for desktop heroes.</li>
                                <li class="mb-1">Keep text overlay under 40% of the frame.</li>
                                <li class="mb-1">Compress large JPG/PNG before upload.</li>
                                <li>Title and image are required to save.</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </form>

    <!-- Sticky action bar -->
    <div class="bcf-sticky-bar" role="toolbar" aria-label="Banner actions">
        <div class="bcf-sticky-inner">
            <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-outline-secondary bcf-btn">Cancel</a>
            <button type="button" class="btn btn-outline-primary bcf-btn" data-bs-toggle="modal" data-bs-target="#bcfPreviewModal">
                <i class="bi bi-eye me-1"></i>Preview
            </button>
            <button type="button" class="btn btn-outline-secondary bcf-btn" id="bcfSaveAndNew">Save &amp; New</button>
            <button type="submit" form="bannerCreateForm" class="btn bcf-btn bcf-btn-primary" id="bcfSaveClose">
                <span class="spinner-border spinner-border-sm d-none" id="bcfSaveSpinner" role="status" aria-hidden="true"></span>
                <i class="bi bi-check2-circle me-1"></i>Save Banner
            </button>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="bcfPreviewModal" tabindex="-1" aria-labelledby="bcfPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="bcfPreviewModalLabel">Banner Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bcf-live-preview" id="bcfModalPreview" style="aspect-ratio:16/9;">
                    <img id="bcfModalPreviewImg" src="" alt="Preview">
                    <div class="bcf-preview-placeholder">Upload an image to preview</div>
                    <div class="bcf-preview-overlay">
                        <p class="bcf-preview-heading" id="bcfModalHeading"></p>
                        <p class="bcf-preview-sub" id="bcfModalSub"></p>
                        <span class="bcf-preview-cta" id="bcfModalCta">Shop Now</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="bannerCreateForm" class="btn btn-primary">Save Banner</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var form = document.getElementById('bannerCreateForm');
    var titleInput = document.getElementById('title');
    var descInput = document.getElementById('description');
    var statusInput = document.getElementById('status');
    var imageInput = document.getElementById('image');
    var dropzone = document.getElementById('bcfDropzone');
    var dropLabel = document.getElementById('bcfDropLabel');
    var preview = document.getElementById('bcfLivePreview');
    var previewImg = document.getElementById('bcfPreviewImg');
    var modalPreview = document.getElementById('bcfModalPreview');
    var modalImg = document.getElementById('bcfModalPreviewImg');
    var progressBar = document.getElementById('bcfFormProgress');

    function showToast(msg, type) {
        var host = document.getElementById('bcfToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'bcf-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
    }

    function slugify(str) {
        return String(str || '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '')
            .substring(0, 40) || 'banner';
    }

    function regenCode() {
        var code = document.getElementById('bcfBannerCode');
        if (!code) return;
        var base = slugify(titleInput && titleInput.value);
        code.value = 'BNR-' + base.toUpperCase().replace(/-/g, '').substring(0, 12) + '-' + String(Date.now()).slice(-4);
    }

    var regenBtn = document.getElementById('bcfRegenCode');
    if (regenBtn) regenBtn.addEventListener('click', regenCode);
    if (titleInput) {
        titleInput.addEventListener('blur', function() {
            var code = document.getElementById('bcfBannerCode');
            if (code && !code.value) regenCode();
        });
    }

    function formatBytes(n) {
        if (!n && n !== 0) return '—';
        if (n < 1024) return n + ' B';
        if (n < 1048576) return (n / 1024).toFixed(1) + ' KB';
        return (n / 1048576).toFixed(2) + ' MB';
    }

    function applyFile(file) {
        var meta = document.getElementById('bcfFileMeta');
        var sideSize = document.getElementById('bcfSideSize');
        var sideMime = document.getElementById('bcfSideMime');
        var sideDims = document.getElementById('bcfSideDims');
        if (!file) {
            if (meta) meta.textContent = 'No file selected';
            if (sideSize) sideSize.textContent = '—';
            if (sideMime) sideMime.textContent = '—';
            if (sideDims) sideDims.textContent = '—';
            if (preview) preview.classList.remove('has-image');
            if (modalPreview) modalPreview.classList.remove('has-image');
            if (dropzone) dropzone.classList.remove('has-file');
            if (dropLabel) dropLabel.textContent = 'Drag & drop image here, or click to browse';
            if (previewImg) previewImg.removeAttribute('src');
            if (modalImg) modalImg.removeAttribute('src');
            updateProgress();
            return;
        }
        var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (file.type && allowed.indexOf(file.type) === -1) {
            showToast('Unsupported format. Use JPG, PNG, GIF, WEBP, or SVG.', 'warning');
        }
        if (file.size > 5 * 1024 * 1024) {
            showToast('File is larger than 5MB — consider compressing before upload.', 'warning');
        }
        if (meta) meta.textContent = file.name + ' · ' + formatBytes(file.size);
        if (sideSize) sideSize.textContent = formatBytes(file.size);
        if (sideMime) sideMime.textContent = (file.type || 'image').replace('image/', '').toUpperCase();
        if (dropLabel) dropLabel.textContent = file.name;
        if (dropzone) dropzone.classList.add('has-file');

        var reader = new FileReader();
        reader.onload = function(e) {
            var url = e.target.result;
            if (previewImg) previewImg.src = url;
            if (modalImg) modalImg.src = url;
            if (preview) preview.classList.add('has-image');
            if (modalPreview) modalPreview.classList.add('has-image');
            var img = new Image();
            img.onload = function() {
                if (sideDims) sideDims.textContent = img.width + '×' + img.height;
                updateProgress();
            };
            img.src = url;
        };
        reader.readAsDataURL(file);
        updateProgress();
    }

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            applyFile(imageInput.files && imageInput.files[0] ? imageInput.files[0] : null);
            var fb = document.getElementById('imageFeedback');
            if (fb) fb.classList.add('d-none');
        });
    }

    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function(ev) {
            dropzone.addEventListener(ev, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function(ev) {
            dropzone.addEventListener(ev, function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('is-dragover');
            });
        });
        dropzone.addEventListener('drop', function(e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (files && files[0] && imageInput) {
                try {
                    var dt = new DataTransfer();
                    dt.items.add(files[0]);
                    imageInput.files = dt.files;
                } catch (err) {
                    /* some browsers block DataTransfer assign — user can click to browse */
                }
                applyFile(files[0]);
            }
        });
        dropzone.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (imageInput) imageInput.click();
            }
        });
    }

    var replaceBtn = document.getElementById('bcfReplaceImage');
    if (replaceBtn) replaceBtn.addEventListener('click', function() {
        if (imageInput) imageInput.click();
    });
    var clearBtn = document.getElementById('bcfClearImage');
    if (clearBtn) clearBtn.addEventListener('click', function() {
        if (imageInput) imageInput.value = '';
        applyFile(null);
        showToast('Image cleared', 'info');
    });

    function syncPreviewCopy() {
        var heading = document.getElementById('bcfHeading');
        var sub = document.getElementById('bcfSubHeading');
        var btn = document.getElementById('bcfBtnText');
        var title = (titleInput && titleInput.value) || 'Untitled banner';
        var h = (heading && heading.value) || title;
        var s = (sub && sub.value) || (descInput && descInput.value) || '';
        var c = (btn && btn.value) || 'Shop Now';
        var hasContent = !!(h || s);
        [['bcfPrevHeading', h], ['bcfPrevSub', s], ['bcfPrevCta', c], ['bcfModalHeading', h], ['bcfModalSub', s], ['bcfModalCta', c]].forEach(function(pair) {
            var el = document.getElementById(pair[0]);
            if (el) el.textContent = pair[1];
        });
        var pt = document.getElementById('bcfPreviewTitle');
        if (pt) pt.textContent = title;
        if (preview) preview.classList.toggle('has-content', hasContent);
        if (modalPreview) modalPreview.classList.toggle('has-content', hasContent);
    }

    function syncSidebar() {
        var st = document.getElementById('bcfSideStatus');
        var pub = document.getElementById('bcfSidePublish');
        var typ = document.getElementById('bcfSideType');
        var pg = document.getElementById('bcfSidePage');
        if (st && statusInput) st.textContent = statusInput.options[statusInput.selectedIndex].text;
        if (pub && statusInput) pub.textContent = statusInput.value === 'active' ? 'Ready to publish' : 'Draft / Inactive';
        var typeSel = document.getElementById('bcfBannerType');
        var pageSel = document.getElementById('bcfPage');
        if (typ && typeSel) typ.textContent = typeSel.options[typeSel.selectedIndex].text;
        if (pg && pageSel) pg.textContent = pageSel.options[pageSel.selectedIndex].text;
    }

    function updateSeoScore() {
        var score = 0;
        if (titleInput && titleInput.value.trim()) score += 25;
        if (descInput && descInput.value.trim()) score += 15;
        if (imageInput && imageInput.files && imageInput.files.length) score += 25;
        var alt = document.getElementById('bcfAltText');
        var mt = document.getElementById('bcfMetaTitle');
        var md = document.getElementById('bcfMetaDesc');
        if (alt && alt.value.trim()) score += 15;
        if (mt && mt.value.trim()) score += 10;
        if (md && md.value.trim()) score += 10;
        var meter = document.getElementById('bcfSeoMeter');
        var label = document.getElementById('bcfSeoScoreLabel');
        var hint = document.getElementById('bcfSeoHint');
        if (meter) meter.style.width = score + '%';
        if (label) label.textContent = score + '%';
        if (hint) {
            if (score >= 80) hint.textContent = 'Strong completeness for launch.';
            else if (score >= 50) hint.textContent = 'Good start — add alt text and meta fields.';
            else hint.textContent = 'Add title, image, and SEO fields.';
        }
    }

    function updateProgress() {
        var filled = 0;
        var total = 4;
        if (titleInput && titleInput.value.trim()) filled++;
        if (imageInput && imageInput.files && imageInput.files.length) filled++;
        if (descInput && descInput.value.trim()) filled++;
        if (statusInput) filled++;
        var pct = Math.round((filled / total) * 100);
        if (progressBar) progressBar.style.width = pct + '%';
        updateSeoScore();
        syncPreviewCopy();
        syncSidebar();
    }

    function bindCount(inputId, countId, max) {
        var input = document.getElementById(inputId);
        var count = document.getElementById(countId);
        if (!input || !count) return;
        function tick() { count.textContent = (input.value || '').length + ' / ' + max; }
        input.addEventListener('input', function() { tick(); updateProgress(); });
        tick();
    }
    bindCount('description', 'descCount', 2000);
    bindCount('bcfMetaTitle', 'metaTitleCount', 70);
    bindCount('bcfMetaDesc', 'metaDescCount', 160);

    ['title', 'description', 'status', 'bcfHeading', 'bcfSubHeading', 'bcfBtnText', 'bcfBannerType', 'bcfPage', 'bcfAltText', 'bcfMetaTitle', 'bcfMetaDesc'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('input', updateProgress);
        if (el) el.addEventListener('change', updateProgress);
    });

    document.querySelectorAll('.bcf-device-tabs button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.bcf-device-tabs button').forEach(function(b) {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            if (preview) preview.style.aspectRatio = btn.getAttribute('data-aspect') || '16/9';
        });
    });

    var overlay = document.getElementById('bcfOverlay');
    var overlayVal = document.getElementById('bcfOverlayVal');
    if (overlay) {
        overlay.addEventListener('input', function() {
            if (overlayVal) overlayVal.textContent = overlay.value + '%';
            var ov = preview ? preview.querySelector('.bcf-preview-overlay') : null;
            if (ov) ov.style.background = 'linear-gradient(90deg, rgba(15,23,42,' + (overlay.value / 100) + '), transparent 70%)';
        });
    }

    function updateCountdown() {
        var el = document.getElementById('bcfCountdownPreview');
        var sd = document.getElementById('bcfStartDate');
        var ed = document.getElementById('bcfEndDate');
        if (!el) return;
        if (!sd || !sd.value) {
            el.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Set start/end dates to preview schedule status. <span class="bcf-ui-only-hint">(UI only)</span>';
            return;
        }
        var now = new Date();
        var start = new Date(sd.value + 'T' + ((document.getElementById('bcfStartTime') || {}).value || '00:00'));
        var end = ed && ed.value ? new Date(ed.value + 'T' + ((document.getElementById('bcfEndTime') || {}).value || '23:59')) : null;
        var status = 'Upcoming';
        if (now >= start && (!end || now <= end)) status = 'Live';
        if (end && now > end) status = 'Expired';
        el.innerHTML = '<i class="bi bi-broadcast me-1"></i> Schedule status: <strong>' + status + '</strong> · Start ' + sd.value + (end ? ' → ' + ed.value : '') + ' <span class="bcf-ui-only-hint">(UI only)</span>';
    }
    ['bcfStartDate', 'bcfStartTime', 'bcfEndDate', 'bcfEndTime'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('change', updateCountdown);
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            var ok = true;
            if (!titleInput || !titleInput.value.trim()) {
                if (titleInput) titleInput.classList.add('is-invalid');
                ok = false;
            } else if (titleInput) {
                titleInput.classList.remove('is-invalid');
            }
            if (!imageInput || !imageInput.files || !imageInput.files.length) {
                var fb = document.getElementById('imageFeedback');
                if (fb) fb.classList.remove('d-none');
                ok = false;
            }
            if (!ok) {
                e.preventDefault();
                showToast('Title and banner image are required.', 'error');
                try { (titleInput && !titleInput.value.trim() ? titleInput : dropzone).focus(); } catch (ex) {}
                return false;
            }
            var spinner = document.getElementById('bcfSaveSpinner');
            if (spinner) spinner.classList.remove('d-none');
            showToast('Uploading banner…', 'info');
        });
    }

    var draftBtn = document.getElementById('bcfSaveDraftBtn');
    if (draftBtn) {
        draftBtn.addEventListener('click', function() {
            if (statusInput) statusInput.value = 'inactive';
            syncSidebar();
            showToast('Status set to Inactive — click Save Banner to store as draft.', 'warning');
        });
    }

    var saveAndNew = document.getElementById('bcfSaveAndNew');
    if (saveAndNew) {
        saveAndNew.addEventListener('click', function() {
            showToast('Save & New: submit this banner, then use Add Banner again.', 'info');
            if (form) form.requestSubmit ? form.requestSubmit() : form.submit();
        });
    }

    regenCode();
    updateProgress();
    updateCountdown();
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
