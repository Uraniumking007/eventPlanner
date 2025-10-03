<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard - Event Planner</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php $showDashboardLink = true; include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">Organizer Dashboard</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-chart-line me-2"></i>Manage your events, track attendees, and view analytics
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex gap-2 justify-content-lg-end">
                            <a href="/organizer/attendees.php" class="btn btn-outline-custom">
                                <i class="fas fa-users me-2"></i>Attendees
                            </a>
                            <button id="newEventBtn" class="btn btn-gradient">
                                <i class="fas fa-plus me-2"></i>New Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Cards -->
        <div class="container py-4">
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-3 overflow-hidden h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="rounded-circle p-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary small">Total</span>
                            </div>
                            <div id="statMyEvents" class="h3 fw-bold mb-1">0</div>
                            <div class="text-muted small">My Events</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-3 overflow-hidden h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="rounded-circle p-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger small">Active</span>
                            </div>
                            <div id="statMyRegistrations" class="h3 fw-bold mb-1">0</div>
                            <div class="text-muted small">Total Registrations</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-3 overflow-hidden h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="rounded-circle p-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info small">Site-wide</span>
                            </div>
                            <div id="statVisits" class="h3 fw-bold mb-1">0</div>
                            <div class="text-muted small">Recent Visits</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row g-3">
                <!-- Events List -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white border-bottom py-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="h6 mb-0 fw-bold">
                                    <i class="fas fa-calendar-check text-primary me-2"></i>Your Events
                                </h2>
                                <button id="newEventBtn2" class="btn btn-sm btn-gradient">
                                    <i class="fas fa-plus me-1"></i>New
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div id="eventsList" class="row g-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Attendees Sidebar -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-gradient text-white border-0 py-2" style="background: var(--gradient-primary);">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="small mb-0 fw-bold">
                                    <i class="fas fa-users me-2"></i>Attendees
                                </h2>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <select id="attendeesEventSelect" class="form-select form-select-sm mb-3"></select>
                            <div id="attendeesList" class="attendees-list" style="max-height: 350px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        async function me() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }

        async function fetchAllEvents() {
            const res = await fetch('/api/events.php', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load events');
            const data = await res.json();
            return data.events || [];
        }

        async function fetchAttendees(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load attendees');
            return (await res.json()).attendees || [];
        }

        async function deleteEvent(id) {
            const res = await fetch('/api/events.php?id=' + id, { method: 'DELETE', credentials: 'same-origin' });
            if (!res.ok) throw new Error('Delete failed');
        }

        function formatDate(dateStr) {
            try {
                return new Date(dateStr).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            } catch {
                return dateStr;
            }
        }

        function eventCard(evt) {
            const count = Number(evt.registration_count || 0);
            return `
                <div class="col-12 col-md-6">
                    <div class="card border-0 h-100" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                        ${evt.image_path ? `
                            <div class="position-relative overflow-hidden" style="height: 140px;">
                                <img src="${evt.image_path}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;">
                                <div class="position-absolute top-0 start-0 end-0 p-2" style="background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 100%);">
                                    ${evt.category ? `<span class="badge bg-white text-dark small">${evt.category}</span>` : ''}
                                </div>
                            </div>
                        ` : `
                            <div class="position-relative" style="height: 140px; background: var(--gradient-primary);">
                                <div class="position-absolute top-0 start-0 p-2">
                                    ${evt.category ? `<span class="badge bg-white text-dark small">${evt.category}</span>` : ''}
                                </div>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-calendar-alt text-white" style="font-size: 2.5rem; opacity: 0.2;"></i>
                                </div>
                            </div>
                        `}
                        <div class="card-body p-3">
                            <h3 class="h6 fw-bold mb-3" style="color: #1f2937;">${evt.title}</h3>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: rgba(6, 182, 212, 0.1);">
                                        <i class="fas fa-calendar" style="font-size: 0.75rem; color: var(--primary-color);"></i>
                                    </div>
                                    <span class="small text-muted">${formatDate(evt.event_date)}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: rgba(239, 68, 68, 0.1);">
                                        <i class="fas fa-map-marker-alt" style="font-size: 0.75rem; color: #ef4444;"></i>
                                    </div>
                                    <span class="small text-muted text-truncate">${evt.location}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 pt-2" style="border-top: 2px solid #f3f4f6;">
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--gradient-primary);">
                                        <i class="fas fa-users text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small" style="color: var(--primary-color);">${count}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">attendees</div>
                                    </div>
                                </div>
                                <a href="/event.php?id=${evt.id}" class="btn btn-sm" style="background: rgba(6, 182, 212, 0.1); color: var(--primary-color); border-radius: 8px; font-weight: 500; font-size: 0.875rem;">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </div>

                            <div class="d-flex gap-2">
                                <button data-action="edit" data-id="${evt.id}" class="btn btn-sm flex-grow-1" style="background: var(--gradient-primary); color: white; border-radius: 8px; font-weight: 500;">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                                <button data-action="delete" data-id="${evt.id}" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderAttendeesList(list) {
            const root = document.getElementById('attendeesList');
            if (!list.length) {
                root.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-lg text-muted opacity-25 mb-2"></i>
                        <p class="small text-muted mb-0">No attendees yet</p>
                    </div>
                `;
                return;
            }
            root.innerHTML = list.map((u, index) => `
                <div class="d-flex align-items-center gap-2 py-2 ${index !== list.length - 1 ? 'border-bottom' : ''}">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.7rem; font-weight: 600;">
                        ${(u.username || u.email).substring(0, 2).toUpperCase()}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold small text-truncate">${u.username || 'User'}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">${u.email}</div>
                    </div>
                </div>
            `).join('');
        }

        async function render() {
            const [{ user }, allEvents] = await Promise.all([me(), fetchAllEvents()]);
            if (!user || user.role !== 'organizer') {
                window.location.href = '/login.php';
                return;
            }
            
            const myEvents = allEvents.filter(e => Number(e.organizer_id) === Number(user.id));

            // Update stats
            document.getElementById('statMyEvents').textContent = String(myEvents.length);

            // Compute total registrations
            let totalRegs = 0;
            for (const e of myEvents) {
                try {
                    const attendees = await fetchAttendees(e.id);
                    totalRegs += attendees.length;
                } catch {}
            }
            document.getElementById('statMyRegistrations').textContent = String(totalRegs);

            // Site-wide visits
            try {
                const sRes = await fetch('/api/stats.php');
                if (sRes.ok) {
                    const s = await sRes.json();
                    document.getElementById('statVisits').textContent = new Intl.NumberFormat().format(s.visits || 0);
                }
            } catch {}

            // Render events
            const list = document.getElementById('eventsList');
            if (!myEvents.length) {
                list.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-calendar-plus fa-3x text-muted opacity-25 mb-3"></i>
                        <h3 class="h5 fw-bold mb-2">No Events Yet</h3>
                        <p class="text-muted mb-3">Create your first event to get started!</p>
                        <button onclick="document.getElementById('newEventBtn').click()" class="btn btn-gradient">
                            <i class="fas fa-plus me-2"></i>Create Your First Event
                        </button>
                    </div>
                `;
            } else {
                list.innerHTML = myEvents.map(eventCard).join('');
                
                // Bind event actions
                list.querySelectorAll('button[data-action]')?.forEach(btn => {
                    const id = Number(btn.getAttribute('data-id'));
                    const action = btn.getAttribute('data-action');
                    
                    btn.addEventListener('click', async () => {
                        if (action === 'edit') {
                            window.location.href = '/organizer/edit.php?id=' + id;
                        } else if (action === 'delete') {
                            if (!confirm('Delete this event? This action cannot be undone.')) return;
                            try {
                                await deleteEvent(id);
                                render();
                            } catch {
                                alert('Delete failed');
                            }
                        }
                    });
                });
            }

            // Attendees panel
            const select = document.getElementById('attendeesEventSelect');
            if (myEvents.length) {
                select.innerHTML = '<option value="">Select an event...</option>' + 
                    myEvents.map(e => `<option value="${e.id}">${e.title}</option>`).join('');
                
                select.addEventListener('change', async () => {
                    const selectedId = Number(select.value);
                    if (!selectedId) {
                        renderAttendeesList([]);
                        return;
                    }
                    try {
                        renderAttendeesList(await fetchAttendees(selectedId));
                    } catch {
                        renderAttendeesList([]);
                    }
                });
                
                renderAttendeesList([]);
            } else {
                select.innerHTML = '<option>No events yet</option>';
                renderAttendeesList([]);
            }
        }

        // New event buttons
        document.getElementById('newEventBtn').addEventListener('click', () => {
            window.location.href = '/organizer/create.php';
        });
        document.getElementById('newEventBtn2').addEventListener('click', () => {
            window.location.href = '/organizer/create.php';
        });

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
