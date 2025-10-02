<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard - Event Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php $showDashboardLink = true; include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
    <section class="py-4 py-lg-5 border-bottom" style="background:linear-gradient(135deg,#f8fbff 0%, #f9f7ff 100%)">
        <div class="container">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-1">Organizer Dashboard</h1>
                    <p class="text-secondary mb-0">Create and manage your events, view attendees, and see stats.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/organizer/attendees.php" class="btn btn-outline-secondary"><i class="fas fa-users me-2"></i>Attendees</a>
                    <button id="newEventBtn" class="btn btn-dark"><i class="fas fa-plus me-2"></i>New Event</button>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4 py-lg-5">
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-4">
                <div class="border rounded-3 shadow-sm bg-white p-4">
                    <div class="text-secondary small">My Events</div>
                    <div id="statMyEvents" class="h3 fw-bold mb-0">0</div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="border rounded-3 shadow-sm bg-white p-4">
                    <div class="text-secondary small">Total Registrations</div>
                    <div id="statMyRegistrations" class="h3 fw-bold mb-0">0</div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="border rounded-3 shadow-sm bg-white p-4">
                    <div class="text-secondary small">Recent Visits (site-wide)</div>
                    <div id="statVisits" class="h3 fw-bold mb-0">0</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h2 class="h6 mb-0">Your Events</h2>
                </div>
                <div id="eventsList" class="row g-3"></div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h2 class="h6 mb-0">Attendees</h2>
                    <select id="attendeesEventSelect" class="form-select form-select-sm w-auto"></select>
                </div>
                <div id="attendeesList" class="border rounded-3 shadow-sm bg-white"></div>
            </div>
        </div>
    </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    

    <script>
        // Navbar is handled in includes/navbar.php

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
                <div class="col-12 col-sm-6">
                    <div class="border rounded-3 bg-white shadow-sm overflow-hidden h-100">
                        ${evt.image_path ? `<img src="${evt.image_path}" class="w-100" style="height: 144px; object-fit: cover;">` : ''}
                        <div class="p-3">
                            <h3 class="h6 mb-1">${evt.title}</h3>
                            <div class="small text-secondary mb-2">${formatDate(evt.event_date)} • ${evt.location}</div>
                            <p class="small text-body mb-3">${evt.description || ''}</p>
                            <div class="d-flex gap-2">
                                <button data-action="edit" data-id="${evt.id}" class="btn btn-sm btn-outline-secondary">Edit</button>
                                <button data-action="delete" data-id="${evt.id}" class="btn btn-sm btn-outline-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderAttendeesList(list) {
            const root = document.getElementById('attendeesList');
            if (!list.length) { root.innerHTML = '<div class="p-3 small text-secondary">No attendees yet.</div>'; return; }
            root.innerHTML = list.map(u => `<div class=\"p-3 d-flex align-items-center justify-content-between border-top\"><div><div class=\"fw-medium\">${u.username || u.email}</div><div class=\"text-secondary small\">${u.email}</div></div></div>`).join('');
        }

        // Modal removed (redirecting to create/edit pages)

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
                        window.location.href = '/organizer/edit.php?id=' + id;
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

        // New event goes to dedicated page
        document.getElementById('newEventBtn').addEventListener('click', () => { window.location.href = '/organizer/create.php'; });

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });
        render();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


