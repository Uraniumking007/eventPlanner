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
