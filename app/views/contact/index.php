<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Contact Us</h2>
                    <p class="mb-0">Have questions? We're here to help!</p>
                </div>
                <div class="card-body">
                    <?php flash('contact_message'); ?>
                    
                    <form action="<?php echo URLROOT; ?>/contact/send" method="post" id="contactForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="name" name="name" value="<?php echo htmlspecialchars($data['name']); ?>">
                                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number (Optional)</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($data['phone']); ?>">
                            <small class="text-muted">Include country code if outside the US</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>" 
                                   id="subject" name="subject" value="<?php echo htmlspecialchars($data['subject']); ?>">
                            <span class="invalid-feedback"><?php echo $data['subject_err']; ?></span>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Your Message <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>" 
                                      id="message" name="message" rows="6"><?php echo htmlspecialchars($data['message']); ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
                            <div class="form-text">Please provide as much detail as possible</div>
                        </div>
                        
                        <!-- reCAPTCHA can be added here -->
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="text-primary mb-2">
                                <i class="fas fa-map-marker-alt fa-2x"></i>
                            </div>
                            <h6 class="h6 mb-1">Our Location</h6>
                            <p class="mb-0 small">123 Business St.<br>City, State 12345</p>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="text-primary mb-2">
                                <i class="fas fa-phone-alt fa-2x"></i>
                            </div>
                            <h6 class="h6 mb-1">Phone</h6>
                            <p class="mb-0">
                                <a href="tel:+1234567890" class="text-decoration-none">+1 (234) 567-890</a>
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-primary mb-2">
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                            <h6 class="h6 mb-1">Email</h6>
                            <p class="mb-0">
                                <a href="mailto:info@example.com" class="text-decoration-none">info@example.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Map Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9 rounded shadow">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215209056535!2d-73.98784492401249!3d40.74844097138994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1623456789012!5m2!1sen!2sus" 
                            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="h1">Frequently Asked Questions</h2>
        <p class="lead text-muted">Find answers to common questions about our services</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item mb-3 border rounded">
                    <h3 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How can I track my order?
                        </button>
                    </h3>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can track your order by logging into your account and visiting the 'My Orders' section. 
                            You'll find a tracking number that you can use on our shipping partner's website.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item mb-3 border rounded">
                    <h3 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What is your return policy?
                        </button>
                    </h3>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a 30-day return policy for most items. Items must be in their original condition with all tags attached. 
                            Please contact our support team to initiate a return and receive a return authorization number.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item mb-3 border rounded">
                    <h3 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How can I contact customer support?
                        </button>
                    </h3>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Our customer support team is available 24/7. You can reach us by phone at +1 (234) 567-890, 
                            by email at support@example.com, or by filling out the contact form on this page.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side form validation
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(event) {
        let isValid = true;
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const subject = document.getElementById('subject');
        const message = document.getElementById('message');
        
        // Reset error states
        [name, email, subject, message].forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        // Validate name
        if (name.value.trim() === '') {
            name.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            email.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate subject
        if (subject.value.trim() === '') {
            subject.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate message
        if (message.value.trim() === '' || message.value.trim().length < 10) {
            message.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
