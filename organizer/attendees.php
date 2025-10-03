<?php
declare(strict_types=1);
require_once __DIR__ . '/../api/init.php';

$user = $_SESSION['user'] ?? null;
if (!$user || ($user['role'] ?? '') !== 'organizer') {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees - Organizer</title>
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
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">Event Attendees</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-users me-2"></i>View and manage attendees for your events
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a class="btn btn-outline-custom" href="/dashboard.php">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold">
                        <i class="fas fa-calendar-alt me-1"></i>Select Event
                    </label>
                    <select id="eventSelect" class="form-select"></select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold">
                        <i class="fas fa-search me-1"></i>Search Attendees
                    </label>
                    <input id="searchInput" type="text" class="form-control" placeholder="Name or email">
                </div>
            </div>
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
                <div class="card-header border-0 py-4" style="background: var(--gradient-primary); border-radius: 16px 16px 0 0;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="text-white">
                            <h2 class="h5 mb-1 fw-bold">
                                <i class="fas fa-users me-2"></i>Attendees List
                            </h2>
                            <div class="small opacity-90" id="count">0 attendees</div>
                        </div>
                        <button id="exportCsvBtn" class="btn btn-light shadow-sm" style="border-radius: 8px; font-weight: 500;">
                            <i class="fas fa-file-csv me-2"></i>Export CSV
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" id="attendeesContainer">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-info-circle fa-2x mb-3" style="opacity: 0.3;"></i>
                        <p class="mb-0">Select an event to view attendees</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        async function me() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }
        async function fetchAllEvents() {
            const res = await fetch('/api/events.php', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load events');
            return (await res.json()).events || [];
        }
        async function fetchAttendees(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed');
            return (await res.json()).attendees || [];
        }

        function row(a, index, total) {
            const initials = (a.username || a.email || 'U').substring(0, 2).toUpperCase();
            const isLast = index === total - 1;
            return `
                <div class="attendee-row p-3 ${!isLast ? 'border-bottom' : ''}" style="transition: all 0.2s ease;">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: var(--gradient-primary); font-weight: 600; font-size: 1rem;">
                                ${initials}
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="h6 mb-0 fw-bold" style="color: #1f2937;">${(a.username || 'Unknown').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</h3>
                                <span class="badge rounded-pill" style="background: rgba(6, 182, 212, 0.1); color: var(--primary-color); font-size: 0.7rem;">#${a.id}</span>
                            </div>
                            <div class="small text-muted">
                                <i class="fas fa-envelope me-1" style="color: var(--primary-color); font-size: 0.75rem;"></i>
                                ${(a.email || 'No email').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <div class="small text-muted mb-1">
                                <i class="fas fa-clock me-1" style="color: var(--primary-color);"></i>
                                Registered
                            </div>
                            <div class="small fw-semibold" style="color: #6b7280;">
                                ${new Date(a.registered_at || Date.now()).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        let myEvents = [], allAttendees = [];
        async function load() {
            const [{ user }, events] = await Promise.all([me(), fetchAllEvents()]);
            if (!user || user.role !== 'organizer') { window.location.href = '/login.php'; return; }
            myEvents = events.filter(e => Number(e.organizer_id) === Number(user.id));
            const select = document.getElementById('eventSelect');
            select.innerHTML = myEvents.length ? myEvents.map(e => `<option value="${e.id}">${e.title}</option>`).join('') : '<option value="">No events found</option>';
            if (myEvents.length) {
                select.value = String(myEvents[0].id);
                await loadAttendees();
            }
            select.addEventListener('change', loadAttendees);
            document.getElementById('searchInput').addEventListener('input', applyFilter);
        }

        async function loadAttendees() {
            const select = document.getElementById('eventSelect');
            const eventId = Number(select.value);
            const container = document.getElementById('attendeesContainer');
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-spinner fa-spin fa-2x mb-3" style="opacity: 0.5;"></i>
                    <p class="mb-0">Loading attendees...</p>
                </div>
            `;
            try {
                allAttendees = await fetchAttendees(eventId);
                applyFilter();
            } catch {
                container.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3" style="opacity: 0.5;"></i>
                        <p class="mb-0">Failed to load attendees.</p>
                    </div>
                `;
            }
        }

        function applyFilter() {
            const q = (document.getElementById('searchInput').value || '').toLowerCase();
            const container = document.getElementById('attendeesContainer');
            const filtered = allAttendees.filter(a => String(a.username||'').toLowerCase().includes(q) || String(a.email||'').toLowerCase().includes(q));
            document.getElementById('count').textContent = `${filtered.length} attendee${filtered.length === 1 ? '' : 's'}`;
            
            if (!filtered.length) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-search fa-2x mb-3" style="opacity: 0.3;"></i>
                        <p class="mb-0">No attendees found matching your search.</p>
                    </div>
                `;
            } else {
                container.innerHTML = filtered.map((a, i) => row(a, i, filtered.length)).join('');
                
                // Add hover effects
                container.querySelectorAll('.attendee-row').forEach(row => {
                    row.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = 'rgba(6, 182, 212, 0.05)';
                    });
                    row.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = 'transparent';
                    });
                });
            }
        }

        function toCsv(rows) {
            const header = ['User ID','Name','Email','Registered At'];
            const esc = (v) => '"' + String(v ?? '').replace(/"/g,'""') + '"';
            const data = rows.map(a => [a.id, a.username || '', a.email || '', new Date(a.registered_at || Date.now()).toISOString()]);
            return [header, ...data].map(r => r.map(esc).join(',')).join('\n');
        }
        function downloadCsv(filename, content) {
            const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }
        document.getElementById('exportCsvBtn')?.addEventListener('click', () => {
            const select = document.getElementById('eventSelect');
            const eventName = select.options[select.selectedIndex]?.text || 'event';
            const q = (document.getElementById('searchInput').value || '').toLowerCase();
            const filtered = allAttendees.filter(a => String(a.username||'').toLowerCase().includes(q) || String(a.email||'').toLowerCase().includes(q));
            const csv = toCsv(filtered);
            downloadCsv(`${eventName.replace(/[^a-z0-9]+/gi,'_').toLowerCase()}_attendees.csv`, csv);
        });

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        load();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


