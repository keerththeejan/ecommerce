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
                    <div>
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
                                        <select name="id" id="countrySelect" class="form-select">
                                            <option value="">-- Select a Country --</option>
                                            <!-- Popular countries first -->
                                            <optgroup label="Popular Countries">
                                                <?php 
                                                $popularCountries = [
                                                    'thailand' => 'Thailand',
                                                    'philippines' => 'Philippines',
                                                    'vietnam' => 'Vietnam',
                                                    'south-korea' => 'South Korea',
                                                    'hong-kong' => 'Hong Kong',
                                                    'china' => 'China',
                                                    'japan' => 'Japan',
                                                    'taiwan' => 'Taiwan',
                                                    'sri-lanka' => 'Sri Lanka',
                                                    'india' => 'India',
                                                    'singapore' => 'Singapore',
                                                    'malaysia' => 'Malaysia',
                                                    'indonesia' => 'Indonesia'
                                                ];
                                                
                                                foreach ($popularCountries as $code => $name): 
                                                    $selected = (isset($data['selectedCountry']) && $data['selectedCountry']['name'] === $name) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $code; ?>" data-flag="<?php echo substr($code, 0, 2); ?>" data-name="<?php echo htmlspecialchars($name); ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                            <optgroup label="Other Countries">
                                                <?php 
                                                $existingCountries = array_column($data['countries'], 'name', 'id');
                                                foreach ($data['countries'] as $country): 
                                                    if (!in_array($country['name'], $popularCountries)): 
                                                ?>
                                                    <option value="<?php echo $country['id']; ?>" 
                                                        data-flag="" 
                                                        data-name="<?php echo htmlspecialchars($country['name']); ?>"
                                                        <?php echo (isset($data['selectedCountry']) && $data['selectedCountry']['id'] == $country['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($country['name']); ?>
                                                    </option>
                                                <?php 
                                                    endif;
                                                endforeach; 
                                                ?>
                                            </optgroup>
                                        </select>
                                        <input type="hidden" id="name" name="name" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="flag_image" class="form-label">Flag Image</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="flag_image" name="flag_image" accept="image/*">
                                        <?php if (!empty($data['selectedCountry']['flag_image'])): ?>
                                            <a href="<?php echo BASE_URL . 'uploads/flags/' . $data['selectedCountry']['flag_image']; ?>" 
                                               target="_blank" class="btn btn-outline-secondary" title="View Current Flag">
                                                <i class="fas fa-flag"></i>
                                            </a>
                                        <?php endif; ?>
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
            <form action="?controller=country&action=create" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCountryModalLabel">Add New Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_country_name" class="form-label">Country Name</label>
                        <input type="text" class="form-control" id="new_country_name" name="name" required>
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
<script>
$(document).ready(function() {
    // Initialize Select2 for the country dropdown
    $('#countrySelect').select2({
        placeholder: 'Search country...',
        width: '100%',
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
        var flagCode = $(country.element).data('flag');
        if (!flagCode) flagCode = 'xx'; // Default flag if none specified
        var $country = $(
            '<span><img class="me-2" src="https://flagcdn.com/24x18/' + flagCode + '.png" />' + country.text + '</span>'
        );
        return $country;
    }
    
    // Format selected country
    function formatCountrySelection(country) {
        if (!country.id) return country.text;
        var flagCode = $(country.element).data('flag');
        if (!flagCode) flagCode = 'xx'; // Default flag if none specified
        var $country = $(
            '<span><img class="me-2" src="https://flagcdn.com/24x18/' + flagCode + '.png" />' + country.text + '</span>'
        );
        return $country;
    }
    
    // Handle country selection
    $('#countrySelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var countryName = selectedOption.data('name') || selectedOption.text();
        var countryId = $(this).val();
        
        if (countryId) {
            // Update the hidden name field with the selected country name
            $('#name').val(countryName);
            
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
/* Style for Select2 dropdown */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    padding: 5px;
}
.select2-container--bootstrap-5 .select2-selection--single {
    padding: 0.375rem 0.75rem;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
}
</style>

<?php require_once APPROOT . '/app/views/admin/layouts/footer.php'; ?>
