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
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">Event Management</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-calendar-alt me-2"></i>Suspend or unsuspend events with a reason
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a class="btn btn-outline-custom" href="/admin/index.php">
                            <i class="fas fa-arrow-left me-2"></i>Back to Admin
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <!-- Events Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="input-group" style="max-width: 400px">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input id="search" type="text" class="form-control border-start-0 ps-0" placeholder="Search events...">
                        </div>
                        <div class="text-muted small fw-semibold" id="count">0 events</div>
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
                                    <th class="small text-muted fw-semibold">Status</th>
                                    <th class="small text-muted fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="eventsTable">
                                <tr><td colspan="6" class="text-center text-muted py-4">
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
        };

        function fmtDate(d) { try { return new Date(d).toLocaleString(); } catch { return d; } }

        function row(e) {
            const suspended = Number(e.suspended || 0) === 1;
            return `
                <tr data-id="${e.id}">
                    <td class="fw-semibold small">${e.title}</td>
                    <td class="small text-muted">${e.organizer_name || e.organizer_id}</td>
                    <td class="small text-nowrap text-muted">${fmtDate(e.event_date)}</td>
                    <td class="small text-muted">${e.location || ''}</td>
                    <td>${suspended ? '<span class="badge bg-danger">Suspended</span>' : '<span class="badge bg-success">Active</span>'}</td>
                    <td class="text-end">
                        <button class="btn btn-sm ${suspended ? 'btn-success' : 'btn-outline-warning'} suspend" data-next="${suspended ? 0 : 1}">
                            <i class="fas fa-${suspended ? 'check' : 'ban'} me-1"></i>${suspended ? 'Unsuspend' : 'Suspend'}
                        </button>
                    </td>
                </tr>
            `;
        }

        function render(list) {
            const tbody = document.getElementById('eventsTable');
            const count = document.getElementById('count');
            if (!list.length) { 
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-search me-2"></i>No events found.</td></tr>'; 
                count.textContent = '0 events'; 
                return; 
            }
            tbody.innerHTML = list.map(row).join('');
            count.textContent = list.length + ' event' + (list.length === 1 ? '' : 's');

            const modalEl = document.getElementById('suspendModal');
            const reasonEl = document.getElementById('suspendReason');
            const errorEl = document.getElementById('suspendError');
            const confirmBtn = document.getElementById('suspendConfirm');
            let modal = null, currentEventId = null, suspendTarget = false, currentRow = null;

            tbody.querySelectorAll('button.suspend').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    currentRow = tr;
                    currentEventId = Number(tr.getAttribute('data-id'));
                    const next = e.target.getAttribute('data-next');
                    suspendTarget = String(next) === '1';
                    document.getElementById('suspendModalLabel').textContent = suspendTarget ? 'Suspend Event' : 'Unsuspend Event';
                    reasonEl.value = '';
                    errorEl.classList.add('d-none');
                    if (!modal && window.bootstrap?.Modal) modal = new bootstrap.Modal(modalEl);
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


        let all = [];
        async function load() {
            try { 
                all = await api.listEvents(); 
                applyFilter(); 
            } catch { 
                document.getElementById('eventsTable').innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load events.</td></tr>'; 
            }
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


