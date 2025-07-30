<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<!-- Modern Full-View Login Section -->
<section class="login-section full-width-section">
    <div class="login-bg-overlay"></div>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <h3>Welcome Back</h3>
                        <p>Sign in to your account to continue</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if(isset($error)): ?>
                            <div class="alert custom-alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form action="<?php echo BASE_URL; ?>?controller=user&action=login" method="POST">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control custom-input" id="username" name="username" placeholder="Username or Email" required>
                                <label for="username"><i class="fas fa-user me-2"></i>Username or Email</label>
                            </div>
                            
                            <div class="form-floating mb-4 password-field">
                                <input type="password" class="form-control custom-input" id="password" name="password" placeholder="Password" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                <button type="button" class="btn password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <a href="<?php echo BASE_URL; ?>?controller=user&action=forgot_password" class="forgot-link">Forgot password?</a>
                            </div>
                            
                            <button type="submit" class="btn custom-btn-primary w-100">Sign In <i class="fas fa-arrow-right ms-2"></i></button>
                        </form>
                        
                        <div class="login-divider">
                            <span>or continue with</span>
                        </div>
                        
                        <div class="social-login">
                            <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                            <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                        
                        <div class="text-center mt-4">
                            <span class="account-text">Don't have an account?</span>
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=register" class="register-link">Register Now</a>
                        </div>
                    </div>
                </div>
                
                <!-- Removed login-footer as it's redundant with the main footer -->
            </div>
        </div>
    </div>
</section>

<!-- Login Page Styles -->
<style>
    /* Full-width Login Section */
    .login-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        background-image: url('<?php echo BASE_URL; ?>public/assets/images/banners/login-bg.jpg');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        z-index: 1;
    }
    
    .login-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(240, 240, 240, 0.5) 100%);
        z-index: 1;
    }
    
    .login-section .container {
        position: relative;
        z-index: 2;
    }
    
    /* Login Card Styles */
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .login-header {
        background: linear-gradient(135deg, #00ADB5 0%, #393E46 100%);
        padding: 30px;
        text-align: center;
        color: white;
    }
    
    .login-header h3 {
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .login-header p {
        opacity: 0.8;
        margin-bottom: 0;
    }
    
    .login-body {
        padding: 30px;
    }
    
    /* Form Styles */
    .custom-input {
        height: 58px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }
    
    .custom-input:focus {
        border-color: #00ADB5;
        box-shadow: 0 0 0 0.25rem rgba(0, 173, 181, 0.25);
    }
    
    .form-floating > label {
        padding-left: 1rem;
        color: #666;
    }
    
    .password-field {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        z-index: 5;
        padding: 0;
    }
    
    .custom-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
    }
    
    .custom-checkbox:checked {
        background-color: #00ADB5;
        border-color: #00ADB5;
    }
    
    .forgot-link {
        color: #00ADB5;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .forgot-link:hover {
        color: #007a80;
        text-decoration: underline;
    }
    
    /* Button Styles */
    .custom-btn-primary {
        background: linear-gradient(135deg, #00ADB5 0%, #007a80 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 173, 181, 0.3);
    }
    
    .custom-btn-primary:hover {
        background: linear-gradient(135deg, #00c2cc 0%, #00ADB5 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 173, 181, 0.4);
    }
    
    /* Alert Styles */
    .custom-alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    /* Divider Styles */
    .login-divider {
        text-align: center;
        margin: 25px 0;
        position: relative;
    }
    
    .login-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
    
    .login-divider span {
        background: white;
        padding: 0 15px;
        position: relative;
        z-index: 2;
        color: #666;
        font-size: 0.9rem;
    }
    
    /* Social Login Styles */
    .social-login {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 10px;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    
    .social-btn.facebook {
        background: #3b5998;
    }
    
    .social-btn.google {
        background: #db4437;
    }
    
    .social-btn.twitter {
        background: #1da1f2;
    }
    
    /* Register Link Styles */
    .account-text {
        color: #666;
        margin-right: 5px;
    }
    
    .register-link {
        color: #00ADB5;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .register-link:hover {
        color: #007a80;
        text-decoration: underline;
    }
    
    /* Login Page Body Styles */
    body.login-page {
        background-color: transparent;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .login-section {
            padding: 40px 0;
        }
        
        .login-header,
        .login-body {
            padding: 20px;
        }
        
        .login-card {
            margin: 0 15px;
        }
    }
    
    @media (max-width: 767px) {
        .login-section {
            padding: 30px 0;
            min-height: calc(100vh - 60px); /* Adjust for mobile header */
        }
        
        .custom-input {
            height: 50px;
        }
        
        .social-login {
            gap: 10px;
        }
        
        .social-btn {
            width: 45px;
            height: 45px;
        }
        
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .forgot-link {
            margin-top: 10px;
            display: inline-block;
        }
    }
    
    @media (max-width: 480px) {
        .login-header h3 {
            font-size: 1.5rem;
        }
        
        .login-header p {
            font-size: 0.9rem;
        }
        
        .form-floating > label {
            font-size: 0.9rem;
        }
        
        .login-divider span {
            font-size: 0.8rem;
        }
        
        .account-text, .register-link {
            font-size: 0.9rem;
        }
    }
</style>

<script>
    // Function to toggle password visibility
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    // Add login-page class to body for specific styling
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('login-page');
    });
    
    // Create a placeholder login background if it doesn't exist
    document.addEventListener('DOMContentLoaded', function() {
        const img = new Image();
        img.onload = function() {
            // Image exists, do nothing
        };
        img.onerror = function() {
            // Image doesn't exist, use a gradient background instead
            // document.querySelector('.login-section').style.backgroundImage = 'linear-gradient(135deg, #222831 0%, #393E46 100%)';
        };
        img.src = '<?php echo BASE_URL; ?>public/assets/images/banners/login-bg.jpg';
    });
</script>

</body>
</html>
