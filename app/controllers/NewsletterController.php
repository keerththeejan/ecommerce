<?php
/**
 * Newsletter Controller
 * Handles newsletter subscriptions
 */
class NewsletterController extends Controller {
    private $newsletterModel;
    
    public function __construct() {
        $this->newsletterModel = $this->model('Newsletter');
    }
    
    /**
     * Subscribe to newsletter
     */
    public function subscribe() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'email' => sanitize($this->post('email'))
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'email' => 'required|email'
            ]);
            
            // Make sure there are no errors
            if(empty($errors)) {
                try {
                    // Check if email already exists
                    if($this->newsletterModel->emailExists($data['email'])) {
                        flash('newsletter_error', 'This email is already subscribed', 'alert alert-warning');
                        redirect('home');
                        return;
                    }
                    
                    // Add subscriber
                    if($this->newsletterModel->addSubscriber($data)) {
                        flash('newsletter_success', 'Thank you for subscribing to our newsletter!');
                        redirect('home');
                    } else {
                        flash('newsletter_error', 'Failed to subscribe. Please try again.', 'alert alert-danger');
                        redirect('home');
                    }
                } catch (Exception $e) {
                    // Log error
                    error_log('Error in NewsletterController::subscribe: ' . $e->getMessage());
                    
                    flash('newsletter_error', 'An error occurred. Please try again later.', 'alert alert-danger');
                    redirect('home');
                }
            } else {
                // Set flash message with error
                flash('newsletter_error', 'Please enter a valid email address', 'alert alert-danger');
                redirect('home');
            }
        } else {
            // Redirect to home page if not POST
            redirect('home');
        }
    }
}
