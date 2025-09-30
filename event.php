<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-1">
    <div class="max-w-5xl mx-auto px-4 py-6 lg:py-10">
        <a href="/events.php" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Back to events
        </a>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div id="eventContainer" class="bg-white border rounded-xl overflow-hidden shadow">
                    <div id="eventImage"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <h1 id="eventTitle" class="text-2xl font-bold text-gray-900">Loading...</h1>
                                <span id="eventCategory"></span>
                            </div>
                            <div id="eventStatus"></div>
                        </div>
                        <div class="mt-2 text-sm text-gray-600 flex flex-wrap items-center gap-3" id="eventMeta"></div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Organizer</div>
                                <div id="eventOrganizer" class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded bg-gray-100 text-gray-800 text-sm"></div>
                            </div>
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Registrations</div>
                                <div class="text-gray-900"><span id="eventRegCount" class="font-semibold">0</span></div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">About this event</h2>
                            <p id="eventDescription" class="text-gray-800 whitespace-pre-line"></p>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-3" id="actionArea"></div>
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">Location map</h2>
                            <div id="eventMap" class="mt-2">
                                <!-- map embed injected by render() -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="bg-white border rounded-xl overflow-hidden shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Attendees</h2>
                            <div class="text-sm text-gray-500" id="attendeeCount">0</div>
                        </div>
                        <div id="attendeesContainer" class="divide-y"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        let mapInitialized = false;
        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }
        async function fetchEvent(id) {
            const res = await fetch(`/api/events.php?id=${id}`);
            if (!res.ok) throw new Error('Not found');
            const data = await res.json();
            return data.event;
        }
        async function fetchAttendees(id) {
            const res = await fetch(`/api/registrations.php?event_id=${id}`);
            if (!res.ok) return [];
            const data = await res.json();
            return data.attendees || [];
        }
        async function isUserRegistered(eventId) {
            try {
                const me = await fetchMe();
                const currentUser = me.user;
                if (!currentUser) return false;
                const attendees = await fetchAttendees(eventId);
                return attendees.some(a => Number(a.id) === Number(currentUser.id));
            } catch { return false; }
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
                return false;
            }
            return true;
        }
        async function unregister(eventId) {
            const res = await fetch('/api/registrations.php?event_id=' + eventId, {
                method: 'DELETE',
                credentials: 'same-origin'
            });
            if (!res.ok) {
                alert('Unregister failed');
                return false;
            }
            return true;
        }
        function formatDate(dateStr) {
            try { return new Date(dateStr).toLocaleString(); } catch { return dateStr; }
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
        function getParam(name) {
            const url = new URL(window.location.href);
            return url.searchParams.get(name);
        }

        async function render() {
            const id = Number(getParam('id'));
            if (!id) return;
            const [{ user }, evt] = await Promise.all([fetchMe(), fetchEvent(id)]);
            // Set page title
            try { document.title = `${evt.title} • Event Details`; } catch {}
            // Image
            const img = evt.image_path ? `<img src="${evt.image_path}" alt="${evt.title}" class="w-full h-64 object-cover">` : '';
            document.getElementById('eventImage').innerHTML = img;
            // Title, meta
            document.getElementById('eventTitle').textContent = evt.title;
            document.getElementById('eventCategory').innerHTML = evt.category ? `<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">${evt.category}</span>` : '';
            const dLeft = daysUntil(evt.event_date);
            const statusLabel = dLeft != null && dLeft >= 0
                ? `<span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Open</span>`
                : `<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Closed</span>`;
            const badge = dLeft != null ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
            document.getElementById('eventStatus').innerHTML = statusLabel;
            const mapHref = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(evt.location || '')}`;
            document.getElementById('eventMeta').innerHTML = `
                <span class="inline-flex items-center gap-2"><i class="fa-regular fa-calendar"></i> ${formatDate(evt.event_date)} ${badge ? '• ' + badge : ''}</span>
                <span class="inline-flex items-center gap-2"><i class="fa-solid fa-location-dot"></i> ${evt.location} • <a class="text-blue-600 hover:underline" href="${mapHref}" target="_blank" rel="noopener">View on map</a></span>
            `;
            document.getElementById('eventRegCount').textContent = String(Number(evt.registration_count || 0));
            document.getElementById('eventDescription').textContent = evt.description || 'No description available.';
            document.getElementById('eventOrganizer').textContent = evt.organizer_name ? `@${evt.organizer_name}` : 'Unknown';
            // Map embed (only once)
            const mapEmbedSrc = `https://www.google.com/maps?q=${encodeURIComponent(evt.location || '')}&output=embed`;
            const mapContainer = document.getElementById('eventMap');
            if (mapContainer && !mapInitialized) {
                mapContainer.innerHTML = `<div class="w-full overflow-hidden rounded-lg border">
                    <iframe class="w-full h-64 md:h-80" src="${mapEmbedSrc}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>`;
                mapInitialized = true;
            }

            // Actions
            const actionArea = document.getElementById('actionArea');
            const actions = [];
            if (!user) {
                actions.push('<a class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-black transition" href="/login.php">Log in to register</a>');
            } else if (user.role === 'attendee') {
                const isClosed = dLeft == null || dLeft < 0;
                const registered = await isUserRegistered(id);
                if (!registered) {
                    actions.push(`<button id="btnRegister" class="px-4 py-2 ${isClosed ? 'bg-gray-300 cursor-not-allowed' : 'bg-gray-900 hover:bg-black'} text-white rounded transition" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>Register now</button>`);
                } else {
                    actions.push(`<button id="btnUnregister" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition" data-id="${evt.id}">Unregister</button>`);
                }
            } else if (user.role === 'organizer' && Number(user.id) === Number(evt.organizer_id)) {
                actions.push('<span class="text-sm text-gray-600">You are the organizer</span>');
            }
            actionArea.innerHTML = actions.join(' ');
            document.getElementById('btnRegister')?.addEventListener('click', async (e) => {
                const ok = await register(id);
                if (ok) { await render(); }
            });
            document.getElementById('btnUnregister')?.addEventListener('click', async (e) => {
                const ok = await unregister(id);
                if (ok) { await render(); }
            });

            // Attendees list
            try {
                const attendees = await fetchAttendees(id);
                const container = document.getElementById('attendeesContainer');
                const countEl = document.getElementById('attendeeCount');
                countEl.textContent = `${attendees.length} attendee${attendees.length === 1 ? '' : 's'}`;
                if (!attendees.length) {
                    container.innerHTML = '<div class="text-sm text-gray-500">No attendees yet.</div>';
                } else {
                    container.innerHTML = attendees.map(a => `
                        <div class="py-3 flex items-center justify-between">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">${a.username}</div>
                            </div>
                        </div>
                    `).join('');
                }
            } catch {}
        }

        function refreshAttendees() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            fetch(`/api/registrations.php?event_id=${id}`)
                .then((res) => res.ok ? res.json() : { attendees: [] })
                .then((data) => {
                    const attendees = data.attendees || [];
                    const container = document.getElementById('attendeesContainer');
                    const countEl = document.getElementById('attendeeCount');
                    if (countEl) countEl.textContent = `${attendees.length} attendee${attendees.length === 1 ? '' : 's'}`;
                    if (!container) return;
                    if (!attendees.length) {
                        container.innerHTML = '<div class="text-sm text-gray-500">No attendees yet.</div>';
                    } else {
                        container.innerHTML = attendees.map(a => `
                            <div class="py-3 flex items-center justify-between">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">${a.username}</div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(() => { /* no-op */ });
        }

        function refreshCounts() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            $.getJSON(`/api/events.php?id=${id}`)
                .done((data) => {
                    const count = Number(data?.event?.registration_count || 0);
                    $('#eventRegCount').text(String(count));
                });
        }

        function refreshStatus() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            $.getJSON(`/api/events.php?id=${id}`)
                .done((data) => {
                    const evt = data?.event;
                    if (!evt) return;
                    const dLeft = daysUntil(evt.event_date);
                    const statusLabel = dLeft != null && dLeft >= 0
                        ? `<span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Open</span>`
                        : `<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Closed</span>`;
                    const badge = dLeft != null ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
                    const mapHref = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(evt.location || '')}`;
                    const statusEl = document.getElementById('eventStatus');
                    const metaEl = document.getElementById('eventMeta');
                    if (statusEl) statusEl.innerHTML = statusLabel;
                    if (metaEl) metaEl.innerHTML = `
                        <span class="inline-flex items-center gap-2"><i class="fa-regular fa-calendar"></i> ${formatDate(evt.event_date)} ${badge ? '• ' + badge : ''}</span>
                        <span class="inline-flex items-center gap-2"><i class="fa-solid fa-location-dot"></i> ${evt.location} • <a class="text-blue-600 hover:underline" href="${mapHref}" target="_blank" rel="noopener">View on map</a></span>
                    `;
                    const isClosed = dLeft == null || dLeft < 0;
                    const btnRegister = document.getElementById('btnRegister');
                    const btnUnregister = document.getElementById('btnUnregister');
                    if (btnRegister) {
                        btnRegister.disabled = isClosed;
                        btnRegister.className = `px-4 py-2 ${isClosed ? 'bg-gray-300 cursor-not-allowed' : 'bg-gray-900 hover:bg-black'} text-white rounded transition`;
                    }
                    if (btnUnregister && isClosed) {
                        // If event closes, hide unregister in favor of clarity
                        btnUnregister.classList.add('hidden');
                    }
                });
        }

        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname + window.location.search }) });
        render();
        // Poll live counts every 10s
        setInterval(refreshCounts, 10000);
        // Poll attendees list every 15s
        setInterval(refreshAttendees, 15000);
        // Poll open/closed status every 60s
        setInterval(refreshStatus, 60000);
    </script>
</body>
</html>


