<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Event Planner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-4 py-8 lg:py-10">
            <div class="mb-6">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 p-[1px] shadow-lg">
                    <div class="rounded-2xl bg-white px-5 py-5 lg:px-7 lg:py-6 flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">Create Event</h1>
                            <p class="text-gray-600 text-sm sm:text-base mt-1">Fill in the details below to create a new event.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="/dashboard.php" class="inline-flex items-center px-3 py-2 text-sm bg-gray-900 text-white rounded-lg hover:bg-black transition shadow"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <form id="createEventForm" class="bg-white p-6 rounded-2xl border border-gray-100 shadow grid gap-4">
                    <div>
                        <label class="block text-sm mb-1 font-medium">Title</label>
                        <input name="title" maxlength="120" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="e.g. Summer Tech Meetup 2025" required />
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500">A short, descriptive name for your event.</p>
                            <span class="text-xs text-gray-400" id="titleCounter">0/120</span>
                        </div>
                        <p class="text-xs text-red-600 mt-1 hidden" id="titleError">Title is required.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1 font-medium">Date & Time</label>
                            <input name="event_date" type="datetime-local" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                            <p class="text-xs text-gray-500 mt-1">Use your local timezone.</p>
                            <p class="text-xs text-red-600 mt-1 hidden" id="dateError">Date & time is required.</p>
                        </div>
                        <div>
                            <label class="block text-sm mb-1 font-medium">Location</label>
                            <input name="location" maxlength="160" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="e.g. 123 Market St, SF or Online" required />
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500">Venue address or \"Online\" for virtual.</p>
                                <span class="text-xs text-gray-400" id="locationCounter">0/160</span>
                            </div>
                            <p class="text-xs text-red-600 mt-1 hidden" id="locationError">Location is required.</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Image URL</label>
                        <input name="image_path" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="https://..." />
                        <p class="text-xs text-gray-500 mt-1">Paste a public image URL. Optional.</p>
                    </div>
                    <div>
                        <label class="block text-sm mb-1 font-medium">Description</label>
                        <textarea name="description" rows="6" maxlength="1000" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Agenda, speakers, who should attend, etc."></textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500">Give attendees the details they need.</p>
                            <span class="text-xs text-gray-400" id="descCounter">0/1000</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 pt-2">
                        <a href="/dashboard.php" class="px-3 py-2 rounded border hover:bg-gray-50">Cancel</a>
                        <div class="flex items-center gap-2">
                            <button type="button" id="resetBtn" class="px-3 py-2 rounded border hover:bg-gray-50">Reset</button>
                            <button type="submit" class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black">Create Event</button>
                        </div>
                    </div>
                </form>
                <aside class="bg-white p-0 rounded-2xl border border-gray-100 shadow overflow-hidden">
                    <div class="px-5 py-4 border-b">
                        <h2 class="text-sm font-semibold text-gray-700">Live Preview</h2>
                        <p class="text-xs text-gray-500">This is how your event will look.</p>
                    </div>
                    <div id="previewCard" class="p-0">
                        <div class="border-0 rounded-none overflow-hidden">
                            <img id="previewImage" src="" class="w-full h-56 object-cover hidden" alt="Event image preview">
                            <div class="p-5">
                                <div class="flex items-center justify-between">
                                    <h3 id="previewTitle" class="font-semibold">Your Event Title</h3>
                                    <span class="text-xs text-gray-500">Draft</span>
                                </div>
                                <div class="text-sm text-gray-600" id="previewMeta">Date • Location</div>
                                <p class="text-sm text-gray-800 mt-2 line-clamp-5" id="previewDesc">Your description will appear here. Add details to attract attendees.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
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
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
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
            const desc = form.elements['description'];
            const date = form.elements['event_date'];
            const titleCounter = document.getElementById('titleCounter');
            const locationCounter = document.getElementById('locationCounter');
            const descCounter = document.getElementById('descCounter');
            const pTitle = document.getElementById('previewTitle');
            const pMeta = document.getElementById('previewMeta');
            const pDesc = document.getElementById('previewDesc');
            const pImg = document.getElementById('previewImage');

            const fmt = (d) => { try { return new Date(d).toLocaleString(); } catch { return d; } };
            const updateCounters = () => {
                titleCounter.textContent = `${title.value.length}/120`;
                locationCounter.textContent = `${location.value.length}/160`;
                descCounter.textContent = `${desc.value.length}/1000`;
            };
            const updatePreview = () => {
                pTitle.textContent = title.value || 'Your Event Title';
                const metaDate = date.value ? fmt(date.value) : 'Date';
                const metaLoc = location.value || 'Location';
                pMeta.textContent = `${metaDate} • ${metaLoc}`;
                pDesc.textContent = desc.value || 'Your description will appear here. Add details to attract attendees.';
                const url = (image.value || '').trim();
                if (url) { pImg.src = url; pImg.classList.remove('hidden'); } else { pImg.classList.add('hidden'); pImg.removeAttribute('src'); }
            };
            ['input','change'].forEach(ev => {
                title.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                location.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                desc.addEventListener(ev, () => { updateCounters(); updatePreview(); });
                date.addEventListener(ev, updatePreview);
                image.addEventListener(ev, updatePreview);
            });
            document.getElementById('resetBtn').addEventListener('click', () => { form.reset(); updateCounters(); updatePreview(); });
            updateCounters(); updatePreview();
        })();

        function setFieldError(inputEl, errorEl, show) {
            if (show) { inputEl.classList.add('border-red-300'); errorEl.classList.remove('hidden'); }
            else { inputEl.classList.remove('border-red-300'); errorEl.classList.add('hidden'); }
        }

        document.getElementById('createEventForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.currentTarget);
            const payload = {
                title: String(fd.get('title') || ''),
                event_date: String(fd.get('event_date') || ''),
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
                const evt = await createEvent(payload);
                window.location.href = '/organizer/edit.php?id=' + encodeURIComponent(evt.id);
            } catch (err) {
                alert('Failed to create event');
            }
        });
    </script>
</body>
</html>



