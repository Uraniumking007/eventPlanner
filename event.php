<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
    <div class="container py-4 py-lg-5">
        <a href="/events.php" class="text-decoration-none text-secondary small mb-3 d-inline-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Back to events
        </a>
        <div id="stickyHeader" class="sticky-top bg-body border-bottom shadow-sm d-none">
            <div class="container py-2">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="text-truncate">
                        <div id="stickyTitle" class="fw-semibold">Event</div>
                        <div id="stickyMeta" class="small text-secondary d-none d-sm-block"></div>
                    </div>
                    <div id="stickyStatus"></div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div id="eventContainer" class="border rounded-3 shadow-sm overflow-hidden bg-white">
                    <div id="eventImage"></div>
                    <div class="p-3 p-lg-4">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <h1 id="eventTitle" class="h3 fw-bold mb-0">Loading...</h1>
                                <span id="eventCategory"></span>
                            </div>
                            <div id="eventStatus" class="flex-shrink-0"></div>
                        </div>
                        <div class="mt-2 small text-secondary d-flex flex-wrap align-items-center gap-3" id="eventMeta"></div>
                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <div class="small text-muted">Organizer</div>
                                <div id="eventOrganizer" class="badge text-bg-light"></div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="small text-muted">Registrations</div>
                                <div class="fw-semibold"><span id="eventRegCount">0</span></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h2 class="h5 mb-2">About this event</h2>
                            <p id="eventDescription" class="mb-0"></p>
                        </div>
                        <div class="mt-4 d-flex flex-wrap gap-2" id="actionArea"></div>
                        <div class="mt-4">
                            <h2 class="h6 mb-2">Location map</h2>
                            <div id="eventMap" class="mt-2">
                                <!-- map embed injected by render() -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h6 mb-0">Attendees</h2>
                            <div class="small text-muted" id="attendeeCount">0</div>
                        </div>
                        <div id="attendeesContainer" class="border-top"></div>
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
            const img = evt.image_path ? `<img src="${evt.image_path}" alt="${evt.title}" class="img-fluid w-100" style="height: 320px; object-fit: cover;">` : '';
            document.getElementById('eventImage').innerHTML = img;
            // Title, meta
            document.getElementById('eventTitle').textContent = evt.title;
            const stickyTitle = document.getElementById('stickyTitle');
            if (stickyTitle) stickyTitle.textContent = evt.title;
            document.getElementById('eventCategory').innerHTML = evt.category ? `<span class="badge text-bg-light">${evt.category}</span>` : '';
            const cutoff = evt.registration_close || evt.event_date;
            const dLeft = daysUntil(cutoff);
            const statusLabel = dLeft != null && dLeft >= 0
                ? `<span class="badge text-bg-success">Open</span>`
                : `<span class="badge text-bg-danger">Closed</span>`;
            const badge = dLeft != null ? `<span class="badge rounded-pill text-bg-secondary">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
            document.getElementById('eventStatus').innerHTML = statusLabel;
            const mapHref = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(evt.location || '')}`;
            document.getElementById('eventMeta').innerHTML = `
                <span class="d-inline-flex align-items-center gap-2"><i class="fa-regular fa-calendar"></i> ${formatDate(evt.event_date)} ${badge ? '• ' + badge : ''}</span>
                <span class="d-inline-flex align-items-center gap-2"><i class="fa-solid fa-location-dot"></i> ${evt.location} • <a class="link-primary" href="${mapHref}" target="_blank" rel="noopener">View on map</a></span>
            `;
            const stickyMeta = document.getElementById('stickyMeta');
            if (stickyMeta) stickyMeta.innerHTML = `${formatDate(evt.event_date)} • ${evt.location}`;
            const stickyStatus = document.getElementById('stickyStatus');
            if (stickyStatus) stickyStatus.innerHTML = statusLabel;
            document.getElementById('eventRegCount').textContent = String(Number(evt.registration_count || 0));
            // Render rich text description safely
            (function renderDescription(){
                const target = document.getElementById('eventDescription');
                const raw = String(evt.description || '').trim();
                if (!target) return;
                if (!raw) { target.textContent = 'No description available.'; return; }
                function sanitizeHtml(html) {
                    try {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const allowedTags = new Set(['P','BR','STRONG','B','EM','I','U','S','A','UL','OL','LI','H1','H2']);
                        const allowedAttrs = { 'A': new Set(['href','target','rel']) };
                        const walker = doc.createTreeWalker(doc.body, NodeFilter.SHOW_ELEMENT, null);
                        const toRemove = [];
                        while (walker.nextNode()) {
                            const el = walker.currentNode;
                            if (!allowedTags.has(el.tagName)) { toRemove.push(el); continue; }
                            // Strip disallowed attributes
                            Array.from(el.attributes).forEach(attr => {
                                const ok = (allowedAttrs[el.tagName] && allowedAttrs[el.tagName].has(attr.name.toLowerCase()));
                                if (!ok) el.removeAttribute(attr.name);
                            });
                            if (el.tagName === 'A') {
                                const href = el.getAttribute('href') || '';
                                if (!/^https?:\/\//i.test(href) && !href.startsWith('#') && !href.startsWith('/')) {
                                    el.removeAttribute('href');
                                }
                                el.setAttribute('rel','noopener');
                                if (!el.getAttribute('target')) el.setAttribute('target','_blank');
                            }
                        }
                        toRemove.forEach(n => n.replaceWith(...Array.from(n.childNodes)));
                        return doc.body.innerHTML;
                    } catch { return ''; }
                }
                target.innerHTML = sanitizeHtml(raw);
            })();
            document.getElementById('eventOrganizer').textContent = evt.organizer_name ? `@${evt.organizer_name}` : 'Unknown';
            // Map embed (only once)
            const mapEmbedSrc = `https://www.google.com/maps?q=${encodeURIComponent(evt.location || '')}&output=embed`;
            const mapContainer = document.getElementById('eventMap');
            if (mapContainer && !mapInitialized) {
                mapContainer.innerHTML = `<div class="rounded border overflow-hidden">
                    <iframe class="w-100" style="height: 320px" src="${mapEmbedSrc}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>`;
                mapInitialized = true;
            }

            // Actions
            const actionArea = document.getElementById('actionArea');
            const actions = [];
            if (!user) {
                actions.push('<a class="btn btn-dark" href="/login.php">Log in to register</a>');
            } else if (user.role === 'attendee') {
                const isClosed = dLeft == null || dLeft < 0;
                const registered = await isUserRegistered(id);
                if (!registered) {
                    actions.push(`<button id="btnRegister" class="btn ${isClosed ? 'btn-secondary' : 'btn-primary'}" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>Register now</button>`);
                } else {
                    actions.push(`<button id="btnUnregister" class="btn btn-outline-danger" data-id="${evt.id}">Unregister</button>`);
                }
            } else if (user.role === 'organizer' && Number(user.id) === Number(evt.organizer_id)) {
                actions.push('<span class="small text-secondary">You are the organizer</span>');
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
                    container.innerHTML = '<div class="small text-secondary pt-2">No attendees yet.</div>';
                } else {
                    container.innerHTML = attendees.map(a => `
                        <div class="py-2 d-flex align-items-center justify-content-between border-top">
                            <div class="small">
                                <div class="fw-medium">${a.username}</div>
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
                        ? `<span class="badge text-bg-success">Open</span>`
                        : `<span class="badge text-bg-danger">Closed</span>`;
                    const badge = dLeft != null ? `<span class="badge rounded-pill text-bg-secondary">${dLeft >= 0 ? dLeft + ' days left' : 'Closed'}</span>` : '';
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
                        btnRegister.className = `btn ${isClosed ? 'btn-secondary' : 'btn-primary'}`;
                    }
                    if (btnUnregister && isClosed) {
                        btnUnregister.classList.add('d-none');
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

        // Sticky header visibility on scroll
        (function setupStickyHeader(){
            const sticky = document.getElementById('stickyHeader');
            const container = document.getElementById('eventContainer');
            function onScroll(){
                if (!sticky || !container) return;
                const threshold = container.getBoundingClientRect().top + window.scrollY + 200;
                if (window.scrollY > threshold) {
                    sticky.classList.remove('hidden');
                } else {
                    sticky.classList.add('hidden');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            setTimeout(onScroll, 0);
        })();
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


