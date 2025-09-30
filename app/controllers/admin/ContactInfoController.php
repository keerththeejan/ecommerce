<?php

class ContactInfoController extends BaseAdminController {
    protected $model;

    public function __construct() {
        parent::__construct();
        $this->model = $this->model('ContactInfo');
    }

    public function index() {
        try {
            $items = $this->model->getAll();
        } catch (\PDOException $e) {
            // 42S02: Base table or view not found
            if ($e->getCode() === '42S02') {
                $this->model->ensureTable();
                if (function_exists('flash')) {
                    flash('contact_info_message', 'Initialized contact_info table. Please try again.', 'alert alert-info');
                }
                return redirect('admin/contact-info');
            }
            throw $e;
        }
        $data = [
            'title' => 'Contact Info',
            'items' => $items
        ];
        $this->view('admin/contact_info/index', $data);
    }

    public function create() {
        $data = [
            'title' => 'Add Contact Info',
            'item' => (object) [
                'address' => '',
                'phone' => '',
                'email' => '',
                'hours_weekdays' => '',
                'hours_weekends' => '',
                'map_embed' => ''
            ],
            'errors' => []
        ];
        $this->view('admin/contact_info/form', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('admin/contact-info');
        }
        $payload = [
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'hours_weekdays' => trim($_POST['hours_weekdays'] ?? ''),
            'hours_weekends' => trim($_POST['hours_weekends'] ?? ''),
            'map_embed' => trim($_POST['map_embed'] ?? '')
        ];

        $errors = $this->validate($payload);
        if (!empty($errors)) {
            $data = [
                'title' => 'Add Contact Info',
                'item' => (object)$payload,
                'errors' => $errors
            ];
            return $this->view('admin/contact_info/form', $data);
        }

        if ($this->model->create($payload)) {
            flash('contact_info_message', 'Contact info created', 'alert alert-success');
            return redirect('admin/contact-info');
        }
        flash('contact_info_message', 'Failed to create contact info', 'alert alert-danger');
        return redirect('admin/contact-info');
    }

    public function edit($id) {
        $item = $this->model->getById($id);
        if (!$item) {
            flash('contact_info_message', 'Item not found', 'alert alert-danger');
            return redirect('admin/contact-info');
        }
        $data = [
            'title' => 'Edit Contact Info',
            'item' => $item,
            'errors' => []
        ];
        $this->view('admin/contact_info/form', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('admin/contact-info');
        }
        $payload = [
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'hours_weekdays' => trim($_POST['hours_weekdays'] ?? ''),
            'hours_weekends' => trim($_POST['hours_weekends'] ?? ''),
            'map_embed' => trim($_POST['map_embed'] ?? '')
        ];

        $errors = $this->validate($payload);
        if (!empty($errors)) {
            $data = [
                'title' => 'Edit Contact Info',
                'item' => (object)array_merge($payload, ['id' => $id]),
                'errors' => $errors
            ];
            return $this->view('admin/contact_info/form', $data);
        }

        if ($this->model->update($id, $payload)) {
            flash('contact_info_message', 'Contact info updated', 'alert alert-success');
            return redirect('admin/contact-info');
        }
        flash('contact_info_message', 'No changes made or update failed', 'alert alert-warning');
        return redirect('admin/contact-info');
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->delete($id)) {
                flash('contact_info_message', 'Item deleted', 'alert alert-success');
            } else {
                flash('contact_info_message', 'Delete failed', 'alert alert-danger');
            }
        }
        return redirect('admin/contact-info');
    }

    public function validate($data, $rules = null) {
        // If rules are provided, use base implementation for compatibility
        if ($rules !== null) {
            return parent::validate($data, $rules);
        }
        // ContactInfo-specific validation
        $errors = [];
        if (($data['address'] ?? '') === '') $errors['address'] = 'Address is required';
        if (($data['phone'] ?? '') === '') $errors['phone'] = 'Phone is required';
        $email = $data['email'] ?? '';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
        if (($data['hours_weekdays'] ?? '') === '') $errors['hours_weekdays'] = 'Weekday hours required';
        if (($data['hours_weekends'] ?? '') === '') $errors['hours_weekends'] = 'Weekend hours required';
        return $errors;
    }
}
