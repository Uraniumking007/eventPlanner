<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white navbar shadow">
        <div class="max-w-8xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center h-16">
                <a class="flex items-center gap-2 font-semibold" href="/index.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="hidden sm:inline">Event Planner</span>
                    <span class="sm:hidden">EP</span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-1 md:gap-2">
                <a id="navHome" href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Home</a>
                <a id="navEvents" href="/events.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Events</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md text-gray-300 hover:bg-white/10 hover:text-white">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Desktop User Area -->
            <div id="userArea" class="hidden md:block text-sm"></div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="md:hidden hidden bg-gray-800 border-t border-gray-700">
            <div class="px-4 py-2 space-y-1">
                <a href="/" class="block px-3 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-md">Home</a>
                <a href="/events.php" class="block px-3 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-md">Events</a>
                <div id="mobileUserArea" class="px-3 py-2"></div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="max-w-8xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center min-h-[50vh] lg:min-h-[75vh]">
                <div class="py-8 lg:py-12 px-4 text-center lg:text-left">
                    <h1 class="text-black font-extrabold text-3xl sm:text-4xl lg:text-5xl mb-4 leading-tight">
                        This is Events Page
                    </h1>
                    <p class="text-black/80 text-base sm:text-lg mb-4 max-w-2xl mx-auto lg:mx-0">
                        Create, manage, and organize events with ease. Our platform helps you bring your vision to life.
                    </p>
                </div>
                <div class="text-center py-8 lg:py-12">
                    <i class="fas fa-calendar-check text-white/80" style="font-size: 4rem; max-width: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-8xl mx-auto px-4 py-6 lg:py-8">
        <div id="eventsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6"></div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="max-w-8xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-6 items-center">
                <div>
                    <h5 class="text-lg font-semibold">Event Planner</h5>
                    <p class="m-0 text-white/80">Making event planning simple and efficient.</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="m-0 text-white/80">&copy; <?php echo date('Y'); ?> Event Planner. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Navbar active + user area
        (async function initNavbar(){
            const path = window.location.pathname;
            const home = document.getElementById('navHome');
            const events = document.getElementById('navEvents');
            if (path === '/' || path.endsWith('index.php')) home.classList.add('bg-white/10','text-white');
            if (path.endsWith('events.php')) events.classList.add('bg-white/10','text-white');
            try {
                const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
                const data = res.ok ? await res.json() : { user: null };
                const user = data.user;
                const area = document.getElementById('userArea');
                const mobileArea = document.getElementById('mobileUserArea');
                if (user) {
                    area.innerHTML = `<span class="hidden sm:inline">Hello, ${user.username || user.email}</span> <button id="logoutBtn" class="ml-3 px-3 py-1 rounded bg-white/10 hover:bg-white/20">Logout</button>`;
                    mobileArea.innerHTML = `<div class="text-sm text-gray-400 mb-2">Hello, ${user.username || user.email}</div><button id="mobileLogoutBtn" class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-white/10 rounded-md">Logout</button>`;
                    
                    document.getElementById('logoutBtn').addEventListener('click', async () => {
                        await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' });
                        window.location.reload();
                    });
                    document.getElementById('mobileLogoutBtn').addEventListener('click', async () => {
                        await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' });
                        window.location.reload();
                    });
                } else {
                    area.innerHTML = `<a href="/login.php" class="px-3 py-1 rounded bg-white/10 hover:bg-white/20">Log in</a>`;
                    mobileArea.innerHTML = `<a href="/login.php" class="block px-3 py-2 text-sm text-gray-300 hover:bg-white/10 rounded-md">Log in</a>`;
                }
            } catch {}
        })();

        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }
        async function fetchEvents() {
            const res = await fetch('/api/events.php');
            const data = await res.json();
            return data.events || [];
        }
        async function register(eventId) {
            const res = await fetch('/api/registrations.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ event_id: eventId })
            });
            if (!res.ok) {
                alert('Registration failed');
                return;
            }
            render();
        }
        async function unregister(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, {
                method: 'DELETE',
                credentials: 'same-origin'
            });
            if (!res.ok) {
                alert('Unregister failed');
                return;
            }
            render();
        }
        function formatDate(dateStr) {
            try { return new Date(dateStr).toLocaleDateString(); } catch { return dateStr; }
        }
        function eventCard(evt, user) {
            const canManage = user && user.role === 'organizer' && Number(user.id) === Number(evt.organizer_id);
            const actions = [];
            if (!user) {
                actions.push('<a class="text-blue-600 underline text-sm" href="/login.php">Log in to register</a>');
            } else if (user.role === 'attendee') {
                actions.push(`<button class="px-3 py-1 bg-gray-900 text-white rounded text-sm hover:bg-black transition" data-action="register" data-id="${evt.id}">Register</button>`);
                actions.push(`<button class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition" data-action="unregister" data-id="${evt.id}">Unregister</button>`);
            } else if (canManage) {
                actions.push('<span class="text-sm text-gray-600">You are the organizer</span>');
            }
            return `
                <div class="border rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition">
                    <h3 class="font-semibold text-lg mb-2">${evt.title}</h3>
                    <p class="text-sm text-gray-600 mb-2">${formatDate(evt.event_date)} • ${evt.location}</p>
                    <p class="text-gray-800 text-sm mb-4 line-clamp-3">${evt.description || 'No description available.'}</p>
                    <div class="flex flex-wrap gap-2">${actions.join(' ')}</div>
                </div>
            `;
        }
        async function render() {
            const [{ user }, events] = await Promise.all([fetchMe(), fetchEvents()]);
            const list = document.getElementById('eventsList');
            list.innerHTML = events.map(e => eventCard(e, user)).join('');
            list.querySelectorAll('button[data-action]')?.forEach(btn => {
                const id = Number(btn.getAttribute('data-id'));
                const action = btn.getAttribute('data-action');
                btn.addEventListener('click', () => action === 'register' ? register(id) : unregister(id));
            });
        }
        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        render();
    </script>
</body>
</html>
