<?php
$countries = $countries ?? [];
$selectedCountry = $selectedCountry ?? null;
require_once APP_PATH . 'views/admin/layouts/header.php';
?>

<style>
    .page-shell {
      width: 100%;
      max-width: none;
      margin: 0;
    }

    .page-title {
      font-weight: 600;
      letter-spacing: -0.02em;
      margin-bottom: 0;
    }
    .page-subtitle {
      color: var(--muted-color);
      font-size: 0.9rem;
      margin-top: 0.25rem;
      margin-bottom: 0;
    }

    .countries-admin .card { border-radius: 14px; border: 1px solid var(--border-color); }
    .countries-admin .card-header {
      background: var(--surface-color);
      border-bottom: 1px solid var(--border-color);
      border-top-left-radius: 14px;
      border-top-right-radius: 14px;
    }

    .countries-admin .btn { border-radius: 10px; }
    .countries-admin .btn:focus { box-shadow: 0 0 0 .2rem rgba(59,130,246,.25); }

    .countries-admin .form-control,
    .countries-admin .custom-file-input,
    .countries-admin .custom-select {
      border-radius: 10px;
    }
    .countries-admin .form-control:focus,
    .countries-admin .custom-file-input:focus,
    .countries-admin .custom-select:focus {
      border-color: rgba(59,130,246,.6);
      box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
    }

    .countries-table-scroll {
      max-height: 65vh;
      overflow: auto;
      -webkit-overflow-scrolling: touch;
      border-top: 1px solid var(--border-color);
    }

    .countries-table-scroll .table { margin-bottom: 0; }

    .countries-table-scroll thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      background: var(--surface-color);
      box-shadow: 0 1px 0 0 var(--border-color);
      border-top: 0;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 0.02em;
      color: var(--muted-color);
      text-transform: uppercase;
      white-space: nowrap;
    }

    .countries-admin #countriesTable tbody tr { transition: background-color .15s ease, box-shadow .15s ease; }
    .countries-admin #countriesTable tbody tr:hover { background: rgba(59,130,246,.06); }

    .table-flag {
      width: 28px;
      height: 20px;
      object-fit: cover;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.08);
      background: #fff;
    }

    .status-badge { font-weight: 600; }

    @media (max-width: 575.98px) {
      #countriesTable thead { display: none; }
      #countriesTable tbody tr {
        display: block;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        background: var(--surface-color);
      }
      #countriesTable tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0.75rem;
        border-bottom: 1px solid var(--border-color);
      }
      #countriesTable tbody td:last-child { border-bottom: 0; }
      #countriesTable tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--muted-color);
        margin-right: 0.75rem;
        flex-shrink: 0;
      }
      #countriesTable tbody td[data-label="Flag"] { display: block; }
      #countriesTable tbody td[data-label="Flag"]::before { content: none; }
      #countriesTable tbody td[data-label="Actions"] .btn-group { width: 100%; display: flex; gap: 0.5rem; }
      #countriesTable tbody td[data-label="Actions"] .btn { flex: 1 1 auto; }
    }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 countries-admin">
    <div class="page-shell">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4">
            <div>
                <h2 class="h4 page-title">Manage Countries</h2>
                <p class="page-subtitle">Add, edit, and maintain countries of origin used in your product system.</p>
            </div>
            <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap: .5rem;">
                <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-3 mb-md-4">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="gap: .75rem;">
                    <div>
                        <div class="font-weight-600" id="countryFormTitle">Add New Country</div>
                        <div class="text-muted small">Use the form below to add a new country or edit an existing one.</div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm d-none" id="cancelEditBtn">
                        Cancel Edit
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="countryForm" action="?controller=country&action=create" method="POST" enctype="multipart/form-data" novalidate>
                    <input type="hidden" name="id" id="country_id" value="">

                    <div class="form-row">
                        <div class="form-group col-12 col-md-5">
                            <label for="country_name" class="mb-1">Country</label>
                            <input
                                type="text"
                                class="form-control"
                                id="country_name"
                                name="name"
                                list="countryNameList"
                                placeholder="Start typing to search / add a country"
                                required
                                autocomplete="off"
                            >
                            <datalist id="countryNameList">
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo htmlspecialchars($country['name'] ?? ''); ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                            <div class="invalid-feedback">Please enter a country name.</div>
                            <small class="text-muted">Tip: type to search existing, or enter a new name to add.</small>
                        </div>

                        <div class="form-group col-12 col-md-5">
                            <label for="flag_image" class="mb-1">Flag Image</label>
                            <div class="d-flex align-items-center" style="gap: .5rem;">
                                <div class="custom-file" style="flex: 1 1 auto;">
                                    <input type="file" class="custom-file-input" id="flag_image" name="flag_image" accept="image/*">
                                    <label class="custom-file-label" for="flag_image">Choose image</label>
                                </div>
                                <img id="flagPreview" src="https://flagcdn.com/24x18/xx.png" alt="Flag preview" class="table-flag" style="opacity: .55; width: 34px; height: 24px;">
                            </div>
                            <small class="text-muted">Recommended: square image, e.g. 64×64px (max 2MB).</small>
                        </div>

                        <div class="form-group col-12 col-md-2">
                            <label class="mb-1 d-block">Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" checked>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary" id="countrySubmitBtn">
                            <i class="fas fa-plus mr-2"></i> Add New Country
                        </button>
                        <small class="text-muted align-self-center">Changes save immediately and will reflect in product forms.</small>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="gap: .75rem;">
                    <div>
                        <div class="font-weight-600">All Countries</div>
                        <div class="text-muted small">Flag, status and actions. On mobile, rows stack for readability.</div>
                    </div>
                    <div class="text-muted small">Total: <strong><?php echo count($countries); ?></strong></div>
                </div>
            </div>
            <div class="countries-table-scroll">
                <div class="table-responsive">
                    <table id="countriesTable" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 72px;">Flag</th>
                                <th>Name</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($countries)): ?>
                                <?php foreach ($countries as $country):
                                    $countryName = htmlspecialchars($country['name'] ?? '');
                                    $status = ($country['status'] ?? 'inactive') === 'active' ? 'active' : 'inactive';
                                    $isActive = $status === 'active';
                                    $code = strtolower(substr($country['name'] ?? '', 0, 2));
                                    $hasLocalFlag = !empty($country['flag_image']) && defined('UPLOAD_PATH') && file_exists(UPLOAD_PATH . 'flags/' . $country['flag_image']);
                                    $flagUrl = $hasLocalFlag
                                        ? (BASE_URL . 'uploads/flags/' . $country['flag_image'])
                                        : ('https://flagcdn.com/24x18/' . $code . '.png');
                                ?>
                                <tr>
                                    <td data-label="Flag" class="text-center">
                                        <img src="<?php echo htmlspecialchars($flagUrl); ?>" alt="<?php echo $countryName; ?> flag" class="table-flag">
                                    </td>
                                    <td data-label="Name">
                                        <div class="font-weight-600"><?php echo $countryName; ?></div>
                                        <?php if (!empty($country['code'])): ?>
                                            <div class="text-muted small">Code: <?php echo htmlspecialchars($country['code']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Status">
                                        <span class="badge badge-<?php echo $isActive ? 'success' : 'secondary'; ?> status-badge">
                                            <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions" class="text-nowrap" onclick="event.stopPropagation();">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Country actions">
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary edit-country"
                                                data-id="<?php echo (int)$country['id']; ?>"
                                                data-name="<?php echo $countryName; ?>"
                                                data-status="<?php echo $status; ?>"
                                                data-flag-url="<?php echo htmlspecialchars($flagUrl); ?>"
                                                data-has-products="<?php echo (int)($country['products_count'] ?? 0); ?>"
                                            >
                                                <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-outline-danger delete-country"
                                                data-id="<?php echo (int)$country['id']; ?>"
                                                data-name="<?php echo $countryName; ?>"
                                                data-disabled="<?php echo (!empty($country['products_count']) && (int)$country['products_count'] > 0) ? '1' : '0'; ?>"
                                            >
                                                <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No countries found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3 mt-md-4">
            <div class="card-header py-3">
                <div class="font-weight-600">Style Guide</div>
                <div class="text-muted small">Tokens aligned with your admin layout (Inter font, 8px spacing rhythm, rounded corners).</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="text-muted small mb-2">Colors</div>
                        <div class="d-flex flex-wrap" style="gap: .5rem;">
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Primary</div>
                                <div class="font-weight-600">#3b82f6</div>
                            </div>
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Success</div>
                                <div class="font-weight-600">#198754</div>
                            </div>
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Danger</div>
                                <div class="font-weight-600">#dc3545</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <div class="text-muted small mb-2">Component states</div>
                        <div class="text-muted small">
                            Buttons: hover highlight, focus ring visible, disabled state for restricted delete.
                            Inputs: blue focus ring, invalid feedback on required fields.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCountryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header" style="background: rgba(220,53,69,0.08); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" id="deleteCountryModalLabel">Delete Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">Are you sure you want to delete <strong id="countryNameToDelete"></strong>?</div>
                <div class="text-muted small" id="deleteCountryHint">This action cannot be undone.</div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCountryForm" action="?controller=country&action=delete" method="POST" class="mb-0">
                    <input type="hidden" name="id" id="deleteCountryId" value="">
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var form = document.getElementById('countryForm');
    var formTitle = document.getElementById('countryFormTitle');
    var cancelEditBtn = document.getElementById('cancelEditBtn');
    var idInput = document.getElementById('country_id');
    var nameInput = document.getElementById('country_name');
    var statusInput = document.getElementById('status');
    var submitBtn = document.getElementById('countrySubmitBtn');
    var fileInput = document.getElementById('flag_image');
    var fileLabel = document.querySelector('label.custom-file-label[for="flag_image"]');
    var preview = document.getElementById('flagPreview');

    function setModeAdd() {
        if (!form) return;
        form.action = '?controller=country&action=create';
        if (idInput) idInput.value = '';
        if (formTitle) formTitle.textContent = 'Add New Country';
        if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-plus mr-2"></i> Add New Country';
        if (cancelEditBtn) cancelEditBtn.classList.add('d-none');
        if (nameInput) nameInput.value = '';
        if (statusInput) statusInput.checked = true;
        if (preview) { preview.src = 'https://flagcdn.com/24x18/xx.png'; preview.style.opacity = '.55'; }
        if (fileInput) fileInput.value = '';
        if (fileLabel) fileLabel.textContent = 'Choose image';
        if (nameInput) { nameInput.classList.remove('is-invalid'); }
    }

    function setModeEdit(country) {
        if (!form || !country) return;
        form.action = '?controller=country&action=update';
        if (idInput) idInput.value = country.id || '';
        if (nameInput) nameInput.value = country.name || '';
        if (statusInput) statusInput.checked = (country.status === 'active');
        if (preview && country.flagUrl) { preview.src = country.flagUrl; preview.style.opacity = '1'; }
        if (formTitle) formTitle.textContent = 'Edit Country';
        if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Update Country';
        if (cancelEditBtn) cancelEditBtn.classList.remove('d-none');
        if (fileInput) fileInput.value = '';
        if (fileLabel) fileLabel.textContent = 'Choose image';

        try {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch (e) {
            // ignore
        }
    }

    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            setModeAdd();
            try { nameInput && nameInput.focus(); } catch (e) {}
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileLabel && fileInput.files && fileInput.files[0]) {
                fileLabel.textContent = fileInput.files[0].name;
            }
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) { preview.src = e.target.result; preview.style.opacity = '1'; }
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    }

    document.addEventListener('click', function(e) {
        var editBtn = e.target.closest('.edit-country');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            setModeEdit({
                id: editBtn.getAttribute('data-id'),
                name: editBtn.getAttribute('data-name') || '',
                status: editBtn.getAttribute('data-status') || 'inactive',
                flagUrl: editBtn.getAttribute('data-flag-url') || ''
            });
            return;
        }

        var delBtn = e.target.closest('.delete-country');
        if (delBtn) {
            e.preventDefault();
            e.stopPropagation();

            var disabled = delBtn.getAttribute('data-disabled') === '1';
            var id = delBtn.getAttribute('data-id');
            var name = delBtn.getAttribute('data-name') || 'this country';

            document.getElementById('countryNameToDelete').textContent = name;
            document.getElementById('deleteCountryId').value = id;

            var hint = document.getElementById('deleteCountryHint');
            var confirmBtn = document.getElementById('confirmDeleteBtn');
            if (disabled) {
                if (hint) hint.textContent = 'You cannot delete a country that has associated products.';
                if (confirmBtn) confirmBtn.disabled = true;
            } else {
                if (hint) hint.textContent = 'This action cannot be undone.';
                if (confirmBtn) confirmBtn.disabled = false;
            }

            $('#deleteCountryModal').modal('show');
            return;
        }
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!nameInput || !nameInput.value || !nameInput.value.trim()) {
                e.preventDefault();
                nameInput.classList.add('is-invalid');
                try { nameInput.focus(); } catch (ex) {}
                return false;
            }
            nameInput.classList.remove('is-invalid');
        });
    }
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
