<?php
// Reusable Navbar include (Bootstrap 5)
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/index.php">
            <span class="d-inline-flex align-items-center justify-content-center rounded bg-primary text-white p-1" style="width:32px;height:32px"><i class="fas fa-calendar-alt"></i></span>
            <span class="d-none d-sm-inline">Event Planner</span>
            <span class="d-sm-none">EP</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-md-0" id="navLinks">
                <li class="nav-item"><a id="navHome" class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a id="navEvents" class="nav-link" href="/events.php">Events</a></li>
                <li class="nav-item"><a id="navProfile" class="nav-link" href="/profile.php">Profile</a></li>
            </ul>
            <div id="userArea" class="d-none d-md-block"></div>
            <div id="mobileUserArea" class="d-md-none w-100 mt-3"></div>
        </div>
    </div>
    <script>
        (function highlightActive(){
            const path = window.location.pathname || '';
            const setActive = (id, match) => { const el = document.getElementById(id); if (el && match) el.classList.add('active'); };
            setActive('navHome', path === '/' || path.endsWith('index.php'));
            setActive('navEvents', path.endsWith('events.php'));
            setActive('navProfile', path.endsWith('profile.php'));
        })();
        (async function initUserArea(){
            try {
                const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
                const data = res.ok ? await res.json() : { user: null };
                const user = data.user;
                const desktop = document.getElementById('userArea');
                const mobile = document.getElementById('mobileUserArea');
                if (!desktop || !mobile) return;
                if (user) {
                    const showDash = user.role === 'organizer';
                    const displayName = String(user.username || user.email || '').trim();
                    desktop.innerHTML = `
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>${displayName}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
                                <li><h6 class="dropdown-header">Signed in</h6></li>
                                <li><a class="dropdown-item" href="/profile.php">Profile</a></li>
                                ${showDash ? '<li><a class="dropdown-item" href="/dashboard.php">Dashboard</a></li>' : ''}
                                <li><a class="dropdown-item" href="/events.php">Events</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item text-danger" id="logoutBtn">Logout</button></li>
                            </ul>
                        </div>`;
                    mobile.innerHTML = `
                        <div class="alert alert-secondary d-flex align-items-center justify-content-between" role="alert">
                            <div><i class="fas fa-user me-2"></i>Hello, ${displayName}</div>
                            <button class="btn btn-sm btn-outline-danger" id="mobileLogoutBtn">Logout</button>
                        </div>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action" href="/profile.php">Profile</a>
                            ${showDash ? '<a class="list-group-item list-group-item-action" href="/dashboard.php">Dashboard</a>' : ''}
                            <a class="list-group-item list-group-item-action" href="/events.php">Events</a>
                        </div>`;
                    const doLogout = async () => { try { await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' }); window.location.reload(); } catch { window.location.reload(); } };
                    document.getElementById('logoutBtn')?.addEventListener('click', doLogout);
                    document.getElementById('mobileLogoutBtn')?.addEventListener('click', doLogout);
                } else {
                    desktop.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <a class="btn btn-outline-light btn-sm" href="/login.php">Log in</a>
                            <a class="btn btn-primary btn-sm" href="/register.php">Register</a>
                        </div>`;
                    mobile.innerHTML = `
                        <div class="d-grid gap-2">
                            <a class="btn btn-outline-secondary" href="/login.php">Log in</a>
                            <a class="btn btn-primary" href="/register.php">Register</a>
                        </div>`;
                }
            } catch (e) {}
        })();
    </script>
</nav>
