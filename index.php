<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner - Home</title>
    <meta name="description" content="Event Planner is a platform for creating and managing events.">
    
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

    <!-- Hero Section (Bootstrap) -->
    <section class="py-5 bg-light border-bottom">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <span class="badge rounded-pill text-bg-primary mb-3"><i class="fas fa-star me-2 text-warning"></i>Trusted by 1000+ organizers</span>
                    <h1 class="display-4 fw-bold mb-3">Effortless <span class="text-primary">Event Planning</span></h1>
                    <p class="lead text-secondary mb-4">From intimate gatherings to large-scale conferences, our platform provides everything you need to create, manage, and host successful events with confidence.</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                        <a href="/events.php" class="btn btn-primary btn-lg"><i class="fas fa-calendar-plus me-2"></i>Browse Events</a>
                        <a href="/login.php" class="btn btn-outline-secondary btn-lg"><i class="fas fa-user me-2"></i>Get Started</a>
                    </div>
                    <div class="row g-3 mt-5 pt-4 border-top">
                        <div class="col-4">
                            <div class="p-3 rounded bg-white shadow-sm">
                                <div id="statEvents" class="h4 mb-0 fw-bold">0</div>
                                <div class="text-muted small">Events</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-white shadow-sm">
                                <div id="statAttendees" class="h4 mb-0 fw-bold">0</div>
                                <div class="text-muted small">Attendees</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-white shadow-sm">
                                <div id="statVisits" class="h4 mb-0 fw-bold">0</div>
                                <div class="text-muted small">Visits</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slider -->
            <div class="slider-container mt-5">
                <div class="position-relative">
                    <div class="slider-wrapper rounded-4 overflow-hidden bg-white shadow"></div>
                    <button class="prev-button btn btn-light position-absolute top-50 start-0 translate-middle-y ms-3 rounded-circle" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                    <button class="next-button btn btn-light position-absolute top-50 end-0 translate-middle-y me-3 rounded-circle" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                    <div id="sliderDots" class="d-flex justify-content-center gap-2 mt-3"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section (Bootstrap) -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Why Choose Event Planner?</h2>
                <p class="text-secondary lead">Streamline your event management with modern, powerful features.</p>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3 mb-3" style="width:56px;height:56px">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3 class="h5">Easy Management</h3>
                            <p class="text-secondary mb-0">Create and manage events with an intuitive, clutter‑free interface.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-3 mb-3" style="width:56px;height:56px">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="h5">Attendee Tracking</h3>
                            <p class="text-secondary mb-0">Monitor registrations, manage capacity, and keep attendees informed.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-3 mb-3" style="width:56px;height:56px">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="h5">Analytics & Insights</h3>
                            <p class="text-secondary mb-0">Understand performance with clear stats and actionable insights.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="p-4 p-lg-5 rounded-4 bg-gradient" style="background:linear-gradient(135deg,#e9f2ff 0%, #f3e9ff 100%)">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h3 class="h4 fw-bold mb-1">Ready to host your next event?</h3>
                        <p class="text-secondary mb-0">Create an event in minutes and start collecting registrations.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/events.php" class="btn btn-primary"><i class="fas fa-calendar-plus me-2"></i>Create event</a>
                        <a href="/events.php" class="btn btn-outline-secondary">Explore events</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });

        // Navbar behavior (mobile toggle, active link, user area) is handled in includes/navbar.php

        const sliderWrapper = document.querySelector('.slider-wrapper');
        const prevButton = document.querySelector('.prev-button');
        const nextButton = document.querySelector('.next-button');
        const dotsContainer = document.getElementById('sliderDots');

        let currentIndex = 0;
        let slides = [];

        async function loadSlides() {
            try {
                const res = await fetch('/api/events.php');
                if (!res.ok) return [];
                const data = await res.json();
                const events = (data.events || []).filter(e => !!e.image_path);
                return events.slice(0, 5); // limit to 5 slides
            } catch {
                return [];
            }
        }

        function renderSlides(items) {
            sliderWrapper.innerHTML = items.map((e) => `
                <div class=\"slide-item\" style=\"float:left; width:100%\">\n                    <a href=\"/event.php?id=${e.id}\">\n                        <img class=\"img-fluid\" src=\"${e.image_path}\" alt=\"${e.title}\">\n                        <div class=\"slide-content\" style=\"background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.65) 100%);\">\n                            <h3 class=\"text-white h5 mb-1\">${e.title}</h3>\n                            <p class=\"text-white-50 small mb-0\">${e.location || ''}</p>\n                        </div>\n                    </a>\n                </div>
            `).join('');
            slides = Array.from(sliderWrapper.querySelectorAll('.slide-item'));
            renderDots(slides.length);
            updateSliderPosition();
        }

        function renderDots(n) {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = Array.from({ length: n }).map((_, i) => `
                <button type=\"button\" class=\"dot btn p-0 border-0 rounded-circle ${i === currentIndex ? 'bg-primary' : 'bg-secondary'}\" style=\"width:10px;height:10px\"></button>
            `).join('');
            Array.from(dotsContainer.children).forEach((dot, i) => {
                dot.addEventListener('click', () => goToSlide(i));
            });
        }

        function getSlideWidth() {
            const first = slides[0];
            return first ? first.clientWidth : 0;
        }

        function updateDots() {
            if (!dotsContainer) return;
            Array.from(dotsContainer.children).forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('bg-primary');
                    dot.classList.remove('bg-secondary');
                } else {
                    dot.classList.remove('bg-primary');
                    dot.classList.add('bg-secondary');
                }
            });
        }

        function updateSliderPosition() {
            const width = getSlideWidth();
            sliderWrapper.style.whiteSpace = 'nowrap';
            sliderWrapper.style.transform = `translateX(${-currentIndex * width}px)`;
            sliderWrapper.style.transition = 'transform 400ms ease';
            updateDots();
        }

        function goToSlide(index) {
            if (!slides.length) return;
            currentIndex = index;
            if (currentIndex < 0) currentIndex = slides.length - 1;
            if (currentIndex > slides.length - 1) currentIndex = 0;
            updateSliderPosition();
        }

        nextButton.addEventListener('click', () => goToSlide(currentIndex + 1));
        prevButton.addEventListener('click', () => goToSlide(currentIndex - 1));

        let autoPlayInterval = null;
        function startAutoPlay() {
            stopAutoPlay();
            autoPlayInterval = setInterval(() => goToSlide(currentIndex + 1), 4000);
        }
        function stopAutoPlay() {
            if (autoPlayInterval) clearInterval(autoPlayInterval);
        }

        sliderWrapper.addEventListener('mouseenter', stopAutoPlay);
        sliderWrapper.addEventListener('mouseleave', startAutoPlay);
        window.addEventListener('resize', updateSliderPosition);

        (async function initSlider() {
            const items = await loadSlides();
            if (!items.length) return; // keep static layout if no images
            renderSlides(items);
            startAutoPlay();
        })();

        // Load dynamic stats
        (async function loadStats() {
            try {
                const res = await fetch('/api/stats.php');
                if (!res.ok) return;
                const s = await res.json();
                const fmt = (n) => new Intl.NumberFormat().format(Number(n || 0));
                const eventsEl = document.getElementById('statEvents');
                const attendeesEl = document.getElementById('statAttendees');
                const visitsEl = document.getElementById('statVisits');
                if (eventsEl) eventsEl.textContent = fmt(s.events);
                if (attendeesEl) attendeesEl.textContent = fmt(s.attendees);
                if (visitsEl) visitsEl.textContent = fmt(s.visits);
            } catch (e) {
                // no-op
            }
        })();
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
