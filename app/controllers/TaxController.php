<?php
class TaxController extends Controller {
    private $taxModel;

    public function __construct() {
        // Check if user is logged in and is admin
        if(!isLoggedIn() || $_SESSION['user_role'] != 'admin') {
            redirect('users/login');
        }

        $this->taxModel = $this->model('TaxModel');
        
        // Create tax table if not exists
        $this->taxModel->createTable();
        
        // Ensure we have a tax rates record
        $this->taxModel->ensureTaxRatesExist();
    }

    // Show tax rates list
    public function index() {
        // Get all active tax rates
        $data = [
            'taxRates' => $this->taxModel->getTaxRates()
        ];

        $this->view('admin/tax/index', $data);
    }

    // Show add tax rate form
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $data = [
                'name' => trim($_POST['name']),
                'rate' => filter_var(trim($_POST['rate']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            // Validate data
            $valid = true;
            
            if (empty($data['name'])) {
                flash('tax_error', 'Please enter a tax name', 'alert alert-danger');
                $valid = false;
            }
            
            if (!is_numeric($data['rate']) || $data['rate'] < 0 || $data['rate'] > 100) {
                flash('tax_error', 'Please enter a valid tax rate between 0 and 100', 'alert alert-danger');
                $valid = false;
            }

            if ($valid) {
                // Add tax rate
                if ($this->taxModel->addTaxRate($data)) {
                    flash('tax_success', 'Tax rate added successfully!', 'alert alert-success');
                    // Redirect to the same page to show the updated list
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    flash('tax_error', 'Failed to add tax rate. Please try again.', 'alert alert-danger');
                    $this->view('admin/tax/add', $data);
                }
            } else {
                // Reload view with errors and data
                $this->view('admin/tax/add', $data);
            }
        }

        $this->view('admin/tax/add');
    }

    // Show edit tax rate form
    public function edit($id) {
        // Get existing tax rate
        $taxRate = $this->taxModel->getTaxRateById($id);
        
        if (!$taxRate) {
            flash('tax_error', 'Tax rate not found', 'alert alert-danger');
            redirect('tax');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'rate' => filter_var(trim($_POST['rate']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            // Validate data
            $valid = true;
            
            if (empty($data['name'])) {
                flash('tax_error', 'Please enter a tax name', 'alert alert-danger');
                $valid = false;
            }
            
            if (!is_numeric($data['rate']) || $data['rate'] < 0 || $data['rate'] > 100) {
                flash('tax_error', 'Please enter a valid tax rate between 0 and 100', 'alert alert-danger');
                $valid = false;
            }

            if ($valid) {
                // Update tax rate
                if ($this->taxModel->updateTaxRate($data)) {
                    flash('tax_success', 'Tax rate updated successfully!', 'alert alert-success');
                    // Redirect to the same page to show the updated list
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    flash('tax_error', 'Failed to update tax rate. Please try again.', 'alert alert-danger');
                    $this->view('admin/tax/edit', $data);
                }
            } else {
                // Reload view with errors and data
                $this->view('admin/tax/edit', $data);
            }
        } else {
            // Load existing data for the form
            $data = [
                'id' => $taxRate->id,
                'name' => $taxRate->name,
                'rate' => $taxRate->rate,
                'is_active' => $taxRate->is_active
            ];
        }

        $this->view('admin/tax/edit', $data);
    }

    // Delete tax rate
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->taxModel->deleteTaxRate($id)) {
                flash('tax_success', 'Tax rate deleted successfully!', 'alert alert-success');
            } else {
                flash('tax_error', 'Failed to delete tax rate. Please try again.', 'alert alert-danger');
            }
            // Redirect back to the same page
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            redirect('tax');
        }
    }
}
