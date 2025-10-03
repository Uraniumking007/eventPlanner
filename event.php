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
<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Sticky Header -->
        <div id="stickyHeader" class="sticky-top bg-white border-bottom shadow-sm d-none" style="z-index: 1020;">
            <div class="container py-3">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                        <a href="/events.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="min-w-0">
                            <div id="stickyTitle" class="fw-bold text-truncate">Event</div>
                            <div id="stickyMeta" class="small text-muted d-none d-sm-block text-truncate"></div>
                        </div>
                    </div>
                    <div id="stickyStatus" class="flex-shrink-0"></div>
                </div>
            </div>
        </div>

        <div class="container py-4">
            <!-- Back Button -->
            <a href="/events.php" class="btn btn-outline-secondary mb-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Events
            </a>

            <div class="row g-4">
                <!-- Main Content -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <!-- Event Image -->
                        <div id="eventImage"></div>
                        
                        <!-- Event Details -->
                        <div class="card-body p-4 p-lg-5">
                            <!-- Title & Status -->
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                <div class="flex-grow-1">
                                    <h1 id="eventTitle" class="display-6 fw-bold mb-2">Loading...</h1>
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="eventCategory"></span>
                                        <span id="eventStatus"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Meta Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-start gap-3 p-3 bg-light rounded">
                                        <div class="text-primary">
                                            <i class="fas fa-calendar-alt fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted mb-1">Date & Time</div>
                                            <div id="eventDate" class="fw-semibold"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-start gap-3 p-3 bg-light rounded">
                                        <div class="text-danger">
                                            <i class="fas fa-map-marker-alt fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="small text-muted mb-1">Location</div>
                                            <div id="eventLocation" class="fw-semibold text-truncate"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Organizer & Registration Stats -->
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 border rounded">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Organized By</div>
                                            <div id="eventOrganizer" class="fw-bold"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 border rounded">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Registrations</div>
                                            <div class="fw-bold">
                                                <span id="eventRegCount">0</span>
                                                <span class="text-muted small">attendees</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <h2 class="h4 fw-bold mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>About This Event
                                </h2>
                                <div id="eventDescription" class="text-muted" style="line-height: 1.8;"></div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="actionArea" class="d-flex flex-wrap gap-3 mb-4"></div>

                            <!-- Location Map -->
                            <div class="mt-5">
                                <h2 class="h4 fw-bold mb-3">
                                    <i class="fas fa-map text-danger me-2"></i>Location
                                </h2>
                                <div id="eventMap" class="rounded overflow-hidden shadow-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-12 col-lg-4">
                    <!-- Attendees Card -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="h5 mb-0 fw-bold">
                                    <i class="fas fa-users text-primary me-2"></i>Attendees
                                </h2>
                                <span id="attendeeCount" class="badge bg-primary rounded-pill">0</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="attendeesContainer" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                    </div>

                    <!-- Event Info Card -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient text-white py-3" style="background: var(--gradient-primary);">
                            <h3 class="h6 mb-0 fw-bold">
                                <i class="fas fa-info-circle me-2"></i>Quick Info
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <span class="text-muted small">Status</span>
                                <span id="quickStatus"></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <span class="text-muted small">Category</span>
                                <span id="quickCategory" class="fw-semibold"></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-2">
                                <span class="text-muted small">Time Left</span>
                                <span id="quickTimeLeft" class="fw-semibold"></span>
                            </div>
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
            } catch { 
                return false; 
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
            try { 
                return new Date(dateStr).toLocaleString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch { 
                return dateStr; 
            }
        }

        function daysUntil(dateStr) {
            try {
                const now = new Date();
                const end = new Date(dateStr);
                const ms = end.setHours(23,59,59,999) - now.getTime();
                const days = Math.ceil(ms / (1000 * 60 * 60 * 24));
                return isNaN(days) ? null : days;
            } catch { 
                return null; 
            }
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
            document.title = `${evt.title} • Event Details`;
            
            // Event Image
            const imgContainer = document.getElementById('eventImage');
            if (evt.image_path) {
                imgContainer.innerHTML = `
                    <div class="position-relative" style="height: 400px; overflow: hidden;">
                        <img src="${evt.image_path}" alt="${evt.title}" class="w-100 h-100" style="object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.7) 100%);">
                            <div class="text-white">
                                <h2 class="h3 fw-bold mb-0">${evt.title}</h2>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Title
            document.getElementById('eventTitle').textContent = evt.title;
            document.getElementById('stickyTitle').textContent = evt.title;
            
            // Category
            const categoryBadge = evt.category ? `<span class="badge bg-light text-dark border">${evt.category}</span>` : '';
            document.getElementById('eventCategory').innerHTML = categoryBadge;
            document.getElementById('quickCategory').textContent = evt.category || 'N/A';
            
            // Status
            const cutoff = evt.registration_close || evt.event_date;
            const dLeft = daysUntil(cutoff);
            const isSuspended = Number(evt.suspended || 0) === 1;
            
            let statusBadge = '';
            if (isSuspended) {
                statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Suspended</span>';
            } else if (dLeft !== null && dLeft >= 0) {
                statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Open</span>';
            } else {
                statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Closed</span>';
            }
            
            document.getElementById('eventStatus').innerHTML = statusBadge;
            document.getElementById('stickyStatus').innerHTML = statusBadge;
            document.getElementById('quickStatus').innerHTML = statusBadge;
            
            // Time left
            if (dLeft !== null && dLeft >= 0) {
                document.getElementById('quickTimeLeft').innerHTML = `<span class="text-primary">${dLeft} ${dLeft === 1 ? 'day' : 'days'}</span>`;
            } else {
                document.getElementById('quickTimeLeft').textContent = 'Event closed';
            }
            
            // Date and Location
            document.getElementById('eventDate').textContent = formatDate(evt.event_date);
            document.getElementById('eventLocation').textContent = evt.location;
            document.getElementById('stickyMeta').textContent = `${formatDate(evt.event_date)} • ${evt.location}`;
            
            // Organizer
            document.getElementById('eventOrganizer').textContent = evt.organizer_name ? `@${evt.organizer_name}` : 'Unknown';
            
            // Registration count
            document.getElementById('eventRegCount').textContent = String(Number(evt.registration_count || 0));
            
            // Suspended notice
            if (isSuspended) {
                const reason = String(evt.suspend_reason || '').trim();
                const notice = document.createElement('div');
                notice.className = 'alert alert-warning mx-4 mt-4';
                notice.innerHTML = `
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                        <div>
                            <strong>Event Suspended</strong>
                            ${reason ? `<div class="mt-1">${reason}</div>` : ''}
                        </div>
                    </div>
                `;
                const cardBody = document.querySelector('.card-body');
                cardBody?.insertBefore(notice, cardBody.firstChild);
            }
            
            // Description
            const descriptionEl = document.getElementById('eventDescription');
            const rawDesc = String(evt.description || '').trim();
            if (!rawDesc) {
                descriptionEl.innerHTML = '<p class="text-muted fst-italic">No description available.</p>';
            } else {
                descriptionEl.innerHTML = sanitizeHtml(rawDesc);
            }
            
            // Action buttons
            const actionArea = document.getElementById('actionArea');
            const actions = [];
            
            if (isSuspended) {
                actions.push(`
                    <div class="alert alert-warning mb-0 w-100">
                        <i class="fas fa-info-circle me-2"></i>Registration is unavailable while the event is suspended.
                    </div>
                `);
            } else if (!user) {
                actions.push('<a class="btn btn-gradient btn-lg px-5" href="/login.php"><i class="fas fa-sign-in-alt me-2"></i>Log In to Register</a>');
            } else if (user.role === 'attendee') {
                const isClosed = dLeft == null || dLeft < 0;
                const registered = await isUserRegistered(id);
                
                if (!registered) {
                    actions.push(`
                        <button id="btnRegister" class="btn ${isClosed ? 'btn-secondary' : 'btn-gradient'} btn-lg px-5" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>
                            <i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register Now'}
                        </button>
                    `);
                } else {
                    actions.push(`
                        <button id="btnUnregister" class="btn btn-outline-danger btn-lg px-5" data-id="${evt.id}">
                            <i class="fas fa-times me-2"></i>Unregister
                        </button>
                    `);
                }
            } else if (user.role === 'organizer' && Number(user.id) === Number(evt.organizer_id)) {
                actions.push('<div class="alert alert-info mb-0"><i class="fas fa-crown me-2"></i>You are the organizer of this event</div>');
            }
            
            actionArea.innerHTML = actions.join(' ');
            
            // Bind action buttons
            document.getElementById('btnRegister')?.addEventListener('click', async () => {
                const ok = await register(id);
                if (ok) await render();
            });
            
            document.getElementById('btnUnregister')?.addEventListener('click', async () => {
                const ok = await unregister(id);
                if (ok) await render();
            });
            
            // Map
            if (!mapInitialized) {
                const mapSrc = `https://www.google.com/maps?q=${encodeURIComponent(evt.location || '')}&output=embed`;
                document.getElementById('eventMap').innerHTML = `
                    <iframe class="w-100 border-0" style="height: 350px;" src="${mapSrc}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                `;
                mapInitialized = true;
            }
            
            // Attendees
            try {
                const attendees = await fetchAttendees(id);
                const container = document.getElementById('attendeesContainer');
                const countEl = document.getElementById('attendeeCount');
                
                countEl.textContent = String(attendees.length);
                
                if (!attendees.length) {
                    container.innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0 small">No attendees yet</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = attendees.map((a, index) => `
                        <div class="d-flex align-items-center gap-3 p-3 ${index !== attendees.length - 1 ? 'border-bottom' : ''}">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-weight: 600;">
                                ${a.username.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">${a.username}</div>
                                <div class="small text-muted">Attendee</div>
                            </div>
                        </div>
                    `).join('');
                }
            } catch {}
        }

        function sanitizeHtml(html) {
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const allowedTags = new Set(['P','BR','STRONG','B','EM','I','U','S','A','UL','OL','LI','H1','H2','H3']);
                const allowedAttrs = { 'A': new Set(['href','target','rel']) };
                const walker = doc.createTreeWalker(doc.body, NodeFilter.SHOW_ELEMENT, null);
                const toRemove = [];
                
                while (walker.nextNode()) {
                    const el = walker.currentNode;
                    if (!allowedTags.has(el.tagName)) { 
                        toRemove.push(el); 
                        continue; 
                    }
                    
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
            } catch { 
                return ''; 
            }
        }

        function refreshAttendees() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            
            fetch(`/api/registrations.php?event_id=${id}`)
                .then(res => res.ok ? res.json() : { attendees: [] })
                .then(data => {
                    const attendees = data.attendees || [];
                    const container = document.getElementById('attendeesContainer');
                    const countEl = document.getElementById('attendeeCount');
                    
                    if (countEl) countEl.textContent = String(attendees.length);
                    if (!container) return;
                    
                    if (!attendees.length) {
                        container.innerHTML = `
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-2x mb-3 opacity-25"></i>
                                <p class="mb-0 small">No attendees yet</p>
                            </div>
                        `;
                    } else {
                        container.innerHTML = attendees.map((a, index) => `
                            <div class="d-flex align-items-center gap-3 p-3 ${index !== attendees.length - 1 ? 'border-bottom' : ''}">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; font-weight: 600;">
                                    ${a.username.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">${a.username}</div>
                                    <div class="small text-muted">Attendee</div>
                                </div>
                            </div>
                        `).join('');
                    }
                });
        }

        function refreshCounts() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            
            $.getJSON(`/api/events.php?id=${id}`)
                .done(data => {
                    const count = Number(data?.event?.registration_count || 0);
                    $('#eventRegCount').text(String(count));
                });
        }

        function refreshStatus() {
            const id = Number(new URL(window.location.href).searchParams.get('id'));
            if (!id) return;
            
            $.getJSON(`/api/events.php?id=${id}`)
                .done(data => {
                    const evt = data?.event;
                    if (!evt) return;
                    
                    const dLeft = daysUntil(evt.event_date);
                    const isClosed = dLeft == null || dLeft < 0;
                    
                    const btnRegister = document.getElementById('btnRegister');
                    if (btnRegister) {
                        btnRegister.disabled = isClosed;
                        btnRegister.className = `btn ${isClosed ? 'btn-secondary' : 'btn-gradient'} btn-lg px-5`;
                        btnRegister.innerHTML = `<i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register Now'}`;
                    }
                    
                    if (dLeft !== null && dLeft >= 0) {
                        document.getElementById('quickTimeLeft').innerHTML = `<span class="text-primary">${dLeft} ${dLeft === 1 ? 'day' : 'days'}</span>`;
                    } else {
                        document.getElementById('quickTimeLeft').textContent = 'Event closed';
                    }
                });
        }

        // Sticky header visibility
        (function setupStickyHeader() {
            const sticky = document.getElementById('stickyHeader');
            const threshold = 400;
            
            function onScroll() {
                if (!sticky) return;
                if (window.scrollY > threshold) {
                    sticky.classList.remove('d-none');
                } else {
                    sticky.classList.add('d-none');
                }
            }
            
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();

        // Track visit
        fetch('/api/visits.php', { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ page_url: window.location.pathname + window.location.search }) 
        });

        // Initial render
        render();

        // Live updates
        setInterval(refreshCounts, 10000);
        setInterval(refreshAttendees, 15000);
        setInterval(refreshStatus, 60000);
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
