<?php require_once APPROOT . '/app/views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Main content area -->
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?php echo isset($data['selectedCountry']) ? 'Edit Country: ' . htmlspecialchars($data['selectedCountry']['name']) : 'Add New Country'; ?>
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Product
                        </a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCountryModal">
                            <i class="fas fa-plus"></i> Add New Country
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($data['selectedCountry'])): ?>
                        <form action="?controller=country&action=update" method="POST" enctype="multipart/form-data" id="countryForm">
                            <input type="hidden" name="id" value="<?php echo $data['selectedCountry']['id']; ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="countrySelect" class="form-label">Select Country</label>
                                        <select name="id" id="countrySelect" class="form-select select2">
                                            <option value="">-- Select a Country --</option>
                                            <?php 
                                            $existingCountries = array_column($data['countries'], 'name', 'id');
                                            foreach ($data['countries'] as $country): 
                                                // Get country code from the country name (first 2 characters in lowercase)
                                                $countryCode = strtolower(substr($country['name'], 0, 2));
                                                $flagImage = !empty($country['flag_image']) ? 
                                                    BASE_URL . 'uploads/flags/' . $country['flag_image'] : 
                                                    'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                                $selected = (isset($data['selectedCountry']) && $data['selectedCountry']['id'] == $country['id']) ? 'selected' : '';
                                                $countryName = htmlspecialchars($country['name']);
                                            ?>
                                            <option value="<?php echo $country['id']; ?>" 
                                                data-flag="<?php echo $countryCode; ?>" 
                                                data-flag-image="<?php echo $flagImage; ?>"
                                                data-flag-path="<?php echo $flagImage; ?>"
                                                <?php echo $selected; ?>>
                                                <?php echo $countryName; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" id="name" name="name" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="flag_image" class="form-label">Flag Image</label>
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="flag_image" name="flag_image" accept="image/*" onchange="previewFlagImage(this)">
                                            <?php if (!empty($data['selectedCountry']['flag_image'])): 
                                                $flagImage = BASE_URL . 'uploads/flags/' . $data['selectedCountry']['flag_image'];
                                            ?>
                                                <div class="input-group-text p-0 overflow-hidden" style="width: 40px;">
                                                    <img src="<?php echo $flagImage; ?>" 
                                                         alt="Flag" 
                                                         id="flagPreview"
                                                         class="img-fluid"
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <a href="<?php echo $flagImage; ?>" 
                                                   target="_blank" 
                                                   class="btn btn-outline-secondary" 
                                                   title="View Current Flag">
                                                    <i class="fas fa-expand"></i>
                                                </a>
                                            <?php else: ?>
                                                <div class="input-group-text p-0 overflow-hidden" style="width: 40px;">
                                                    <img src="https://flagcdn.com/24x18/xx.png" 
                                                         alt="No Flag" 
                                                         id="flagPreview"
                                                         class="img-fluid"
                                                         style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">Upload a square flag image (recommended: 64x64px)</small>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                    <?php echo (empty($data['selectedCountry']['status']) || $data['selectedCountry']['status'] === 'active') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="status">Active</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Country</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Please select a country from the dropdown or add a new one.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCountryForm" action="?controller=country&action=create" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCountryModalLabel">Add New Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_country_name" class="form-label">Country Name</label>
                        <input type="text" class="form-control" id="new_country_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_flag_image" class="form-label">Flag Image</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="new_flag_image" name="flag_image" accept="image/*" onchange="previewNewFlagImage(this)">
                            <div class="input-group-text p-0 overflow-hidden" style="width: 40px;">
                                <img src="https://flagcdn.com/24x18/xx.png" 
                                     alt="No Flag" 
                                     id="newFlagPreview"
                                     class="img-fluid"
                                     style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                            </div>
                        </div>
                        <small class="text-muted">Upload a square flag image (recommended: 64x64px)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Initialize Select2 for the country dropdown -->
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 5px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #0d6efd;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #e9ecef;
}
.flag-icon {
    width: 20px;
    height: 15px;
    object-fit: cover;
    margin-right: 8px;
    display: inline-block;
    vertical-align: middle;
}
</style>

