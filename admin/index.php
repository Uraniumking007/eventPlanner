<?php
declare(strict_types=1);

require_once __DIR__ . '/../api/init.php';

// Gate: only admins can access admin
$user = $_SESSION['user'] ?? null;
if (!$user || ($user['role'] ?? '') !== 'admin') {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Event Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="h3 fw-bold mb-2">Admin Dashboard</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-shield-alt me-2"></i>Manage the platform: users, events, and site metrics
                        </p>
                    </div>
                    <div class="col-lg-6 mt-3 mt-lg-0">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a class="btn btn-outline-custom btn-sm" href="/admin/users.php">
                                <i class="fas fa-users-cog me-1"></i>Users
                            </a>
                            <a class="btn btn-outline-custom btn-sm" href="/admin/events.php">
                                <i class="fas fa-calendar-alt me-1"></i>Events
                            </a>
                            <a class="btn btn-outline-custom btn-sm" href="/admin/audit.php">
                                <i class="fas fa-clipboard-list me-1"></i>Audit Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt text-white small"></i>
                            </div>
                            <div id="statEvents" class="h4 fw-bold mb-1">0</div>
                            <div class="text-muted small">Events</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; background: var(--gradient-secondary); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-ticket-alt text-white small"></i>
                            </div>
                            <div id="statRegistrations" class="h4 fw-bold mb-1">0</div>
                            <div class="text-muted small">Registrations</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; background: var(--gradient-success); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-white small"></i>
                            </div>
                            <div id="statAttendees" class="h4 fw-bold mb-1">0</div>
                            <div class="text-muted small">Attendees</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-eye text-white small"></i>
                            </div>
                            <div id="statVisits" class="h4 fw-bold mb-1">0</div>
                            <div class="text-muted small">Visits</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3 text-center">
                            <div class="rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie text-white small"></i>
                            </div>
                            <div id="statOrganizers" class="h4 fw-bold mb-1">0</div>
                            <div class="text-muted small">Organizers</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Events Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="h6 mb-1 fw-bold">
                                <i class="fas fa-calendar-check text-primary me-2"></i>Recent Events
                            </h2>
                            <div class="small text-muted">Latest events on the platform</div>
                        </div>
                        <a class="btn btn-sm btn-gradient" href="/events.php">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small text-muted fw-semibold">Title</th>
                                    <th class="small text-muted fw-semibold">Organizer</th>
                                    <th class="small text-muted fw-semibold">Date</th>
                                    <th class="small text-muted fw-semibold">Location</th>
                                    <th class="small text-muted fw-semibold text-end">Registrations</th>
                                </tr>
                            </thead>
                            <tbody id="adminEventsTable">
                                <tr><td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading events...
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        async function fetchEvents() {
            const res = await fetch('/api/events.php', { credentials: 'same-origin' });
            if (!res.ok) return [];
            const data = await res.json();
            return data.events || [];
        }

        function fmtDate(d) { try { return new Date(d).toLocaleString(); } catch { return d; } }

        function renderEventsTable(items) {
            const body = document.getElementById('adminEventsTable');
            if (!body) return;
            if (!items.length) { 
                body.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>No events found.</td></tr>'; 
                return; 
            }
            body.innerHTML = items.slice(0, 10).map(e => `
                <tr>
                    <td class="fw-semibold small">${e.title}</td>
                    <td class="small text-muted">${e.organizer_name || e.organizer_id}</td>
                    <td class="small text-nowrap text-muted">${fmtDate(e.event_date)}</td>
                    <td class="small text-muted">${e.location || ''}</td>
                    <td class="text-end">
                        <span class="badge bg-primary rounded-pill">${Number(e.registration_count || 0)}</span>
                    </td>
                </tr>
            `).join('');
        }

        (async function loadStats(){
            try {
                const res = await fetch('/api/stats.php', { credentials: 'same-origin' });
                if (!res.ok) return;
                const s = await res.json();
                const fmt = (n) => new Intl.NumberFormat().format(Number(n || 0));
                const set = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = fmt(v); };
                set('statEvents', s.events);
                set('statRegistrations', s.registrations);
                set('statAttendees', s.attendees);
                set('statVisits', s.visits);
                set('statOrganizers', s.organizers);
            } catch {}
        })();

        (async function loadEvents(){
            try { renderEventsTable(await fetchEvents()); } catch { renderEventsTable([]); }
        })();

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


