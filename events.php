<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner - Events</title>
    <meta name="description" content="Explore upcoming events and find the perfect one for you.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery for AJAX (4.2 requirement) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero / Header -->
        <section class="py-4 py-lg-5 border-bottom" style="background:linear-gradient(135deg,#f8fbff 0%, #f9f7ff 100%)">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8">
                        <h1 class="display-6 fw-bold mb-2">Explore Events</h1>
                        <p class="text-secondary mb-0">Find and register for upcoming events that match your interests.</p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <span class="text-muted small">Showing <span id="eventsCount">0</span> events</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <!-- Filters Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="searchInput" class="form-label small text-muted">Search</label>
                            <input id="searchInput" type="text" placeholder="Search events..." class="form-control">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="categorySelect" class="form-label small text-muted">Category</label>
                            <select id="categorySelect" class="form-select">
                                <option value="">All categories</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Social">Social</option>
                                <option value="Tech">Tech</option>
                                <option value="Workshop">Workshop</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="sortSelect" class="form-label small text-muted">Sort by</label>
                            <select id="sortSelect" class="form-select">
                                <option value="date_asc">Date: Soonest first</option>
                                <option value="date_desc">Date: Latest first</option>
                                <option value="title_asc">Title: A-Z</option>
                                <option value="title_desc">Title: Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            <div id="eventsList" class="row g-3 g-lg-4"></div>
        </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Navbar behavior (mobile toggle, active link, avatar menu) is handled in includes/navbar.php

        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }
        // Removed dummyEvents; page uses dynamic data from /api/events.php

        async function fetchEvents() {
            const res = await fetch('/api/events.php');
            if (!res.ok) throw new Error('Failed to load events');
            const data = await res.json();
            return data.events || [];
        }
        async function register(eventId) {
            const res = await fetch('/api/registrations.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ event_id: eventId })
            });
            if (!res.ok) {
                alert('Registration failed');
                return;
            }
            render();
        }
        async function unregister(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, {
                method: 'DELETE',
                credentials: 'same-origin'
            });
            if (!res.ok) {
                alert('Unregister failed');
                return;
            }
            render();
        }
        function formatDate(dateStr) {
            try { return new Date(dateStr).toLocaleDateString(); } catch { return dateStr; }
        }
        function daysUntil(dateStr) {
            try {
                const now = new Date();
                const end = new Date(dateStr);
                const ms = end.setHours(23,59,59,999) - now.getTime();
                const days = Math.ceil(ms / (1000 * 60 * 60 * 24));
                return isNaN(days) ? null : days;
            } catch { return null; }
        }
        function eventCard(evt, user) {
            const actions = [];
            actions.push(`<a href="/event.php?id=${evt.id}" class="btn btn-sm btn-outline-secondary">More info</a>`);
            const count = Number(evt.registration_count || 0);
            const dLeft = daysUntil(evt.event_date);
            const isSuspended = Number(evt.suspended || 0) === 1;
            const statusLabel = isSuspended
                ? `<span class=\"badge text-bg-warning\">Suspended</span>`
                : (dLeft != null && dLeft >= 0
                    ? `<span class=\"badge text-bg-success\">Open</span>`
                    : `<span class=\"badge text-bg-danger\">Closed</span>`);
            const badge = dLeft != null ? `<span class=\"badge rounded-pill text-bg-secondary ms-2\">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
            return `
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        ${evt.image_path ? `<img src="${evt.image_path}" alt="${evt.title}" class="card-img-top" style="height: 200px; object-fit: cover;">` : ''}
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h3 class="h6 mb-0">${evt.title}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    ${evt.category ? `<span class=\"badge text-bg-light\">${evt.category}</span>` : ''}
                                    ${statusLabel}
                                </div>
                            </div>
                            <p class="text-secondary small mb-2">${formatDate(evt.event_date)} • ${evt.location} ${badge}</p>
                            ${isSuspended ? `<div class=\"alert alert-warning py-2 small mb-2\"><strong>Suspended</strong>${evt.suspend_reason ? ` — Reason: ${evt.suspend_reason}` : ''}</div>` : ''}
                            <div class="text-muted small mb-2">Registrations: <span class="fw-medium reg-count" data-event-id="${evt.id}">${count}</span></div>
                            <p class="mb-3 small">${evt.description || 'No description available.'}</p>
                            <div class="d-flex flex-wrap gap-2">${actions.join(' ')}</div>
                        </div>
                    </div>
                </div>
            `;
        }
        function applyFilters(events) {
            const q = (document.getElementById('searchInput').value || '').toLowerCase();
            const cat = document.getElementById('categorySelect').value;
            const sort = document.getElementById('sortSelect').value;

            let filtered = events.filter(e =>
                (!q || `${e.title} ${e.location} ${e.description}`.toLowerCase().includes(q)) &&
                (!cat || e.category === cat)
            );

            filtered = filtered.sort((a, b) => {
                if (sort === 'date_asc') return new Date(a.event_date) - new Date(b.event_date);
                if (sort === 'date_desc') return new Date(b.event_date) - new Date(a.event_date);
                if (sort === 'title_asc') return String(a.title).localeCompare(String(b.title));
                if (sort === 'title_desc') return String(b.title).localeCompare(String(a.title));
                return 0;
            });
            return filtered;
        }

        async function render() {
            const [{ user }, events] = await Promise.all([fetchMe(), fetchEvents()]);
            const list = document.getElementById('eventsList');
            const countEl = document.getElementById('eventsCount');
            const renderList = () => {
                const filtered = applyFilters(events);
                if (!filtered.length) {
                    list.innerHTML = `
                        <div class=\"col-12\">
                            <div class=\"text-center py-5 border rounded-3 bg-light\">
                                <div class=\"display-6 mb-2\">😕</div>
                                <h2 class=\"h5 mb-2\">No events found</h2>
                                <p class=\"text-secondary mb-0\">Try adjusting your filters or check back later.</p>
                            </div>
                        </div>`;
                } else {
                    list.innerHTML = filtered.map(e => eventCard(e, user)).join('');
                }
                list.querySelectorAll('button[data-action]')?.forEach(btn => {
                    const id = Number(btn.getAttribute('data-id'));
                    const action = btn.getAttribute('data-action');
                    btn.addEventListener('click', () => action === 'register' ? register(id) : unregister(id));
                });
                if (countEl) countEl.textContent = String(filtered.length);
            };

            // Bind inputs once
            document.getElementById('searchInput').addEventListener('input', renderList);
            document.getElementById('categorySelect').addEventListener('change', renderList);
            document.getElementById('sortSelect').addEventListener('change', renderList);

            renderList();
        }
        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        render();

        // Live Reg Count Update using jQuery + AJAX (4.2)
        (function setupLiveRegistrationCounts() {
            function updateCountsOnce() {
                // Collect visible event IDs from DOM
                const ids = new Set();
                $('.reg-count').each(function () {
                    const id = Number($(this).data('event-id'));
                    if (!Number.isNaN(id)) ids.add(id);
                });
                if (ids.size === 0) return;
                // Fetch each event's latest count and update span
                ids.forEach((id) => {
                    $.getJSON(`/api/events.php?id=${id}`)
                        .done((data) => {
                            const count = Number(data?.event?.registration_count || 0);
                            $(`.reg-count[data-event-id="${id}"]`).text(String(count));
                        });
                });
            }
            // Initial after first render
            setTimeout(updateCountsOnce, 600);
            // Poll every 10 seconds
            setInterval(updateCountsOnce, 10000);
            // Nudge an update shortly after user actions
            document.addEventListener('click', (e) => {
                const t = e.target;
                if (t && t.matches && t.matches('button[data-action="register"], button[data-action="unregister"]')) {
                    setTimeout(updateCountsOnce, 1200);
                }
            });
        })();
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
