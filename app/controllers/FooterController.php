<?php
class FooterController {
    private $footerModel;
    protected $view;
    private $sectionTypes = [
        'about' => 'About Us',
        'links' => 'Quick Links',
        'contact' => 'Contact Info',
        'social' => 'Social Media',
        'newsletter' => 'Newsletter'
    ];
    
    private $fieldTemplates = [
        'about' => [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'content' => ['type' => 'textarea', 'label' => 'About Content', 'required' => true],
            'image' => ['type' => 'image', 'label' => 'Logo/Image', 'required' => false]
        ],
        'links' => [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'links' => [
                'type' => 'repeater',
                'label' => 'Links',
                'fields' => [
                    'text' => ['type' => 'text', 'label' => 'Link Text', 'required' => true],
                    'url' => ['type' => 'url', 'label' => 'Link URL', 'required' => true],
                    'icon' => ['type' => 'text', 'label' => 'Icon Class (e.g., fas fa-home)', 'required' => false]
                ]
            ]
        ],
        'contact' => [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'address' => ['type' => 'textarea', 'label' => 'Address', 'required' => false],
            'phone' => ['type' => 'tel', 'label' => 'Phone Number', 'required' => false],
            'email' => ['type' => 'email', 'label' => 'Email', 'required' => false],
            'working_hours' => ['type' => 'text', 'label' => 'Working Hours', 'required' => false]
        ],
        'social' => [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'social_links' => [
                'type' => 'repeater',
                'label' => 'Social Media Links',
                'fields' => [
                    'platform' => ['type' => 'text', 'label' => 'Platform (e.g., Facebook)', 'required' => true],
                    'url' => ['type' => 'url', 'label' => 'Profile URL', 'required' => true],
                    'icon' => ['type' => 'text', 'label' => 'Icon Class (e.g., fab fa-facebook)', 'required' => true]
                ]
            ]
        ],
        'newsletter' => [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'description' => ['type' => 'textarea', 'label' => 'Description', 'required' => false],
            'form_action' => ['type' => 'url', 'label' => 'Form Action URL', 'required' => false],
            'button_text' => ['type' => 'text', 'label' => 'Button Text', 'required' => false, 'default' => 'Subscribe']
        ]
    ];

    /**
     * Handle AJAX request to update section orders
     */
    public function updateOrder() {
        header('Content-Type: application/json');
        
        // Check if this is an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Direct access not allowed']);
            exit;
        }
        
