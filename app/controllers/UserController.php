<?php
/**
 * User Controller
 * Handles user authentication and profile management
 */
class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    /**
     * Register new user
     */
    public function register() {
        // Check if logged in
        if(isLoggedIn()) {
            redirect('');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'username' => sanitize($this->post('username')),
                'email' => sanitize($this->post('email')),
                'password' => $this->post('password'),
                'confirm_password' => $this->post('confirm_password'),
                'first_name' => sanitize($this->post('first_name')),
                'last_name' => sanitize($this->post('last_name')),
                'role' => 'customer'
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'username' => 'required|min:3|max:50',
                'email' => 'required|email|max:100',
                'password' => 'required|min:6|max:255',
                'confirm_password' => 'required|match:password',
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50'
            ]);
            
            // Check if username exists
            if(empty($errors['username']) && $this->userModel->findUserByUsername($data['username'])) {
                $errors['username'] = 'Username is already taken';
            }
            
            // Check if email exists
            if(empty($errors['email']) && $this->userModel->findUserByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Register user
                $userId = $this->userModel->register($data);
                
                if($userId) {
                    flash('register_success', 'You are registered and can now log in');
                    redirect('user/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('customer/user/register', [
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name' => '',
                'last_name' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('customer/user/register', $data);
        }
    }
    
    /**
     * Login user
     */
    public function login() {
        // Check if logged in
        if(isLoggedIn()) {
            redirect('');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'username' => sanitize($this->post('username')),
                'password' => $this->post('password'),
                'remember_me' => $this->post('remember_me') ? true : false
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'username' => 'required',
                'password' => 'required'
            ]);
            
            // Check for errors
            if(empty($errors)) {
                // Check and set logged in user
                $user = $this->userModel->login($data['username'], $data['password']);
                
                if($user) {
                    // Create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    // Set remember me cookie if checked
                    if($data['remember_me']) {
                        $token = bin2hex(random_bytes(32));
                        
                        // Store token in database
                        $tokenData = [
                            'user_id' => $user['id'],
                            'token' => $token,
                            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days'))
                        ];
                        
                        // Use the remember_tokens table (you'll need to create this)
                        $tokenModel = $this->model('RememberToken');
                        $tokenModel->create($tokenData);
                        
                        // Set cookie
                        setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
                    }
                    
                    // Redirect based on role
                    switch($user['role']) {
                        case 'admin':
                            redirect('admin/dashboard');
                            break;
                        case 'staff':
                            redirect('pos');
                            break;
                        default:
                            redirect('');
                            break;
                    }
                } else {
                    // Login failed
                    $errors['login'] = 'Invalid username/email or password';
                    
                    // Load view with errors
                    $this->view('customer/user/login', [
                        'errors' => $errors,
                        'data' => $data
                    ]);
                }
            } else {
                // Load view with errors
                $this->view('customer/user/login', [
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'remember_me' => false,
                'errors' => []
            ];
            
            // Load view
            $this->view('customer/user/login', $data);
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Call the logout helper function
        logout();
        
        // Redirect to login page
        flash('logout_success', 'You are now logged out');
        redirect('user/login');
    }
    
    /**
     * User profile
     */
    public function profile() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get user
        $user = $this->userModel->getById($_SESSION['user_id']);
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'first_name' => sanitize($this->post('first_name')),
                'last_name' => sanitize($this->post('last_name')),
                'email' => sanitize($this->post('email'))
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'email' => 'required|email|max:100'
            ]);
            
            // Check if email exists and is not the current user's email
            if(empty($errors['email']) && $data['email'] != $user['email']) {
                $existingUser = $this->userModel->findUserByEmail($data['email']);
                if($existingUser) {
                    $errors['email'] = 'Email is already registered';
                }
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Update user
                if($this->userModel->update($_SESSION['user_id'], $data)) {
                    // Update session
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                    
                    flash('profile_success', 'Profile updated successfully');
                    redirect('user/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('customer/profile', [
                    'errors' => $errors,
                    'user' => array_merge($user, $data)
                ]);
            }
        } else {
            // Load view
            $this->view('customer/profile', [
                'user' => $user,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Change password
     */
    public function changePassword() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'current_password' => $this->post('current_password'),
                'new_password' => $this->post('new_password'),
                'confirm_password' => $this->post('confirm_password')
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'current_password' => 'required',
                'new_password' => 'required|min:6|max:255',
                'confirm_password' => 'required|match:new_password'
            ]);
            
            // Check current password
            $user = $this->userModel->getById($_SESSION['user_id']);
            if(empty($errors['current_password']) && !password_verify($data['current_password'], $user['password'])) {
                $errors['current_password'] = 'Current password is incorrect';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Update password
                if($this->userModel->updatePassword($_SESSION['user_id'], $data['new_password'])) {
                    flash('password_success', 'Password changed successfully');
                    redirect('user/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('customer/user/change_password', [
                    'errors' => $errors
                ]);
            }
        } else {
            // Load view
            $this->view('customer/user/change_password', [
                'errors' => []
            ]);
        }
    }
    
    /**
     * Forgot password
     */
    public function forgotPassword() {
        // Check if logged in
        if(isLoggedIn()) {
            redirect('');
        }
        
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
                // Find user by email
                $user = $this->userModel->findUserByEmail($data['email']);
                
                if($user) {
                    // Generate reset token
                    $token = generateRandomString(32);
                    
                    // Store token in database (would need to add a password_resets table)
                    
                    // Send email with reset link
                    // This is just a placeholder - you would need to implement actual email sending
                    $resetLink = BASE_URL . 'user/resetPassword/' . $token;
                    
                    flash('forgot_password_success', 'Reset link has been sent to your email');
                    redirect('user/login');
                } else {
                    $errors['email'] = 'No account found with that email';
                    
                    // Load view with errors
                    $this->view('customer/user/forgot_password', [
                        'errors' => $errors,
                        'data' => $data
                    ]);
                }
            } else {
                // Load view with errors
                $this->view('customer/user/forgot_password', [
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('customer/user/forgot_password', [
                'data' => $data,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin: List all users
     */
    public function adminIndex() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get page number
        $page = $this->get('page', 1);
        
        // Get users with pagination
        $users = $this->userModel->paginate($page, 20, 'id', 'DESC');
        
        // Load view
        $this->view('admin/users/index', [
            'users' => $users
        ]);
    }
    
    /**
     * Admin: Create user form
     */
    public function adminCreate() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'username' => sanitize($this->post('username')),
                'email' => sanitize($this->post('email')),
                'password' => $this->post('password'),
                'confirm_password' => $this->post('confirm_password'),
                'first_name' => sanitize($this->post('first_name')),
                'last_name' => sanitize($this->post('last_name')),
                'role' => $this->post('role')
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'username' => 'required|min:3|max:50',
                'email' => 'required|email|max:100',
                'password' => 'required|min:6|max:255',
                'confirm_password' => 'required|match:password',
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'role' => 'required'
            ]);
            
            // Check if username exists
            if(empty($errors['username']) && $this->userModel->findUserByUsername($data['username'])) {
                $errors['username'] = 'Username is already taken';
            }
            
            // Check if email exists
            if(empty($errors['email']) && $this->userModel->findUserByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Register user
                $userId = $this->userModel->register($data);
                
                if($userId) {
                    flash('user_success', 'User created successfully');
                    redirect('user/adminIndex');
                } else {
                    $errors['db_error'] = 'Failed to create user: ' . $this->userModel->getLastError();
                }
            }
            
            // Load view with errors
            $this->view('admin/users/create', [
                'errors' => $errors,
                'data' => $data
            ]);
        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name' => '',
                'last_name' => '',
                'role' => 'customer'
            ];
            
            // Load view
            $this->view('admin/users/create', [
                'errors' => [],
                'data' => $data
            ]);
        }
    }
    
    /**
     * Admin: Edit user form
     * 
     * @param int $id User ID
     */
    public function adminEdit($id) {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get user
        $user = $this->userModel->getById($id);
        
        // Check if user exists
        if(!$user) {
            flash('user_error', 'User not found', 'alert alert-danger');
            redirect('user/adminIndex');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'username' => sanitize($this->post('username')),
                'email' => sanitize($this->post('email')),
                'first_name' => sanitize($this->post('first_name')),
                'last_name' => sanitize($this->post('last_name')),
                'role' => $this->post('role')
            ];
            
            // Check if password is being updated
            $password = $this->post('password');
            if(!empty($password)) {
                $data['password'] = $password;
                $data['confirm_password'] = $this->post('confirm_password');
            }
            
            // Validation rules
            $rules = [
                'username' => 'required|min:3|max:50',
                'email' => 'required|email|max:100',
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'role' => 'required'
            ];
            
            // Add password validation if being updated
            if(!empty($password)) {
                $rules['password'] = 'required|min:6|max:255';
                $rules['confirm_password'] = 'required|match:password';
            }
            
            // Validate data
            $errors = $this->validate($data, $rules);
            
            // Check if username exists (if changed)
            if(empty($errors['username']) && $data['username'] != $user['username'] && $this->userModel->findUserByUsername($data['username'])) {
                $errors['username'] = 'Username is already taken';
            }
            
            // Check if email exists (if changed)
            if(empty($errors['email']) && $data['email'] != $user['email'] && $this->userModel->findUserByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Remove confirm_password from data
                if(isset($data['confirm_password'])) {
                    unset($data['confirm_password']);
                }
                
                // Update user
                if($this->userModel->update($id, $data)) {
                    flash('user_success', 'User updated successfully');
                    redirect('user/adminIndex');
                } else {
                    $errors['db_error'] = 'Failed to update user: ' . $this->userModel->getLastError();
                }
            }
            
            // Load view with errors
            $this->view('admin/users/edit', [
                'errors' => $errors,
                'user' => array_merge($user, $data)
            ]);
        } else {
            // Load view
            $this->view('admin/users/edit', [
                'user' => $user,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin: Delete user
     * 
     * @param int $id User ID
     */
    public function adminDelete($id) {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get user
        $user = $this->userModel->getById($id);
        
        // Check if user exists
        if(!$user) {
            flash('user_error', 'User not found', 'alert alert-danger');
            redirect('user/adminIndex');
        }
        
        // Prevent deleting own account
        if($user['id'] == $_SESSION['user_id']) {
            flash('user_error', 'You cannot delete your own account', 'alert alert-danger');
            redirect('user/adminIndex');
        }
        
        // Check for POST
        if($this->isPost()) {
            // Delete user
            if($this->userModel->delete($id)) {
                flash('user_success', 'User deleted successfully');
            } else {
                flash('user_error', 'Failed to delete user: ' . $this->userModel->getLastError(), 'alert alert-danger');
            }
            
            redirect('user/adminIndex');
        } else {
            // Load view
            $this->view('admin/users/delete', [
                'user' => $user
            ]);
        }
    }
}
