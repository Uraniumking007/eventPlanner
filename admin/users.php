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
        <section class="py-4 border-bottom bg-light">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="h3 mb-0">User Management</h1>
                        <div class="text-secondary small">Change roles, suspend accounts, reset passwords</div>
                    </div>
                    <a class="btn btn-outline-secondary" href="/admin/index.php"><i class="fas fa-chevron-left me-2"></i>Back to Admin</a>
                </div>
            </div>
        </section>

        <div class="container py-4 py-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="input-group" style="max-width: 360px">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input id="search" type="text" class="form-control" placeholder="Search users...">
                        </div>
                        <div class="text-secondary small" id="count"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable">
                                <tr><td colspan="7" class="text-secondary small">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

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
            async setSuspended(id, suspended) {
                const res = await fetch('/api/users.php?action=suspend', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify({ id, suspended }) });
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
            const badge = u.suspended ? '<span class="badge bg-danger-subtle text-danger">Suspended</span>' : '<span class="badge bg-success-subtle text-success">Active</span>';
            return `
                <tr data-id="${u.id}">
                    <td>${u.id}</td>
                    <td>${esc(u.username)}</td>
                    <td>${esc(u.email)}</td>
                    <td>
                        <select class="form-select form-select-sm role" style="width:auto">
                            <option value="attendee" ${u.role==='attendee'?'selected':''}>attendee</option>
                            <option value="organizer" ${u.role==='organizer'?'selected':''}>organizer</option>
                            <option value="admin" ${u.role==='admin'?'selected':''}>admin</option>
                        </select>
                    </td>
                    <td>${badge}</td>
                    <td class="text-nowrap">${new Date(u.created_at).toLocaleDateString()}</td>
                    <td class="text-end">
                        <button class="btn btn-sm ${u.suspended ? 'btn-success' : 'btn-outline-warning'} suspend">${u.suspended ? 'Unsuspend' : 'Suspend'}</button>
                        <button class="btn btn-sm btn-outline-secondary reset">Reset PW</button>
                    </td>
                </tr>
            `;
        }

        function render(list) {
            const tbody = document.getElementById('usersTable');
            const count = document.getElementById('count');
            if (!list.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-secondary small">No users found.</td></tr>'; count.textContent = '0 users'; return; }
            tbody.innerHTML = list.map(row).join('');
            count.textContent = list.length + ' users';

            tbody.querySelectorAll('select.role').forEach(sel => {
                sel.addEventListener('change', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = Number(tr.getAttribute('data-id'));
                    const role = e.target.value;
                    try { await api.setRole(id, role); tr.classList.add('table-success'); setTimeout(()=>tr.classList.remove('table-success'), 600); } catch { alert('Failed to update role'); }
                });
            });
            tbody.querySelectorAll('button.suspend').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = Number(tr.getAttribute('data-id'));
                    const makeSuspended = e.target.textContent.includes('Suspend');
                    try { await api.setSuspended(id, makeSuspended); load(); } catch { alert('Failed to update status'); }
                });
            });
            tbody.querySelectorAll('button.reset').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const tr = e.target.closest('tr');
                    const id = Number(tr.getAttribute('data-id'));
                    const pw = prompt('Enter a new password (min 6 chars):');
                    if (!pw) return;
                    if (pw.length < 6) { alert('Password too short'); return; }
                    try { await api.resetPassword(id, pw); alert('Password updated'); } catch { alert('Failed to reset password'); }
                });
            });
        }

        let allUsers = [];
        async function load() {
            try { allUsers = await api.list(); applyFilter(); } catch { document.getElementById('usersTable').innerHTML = '<tr><td colspan="7" class="text-danger small">Load failed.</td></tr>'; }
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


