<?php
require_once 'Model.php';

class Footer extends Model {
    protected $table = 'footer_sections';
    protected $primaryKey = 'id';
    
    // Field types and their configurations
    protected $fieldTypes = [
        'text' => [
            'input_type' => 'text',
            'validation' => 'trim|required|max_length[255]'
        ],
        'textarea' => [
            'input_type' => 'textarea',
            'validation' => 'trim|required'
        ],
        'email' => [
            'input_type' => 'email',
            'validation' => 'trim|required|valid_email|max_length[255]'
        ],
        'url' => [
            'input_type' => 'url',
            'validation' => 'trim|valid_url|max_length[500]'
        ],
        'tel' => [
            'input_type' => 'tel',
            'validation' => 'trim|max_length[50]'
        ],
        'select' => [
            'input_type' => 'select',
            'validation' => 'trim|required'
        ],
        'checkbox' => [
            'input_type' => 'checkbox',
            'validation' => ''
        ],
        'repeater' => [
            'input_type' => 'repeater',
            'validation' => 'is_array'
        ]
    ];
    
    // Section type configurations
    protected $sectionTypes = [
        'about' => [
            'name' => 'About Us',
            'icon' => 'fa-info-circle',
            'fields' => [
                'subtitle' => ['type' => 'text', 'label' => 'Subtitle', 'required' => false],
                'content' => ['type' => 'textarea', 'label' => 'Content', 'required' => true],
                'image' => ['type' => 'image', 'label' => 'Logo/Image', 'required' => false]
            ]
        ],
        'links' => [
            'name' => 'Quick Links',
            'icon' => 'fa-link',
            'fields' => [
                'links' => [
                    'type' => 'repeater',
                    'label' => 'Links',
                    'fields' => [
                        'text' => ['type' => 'text', 'label' => 'Link Text', 'required' => true],
                        'url' => ['type' => 'url', 'label' => 'URL', 'required' => true],
                        'icon' => ['type' => 'text', 'label' => 'Icon Class', 'required' => false]
                    ]
                ]
            ]
        ],
        'contact' => [
            'name' => 'Contact Info',
            'icon' => 'fa-envelope',
            'fields' => [
                'address' => ['type' => 'textarea', 'label' => 'Address', 'required' => false],
                'email' => ['type' => 'email', 'label' => 'Email', 'required' => false],
                'phone' => ['type' => 'tel', 'label' => 'Phone', 'required' => false],
                'working_hours' => ['type' => 'text', 'label' => 'Working Hours', 'required' => false]
            ]
        ],
        'social' => [
            'name' => 'Social Media',
            'icon' => 'fa-share-alt',
            'fields' => [
                'social_links' => [
                    'type' => 'repeater',
                    'label' => 'Social Links',
                    'fields' => [
                        'platform' => ['type' => 'text', 'label' => 'Platform', 'required' => true],
                        'url' => ['type' => 'url', 'label' => 'Profile URL', 'required' => true],
                        'icon' => ['type' => 'text', 'label' => 'Icon Class', 'required' => true]
                    ]
                ]
            ]
        ],
        'newsletter' => [
            'name' => 'Newsletter',
            'icon' => 'fa-newspaper',
            'fields' => [
                'subtitle' => ['type' => 'text', 'label' => 'Subtitle', 'required' => false],
                'description' => ['type' => 'textarea', 'label' => 'Description', 'required' => false],
                'form_action' => ['type' => 'url', 'label' => 'Form Action URL', 'required' => false],
                'button_text' => ['type' => 'text', 'label' => 'Button Text', 'required' => false, 'default' => 'Subscribe']
            ]
        ]
    ];
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all section types with their configurations
     */
    public function getSectionTypes() {
        return $this->sectionTypes;
    }
    
    /**
     * Get section type configuration
     */
    public function getSectionType($type) {
        return $this->sectionTypes[$type] ?? null;
    }
    
    /**
     * Get field type configuration
     */
    public function getFieldType($type) {
        return $this->fieldTypes[$type] ?? null;
    }
    
    /**
     * Get section fields with values
     */
    public function getSectionFields($sectionId) {
        $sql = "SELECT * FROM footer_section_fields 
                WHERE section_id = :section_id 
                ORDER BY sort_order ASC";
        $this->db->query($sql);
        $this->db->bind(':section_id', $sectionId);
        return $this->db->resultSet();
    }
    
