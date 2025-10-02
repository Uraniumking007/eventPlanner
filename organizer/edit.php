<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Event Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="container py-4 py-lg-5">
            <section class="py-4 border rounded-3 shadow-sm mb-4" style="background:linear-gradient(135deg,#f8fbff 0%, #f9f7ff 100%)">
                <div class="container-fluid">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div>
                            <h1 class="h3 fw-bold mb-1">Edit Event</h1>
                            <p class="text-secondary mb-0">Update your event details below.</p>
                        </div>
                        <div>
                            <a href="/dashboard.php" class="btn btn-dark btn-sm"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row g-4">
                <form id="editEventForm" class="col-12 col-lg-8 border rounded-3 shadow-sm bg-white p-4">
                    <input type="hidden" name="id" />
                    <div>
                        <label class="form-label small text-muted">Title</label>
                        <input name="title" maxlength="120" class="form-control" placeholder="e.g. Summer Tech Meetup 2025" required />
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="small text-secondary mb-0">A short, descriptive name for your event.</p>
                            <span class="small text-muted" id="titleCounter">0/120</span>
                        </div>
                        <div class="invalid-feedback d-none" id="titleError">Title is required.</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted">Date & Time</label>
                            <input name="event_date" type="datetime-local" class="form-control" required />
                            <div class="small text-secondary mt-1">Use your local timezone.</div>
                            <div class="invalid-feedback d-none" id="dateError">Date & time is required.</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted">Location</label>
                            <input name="location" maxlength="160" class="form-control" placeholder="e.g. 123 Market St, SF or Online" required />
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <p class="small text-secondary mb-0">Venue address or "Online" for virtual.</p>
                                <span class="small text-muted" id="locationCounter">0/160</span>
                            </div>
                            <div class="invalid-feedback d-none" id="locationError">Location is required.</div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small text-muted">Registration closes</label>
                        <input name="registration_close" type="datetime-local" class="form-control" />
                        <div class="small text-secondary mt-1">Optional. If set, registrations close at this time.</div>
                    </div>
                    <div>
                        <label class="form-label small text-muted">Image URL</label>
                        <input name="image_path" class="form-control" placeholder="https://..." />
                        <div class="small text-secondary mt-1">Paste a public image URL. Optional.</div>
                    </div>
                    <div>
                        <label class="form-label small text-muted">Description</label>
                        <div id="descEditor" class="form-control" style="height: 220px; padding:0;">
                            <div class="ql-editor"></div>
                        </div>
                        <textarea name="description" id="descriptionHidden" class="d-none"></textarea>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="small text-secondary mb-0">Give attendees the details they need.</p>
                            <span class="small text-muted" id="descCounter">0/1000</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 pt-2">
                        <button id="deleteBtn" type="button" class="btn btn-outline-danger">Delete Event</button>
                        <div class="d-flex align-items-center gap-2">
                            <a href="/dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-dark">Save Changes</button>
                        </div>
                    </div>
                </form>
                <aside class="col-12 col-lg-4 border rounded-3 shadow-sm bg-white overflow-hidden">
                    <div class="p-3 border-bottom">
                        <h2 class="small fw-semibold mb-0">Live Preview</h2>
                        <div class="small text-secondary">This is how your event will look.</div>
                    </div>
                    <div id="previewCard">
                        <div>
                            <img id="previewImage" src="" class="w-100 d-none" style="height: 224px; object-fit: cover;" alt="Event image preview">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 id="previewTitle" class="h6 mb-0">Your Event Title</h3>
                                    <span class="small text-secondary">Draft</span>
                                </div>
                                <div class="small text-secondary" id="previewMeta">Date • Location</div>
                                <p class="small mt-2 mb-0" id="previewDesc">Your description will appear here. Add details to attract attendees.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        function getParam(name){ const u = new URL(window.location.href); return u.searchParams.get(name); }
        const eventId = Number(getParam('id')) || null;

        // Guard + ownership check + load data
        (async function init(){
            try {
                const [meRes, evtRes] = await Promise.all([
                    fetch('/api/auth.php?action=me', { credentials: 'same-origin' }),
                    eventId ? fetch('/api/events.php?id=' + eventId, { credentials: 'same-origin' }) : Promise.resolve(null)
                ]);
                const meData = meRes.ok ? await meRes.json() : { user: null };
                const user = meData.user;
                if (!user || user.role !== 'organizer') { window.location.href = '/login.php'; return; }
                if (!eventId) { window.location.href = '/dashboard.php'; return; }
                if (!evtRes || !evtRes.ok) { alert('Event not found'); window.location.href = '/dashboard.php'; return; }
                const evtData = await evtRes.json();
                const evt = evtData.event;
                if (!evt || Number(evt.organizer_id) !== Number(user.id)) { alert('Forbidden'); window.location.href = '/dashboard.php'; return; }

                // Fill form
                const form = document.getElementById('editEventForm');
                form.elements['id'].value = String(evt.id);
                form.elements['title'].value = evt.title || '';
                form.elements['event_date'].value = (evt.event_date || '').slice(0,16);
                form.elements['location'].value = evt.location || '';
                form.elements['image_path'].value = evt.image_path || '';
                form.elements['description'].value = evt.description || '';
                try {
                    if (evt.registration_close) {
                        form.elements['registration_close'].value = String(evt.registration_close).slice(0,16);
                    }
                } catch {}
                
                // Initialize UX features after form is populated
                initUX();
            } catch {
                window.location.href = '/login.php';
            }
        })();

        async function updateEvent(id, payload) {
            const res = await fetch('/api/events.php?id=' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });
            if (!res.ok) throw new Error('Update failed');
            return (await res.json()).event;
        }

        async function deleteEvent(id) {
            const res = await fetch('/api/events.php?id=' + id, { method: 'DELETE', credentials: 'same-origin' });
            if (!res.ok) throw new Error('Delete failed');
        }

        // Character counters + live preview
        function initUX(){
            const form = document.getElementById('editEventForm');
            const title = form.elements['title'];
            const location = form.elements['location'];
            const image = form.elements['image_path'];
            const desc = form.elements['description'];
            const date = form.elements['event_date'];
            const titleCounter = document.getElementById('titleCounter');
            const locationCounter = document.getElementById('locationCounter');
            const descCounter = document.getElementById('descCounter');
            const pTitle = document.getElementById('previewTitle');
            const pMeta = document.getElementById('previewMeta');
            const pDesc = document.getElementById('previewDesc');
            const pImg = document.getElementById('previewImage');
            const pStatus = document.getElementById('previewStatus');
            // Quill editor
            const quill = new Quill('#descEditor', { theme: 'snow', placeholder: 'Agenda, speakers, who should attend, etc.', modules: { toolbar: [[{ header: [1,2,false] }], ['bold','italic','underline','strike'], [{ list: 'ordered' }, { list: 'bullet' }], ['link'], ['clean']] } });
            if (desc && desc.value) { try { quill.root.innerHTML = desc.value; } catch {} }

            const fmt = (d) => { try { return new Date(d).toLocaleString(); } catch { return d; } };
            const daysUntil = (d) => {
                try {
                    const now = new Date();
                    const end = new Date(d);
                    const ms = end.setHours(23,59,59,999) - now.getTime();
                    const days = Math.ceil(ms / (1000 * 60 * 60 * 24));
                    return isNaN(days) ? null : days;
                } catch { return null; }
            };
            const updateCounters = () => {
                titleCounter.textContent = `${title.value.length}/120`;
                locationCounter.textContent = `${location.value.length}/160`;
                try { descCounter.textContent = `${Math.max(0, (quill.getText()||'').trim().length)}/1000`; } catch { descCounter.textContent = '0/1000'; }
            };
            const updatePreview = () => {
                pTitle.textContent = title.value || 'Your Event Title';
                const metaDate = date.value ? fmt(date.value) : 'Date';
                const metaLoc = location.value || 'Location';
                // Build meta with map link like event.php
                const mapHref = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(location.value || '')}`;
                pMeta.innerHTML = `
                    <span class="inline-flex items-center gap-2"><i class="fa-regular fa-calendar"></i> ${metaDate}</span>
                    <span class="inline-flex items-center gap-2"><i class="fa-solid fa-location-dot"></i> ${metaLoc} ${location.value ? '• <a class="text-blue-600 hover:underline" href="' + mapHref + '" target="_blank" rel="noopener">View on map</a>' : ''}</span>
                `;
                pDesc.innerHTML = (quill.root.innerHTML && quill.getText().trim().length) ? quill.root.innerHTML : 'Your description will appear here. Add details to attract attendees.';
                const url = (image.value || '').trim();
                if (url) { pImg.src = url; pImg.classList.remove('d-none'); } else { pImg.classList.add('d-none'); pImg.removeAttribute('src'); }
                // Status like event.php
                if (pStatus) {
                    const dLeft = date.value ? daysUntil(date.value) : null;
                    if (dLeft == null) { pStatus.innerHTML = ''; }
                    else if (dLeft >= 0) { pStatus.innerHTML = '<span class="badge text-bg-success">Open</span>';
                    } else { pStatus.innerHTML = '<span class="badge text-bg-danger">Closed</span>'; }
                }
            };
            ['input','change'].forEach(ev => {
                title.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                location.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                quill.on(ev === 'input' ? 'text-change' : 'selection-change', () => { updateCounters(); updatePreview(); });
                date.addEventListener(ev, updatePreview);
                image.addEventListener(ev, updatePreview);
            });
            updateCounters(); updatePreview();
        }

        function setFieldError(inputEl, errorEl, show) {
            if (show) { inputEl.classList.add('is-invalid'); errorEl.classList.remove('d-none'); }
            else { inputEl.classList.remove('is-invalid'); errorEl.classList.add('d-none'); }
        }

        document.getElementById('editEventForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('descriptionHidden').value = document.querySelector('#descEditor .ql-editor').innerHTML;
            const fd = new FormData(e.currentTarget);
            const id = Number(fd.get('id'));
            const payload = {
                title: String(fd.get('title') || ''),
                event_date: String(fd.get('event_date') || ''),
                registration_close: String(fd.get('registration_close') || ''),
                location: String(fd.get('location') || ''),
                image_path: String(fd.get('image_path') || ''),
                description: String(fd.get('description') || ''),
            };
            // Simple validation
            const form = e.currentTarget;
            const titleEl = form.elements['title'];
            const dateEl = form.elements['event_date'];
            const locEl = form.elements['location'];
            const titleError = document.getElementById('titleError');
            const dateError = document.getElementById('dateError');
            const locationError = document.getElementById('locationError');
            let ok = true;
            if (!payload.title.trim()) { setFieldError(titleEl, titleError, true); ok = false; } else { setFieldError(titleEl, titleError, false); }
            if (!payload.event_date.trim()) { setFieldError(dateEl, dateError, true); ok = false; } else { setFieldError(dateEl, dateError, false); }
            if (!payload.location.trim()) { setFieldError(locEl, locationError, true); ok = false; } else { setFieldError(locEl, locationError, false); }
            if (!ok) return;
            try {
                await updateEvent(id, payload);
                window.location.href = '/dashboard.php';
            } catch (err) {
                alert('Failed to save changes');
            }
        });

        document.getElementById('deleteBtn').addEventListener('click', async () => {
            if (!eventId) return;
            if (!confirm('Delete this event?')) return;
            try { await deleteEvent(eventId); window.location.href = '/dashboard.php'; } catch { alert('Delete failed'); }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
</body>
</html>