        // Check if required data is provided
        if (!isset($_POST['orders']) || !is_array($_POST['orders'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request data']);
            exit;
        }
        
        try {
            // Prepare orders data
            $orders = [];
            foreach ($_POST['orders'] as $index => $sectionId) {
                $orders[] = [
                    'id' => (int)$sectionId,
                    'order' => $index + 1
                ];
            }
            
            // Update orders in database
            $updated = $this->footerModel->updateSectionOrders($orders);
            
            if ($updated > 0) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Section order updated successfully',
                    'updated' => $updated
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Failed to update section order'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Toggle section status (active/inactive)
     */
    public function toggleStatus() {
        header('Content-Type: application/json');
        
        // Check if this is an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Direct access not allowed']);
            exit;
        }
        
        // Check if required data is provided
        if (!isset($_POST['id']) || !isset($_POST['status'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request data']);
            exit;
        }
        
        try {
            $id = (int)$_POST['id'];
            $status = (int)$_POST['status'] ? 'active' : 'inactive';
            
            // Update status in database
            $sql = "UPDATE footer_sections SET status = :status, updated_at = NOW() WHERE id = :id";
            $this->footerModel->db->query($sql);
            $this->footerModel->db->bind(':status', $status);
            $this->footerModel->db->bind(':id', $id);
            
            $result = $this->footerModel->db->execute();
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Status updated successfully',
                    'newStatus' => $status
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Failed to update status'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    
    public function __construct() {
        // Initialize the model
        require_once APP_PATH . 'models/Footer.php';
        $this->footerModel = new Footer();
        $this->view = function($view, $data = []) {
            extract($data);
            require_once APP_PATH . 'views/' . $view . '.php';
        };
        
        // Get section types from model
        $this->sectionTypes = $this->footerModel->getSectionTypes();
        
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                // Return JSON response for AJAX requests
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized access']);
                exit;
            } else {
                // Redirect for regular requests
                header('Location: ' . BASE_URL . '?controller=user&action=login');
                exit;
            }
        }
    }
    
    /**
     * Display the footer management page
     */
    public function manage() {
        try {
            // Get all footer sections with their fields
            $sections = [];
            $allSections = $this->footerModel->getAllSections();
            
            foreach ($allSections as $section) {
                $sectionWithFields = $this->footerModel->getSectionWithFields($section['id']);
                if ($sectionWithFields) {
                    $sections[] = $sectionWithFields;
                }
            }
            
            // Get section types for the add section dropdown
            $sectionTypes = $this->footerModel->getSectionTypes();
            
            // Render the view
            $view = $this->view;
            $view('admin/footer_management', [
                'sections' => $sections,
                'sectionTypes' => $sectionTypes
            ]);
            if ($sectionId) {
                foreach ($sections as $section) {
                    if ($section['id'] == $sectionId) {
                        $currentSection = $section;
                        break;
                    }
                }
            }
            
            // Load the management view
            require_once APP_PATH . 'views/admin/layouts/header.php';
            $view = $this->view;
            $view('admin/footer/management', [
                'title' => 'Footer Management',
                'sections' => $sections,
                'currentSection' => $currentSection,
                'sectionTypes' => $this->sectionTypes
            ]);
            require_once APP_PATH . 'views/admin/layouts/footer.php';
            
        } catch (Exception $e) {
            error_log('Error in FooterController::manage: ' . $e->getMessage());
            
            // Set error message
            $_SESSION['error'] = 'Failed to load footer management. Please try again.';
            
            // Redirect to admin dashboard
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }
    }
    
    /**
     * Display footer management page in admin dashboard
     */
    public function manageFooter() {
    public function manage() {
        try {
            $sections = $this->footerModel->getAllSections();
            
            // Check if it's an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // Return just the content for AJAX
                $view = $this->view;
                $view('admin/footer/manage_ajax', [
                    'sections' => $sections,
                    'sectionTypes' => $this->sectionTypes
                ]);
            } else {
                // Full page load
                require_once APP_PATH . 'views/admin/layouts/header.php';
                $view = $this->view;
                $view('admin/footer/manage', [
                    'title' => 'Manage Footer Sections',
                    'sections' => $sections,
                    'sectionTypes' => $this->sectionTypes
                ]);
                require_once APP_PATH . 'views/admin/layouts/footer.php';
            }
        } catch (Exception $e) {
            error_log('Error in FooterController::manage: ' . $e->getMessage());
            
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to load footer sections.']);
                exit;
            } else {
                $_SESSION['error'] = 'Failed to load footer sections. Please try again.';
                header('Location: ' . BASE_URL . 'admin/dashboard');
                exit;
            }
        }
    }
    
    /**
     * Get section fields via AJAX
     */
    public function getSectionFields() {
        $type = $_GET['type'] ?? '';
        
        // Return 404 if type is not provided
        if (empty($type)) {
            http_response_code(404);
            echo 'Section type not specified';
            exit;
        }
        
        // Create a temporary section data array
        $section = [
            'type' => $type,
            'content' => []
        ];
        
        // Output the section fields
        ob_start();
        $this->loadSectionFields($type, $section);
        $output = ob_get_clean();
        
        // Return the output
        echo $output;
        exit;
    }
    
    /**
     * Load section fields based on type
     */
    protected function loadSectionFields($type, $section = []) {
        $content = is_array($section['content'] ?? '') ? $section['content'] : [];
        
        switch ($type) {
            case 'about':
                ?>
                <div class="mb-3">
                    <label for="content" class="form-label">About Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" rows="5" required><?php 
                        echo htmlspecialchars($content['content'] ?? $section['content'] ?? ''); 
                    ?></textarea>
                </div>
                <?php
                break;
                
            case 'links':
                $links = is_array($content) ? $content : [];
                if (empty($links)) {
                    $links = [['text' => '', 'url' => '', 'icon' => '']];
                }
                ?>
                <div class="mb-3">
                    <label class="form-label">Quick Links</label>
                    <div id="links-container">
                        <?php foreach ($links as $index => $link): ?>
                            <div class="link-item mb-3 border p-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Link Text <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="links[<?php echo $index; ?>][text]" 
                                               value="<?php echo htmlspecialchars($link['text'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="links[<?php echo $index; ?>][url]" 
                                               value="<?php echo htmlspecialchars($link['url'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" class="form-control" name="links[<?php echo $index; ?>][icon]" 
                                               value="<?php echo htmlspecialchars($link['icon'] ?? ''); ?>" 
                                               placeholder="fas fa-link">
                                    </div>
                                </div>
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-link">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-success mt-2" id="add-link">
                        <i class="fas fa-plus"></i> Add Link
                    </button>
                </div>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const container = document.getElementById('links-container');
                    let linkCount = <?php echo count($links); ?>;
                    
                    // Add new link
                    document.getElementById('add-link').addEventListener('click', function() {
                        const newIndex = linkCount++;
                        const newLink = `
                            <div class="link-item mb-3 border p-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Link Text <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="links[${newIndex}][text]" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="links[${newIndex}][url]" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" class="form-control" name="links[${newIndex}][icon]" 
                                               placeholder="fas fa-link">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-link">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', newLink);
                    });
                    
                    // Remove link
                    container.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-link')) {
                            e.target.closest('.link-item').remove();
                        }
                    });
                });
                </script>
                <?php
                break;
                
            case 'contact':
                ?>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="content[address]" rows="3"><?php 
                        echo htmlspecialchars($content['address'] ?? ''); 
                    ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="content[phone]" 
                                   value="<?php echo htmlspecialchars($content['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="content[email]" 
                                   value="<?php echo htmlspecialchars($content['email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="working_hours" class="form-label">Working Hours</label>
                    <input type="text" class="form-control" id="working_hours" name="content[working_hours]" 
                           value="<?php echo htmlspecialchars($content['working_hours'] ?? ''); ?>" 
                           placeholder="e.g., Mon-Fri: 9:00 AM - 6:00 PM">
                </div>
                <?php
                break;
                
            case 'social':
                $socialLinks = is_array($content) ? $content : [];
                if (empty($socialLinks)) {
                    $socialLinks = [['platform' => '', 'url' => '', 'icon' => '']];
                }
                ?>
                <div class="mb-3">
                    <label class="form-label">Social Media Links</label>
                    <div id="social-links-container">
                        <?php foreach ($socialLinks as $index => $social): ?>
                            <div class="social-item mb-3 border p-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Platform <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="social_links[<?php echo $index; ?>][platform]" 
                                               value="<?php echo htmlspecialchars($social['platform'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Profile URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="social_links[<?php echo $index; ?>][url]" 
                                               value="<?php echo htmlspecialchars($social['url'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Icon Class <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="social_links[<?php echo $index; ?>][icon]" 
                                               value="<?php echo htmlspecialchars($social['icon'] ?? ''); ?>" 
                                               placeholder="fab fa-facebook" required>
                                    </div>
                                </div>
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-social">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-success mt-2" id="add-social">
                        <i class="fas fa-plus"></i> Add Social Link
                    </button>
                </div>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const container = document.getElementById('social-links-container');
                    let socialCount = <?php echo count($socialLinks); ?>;
                    
                    // Add new social link
                    document.getElementById('add-social').addEventListener('click', function() {
                        const newIndex = socialCount++;
                        const newSocial = `
                            <div class="social-item mb-3 border p-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Platform <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="social_links[${newIndex}][platform]" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Profile URL <span class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="social_links[${newIndex}][url]" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Icon Class <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="social_links[${newIndex}][icon]" 
                                               placeholder="fab fa-facebook" required>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-social">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', newSocial);
                    });
                    
                    // Remove social link
                    container.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-social')) {
                            e.target.closest('.social-item').remove();
                        }
                    });
                });
                </script>
                <?php
                break;
                
