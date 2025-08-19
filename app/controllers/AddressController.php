<?php
class AddressController extends Controller {
    private $addressModel;

    public function __construct() {
        parent::__construct();
        $this->addressModel = $this->model('Address');
    }

    public function index() {
        if(!isLoggedIn()) redirect('user/login');
        $addresses = $this->addressModel->getByUser($_SESSION['user_id']);
        $this->view('customer/address/index', [
            'addresses' => $addresses
        ]);
    }

    public function create() {
        if(!isLoggedIn()) redirect('user/login');
        $this->view('customer/address/form', [
            'mode' => 'create',
            'action' => BASE_URL . '?controller=address&action=store',
            'data' => [
                'type' => 'shipping',
                'first_name' => '',
                'last_name' => '',
                'company' => '',
                'address1' => '',
                'address2' => '',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'country' => '',
                'phone' => '',
                'is_default' => 0
            ],
            'errors' => []
        ]);
    }

    public function store() {
        if(!isLoggedIn()) redirect('user/login');
        if(!$this->isPost()) redirect('address');

        $data = [
            'user_id' => $_SESSION['user_id'],
            'type' => sanitize($this->post('type')),
            'first_name' => sanitize($this->post('first_name')),
            'last_name' => sanitize($this->post('last_name')),
            'company' => sanitize($this->post('company')),
            'address1' => sanitize($this->post('address1')),
            'address2' => sanitize($this->post('address2')),
            'city' => sanitize($this->post('city')),
            'state' => sanitize($this->post('state')),
            'postal_code' => sanitize($this->post('postal_code')),
            'country' => sanitize($this->post('country')),
            'phone' => sanitize($this->post('phone')),
            'is_default' => $this->post('is_default') ? 1 : 0
        ];

        $errors = $this->validate($data, [
            'type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required'
        ]);

        if(!in_array($data['type'], ['billing','shipping'])) {
            $errors['type'] = 'Invalid address type';
        }

        if(!empty($errors)) {
            $this->view('customer/address/form', [
                'mode' => 'create',
                'action' => BASE_URL . '?controller=address&action=store',
                'data' => $data,
                'errors' => $errors
            ]);
            return;
        }

        // If setting default, unset existing default of same type
        if($data['is_default']) {
            $this->addressModel->unsetDefaultForUser($_SESSION['user_id'], $data['type']);
        }

        $id = $this->addressModel->createAddress($data);
        if($id) {
            flash('address_success', 'Address created successfully');
            redirect('address');
        } else {
            flash('address_error', 'Failed to create address', 'alert alert-danger');
            $this->view('customer/address/form', [
                'mode' => 'create',
                'action' => BASE_URL . '?controller=address&action=store',
                'data' => $data,
                'errors' => []
            ]);
        }
    }

    public function edit($id = null) {
        if(!isLoggedIn()) redirect('user/login');
        $id = $id ?: (int)$this->get('id');
        if(!$id) redirect('address');
        $address = $this->addressModel->getByIdForUser($id, $_SESSION['user_id']);
        if(!$address) {
            flash('address_error', 'Address not found', 'alert alert-danger');
            redirect('address');
        }
        $this->view('customer/address/form', [
            'mode' => 'edit',
            'action' => BASE_URL . '?controller=address&action=update&id=' . $id,
            'data' => $address,
            'errors' => []
        ]);
    }

    public function update($id = null) {
        if(!isLoggedIn()) redirect('user/login');
        if(!$this->isPost()) redirect('address');
        $id = $id ?: (int)$this->get('id');
        if(!$id) redirect('address');

        $address = $this->addressModel->getByIdForUser($id, $_SESSION['user_id']);
        if(!$address) {
            flash('address_error', 'Address not found', 'alert alert-danger');
            redirect('address');
        }

        $data = [
            'type' => sanitize($this->post('type')),
            'first_name' => sanitize($this->post('first_name')),
            'last_name' => sanitize($this->post('last_name')),
            'company' => sanitize($this->post('company')),
            'address1' => sanitize($this->post('address1')),
            'address2' => sanitize($this->post('address2')),
            'city' => sanitize($this->post('city')),
            'state' => sanitize($this->post('state')),
            'postal_code' => sanitize($this->post('postal_code')),
            'country' => sanitize($this->post('country')),
            'phone' => sanitize($this->post('phone')),
            'is_default' => $this->post('is_default') ? 1 : 0
        ];

        $errors = $this->validate($data, [
            'type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required'
        ]);

        if(!in_array($data['type'], ['billing','shipping'])) {
            $errors['type'] = 'Invalid address type';
        }

        if(!empty($errors)) {
            $address['type'] = $data['type'];
            $address = array_merge($address, $data);
            $this->view('customer/address/form', [
                'mode' => 'edit',
                'action' => BASE_URL . '?controller=address&action=update&id=' . $id,
                'data' => $address,
                'errors' => $errors
            ]);
            return;
        }

        if($data['is_default']) {
            $this->addressModel->unsetDefaultForUser($_SESSION['user_id'], $data['type']);
        }

        $ok = $this->addressModel->updateAddress($id, $data);
        if($ok) {
            flash('address_success', 'Address updated successfully');
        } else {
            flash('address_error', 'Failed to update address', 'alert alert-danger');
        }
        redirect('address');
    }

    public function delete($id = null) {
        if(!isLoggedIn()) redirect('user/login');
        $id = $id ?: (int)$this->get('id');
        if(!$id) redirect('address');
        $address = $this->addressModel->getByIdForUser($id, $_SESSION['user_id']);
        if(!$address) {
            flash('address_error', 'Address not found', 'alert alert-danger');
            redirect('address');
        }
        if($this->addressModel->deleteAddress($id)) {
            flash('address_success', 'Address deleted successfully');
        } else {
            flash('address_error', 'Failed to delete address', 'alert alert-danger');
        }
        redirect('address');
    }
}
