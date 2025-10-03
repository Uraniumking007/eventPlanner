<?php
declare(strict_types=1);
require_once __DIR__ . '/api/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Event Planner</title>
    
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
                        <h1 class="h2 fw-bold mb-3">About Event Planner</h1>
                        <p class="lead mb-0 opacity-90">
                            <i class="fas fa-heart me-2"></i>Connecting communities through unforgettable events
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end gap-3">
                            <div class="text-center">
                                <div class="h3 fw-bold mb-0">500+</div>
                                <div class="small opacity-75">Events Created</div>
                            </div>
                            <div class="text-center">
                                <div class="h3 fw-bold mb-0">10K+</div>
                                <div class="small opacity-75">Happy Attendees</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-5">
            <!-- Our Story -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <div class="pe-lg-4">
                        <h2 class="h3 fw-bold mb-4" style="color: #1f2937;">
                            <i class="fas fa-lightbulb me-2" style="color: var(--primary-color);"></i>Our Story
                        </h2>
                        <p class="text-muted mb-4">
                            Founded in 2020, Event Planner was born from a simple idea: making event management accessible to everyone. 
                            We believe that every great event starts with great planning, and every great plan deserves the right tools.
                        </p>
                        <p class="text-muted mb-4">
                            What started as a small team of passionate event organizers has grown into a comprehensive platform 
                            that empowers thousands of organizers to create memorable experiences for their communities.
                        </p>
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-center">
                                <div class="h4 fw-bold mb-1" style="color: var(--primary-color);">4+</div>
                                <div class="small text-muted">Years of Excellence</div>
                            </div>
                            <div class="text-center">
                                <div class="h4 fw-bold mb-1" style="color: var(--success-color);">50+</div>
                                <div class="small text-muted">Cities Served</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="card border-0" style="border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="text-center p-3" style="background: rgba(6, 182, 212, 0.1); border-radius: 12px;">
                                            <i class="fas fa-users fa-2x mb-2" style="color: var(--primary-color);"></i>
                                            <div class="fw-bold">Community</div>
                                            <div class="small text-muted">First Approach</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3" style="background: rgba(20, 184, 166, 0.1); border-radius: 12px;">
                                            <i class="fas fa-rocket fa-2x mb-2" style="color: var(--success-color);"></i>
                                            <div class="fw-bold">Innovation</div>
                                            <div class="small text-muted">Driven Solutions</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3" style="background: rgba(34, 211, 238, 0.1); border-radius: 12px;">
                                            <i class="fas fa-shield-alt fa-2x mb-2" style="color: var(--accent-color);"></i>
                                            <div class="fw-bold">Reliability</div>
                                            <div class="small text-muted">Trusted Platform</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3" style="background: rgba(139, 92, 246, 0.1); border-radius: 12px;">
                                            <i class="fas fa-star fa-2x mb-2" style="color: #8b5cf6;"></i>
                                            <div class="fw-bold">Excellence</div>
                                            <div class="small text-muted">In Every Detail</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission & Vision -->
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="card h-100 border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: var(--gradient-primary);">
                                    <i class="fas fa-bullseye text-white"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-0" style="color: #1f2937;">Our Mission</h3>
                            </div>
                            <p class="text-muted mb-0">
                                To democratize event management by providing intuitive, powerful tools that enable anyone 
                                to create, organize, and manage events that bring people together and create lasting memories.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: var(--gradient-secondary);">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-0" style="color: #1f2937;">Our Vision</h3>
                            </div>
                            <p class="text-muted mb-0">
                                To become the world's leading platform for event management, where every organizer 
                                has the tools they need to create extraordinary experiences that inspire and connect communities.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Values -->
            <div class="mb-5">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-bold mb-3" style="color: #1f2937;">
                        <i class="fas fa-gem me-2" style="color: var(--primary-color);"></i>Our Values
                    </h2>
                    <p class="text-muted">The principles that guide everything we do</p>
                </div>
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background: var(--gradient-primary);">
                                    <i class="fas fa-handshake text-white fa-lg"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-2" style="color: #1f2937;">Trust</h4>
                                <p class="small text-muted mb-0">Building lasting relationships through transparency and reliability</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background: var(--gradient-secondary);">
                                    <i class="fas fa-lightbulb text-white fa-lg"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-2" style="color: #1f2937;">Innovation</h4>
                                <p class="small text-muted mb-0">Continuously improving and evolving our platform</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background: var(--gradient-success);">
                                    <i class="fas fa-users text-white fa-lg"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-2" style="color: #1f2937;">Community</h4>
                                <p class="small text-muted mb-0">Fostering connections and bringing people together</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                    <i class="fas fa-heart text-white fa-lg"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-2" style="color: #1f2937;">Passion</h4>
                                <p class="small text-muted mb-0">Driven by our love for creating memorable experiences</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="mb-5">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-bold mb-3" style="color: #1f2937;">
                        <i class="fas fa-users-cog me-2" style="color: var(--primary-color);"></i>Meet Our Team
                    </h2>
                    <p class="text-muted">The passionate developers behind Event Planner</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="card border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: var(--gradient-primary);">
                                    <i class="fas fa-code text-white fa-2x"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Bhavesh Patil</h4>
                                <p class="small text-muted mb-2">Fullstack Developer</p>
                                <p class="small text-muted mb-0">Building robust backend systems and seamless user experiences</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="card border-0 text-center" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; background: var(--gradient-secondary);">
                                    <i class="fas fa-paint-brush text-white fa-2x"></i>
                                </div>
                                <h4 class="h6 fw-bold mb-1" style="color: #1f2937;">Krishna Choksi</h4>
                                <p class="small text-muted mb-2">Frontend Developer</p>
                                <p class="small text-muted mb-0">Creating beautiful and intuitive user interfaces</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center">
                <div class="card border-0" style="border-radius: 20px; background: var(--gradient-hero); color: white;">
                    <div class="card-body p-5">
                        <h2 class="h3 fw-bold mb-3">Ready to Create Amazing Events?</h2>
                        <p class="lead mb-4 opacity-90">
                            Join thousands of organizers who trust Event Planner to bring their vision to life
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            <a href="/register.php" class="btn btn-light btn-lg px-4">
                                <i class="fas fa-rocket me-2"></i>Get Started Free
                            </a>
                            <a href="/contact.php" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Add hover effects to value cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = '0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04)';
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