            case 'newsletter':
                ?>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="content[description]" rows="3"><?php 
                        echo htmlspecialchars($content['description'] ?? 'Subscribe to our newsletter for updates.'); 
                    ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="form_action" class="form-label">Form Action URL</label>
                    <input type="url" class="form-control" id="form_action" name="content[form_action]" 
                           value="<?php echo htmlspecialchars($content['form_action'] ?? '#'); ?>">
                </div>
                <div class="mb-3">
                    <label for="button_text" class="form-label">Button Text</label>
                    <input type="text" class="form-control" id="button_text" name="content[button_text]" 
                           value="<?php echo htmlspecialchars($content['button_text'] ?? 'Subscribe'); ?>">
                </div>
                <?php
                break;
                
            default:
                // Default textarea for custom content
                ?>
                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" rows="5" required><?php 
                        echo htmlspecialchars(is_array($content) ? json_encode($content, JSON_PRETTY_PRINT) : ($section['content'] ?? '')); 
                    ?></textarea>
                </div>
                <?php
                break;
        }
    }
    
    /**
     * Display footer management page in admin dashboard
     */
    public function manage() {
        // Check if user is logged in as admin
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit();
        }
        
        // Get all footer sections
        $sections = $this->footerModel->getAllSections();
        
        // Include admin header
        require_once APPROOT . '/views/admin/layouts/header.php';
        
        // Load the management view
        require_once APPROOT . '/views/admin/footer_management.php';
        
        // Include admin footer
        require_once APPROOT . '/views/admin/layouts/footer.php';
    }
    
    /**
     * Display form to add new footer section
     */
    public function add() {
        // Check if user is logged in as admin
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit();
        }
        
        // Include admin header
        require_once APPROOT . '/views/admin/layouts/header.php';
        
        // Set default values
        $data = [
            'title' => 'Add New Footer Section',
            'section' => [
                'title' => '',
                'type' => 'links',
                'content' => '',
                'status' => 'active',
                'sort_order' => 0
            ],
            'sectionTypes' => $this->sectionTypes,
            'action' => BASE_URL . 'admin/footer/store',
            'formTitle' => 'Add New Footer Section'
        ];
        
        // Save to database
        if ($this->footerModel->createSection($data)) {
            $_SESSION['success'] = 'Footer section created successfully!';
            header('Location: ' . BASE_URL . 'admin/footer');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to create footer section. Please try again.';
            // Redirect back with form data
            header('Location: ' . BASE_URL . 'admin/footer/add');
            $sortOrder = intval($_POST['sort_order'] ?? 0);
            
            // Initialize content based on section type
            $content = [];
            
            // Process content based on section type
            switch ($type) {
                case 'links':
                    $links = $_POST['links'] ?? [];
                    foreach ($links as $link) {
                        if (!empty($link['text']) && !empty($link['url'])) {
                            $content[] = [
                                'text' => $link['text'],
                                'url' => $link['url'],
                                'icon' => $link['icon'] ?? ''
                            ];
                        }
                    }
                    break;
                    
                case 'social':
                    $socialLinks = $_POST['social_links'] ?? [];
                    foreach ($socialLinks as $social) {
                        if (!empty($social['platform']) && !empty($social['url'])) {
                            $content[] = [
                                'platform' => $social['platform'],
                                'url' => $social['url'],
                                'icon' => $social['icon']
                            ];
                        }
                    }
                    break;
                    
                default:
                    // For simple text content
                    $content = $_POST['content'] ?? '';
                    break;
            }
            
            // Convert content to JSON for storage
            $contentJson = is_array($content) ? json_encode($content, JSON_UNESCAPED_UNICODE) : $content;
            
            // Prepare data for database
            $data = [
                'title' => $title,
                'type' => $type,
                'content' => $contentJson,
                'status' => $status,
                'sort_order' => $sortOrder,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Save to database
            if ($this->footerModel->createSection($data)) {
                $_SESSION['success'] = 'Footer section created successfully!';
                header('Location: ' . BASE_URL . 'admin/footer');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to create footer section. Please try again.';
                // Redirect back with form data
                header('Location: ' . BASE_URL . 'admin/footer/add');
                exit();
            }
        }
        
        // If not a POST request, redirect to add page
        header('Location: ' . BASE_URL . 'admin/footer/add');
        exit();
    }
    
    /**
     * Display footer management page
     */
    public function management() {
        $sections = $this->footerModel->getAllSections();
        $data = [
            'title' => 'Footer Management',
            'sections' => $sections,
            'sectionTypes' => $this->sectionTypes
        ];
        
        // Load the management view
        require_once APP_PATH . 'views/admin/footer_management.php';
    }

    /**
     * List all footer sections
     */
    public function index() {
        $sections = $this->footerModel->getAllSections();
        $data = [
            'title' => 'Footer Sections Management',
            'sections' => $sections,
            'sectionTypes' => $this->sectionTypes
        ];
        call_user_func($this->view, 'admin/footer/index', $data);
    }
    


    /**
     * Show form to create new footer section
     */
    public function create() {
        $sectionType = $_GET['type'] ?? 'links';
        
        $data = [
            'title' => 'Add New ' . ($this->sectionTypes[$sectionType] ?? 'Footer Section'),
            'section' => [
                'title' => '',
                'type' => $sectionType,
                'status' => 'active',
                'sort_order' => 0,
                'data' => [] // Will hold the dynamic fields data
            ],
            'sectionTypes' => $this->sectionTypes,
            'fieldTemplate' => $this->fieldTemplates[$sectionType] ?? [],
            'errors' => []
        ];
        
        // Set default values for fields
        if (isset($this->fieldTemplates[$sectionType])) {
            foreach ($this->fieldTemplates[$sectionType] as $field => $config) {
                if (isset($config['default'])) {
                    $data['section']['data'][$field] = $config['default'];
                } else if ($config['type'] === 'repeater') {
                    $data['section']['data'][$field] = [];
                } else {
                    $data['section']['data'][$field] = '';
                }
            }
        }
        
        call_user_func($this->view, 'admin/footer/form', $data);
    }

    /**
     * Store new footer section
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'type' => $_POST['type'] ?? 'links',
                'content' => trim($_POST['content'] ?? ''),
                'status' => $_POST['status'] ?? 'active',
                'sort_order' => intval($_POST['sort_order'] ?? 0)
            ];

            // Process content based on type
            if ($data['type'] === 'links' || $data['type'] === 'social') {
                $lines = explode("\n", $data['content']);
                $links = [];
                foreach ($lines as $line) {
                    $parts = explode('|', trim($line), 2);
                    if (count($parts) === 2) {
                        $links[] = [
                            'text' => trim($parts[0]),
                            'url' => trim($parts[1])
                        ];
                    }
                }
                $data['content'] = json_encode($links);
            }

            // Save to database
            if ($this->footerModel->createSection($data)) {
                $_SESSION['success'] = 'Footer section added successfully';
                header('Location: ' . BASE_URL . 'admin/footer');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to add footer section';
                $_SESSION['form_data'] = $_POST; // Save form data to repopulate the form
                header('Location: ' . BASE_URL . 'admin/footer/add');
                exit;
            }
        }
        
        // If not POST, redirect to add page
        header('Location: ' . BASE_URL . 'admin/footer/add');
        exit;
    }

    /**
     * Show form to edit footer section
     */
    public function edit($id) {
        // Check if user is logged in as admin
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit();
        }
        
        $section = $this->footerModel->getSectionById($id);
        
        if (!$section) {
            $_SESSION['error'] = 'Footer section not found';
            header('Location: ' . BASE_URL . 'admin/footer');
            exit();
        }
        
        // Include admin header
        require_once APPROOT . '/views/admin/layouts/header.php';
        
        // Prepare data for the form
        $data = [
            'title' => 'Edit Footer Section',
            'section' => $section,
            'sectionTypes' => $this->sectionTypes,
            'action' => BASE_URL . 'admin/footer/update/' . $id,
            'formTitle' => 'Edit Footer Section'
        ];
        
        // Load the form view
        require_once APPROOT . '/views/admin/footer/form.php';
        
        // Include admin footer
        require_once APPROOT . '/views/admin/layouts/footer.php';
    }

    /**
     * Update footer section
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'type' => $_POST['type'] ?? 'links',
                'status' => $_POST['status'] ?? 'active',
                'sort_order' => intval($_POST['sort_order'] ?? 0)
            ];

            // Validate input
            $errors = $this->validateSection($data);

            if (empty($errors)) {
                // Update in database
                if ($this->footerModel->updateSection($id, $data)) {
                    $_SESSION['success'] = 'Footer section updated successfully';
                    header('Location: ' . BASE_URL . 'admin/footer/manage');
                    exit;
                } else {
                    $errors[] = 'Failed to update footer section';
                }
            }

            // If we got here, there were errors
            $data['title'] = 'Edit Footer Section';
            $data['section'] = array_merge(['id' => $id], $data);
            $data['errors'] = $errors;
            $data['sectionTypes'] = $this->sectionTypes;
            
            // Load admin layout header
            require_once APP_PATH . 'views/admin/layouts/header.php';
            
            // Load the view
            $view = $this->view;
            $view('admin/footer/form', $data);
            
            // Load admin layout footer
            require_once APP_PATH . 'views/admin/layouts/footer.php';
        } else {
            header('Location: ' . BASE_URL . 'admin/footer/manage');
            exit;
        }
    }
    
    /**
     * Update section status via AJAX
     */
    public function updateStatus($id) {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['status'])) {
                throw new Exception('Status is required');
            }
            
            $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'inactive';
            
            // Update the status in the database
            $sql = "UPDATE footer_sections SET status = :status, updated_at = NOW() WHERE id = :id";
            $this->footerModel->db->query($sql);
            $this->footerModel->db->bind(':status', $status);
            $this->footerModel->db->bind(':id', $id);
            
            if ($this->footerModel->db->execute()) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Status updated successfully',
                    'status' => $status
                ]);
            } else {
                throw new Exception('Failed to update status');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    /**
     * Update section order via AJAX
     */
    public function updateOrder() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['order']) || !is_array($_POST['order'])) {
                throw new Exception('Invalid order data');
            }
            
            $this->footerModel->db->beginTransaction();
            
            try {
                foreach ($_POST['order'] as $item) {
                    if (!isset($item['id']) || !is_numeric($item['sort_order'])) {
                        throw new Exception('Invalid order data format');
                    }
                    
                    $sql = "UPDATE footer_sections SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id";
                    $this->footerModel->db->query($sql);
                    $this->footerModel->db->bind(':sort_order', (int)$item['sort_order']);
                    $this->footerModel->db->bind(':id', $item['id']);
                    
                    if (!$this->footerModel->db->execute()) {
                        throw new Exception('Failed to update sort order');
                    }
                }
                
                $this->footerModel->db->commit();
                echo json_encode([
                    'success' => true, 
                    'message' => 'Order updated successfully'
                ]);
                
            } catch (Exception $e) {
                $this->footerModel->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
    exit;
}

