<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/category-form.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<?php
$existingNames = [];
if (!empty($parentCategories)) {
    foreach ($parentCategories as $p) {
        $existingNames[] = strtolower(trim(is_object($p) ? (string)$p->name : (string)($p['name'] ?? '')));
    }
}
$existingNamesJson = json_encode(array_values(array_filter($existingNames)));
?>

<div class="container-fluid cat-form-page" id="categoryCreatePage">
    <div class="cf-toast-host" id="cfToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="cf-header">
        <div>
            <nav class="cf-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span class="sep">›</span>
                <span>Products</span>
                <span class="sep">›</span>
                <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex">Categories</a>
                <span class="sep">›</span>
                <span aria-current="page">Create Category</span>
            </nav>
            <h1 class="cf-title">Category Management</h1>
            <p class="cf-subtitle">Create a structured category with tax, visibility, and storefront-ready presentation.</p>
        </div>
        <div class="cf-actions">
            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-outline-secondary cf-btn" id="cfBackTop">
                <i class="bi bi-arrow-left" aria-hidden="true"></i><span>Back</span>
            </a>
            <button type="button" class="btn btn-outline-secondary cf-btn" id="cfSaveDraftBtn">
                <i class="bi bi-file-earmark" aria-hidden="true"></i><span>Save Draft</span>
            </button>
            <button type="button" class="btn btn-outline-primary cf-btn" id="cfPreviewBtn" data-bs-toggle="modal" data-bs-target="#cfPreviewModal">
                <i class="bi bi-eye" aria-hidden="true"></i><span>Preview</span>
            </button>
            <button type="submit" form="categoryCreateForm" class="btn cf-btn cf-btn-primary" id="cfSaveTop">
                <i class="bi bi-check2-circle" aria-hidden="true"></i><span>Save Category</span>
            </button>
        </div>
    </div>

    <div class="cf-progress" aria-hidden="true"><span id="cfFormProgress"></span></div>

    <?php if(isset($errors['db_error'])): ?>
        <div class="alert alert-danger rounded-4 shadow-sm"><?php echo $errors['db_error']; ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>?controller=category&action=create" method="POST" enctype="multipart/form-data" novalidate id="categoryCreateForm">
        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <!-- Category Information -->
                <section class="cf-card">
                    <div class="cf-card-header">
                        <h2><i class="bi bi-folder2-open text-primary" aria-hidden="true"></i> Category Information</h2>
                        <span class="badge-soft">Required</span>
                    </div>
                    <div class="cf-card-body">
                        <div class="row g-3">
                            <div class="col-12 col-lg-7">
                                <label for="name" class="form-label">Category Name <span class="req">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>" required maxlength="255" autocomplete="off" aria-describedby="nameHelp nameFeedback">
                                <?php if(isset($errors['name'])): ?>
                                    <div class="invalid-feedback" id="nameFeedback"><?php echo $errors['name']; ?></div>
                                <?php else: ?>
                                    <div class="invalid-feedback" id="nameFeedback"></div>
                                <?php endif; ?>
                                <div class="form-text" id="nameHelp">Use a clear, customer-facing name (e.g. Soft Drinks).</div>
                            </div>
                            <div class="col-12 col-lg-5">
                                <label for="cfCategoryCode" class="form-label">Category Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cfCategoryCode" value="" aria-describedby="codeHelp" autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" id="cfRegenCode" title="Regenerate code" aria-label="Regenerate category code"><i class="bi bi-arrow-repeat"></i></button>
                                </div>
                                <div class="form-text" id="codeHelp">Auto-generated from name. Editable for your reference.</div>
                                <div class="cf-ui-only-note">Workspace reference only — not stored by current API.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select id="parent_id" name="parent_id" class="form-select <?php echo isset($errors['parent_id']) ? 'is-invalid' : ''; ?>" aria-describedby="parentHelp">
                                    <option value="">None (Top Level)</option>
                                    <?php if (!empty($parentCategories)): ?>
                                        <?php foreach ($parentCategories as $p): ?>
                                            <?php 
                                                $pid = is_object($p) ? $p->id : (isset($p['id']) ? $p['id'] : null);
                                                $pname = is_object($p) ? $p->name : (isset($p['name']) ? $p['name'] : '');
                                                $selected = isset($data['parent_id']) && (string)$data['parent_id'] === (string)$pid ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo htmlspecialchars($pid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($pname); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if(isset($errors['parent_id'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['parent_id']; ?></div>
                                <?php endif; ?>
                                <div class="form-text" id="parentHelp">Leave empty for a root category.</div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="tax_id" class="form-label">Tax Class</label>
                                <select id="tax_id" name="tax_id" class="form-select <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>">
                                    <option value="">None</option>
                                    <?php if (!empty($taxRates)): ?>
                                        <?php foreach ($taxRates as $t): ?>
                                            <?php 
                                                $tid = is_object($t) ? $t->id : (isset($t['id']) ? $t['id'] : null);
                                                $tname = is_object($t) ? $t->name : (isset($t['name']) ? $t['name'] : '');
                                                $trate = is_object($t) ? $t->rate : (isset($t['rate']) ? $t['rate'] : '');
                                                $label = trim($tname . ' (' . $trate . '%)');
                                                $selected = isset($data['tax_id']) && (string)$data['tax_id'] === (string)$tid ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo htmlspecialchars($tid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if(isset($errors['tax_id'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['tax_id']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="cfShortDescription" class="form-label">Short Description</label>
                                <textarea class="form-control" id="cfShortDescription" rows="2" maxlength="160" placeholder="Brief summary for listings and menus"></textarea>
                                <div class="cf-char-count" id="cfShortCount">0 / 160</div>
                                <div class="cf-ui-only-note">Preview helper — current save API stores name, parent, tax, status &amp; image.</div>
                            </div>

                            <div class="col-12">
                                <label for="cfDescription" class="form-label">Category Description</label>
                                <textarea class="form-control" id="cfDescription" rows="4" maxlength="1000" placeholder="Detailed category description for internal notes / SEO drafting"></textarea>
                                <div class="cf-char-count" id="cfDescCount">0 / 1000</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Media -->
                <section class="cf-card">
                    <div class="cf-card-header">
                        <h2><i class="bi bi-image text-primary" aria-hidden="true"></i> Media</h2>
                        <span class="badge-soft">JPG · PNG · WEBP</span>
                    </div>
                    <div class="cf-card-body">
                        <label class="form-label" for="image">Category Image</label>
                        <div class="cf-dropzone <?php echo isset($errors['image']) ? 'border-danger' : ''; ?>" id="cfImageDropzone">
                            <div class="cf-drop-icon" aria-hidden="true"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="fw-semibold mb-1">Drag &amp; drop image here</div>
                            <div class="text-muted small mb-2">or click to browse · Max 5MB · 800×600 recommended</div>
                            <input type="file" class="<?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" aria-describedby="imageHelp">
                        </div>
                        <?php if(isset($errors['image'])): ?>
                            <div class="text-danger small mt-2"><?php echo $errors['image']; ?></div>
                        <?php endif; ?>
                        <div class="form-text" id="imageHelp">Allowed: JPG, PNG, WEBP, GIF. Maximum size 5MB.</div>

                        <div class="cf-preview-wrap" id="cfImagePreviewWrap">
                            <div class="cf-preview-frame">
                                <img src="" alt="Category image preview" id="cfImagePreview">
                            </div>
                            <div class="cf-preview-actions">
                                <button type="button" class="btn btn-sm btn-outline-secondary cf-btn" id="cfReplaceImage"><i class="bi bi-arrow-left-right"></i> Replace</button>
                                <button type="button" class="btn btn-sm btn-outline-danger cf-btn" id="cfRemoveImage"><i class="bi bi-trash"></i> Remove</button>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label" for="cfBannerUi">Banner Image</label>
                                <input type="file" class="form-control" id="cfBannerUi" accept="image/*">
                                <div class="cf-ui-only-note">UI workspace — not submitted with current save.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="cfIconUi">Category Icon</label>
                                <input type="file" class="form-control" id="cfIconUi" accept="image/*">
                                <div class="cf-ui-only-note">UI workspace — not submitted with current save.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SEO -->
                <section class="cf-card">
                    <div class="cf-card-header">
                        <h2><i class="bi bi-search text-primary" aria-hidden="true"></i> SEO Panel</h2>
                        <span class="badge-soft" id="cfSeoScoreLabel">Score 0</span>
                    </div>
                    <div class="cf-card-body">
                        <div class="cf-seo-score">
                            <div class="cf-score-ring" id="cfScoreRing" style="--score:0"><span id="cfScoreValue">0</span></div>
                            <div>
                                <div class="fw-semibold" id="cfSeoHint">Add a clear category name to improve SEO readiness.</div>
                                <div class="small text-muted">Live preview based on name &amp; draft meta (client-side).</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cfSlug" class="form-label">SEO URL (Slug)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo rtrim(BASE_URL, '/'); ?>/category/</span>
                                    <input type="text" class="form-control" id="cfSlug" value="" autocomplete="off">
                                </div>
                                <div class="form-text" id="cfSlugStatus">Slug preview updates as you type the name.</div>
                            </div>
                            <div class="col-12">
                                <label for="cfMetaTitle" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="cfMetaTitle" maxlength="70" value="">
                                <div class="cf-char-count" id="cfMetaTitleCount">0 / 70</div>
                            </div>
                            <div class="col-12">
                                <label for="cfMetaKeywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="cfMetaKeywords" placeholder="keyword1, keyword2">
                            </div>
                            <div class="col-12">
                                <label for="cfMetaDescription" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="cfMetaDescription" rows="3" maxlength="160"></textarea>
                                <div class="cf-char-count" id="cfMetaDescCount">0 / 160</div>
                            </div>
                        </div>

                        <hr class="my-3">
                        <div class="small text-muted mb-2 fw-semibold">Google Search Preview</div>
                        <div class="cf-google-preview" id="cfGooglePreview">
                            <div class="g-url" id="cfGUrl"><?php echo rtrim(BASE_URL, '/'); ?>/category/</div>
                            <div class="g-title" id="cfGTitle">Category title preview</div>
                            <div class="g-desc" id="cfGDesc">Meta description will appear here once you add content.</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <!-- Status / Visibility -->
                <aside class="cf-card">
                    <div class="cf-card-header">
                        <h3><i class="bi bi-sliders text-primary" aria-hidden="true"></i> Category Settings</h3>
                    </div>
                    <div class="cf-card-body">
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Status</div>
                                <div class="hint">Active categories appear in catalogs</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?php echo !empty($data['status']) ? 'checked' : ''; ?> role="switch" aria-checked="<?php echo !empty($data['status']) ? 'true' : 'false'; ?>">
                                <label class="form-check-label visually-hidden" for="status">Active</label>
                            </div>
                        </div>
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Inactive</div>
                                <div class="hint">Hide from storefront listings</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="cfInactiveToggle" role="switch">
                                <label class="form-check-label visually-hidden" for="cfInactiveToggle">Inactive</label>
                            </div>
                        </div>
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Featured Category</div>
                                <div class="hint">Highlight in featured blocks</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="cfFeatured" role="switch">
                            </div>
                        </div>
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Show on Homepage</div>
                                <div class="hint">Surface on home category strip</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="cfHomepage" role="switch">
                            </div>
                        </div>
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Display Menu</div>
                                <div class="hint">Include in navigation menu</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="cfMenu" role="switch" checked>
                            </div>
                        </div>
                        <div class="cf-switch-row">
                            <div>
                                <div class="label">Allow Product Reviews</div>
                                <div class="hint">Enable reviews for products</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="checkbox" class="form-check-input" id="cfReviews" role="switch" checked>
                            </div>
                        </div>
                        <div class="cf-ui-only-note mt-2">Only <strong>Status (Active)</strong> is saved by the current API. Other toggles are workspace preferences.</div>
                    </div>
                </aside>

                <aside class="cf-card">
                    <div class="cf-card-header">
                        <h3><i class="bi bi-info-circle text-primary" aria-hidden="true"></i> Visibility Snapshot</h3>
                    </div>
                    <div class="cf-card-body">
                        <ul class="cf-side-list">
                            <li><span class="k">Status</span><span class="v" id="cfSideStatus">Active</span></li>
                            <li><span class="k">Parent</span><span class="v" id="cfSideParent">Top Level</span></li>
                            <li><span class="k">Tax Class</span><span class="v" id="cfSideTax">None</span></li>
                            <li><span class="k">Featured</span><span class="v" id="cfSideFeatured">No</span></li>
                            <li><span class="k">Homepage</span><span class="v" id="cfSideHome">No</span></li>
                            <li><span class="k">Menu</span><span class="v" id="cfSideMenu">Yes</span></li>
                            <li><span class="k">Product Count</span><span class="v">0</span></li>
                        </ul>
                        <div class="mt-3">
                            <label for="cfSortOrder" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="cfSortOrder" value="0" min="0" step="1">
                        </div>
                        <div class="mt-3">
                            <label for="cfWarehouse" class="form-label">Default Warehouse</label>
                            <select class="form-select" id="cfWarehouse">
                                <option value="">Main Warehouse</option>
                                <option value="online">Online Fulfillment</option>
                                <option value="store">Store Pickup</option>
                            </select>
                            <div class="cf-ui-only-note">Reference only for planning layouts.</div>
                        </div>
                    </div>
                </aside>

                <aside class="cf-card">
                    <div class="cf-card-header">
                        <h3><i class="bi bi-lightbulb text-warning" aria-hidden="true"></i> Quick Tips</h3>
                    </div>
                    <div class="cf-card-body">
                        <div class="cf-tip">
                            <strong>Clear hierarchy</strong>
                            Nest related categories under a parent for cleaner navigation and reporting.
                        </div>
                        <div class="cf-tip">
                            <strong>Tax first</strong>
                            Assign the correct tax class so POS and checkout calculate accurately.
                        </div>
                        <div class="cf-tip">
                            <strong>Image quality</strong>
                            Use square or 4:3 images under 5MB for faster admin &amp; storefront loads.
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- Sticky footer actions -->
        <div class="cf-sticky-footer">
            <div class="container-fluid px-0">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-outline-secondary cf-btn" id="cfCancelBtn">
                            <i class="bi bi-x-lg"></i><span>Cancel</span>
                        </a>
                        <button type="reset" class="btn btn-outline-secondary cf-btn" id="cfResetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i><span>Reset</span>
                        </button>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-outline-primary cf-btn" id="cfSaveCloseBtn">
                            <i class="bi bi-box-arrow-right"></i><span>Save &amp; Close</span>
                        </button>
                        <button type="submit" class="btn cf-btn cf-btn-success" id="cfSaveNewBtn">
                            <i class="bi bi-plus-lg"></i><span>Save &amp; New</span>
                        </button>
                        <button type="submit" class="btn cf-btn cf-btn-primary" id="cfSaveBtn">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="cfSaveSpinner" role="status" aria-hidden="true"></span>
                            <i class="bi bi-check2-circle" id="cfSaveIcon"></i><span>Save Category</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="cfPreviewModal" tabindex="-1" aria-labelledby="cfPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="cfPreviewModalLabel">Category Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cf-google-preview mb-3">
                    <div class="g-url" id="cfPrevUrl"></div>
                    <div class="g-title" id="cfPrevTitle"></div>
                    <div class="g-desc" id="cfPrevDesc"></div>
                </div>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><strong>Name:</strong> <span id="cfPrevName">—</span></li>
                    <li class="mb-1"><strong>Parent:</strong> <span id="cfPrevParent">—</span></li>
                    <li class="mb-1"><strong>Tax:</strong> <span id="cfPrevTax">—</span></li>
                    <li><strong>Status:</strong> <span id="cfPrevStatus">—</span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm leave modal -->
<div class="modal fade" id="cfConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Discard changes?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">Unsaved changes will be lost.</div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Stay</button>
                <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-sm btn-danger" id="cfConfirmLeave">Discard</a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    var EXISTING = <?php echo $existingNamesJson ?: '[]'; ?>;
    var BASE = <?php echo json_encode(rtrim(BASE_URL, '/')); ?>;
    var form = document.getElementById('categoryCreateForm');
    if (!form) return;

    var nameEl = document.getElementById('name');
    var codeEl = document.getElementById('cfCategoryCode');
    var slugEl = document.getElementById('cfSlug');
    var statusEl = document.getElementById('status');
    var inactiveEl = document.getElementById('cfInactiveToggle');
    var imageEl = document.getElementById('image');
    var dropzone = document.getElementById('cfImageDropzone');
    var previewWrap = document.getElementById('cfImagePreviewWrap');
    var previewImg = document.getElementById('cfImagePreview');
    var dirty = false;
    var codeManual = false;
    var slugManual = false;

    function toast(type, title, msg) {
        var host = document.getElementById('cfToastHost');
        if (!host) return;
        var el = document.createElement('div');
        el.className = 'cf-toast ' + type;
        el.setAttribute('role', 'status');
        el.innerHTML = '<div><strong>' + title + '</strong><div class="small text-muted">' + msg + '</div></div>';
        host.appendChild(el);
        setTimeout(function () { el.remove(); }, 4200);
    }

    function slugify(str) {
        return String(str || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    function codeify(str) {
        var s = slugify(str).replace(/-/g, '_').toUpperCase();
        return s ? ('CAT_' + s.substring(0, 24)) : '';
    }

    function setCount(el, countEl, max) {
        if (!el || !countEl) return;
        var n = (el.value || '').length;
        countEl.textContent = n + ' / ' + max;
        countEl.classList.toggle('is-warn', n > max * 0.85 && n <= max);
        countEl.classList.toggle('is-over', n > max);
    }

    function updateSeo() {
        var name = (nameEl && nameEl.value) || '';
        var slug = slugManual ? (slugEl.value || '') : slugify(name);
        if (slugEl && !slugManual) slugEl.value = slug;
        if (codeEl && !codeManual) codeEl.value = codeify(name);

        var metaTitle = document.getElementById('cfMetaTitle');
        var metaDesc = document.getElementById('cfMetaDescription');
        var shortDesc = document.getElementById('cfShortDescription');
        if (metaTitle && !metaTitle.dataset.touched) metaTitle.value = name ? (name + ' | Sivakamy') : '';
        var title = (metaTitle && metaTitle.value) || name || 'Category title preview';
        var desc = (metaDesc && metaDesc.value) || (shortDesc && shortDesc.value) || 'Meta description will appear here once you add content.';
        var url = BASE + '/category/' + (slug || '');

        var gUrl = document.getElementById('cfGUrl');
        var gTitle = document.getElementById('cfGTitle');
        var gDesc = document.getElementById('cfGDesc');
        if (gUrl) gUrl.textContent = url;
        if (gTitle) gTitle.textContent = title.substring(0, 70);
        if (gDesc) gDesc.textContent = desc.substring(0, 160);

        var score = 0;
        if (name.length >= 3) score += 30;
        if (slug.length >= 3) score += 20;
        if ((metaTitle && metaTitle.value.length >= 20) || name.length >= 8) score += 20;
        if (metaDesc && metaDesc.value.length >= 50) score += 20;
        if (imageEl && imageEl.files && imageEl.files.length) score += 10;
        score = Math.min(100, score);

        var ring = document.getElementById('cfScoreRing');
        var val = document.getElementById('cfScoreValue');
        var label = document.getElementById('cfSeoScoreLabel');
        var hint = document.getElementById('cfSeoHint');
        if (ring) ring.style.setProperty('--score', String(score));
        if (val) val.textContent = String(score);
        if (label) label.textContent = 'Score ' + score;
        if (hint) {
            hint.textContent = score >= 80 ? 'Strong SEO readiness for this category.'
                : score >= 50 ? 'Good start — add meta description and image.'
                : 'Add a clear category name to improve SEO readiness.';
        }

        var slugStatus = document.getElementById('cfSlugStatus');
        if (slugStatus) {
            slugStatus.textContent = slug ? ('URL preview: ' + url) : 'Slug preview updates as you type the name.';
        }
        updateProgress();
        updateSidebar();
    }

    function updateSidebar() {
        var st = document.getElementById('cfSideStatus');
        var parent = document.getElementById('parent_id');
        var tax = document.getElementById('tax_id');
        if (st) st.textContent = (statusEl && statusEl.checked) ? 'Active' : 'Inactive';
        var sp = document.getElementById('cfSideParent');
        if (sp && parent) sp.textContent = parent.options[parent.selectedIndex] ? parent.options[parent.selectedIndex].text : 'Top Level';
        var stx = document.getElementById('cfSideTax');
        if (stx && tax) stx.textContent = tax.options[tax.selectedIndex] ? tax.options[tax.selectedIndex].text : 'None';
        var sf = document.getElementById('cfSideFeatured');
        var sh = document.getElementById('cfSideHome');
        var sm = document.getElementById('cfSideMenu');
        if (sf) sf.textContent = document.getElementById('cfFeatured') && document.getElementById('cfFeatured').checked ? 'Yes' : 'No';
        if (sh) sh.textContent = document.getElementById('cfHomepage') && document.getElementById('cfHomepage').checked ? 'Yes' : 'No';
        if (sm) sm.textContent = document.getElementById('cfMenu') && document.getElementById('cfMenu').checked ? 'Yes' : 'No';
    }

    function updateProgress() {
        var filled = 0, total = 4;
        if (nameEl && nameEl.value.trim().length >= 2) filled++;
        if (document.getElementById('parent_id')) filled++; // optional but counts as reviewed
        if (document.getElementById('tax_id')) filled++;
        if (statusEl) filled++;
        var pct = Math.round((filled / total) * 100);
        var bar = document.getElementById('cfFormProgress');
        if (bar) bar.style.width = pct + '%';
    }

    function validateNameRealtime() {
        if (!nameEl) return true;
        var v = nameEl.value.trim();
        var fb = document.getElementById('nameFeedback');
        nameEl.classList.remove('is-invalid', 'is-valid');
        if (!v) {
            nameEl.classList.add('is-invalid');
            if (fb) fb.textContent = 'Category name is required.';
            return false;
        }
        if (EXISTING.indexOf(v.toLowerCase()) !== -1) {
            nameEl.classList.add('is-invalid');
            if (fb) fb.textContent = 'A similar category name already exists. Consider a unique name.';
            toast('warning', 'Possible duplicate', 'Category name matches an existing parent category.');
            return false;
        }
        nameEl.classList.add('is-valid');
        if (fb) fb.textContent = '';
        return true;
    }

    function validateImage(file) {
        if (!file) return true;
        var okTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (okTypes.indexOf(file.type) === -1) {
            toast('error', 'Invalid image', 'Allowed types: JPG, PNG, WEBP, GIF.');
            return false;
        }
        if (file.size > 5 * 1024 * 1024) {
            toast('error', 'Image too large', 'Maximum size is 5MB.');
            return false;
        }
        return true;
    }

    function showPreview(file) {
        if (!file || !previewImg || !previewWrap) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewWrap.classList.add('is-visible');
        };
        reader.readAsDataURL(file);
    }

    function clearImage() {
        if (imageEl) imageEl.value = '';
        if (previewImg) previewImg.src = '';
        if (previewWrap) previewWrap.classList.remove('is-visible');
        updateSeo();
    }

    // Sync inactive <-> status
    if (statusEl && inactiveEl) {
        inactiveEl.checked = !statusEl.checked;
        statusEl.addEventListener('change', function () {
            inactiveEl.checked = !statusEl.checked;
            statusEl.setAttribute('aria-checked', statusEl.checked ? 'true' : 'false');
            dirty = true;
            updateSidebar();
        });
        inactiveEl.addEventListener('change', function () {
            statusEl.checked = !inactiveEl.checked;
            statusEl.dispatchEvent(new Event('change'));
        });
    }

    if (nameEl) {
        nameEl.addEventListener('input', function () {
            dirty = true;
            updateSeo();
            validateNameRealtime();
        });
    }
    if (codeEl) {
        codeEl.addEventListener('input', function () { codeManual = true; dirty = true; });
    }
    if (slugEl) {
        slugEl.addEventListener('input', function () { slugManual = true; dirty = true; updateSeo(); });
    }
    var regen = document.getElementById('cfRegenCode');
    if (regen) regen.addEventListener('click', function () {
        codeManual = false;
        if (codeEl && nameEl) codeEl.value = codeify(nameEl.value);
        toast('success', 'Code updated', 'Category code regenerated from name.');
    });

    ['cfShortDescription', 'cfDescription', 'cfMetaTitle', 'cfMetaDescription', 'cfMetaKeywords'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function () {
            dirty = true;
            if (id === 'cfMetaTitle') el.dataset.touched = '1';
            if (id === 'cfShortDescription') setCount(el, document.getElementById('cfShortCount'), 160);
            if (id === 'cfDescription') setCount(el, document.getElementById('cfDescCount'), 1000);
            if (id === 'cfMetaTitle') setCount(el, document.getElementById('cfMetaTitleCount'), 70);
            if (id === 'cfMetaDescription') setCount(el, document.getElementById('cfMetaDescCount'), 160);
            updateSeo();
        });
    });

    ['parent_id', 'tax_id', 'cfFeatured', 'cfHomepage', 'cfMenu', 'cfReviews'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('change', function () { dirty = true; updateSidebar(); updateProgress(); });
    });

    // Image dropzone
    if (dropzone && imageEl) {
        ['dragenter', 'dragover'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            dropzone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropzone.classList.remove('is-dragover');
            });
        });
        dropzone.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (!files || !files.length) return;
            if (!validateImage(files[0])) return;
            try {
                var dt = new DataTransfer();
                dt.items.add(files[0]);
                imageEl.files = dt.files;
            } catch (err) { /* older browsers */ }
            dirty = true;
            showPreview(files[0]);
            updateSeo();
            toast('success', 'Image ready', files[0].name);
        });
        imageEl.addEventListener('change', function () {
            var file = imageEl.files && imageEl.files[0];
            if (!file) { clearImage(); return; }
            if (!validateImage(file)) { clearImage(); return; }
            dirty = true;
            showPreview(file);
            updateSeo();
        });
    }
    var replaceBtn = document.getElementById('cfReplaceImage');
    var removeBtn = document.getElementById('cfRemoveImage');
    if (replaceBtn && imageEl) replaceBtn.addEventListener('click', function () { imageEl.click(); });
    if (removeBtn) removeBtn.addEventListener('click', function () {
        clearImage();
        toast('warning', 'Image removed', 'No image will be uploaded.');
    });

    // Draft
    var draftKey = 'category_create_draft';
    var draftBtn = document.getElementById('cfSaveDraftBtn');
    if (draftBtn) {
        draftBtn.addEventListener('click', function () {
            try {
                localStorage.setItem(draftKey, JSON.stringify({
                    name: nameEl ? nameEl.value : '',
                    parent_id: document.getElementById('parent_id') ? document.getElementById('parent_id').value : '',
                    tax_id: document.getElementById('tax_id') ? document.getElementById('tax_id').value : '',
                    status: statusEl ? !!statusEl.checked : true,
                    short: document.getElementById('cfShortDescription') ? document.getElementById('cfShortDescription').value : '',
                    desc: document.getElementById('cfDescription') ? document.getElementById('cfDescription').value : '',
                    slug: slugEl ? slugEl.value : '',
                    metaTitle: document.getElementById('cfMetaTitle') ? document.getElementById('cfMetaTitle').value : '',
                    metaDesc: document.getElementById('cfMetaDescription') ? document.getElementById('cfMetaDescription').value : '',
                    savedAt: new Date().toISOString()
                }));
                toast('success', 'Draft saved', 'Saved on this device only.');
            } catch (e) {
                toast('error', 'Draft failed', 'Could not save draft locally.');
            }
        });
        try {
            var raw = localStorage.getItem(draftKey);
            if (raw && nameEl && !nameEl.value) {
                var d = JSON.parse(raw);
                if (d.name) nameEl.value = d.name;
                if (d.parent_id && document.getElementById('parent_id')) document.getElementById('parent_id').value = d.parent_id;
                if (d.tax_id && document.getElementById('tax_id')) document.getElementById('tax_id').value = d.tax_id;
                if (statusEl && typeof d.status !== 'undefined') {
                    statusEl.checked = !!d.status;
                    if (inactiveEl) inactiveEl.checked = !statusEl.checked;
                }
                if (d.short && document.getElementById('cfShortDescription')) document.getElementById('cfShortDescription').value = d.short;
                if (d.desc && document.getElementById('cfDescription')) document.getElementById('cfDescription').value = d.desc;
                if (d.slug && slugEl) { slugEl.value = d.slug; slugManual = true; }
                if (d.metaTitle && document.getElementById('cfMetaTitle')) {
                    document.getElementById('cfMetaTitle').value = d.metaTitle;
                    document.getElementById('cfMetaTitle').dataset.touched = '1';
                }
                if (d.metaDesc && document.getElementById('cfMetaDescription')) document.getElementById('cfMetaDescription').value = d.metaDesc;
                toast('success', 'Draft restored', 'Loaded your last local draft.');
            }
        } catch (e) {}
    }

    // Preview modal fill
    var previewBtn = document.getElementById('cfPreviewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function () {
            updateSeo();
            document.getElementById('cfPrevUrl').textContent = document.getElementById('cfGUrl').textContent;
            document.getElementById('cfPrevTitle').textContent = document.getElementById('cfGTitle').textContent;
            document.getElementById('cfPrevDesc').textContent = document.getElementById('cfGDesc').textContent;
            document.getElementById('cfPrevName').textContent = nameEl.value || '—';
            var p = document.getElementById('parent_id');
            var t = document.getElementById('tax_id');
            document.getElementById('cfPrevParent').textContent = p && p.options[p.selectedIndex] ? p.options[p.selectedIndex].text : '—';
            document.getElementById('cfPrevTax').textContent = t && t.options[t.selectedIndex] ? t.options[t.selectedIndex].text : '—';
            document.getElementById('cfPrevStatus').textContent = statusEl && statusEl.checked ? 'Active' : 'Inactive';
        });
    }

    // Save & New intent (same POST; remember preference after redirect is not possible without backend —
    // we only mark UI spinner). Save & Close / Save all submit the same form.
    form.addEventListener('submit', function (e) {
        if (!validateNameRealtime()) {
            e.preventDefault();
            toast('error', 'Validation', 'Please fix the category name before saving.');
            nameEl.focus();
            return;
        }
        if (imageEl && imageEl.files && imageEl.files[0] && !validateImage(imageEl.files[0])) {
            e.preventDefault();
            return;
        }
        var spinner = document.getElementById('cfSaveSpinner');
        var icon = document.getElementById('cfSaveIcon');
        if (spinner) spinner.classList.remove('d-none');
        if (icon) icon.classList.add('d-none');
        dirty = false;
        try { localStorage.removeItem(draftKey); } catch (err) {}
    });

    form.addEventListener('reset', function () {
        setTimeout(function () {
            codeManual = false;
            slugManual = false;
            clearImage();
            updateSeo();
            toast('warning', 'Form reset', 'All fields were cleared.');
        }, 0);
    });

    // Cancel with confirm if dirty
    document.querySelectorAll('#cfCancelBtn, #cfBackTop').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!dirty) return;
            e.preventDefault();
            var modalEl = document.getElementById('cfConfirmModal');
            if (modalEl && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else if (confirm('Discard unsaved changes?')) {
                window.location.href = btn.getAttribute('href');
            }
        });
    });

    updateSeo();
    setCount(document.getElementById('cfShortDescription'), document.getElementById('cfShortCount'), 160);
    setCount(document.getElementById('cfDescription'), document.getElementById('cfDescCount'), 1000);
    setCount(document.getElementById('cfMetaTitle'), document.getElementById('cfMetaTitleCount'), 70);
    setCount(document.getElementById('cfMetaDescription'), document.getElementById('cfMetaDescCount'), 160);
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
