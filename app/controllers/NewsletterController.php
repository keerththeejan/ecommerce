<?php
/**
 * Newsletter Controller
 * Handles newsletter subscriptions
 */
class NewsletterController extends Controller {
    private $newsletterModel;
    private $settingModel;
    
    public function __construct() {
        $this->newsletterModel = $this->model('Newsletter');
        // Load Setting model for managing newsletter widget text
        $this->settingModel = $this->model('Setting');
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

    /**
     * Admin: List subscribers and show create form
     */
    public function adminIndex() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }

        $subscribers = $this->newsletterModel->getAllSubscribers(false);
        // Load current newsletter widget settings
        $newsletterTitle = $this->settingModel->getSetting('newsletter_title', 'Newsletter');
        $newsletterDesc = $this->settingModel->getSetting('newsletter_description', 'Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.');
        $data = [
            'title' => 'Newsletter Subscribers',
            'subscribers' => $subscribers,
            'errors' => [],
            'newsletter_title' => $newsletterTitle,
            'newsletter_description' => $newsletterDesc,
        ];
        $this->view('admin/newsletter/index', $data);
    }

    /**
     * Admin: Store a new subscriber
     */
    public function adminStore() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        if ($this->isPost()) {
            $email = sanitize($this->post('email'));
            $errors = $this->validate(['email' => $email], [
                'email' => 'required|email'
            ]);
            if (!empty($errors)) {
                flash('newsletter_error', 'Please enter a valid email.', 'alert alert-danger');
                redirect('?controller=newsletter&action=adminIndex');
                return;
            }
            if ($this->newsletterModel->emailExists($email)) {
                flash('newsletter_error', 'Email already subscribed.', 'alert alert-warning');
                redirect('?controller=newsletter&action=adminIndex');
                return;
            }
            if ($this->newsletterModel->addSubscriber(['email' => $email])) {
                flash('newsletter_success', 'Subscriber added successfully.');
            } else {
                flash('newsletter_error', 'Failed to add subscriber.', 'alert alert-danger');
            }
            redirect('?controller=newsletter&action=adminIndex');
        } else {
            redirect('?controller=newsletter&action=adminIndex');
        }
    }

    /**
     * Admin: Delete a subscriber by ID
     */
    public function adminDelete() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        if ($this->isPost()) {
            $id = (int)($this->post('id') ?? 0);
            if ($id <= 0) {
                flash('newsletter_error', 'Invalid subscriber ID.', 'alert alert-danger');
                redirect('?controller=newsletter&action=adminIndex');
                return;
            }
            if ($this->newsletterModel->deleteById($id)) {
                flash('newsletter_success', 'Subscriber deleted.');
            } else {
                flash('newsletter_error', 'Failed to delete subscriber.', 'alert alert-danger');
            }
            redirect('?controller=newsletter&action=adminIndex');
        } else {
            redirect('?controller=newsletter&action=adminIndex');
        }
    }

    /**
     * Admin: Export subscribers CSV
     */
    public function adminExport() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        $subs = $this->newsletterModel->getAllSubscribers(false);
        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=newsletter_subscribers.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Email', 'Active', 'Created At', 'Updated At']);
        foreach ($subs as $s) {
            // $s may be array or object depending on DB wrapper
            $id = is_array($s) ? ($s['id'] ?? '') : ($s->id ?? '');
            $email = is_array($s) ? ($s['email'] ?? '') : ($s->email ?? '');
            $active = is_array($s) ? ($s['active'] ?? '') : ($s->active ?? '');
            $created = is_array($s) ? ($s['created_at'] ?? '') : ($s->created_at ?? '');
            $updated = is_array($s) ? ($s['updated_at'] ?? '') : ($s->updated_at ?? '');
            fputcsv($output, [$id, $email, $active, $created, $updated]);
        }
        fclose($output);
        exit;
    }

    /**
     * Admin: Edit subscriber form
     */
    public function adminEdit() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('newsletter_error', 'Invalid subscriber ID.', 'alert alert-danger');
            redirect('?controller=newsletter&action=adminIndex');
            return;
        }
        $item = $this->newsletterModel->getById($id);
        if (!$item) {
            flash('newsletter_error', 'Subscriber not found.', 'alert alert-danger');
            redirect('?controller=newsletter&action=adminIndex');
            return;
        }
        $data = [
            'title' => 'Edit Subscriber',
            'item' => $item,
        ];
        $this->view('admin/newsletter/edit', $data);
    }

    /**
     * Admin: Update subscriber
     */
    public function adminUpdate() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        if ($this->isPost()) {
            $id = (int)($this->post('id') ?? 0);
            $email = sanitize($this->post('email'));
            $active = (int)($this->post('active') ?? 0);
            if ($id <= 0) {
                flash('newsletter_error', 'Invalid subscriber ID.', 'alert alert-danger');
                redirect('?controller=newsletter&action=adminIndex');
                return;
            }
            $errors = $this->validate(['email' => $email], [
                'email' => 'required|email'
            ]);
            if (!empty($errors)) {
                flash('newsletter_error', 'Please enter a valid email.', 'alert alert-danger');
                redirect('?controller=newsletter&action=adminEdit&id=' . $id);
                return;
            }
            // Ensure email is unique for other IDs
            $existing = $this->newsletterModel->getByEmail($email);
            if ($existing && (int)($existing['id'] ?? $existing->id ?? 0) !== $id) {
                flash('newsletter_error', 'Email already used by another subscriber.', 'alert alert-warning');
                redirect('?controller=newsletter&action=adminEdit&id=' . $id);
                return;
            }
            if ($this->newsletterModel->updateById($id, ['email' => $email, 'active' => $active])) {
                flash('newsletter_success', 'Subscriber updated successfully.');
            } else {
                flash('newsletter_error', 'Failed to update subscriber.', 'alert alert-danger');
            }
            redirect('?controller=newsletter&action=adminIndex');
        } else {
            redirect('?controller=newsletter&action=adminIndex');
        }
    }

    /**
     * Admin: Save newsletter widget settings (title, description)
     */
    public function adminSaveSettings() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }
        if ($this->isPost()) {
            $title = trim((string)$this->post('newsletter_title'));
            $desc  = trim((string)$this->post('newsletter_description'));
            // Basic validation
            if ($title === '' || $desc === '') {
                flash('newsletter_error', 'Title and Description are required.', 'alert alert-danger');
                redirect('?controller=newsletter&action=adminIndex');
                return;
            }
            $ok1 = $this->settingModel->updateSetting('newsletter_title', $title);
            $ok2 = $this->settingModel->updateSetting('newsletter_description', $desc);
            if ($ok1 && $ok2) {
                flash('newsletter_success', 'Newsletter settings updated successfully.');
            } else {
                flash('newsletter_error', 'Failed to update settings.', 'alert alert-danger');
            }
            redirect('?controller=newsletter&action=adminIndex');
        } else {
            redirect('?controller=newsletter&action=adminIndex');
        }
    }
}
