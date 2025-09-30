<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<!-- Contact Hero Section -->
<section class="contact-hero py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead">Have questions, feedback, or need assistance? We're here to help. Reach out to our team using the form below.</p>
            </div>
            <div class="col-md-6">
                <img src="<?php echo BASE_URL; ?>assets/images/contact-hero.jpg" alt="Contact Us" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="contact-form py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="mb-4">Send Us a Message</h2>
                        
                        <?php flash('contact_success'); ?>
                        
                        <form action="<?php echo BASE_URL; ?>?controller=home&action=contact" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" required>
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Your Email</label>
                                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" required>
                                    <?php if(isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control <?php echo isset($errors['subject']) ? 'is-invalid' : ''; ?>" id="subject" name="subject" value="<?php echo isset($data['subject']) ? $data['subject'] : ''; ?>" required>
                                <?php if(isset($errors['subject'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['subject']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control <?php echo isset($errors['message']) ? 'is-invalid' : ''; ?>" id="message" name="message" rows="5" required><?php echo isset($data['message']) ? $data['message'] : ''; ?></textarea>
                                <?php if(isset($errors['message'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['message']; ?></div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Contact Information</h4>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                123 Commerce Street, Business District, City, Country
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                +1 (555) 123-4567
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                info@ecommercestore.com
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Business Hours</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM
                            </li>
                            <li class="mb-2">
                                <strong>Saturday:</strong> 10:00 AM - 4:00 PM
                            </li>
                            <li>
                                <strong>Sunday:</strong> Closed
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Find Us</h2>
        <div class="row">
            <div class="col-12">
                <div class="map-container">
                    <!-- Replace with an actual map embed code in production -->
                    <div class="bg-secondary p-5 text-center text-white rounded">
                        <h4>Google Maps Placeholder</h4>
                        <p class="mb-0">In a real implementation, this would be replaced with an actual Google Maps embed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="accordion" id="contactFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                Standard shipping typically takes 3-5 business days within the country. International shipping can take 7-14 business days depending on the destination.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What is your return policy?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                We offer a 30-day return policy for most items. Products must be in their original condition with all packaging and tags. Some restrictions apply to certain categories.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Do you ship internationally?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                Yes, we ship to most countries worldwide. International shipping rates and delivery times vary by location. You can see the shipping options during checkout.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How can I track my order?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#contactFaq">
                            <div class="accordion-body">
                                Once your order ships, you'll receive a confirmation email with tracking information. You can also track your order by logging into your account and viewing your order history.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
