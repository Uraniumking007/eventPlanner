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
    <title>Manage Events - Admin</title>
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
                    <h1 class="h3 mb-0">Event Management</h1>
                    <div class="text-secondary small">Admins can only suspend/unsuspend events with a reason.</div>
                </div>
                <a class="btn btn-outline-secondary" href="/admin/index.php"><i class="fas fa-chevron-left me-2"></i>Back to Admin</a>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="input-group" style="max-width: 360px">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input id="search" type="text" class="form-control" placeholder="Search events...">
                </div>
                <div class="text-secondary small" id="count"></div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Organizer</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="eventsTable"><tr><td colspan="5" class="text-secondary small">Loading...</td></tr></tbody>
                </table>
            </div>

            <div class="mt-4">
                <h2 class="h6 mb-2">Recent Audit Logs</h2>
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
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Suspend/Unsuspend Modal -->
    <div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suspendModalLabel">Update Event Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">(required)</span></label>
                        <textarea id="suspendReason" class="form-control" rows="3" placeholder="Enter the reason"></textarea>
                        <div id="suspendError" class="text-danger small mt-2 d-none">Reason is required.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="suspendConfirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const api = {
            async listEvents() {
                const res = await fetch('/api/events.php', { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).events || [];
            },
            async updateEvent(id, payload) {
                const res = await fetch('/api/events.php?id=' + id, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(payload) });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).event;
            },
            async deleteEvent(id, reason) {
                const res = await fetch('/api/events.php?id=' + id, { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify({ reason }) });
                if (!res.ok) throw new Error('Failed');
                return true;
            },
            async logs(eventId) {
                const url = eventId ? '/api/events.php?action=logs&event_id=' + eventId : '/api/events.php?action=logs';
                const res = await fetch(url, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).logs || [];
            }
        };

        function fmtDate(d) { try { return new Date(d).toLocaleString(); } catch { return d; } }

        function row(e) {
            const suspended = Number(e.suspended || 0) === 1;
            return `
                <tr data-id="${e.id}">
                    <td>${e.title}</td>
                    <td>${e.organizer_name || e.organizer_id}</td>
                    <td>${fmtDate(e.event_date)}</td>
                    <td>${e.location || ''}</td>
                    <td>${suspended ? '<span class="badge bg-danger-subtle text-danger">Suspended</span>' : '<span class="badge bg-success-subtle text-success">Active</span>'}</td>
                    <td class="text-end">
                        <button class="btn btn-sm ${suspended ? 'btn-success' : 'btn-outline-warning'} suspend">${suspended ? 'Unsuspend' : 'Suspend'}</button>
                    </td>
                </tr>
            `;
        }

        function render(list) {
            const tbody = document.getElementById('eventsTable');
            const count = document.getElementById('count');
            if (!list.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-secondary small">No events found.</td></tr>'; count.textContent = '0 events'; return; }
            tbody.innerHTML = list.map(row).join('');
            count.textContent = list.length + ' events';

            const modalEl = document.getElementById('suspendModal');
            const reasonEl = document.getElementById('suspendReason');
            const errorEl = document.getElementById('suspendError');
            const confirmBtn = document.getElementById('suspendConfirm');
            let modal, currentEventId = null, suspendTarget = false, currentRow = null;
            if (modalEl) modal = new bootstrap.Modal(modalEl);

            tbody.querySelectorAll('button.suspend').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    currentRow = tr;
                    currentEventId = Number(tr.getAttribute('data-id'));
                    suspendTarget = e.target.textContent.includes('Suspend');
                    document.getElementById('suspendModalLabel').textContent = suspendTarget ? 'Suspend Event' : 'Unsuspend Event';
                    reasonEl.value = '';
                    errorEl.classList.add('d-none');
                    modal?.show();
                });
            });

            confirmBtn?.addEventListener('click', async () => {
                const reason = String(reasonEl.value || '').trim();
                if (!reason) { errorEl.classList.remove('d-none'); return; }
                try {
                    await api.updateEvent(currentEventId, { suspended: suspendTarget, reason });
                    modal?.hide();
                    if (currentRow) { currentRow.classList.add('table-success'); setTimeout(()=>currentRow.classList.remove('table-success'), 600); }
                    load();
                } catch (e) {
                    errorEl.textContent = 'Failed to update status. Please try again.';
                    errorEl.classList.remove('d-none');
                }
            });
        }

        function renderLogs(list) {
            const tbody = document.getElementById('logsTable');
            if (!list.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-secondary small">No logs yet.</td></tr>'; return; }
            tbody.innerHTML = list.map(l => `
                <tr>
                    <td class="text-nowrap">${fmtDate(l.created_at)}</td>
                    <td>${l.admin_name || l.admin_user_id}</td>
                    <td>${l.action}</td>
                    <td>${l.event_id ?? ''}</td>
                    <td>${(l.reason || '').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</td>
                </tr>
            `).join('');
        }

        let all = [];
        async function load() {
            try { all = await api.listEvents(); applyFilter(); renderLogs(await api.logs()); } catch { document.getElementById('eventsTable').innerHTML = '<tr><td colspan="5" class="text-danger small">Load failed.</td></tr>'; }
        }
        function applyFilter() {
            const q = (document.getElementById('search').value || '').toLowerCase();
            const filtered = all.filter(e => String(e.title||'').toLowerCase().includes(q) || String(e.location||'').toLowerCase().includes(q));
            render(filtered);
        }
        document.getElementById('search').addEventListener('input', applyFilter);

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        load();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


