<?php

class ContactController extends BaseAdminController {
    protected $model;
    protected $modelName = 'Contact';
    protected $viewPath = 'admin/contacts';
    protected $redirectPath = 'admin/contacts';
    
    public function __construct() {
        parent::__construct();
        $this->model = $this->model('Contact');
    }
    
    /**
     * Display all contacts
     */
    public function index() {
        $data = [
            'title' => 'Contact Messages',
            'contacts' => $this->model->getAll(),
            'unreadCount' => $this->model->getCountByStatus('unread'),
            'readCount' => $this->model->getCountByStatus('read'),
            'repliedCount' => $this->model->getCountByStatus('replied')
        ];
        $this->view('admin/contacts/index', $data);
    }
    
    /**
     * View single contact message
     */
    public function view($id) {
        $contact = $this->model->getById($id);
        
        if (!$contact) {
            flash('contact_message', 'Contact message not found', 'alert alert-danger');
            redirect('admin/contacts');
            return;
        }
        
        // Mark as read when viewed
        if ($contact->status === 'unread') {
            $this->model->updateStatus($id, 'read');
        }
        
        $data = [
            'title' => 'View Message',
            'contact' => $contact
        ];
        
        $this->view('admin/contacts/view', $data);
    }
    
    /**
     * Mark message as replied
     */
    public function markReplied($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->updateStatus($id, 'replied')) {
                flash('contact_message', 'Message marked as replied', 'alert alert-success');
            } else {
                flash('contact_message', 'Something went wrong', 'alert alert-danger');
            }
        }
        redirect('admin/contacts/view/' . $id);
    }
    
    /**
     * Delete contact message
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->delete($id)) {
                flash('contact_message', 'Message deleted successfully', 'alert alert-success');
            } else {
                flash('contact_message', 'Failed to delete message', 'alert alert-danger');
            }
            redirect('admin/contacts');
        } else {
            redirect('admin/contacts');
        }
    }
    
    /**
     * Get contacts by status
     */
    public function byStatus($status) {
        $validStatuses = ['unread', 'read', 'replied'];
        
        if (!in_array($status, $validStatuses)) {
            $status = 'unread';
        }
        
        $data = [
            'title' => ucfirst($status) . ' Messages',
            'contacts' => $this->model->getByStatus($status),
            'status' => $status,
            'unreadCount' => $this->model->getCountByStatus('unread'),
            'readCount' => $this->model->getCountByStatus('read'),
            'repliedCount' => $this->model->getCountByStatus('replied')
        ];
        
        $this->view('admin/contacts/index', $data);
    }
}