/**
 * Update section order via AJAX
 */
public function updateOrder() {
    header('Content-Type: application/json');
    
    try {
        if (!isset($_POST['order']) || !is_array($_POST['order'])) {
            throw new Exception('Invalid order data');
        }
        
        $this->footerModel->db->beginTransaction();
        
        try {
            foreach ($_POST['order'] as $item) {
                if (!isset($item['id']) || !is_numeric($item['sort_order'])) {
                    throw new Exception('Invalid order data format');
                }
                
                $sql = "UPDATE footer_sections SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id";
                $this->footerModel->db->query($sql);
                $this->footerModel->db->bind(':sort_order', (int)$item['sort_order']);
                $this->footerModel->db->bind(':id', $item['id']);
                
                if (!$this->footerModel->db->execute()) {
                    throw new Exception('Failed to update sort order');
                }
            }
            
            $this->footerModel->db->commit();
            echo json_encode([
                'success' => true, 
                'message' => 'Order updated successfully'
            ]);
            
        } catch (Exception $e) {
            $this->footerModel->db->rollBack();
            throw $e;
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

/**
 * Delete footer section
 */
public function delete($id) {
    try {
        if ($this->footerModel->deleteSection($id)) {
            $_SESSION['success'] = 'Footer section deleted successfully';
        } else {
            throw new Exception('Failed to delete footer section');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    header('Location: ' . BASE_URL . 'admin/footer/manage');
    exit;
        if (empty($data['type']) || !array_key_exists($data['type'], $this->sectionTypes)) {
            $errors[] = 'Invalid section type';
        }
        
        if (empty($data['content'])) {
            $errors[] = 'Content is required';
        }
        
        return $errors;
    }
}
