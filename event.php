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
    
    <style>
        .event-hero-overlay {
            position: relative;
            min-height: 450px;
            background: linear-gradient(135deg, #0891b2 0%, #14b8a6 100%);
            overflow: hidden;
        }
        .event-hero-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="3" cy="3" r="3"/></g></g></svg>');
            animation: bgScroll 20s linear infinite;
        }
        @keyframes bgScroll {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }
        .event-content-card {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .info-pill {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.2);
            transition: all 0.3s ease;
        }
        .info-pill:hover {
            background: rgba(6, 182, 212, 0.15);
            border-color: rgba(6, 182, 212, 0.3);
            transform: translateY(-2px);
        }
        .attendee-avatar {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            transition: transform 0.2s ease;
        }
        .attendee-item:hover .attendee-avatar {
            transform: scale(1.1);
        }
        .map-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .stat-box {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100" style="background: #f8fafc;">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero Section with Image/Gradient -->
        <div class="event-hero-overlay" id="heroSection">
            <div class="container h-100 d-flex align-items-end pb-5 position-relative" style="z-index: 2;">
                <div class="row w-100 align-items-end">
                    <div class="col-lg-8">
                        <a href="/events.php" class="btn btn-light btn-sm mb-3 shadow-sm mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Events
                        </a>
                        <div id="heroContent">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span id="heroCategory"></span>
                                <span id="heroStatus"></span>
                            </div>
                            <h1 id="heroTitle" class="display-5 fw-bold text-white mb-3">Loading Event...</h1>
                            <div class="d-flex flex-wrap gap-3 text-white">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span id="heroDate" class="small"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="heroLocation" class="small"></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-users"></i>
                                    <span id="heroAttendees" class="small">0 attendees</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0">
                        <div id="heroActionArea"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="row g-4">
                <!-- Main Column -->
                <div class="col-12 col-lg-8">
                    <!-- Content Card -->
                    <div class="card shadow-sm border-0 rounded-4 event-content-card">
                        <div class="card-body p-4">
                            <!-- Suspended Notice -->
                            <div id="suspendedNotice"></div>

                            <!-- Key Info Pills -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="info-pill rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-primary);">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Organized by</div>
                                                <div id="organizerName" class="fw-bold"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-pill rounded-3 p-3 h-100">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--gradient-secondary);">
                                                <i class="fas fa-clock text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small mb-1">Time Remaining</div>
                                                <div id="timeRemaining" class="fw-bold"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--gradient-primary);">
                                        <i class="fas fa-align-left text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <h2 class="h5 mb-0 fw-bold">Event Description</h2>
                                </div>
                                <div id="eventDescription" class="text-muted" style="line-height: 1.8; font-size: 0.95rem;"></div>
                            </div>

                            <!-- Action Buttons -->
                            <div id="actionArea" class="mb-4"></div>

                            <!-- Location Map -->
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: var(--gradient-primary);">
                                        <i class="fas fa-map-marked-alt text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <h2 class="h5 mb-0 fw-bold">Event Location</h2>
                                </div>
                                <div id="eventMap" class="map-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-12 col-lg-4">
                    <!-- Registration Stats -->
                    <div class="card shadow-sm border-0 rounded-4 mb-3">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: var(--gradient-primary);">
                                    <i class="fas fa-ticket-alt text-white fa-2x"></i>
                                </div>
                                <h3 class="h2 fw-bold mb-1" id="regCount">0</h3>
                                <p class="text-muted mb-0">Registered Attendees</p>
                            </div>
                            <div class="stat-box rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Event Status</span>
                                    <span id="sidebarStatus"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendees List -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header border-0 pt-4 px-4 pb-0 bg-white">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="h6 mb-0 fw-bold">
                                    <i class="fas fa-users me-2" style="color: var(--primary-color);"></i>
                                    Attendees
                                </h3>
                                <span id="attendeeCount" class="badge rounded-pill" style="background: var(--gradient-primary);">0</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="attendeesContainer" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Spacing -->
        <div style="height: 80px;"></div>
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
            
            // Hero Section Background Image
            const heroSection = document.getElementById('heroSection');
            if (evt.image_path) {
                heroSection.style.background = `linear-gradient(rgba(8, 145, 178, 0.85), rgba(20, 184, 166, 0.85)), url('${evt.image_path}')`;
                heroSection.style.backgroundSize = 'cover';
                heroSection.style.backgroundPosition = 'center';
            }
            
            // Hero Title
            document.getElementById('heroTitle').textContent = evt.title;
            
            // Hero Category
            const categoryBadge = evt.category ? `<span class="badge bg-light text-dark">${evt.category}</span>` : '';
            document.getElementById('heroCategory').innerHTML = categoryBadge;
            
            // Status
            const cutoff = evt.registration_close || evt.event_date;
            const dLeft = daysUntil(cutoff);
            const isSuspended = Number(evt.suspended || 0) === 1;
            
            let statusBadge = '';
            if (isSuspended) {
                statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-ban me-1"></i>Suspended</span>';
            } else if (dLeft !== null && dLeft >= 0) {
                statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Open</span>';
            } else {
                statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Closed</span>';
            }
            
            document.getElementById('heroStatus').innerHTML = statusBadge;
            document.getElementById('sidebarStatus').innerHTML = statusBadge;
            
            // Hero Date and Location
            document.getElementById('heroDate').textContent = formatDate(evt.event_date);
            document.getElementById('heroLocation').textContent = evt.location;
            
            // Hero Attendees
            const attendeeCount = Number(evt.registration_count || 0);
            document.getElementById('heroAttendees').textContent = `${attendeeCount} ${attendeeCount === 1 ? 'attendee' : 'attendees'}`;
            
            // Organizer
            document.getElementById('organizerName').textContent = evt.organizer_name ? `@${evt.organizer_name}` : 'Unknown';
            
            // Time Remaining
            if (dLeft !== null && dLeft >= 0) {
                document.getElementById('timeRemaining').innerHTML = `<span style="color: var(--primary-color);">${dLeft} ${dLeft === 1 ? 'day' : 'days'} left</span>`;
            } else {
                document.getElementById('timeRemaining').textContent = 'Event has ended';
            }
            
            // Registration count
            document.getElementById('regCount').textContent = String(attendeeCount);
            
            // Suspended notice
            if (isSuspended) {
                const reason = String(evt.suspend_reason || '').trim();
                document.getElementById('suspendedNotice').innerHTML = `
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Event Suspended</h6>
                                ${reason ? `<p class="mb-0 small">${reason}</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
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
            const heroActionArea = document.getElementById('heroActionArea');
            const actionArea = document.getElementById('actionArea');
            
            let heroActions = [];
            let contentActions = [];
            
            if (isSuspended) {
                const suspendedMsg = `
                    <div class="alert alert-warning mb-0 w-100 shadow">
                        <i class="fas fa-info-circle me-2"></i>Registration unavailable - Event suspended
                    </div>
                `;
                heroActions.push(suspendedMsg);
                contentActions.push(`<div class="alert alert-info mb-0 w-100"><i class="fas fa-info-circle me-2"></i>Registration is unavailable while the event is suspended.</div>`);
            } else if (!user) {
                const loginBtn = `
                    <a class="btn btn-light btn-lg shadow-lg w-100" href="/login.php">
                        <i class="fas fa-sign-in-alt me-2"></i>Log In to Register
                    </a>
                `;
                heroActions.push(loginBtn);
                contentActions.push(`<div class="d-grid w-100"><a class="btn btn-gradient btn-lg" href="/login.php"><i class="fas fa-sign-in-alt me-2"></i>Log In to Register</a></div>`);
            } else if (user && (user.role === 'attendee' || user.role === 'admin')) {
                // Attendees and Admins can register for events
                const isClosed = dLeft == null || dLeft < 0;
                const registered = await isUserRegistered(id);
                
                if (!registered) {
                    heroActions.push(`
                        <button id="btnRegisterHero" class="btn ${isClosed ? 'btn-light' : 'btn-light'} btn-lg shadow-lg w-100" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>
                            <i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register Now'}
                        </button>
                    `);
                    contentActions.push(`
                        <div class="d-grid w-100">
                            <button id="btnRegister" class="btn ${isClosed ? 'btn-secondary' : 'btn-gradient'} btn-lg" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>
                                <i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register for This Event'}
                            </button>
                        </div>
                    `);
                } else {
                    heroActions.push(`
                        <button id="btnUnregisterHero" class="btn btn-outline-light btn-lg shadow-lg w-100" data-id="${evt.id}">
                            <i class="fas fa-check-circle me-2"></i>You're Registered
                        </button>
                    `);
                    contentActions.push(`
                        <div class="d-grid w-100">
                            <button id="btnUnregister" class="btn btn-outline-danger btn-lg" data-id="${evt.id}">
                                <i class="fas fa-times me-2"></i>Cancel Registration
                            </button>
                        </div>
                    `);
                }
            } else if (user && user.role === 'organizer') {
                if (Number(user.id) === Number(evt.organizer_id)) {
                    const organizerMsg = `
                        <div class="badge bg-light text-dark p-3 w-100 shadow">
                            <i class="fas fa-crown me-2"></i>You're the Organizer
                        </div>
                    `;
                    heroActions.push(organizerMsg);
                    contentActions.push(`<div class="alert alert-info mb-0 d-flex align-items-center gap-2 w-100"><i class="fas fa-crown"></i><span>You are the organizer of this event</span></div>`);
                } else {
                    // Organizers can register for other people's events
                    const isClosed = dLeft == null || dLeft < 0;
                    const registered = await isUserRegistered(id);
                    
                    if (!registered) {
                        heroActions.push(`
                            <button id="btnRegisterHero" class="btn btn-light btn-lg shadow-lg w-100" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>
                                <i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register Now'}
                            </button>
                        `);
                        contentActions.push(`
                            <div class="d-grid w-100">
                                <button id="btnRegister" class="btn ${isClosed ? 'btn-secondary' : 'btn-gradient'} btn-lg" data-id="${evt.id}" ${isClosed ? 'disabled' : ''}>
                                    <i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register for This Event'}
                                </button>
                            </div>
                        `);
                    } else {
                        heroActions.push(`
                            <button id="btnUnregisterHero" class="btn btn-outline-light btn-lg shadow-lg w-100" data-id="${evt.id}">
                                <i class="fas fa-check-circle me-2"></i>You're Registered
                            </button>
                        `);
                        contentActions.push(`
                            <div class="d-grid w-100">
                                <button id="btnUnregister" class="btn btn-outline-danger btn-lg" data-id="${evt.id}">
                                    <i class="fas fa-times me-2"></i>Cancel Registration
                                </button>
                            </div>
                        `);
                    }
                }
            }
            
            if (heroActionArea) {
                heroActionArea.innerHTML = heroActions.join('');
            }
            if (actionArea) {
                actionArea.innerHTML = contentActions.join('');
            }
            
            // Bind action buttons (both hero and content)
            document.getElementById('btnRegister')?.addEventListener('click', async () => {
                const ok = await register(id);
                if (ok) await render();
            });
            
            document.getElementById('btnRegisterHero')?.addEventListener('click', async () => {
                const ok = await register(id);
                if (ok) await render();
            });
            
            document.getElementById('btnUnregister')?.addEventListener('click', async () => {
                const ok = await unregister(id);
                if (ok) await render();
            });
            
            document.getElementById('btnUnregisterHero')?.addEventListener('click', async () => {
                const ok = await unregister(id);
                if (ok) await render();
            });
            
            // Map
            if (!mapInitialized) {
                const mapSrc = `https://www.google.com/maps?q=${encodeURIComponent(evt.location || '')}&output=embed`;
                document.getElementById('eventMap').innerHTML = `
                    <iframe class="w-100 border-0" style="height: 350px; border-radius: 16px;" src="${mapSrc}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
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
                            <div class="mb-3" style="opacity: 0.3;">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <p class="mb-0">No attendees yet</p>
                            <p class="small mb-0">Be the first to register!</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = attendees.map((a, index) => `
                        <div class="attendee-item d-flex align-items-center gap-3 p-3 ${index !== attendees.length - 1 ? 'border-bottom' : ''}">
                            <div class="attendee-avatar rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="font-size: 0.875rem;">
                                ${a.username.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">${a.username}</div>
                                <div class="text-muted small">Registered attendee</div>
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
                                <div class="mb-3" style="opacity: 0.3;">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <p class="mb-0">No attendees yet</p>
                                <p class="small mb-0">Be the first to register!</p>
                            </div>
                        `;
                    } else {
                        container.innerHTML = attendees.map((a, index) => `
                            <div class="attendee-item d-flex align-items-center gap-3 p-3 ${index !== attendees.length - 1 ? 'border-bottom' : ''}">
                                <div class="attendee-avatar rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="font-size: 0.875rem;">
                                    ${a.username.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">${a.username}</div>
                                    <div class="text-muted small">Registered attendee</div>
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
                    $('#regCount').text(String(count));
                    $('#heroAttendees').text(`${count} ${count === 1 ? 'attendee' : 'attendees'}`);
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
                        btnRegister.className = `btn ${isClosed ? 'btn-secondary' : 'btn-gradient'} btn-lg`;
                        btnRegister.innerHTML = `<i class="fas fa-ticket-alt me-2"></i>${isClosed ? 'Registration Closed' : 'Register for This Event'}`;
                    }
                    
                    const timeEl = document.getElementById('timeRemaining');
                    if (timeEl) {
                        if (dLeft !== null && dLeft >= 0) {
                            timeEl.innerHTML = `<span style="color: var(--primary-color);">${dLeft} ${dLeft === 1 ? 'day' : 'days'} left</span>`;
                        } else {
                            timeEl.textContent = 'Event has ended';
                        }
                    }
                });
        }

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
