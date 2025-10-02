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
        <section class="py-4 border-bottom bg-light">
            <div class="container d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mb-0">Event Attendees</h1>
                    <div class="text-secondary small">View user details for attendees of your events.</div>
                </div>
                <a class="btn btn-outline-secondary" href="/dashboard.php"><i class="fas fa-chevron-left me-2"></i>Back to Dashboard</a>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <div class="row g-3 align-items-end mb-3">
                <div class="col-12 col-md-6">
                    <label class="form-label small text-secondary">Event</label>
                    <select id="eventSelect" class="form-select"></select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label small text-secondary">Search attendees</label>
                    <input id="searchInput" type="text" class="form-control" placeholder="Name or email">
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h2 class="h6 mb-0">Attendees</h2>
                        <div class="d-flex align-items-center gap-2">
                            <div class="small text-secondary" id="count">0</div>
                            <button id="exportCsvBtn" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-csv me-1"></i>Export CSV</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody id="attendeesTable"><tr><td colspan="4" class="text-secondary small">Select an event.</td></tr></tbody>
                        </table>
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

        function row(a) {
            return `
                <tr>
                    <td>${a.id}</td>
                    <td>${(a.username || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</td>
                    <td>${(a.email || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</td>
                    <td class="text-nowrap">${new Date(a.registered_at || Date.now()).toLocaleString()}</td>
                </tr>
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
            const tbody = document.getElementById('attendeesTable');
            tbody.innerHTML = '<tr><td colspan="4" class="text-secondary small">Loading...</td></tr>';
            try {
                // Extend registrations API to include registered_at for clarity; currently we fallback to now.
                allAttendees = await fetchAttendees(eventId);
                applyFilter();
            } catch {
                tbody.innerHTML = '<tr><td colspan="4" class="text-danger small">Failed to load attendees.</td></tr>';
            }
        }

        function applyFilter() {
            const q = (document.getElementById('searchInput').value || '').toLowerCase();
            const tbody = document.getElementById('attendeesTable');
            const filtered = allAttendees.filter(a => String(a.username||'').toLowerCase().includes(q) || String(a.email||'').toLowerCase().includes(q));
            document.getElementById('count').textContent = `${filtered.length} attendee${filtered.length === 1 ? '' : 's'}`;
            tbody.innerHTML = filtered.length ? filtered.map(row).join('') : '<tr><td colspan="4" class="text-secondary small">No attendees found.</td></tr>';
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


