<?php
/**
 * Country Controller
 */
class CountryController extends Controller {
    private $countryModel;
    private $productModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->countryModel = $this->model('Country');
        $this->productModel = $this->model('Product');
    }
    
    /**
     * Index - List all countries
     */
    public function index() {
        // Get all countries
        $countries = $this->countryModel->getActiveCountries();
        
        // Load view
        $this->view('customer/countries/index', [
            'countries' => $countries,
            'title' => 'Countries of Origin'
        ]);
    }
    
    /**
     * Show - Show products from a specific country
     * 
     * @param int $id Country ID
     */
    public function show($id = null) {
        // Get country
        $country = $this->countryModel->getCountryById($id);
        
        if(!$country) {
            redirect('?controller=country&action=index');
        }
        
        // Get products by country
        $products = $this->countryModel->getProductsByCountry($id);
        
        // Load view
        $this->view('customer/countries/show', [
            'country' => $country,
            'products' => $products,
            'title' => $country['name'] . ' Products'
        ]);
    }
}
