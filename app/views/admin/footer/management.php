<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                    <h5 class="text-white mb-0">Footer Sections</h5>
                    <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                
                <ul class="nav flex-column" id="footerSectionsList">
                    <?php if (!empty($sections)): ?>
                        <?php foreach ($sections as $section): ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-white footer-section-item <?php echo (isset($currentSection) && $currentSection['id'] == $section['id']) ? 'active' : ''; ?>" 
                                   data-id="<?php echo $section['id']; ?>"
                                   data-type="<?php echo $section['type']; ?>">
                                    <?php if (!empty($section['icon'])): ?>
                                        <i class="<?php echo htmlspecialchars($section['icon']); ?> me-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-cog me-2"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($section['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <div class="text-muted small px-3 py-2">No footer sections found</div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-shoe-prints text-primary me-2"></i>
                    Footer Management
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="refreshSections">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                    <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Section
                    </a>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading section details...</p>
            </div>

            <!-- Content will be loaded here -->
            <div id="sectionContent">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-mouse-pointer fa-3x mb-3"></i>
                            <h4>Select a section from the sidebar</h4>
                            <p class="mb-0">or create a new one to get started</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i> Create New Section
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Section Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Global variables
    let currentSectionId = null;
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle section click
    $(document).on('click', '.footer-section-item', function(e) {
        e.preventDefault();
        const sectionId = $(this).data('id');
        const sectionType = $(this).data('type');
        
        // Update active state
        $('.footer-section-item').removeClass('active');
        $(this).addClass('active');
        
        // Load section content
        loadSectionContent(sectionId, sectionType);
    });
    
    // Handle refresh button
    $('#refreshSections').on('click', function() {
        if (currentSectionId) {
            const activeSection = $('.footer-section-item.active');
            loadSectionContent(currentSectionId, activeSection.data('type'));
        } else {
            // Reload the page to refresh the sidebar
            window.location.reload();
        }
    });
    
    // Function to load section content
    function loadSectionContent(sectionId, sectionType) {
        currentSectionId = sectionId;
        
        // Show loading indicator
        $('#loadingIndicator').show();
        $('#sectionContent').hide();
        
        // Load section content via AJAX
        $.ajax({
            url: '<?php echo BASE_URL; ?>admin/footer/edit/' + sectionId,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#sectionContent').html(response);
                
                // Initialize any plugins in the loaded content
                initializePlugins();
                
                // Show the content
                $('#loadingIndicator').hide();
                $('#sectionContent').fadeIn();
                
                // Update browser URL without reloading
                const newUrl = '<?php echo BASE_URL; ?>admin/footer/manage/' + sectionId;
                window.history.pushState({ path: newUrl }, '', newUrl);
            },
            error: function(xhr, status, error) {
                console.error('Error loading section:', error);
                
                // Show error message
                $('#sectionContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Failed to load section. Please try again.
                        <button class="btn btn-link p-0 ms-2" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i> Retry
                        </button>
                    </div>
                `);
                
                $('#loadingIndicator').hide();
                $('#sectionContent').fadeIn();
            }
        });
    }
    
    // Function to initialize plugins in loaded content
    function initializePlugins() {
        // Initialize form validation
        if ($.fn.validate) {
            $('form').validate({
                errorClass: 'is-invalid',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.after(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            });
        }
        
        // Initialize select2 if available
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Initialize icon picker if available
        if ($.fn.fontIconPicker) {
            $('.icon-picker').fontIconPicker({
                theme: 'fip-bootstrap',
                source: ['font-awesome'],
                emptyIcon: true
            });
        }
        
        // Initialize TinyMCE if available
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.tinymce',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                         'alignleft aligncenter alignright alignjustify | ' +
                         'bullist numlist outdent indent | removeformat | help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
                skin: 'bootstrap',
                icons: 'bootstrap'
            });
        }
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        // Check if we should load a specific section
        const match = window.location.href.match(/admin\/footer\/manage\/(\d+)/);
        if (match && match[1]) {
            const sectionId = match[1];
            const sectionItem = $(`.footer-section-item[data-id="${sectionId}"]`);
            if (sectionItem.length) {
                sectionItem.trigger('click');
            }
        } else {
            // Show the default view
            currentSectionId = null;
            $('.footer-section-item').removeClass('active');
            $('#sectionContent').html(`
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-mouse-pointer fa-3x mb-3"></i>
                            <h4>Select a section from the sidebar</h4>
                            <p class="mb-0">or create a new one to get started</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>admin/footer/add" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i> Create New Section
                        </a>
                    </div>
                </div>
            `);
        }
    });
});

// Function to show preview
function showPreview() {
    const form = document.getElementById('footerSectionForm');
    const formData = new FormData(form);
    
    // Show loading state
    $('#previewContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Generating preview...</p>
        </div>
    `);
    
    // Show the modal
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
    
    // Submit form data via AJAX for preview
    fetch('<?php echo BASE_URL; ?>admin/footer/preview', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        $('#previewContent').html(html);
    })
    .catch(error => {
        console.error('Error generating preview:', error);
        $('#previewContent').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to generate preview. Please try again.
            </div>
        `);
    });
}
</script>

<style>
/* Sidebar styles */
.sidebar {
    min-height: calc(100vh - 56px);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.8);
    border-radius: 0.25rem;
    margin: 0.25rem 0.5rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #fff;
    background-color: rgba(13, 110, 253, 0.25);
    font-weight: 500;
}

.sidebar .nav-link i {
    width: 20px;
    text-align: center;
}

/* Main content area */
main {
    padding-top: 1rem;
}

/* Loading indicator */
#loadingIndicator {
    display: none;
}

/* Preview modal */
#previewContent {
    max-height: 60vh;
    overflow-y: auto;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .sidebar {
        position: fixed;
        top: 56px;
        bottom: 0;
        left: 0;
        z-index: 1000;
        padding: 20px 0 0;
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .sidebar-sticky {
        position: relative;
        top: 0;
        height: calc(100vh - 56px);
        padding-top: .5rem;
        overflow-x: hidden;
        overflow-y: auto;
    }
    
    .sidebar-toggle {
        display: block !important;
    }
}
</style>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>
