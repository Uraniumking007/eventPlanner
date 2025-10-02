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
            <!-- Carousel -->
            <div id="homeCarousel" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-indicators"></div>
                <div class="carousel-inner rounded-4 overflow-hidden bg-white shadow"></div>
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
                // Fallback to built-in slides when no event images available
                const fallback = [
                    { id: 's1', title: 'Welcome to Event Planner', location: 'Create your first event', _src: '/assets/images/slide1.jpg' },
                    { id: 's2', title: 'Engage Your Audience', location: 'Share beautiful visuals', _src: '/assets/images/slide2.jpeg' },
                    { id: 's3', title: 'Host Great Events', location: 'On-site or virtual', _src: '/assets/images/slide3.png' },
                    { id: 's4', title: 'Promote Easily', location: 'Reach more attendees', _src: '/assets/images/slide4.jpg' },
                    { id: 's5', title: 'Analyze Results', location: 'Insights that matter', _src: '/assets/images/slide5.jpg' },
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
                    <a href="${e.id ? `/event.php?id=${e.id}` : '#'}" class="d-block position-relative">
                        <div class="ratio ratio-16x9">
                            <img class="w-100 h-100" src="${e._src || e.image_path}" alt="${e.title || ''}" style="object-fit:cover;">
                        </div>
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.65) 100%);">
                            <h3 class="text-white h5 mb-1">${e.title || ''}</h3>
                            <p class="text-white-50 small mb-0">${e.location || ''}</p>
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
            // Bootstrap 5 auto-initializes via data attributes; no manual init needed
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
