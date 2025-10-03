<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner - Create & Manage Amazing Events</title>
    <meta name="description" content="The modern platform for creating, managing, and hosting successful events. Join thousands of organizers worldwide.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-5" style="min-height: 100vh; display: flex; align-items: center;">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="badge bg-white text-primary px-3 py-2 rounded-pill mb-3">
                        <i class="fas fa-star text-warning me-2"></i>
                        Trusted by 1000+ organizers worldwide
                    </div>
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">
                        Create Unforgettable Events That People Love
                    </h1>
                    <p class="lead mb-4 opacity-90" style="font-size: 1.25rem;">
                        From conferences to concerts, workshops to weddings – manage every detail with our powerful, intuitive platform. Join thousands of successful event organizers today.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-5">
                        <a href="/events.php" class="btn btn-gradient btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i>Browse Events
                        </a>
                        <a href="/login.php" class="btn btn-outline-custom btn-lg">
                            <i class="fas fa-rocket me-2"></i>Get Started Free
                        </a>
                    </div>
                    
                    <!-- Stats Preview -->
                    <div class="row g-4 mt-4">
                        <div class="col-4">
                            <div class="stat-number" id="heroStatEvents">0</div>
                            <div class="opacity-90">Events Created</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number" id="heroStatAttendees">0</div>
                            <div class="opacity-90">Happy Attendees</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number" id="heroStatVisits">0</div>
                            <div class="opacity-90">Platform Visits</div>
                        </div>
                    </div>
                </div>
                
                <!-- Carousel -->
                <div class="col-lg-6">
                    <div id="homeCarousel" class="carousel slide floating-card" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-indicators"></div>
                        <div class="carousel-inner shadow-lg" style="border-radius: 20px;"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light" style="padding: 6rem 0 !important;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">
                    <i class="fas fa-sparkles me-2"></i>Features
                </span>
                <h2 class="display-5 fw-bold mb-3">Everything You Need to Succeed</h2>
                <p class="text-muted lead" style="max-width: 700px; margin: 0 auto;">
                    Powerful tools designed to make event planning effortless, from creation to execution.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: var(--gradient-primary);">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Easy Event Creation</h3>
                        <p class="text-muted mb-0">Launch your event in minutes with our intuitive builder. No technical skills required – just your vision.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: var(--gradient-secondary);">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Attendee Management</h3>
                        <p class="text-muted mb-0">Track registrations, manage capacity, send updates, and keep your audience engaged every step of the way.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: var(--gradient-success);">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Real-time Analytics</h3>
                        <p class="text-muted mb-0">Make data-driven decisions with comprehensive insights, attendance tracking, and engagement metrics.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-ticket-alt text-white"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Smart Registration</h3>
                        <p class="text-muted mb-0">Customizable registration forms, automated confirmations, and seamless attendee experience.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Instant Notifications</h3>
                        <p class="text-muted mb-0">Keep everyone informed with automated email updates, reminders, and important announcements.</p>
                    </div>
                </div>
                
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm p-4 border-0">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                            <i class="fas fa-shield-alt text-primary"></i>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Secure & Reliable</h3>
                        <p class="text-muted mb-0">Enterprise-grade security to protect your data and provide a safe experience for all attendees.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5" style="padding: 6rem 0 !important; background: white;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">
                    <i class="fas fa-lightbulb me-2"></i>How It Works
                </span>
                <h2 class="display-5 fw-bold mb-3">Get Started in 3 Simple Steps</h2>
                <p class="text-muted lead" style="max-width: 700px; margin: 0 auto;">
                    Creating amazing events has never been easier. Follow these steps and you're on your way.
                </p>
            </div>
            
            <div class="row g-5 mt-4">
                <div class="col-12 col-md-4 text-center">
                    <div class="step-number">1</div>
                    <h3 class="h4 fw-bold mb-3">Create Your Event</h3>
                    <p class="text-muted">Sign up and fill in your event details. Add images, descriptions, date, location, and capacity in minutes.</p>
                </div>
                
                <div class="col-12 col-md-4 text-center">
                    <div class="step-number">2</div>
                    <h3 class="h4 fw-bold mb-3">Share & Promote</h3>
                    <p class="text-muted">Get a shareable link instantly. Spread the word on social media, email, or embed on your website.</p>
                </div>
                
                <div class="col-12 col-md-4 text-center">
                    <div class="step-number">3</div>
                    <h3 class="h4 fw-bold mb-3">Manage & Track</h3>
                    <p class="text-muted">Monitor registrations in real-time, communicate with attendees, and access powerful analytics.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light" style="padding: 5rem 0 !important;">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <div class="stat-number" id="statEvents">0</div>
                        <h3 class="h5 text-muted mb-0">Total Events</h3>
                        <p class="small text-muted mt-2 mb-0">Events created by our community</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <div class="stat-number" id="statAttendees">0</div>
                        <h3 class="h5 text-muted mb-0">Attendees Registered</h3>
                        <p class="small text-muted mt-2 mb-0">People attending amazing events</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 p-4 text-center">
                        <div class="stat-number" id="statVisits">0</div>
                        <h3 class="h5 text-muted mb-0">Platform Visits</h3>
                        <p class="small text-muted mt-2 mb-0">Engaged users exploring events</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-5 bg-light" style="padding: 5rem 0 !important;">
        <div class="container">
            <div class="cta-section text-white text-center position-relative">
                <div style="position: relative; z-index: 1;">
                    <h2 class="display-5 fw-bold mb-3">Ready to Create Your Next Event?</h2>
                    <p class="lead mb-4 opacity-90" style="max-width: 600px; margin: 0 auto;">
                        Join thousands of organizers who trust Event Planner to bring their events to life. Get started for free today!
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="/events.php" class="btn btn-light btn-lg px-5" style="border-radius: 50px; font-weight: 600;">
                            <i class="fas fa-calendar-plus me-2"></i>Create Your Event
                        </a>
                        <a href="/events.php" class="btn btn-outline-custom btn-lg px-5">
                            <i class="fas fa-compass me-2"></i>Explore Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Track visit
        fetch('/api/visits.php', { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ page_url: window.location.pathname }) 
        });

        // Carousel functionality
        const indicators = document.querySelector('#homeCarousel .carousel-indicators');
        const inner = document.querySelector('#homeCarousel .carousel-inner');

        async function loadSlides() {
            try {
                const res = await fetch('/api/events.php');
                if (!res.ok) return [];
                const data = await res.json();
                const events = (data.events || []).filter(e => !!e.image_path).map(e => {
                    const raw = String(e.image_path || '').trim();
                    const isAbs = /^https?:\/\//i.test(raw);
                    const normalized = isAbs ? raw : (raw.startsWith('/') ? raw : ('/' + raw));
                    return { ...e, _src: normalized };
                });
                const limited = events.slice(0, 5);
                if (limited.length > 0) return limited;
                
                // Fallback slides
                const fallback = [
                    { id: 's1', title: 'Welcome to Event Planner', location: 'Create your first event today', _src: '/assets/images/slide1.jpg' },
                    { id: 's2', title: 'Engage Your Audience', location: 'Beautiful event pages', _src: '/assets/images/slide2.jpeg' },
                    { id: 's3', title: 'Host Amazing Events', location: 'On-site or virtual', _src: '/assets/images/slide3.png' },
                    { id: 's4', title: 'Promote Everywhere', location: 'Reach more people', _src: '/assets/images/slide4.jpg' },
                    { id: 's5', title: 'Track Success', location: 'Powerful analytics', _src: '/assets/images/slide5.jpg' },
                ];
                return fallback;
            } catch {
                return [];
            }
        }

        function renderCarousel(items) {
            if (!inner || !indicators) return;
            inner.innerHTML = '';
            indicators.innerHTML = '';
            items.forEach((e, i) => {
                const isActive = i === 0 ? ' active' : '';
                const item = document.createElement('div');
                item.className = 'carousel-item' + isActive;
                item.innerHTML = `
                    <a href="${e.id ? `/event.php?id=${e.id}` : '#'}" class="d-block position-relative" style="text-decoration: none;">
                        <img class="w-100" src="${e._src || e.image_path}" alt="${e.title || ''}" style="height: 400px; object-fit: cover; border-radius: 20px;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.75) 100%); border-radius: 0 0 20px 20px;">
                            <h3 class="text-white h4 fw-bold mb-2">${e.title || ''}</h3>
                            <p class="text-white-50 mb-0"><i class="fas fa-map-marker-alt me-2"></i>${e.location || ''}</p>
                        </div>
                    </a>`;
                inner.appendChild(item);
                
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('data-bs-target', '#homeCarousel');
                btn.setAttribute('data-bs-slide-to', String(i));
                if (i === 0) btn.className = 'active';
                btn.setAttribute('aria-label', `Slide ${i+1}`);
                indicators.appendChild(btn);
            });
        }

        (async function initCarousel() {
            const items = await loadSlides();
            if (!items.length) return;
            renderCarousel(items);
        })();

        // Load and animate stats
        function animateNumber(element, target) {
            const duration = 2000;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = new Intl.NumberFormat().format(Math.floor(current));
            }, 16);
        }

        (async function loadStats() {
            try {
                const res = await fetch('/api/stats.php');
                if (!res.ok) return;
                const s = await res.json();
                
                const eventsEl = document.getElementById('statEvents');
                const attendeesEl = document.getElementById('statAttendees');
                const visitsEl = document.getElementById('statVisits');
                
                const heroEventsEl = document.getElementById('heroStatEvents');
                const heroAttendeesEl = document.getElementById('heroStatAttendees');
                const heroVisitsEl = document.getElementById('heroStatVisits');
                
                // Animate stats
                if (eventsEl) animateNumber(eventsEl, Number(s.events || 0));
                if (attendeesEl) animateNumber(attendeesEl, Number(s.attendees || 0));
                if (visitsEl) animateNumber(visitsEl, Number(s.visits || 0));
                
                if (heroEventsEl) animateNumber(heroEventsEl, Number(s.events || 0));
                if (heroAttendeesEl) animateNumber(heroAttendeesEl, Number(s.attendees || 0));
                if (heroVisitsEl) animateNumber(heroVisitsEl, Number(s.visits || 0));
            } catch (e) {
                console.error('Failed to load stats:', e);
            }
        })();
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>