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
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">Event Audit Logs</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-clipboard-list me-2"></i>Review all admin actions with reasons
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
            <!-- Filters -->
            <div class="card shadow-sm border-0 rounded-3 mb-3">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3">
                            <label for="filterAdmin" class="form-label small fw-semibold mb-1">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </label>
                            <input id="filterAdmin" type="text" class="form-control" placeholder="Search by admin...">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterAction" class="form-label small fw-semibold mb-1">
                                <i class="fas fa-tasks me-1"></i>Action
                            </label>
                            <select id="filterAction" class="form-select">
                                <option value="">All Actions</option>
                                <option value="create">Create</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterEventId" class="form-label small fw-semibold mb-1">
                                <i class="fas fa-hashtag me-1"></i>Event ID
                            </label>
                            <input id="filterEventId" type="number" class="form-control" placeholder="e.g. 42">
                        </div>
                        <div class="col-12 col-md-3">
                            <button id="applyFilters" class="btn btn-gradient w-100">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="h6 mb-0 fw-bold">
                        <i class="fas fa-history text-primary me-2"></i>Audit Trail
                    </h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small text-muted fw-semibold">When</th>
                                    <th class="small text-muted fw-semibold">Admin</th>
                                    <th class="small text-muted fw-semibold">Action</th>
                                    <th class="small text-muted fw-semibold">Event ID</th>
                                    <th class="small text-muted fw-semibold">Reason</th>
                                </tr>
                            </thead>
                            <tbody id="logsTable">
                                <tr><td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading logs...
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
        async function fetchLogs(eventId) {
            const url = eventId ? '/api/events.php?action=logs&event_id=' + eventId : '/api/events.php?action=logs';
            const res = await fetch(url, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed');
            return (await res.json()).logs || [];
        }

        function fmtDate(d) { try { return new Date(d).toLocaleString(); } catch { return d; } }

        function render(list) {
            const tbody = document.getElementById('logsTable');
            if (!list.length) { 
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>No logs found.</td></tr>'; 
                return; 
            }
            tbody.innerHTML = list.map(l => `
                <tr>
                    <td class="small text-nowrap text-muted">${fmtDate(l.created_at)}</td>
                    <td class="small">${l.admin_name || l.admin_user_id}</td>
                    <td class="small">
                        <span class="badge ${
                            l.action === 'create' ? 'bg-success-subtle text-success' :
                            l.action === 'update' ? 'bg-primary-subtle text-primary' :
                            'bg-danger-subtle text-danger'
                        } text-capitalize">${l.action}</span>
                    </td>
                    <td class="small text-muted">#${l.event_id ?? ''}</td>
                    <td class="small text-muted">${String(l.reason||'').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</td>
                </tr>
            `).join('');
        }

        let all = [];
        async function load() {
            try { 
                all = await fetchLogs(); 
                apply(); 
            } catch { 
                document.getElementById('logsTable').innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load logs.</td></tr>'; 
            }
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


