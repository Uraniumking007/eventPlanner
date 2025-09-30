<?php
// Reusable Navbar include
?>
<nav class="bg-gray-900 text-white navbar shadow">
    <div class="max-w-8xl mx-auto px-4 flex items-center justify-between">
        <div class="flex items-center h-16">
            <a class="flex items-center gap-2 font-semibold" href="/index.php">
                <i class="fas fa-calendar-alt"></i>
                <span class="hidden sm:inline">Event Planner</span>
                <span class="sm:hidden">EP</span>
            </a>
        </div>
        <div id="navLinks" class="hidden md:flex items-center gap-1 md:gap-2">
            <a id="navHome" href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Home</a>
            <a id="navEvents" href="/events.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Events</a>
        </div>
        <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md text-gray-300 hover:bg-white/10 hover:text-white">
            <i class="fas fa-bars"></i>
        </button>
        <div id="userArea" class="hidden md:block text-sm"></div>
    </div>
    <div id="mobileMenu" class="md:hidden hidden bg-gray-800 border-t border-gray-700">
        <div id="mobileNavLinks" class="px-4 py-2 space-y-1">
            <a href="/" class="block px-3 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-md">Home</a>
            <a href="/events.php" class="block px-3 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-md">Events</a>
            <div id="mobileUserArea" class="px-3 py-2"></div>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    (function(){
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn && menu) {
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        }
    })();

    // Active nav highlight
    (function(){
        const path = window.location.pathname || '';
        const home = document.getElementById('navHome');
        const events = document.getElementById('navEvents');
        const dash = document.getElementById('navDashboard');
        if (home && (path === '/' || path.endsWith('index.php'))) home.classList.add('bg-white/10','text-white');
        if (events && path.endsWith('events.php')) events.classList.add('bg-white/10','text-white');
        if (dash && path.endsWith('dashboard.php')) dash.classList.add('bg-white/10','text-white');
    })();

    // Populate user area (desktop + mobile) and handle logout
    (async function initUserArea(){
        try {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            const data = res.ok ? await res.json() : { user: null };
            const user = data.user;
            const area = document.getElementById('userArea');
            const navLinks = document.getElementById('navLinks');
            const mobileArea = document.getElementById('mobileUserArea');
            const mobileNavLinks = document.getElementById('mobileNavLinks');
            if (!area || !mobileArea) return;
            if (user) {
                const showDash = user.role === 'organizer';
                const displayName = String(user.username || user.email || '').trim();
                const initials = (function(name){
                    if (!name) return 'U';
                    const parts = name.replace(/@.*/, '').split(/\s+/).filter(Boolean);
                    const first = parts[0]?.[0] || name[0];
                    const second = parts[1]?.[0] || '';
                    return (first + second).toUpperCase();
                })(displayName);
                area.innerHTML = `
                    <div class="relative">
                        <button id="avatarBtn" class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40" aria-haspopup="true" aria-expanded="false">${initials}</button>
                        <div id="avatarMenu" class="absolute right-0 mt-2 w-44 bg-white text-gray-800 rounded-lg shadow-lg border border-gray-100 py-1 hidden z-50">
                            <div class="px-3 py-2 text-xs text-gray-500">Signed in as<br><span class="font-medium">${displayName}</span></div>
                            <a href="/profile.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Profile</a>
                            ${showDash ? '<a href="/dashboard.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Dashboard</a>' : ''}
                            <a href="/events.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Events</a>
                            <button id="logoutBtn" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </div>
                    </div>
                `;
                const mobileDashLink = showDash ? '<a href="/dashboard.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Dashboard</a>' : '';
                // Mobile avatar + dropdown inside mobile menu panel (for all logged-in users)
                mobileArea.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-400">Hello, ${displayName}</div>
                        <button id="avatarMobileBtn" class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/20">${initials}</button>
                    </div>
                    <div id="avatarMobileMenu" class="mt-2 w-full bg-white text-gray-800 rounded-lg shadow-lg border border-gray-100 py-1 hidden">
                        <div class="px-3 py-2 text-xs text-gray-500">Signed in as<br><span class="font-medium">${displayName}</span></div>
                        <a href="/profile.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Profile</a>
                        ${mobileDashLink}
                        <a href="/events.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Events</a>
                        <button id="mobileLogoutBtn" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </div>
                `;
                const doLogout = async () => { try { await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' }); window.location.reload(); } catch { window.location.reload(); } };
                document.getElementById('logoutBtn')?.addEventListener('click', doLogout);
                document.getElementById('mobileLogoutBtn')?.addEventListener('click', doLogout);

                // Mobile avatar dropdown interactions
                const avatarMobileBtn = document.getElementById('avatarMobileBtn');
                const avatarMobileMenu = document.getElementById('avatarMobileMenu');
                if (avatarMobileBtn && avatarMobileMenu) {
                    const closeMobileMenu = () => { avatarMobileMenu.classList.add('hidden'); };
                    const toggleMobileMenu = (e) => { e.stopPropagation(); avatarMobileMenu.classList.toggle('hidden'); };
                    avatarMobileBtn.addEventListener('click', toggleMobileMenu);
                    document.addEventListener('click', (e) => { if (!avatarMobileMenu.contains(e.target) && e.target !== avatarMobileBtn) closeMobileMenu(); });
                    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMobileMenu(); });
                }

                // Avatar dropdown interactions
                const avatarBtn = document.getElementById('avatarBtn');
                const avatarMenu = document.getElementById('avatarMenu');
                if (avatarBtn && avatarMenu) {
                    const closeMenu = () => { avatarMenu.classList.add('hidden'); avatarBtn.setAttribute('aria-expanded', 'false'); };
                    const toggleMenu = (e) => { e.stopPropagation(); const isHidden = avatarMenu.classList.contains('hidden'); if (isHidden) { avatarMenu.classList.remove('hidden'); avatarBtn.setAttribute('aria-expanded', 'true'); } else { closeMenu(); } };
                    avatarBtn.addEventListener('click', toggleMenu);
                    document.addEventListener('click', (e) => { if (!avatarMenu.contains(e.target) && e.target !== avatarBtn) closeMenu(); });
                    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });
                }
            } else {
                // Guest avatar + dropdown (visible for all users)
                const guestInitials = 'G';
                area.innerHTML = `
                    <div class="relative">
                        <button id="guestAvatarBtn" class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40" aria-haspopup="true" aria-expanded="false">${guestInitials}</button>
                        <div id="guestAvatarMenu" class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-lg shadow-lg border border-gray-100 py-1 hidden z-50">
                            <div class="px-3 py-2 text-xs text-gray-500">You are browsing as<br><span class="font-medium">Guest</span></div>
                            <a href="/login.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Log in</a>
                            <a href="/register.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Register</a>
                        </div>
                    </div>
                `;
                mobileArea.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-400">Hello, Guest</div>
                        <button id="guestAvatarMobileBtn" class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/20">${guestInitials}</button>
                    </div>
                    <div id="guestAvatarMobileMenu" class="mt-2 w-full bg-white text-gray-800 rounded-lg shadow-lg border border-gray-100 py-1 hidden">
                        <a href="/login.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Log in</a>
                        <a href="/register.php" class="block px-3 py-2 text-sm hover:bg-gray-50">Register</a>
                    </div>
                `;
                // Guest avatar interactions
                const guestAvatarBtn = document.getElementById('guestAvatarBtn');
                const guestAvatarMenu = document.getElementById('guestAvatarMenu');
                if (guestAvatarBtn && guestAvatarMenu) {
                    const closeGuestMenu = () => { guestAvatarMenu.classList.add('hidden'); guestAvatarBtn.setAttribute('aria-expanded', 'false'); };
                    const toggleGuestMenu = (e) => { e.stopPropagation(); const isHidden = guestAvatarMenu.classList.contains('hidden'); if (isHidden) { guestAvatarMenu.classList.remove('hidden'); guestAvatarBtn.setAttribute('aria-expanded', 'true'); } else { closeGuestMenu(); } };
                    guestAvatarBtn.addEventListener('click', toggleGuestMenu);
                    document.addEventListener('click', (e) => { if (!guestAvatarMenu.contains(e.target) && e.target !== guestAvatarBtn) closeGuestMenu(); });
                    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeGuestMenu(); });
                }
                const guestAvatarMobileBtn = document.getElementById('guestAvatarMobileBtn');
                const guestAvatarMobileMenu = document.getElementById('guestAvatarMobileMenu');
                if (guestAvatarMobileBtn && guestAvatarMobileMenu) {
                    const closeGuestMobile = () => { guestAvatarMobileMenu.classList.add('hidden'); };
                    const toggleGuestMobile = (e) => { e.stopPropagation(); guestAvatarMobileMenu.classList.toggle('hidden'); };
                    guestAvatarMobileBtn.addEventListener('click', toggleGuestMobile);
                    document.addEventListener('click', (e) => { if (!guestAvatarMobileMenu.contains(e.target) && e.target !== guestAvatarMobileBtn) closeGuestMobile(); });
                    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeGuestMobile(); });
                }
            }
        } catch (e) {
            // no-op on failure
        }
    })();
</script>


