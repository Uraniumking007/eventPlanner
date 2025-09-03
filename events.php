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
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col">
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

    <main class="flex-1">
    <div class="max-w-8xl mx-auto px-4 py-6 lg:py-10">
        <div class="mb-6 lg:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">Explore Events</h1>
            <p class="text-gray-600 text-sm sm:text-base mt-1">Find and register for upcoming events that match your interests.</p>
            <div class="text-xs sm:text-sm text-gray-500 mt-2">Showing <span id="eventsCount">0</span> events</div>
        </div>
        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <input id="searchInput" type="text" placeholder="Search events..." class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div>
                <select id="categorySelect" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="">All categories</option>
                    <option value="Corporate">Corporate</option>
                    <option value="Social">Social</option>
                    <option value="Tech">Tech</option>
                    <option value="Workshop">Workshop</option>
                </select>
            </div>
            <div>
                <select id="sortSelect" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="date_asc">Date: Soonest first</option>
                    <option value="date_desc">Date: Latest first</option>
                    <option value="title_asc">Title: A-Z</option>
                    <option value="title_desc">Title: Z-A</option>
                </select>
            </div>
        </div>

        <div id="eventsList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6"></div>
    </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6 mt-auto">
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
        const dummyEvents = [
            { id: 1, title: 'Tech Meetup 2025', event_date: new Date(Date.now() + 86400000 * 2).toISOString(), location: 'San Francisco, CA', description: 'Network with developers and discuss the latest in AI.', organizer_id: 1, category: 'Tech', image: 'assets/images/slide3.png' },
            { id: 2, title: 'Corporate Strategy Summit', event_date: new Date(Date.now() + 86400000 * 10).toISOString(), location: 'New York, NY', description: 'Insights into corporate planning and leadership.', organizer_id: 2, category: 'Corporate', image: 'assets/images/slide1.jpg' },
            { id: 3, title: 'Community Social Night', event_date: new Date(Date.now() + 86400000 * 5).toISOString(), location: 'Austin, TX', description: 'An evening of fun, games, and community bonding.', organizer_id: 3, category: 'Social', image: 'assets/images/slide2.jpeg' },
            { id: 4, title: 'Hands-on Workshop: Web Security', event_date: new Date(Date.now() + 86400000 * 15).toISOString(), location: 'Remote', description: 'Practical security techniques for modern web apps.', organizer_id: 1, category: 'Workshop', image: 'assets/images/slide4.jpg' },
            { id: 5, title: 'Design Thinking Bootcamp', event_date: new Date(Date.now() + 86400000 * 20).toISOString(), location: 'Chicago, IL', description: 'Learn to solve problems creatively with design thinking.', organizer_id: 2, category: 'Workshop', image: 'assets/images/slide5.jpg' },
            { id: 6, title: 'Startup Social Mixer', event_date: new Date(Date.now() + 86400000 * 7).toISOString(), location: 'Los Angeles, CA', description: 'Meet fellow founders and potential collaborators.', organizer_id: 3, category: 'Social', image: 'assets/images/images.jpg' }
        ];

        async function fetchEvents() {
            try {
                const res = await fetch('/api/events.php');
                if (!res.ok) throw new Error('Failed');
                const data = await res.json();
                const apiEvents = data.events || [];
                return apiEvents.length ? apiEvents : dummyEvents;
            } catch {
                return dummyEvents;
            }
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
                <div class="border rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition">
                    ${evt.image ? `<img src="${evt.image}" alt="${evt.title}" class="w-full h-40 object-cover">` : ''}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-semibold text-lg">${evt.title}</h3>
                            ${evt.category ? `<span class=\"px-2 py-1 text-xs rounded bg-gray-100 text-gray-700\">${evt.category}</span>` : ''}
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${formatDate(evt.event_date)} • ${evt.location}</p>
                        <p class="text-gray-800 text-sm mb-4 line-clamp-3">${evt.description || 'No description available.'}</p>
                        <div class="flex flex-wrap gap-2">${actions.join(' ')}</div>
                    </div>
                </div>
            `;
        }
        function applyFilters(events) {
            const q = (document.getElementById('searchInput').value || '').toLowerCase();
            const cat = document.getElementById('categorySelect').value;
            const sort = document.getElementById('sortSelect').value;

            let filtered = events.filter(e =>
                (!q || `${e.title} ${e.location} ${e.description}`.toLowerCase().includes(q)) &&
                (!cat || e.category === cat)
            );

            filtered = filtered.sort((a, b) => {
                if (sort === 'date_asc') return new Date(a.event_date) - new Date(b.event_date);
                if (sort === 'date_desc') return new Date(b.event_date) - new Date(a.event_date);
                if (sort === 'title_asc') return String(a.title).localeCompare(String(b.title));
                if (sort === 'title_desc') return String(b.title).localeCompare(String(a.title));
                return 0;
            });
            return filtered;
        }

        async function render() {
            const [{ user }, events] = await Promise.all([fetchMe(), fetchEvents()]);
            const list = document.getElementById('eventsList');
            const countEl = document.getElementById('eventsCount');
            const renderList = () => {
                const filtered = applyFilters(events);
                list.innerHTML = filtered.map(e => eventCard(e, user)).join('');
                list.querySelectorAll('button[data-action]')?.forEach(btn => {
                    const id = Number(btn.getAttribute('data-id'));
                    const action = btn.getAttribute('data-action');
                    btn.addEventListener('click', () => action === 'register' ? register(id) : unregister(id));
                });
                if (countEl) countEl.textContent = String(filtered.length);
            };

            // Bind inputs once
            document.getElementById('searchInput').addEventListener('input', renderList);
            document.getElementById('categorySelect').addEventListener('change', renderList);
            document.getElementById('sortSelect').addEventListener('change', renderList);

            renderList();
        }
        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        render();
    </script>
</body>
</html>
