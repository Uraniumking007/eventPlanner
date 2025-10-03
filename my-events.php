<?php
declare(strict_types=1);
require_once __DIR__ . '/api/init.php';

$user = $_SESSION['user'] ?? null;
if (!$user) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Event Planner</title>
    
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
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">My Registered Events</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-ticket-alt me-2"></i>View and manage events you've registered for
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a class="btn btn-outline-custom" href="/events.php">
                            <i class="fas fa-search me-2"></i>Browse Events
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-primary);">
                                    <i class="fas fa-ticket-alt text-white fa-lg"></i>
                                </div>
                                <span class="badge" style="background: rgba(6, 182, 212, 0.1); color: var(--primary-color);">Total</span>
                            </div>
                            <h3 class="h2 fw-bold mb-1" id="statTotal" style="color: var(--primary-color);">0</h3>
                            <p class="text-muted mb-0 small">Total Registrations</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-secondary);">
                                    <i class="fas fa-calendar-check text-white fa-lg"></i>
                                </div>
                                <span class="badge" style="background: rgba(20, 184, 166, 0.1); color: var(--success-color);">Active</span>
                            </div>
                            <h3 class="h2 fw-bold mb-1" id="statUpcoming" style="color: var(--success-color);">0</h3>
                            <p class="text-muted mb-0 small">Upcoming Events</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: linear-gradient(135deg, #94a3b8, #64748b);">
                                    <i class="fas fa-history text-white fa-lg"></i>
                                </div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Past</span>
                            </div>
                            <h3 class="h2 fw-bold mb-1 text-secondary" id="statPast">0</h3>
                            <p class="text-muted mb-0 small">Past Events</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card border-0 mb-4" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="searchInput" class="form-label small fw-semibold">
                                <i class="fas fa-search me-1" style="color: var(--primary-color);"></i>Search Events
                            </label>
                            <input id="searchInput" type="text" class="form-control" placeholder="Search by title or location...">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="filterSelect" class="form-label small fw-semibold">
                                <i class="fas fa-filter me-1" style="color: var(--primary-color);"></i>Filter
                            </label>
                            <select id="filterSelect" class="form-select">
                                <option value="all">All Events</option>
                                <option value="upcoming">Upcoming Only</option>
                                <option value="past">Past Only</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events List -->
            <div id="eventsList" class="row g-3"></div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }

        async function fetchAllEvents() {
            const res = await fetch('/api/events.php', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load events');
            return (await res.json()).events || [];
        }

        async function fetchMyRegistrations() {
            const res = await fetch('/api/registrations.php', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load registrations');
            return (await res.json()).my_registrations || [];
        }

        async function unregister(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, {
                method: 'DELETE',
                credentials: 'same-origin'
            });
            if (!res.ok) {
                alert('Failed to unregister');
                return false;
            }
            return true;
        }

        function formatDate(dateStr) {
            try {
                return new Date(dateStr).toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
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

        function eventCard(evt) {
            const dLeft = daysUntil(evt.event_date);
            const isPast = dLeft !== null && dLeft < 0;
            const isSuspended = Number(evt.suspended || 0) === 1;
            
            let statusBadge = '';
            if (isSuspended) {
                statusBadge = '<span class="badge" style="background: #fbbf24; color: #78350f;"><i class="fas fa-ban me-1"></i>Suspended</span>';
            } else if (isPast) {
                statusBadge = '<span class="badge bg-secondary"><i class="fas fa-check me-1"></i>Completed</span>';
            } else {
                statusBadge = '<span class="badge" style="background: var(--gradient-secondary); color: white;"><i class="fas fa-calendar-check me-1"></i>Upcoming</span>';
            }

            return `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                        ${evt.image_path ? `
                            <div class="position-relative overflow-hidden" style="height: 200px;">
                                <img src="${evt.image_path}" alt="${evt.title}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;">
                                <div class="position-absolute top-0 start-0 end-0 p-3" style="background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, transparent 100%);">
                                    <div class="d-flex justify-content-between align-items-start">
                                        ${evt.category ? `<span class="badge bg-white text-dark">${evt.category}</span>` : '<span></span>'}
                                        ${statusBadge}
                                    </div>
                                </div>
                                ${!isPast && !isSuspended && dLeft !== null ? `
                                    <div class="position-absolute bottom-0 end-0 m-3">
                                        <div class="badge" style="background: rgba(6, 182, 212, 0.95); color: white; font-weight: 600; padding: 8px 14px;">
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
                                ${!isPast && !isSuspended && dLeft !== null ? `
                                    <div class="position-absolute bottom-0 end-0 m-3">
                                        <div class="badge bg-white text-dark" style="font-weight: 600; padding: 8px 14px;">
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
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.1);">
                                        <i class="fas fa-map-marker-alt" style="font-size: 0.875rem; color: #ef4444;"></i>
                                    </div>
                                    <span class="small text-muted text-truncate">${evt.location}</span>
                                </div>
                                ${evt.organizer_name ? `
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(139, 92, 246, 0.1);">
                                            <i class="fas fa-user-tie" style="font-size: 0.875rem; color: #8b5cf6;"></i>
                                        </div>
                                        <span class="small text-muted">@${evt.organizer_name}</span>
                                    </div>
                                ` : ''}
                            </div>

                            <div class="d-flex gap-2 pt-3" style="border-top: 2px solid #f3f4f6;">
                                <a href="/event.php?id=${evt.id}" class="btn btn-sm flex-grow-1" style="background: var(--gradient-primary); color: white; border-radius: 8px; font-weight: 500;">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                ${!isSuspended && !isPast ? `
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" data-action="unregister" data-id="${evt.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        let allMyEvents = [];

        async function render() {
            const [{ user }, myRegistrations] = await Promise.all([
                fetchMe(),
                fetchMyRegistrations()
            ]);

            if (!user) {
                window.location.href = '/login.php';
                return;
            }

            // The API already returns full event data for registered events
            allMyEvents = myRegistrations;

            // Update stats
            const upcoming = allMyEvents.filter(e => {
                const dLeft = daysUntil(e.event_date);
                return dLeft !== null && dLeft >= 0 && Number(e.suspended || 0) === 0;
            });
            const past = allMyEvents.filter(e => {
                const dLeft = daysUntil(e.event_date);
                return dLeft !== null && dLeft < 0;
            });

            document.getElementById('statTotal').textContent = String(allMyEvents.length);
            document.getElementById('statUpcoming').textContent = String(upcoming.length);
            document.getElementById('statPast').textContent = String(past.length);

            applyFilters();
        }

        function applyFilters() {
            const search = (document.getElementById('searchInput').value || '').toLowerCase();
            const filter = document.getElementById('filterSelect').value;

            let filtered = allMyEvents.filter(e => {
                const matchesSearch = !search || 
                    String(e.title || '').toLowerCase().includes(search) ||
                    String(e.location || '').toLowerCase().includes(search);

                const dLeft = daysUntil(e.event_date);
                const isPast = dLeft !== null && dLeft < 0;
                const isUpcoming = dLeft !== null && dLeft >= 0 && Number(e.suspended || 0) === 0;

                let matchesFilter = true;
                if (filter === 'upcoming') matchesFilter = isUpcoming;
                if (filter === 'past') matchesFilter = isPast;

                return matchesSearch && matchesFilter;
            });

            const list = document.getElementById('eventsList');
            
            if (!filtered.length) {
                list.innerHTML = `
                    <div class="col-12">
                        <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-ticket-alt fa-3x mb-3" style="color: var(--primary-color); opacity: 0.3;"></i>
                                <h3 class="h5 fw-bold mb-2">No Events Found</h3>
                                <p class="text-muted mb-4">
                                    ${allMyEvents.length === 0 ? "You haven't registered for any events yet." : "No events match your current filters."}
                                </p>
                                <a href="/events.php" class="btn btn-gradient">
                                    <i class="fas fa-search me-2"></i>Browse Events
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                list.innerHTML = filtered.map(eventCard).join('');

                // Bind unregister buttons
                list.querySelectorAll('button[data-action="unregister"]').forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const eventId = Number(this.getAttribute('data-id'));
                        if (!confirm('Are you sure you want to unregister from this event?')) return;
                        
                        const success = await unregister(eventId);
                        if (success) {
                            render();
                        }
                    });
                });
            }
        }

        // Bind filter inputs
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('filterSelect').addEventListener('change', applyFilters);

        // Track visit
        fetch('/api/visits.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ page_url: window.location.pathname })
        });

        // Initialize
        render();
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

