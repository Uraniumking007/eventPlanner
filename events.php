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
    <!-- jQuery for AJAX (4.2 requirement) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

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
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Navbar behavior (mobile toggle, active link, avatar menu) is handled in includes/navbar.php

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
            const res = await fetch('/api/events.php');
            if (!res.ok) throw new Error('Failed to load events');
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
        function daysUntil(dateStr) {
            try {
                const now = new Date();
                const end = new Date(dateStr);
                const ms = end.setHours(23,59,59,999) - now.getTime();
                const days = Math.ceil(ms / (1000 * 60 * 60 * 24));
                return isNaN(days) ? null : days;
            } catch { return null; }
        }
        function eventCard(evt, user) {
            const actions = [];
            actions.push(`<a href="/event.php?id=${evt.id}" class="px-3 py-1 border border-gray-300 text-gray-800 rounded text-sm hover:bg-gray-50 transition">More info</a>`);
            const count = Number(evt.registration_count || 0);
            const dLeft = daysUntil(evt.event_date);
            const statusLabel = dLeft != null && dLeft >= 0
                ? `<span class=\"px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700\">Open</span>`
                : `<span class=\"px-2 py-1 text-xs rounded bg-red-100 text-red-700\">Closed</span>`;
            const badge = dLeft != null ? `<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
            return `
                <div class="border rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition">
                    ${evt.image_path ? `<img src="${evt.image_path}" alt="${evt.title}" class="w-full h-40 object-cover">` : ''}
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-semibold text-lg">${evt.title}</h3>
                            <div class="flex items-center gap-2">
                                ${evt.category ? `<span class=\"px-2 py-1 text-xs rounded bg-gray-100 text-gray-700\">${evt.category}</span>` : ''}
                                ${statusLabel}
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${formatDate(evt.event_date)} • ${evt.location} ${badge}</p>
                        <div class="text-xs text-gray-500 mb-2">Registrations: <span class="font-medium reg-count" data-event-id="${evt.id}">${count}</span></div>
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

        // Live Reg Count Update using jQuery + AJAX (4.2)
        (function setupLiveRegistrationCounts() {
            function updateCountsOnce() {
                // Collect visible event IDs from DOM
                const ids = new Set();
                $('.reg-count').each(function () {
                    const id = Number($(this).data('event-id'));
                    if (!Number.isNaN(id)) ids.add(id);
                });
                if (ids.size === 0) return;
                // Fetch each event's latest count and update span
                ids.forEach((id) => {
                    $.getJSON(`/api/events.php?id=${id}`)
                        .done((data) => {
                            const count = Number(data?.event?.registration_count || 0);
                            $(`.reg-count[data-event-id="${id}"]`).text(String(count));
                        });
                });
            }
            // Initial after first render
            setTimeout(updateCountsOnce, 600);
            // Poll every 10 seconds
            setInterval(updateCountsOnce, 10000);
            // Nudge an update shortly after user actions
            document.addEventListener('click', (e) => {
                const t = e.target;
                if (t && t.matches && t.matches('button[data-action="register"], button[data-action="unregister"]')) {
                    setTimeout(updateCountsOnce, 1200);
                }
            });
        })();
    </script>
</body>
</html>