<script>
$(document).ready(function() {
    // Handle add country form submission
    $('#addCountryForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalBtnText = $submitBtn.html();
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Close the modal
                    $('#addCountryModal').modal('hide');
                    
                    // Add the new country to the dropdown
                    var country = response.country;
                    var countryCode = country.code ? country.code.toLowerCase() : country.name.substring(0, 2).toLowerCase();
                    var flagImagePath = country.flag_image ? 
                        '<?php echo BASE_URL; ?>uploads/flags/' + country.flag_image : 
                        'https://flagcdn.com/24x18/' + countryCode + '.png';
                        
                    // Add to select2 dropdown
                    var $option = $('<option>', {
                        value: country.id,
                        'data-flag': countryCode,
                        'data-flag-image': flagImagePath,
                        'data-flag-path': flagImagePath,
                        selected: true
                    }).text(country.name);
                    
                    // Add the new option to the dropdown
                    $('#countrySelect').append($option).trigger('change');
                    
                    // Update the select2 to refresh the dropdown
                    if ($.fn.select2) {
                        $('#countrySelect').select2('destroy');
                        $('#countrySelect').select2({
                            templateResult: formatCountry,
                            templateSelection: formatCountry,
                            escapeMarkup: function(m) { return m; }
                        });
                    }
                    
                    // Create the new row with proper flag image - matching website style
                    var flagImg = '';
                    if (country.flag_image) {
                        flagImg = '<img src="' + '<?php echo BASE_URL; ?>uploads/flags/' + country.flag_image + '" ' +
                                 'alt="' + country.name + '" ' +
                                 'class="table-flag" ' +
                                 'style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px; vertical-align: middle; margin-right: 8px;">';
                    } else {
                        flagImg = '<img src="https://flagcdn.com/24x18/' + countryCode + '.png" ' +
                                 'alt="' + country.name + '" ' +
                                 'class="table-flag" ' +
                                 'style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px; vertical-align: middle; margin-right: 8px;">';
                    }
                    
                    var $newRow = $('<tr>' +
                        '<td>' + country.id + '</td>' +
                        '<td class="text-center">' + flagImg + '</td>' +
                        '<td>' + country.name + '</td>' +
                        '<td><span class="badge bg-success">' + (country.status === 'active' ? 'Active' : 'Inactive') + '</span></td>' +
                        '<td>' +
                            '<a href="?controller=country&action=adminIndex&id=' + country.id + '" ' +
                               'class="btn btn-sm btn-primary" title="Edit">' +
                                '<i class="fas fa-edit"></i>' +
                            '</a> ' +
                            '<form action="?controller=country&action=delete" method="POST" class="d-inline" ' +
                                  'onsubmit="return confirm(\'Are you sure you want to delete this country? This action cannot be undone.\');">' +
                                '<input type="hidden" name="id" value="' + country.id + '">' +
                                '<button type="submit" class="btn btn-sm btn-danger" title="Delete">' +
                                    '<i class="fas fa-trash"></i>' +
                                '</button>' +
                            '</form>' +
                        '</td>' +
                    '</tr>');
                    
                    $('table tbody').prepend($newRow);
                    
                    // Show success message
                    showAlert('Country added successfully', 'success');
                    
                    // Reset the form
                    $form.trigger('reset');
                    $('#newFlagPreview').attr('src', 'https://flagcdn.com/24x18/xx.png').css('opacity', '0.5');
                } else {
                    showAlert(response.message || 'Error adding country', 'danger');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Error adding country: ';
                try {
                    var response = JSON.parse(xhr.responseText);
                    errorMessage += response.message || 'Unknown error';
                } catch (e) {
                    errorMessage += xhr.statusText || 'Unknown error';
                }
                showAlert(errorMessage, 'danger');
            },
            complete: function() {
                // Reset button state
                $submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });
    
    // Helper function to show alerts
    function showAlert(message, type) {
        var $alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                       message +
                       '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                       '</div>');
        
        $('.container-fluid').prepend($alert);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $alert.alert('close');
        }, 5000);
    }

    // Function to format the country option
    function formatCountry(country) {
        if (!country.id) { return country.text; }
        
        var $country = $(
            '<div class="d-flex align-items-center">' +
            '<img src="' + $(country.element).data('flag-image') + '" class="flag-icon me-2" alt="' + country.text + '">' +
            '<span>' + country.text + '</span>' +
            '</div>'
        );
        return $country;
    };

    // Initialize Select2 for the country dropdown
    $('#countrySelect').select2({
        placeholder: 'Search for a country...',
        width: '100%',
        templateResult: formatCountry,
        templateSelection: formatCountry,
        escapeMarkup: function(m) { return m; },
        allowClear: true,
        templateResult: formatCountry,
        templateSelection: formatCountrySelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });
    
        // Format country with flag
    function formatCountry(country) {
        if (!country.id) return country.text;
        var $country = $(
            '<div class="d-flex align-items-center">' +
            '   <img src="' + $(country.element).data('flag-image') + '" ' +
            '        alt="' + country.text.trim() + '" ' +
            '        class="me-2" ' +
            '        style="width: 20px; height: 15px; object-fit: cover; border: 1px solid #dee2e6;">' +
            '   <span>' + country.text + '</span>' +
            '</div>'
        );
        return $country;
    }
    
    // Format selected country
    function formatCountrySelection(country) {
        if (!country.id) return country.text;
        
        var flagImage = $(country.element).data('flag-image');
        if (!flagImage) {
            // If no flag image is set, use the flag from the country code
            var countryCode = $(country.element).data('flag');
            flagImage = 'https://flagcdn.com/24x18/' + countryCode + '.png';
        }
        
        return $(
            '<div class="d-flex align-items-center">' +
            '   <img src="' + flagImage + '" ' +
            '        alt="' + (country.text || '').trim() + '" ' +
            '        class="me-2" ' +
            '        style="width: 20px; height: 15px; object-fit: cover; border: 1px solid #dee2e6;">' +
            '   <span>' + (country.text || '') + '</span>' +
            '</div>'
        );
    }
    
        // Handle country selection
    $('#countrySelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var countryName = selectedOption.text();
        var countryId = $(this).val();
        var flagImage = selectedOption.data('flag-image');
        
        // Update the hidden name field with the selected country name
        $('#name').val(countryName);
        
        // Update flag preview if available
        var $flagPreview = $('#flagPreview');
        var $flagLink = $flagPreview.closest('.input-group').find('a');
        
        if (flagImage) {
            $flagPreview.attr('src', flagImage).css('opacity', '1');
            $flagLink.attr('href', flagImage);
        } else {
            // Try to get flag from country code if no image is set
            var countryCode = selectedOption.data('flag');
            if (countryCode) {
                var flagUrl = 'https://flagcdn.com/24x18/' + countryCode + '.png';
                $flagPreview.attr('src', flagUrl).css('opacity', '1');
                $flagLink.attr('href', flagUrl);
            } else {
                $flagPreview.attr('src', 'https://flagcdn.com/24x18/xx.png').css('opacity', '0.5');
                $flagLink.attr('href', '#');
            }
        }
        
        if (countryId) {
            // Only redirect if this is a predefined country (not a new one being added)
            var $matchingOption = $(this).find('option[value="' + countryId + '"]');
            if ($matchingOption.length > 0) {
                // If we're not already on this country's edit page, redirect to it
                var currentUrl = new URL(window.location.href);
                var currentId = currentUrl.searchParams.get('id');
                
                if (currentId !== countryId) {
                    // Update the form action to point to the update URL for the selected country
                    $('#countryForm').attr('action', '?controller=country&action=update&id=' + countryId);
                    
                    // Redirect to the country's edit page
                    window.location.href = '?controller=country&action=adminIndex&id=' + countryId;
                }
            } else {
                // For new countries, update the form action to point to the add URL
                $('#countryForm').attr('action', '?controller=country&action=add');
            }
        } else {
            // Clear the name field if no country is selected
            $('#name').val('');
            $('#flagPreview').attr('src', 'https://flagcdn.com/24x18/xx.png').css('opacity', '0.5');
        }
    });
    
    // Prevent form submission when pressing Enter in the search box
    $('#countrySelect').on('select2:opening select2:closing', function(event) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });

    // Set the selected value when the page loads
    <?php if (isset($data['selectedCountry'])): ?>
        var selectedCountryId = '<?php echo $data['selectedCountry']['id']; ?>';
        var selectedCountryName = '<?php echo addslashes($data['selectedCountry']['name']); ?>';
        
        // Update the flag preview with the selected country's flag
        var selectedFlagImage = $('#countrySelect option:selected').data('flag-image');
        if (!selectedFlagImage && '<?php echo !empty($data['selectedCountry']['flag_image']) ? 'true' : ''; ?>') {
            selectedFlagImage = '<?php echo BASE_URL; ?>uploads/flags/<?php echo $data['selectedCountry']['flag_image']; ?>';
        }
        
        if (selectedFlagImage) {
            $('#flagPreview').attr('src', selectedFlagImage).css('opacity', '1');
        }
        
        if (selectedCountryId) {
            // Try to find the option with matching ID or name
            var $option = $('#countrySelect option[value="' + selectedCountryId + '"]');
            
            // If not found by ID, try to find by name
            if ($option.length === 0) {
                $option = $('#countrySelect option').filter(function() {
                    return $(this).text().trim() === selectedCountryName;
                }).first();
            }
            
            if ($option.length > 0) {
                $option.prop('selected', true);
                $('#countrySelect').trigger('change');
            } else {
                // If the country is not in the dropdown, update the name field directly
                $('#name').val(selectedCountryName);
            }
            
            // Set the form action to update the current country
            $('#countryForm').attr('action', '?controller=country&action=update&id=' + selectedCountryId);
        }
    <?php endif; ?>
});
</script>

