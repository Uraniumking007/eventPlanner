<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard - Event Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col">
    <?php $showDashboardLink = true; include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-1">
    <div class="max-w-8xl mx-auto px-4 py-6 lg:py-10">
        <div class="mb-6 lg:mb-8">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 p-[1px] shadow-lg">
                <div class="rounded-2xl bg-white/90 backdrop-blur-sm px-5 py-5 lg:px-7 lg:py-6 flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">Organizer Dashboard</h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-1">Create and manage your events, view attendees, and see stats.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="newEventBtn" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition shadow"><i class="fas fa-plus mr-2"></i>New Event</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="p-[1px] rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 shadow">
                <div class="p-5 rounded-2xl bg-white">
                    <div class="text-gray-500 text-sm">My Events</div>
                    <div id="statMyEvents" class="text-3xl font-extrabold text-gray-900">0</div>
                </div>
            </div>
            <div class="p-[1px] rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 shadow">
                <div class="p-5 rounded-2xl bg-white">
                    <div class="text-gray-500 text-sm">Total Registrations</div>
                    <div id="statMyRegistrations" class="text-3xl font-extrabold text-gray-900">0</div>
                </div>
            </div>
            <div class="p-[1px] rounded-2xl bg-gradient-to-r from-amber-500 to-rose-500 shadow">
                <div class="p-5 rounded-2xl bg-white">
                    <div class="text-gray-500 text-sm">Recent Visits (site-wide)</div>
                    <div id="statVisits" class="text-3xl font-extrabold text-gray-900">0</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Your Events</h2>
                </div>
                <div id="eventsList" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Attendees</h2>
                    <select id="attendeesEventSelect" class="border rounded-md px-2 py-1 text-sm"></select>
                </div>
                <div id="attendeesList" class="bg-white rounded-2xl border border-gray-100 shadow divide-y"></div>
            </div>
        </div>
    </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <div id="eventModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <h3 id="modalTitle" class="font-semibold text-gray-900">New Event</h3>
                <button id="modalClose" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <form id="eventForm" class="p-5 grid gap-3">
                <input type="hidden" name="id" />
                <div>
                    <label class="block text-sm mb-1">Title</label>
                    <input name="title" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Date</label>
                    <input name="event_date" type="datetime-local" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Location</label>
                    <input name="location" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Image URL</label>
                    <input name="image_path" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="modalCancel" class="px-3 py-2 rounded border hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        (async function initNavbar(){
            const path = window.location.pathname;
            const home = document.getElementById('navHome');
            const events = document.getElementById('navEvents');
            const dash = document.getElementById('navDashboard');
            if (path === '/' || path.endsWith('index.php')) home.classList.add('bg-white/10','text-white');
            if (path.endsWith('events.php')) events.classList.add('bg-white/10','text-white');
            if (path.endsWith('dashboard.php')) dash.classList.add('bg-white/10','text-white');
            try {
                const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
                const data = res.ok ? await res.json() : { user: null };
                const user = data.user;
                const area = document.getElementById('userArea');
                const mobileArea = document.getElementById('mobileUserArea');
                if (user) {
                    area.innerHTML = `<span class="hidden sm:inline">Hello, ${user.username || user.email}</span> <button id="logoutBtn" class="ml-3 px-3 py-1 rounded bg-white/10 hover:bg-white/20">Logout</button>`;
                    mobileArea.innerHTML = `<div class="text-sm text-gray-400 mb-2">Hello, ${user.username || user.email}</div><button id="mobileLogoutBtn" class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-white/10 rounded-md">Logout</button>`;
                    document.getElementById('logoutBtn').addEventListener('click', async () => { await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' }); window.location.href = '/'; });
                    document.getElementById('mobileLogoutBtn').addEventListener('click', async () => { await fetch('/api/auth.php?action=logout', { method: 'POST', credentials: 'same-origin' }); window.location.href = '/'; });
                } else {
                    window.location.href = '/login.php';
                }
            } catch {
                window.location.href = '/login.php';
            }
        })();

        async function me() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }

        async function fetchAllEvents() {
            const res = await fetch('/api/events.php', { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load events');
            const data = await res.json();
            return data.events || [];
        }

        async function fetchAttendees(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('Failed to load attendees');
            return (await res.json()).attendees || [];
        }

        async function createEvent(payload) {
            const res = await fetch('/api/events.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(payload) });
            if (!res.ok) throw new Error('Create failed');
            return (await res.json()).event;
        }

        async function updateEvent(id, payload) {
            const res = await fetch('/api/events.php?id=' + id, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, credentials: 'same-origin', body: JSON.stringify(payload) });
            if (!res.ok) throw new Error('Update failed');
            return (await res.json()).event;
        }

        async function deleteEvent(id) {
            const res = await fetch('/api/events.php?id=' + id, { method: 'DELETE', credentials: 'same-origin' });
            if (!res.ok) throw new Error('Delete failed');
        }

        function formatDate(dateStr) { try { return new Date(dateStr).toLocaleString(); } catch { return dateStr; } }

        function eventCard(evt) {
            return `
                <div class="border rounded-lg overflow-hidden bg-white shadow-sm">
                    ${evt.image_path ? `<img src="${evt.image_path}" class="w-full h-36 object-cover">` : ''}
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold">${evt.title}</h3>
                            <div class="flex items-center gap-2">
                                <button data-action="edit" data-id="${evt.id}" class="text-blue-600 hover:underline text-sm">Edit</button>
                                <button data-action="delete" data-id="${evt.id}" class="text-red-600 hover:underline text-sm">Delete</button>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">${formatDate(evt.event_date)} • ${evt.location}</div>
                        <p class="text-sm text-gray-800 mt-2 line-clamp-3">${evt.description || ''}</p>
                    </div>
                </div>
            `;
        }

        function renderAttendeesList(list) {
            const root = document.getElementById('attendeesList');
            if (!list.length) { root.innerHTML = '<div class="p-4 text-sm text-gray-600">No attendees yet.</div>'; return; }
            root.innerHTML = list.map(u => `<div class="p-3 text-sm flex items-center justify-between"><div><div class="font-medium">${u.username || u.email}</div><div class="text-gray-500">${u.email}</div></div></div>`).join('');
        }

        function openModal(values) {
            const modal = document.getElementById('eventModal');
            const form = document.getElementById('eventForm');
            document.getElementById('modalTitle').textContent = values && values.id ? 'Edit Event' : 'New Event';
            form.reset();
            ['id','title','event_date','location','image_path','description'].forEach(k => { if (values && values[k] != null) form.elements[k].value = k === 'event_date' ? (values[k]?.slice(0,16) || '') : values[k]; });
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        async function render() {
            const [{ user }, allEvents] = await Promise.all([me(), fetchAllEvents()]);
            if (!user || user.role !== 'organizer') { window.location.href = '/login.php'; return; }
            const myEvents = allEvents.filter(e => Number(e.organizer_id) === Number(user.id));

            document.getElementById('statMyEvents').textContent = String(myEvents.length);

            // Compute total registrations for my events via attendees fetch sequentially (small lists expected)
            let totalRegs = 0;
            for (const e of myEvents) {
                try { const attendees = await fetchAttendees(e.id); totalRegs += attendees.length; } catch {}
            }
            document.getElementById('statMyRegistrations').textContent = String(totalRegs);

            // visits (site-wide) for quick signal
            try {
                const sRes = await fetch('/api/stats.php');
                if (sRes.ok) {
                    const s = await sRes.json();
                    document.getElementById('statVisits').textContent = String(s.visits || 0);
                }
            } catch {}

            const list = document.getElementById('eventsList');
            list.innerHTML = myEvents.map(eventCard).join('');
            list.querySelectorAll('button[data-action]')?.forEach(btn => {
                const id = Number(btn.getAttribute('data-id'));
                const action = btn.getAttribute('data-action');
                const evt = myEvents.find(e => Number(e.id) === id);
                btn.addEventListener('click', async () => {
                    if (action === 'edit') {
                        openModal(evt);
                    } else if (action === 'delete') {
                        if (!confirm('Delete this event?')) return;
                        try { await deleteEvent(id); render(); } catch { alert('Delete failed'); }
                    }
                });
            });

            // attendees panel
            const select = document.getElementById('attendeesEventSelect');
            select.innerHTML = myEvents.map(e => `<option value="${e.id}">${e.title}</option>`).join('');
            if (myEvents.length) {
                select.value = String(myEvents[0].id);
                try { renderAttendeesList(await fetchAttendees(myEvents[0].id)); } catch { renderAttendeesList([]); }
            } else {
                renderAttendeesList([]);
            }
            select.addEventListener('change', async () => {
                try { renderAttendeesList(await fetchAttendees(Number(select.value))); } catch { renderAttendeesList([]); }
            });
        }

        // Modal wiring
        document.getElementById('newEventBtn').addEventListener('click', () => openModal(null));
        document.getElementById('modalClose').addEventListener('click', closeModal);
        document.getElementById('modalCancel').addEventListener('click', closeModal);
        document.getElementById('eventModal').addEventListener('click', (e) => { if (e.target === e.currentTarget) closeModal(); });
        document.getElementById('eventForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.currentTarget);
            const id = fd.get('id');
            const payload = {
                title: String(fd.get('title') || ''),
                event_date: String(fd.get('event_date') || ''),
                location: String(fd.get('location') || ''),
                image_path: String(fd.get('image_path') || ''),
                description: String(fd.get('description') || ''),
            };
            try {
                if (id) await updateEvent(Number(id), payload); else await createEvent(payload);
                closeModal();
                render();
            } catch {
                alert('Save failed');
            }
        });

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        render();
    </script>
</body>
</html>