    /**
     * Save section fields
     */
    public function saveSectionFields($sectionId, $data, $sectionType) {
        // First, delete existing fields
        $this->db->query("DELETE FROM footer_section_fields WHERE section_id = :section_id");
        $this->db->bind(':section_id', $sectionId);
        $this->db->execute();
        
        // Get section type configuration
        $sectionConfig = $this->getSectionType($sectionType);
        if (!$sectionConfig) return false;
        
        // Save each field
        $success = true;
        foreach ($sectionConfig['fields'] as $fieldName => $fieldConfig) {
            if ($fieldConfig['type'] === 'repeater') {
                // Handle repeater fields
                $fieldValue = isset($data[$fieldName]) && is_array($data[$fieldName]) ? 
                    json_encode($data[$fieldName]) : '[]';
                
                $sql = "INSERT INTO footer_section_fields 
                        (section_id, field_name, field_value, field_type, field_label, sort_order) 
                        VALUES 
                        (:section_id, :field_name, :field_value, :field_type, :field_label, :sort_order)";
                
                $this->db->query($sql);
                $this->db->bind(':section_id', $sectionId);
                $this->db->bind(':field_name', $fieldName);
                $this->db->bind(':field_value', $fieldValue);
                $this->db->bind(':field_type', $fieldConfig['type']);
                $this->db->bind(':field_label', $fieldConfig['label']);
                $this->db->bind(':sort_order', 0);
                
                $success = $success && $this->db->execute();
            } else {
                // Handle regular fields
                $fieldValue = $data[$fieldName] ?? '';
                
                $sql = "INSERT INTO footer_section_fields 
                        (section_id, field_name, field_value, field_type, field_label, sort_order) 
                        VALUES 
                        (:section_id, :field_name, :field_value, :field_type, :field_label, :sort_order)";
                
                $this->db->query($sql);
                $this->db->bind(':section_id', $sectionId);
                $this->db->bind(':field_name', $fieldName);
                $this->db->bind(':field_value', is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue);
                $this->db->bind(':field_type', $fieldConfig['type']);
                $this->db->bind(':field_label', $fieldConfig['label']);
                $this->db->bind(':sort_order', 0);
                
                $success = $success && $this->db->execute();
            }
        }
        
        return $success;
    }
    
    /**
     * Get section with its fields
     */
    public function getSectionWithFields($sectionId) {
        $section = $this->getSectionById($sectionId);
        if (!$section) return null;
        
        $fields = $this->getSectionFields($sectionId);
        $section->fields = [];
        
        foreach ($fields as $field) {
            if ($field->field_type === 'repeater') {
                $section->fields[$field->field_name] = json_decode($field->field_value, true) ?: [];
            } else {
                $section->fields[$field->field_name] = $field->field_value;
            }
        }
        
        return $section;
    }

    /**
     * Get all footer sections
     */
    public function getAllSections($status = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($status !== null) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY sort_order ASC";
        
        $this->db->query($sql);
        
        // Bind parameters if any
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get section by ID
     */
    public function getSectionById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Create new footer section
     */
    public function createSection($data) {
        $sql = "INSERT INTO {$this->table} 
                (title, content, type, status, sort_order, created_at, updated_at) 
                VALUES 
                (:title, :content, :type, :status, :sort_order, NOW(), NOW())";
        
        $this->db->query($sql);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':status', $data['status'] ?? 'active');
        $this->db->bind(':sort_order', $data['sort_order'] ?? 0);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    /**
     * Update footer section
     */
    public function updateSection($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = :title,
                content = :content,
                type = :type,
                status = :status,
                sort_order = :sort_order,
                updated_at = NOW()
                WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':sort_order', $data['sort_order'] ?? 0);
        
        return $this->db->execute();
    }

    /**
     * Delete footer section
     */
    public function deleteSection($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Update section orders
     */
    public function updateSectionOrders($orders) {
        $updated = 0;
        foreach ($orders as $order) {
            $sql = "UPDATE {$this->table} SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':sort_order', $order['order']);
            $this->db->bind(':id', $order['id']);
            if ($this->db->execute()) {
                $updated++;
            }
        }
        return $updated;
    }

    /**
     * Get active footer sections for frontend
     */
    public function getActiveSections() {
        return $this->getAllSections('active');
    }
    
    /**
     * Get the last error message
     */
    public function getError() {
        return $this->db->getError();
    }
}