<!-- Add Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Style for table flags - Match website country of origin dropdown */
.table-flag {
    width: 24px;
    height: 18px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    border-radius: 2px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    vertical-align: middle;
    margin-right: 8px;
}

/* Style for Select2 dropdown with flags */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    padding: 5px;
}
.select2-container--bootstrap-5 .select2-selection--single {
    padding: 0.375rem 0.75rem;
    height: auto;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    line-height: 1.5;
}

/* Style for flag images in dropdown - Match website country of origin dropdown */
.select2-container--bootstrap-5 .select2-results__option {
    padding: 6px 12px;
    display: flex;
    align-items: center;
    font-size: 14px;
}

.select2-container--bootstrap-5 .select2-results__option img {
    width: 24px;
    height: 18px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    margin-right: 10px;
    border-radius: 2px;
}

/* Selected item in dropdown */
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    display: flex;
    align-items: center;
}

/* Flag preview in the form */
.flag-preview {
    width: 32px;
    height: 24px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    margin-right: 8px;
}
</style>

<!-- List of All Countries -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">All Countries</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="countriesTable" class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Flag</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['countries'] as $index => $country): ?>
                                <tr>
                                    <td><?php echo $country['id']; ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $countryCode = strtolower(substr($country['name'], 0, 2));
                                        $flagImage = !empty($country['flag_image']) ? 
                                            BASE_URL . 'uploads/flags/' . $country['flag_image'] : 
                                            'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                        ?>
                                        <?php if (!empty($country['flag_image']) && file_exists(UPLOAD_PATH . 'flags/' . $country['flag_image'])): ?>
                                            <img src="<?php echo BASE_URL . 'uploads/flags/' . $country['flag_image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($country['name']); ?>" 
                                                 class="table-flag"
                                                 style="width: 30px; height: 20px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">
                                        <?php else: ?>
                                            <img src="https://flagcdn.com/24x18/<?php echo $countryCode; ?>.png" 
                                                 alt="<?php echo htmlspecialchars($country['name']); ?>" 
                                                 class="table-flag"
                                                 style="width: 30px; height: 20px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($country['name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $country['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($country['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?controller=country&action=adminIndex&id=<?php echo $country['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="?controller=country&action=delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this country? This action cannot be undone.');">
                                            <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete" <?php echo $country['products_count'] > 0 ? 'disabled' : ''; ?>>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php if ($country['products_count'] > 0): ?>
                                                <small class="d-block text-muted" title="Cannot delete country with products">
                                                    <?php echo $country['products_count']; ?> product(s)
                                                </small>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCountryModal" tabindex="-1" aria-labelledby="deleteCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCountryModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="countryNameToDelete"></strong>? This action cannot be undone.</p>
                <p class="text-danger"><strong>Note:</strong> You cannot delete a country that has associated products.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="?controller=country&action=delete" method="POST">
                    <input type="hidden" name="id" id="deleteCountryId" value="">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview flag image when a file is selected in edit form
    function previewFlagImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#flagPreview')
                    .attr('src', e.target.result)
                    .css('opacity', '1')
                    .closest('.input-group')
                    .find('a')
                    .attr('href', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Preview flag image when a file is selected in add form
    function previewNewFlagImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#newFlagPreview')
                    .attr('src', e.target.result)
                    .css('opacity', '1');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}

// Handle delete button click
$(document).on('click', '.delete-country', function() {
    var countryId = $(this).data('id');
    var countryName = $(this).data('name');
    
    // Set the country name and ID in the modal
    $('#countryNameToDelete').text(countryName);
    $('#deleteCountryId').val(countryId);
    
    // Show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteCountryModal'));
    deleteModal.show();
});

// Initialize Select2 for the country dropdown
$(document).ready(function() {
    // Handle delete button click
    $('.delete-country').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var countryName = $(this).data('name');
        
        if (confirm('Are you sure you want to delete ' + countryName + '? This action cannot be undone.')) {
            form.submit();
        }
    });
    // ... (existing Select2 initialization code) ...
});
</script>

<?php require_once APPROOT . '/app/views/admin/layouts/footer.php'; ?>
