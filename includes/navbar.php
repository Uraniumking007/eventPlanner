<?php
// Reusable Navbar include (Bootstrap 5)
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="/index.php">
            <div class="d-inline-flex align-items-center justify-content-center rounded-3" style="width:40px; height:40px; background: var(--gradient-primary);">
                <i class="fas fa-calendar-alt text-white"></i>
            </div>
            <span class="d-none d-md-inline">Event Planner</span>
            <span class="d-md-none">EP</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1" id="navLinks">
                <li class="nav-item">
                    <a id="navHome" class="nav-link px-3 py-2 rounded-3" href="/">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navEvents" class="nav-link px-3 py-2 rounded-3" href="/events.php">
                        <i class="fas fa-calendar-alt me-2"></i>Events
                    </a>
                </li>
                <li class="nav-item">
                    <a id="navProfile" class="nav-link px-3 py-2 rounded-3" href="/profile.php">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                </li>
            </ul>

            <!-- Desktop User Area -->
            <div id="userArea" class="d-none d-lg-block"></div>
            
            <!-- Mobile User Area -->
            <div id="mobileUserArea" class="d-lg-none w-100 mt-3"></div>
        </div>
    </div>

    <script>
        // Highlight active navigation link
        (function highlightActive() {
            const path = window.location.pathname || '';
            const setActive = (id, match) => {
                const el = document.getElementById(id);
                if (el && match) {
                    el.classList.add('active', 'bg-white', 'bg-opacity-10');
                }
            };
            setActive('navHome', path === '/' || path.endsWith('index.php'));
            setActive('navEvents', path.endsWith('events.php'));
            setActive('navProfile', path.endsWith('profile.php'));
        })();

        // Initialize user area
        (async function initUserArea() {
            try {
                const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
                const data = res.ok ? await res.json() : { user: null };
                const user = data.user;
                const desktop = document.getElementById('userArea');
                const mobile = document.getElementById('mobileUserArea');
                
                if (!desktop || !mobile) return;

                if (user) {
                    const showDash = user.role === 'organizer';
                    const showAdmin = user.role === 'admin';
                    const displayName = String(user.username || user.email || '').trim();
                    const initials = displayName.substring(0, 2).toUpperCase();

                    // Desktop user menu
                    desktop.innerHTML = `
                        <div class="dropdown">
                            <button class="btn d-flex align-items-center gap-2 text-white border-0 dropdown-toggle" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: rgba(255, 255, 255, 0.1);">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; background: var(--gradient-primary); font-size: 0.75rem;">
                                    ${initials}
                                </div>
                                <span class="d-none d-xl-inline">${displayName}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 220px;">
                                <li>
                                    <div class="dropdown-header d-flex align-items-center gap-2 pb-2 border-bottom">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; background: var(--gradient-primary); font-size: 0.875rem;">
                                            ${initials}
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold text-truncate">${displayName}</div>
                                            <div class="small text-muted text-capitalize">${user.role}</div>
                                        </div>
                                    </div>
                                </li>
                                <li><a class="dropdown-item py-2" href="/profile.php"><i class="fas fa-user me-2 text-primary"></i>Profile</a></li>
                                ${showDash ? '<li><a class="dropdown-item py-2" href="/dashboard.php"><i class="fas fa-th-large me-2 text-success"></i>Dashboard</a></li>' : ''}
                                ${showAdmin ? '<li><a class="dropdown-item py-2" href="/admin/index.php"><i class="fas fa-shield-alt me-2 text-warning"></i>Admin</a></li>' : ''}
                                <li><a class="dropdown-item py-2" href="/events.php"><i class="fas fa-calendar-alt me-2 text-info"></i>Events</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item py-2 text-danger" id="logoutBtn"><i class="fas fa-sign-out-alt me-2"></i>Logout</button></li>
                            </ul>
                        </div>
                    `;

                    // Mobile user menu
                    mobile.innerHTML = `
                        <div class="card border-0 mb-3" style="background: rgba(255, 255, 255, 0.1);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; background: var(--gradient-primary);">
                                        ${initials}
                                    </div>
                                    <div class="flex-grow-1 min-w-0 text-white">
                                        <div class="fw-semibold text-truncate">${displayName}</div>
                                        <div class="small opacity-75 text-capitalize">${user.role}</div>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-outline-light btn-sm" id="mobileLogoutBtn">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action d-flex align-items-center gap-2" href="/profile.php">
                                <i class="fas fa-user text-primary"></i>Profile
                            </a>
                            ${showDash ? '<a class="list-group-item list-group-item-action d-flex align-items-center gap-2" href="/dashboard.php"><i class="fas fa-th-large text-success"></i>Dashboard</a>' : ''}
                            ${showAdmin ? '<a class="list-group-item list-group-item-action d-flex align-items-center gap-2" href="/admin/index.php"><i class="fas fa-shield-alt text-warning"></i>Admin</a>' : ''}
                            <a class="list-group-item list-group-item-action d-flex align-items-center gap-2" href="/events.php">
                                <i class="fas fa-calendar-alt text-info"></i>Events
                            </a>
                        </div>
                    `;

                    // Logout functionality
                    const doLogout = async () => {
                        try {
                            await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' });
                            window.location.href = '/';
                        } catch {
                            window.location.href = '/';
                        }
                    };
                    document.getElementById('logoutBtn')?.addEventListener('click', doLogout);
                    document.getElementById('mobileLogoutBtn')?.addEventListener('click', doLogout);
                } else {
                    // Not logged in - Desktop
                    desktop.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <a class="btn btn-outline-light" href="/login.php">
                                <i class="fas fa-sign-in-alt me-2"></i>Log in
                            </a>
                            <a class="btn btn-gradient px-4" href="/register.php">
                                <i class="fas fa-user-plus me-2"></i>Sign up
                            </a>
                        </div>
                    `;

                    // Not logged in - Mobile
                    mobile.innerHTML = `
                        <div class="d-grid gap-2">
                            <a class="btn btn-outline-light" href="/login.php">
                                <i class="fas fa-sign-in-alt me-2"></i>Log in
                            </a>
                            <a class="btn btn-gradient" href="/register.php">
                                <i class="fas fa-user-plus me-2"></i>Sign up
                            </a>
                        </div>
                    `;
                }
            } catch (e) {
                console.error('Failed to load user area:', e);
            }
        })();
    </script>
</nav>
