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
    <title>Manage Users - Admin</title>
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
                        <h1 class="h3 fw-bold mb-2">User Management</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-users-cog me-2"></i>Change roles, suspend accounts, and reset passwords
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
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="input-group" style="max-width: 400px">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input id="search" type="text" class="form-control border-start-0 ps-0" placeholder="Search by name or email...">
                        </div>
                        <div class="text-muted small fw-semibold" id="count">0 users</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small text-muted fw-semibold">ID</th>
                                    <th class="small text-muted fw-semibold">Name</th>
                                    <th class="small text-muted fw-semibold">Email</th>
                                    <th class="small text-muted fw-semibold">Role</th>
                                    <th class="small text-muted fw-semibold">Status</th>
                                    <th class="small text-muted fw-semibold">Created</th>
                                    <th class="small text-muted fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable">
                                <tr><td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Loading users...
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
    <div class="modal fade" id="suspendUserModal" tabindex="-1" aria-labelledby="suspendUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="suspendUserModalLabel">Update User Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">(required)</span></label>
                        <textarea id="suspendUserReason" class="form-control" rows="3" placeholder="Enter the reason"></textarea>
                        <div id="suspendUserError" class="text-danger small mt-2 d-none">Reason is required.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="suspendUserConfirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPwModal" tabindex="-1" aria-labelledby="resetPwModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPwModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">(min 6 chars)</span></label>
                        <input type="password" id="newPassword" class="form-control" placeholder="Enter new password">
                        <div id="pwError" class="text-danger small mt-2 d-none">Password must be at least 6 characters.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="resetPwConfirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const api = {
            async list() {
                const res = await fetch('/api/users.php', { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).users || [];
            },
            async setRole(id, role) {
                const res = await fetch('/api/users.php?action=role', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify({ id, role }) });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).user;
            },
            async setSuspended(id, suspended, reason) {
                const res = await fetch('/api/users.php?action=suspend', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify({ id, suspended, reason }) });
                if (!res.ok) throw new Error('Failed');
                return (await res.json()).user;
            },
            async resetPassword(id, password) {
                const res = await fetch('/api/users.php?action=password', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify({ id, password }) });
                if (!res.ok) throw new Error('Failed');
                return true;
            }
        };

        function esc(s) { return String(s ?? '').replace(/[&<>"] /g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',' ':' '})[c]); }

        function row(u) {
            const badge = u.suspended ? '<span class="badge bg-danger">Suspended</span>' : '<span class="badge bg-success">Active</span>';
            const initials = (u.username || u.email || 'U').substring(0, 2).toUpperCase();
            return `
                <tr data-id="${u.id}">
                    <td class="small text-muted">#${u.id}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: 600;">
                                ${initials}
                            </div>
                            <span class="fw-semibold small">${esc(u.username)}</span>
                        </div>
                    </td>
                    <td class="small text-muted">${esc(u.email)}</td>
                    <td>
                        <select class="form-select form-select-sm role" style="width: 110px; font-size: 0.875rem;">
                            <option value="attendee" ${u.role==='attendee'?'selected':''}>Attendee</option>
                            <option value="organizer" ${u.role==='organizer'?'selected':''}>Organizer</option>
                            <option value="admin" ${u.role==='admin'?'selected':''}>Admin</option>
                        </select>
                    </td>
                    <td>${badge}</td>
                    <td class="small text-muted text-nowrap">${new Date(u.created_at).toLocaleDateString()}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <button class="btn ${u.suspended ? 'btn-success' : 'btn-outline-warning'} suspend">
                                <i class="fas fa-${u.suspended ? 'check' : 'ban'} me-1"></i>${u.suspended ? 'Unsuspend' : 'Suspend'}
                            </button>
                            <button class="btn btn-outline-secondary reset">
                                <i class="fas fa-key me-1"></i>Reset
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        function render(list) {
            const tbody = document.getElementById('usersTable');
            const count = document.getElementById('count');
            if (!list.length) { 
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-search me-2"></i>No users found.</td></tr>'; 
                count.textContent = '0 users'; 
                return; 
            }
            tbody.innerHTML = list.map(row).join('');
            count.textContent = list.length + ' user' + (list.length === 1 ? '' : 's');

            tbody.querySelectorAll('select.role').forEach(sel => {
                sel.addEventListener('change', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = Number(tr.getAttribute('data-id'));
                    const role = e.target.value;
                    try { await api.setRole(id, role); tr.classList.add('table-success'); setTimeout(()=>tr.classList.remove('table-success'), 600); } catch { alert('Failed to update role'); }
                });
            });
            // Suspend/Unsuspend via modal
            const suModalEl = document.getElementById('suspendUserModal');
            const suModal = suModalEl ? new bootstrap.Modal(suModalEl) : null;
            const suReason = document.getElementById('suspendUserReason');
            const suError = document.getElementById('suspendUserError');
            let suUserId = null, suTarget = false;
            document.getElementById('suspendUserConfirm')?.addEventListener('click', async () => {
                const reason = String(suReason.value || '').trim();
                if (!reason) { suError.classList.remove('d-none'); return; }
                try { await api.setSuspended(suUserId, suTarget, reason); suModal?.hide(); load(); } catch { suError.textContent = 'Failed to update status.'; suError.classList.remove('d-none'); }
            });
            tbody.querySelectorAll('button.suspend').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    suUserId = Number(tr.getAttribute('data-id'));
                    suTarget = e.target.textContent.includes('Suspend');
                    suReason.value = '';
                    suError.classList.add('d-none');
                    suModal?.show();
                });
            });
            const pwModalEl = document.getElementById('resetPwModal');
            const pwModal = pwModalEl ? new bootstrap.Modal(pwModalEl) : null;
            const pwInput = document.getElementById('newPassword');
            const pwError = document.getElementById('pwError');
            let currentUserId = null;
            document.getElementById('resetPwConfirm')?.addEventListener('click', async () => {
                const pw = String(pwInput.value || '');
                if (pw.length < 6) { pwError.classList.remove('d-none'); return; }
                try { await api.resetPassword(currentUserId, pw); pwModal?.hide(); } catch { pwError.textContent = 'Failed to reset password.'; pwError.classList.remove('d-none'); }
            });
            tbody.querySelectorAll('button.reset').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    currentUserId = Number(tr.getAttribute('data-id'));
                    pwInput.value = '';
                    pwError.classList.add('d-none');
                    pwModal?.show();
                });
            });
        }

        let allUsers = [];
        async function load() {
            try { 
                allUsers = await api.list(); 
                applyFilter(); 
            } catch { 
                document.getElementById('usersTable').innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load users.</td></tr>'; 
            }
        }
        function applyFilter() {
            const q = (document.getElementById('search').value || '').toLowerCase();
            const filtered = allUsers.filter(u => String(u.username||'').toLowerCase().includes(q) || String(u.email||'').toLowerCase().includes(q));
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


