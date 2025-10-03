<?php
declare(strict_types=1);
require_once __DIR__ . '/api/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Event Planner</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100" style="background: #f8fafc;">
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-5">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h2 fw-bold mb-3">Get in Touch</h1>
                        <p class="lead mb-0 opacity-90">
                            <i class="fas fa-comments me-2"></i>We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end gap-4">
                            <div class="text-center">
                                <div class="h4 fw-bold mb-0"><i class="fas fa-clock"></i></div>
                                <div class="small opacity-75">24/7 Support</div>
                            </div>
                            <div class="text-center">
                                <div class="h4 fw-bold mb-0"><i class="fas fa-reply"></i></div>
                                <div class="small opacity-75">Quick Response</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-5">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="card border-0" style="border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background: var(--gradient-primary);">
                                    <i class="fas fa-envelope text-white fa-lg"></i>
                                </div>
                                <h2 class="h4 fw-bold mb-2" style="color: #1f2937;">Send us a Message</h2>
                                <p class="text-muted">Fill out the form below and we'll get back to you within 24 hours</p>
                            </div>

                            <form id="contactForm" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label fw-semibold">
                                            <i class="fas fa-user me-1" style="color: var(--primary-color);"></i>First Name *
                                        </label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                                        <div class="invalid-feedback">Please provide your first name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label fw-semibold">
                                            <i class="fas fa-user me-1" style="color: var(--primary-color);"></i>Last Name *
                                        </label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                                        <div class="invalid-feedback">Please provide your last name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-1" style="color: var(--primary-color);"></i>Email Address *
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Please provide a valid email address.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">
                                            <i class="fas fa-phone me-1" style="color: var(--primary-color);"></i>Phone Number
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label fw-semibold">
                                            <i class="fas fa-tag me-1" style="color: var(--primary-color);"></i>Subject *
                                        </label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Choose a subject...</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="support">Technical Support</option>
                                            <option value="billing">Billing Question</option>
                                            <option value="feature">Feature Request</option>
                                            <option value="partnership">Partnership</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a subject.</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold">
                                            <i class="fas fa-comment me-1" style="color: var(--primary-color);"></i>Message *
                                        </label>
                                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                                        <div class="invalid-feedback">Please provide your message.</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                            <label class="form-check-label small text-muted" for="newsletter">
                                                I'd like to receive updates about new features and events
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-gradient btn-lg w-100">
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Success Message (hidden by default) -->
                            <div id="successMessage" class="alert alert-success mt-4" style="display: none;">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <!-- Contact Info Card -->
                        <div class="card border-0 mb-4" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                            <div class="card-header border-0" style="background: var(--gradient-primary); border-radius: 16px 16px 0 0;">
                                <h3 class="h5 fw-bold mb-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i>Contact Information
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.1);">
                                            <i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i>
                                        </div>
                                        <div>
                                            <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Address</h4>
                                            <p class="small text-muted mb-0">
                                                Street Name 123<br>
                                                City, State 12345<br>
                                                India
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(20, 184, 166, 0.1);">
                                            <i class="fas fa-phone" style="color: var(--success-color);"></i>
                                        </div>
                                        <div>
                                            <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Phone</h4>
                                            <p class="small text-muted mb-0">
                                                <a href="tel:+91-9876543210" class="text-decoration-none">+91 9876543210</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(34, 211, 238, 0.1);">
                                            <i class="fas fa-envelope" style="color: var(--accent-color);"></i>
                                        </div>
                                        <div>
                                            <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Email</h4>
                                            <p class="small text-muted mb-0">
                                                <a href="mailto:hello@eventplanner.com" class="text-decoration-none">hello@eventplanner.com</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(139, 92, 246, 0.1);">
                                            <i class="fas fa-clock" style="color: #8b5cf6;"></i>
                                        </div>
                                        <div>
                                            <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Business Hours</h4>
                                            <p class="small text-muted mb-0">
                                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                                Saturday: 10:00 AM - 4:00 PM<br>
                                                Sunday: Closed
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Card -->
                        <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                            <div class="card-header border-0" style="background: var(--gradient-secondary); border-radius: 16px 16px 0 0;">
                                <h3 class="h5 fw-bold mb-0 text-white">
                                    <i class="fas fa-question-circle me-2"></i>Quick FAQ
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="accordion accordion-flush" id="faqAccordion">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                                How quickly do you respond?
                                            </button>
                                        </h2>
                                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body small text-muted">
                                                We typically respond to all inquiries within 24 hours during business days.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                                Do you offer phone support?
                                            </button>
                                        </h2>
                                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body small text-muted">
                                                Yes! You can call us during business hours for immediate assistance.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                                Can I schedule a demo?
                                            </button>
                                        </h2>
                                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body small text-muted">
                                                Absolutely! Contact us to schedule a personalized demo of our platform.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Form validation and submission
        (function() {
            'use strict';
            
            const form = document.getElementById('contactForm');
            const successMessage = document.getElementById('successMessage');
            
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                if (form.checkValidity()) {
                    // Simulate form submission
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                    submitBtn.disabled = true;
                    
                    setTimeout(() => {
                        form.style.display = 'none';
                        successMessage.style.display = 'block';
                        
                        // Reset form after 5 seconds
                        setTimeout(() => {
                            form.style.display = 'block';
                            successMessage.style.display = 'none';
                            form.reset();
                            form.classList.remove('was-validated');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 5000);
                    }, 2000);
                }
                
                form.classList.add('was-validated');
            });
        })();

        // Add hover effects to contact info items
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)';
            });
        });

        // Track visit
        fetch('/api/visits.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ page_url: window.location.pathname })
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
