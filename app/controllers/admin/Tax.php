<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('tax_model');
        $this->load->library('session');
        
        // Check if user is logged in and is admin
        if(!$this->session->userdata('logged_in') || $this->session->userdata('user_type') != 'admin') {
            redirect('admin/login');
        }
    }
    
    public function index() {
        $data['tax_rates'] = $this->tax_model->get_tax_rates();
        $this->load->view('admin/layouts/header');
        $this->load->view('admin/layouts/sidebar');
        $this->load->view('admin/tax/index', $data);
        $this->load->view('admin/layouts/footer');
    }
    
    public function update() {
        $data = array(
            'tax1' => $this->input->post('tax1'),
            'tax2' => $this->input->post('tax2'),
            'tax3' => $this->input->post('tax3'),
            'tax4' => $this->input->post('tax4')
        );
        
        if($this->tax_model->update_tax_rates($data)) {
            $this->session->set_flashdata('success', 'Tax rates updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update tax rates.');
        }
        
        redirect('admin/tax');
    }
}
