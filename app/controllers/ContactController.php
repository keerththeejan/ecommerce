<?php

class ContactController extends Controller {
    private $contactModel;
    
    public function __construct() {
        parent::__construct();
        $this->contactModel = $this->model('Contact');
    }
    
    /**
     * Display contact form
     */
    public function index() {
        $data = [
            'title' => 'Contact Us',
            'name' => '',
            'email' => '',
            'phone' => '',
            'subject' => '',
            'message' => '',
            'name_err' => '',
            'email_err' => '',
            'subject_err' => '',
            'message_err' => ''
        ];
        
        $this->view('contact/index', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => 'Contact Us',
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone'] ?? ''),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            // Validate subject
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }
            
            // Validate message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your message';
            } elseif (strlen($data['message']) < 10) {
                $data['message_err'] = 'Message must be at least 10 characters long';
            }
            
            // If no errors, process the form
            if (empty($data['name_err']) && empty($data['email_err']) && 
                empty($data['subject_err']) && empty($data['message_err'])) {
                
                if ($this->contactModel->create($data)) {
                    flash('contact_message', 'Thank you for your message. We will get back to you soon!', 'alert alert-success');
                    redirect('contact');
                } else {
                    die('Something went wrong');
                }
                
            } else {
                // Reload view with errors
                $this->view('contact/index', $data);
            }
            
        } else {
            // If not a POST request, redirect to contact page
            redirect('contact');
        }
    }
}
