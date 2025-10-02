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
        <section class="py-4 py-lg-5 border-bottom" style="background:linear-gradient(135deg,#f8fbff 0%, #f9f7ff 100%)">
            <div class="container">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h1 class="display-6 fw-bold mb-1">Admin Dashboard</h1>
                        <p class="text-secondary mb-0">Manage the platform: users, events, and site metrics.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-dark" href="/events.php"><i class="fas fa-calendar-alt me-2"></i>View All Events</a>
                        <a class="btn btn-outline-secondary" href="/profile.php"><i class="fas fa-user-cog me-2"></i>Your Profile</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-2">
                    <div class="border rounded-3 shadow-sm bg-white p-4 text-center h-100">
                        <div class="text-secondary small">Events</div>
                        <div id="statEvents" class="h3 fw-bold mb-0">0</div>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="border rounded-3 shadow-sm bg-white p-4 text-center h-100">
                        <div class="text-secondary small">Registrations</div>
                        <div id="statRegistrations" class="h3 fw-bold mb-0">0</div>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="border rounded-3 shadow-sm bg-white p-4 text-center h-100">
                        <div class="text-secondary small">Attendees</div>
                        <div id="statAttendees" class="h3 fw-bold mb-0">0</div>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="border rounded-3 shadow-sm bg-white p-4 text-center h-100">
                        <div class="text-secondary small">Visits</div>
                        <div id="statVisits" class="h3 fw-bold mb-0">0</div>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="border rounded-3 shadow-sm bg-white p-4 text-center h-100">
                        <div class="text-secondary small">Organizers</div>
                        <div id="statOrganizers" class="h3 fw-bold mb-0">0</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h2 class="h6 mb-0">Recent Events</h2>
                                <a class="btn btn-sm btn-outline-secondary" href="/events.php">View all</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Organizer</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th class="text-end">Registrations</th>
                                        </tr>
                                    </thead>
                                    <tbody id="adminEventsTable">
                                        <tr><td colspan="5" class="text-secondary small">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            if (!items.length) { body.innerHTML = '<tr><td colspan="5" class="text-secondary small">No events found.</td></tr>'; return; }
            body.innerHTML = items.slice(0, 10).map(e => `
                <tr>
                    <td>${e.title}</td>
                    <td>${e.organizer_name || e.organizer_id}</td>
                    <td>${fmtDate(e.event_date)}</td>
                    <td>${e.location || ''}</td>
                    <td class="text-end">${Number(e.registration_count || 0)}</td>
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


