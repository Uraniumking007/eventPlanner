<?php
declare(strict_types=1);
require_once __DIR__ . '/../api/init.php';

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
    <title>Audit Logs - Admin</title>
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
                    <h1 class="h3 mb-0">Event Audit Logs</h1>
                    <div class="text-secondary small">Review admin actions with reasons.</div>
                </div>
                <a class="btn btn-outline-secondary" href="/admin/index.php"><i class="fas fa-chevron-left me-2"></i>Back to Admin</a>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <div class="row g-3 align-items-end mb-3">
                <div class="col-12 col-md-3">
                    <label for="filterAdmin" class="form-label small text-secondary mb-1">Admin</label>
                    <input id="filterAdmin" type="text" class="form-control" placeholder="Admin username/email">
                </div>
                <div class="col-12 col-md-3">
                    <label for="filterAction" class="form-label small text-secondary mb-1">Action</label>
                    <select id="filterAction" class="form-select">
                        <option value="">All</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="filterEventId" class="form-label small text-secondary mb-1">Event ID</label>
                    <input id="filterEventId" type="number" class="form-control" placeholder="e.g. 42">
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button id="applyFilters" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Apply</button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>When</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Event ID</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody id="logsTable"><tr><td colspan="5" class="text-secondary small">Loading...</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        async function fetchLogs(eventId) {
            const url = eventId ? '/api/events.php?action=logs&event_id=' + eventId : '/api/events.php?action=logs';
            const res = await fetch(url, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed');
            return (await res.json()).logs || [];
        }

        function fmtDate(d) { try { return new Date(d).toLocaleString(); } catch { return d; } }

        function render(list) {
            const tbody = document.getElementById('logsTable');
            if (!list.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-secondary small">No logs.</td></tr>'; return; }
            tbody.innerHTML = list.map(l => `
                <tr>
                    <td class="text-nowrap">${fmtDate(l.created_at)}</td>
                    <td>${l.admin_name || l.admin_user_id}</td>
                    <td class="text-capitalize">${l.action}</td>
                    <td>${l.event_id ?? ''}</td>
                    <td>${String(l.reason||'').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</td>
                </tr>
            `).join('');
        }

        let all = [];
        async function load() {
            try { all = await fetchLogs(); apply(); } catch { document.getElementById('logsTable').innerHTML = '<tr><td colspan="5" class="text-danger small">Load failed.</td></tr>'; }
        }
        function apply() {
            const adminQ = (document.getElementById('filterAdmin').value || '').toLowerCase();
            const actionQ = (document.getElementById('filterAction').value || '').toLowerCase();
            const eventIdQ = Number(document.getElementById('filterEventId').value || 0);
            let list = all;
            if (actionQ) list = list.filter(l => String(l.action).toLowerCase() === actionQ);
            if (eventIdQ) list = list.filter(l => Number(l.event_id) === eventIdQ);
            if (adminQ) list = list.filter(l => String(l.admin_name||'').toLowerCase().includes(adminQ));
            render(list);
        }
        document.getElementById('applyFilters').addEventListener('click', apply);

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        load();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


