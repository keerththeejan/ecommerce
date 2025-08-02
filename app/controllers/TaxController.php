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

    // Show tax settings form
    public function index() {
        // Get tax rates
        $taxRates = $this->taxModel->getTaxRates();

        $data = [
            'tax1' => $taxRates['tax1'] ?? 0,
            'tax2' => $taxRates['tax2'] ?? 0,
            'tax3' => $taxRates['tax3'] ?? 0,
            'tax4' => $taxRates['tax4'] ?? 0
        ];

        $this->view('admin/tax/index', $data);
    }

    // Update tax rates
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $tax1 = filter_input(INPUT_POST, 'tax1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $tax2 = filter_input(INPUT_POST, 'tax2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $tax3 = filter_input(INPUT_POST, 'tax3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $tax4 = filter_input(INPUT_POST, 'tax4', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            $data = [
                'tax1' => $tax1,
                'tax2' => $tax2,
                'tax3' => $tax3,
                'tax4' => $tax4
            ];

            // Validate tax rates
            $valid = true;
            
            foreach (['tax1', 'tax2', 'tax3', 'tax4'] as $tax) {
                if (!is_numeric($data[$tax]) || $data[$tax] < 0 || $data[$tax] > 100) {
                    flash('tax_error', 'Please enter valid tax rates between 0 and 100', 'alert alert-danger');
                    $valid = false;
                    break;
                }
            }

            if ($valid) {
                // Update tax rates
                if($this->taxModel->updateTaxRates($data)) {
                    flash('tax_message', 'Tax rates updated successfully');
                } else {
                    flash('tax_error', 'Failed to update tax rates', 'alert alert-danger');
                }
            }
            
            // Redirect back to tax page
            header('Location: ' . BASE_URL . '?controller=tax&action=index');
            exit();

        } else {
            // If not a POST request, redirect to tax
            redirect('tax');
        }
    }
}
