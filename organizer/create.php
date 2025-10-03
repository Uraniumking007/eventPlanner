<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Event Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Hero Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="h3 fw-bold mb-2">Create New Event</h1>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-info-circle me-2"></i>Fill in the details below to create a new event
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="/dashboard.php" class="btn btn-outline-custom">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <div class="row g-3">
                <form id="createEventForm" class="col-12 col-lg-8 card border-0 shadow-sm rounded-3 p-3 p-md-4" enctype="multipart/form-data">
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
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted">Image URL</label>
                            <input name="image_path" class="form-control" placeholder="https://..." />
                            <div class="small text-secondary mt-1">Optional. Used if no file is uploaded.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small text-muted">Upload Image</label>
                            <input name="image_file" type="file" accept="image/*" class="form-control" />
                            <div class="small text-secondary mt-1">PNG, JPG, or WebP. Max 5 MB.</div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small text-muted">Description</label>
                        <div id="descEditor" class="form-control" style="height: 180px; padding:0;">
                            <div class="ql-editor"></div>
                        </div>
                        <textarea name="description" id="descriptionHidden" class="d-none"></textarea>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <p class="small text-secondary mb-0">Give attendees the details they need.</p>
                            <span class="small text-muted" id="descCounter">0/1000</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between gap-2 pt-3 border-top">
                        <a href="/dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-gradient">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </button>
                        </div>
                    </div>
                </form>
                <aside class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden sticky-top" style="top: 20px;">
                        <div class="card-header bg-gradient text-white border-0 py-2" style="background: var(--gradient-primary);">
                            <h2 class="small fw-bold mb-0">
                                <i class="fas fa-eye me-2"></i>Live Preview
                            </h2>
                            <div class="small opacity-90">How your event will look</div>
                        </div>
                        <div id="previewCard">
                            <div>
                                <img id="previewImage" src="" class="w-100 d-none" style="height: 180px; object-fit: cover;" alt="Event image preview">
                                <div class="p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h3 id="previewTitle" class="h6 mb-0 fw-bold">Your Event Title</h3>
                                        <span class="badge bg-light text-dark small">Draft</span>
                                    </div>
                                    <div class="small text-muted mb-2" id="previewMeta">
                                        <i class="fas fa-calendar me-1"></i>Date • <i class="fas fa-map-marker-alt me-1"></i>Location
                                    </div>
                                    <p class="small text-muted mb-0" id="previewDesc">Your description will appear here. Add details to attract attendees.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script>
        var quill;
        // Ensure only organizers can access
        (async function guard(){
            try {
                const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
                const data = res.ok ? await res.json() : { user: null };
                if (!data.user || data.user.role !== 'organizer') { window.location.href = '/login.php'; }
            } catch { window.location.href = '/login.php'; }
        })();

        async function createEvent(payload) {
            const res = await fetch('/api/events.php', {
                method: 'POST',
                credentials: 'same-origin',
                body: payload
            });
            if (!res.ok) throw new Error('Create failed');
            return (await res.json()).event;
        }

        // Character counters + live preview
        (function initUX(){
            const form = document.getElementById('createEventForm');
            const title = form.elements['title'];
            const location = form.elements['location'];
            const image = form.elements['image_path'];
            const imageFile = form.elements['image_file'];
            const desc = form.elements['description'];
            const date = form.elements['event_date'];
            const titleCounter = document.getElementById('titleCounter');
            const locationCounter = document.getElementById('locationCounter');
            const descCounter = document.getElementById('descCounter');
            // Quill editor
            quill = new Quill('#descEditor', { theme: 'snow', placeholder: 'Agenda, speakers, who should attend, etc.', modules: { toolbar: [[{ header: [1,2,false] }], ['bold','italic','underline','strike'], [{ list: 'ordered' }, { list: 'bullet' }], ['link'], ['clean']] } });
            if (desc && desc.value) { try { quill.root.innerHTML = desc.value; } catch {} }
            const pTitle = document.getElementById('previewTitle');
            const pMeta = document.getElementById('previewMeta');
            const pDesc = document.getElementById('previewDesc');
            const pImg = document.getElementById('previewImage');

            const fmt = (d) => { try { return new Date(d).toLocaleString(); } catch { return d; } };
            const updateCounters = () => {
                titleCounter.textContent = `${title.value.length}/120`;
                locationCounter.textContent = `${location.value.length}/160`;
                try { descCounter.textContent = `${Math.max(0, (quill.getText()||'').trim().length)}/1000`; } catch { descCounter.textContent = '0/1000'; }
            };
            const updatePreview = () => {
                pTitle.textContent = title.value || 'Your Event Title';
                const metaDate = date.value ? fmt(date.value) : 'Date';
                const metaLoc = location.value || 'Location';
                pMeta.textContent = `${metaDate} • ${metaLoc}`;
                pDesc.innerHTML = (quill.root.innerHTML && quill.getText().trim().length) ? quill.root.innerHTML : 'Your description will appear here. Add details to attract attendees.';
                const file = imageFile && imageFile.files && imageFile.files[0];
                const url = (image.value || '').trim();
                if (file) {
                    const objectUrl = URL.createObjectURL(file);
                    pImg.src = objectUrl;
                    pImg.classList.remove('d-none');
                } else if (url) {
                    pImg.src = url;
                    pImg.classList.remove('d-none');
                } else {
                    pImg.classList.add('d-none');
                    pImg.removeAttribute('src');
                }
            };
            ['input','change'].forEach(ev => {
                title.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                location.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                quill.on(ev === 'input' ? 'text-change' : 'selection-change', () => { updateCounters(); updatePreview(); });
                date.addEventListener(ev, updatePreview);
                image.addEventListener(ev, updatePreview);
                imageFile.addEventListener(ev, updatePreview);
            });
            document.getElementById('resetBtn').addEventListener('click', () => { form.reset(); updateCounters(); updatePreview(); });
            updateCounters(); updatePreview();
        })();

        function setFieldError(inputEl, errorEl, show) {
            if (show) { inputEl.classList.add('is-invalid'); errorEl.classList.remove('d-none'); }
            else { inputEl.classList.remove('is-invalid'); errorEl.classList.add('d-none'); }
        }

        document.getElementById('createEventForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('descriptionHidden').value = (typeof quill !== 'undefined' && quill && quill.root) ? quill.root.innerHTML : '';
            const fd = new FormData(e.currentTarget);
            // Simple validation
            const form = e.currentTarget;
            const titleEl = form.elements['title'];
            const dateEl = form.elements['event_date'];
            const locEl = form.elements['location'];
            const titleError = document.getElementById('titleError');
            const dateError = document.getElementById('dateError');
            const locationError = document.getElementById('locationError');
            let ok = true;
            if (!String(fd.get('title')||'').trim()) { setFieldError(titleEl, titleError, true); ok = false; } else { setFieldError(titleEl, titleError, false); }
            if (!String(fd.get('event_date')||'').trim()) { setFieldError(dateEl, dateError, true); ok = false; } else { setFieldError(dateEl, dateError, false); }
            if (!String(fd.get('location')||'').trim()) { setFieldError(locEl, locationError, true); ok = false; } else { setFieldError(locEl, locationError, false); }
            if (!ok) return;
            try {
                const evt = await createEvent(fd);
                window.location.href = '/organizer/edit.php?id=' + encodeURIComponent(evt.id);
            } catch (err) {
                alert('Failed to create event');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
</body>
</html>



