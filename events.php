<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner - Explore Events</title>
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
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="section-badge bg-white text-primary mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>Discover Events
                        </span>
                        <h1 class="h2 fw-bold mb-2">Find Your Next Experience</h1>
                        <p class="mb-0 opacity-90">
                            Browse through amazing events happening around you. From workshops to conferences, find what excites you.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill">
                            <i class="fas fa-ticket-alt me-2"></i>
                            <span id="eventsCount" class="fw-bold">0</span>
                            <span class="ms-1 small">events available</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="py-3 bg-light border-bottom">
            <div class="container">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="searchInput" class="form-label fw-semibold small">
                                    <i class="fas fa-search me-1"></i>Search Events
                                </label>
                                <input id="searchInput" type="text" placeholder="Type to search..." class="form-control">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="categorySelect" class="form-label fw-semibold small">
                                    <i class="fas fa-filter me-1"></i>Category
                                </label>
                                <select id="categorySelect" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="Corporate">Corporate</option>
                                    <option value="Social">Social</option>
                                    <option value="Tech">Tech</option>
                                    <option value="Workshop">Workshop</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="sortSelect" class="form-label fw-semibold small">
                                    <i class="fas fa-sort me-1"></i>Sort By
                                </label>
                                <select id="sortSelect" class="form-select">
                                    <option value="date_asc">Date: Soonest First</option>
                                    <option value="date_desc">Date: Latest First</option>
                                    <option value="title_asc">Title: A-Z</option>
                                    <option value="title_desc">Title: Z-A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Events Grid -->
        <section class="py-4">
            <div class="container">
                <div id="eventsList" class="row g-3"></div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }

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
            try { 
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            } catch { 
                return dateStr; 
            }
        }

        function daysUntil(dateStr) {
            try {
                const now = new Date();
                const end = new Date(dateStr);
                const ms = end.setHours(23,59,59,999) - now.getTime();
                const days = Math.ceil(ms / (1000 * 60 * 60 * 24));
                return isNaN(days) ? null : days;
            } catch { 
                return null; 
            }
        }

        function eventCard(evt, user) {
            const count = Number(evt.registration_count || 0);
            const dLeft = daysUntil(evt.event_date);
            const isSuspended = Number(evt.suspended || 0) === 1;
            
            let statusBadge = '';
            let statusColor = '';
            
            if (isSuspended) {
                statusBadge = '<span class="badge" style="background: #fbbf24; color: #78350f;"><i class="fas fa-ban me-1"></i>Suspended</span>';
                statusColor = '#fbbf24';
            } else if (dLeft !== null && dLeft >= 0) {
                statusBadge = '<span class="badge" style="background: linear-gradient(135deg, #14b8a6, #0d9488); color: white;"><i class="fas fa-check-circle me-1"></i>Open</span>';
                statusColor = '#14b8a6';
            } else {
                statusBadge = '<span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>Closed</span>';
                statusColor = '#6b7280';
            }

            return `
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 overflow-hidden" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                        ${evt.image_path ? `
                            <div class="position-relative overflow-hidden" style="height: 200px;">
                                <img src="${evt.image_path}" alt="${evt.title}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;">
                                <div class="position-absolute top-0 start-0 end-0 p-3" style="background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 100%);">
                                    <div class="d-flex justify-content-between align-items-start">
                                        ${evt.category ? `<span class="badge bg-white text-dark">${evt.category}</span>` : '<span></span>'}
                                        ${statusBadge}
                                    </div>
                                </div>
                                ${dLeft !== null && dLeft >= 0 && !isSuspended ? `
                                    <div class="position-absolute bottom-0 end-0 m-3">
                                        <div class="badge" style="background: rgba(6, 182, 212, 0.95); color: white; font-weight: 600; padding: 6px 12px;">
                                            <i class="fas fa-clock me-1"></i>${dLeft} ${dLeft === 1 ? 'day' : 'days'} left
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        ` : `
                            <div class="position-relative" style="height: 200px; background: var(--gradient-primary);">
                                <div class="position-absolute top-0 start-0 end-0 p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        ${evt.category ? `<span class="badge bg-white text-dark">${evt.category}</span>` : '<span></span>'}
                                        ${statusBadge}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-calendar-alt text-white" style="font-size: 3.5rem; opacity: 0.2;"></i>
                                </div>
                                ${dLeft !== null && dLeft >= 0 && !isSuspended ? `
                                    <div class="position-absolute bottom-0 end-0 m-3">
                                        <div class="badge bg-white text-dark" style="font-weight: 600; padding: 6px 12px;">
                                            <i class="fas fa-clock me-1"></i>${dLeft} ${dLeft === 1 ? 'day' : 'days'} left
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        `}
                        <div class="card-body p-4">
                            <h3 class="h5 fw-bold mb-3" style="color: #1f2937;">${evt.title}</h3>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(6, 182, 212, 0.1);">
                                        <i class="fas fa-calendar" style="font-size: 0.875rem; color: var(--primary-color);"></i>
                                    </div>
                                    <span class="small text-muted">${formatDate(evt.event_date)}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.1);">
                                        <i class="fas fa-map-marker-alt" style="font-size: 0.875rem; color: #ef4444;"></i>
                                    </div>
                                    <span class="small text-muted text-truncate">${evt.location}</span>
                                </div>
                            </div>

                            ${isSuspended && evt.suspend_reason ? `
                                <div class="alert alert-warning py-2 px-3 mb-3" style="border-radius: 8px; font-size: 0.875rem;">
                                    <i class="fas fa-info-circle me-1"></i>${evt.suspend_reason}
                                </div>
                            ` : ''}

                            <div class="d-flex align-items-center justify-content-between pt-3" style="border-top: 2px solid #f3f4f6;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: var(--gradient-primary);">
                                        <i class="fas fa-users text-white" style="font-size: 0.875rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold reg-count" data-event-id="${evt.id}" style="color: var(--primary-color);">${count}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">attendees</div>
                                    </div>
                                </div>
                                <a href="/event.php?id=${evt.id}" class="btn btn-sm px-3" style="background: var(--gradient-primary); color: white; border-radius: 8px; font-weight: 500;">
                                    View Event <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
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
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-search" style="font-size: 4rem; color: var(--primary-color); opacity: 0.3;"></i>
                                </div>
                                <h2 class="h4 fw-bold mb-2">No Events Found</h2>
                                <p class="text-muted mb-4">Try adjusting your search or filters to find what you're looking for.</p>
                                <button class="btn btn-gradient" onclick="document.getElementById('searchInput').value=''; document.getElementById('categorySelect').value=''; render();">
                                    <i class="fas fa-redo me-2"></i>Clear Filters
                                </button>
                            </div>
                        </div>`;
                } else {
                    list.innerHTML = filtered.map(e => eventCard(e, user)).join('');
                }
                
                if (countEl) countEl.textContent = String(filtered.length);
            };

            // Bind inputs once
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const sortSelect = document.getElementById('sortSelect');
            
            if (searchInput && !searchInput.hasAttribute('data-bound')) {
                searchInput.addEventListener('input', renderList);
                searchInput.setAttribute('data-bound', 'true');
            }
            if (categorySelect && !categorySelect.hasAttribute('data-bound')) {
                categorySelect.addEventListener('change', renderList);
                categorySelect.setAttribute('data-bound', 'true');
            }
            if (sortSelect && !sortSelect.hasAttribute('data-bound')) {
                sortSelect.addEventListener('change', renderList);
                sortSelect.setAttribute('data-bound', 'true');
            }

            renderList();
        }

        // Track visit
        fetch('/api/visits.php', { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ page_url: window.location.pathname }) 
        });

        render();

        // Live Reg Count Update using jQuery + AJAX (4.2)
        (function setupLiveRegistrationCounts() {
            function updateCountsOnce() {
                const ids = new Set();
                $('.reg-count').each(function () {
                    const id = Number($(this).data('event-id'));
                    if (!Number.isNaN(id)) ids.add(id);
                });
                if (ids.size === 0) return;
                
                ids.forEach((id) => {
                    $.getJSON(`/api/events.php?id=${id}`)
                        .done((data) => {
                            const count = Number(data?.event?.registration_count || 0);
                            $(`.reg-count[data-event-id="${id}"]`).text(String(count));
                        });
                });
            }
            
            setTimeout(updateCountsOnce, 600);
            setInterval(updateCountsOnce, 10000);
            
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
