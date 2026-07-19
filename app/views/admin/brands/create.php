<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/brand-form.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<?php
$data = is_array($data ?? null) ? $data : ['name' => '', 'description' => '', 'status' => 'active'];
$errors = is_array($errors ?? null) ? $errors : [];
$brandName = htmlspecialchars((string)($data['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$brandDesc = htmlspecialchars((string)($data['description'] ?? ''), ENT_QUOTES, 'UTF-8');
$brandStatus = (($data['status'] ?? 'active') === 'inactive') ? 'inactive' : 'active';
?>

<div class="container-fluid brand-form-page" id="brandCreatePage">
    <div class="bf-toast-host" id="bfToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="bf-header">
        <div>
            <nav class="bf-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span class="sep">›</span>
                <span>Catalog</span>
                <span class="sep">›</span>
                <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex">Brands</a>
                <span class="sep">›</span>
                <span aria-current="page">Create Brand</span>
            </nav>
            <h1 class="bf-title">Brand Management</h1>
            <p class="bf-subtitle">Create a storefront-ready brand with logo, visibility, and SEO presentation tools.</p>
        </div>
        <div class="bf-actions">
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary bf-btn" id="bfBackTop">
                <i class="bi bi-arrow-left" aria-hidden="true"></i><span>Back</span>
            </a>
            <button type="button" class="btn btn-outline-secondary bf-btn" id="bfSaveDraftBtn">
                <i class="bi bi-file-earmark" aria-hidden="true"></i><span>Save Draft</span>
            </button>
            <button type="button" class="btn btn-outline-primary bf-btn" id="bfPreviewBtn" data-bs-toggle="modal" data-bs-target="#bfPreviewModal">
                <i class="bi bi-eye" aria-hidden="true"></i><span>Preview</span>
            </button>
            <button type="submit" form="createBrandForm" class="btn bf-btn bf-btn-primary" id="bfSaveTop">
                <i class="bi bi-check2-circle" aria-hidden="true"></i><span>Save Brand</span>
            </button>
        </div>
    </div>

    <div class="bf-progress" aria-hidden="true"><span id="bfFormProgress"></span></div>

    <!-- Alert Messages (required by existing JS) -->
    <div id="alert-messages">
        <?php flash('brand_success', '', 'alert alert-success alert-dismissible fade show'); ?>
        <?php flash('brand_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
        <?php if (!empty($errors['db_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($errors['db_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <form id="createBrandForm" action="<?php echo BASE_URL; ?>?controller=brand&action=create" method="POST" enctype="multipart/form-data" novalidate>
        <div class="row g-3">
            <div class="col-12 col-xl-9">

                <!-- Brand Information -->
                <section class="bf-card">
                    <div class="bf-card-header">
                        <h2><i class="bi bi-award text-primary" aria-hidden="true"></i> Brand Information</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="bf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-lg-7">
                                <label for="name" class="form-label">Brand Name <span class="req">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $brandName; ?>" required maxlength="255" autocomplete="organization" aria-describedby="nameHelp nameFeedback">
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback" id="nameFeedback"><?php echo htmlspecialchars($errors['name']); ?></div>
                                <?php else: ?>
                                    <div class="invalid-feedback" id="nameFeedback"></div>
                                <?php endif; ?>
                                <div class="form-text" id="nameHelp">Customer-facing brand name (e.g. Nike, Samsung).</div>
                            </div>
                            <div class="col-12 col-lg-5">
                                <label for="bfBrandCode" class="form-label">Brand Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bfBrandCode" value="" aria-describedby="codeHelp" autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" id="bfRegenCode" title="Regenerate code" aria-label="Regenerate brand code"><i class="bi bi-arrow-repeat"></i></button>
                                </div>
                                <div class="form-text" id="codeHelp">Auto-generated from name. Editable for your reference. <span class="bf-ui-only-note">(UI only)</span></div>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Brand Description</label>
                                <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4" maxlength="2000" aria-describedby="descHelp descCount"><?php echo $brandDesc; ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between">
                                    <div class="form-text" id="descHelp">Brief description shown on brand pages and listings.</div>
                                    <div class="bf-char-count" id="descCount">0 / 2000</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="bfShortDesc" class="form-label">Short Description</label>
                                <textarea class="form-control" id="bfShortDesc" rows="2" maxlength="160" aria-describedby="shortDescHelp"></textarea>
                                <div class="form-text" id="shortDescHelp">Max 160 characters. <span class="bf-ui-only-note">(UI only)</span></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bfBrandStory" class="form-label">Brand Story</label>
                                <textarea class="form-control" id="bfBrandStory" rows="2" maxlength="500"></textarea>
                                <div class="form-text"><span class="bf-ui-only-note">(UI only)</span></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="bfCountry" class="form-label">Country of Origin</label>
                                <input type="text" class="form-control" id="bfCountry" placeholder="e.g. Switzerland" autocomplete="country-name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bfManufacturer" class="form-label">Manufacturer</label>
                                <input type="text" class="form-control" id="bfManufacturer" placeholder="Legal manufacturer name">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bfWebsite" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="bfWebsite" placeholder="https://">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bfEmail" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="bfEmail" placeholder="brand@example.com">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="bfPhone" class="form-label">Support Phone</label>
                                <input type="tel" class="form-control" id="bfPhone" placeholder="+41 …">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Media -->
                <section class="bf-card">
                    <div class="bf-card-header">
                        <h2><i class="bi bi-image text-primary" aria-hidden="true"></i> Media</h2>
                        <span class="badge-soft">Logo saved</span>
                    </div>
                    <div class="bf-card-body">
                        <div class="bf-media-grid">
                            <div>
                                <label class="form-label" for="logo">Brand Logo</label>
                                <div class="bf-dropzone" id="bfLogoDropzone" role="button" tabindex="0" aria-label="Upload brand logo">
                                    <div class="bf-drop-icon"><i class="bi bi-cloud-arrow-up" aria-hidden="true"></i></div>
                                    <div class="fw-semibold">Drag & drop logo here</div>
                                    <div class="form-text mb-0">WEBP, PNG, JPG · Max 5MB · Recommended 200×200</div>
                                    <input type="file" class="<?php echo isset($errors['logo']) ? 'is-invalid' : ''; ?>" id="logo" name="logo" accept="image/*,.webp" aria-describedby="logoHelp">
                                </div>
                                <?php if (isset($errors['logo'])): ?>
                                    <div class="text-danger small mt-1"><?php echo htmlspecialchars($errors['logo']); ?></div>
                                <?php endif; ?>
                                <div class="form-text" id="logoHelp">This is the only media field saved to the database.</div>

                                <div class="mt-3 text-center">
                                    <div class="logo-preview" aria-live="polite">
                                        <i class="fas fa-building text-muted fa-4x" aria-hidden="true"></i>
                                    </div>
                                    <div class="bf-preview-actions justify-content-center mt-2" id="bfLogoActions" hidden>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bfReplaceLogo">Replace</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bfRotateLogo" title="Rotate preview">Rotate</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeLogoBtn">Delete</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Extra Media <span class="bf-ui-only-note">(UI only)</span></label>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="bf-dropzone bf-dropzone-sm" id="bfBannerZone">
                                            <div class="small fw-semibold mb-1">Brand Banner</div>
                                            <div class="form-text mb-0">Drag & drop</div>
                                            <input type="file" id="bfBanner" accept="image/*,.webp" aria-label="Brand banner">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bf-dropzone bf-dropzone-sm" id="bfCoverZone">
                                            <div class="small fw-semibold mb-1">Cover</div>
                                            <input type="file" id="bfCover" accept="image/*,.webp" aria-label="Brand cover">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bf-dropzone bf-dropzone-sm" id="bfIconZone">
                                            <div class="small fw-semibold mb-1">Icon</div>
                                            <input type="file" id="bfIcon" accept="image/*,.webp" aria-label="Brand icon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings -->
                <section class="bf-card">
                    <div class="bf-card-header">
                        <h2><i class="bi bi-sliders text-primary" aria-hidden="true"></i> Settings</h2>
                    </div>
                    <div class="bf-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Status <span class="req">*</span></label>
                                <div class="bf-status-pills" role="radiogroup" aria-label="Brand status">
                                    <label class="bf-status-pill">
                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="active" <?php echo $brandStatus === 'active' ? 'checked' : ''; ?> required>
                                        <span>
                                            <strong>Active</strong>
                                            <div class="form-text mb-0">Visible in storefront</div>
                                        </span>
                                    </label>
                                    <label class="bf-status-pill">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" <?php echo $brandStatus === 'inactive' ? 'checked' : ''; ?> required>
                                        <span>
                                            <strong>Inactive</strong>
                                            <div class="form-text mb-0">Hidden from shoppers</div>
                                        </span>
                                    </label>
                                </div>
                                <?php if (isset($errors['status'])): ?>
                                    <div class="text-danger mt-1"><?php echo htmlspecialchars($errors['status']); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bfFeatured" checked>
                                    <label class="form-check-label" for="bfFeatured">Featured Brand <span class="bf-ui-only-note">(UI)</span></label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bfShowHome" checked>
                                    <label class="form-check-label" for="bfShowHome">Show on Homepage <span class="bf-ui-only-note">(UI)</span></label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bfShowNav">
                                    <label class="form-check-label" for="bfShowNav">Show in Navigation <span class="bf-ui-only-note">(UI)</span></label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bfAllowReviews" checked>
                                    <label class="form-check-label" for="bfAllowReviews">Allow Reviews <span class="bf-ui-only-note">(UI)</span></label>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="bfDisplayOrder" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="bfDisplayOrder" value="0" min="0" step="1">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="bfPriority" class="form-label">Brand Priority</label>
                                <select class="form-select" id="bfPriority">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SEO -->
                <section class="bf-card">
                    <div class="bf-card-header">
                        <h2><i class="bi bi-search text-primary" aria-hidden="true"></i> SEO</h2>
                        <span class="badge-soft">UI tools</span>
                    </div>
                    <div class="bf-card-body">
                        <div class="bf-seo-score">
                            <div class="bf-score-ring" id="bfSeoRing" style="--score: 0"><span id="bfSeoScoreVal">0</span></div>
                            <div>
                                <div class="fw-semibold" id="bfSeoScoreLabel">SEO Score</div>
                                <div class="form-text mb-0" id="bfSeoScoreHint">Fill name, description, and meta fields to improve score.</div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="bfSlug" class="form-label">SEO URL (Slug)</label>
                                <div class="input-group">
                                    <span class="input-group-text">/brands/</span>
                                    <input type="text" class="form-control" id="bfSlug" autocomplete="off">
                                </div>
                                <div class="form-text">Server generates slug from name on save. <span class="bf-ui-only-note">(Preview only)</span></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bfMetaTitle" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="bfMetaTitle" maxlength="70">
                                <div class="bf-char-count" id="bfMetaTitleCount">0 / 70</div>
                            </div>
                            <div class="col-12">
                                <label for="bfMetaKeywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="bfMetaKeywords" placeholder="brand, quality, official">
                            </div>
                            <div class="col-12">
                                <label for="bfMetaDesc" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="bfMetaDesc" rows="2" maxlength="160"></textarea>
                                <div class="bf-char-count" id="bfMetaDescCount">0 / 160</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="bfCanonical" class="form-label">Canonical URL</label>
                                <input type="url" class="form-control" id="bfCanonical" placeholder="https://">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="bfOgImage" class="form-label">Open Graph Image</label>
                                <input type="file" class="form-control" id="bfOgImage" accept="image/*">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="bfTwitterImage" class="form-label">Twitter Card Image</label>
                                <input type="file" class="form-control" id="bfTwitterImage" accept="image/*">
                            </div>
                            <div class="col-12">
                                <div class="bf-google-preview" id="bfGooglePreview" aria-live="polite">
                                    <div class="gp-url" id="bfGpUrl"><?php echo htmlspecialchars(rtrim(BASE_URL, '/')); ?>/brands/…</div>
                                    <div class="gp-title" id="bfGpTitle">Brand title preview</div>
                                    <div class="gp-desc" id="bfGpDesc">Meta description preview will appear here as you type.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Social -->
                <section class="bf-card">
                    <div class="bf-card-header">
                        <h2><i class="bi bi-share text-primary" aria-hidden="true"></i> Social Links</h2>
                        <span class="badge-soft">UI only</span>
                    </div>
                    <div class="bf-card-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="bfFb">Facebook</label><input type="url" class="form-control" id="bfFb" placeholder="https://facebook.com/…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfIg">Instagram</label><input type="url" class="form-control" id="bfIg" placeholder="https://instagram.com/…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfYt">YouTube</label><input type="url" class="form-control" id="bfYt" placeholder="https://youtube.com/…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfTt">TikTok</label><input type="url" class="form-control" id="bfTt" placeholder="https://tiktok.com/@…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfLi">LinkedIn</label><input type="url" class="form-control" id="bfLi" placeholder="https://linkedin.com/…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfPin">Pinterest</label><input type="url" class="form-control" id="bfPin" placeholder="https://pinterest.com/…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfWa">WhatsApp</label><input type="text" class="form-control" id="bfWa" placeholder="+41…"></div>
                            <div class="col-md-6"><label class="form-label" for="bfOfficial">Official Website</label><input type="url" class="form-control" id="bfOfficial" placeholder="https://"></div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right sidebar -->
            <div class="col-12 col-xl-3">
                <aside class="bf-side-stack">
                    <section class="bf-card">
                        <div class="bf-card-header">
                            <h3>Brand Status</h3>
                        </div>
                        <div class="bf-card-body">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="statusToggle" <?php echo $brandStatus === 'active' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusToggle" id="statusLabel"><?php echo $brandStatus === 'active' ? 'Active' : 'Inactive'; ?></label>
                            </div>
                            <!-- JS-only status mirror (no name — radios inside form are authoritative) -->
                            <input type="hidden" id="statusInput" value="<?php echo htmlspecialchars($brandStatus); ?>">
                            <div class="form-text">Toggle syncs with status radios before save.</div>
                        </div>
                    </section>

                    <section class="bf-card">
                        <div class="bf-card-header"><h3>Logo Preview</h3></div>
                        <div class="bf-card-body text-center">
                            <div class="logo-preview logo-preview--side" id="bfSideLogoPreview">
                                <i class="bi bi-building text-muted fs-1" aria-hidden="true"></i>
                            </div>
                        </div>
                    </section>

                    <section class="bf-card">
                        <div class="bf-card-header"><h3>SEO Score</h3></div>
                        <div class="bf-card-body">
                            <div class="bf-seo-score mb-0">
                                <div class="bf-score-ring" id="bfSeoRingSide" style="--score: 0"><span id="bfSeoScoreValSide">0</span></div>
                                <div class="form-text mb-0" id="bfSeoHintSide">Start typing to score.</div>
                            </div>
                        </div>
                    </section>

                    <section class="bf-card">
                        <div class="bf-card-header"><h3>Quick Statistics</h3></div>
                        <div class="bf-card-body">
                            <ul class="list-unstyled mb-0 small">
                                <li class="d-flex justify-content-between py-1"><span class="text-muted">Products linked</span><strong>0</strong></li>
                                <li class="d-flex justify-content-between py-1"><span class="text-muted">Last Updated</span><strong>Now</strong></li>
                                <li class="d-flex justify-content-between py-1"><span class="text-muted">Created By</span><strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></strong></li>
                            </ul>
                        </div>
                    </section>

                    <section class="bf-card">
                        <div class="bf-card-header"><h3>Helpful Tips</h3></div>
                        <div class="bf-card-body">
                            <ul class="bf-tips mb-0">
                                <li>Use the official brand spelling for SEO consistency.</li>
                                <li>Square logos (PNG/WEBP) look best on product cards.</li>
                                <li>Keep descriptions clear — avoid marketing fluff.</li>
                                <li>Only Name, Description, Status, and Logo are saved.</li>
                            </ul>
                        </div>
                    </section>

                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary bf-btn">
                            <i class="bi bi-box-seam" aria-hidden="true"></i> Back to Product
                        </a>
                    </div>
                </aside>
            </div>
        </div>

        <!-- Sticky footer actions -->
        <div class="bf-sticky-footer" role="toolbar" aria-label="Brand form actions">
            <div class="container-fluid px-0">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary bf-btn">Cancel</a>
                        <button type="reset" class="btn btn-outline-secondary bf-btn" id="bfResetBtn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset
                        </button>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary bf-btn" id="bfSaveNewBtn">Save &amp; New</button>
                        <button type="button" class="btn btn-outline-primary bf-btn" id="bfSaveCloseBtn">Save &amp; Close</button>
                        <button type="submit" id="submitBtn" class="btn bf-btn bf-btn-primary">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i> Save Brand
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="bfPreviewModal" tabindex="-1" aria-labelledby="bfPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="bfPreviewModalLabel">Brand Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-3 align-items-center mb-3">
                    <div class="logo-preview" id="bfModalLogo" style="width:72px;min-height:72px;max-width:72px;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5" id="bfPrevName">Brand Name</div>
                        <div class="text-muted small" id="bfPrevStatus">Active</div>
                    </div>
                </div>
                <p class="mb-0" id="bfPrevDesc">Description preview…</p>
            </div>
        </div>
    </div>
</div>

<!-- Confirm modal -->
<div class="modal fade" id="bfConfirmModal" tabindex="-1" aria-labelledby="bfConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="bfConfirmModalLabel">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bfConfirmBody">Are you sure?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="bfConfirmOk">Continue</button>
            </div>
        </div>
    </div>
</div>

<style>
.brand-form-page .bf-dropzone-sm { padding: 0.85rem; min-height: 88px; }
.brand-form-page .bf-dropzone-sm .bf-drop-icon { display: none; }
.brand-form-page .bf-side-stack { position: sticky; top: 1rem; }
.brand-form-page .bf-tips { padding-left: 1.1rem; color: var(--bf-muted); font-size: 0.82rem; }
.brand-form-page .bf-tips li { margin-bottom: 0.45rem; }
.brand-form-page .bf-google-preview {
  border: 1px solid var(--bf-border); border-radius: 14px; padding: 1rem 1.1rem;
  background: var(--bf-solid);
}
.brand-form-page .bf-google-preview .gp-url { color: #202124; font-size: 0.78rem; }
.brand-form-page .bf-google-preview .gp-title { color: #1a0dab; font-size: 1.05rem; font-weight: 500; margin: 0.15rem 0; }
.brand-form-page .bf-google-preview .gp-desc { color: #4d5156; font-size: 0.85rem; }
[data-theme="dark"] .brand-form-page .bf-google-preview .gp-url { color: #94a3b8; }
[data-theme="dark"] .brand-form-page .bf-google-preview .gp-title { color: #93c5fd; }
[data-theme="dark"] .brand-form-page .bf-google-preview .gp-desc { color: #cbd5e1; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createBrandForm');
    const submitBtn = document.getElementById('submitBtn');
    const statusToggle = document.getElementById('statusToggle');
    const statusInput = document.getElementById('statusInput');
    const statusLabel = document.getElementById('statusLabel');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.querySelector('.logo-preview');
    const sidePreview = document.getElementById('bfSideLogoPreview');
    const logoActions = document.getElementById('bfLogoActions');
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const brandCode = document.getElementById('bfBrandCode');
    const slugInput = document.getElementById('bfSlug');
    const DRAFT_KEY = 'brandCreateDraft_v1';
    let afterSaveMode = 'stay'; // stay | new | close
    let logoRotation = 0;

    function slugify(str) {
        return String(str || '').toLowerCase().trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 80);
    }

    function codeFromName(str) {
        const base = String(str || '').toUpperCase().replace(/[^A-Z0-9]+/g, '').substring(0, 6) || 'BRAND';
        return 'BR-' + base + '-' + Math.floor(100 + Math.random() * 900);
    }

    function syncStatusUI(status) {
        if (statusInput) statusInput.value = status;
        if (statusLabel) statusLabel.textContent = status === 'active' ? 'Active' : 'Inactive';
        if (statusToggle) statusToggle.checked = (status === 'active');
        document.querySelectorAll('input[name="status"]').forEach(function(radio) {
            radio.checked = (radio.value === status);
        });
    }

    if (statusToggle) {
        const activeRadio = document.querySelector('input[name="status"][value="active"]');
        if (activeRadio) {
            syncStatusUI(activeRadio.checked ? 'active' : 'inactive');
        }
        statusToggle.addEventListener('change', function() {
            syncStatusUI(this.checked ? 'active' : 'inactive');
        });
        document.querySelectorAll('input[name="status"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                syncStatusUI(this.value);
            });
        });
    }

    function setLogoPreview(src) {
        const html = src
            ? `<div class="position-relative" style="width:100%;height:100%;"><img src="${src}" alt="Logo Preview" style="max-width:100%;max-height:100%;object-fit:contain;transform:rotate(${logoRotation}deg);"><button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" id="removeLogoBtn" title="Remove logo"><i class="fas fa-times"></i></button></div>`
            : '<i class="fas fa-building text-muted fa-4x"></i>';
        document.querySelectorAll('.logo-preview').forEach(function(el) {
            if (el.id === 'bfModalLogo') {
                el.innerHTML = src ? `<img src="${src}" alt="" style="max-width:100%;max-height:100%;object-fit:contain;">` : '<i class="bi bi-building"></i>';
            } else if (el.id === 'bfSideLogoPreview') {
                el.innerHTML = src ? `<img src="${src}" alt="" style="max-width:100%;max-height:160px;object-fit:contain;transform:rotate(${logoRotation}deg);">` : '<i class="bi bi-building text-muted fs-1"></i>';
            } else {
                el.innerHTML = html;
            }
        });
        if (logoActions) logoActions.hidden = !src;
        const removeBtn = document.getElementById('removeLogoBtn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clearLogo();
            });
        }
    }

    function clearLogo() {
        if (logoInput) logoInput.value = '';
        logoRotation = 0;
        setLogoPreview(null);
    }

    function handleLogoFile(file) {
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            showAlert('Logo must be 5MB or smaller', 'warning');
            clearLogo();
            return;
        }
        if (!/^image\//.test(file.type) && !/\.webp$/i.test(file.name)) {
            showAlert('Please choose a valid image file', 'warning');
            clearLogo();
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) { setLogoPreview(e.target.result); };
        reader.readAsDataURL(file);
    }

    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) handleLogoFile(this.files[0]);
        });
    }

    const dropzone = document.getElementById('bfLogoDropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(function(evt) {
            dropzone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropzone.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function(evt) {
            dropzone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropzone.classList.remove('is-dragover');
            });
        });
        dropzone.addEventListener('drop', function(e) {
            const file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
            if (!file || !logoInput) return;
            try {
                const dt = new DataTransfer();
                dt.items.add(file);
                logoInput.files = dt.files;
            } catch (_) { /* browser may block DataTransfer */ }
            handleLogoFile(file);
        });
    }

    document.getElementById('bfReplaceLogo')?.addEventListener('click', function() { logoInput?.click(); });
    document.getElementById('removeLogoBtn')?.addEventListener('click', function(e) { e.preventDefault(); clearLogo(); });
    document.getElementById('bfRotateLogo')?.addEventListener('click', function() {
        logoRotation = (logoRotation + 90) % 360;
        const img = logoPreview && logoPreview.querySelector('img');
        if (img) {
            img.style.transform = 'rotate(' + logoRotation + 'deg)';
            const sideImg = sidePreview && sidePreview.querySelector('img');
            if (sideImg) sideImg.style.transform = 'rotate(' + logoRotation + 'deg)';
        }
    });

    function updateChar(el, counter, max) {
        if (!el || !counter) return;
        const len = el.value.length;
        counter.textContent = len + ' / ' + max;
        counter.classList.toggle('is-warn', len > max * 0.85);
        counter.classList.toggle('is-over', len > max);
    }

    function updateSeo() {
        const name = (nameInput && nameInput.value.trim()) || '';
        const desc = (descInput && descInput.value.trim()) || '';
        const metaTitle = (document.getElementById('bfMetaTitle')?.value || name).trim();
        const metaDesc = (document.getElementById('bfMetaDesc')?.value || desc).trim();
        const slug = (slugInput && slugInput.value) || slugify(name) || '…';

        let score = 0;
        if (name.length >= 2) score += 25;
        if (desc.length >= 40) score += 25;
        if (metaTitle.length >= 10 && metaTitle.length <= 70) score += 20;
        if (metaDesc.length >= 50 && metaDesc.length <= 160) score += 20;
        if (document.getElementById('logo')?.files?.length) score += 10;

        document.querySelectorAll('#bfSeoRing, #bfSeoRingSide').forEach(function(ring) {
            ring.style.setProperty('--score', score);
        });
        document.querySelectorAll('#bfSeoScoreVal, #bfSeoScoreValSide').forEach(function(el) {
            el.textContent = String(score);
        });
        const hint = score >= 80 ? 'Excellent — ready to publish.' : score >= 50 ? 'Good — a few improvements left.' : 'Needs more content for SEO.';
        const hintEl = document.getElementById('bfSeoScoreHint');
        const hintSide = document.getElementById('bfSeoHintSide');
        if (hintEl) hintEl.textContent = hint;
        if (hintSide) hintSide.textContent = hint;

        const gpTitle = document.getElementById('bfGpTitle');
        const gpDesc = document.getElementById('bfGpDesc');
        const gpUrl = document.getElementById('bfGpUrl');
        if (gpTitle) gpTitle.textContent = metaTitle || 'Brand title preview';
        if (gpDesc) gpDesc.textContent = metaDesc || 'Meta description preview will appear here as you type.';
        if (gpUrl) gpUrl.textContent = '<?php echo rtrim(BASE_URL, '/'); ?>/brands/' + slug;
    }

    function bindNameSlug() {
        if (!nameInput) return;
        nameInput.addEventListener('input', function() {
            if (brandCode && !brandCode.dataset.locked) brandCode.value = codeFromName(nameInput.value);
            if (slugInput && !slugInput.dataset.locked) slugInput.value = slugify(nameInput.value);
            const metaTitle = document.getElementById('bfMetaTitle');
            if (metaTitle && !metaTitle.dataset.locked) metaTitle.value = nameInput.value.substring(0, 70);
            updateSeo();
            updateProgress();
        });
    }

    brandCode?.addEventListener('input', function() { brandCode.dataset.locked = '1'; });
    slugInput?.addEventListener('input', function() { slugInput.dataset.locked = '1'; updateSeo(); });
    document.getElementById('bfMetaTitle')?.addEventListener('input', function() {
        this.dataset.locked = '1';
        updateChar(this, document.getElementById('bfMetaTitleCount'), 70);
        updateSeo();
    });
    document.getElementById('bfMetaDesc')?.addEventListener('input', function() {
        updateChar(this, document.getElementById('bfMetaDescCount'), 160);
        updateSeo();
    });
    descInput?.addEventListener('input', function() {
        updateChar(this, document.getElementById('descCount'), 2000);
        updateSeo();
        updateProgress();
    });
    document.getElementById('bfRegenCode')?.addEventListener('click', function() {
        if (brandCode) {
            brandCode.dataset.locked = '';
            brandCode.value = codeFromName(nameInput?.value || 'BRAND');
        }
    });

    bindNameSlug();
    updateChar(descInput, document.getElementById('descCount'), 2000);
    updateChar(document.getElementById('bfMetaTitle'), document.getElementById('bfMetaTitleCount'), 70);
    updateChar(document.getElementById('bfMetaDesc'), document.getElementById('bfMetaDescCount'), 160);
    if (brandCode && nameInput?.value) brandCode.value = codeFromName(nameInput.value);
    if (slugInput && nameInput?.value) slugInput.value = slugify(nameInput.value);
    updateSeo();

    function updateProgress() {
        const bar = document.getElementById('bfFormProgress');
        if (!bar) return;
        let filled = 0;
        if (nameInput?.value.trim()) filled += 40;
        if (descInput?.value.trim()) filled += 30;
        if (logoInput?.files?.length) filled += 20;
        if (document.querySelector('input[name="status"]:checked')) filled += 10;
        bar.style.width = filled + '%';
    }
    form?.addEventListener('input', updateProgress);
    form?.addEventListener('change', updateProgress);
    updateProgress();

    // Draft (localStorage only)
    document.getElementById('bfSaveDraftBtn')?.addEventListener('click', function() {
        try {
            const payload = {
                name: nameInput?.value || '',
                description: descInput?.value || '',
                status: statusInput?.value || 'active',
                code: brandCode?.value || '',
                slug: slugInput?.value || '',
                metaTitle: document.getElementById('bfMetaTitle')?.value || '',
                metaDesc: document.getElementById('bfMetaDesc')?.value || ''
            };
            localStorage.setItem(DRAFT_KEY, JSON.stringify(payload));
            showAlert('Draft saved locally on this device', 'success');
        } catch (_) {
            showAlert('Unable to save draft', 'danger');
        }
    });

    try {
        const raw = localStorage.getItem(DRAFT_KEY);
        if (raw && !nameInput?.value) {
            const d = JSON.parse(raw);
            if (d.name && nameInput) nameInput.value = d.name;
            if (d.description && descInput) descInput.value = d.description;
            if (d.status) syncStatusUI(d.status);
            if (d.code && brandCode) { brandCode.value = d.code; brandCode.dataset.locked = '1'; }
            if (d.slug && slugInput) { slugInput.value = d.slug; slugInput.dataset.locked = '1'; }
            if (d.metaTitle) {
                const mt = document.getElementById('bfMetaTitle');
                if (mt) { mt.value = d.metaTitle; mt.dataset.locked = '1'; }
            }
            if (d.metaDesc) {
                const md = document.getElementById('bfMetaDesc');
                if (md) md.value = d.metaDesc;
            }
            updateSeo();
            updateProgress();
        }
    } catch (_) { /* ignore */ }

    document.getElementById('bfPreviewBtn')?.addEventListener('click', function() {
        document.getElementById('bfPrevName').textContent = nameInput?.value.trim() || 'Brand Name';
        document.getElementById('bfPrevStatus').textContent = (statusInput?.value || 'active') === 'active' ? 'Active' : 'Inactive';
        document.getElementById('bfPrevDesc').textContent = descInput?.value.trim() || 'Description preview…';
        const src = logoPreview?.querySelector('img')?.src;
        const modalLogo = document.getElementById('bfModalLogo');
        if (modalLogo) modalLogo.innerHTML = src ? `<img src="${src}" alt="" style="max-width:100%;max-height:100%;object-fit:contain;">` : '<i class="bi bi-building"></i>';
    });

    document.getElementById('bfSaveNewBtn')?.addEventListener('click', function() {
        afterSaveMode = 'new';
        form?.requestSubmit();
    });
    document.getElementById('bfSaveCloseBtn')?.addEventListener('click', function() {
        afterSaveMode = 'close';
        form?.requestSubmit();
    });

    // Preserve existing AJAX create flow
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!nameInput?.value.trim()) {
                nameInput?.classList.add('is-invalid');
                const fb = document.getElementById('nameFeedback');
                if (fb) fb.textContent = 'Brand name is required';
                showAlert('Please enter a brand name', 'warning');
                nameInput?.focus();
                return;
            }

            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...';
            document.getElementById('bfSaveTop')?.setAttribute('disabled', 'disabled');

            const formData = new FormData(this);
            formData.set('status', statusInput ? statusInput.value : 'active');

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    showAlert(data.message, 'success');
                    try { localStorage.removeItem(DRAFT_KEY); } catch (_) {}

                    if (afterSaveMode === 'close') {
                        window.location.href = '<?php echo BASE_URL; ?>?controller=brand&action=adminIndex';
                        return;
                    }

                    form.reset();
                    clearLogo();
                    syncStatusUI('active');
                    if (brandCode) { brandCode.value = ''; brandCode.dataset.locked = ''; }
                    if (slugInput) { slugInput.value = ''; slugInput.dataset.locked = ''; }
                    afterSaveMode = 'stay';
                    updateSeo();
                    updateProgress();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Failed to create brand');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showAlert(error.message || 'An error occurred while creating the brand', 'danger');
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText.indexOf('Creating') >= 0
                    ? '<i class="bi bi-check2-circle me-1"></i> Save Brand'
                    : originalBtnText;
                document.getElementById('bfSaveTop')?.removeAttribute('disabled');
            });
        });
    }

    function showAlert(message, type) {
        type = type || 'success';
        const alertMessages = document.getElementById('alert-messages');
        if (!alertMessages) return;

        const existingAlerts = alertMessages.querySelectorAll('.alert');
        existingAlerts.forEach(function(alert) { alert.remove(); });

        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show';
        alertDiv.role = 'alert';
        alertDiv.innerHTML =
            '<i class="' + (type === 'success' ? 'fas fa-check-circle' : type === 'danger' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle') + ' me-2"></i>' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);

        setTimeout(function() {
            try {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            } catch (_) { alertDiv.remove(); }
        }, 5000);

        // Mirror to toast host
        const host = document.getElementById('bfToastHost');
        if (host && window.bootstrap?.Toast) {
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-' + (type === 'danger' ? 'danger' : type === 'warning' ? 'warning' : 'success') + ' border-0 show';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = '<div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
            host.appendChild(toast);
            const t = new bootstrap.Toast(toast, { delay: 4000 });
            t.show();
            toast.addEventListener('hidden.bs.toast', function() { toast.remove(); });
        }
    }

    // Expose for any legacy callers
    window.showAlert = showAlert;
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
