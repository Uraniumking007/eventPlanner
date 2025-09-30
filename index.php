<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Not Planner</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="hero-section relative overflow-hidden">
        <!-- Background gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50"></div>
        
        <div class="relative max-w-8xl mx-auto px-4">
            <div class="pointer-events-none absolute -top-24 -right-24 w-72 h-72 bg-gradient-to-br from-blue-200/40 to-purple-200/40 rounded-full blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 w-72 h-72 bg-gradient-to-br from-purple-200/40 to-pink-200/40 rounded-full blur-3xl"></div>
            <div class="grid grid-cols-1 items-center justify-center text-center min-h-[85vh] lg:min-h-[90vh]">
                <div class="py-8 md:py-12 px-4 space-y-6">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-800 text-sm font-medium mb-6">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                        Trusted by 1000+ event organizers
                    </div>
                    
                    <h1 class="text-gray-900 font-extrabold text-4xl sm:text-5xl lg:text-7xl mb-6 tracking-tight leading-tight">
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Effortless
                        </span>
                        <br>
                        <span class="text-gray-800">Event Planning</span>
                    </h1>
                    
                    <p class="text-gray-600 text-lg sm:text-xl mb-8 max-w-4xl mx-auto leading-relaxed">
                        From intimate gatherings to large-scale conferences, our platform provides everything you need to create, manage, and host successful events with confidence.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="/events.php" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl ring-1 ring-white/10">
                            <i class="fas fa-calendar-plus mr-3 text-lg"></i>
                            Browse Events
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="/login.php" class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-gray-400 hover:bg-gray-50 transition-all duration-300 bg-white/60 backdrop-blur">
                            <i class="fas fa-user mr-3"></i>
                            Get Started
                        </a>
                    </div>
                    
                    <!-- Stats (dynamic) -->
                    <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto mt-12 pt-8 border-t border-gray-200">
                        <div class="p-[1px] rounded-xl bg-gradient-to-r from-blue-600/60 to-purple-600/60">
                            <div class="rounded-xl bg-white p-4 text-center">
                                <div id="statEvents" class="text-2xl font-extrabold text-gray-900">0</div>
                                <div class="text-sm text-gray-600">Events</div>
                            </div>
                        </div>
                        <div class="p-[1px] rounded-xl bg-gradient-to-r from-emerald-500/60 to-teal-500/60">
                            <div class="rounded-xl bg-white p-4 text-center">
                                <div id="statAttendees" class="text-2xl font-extrabold text-gray-900">0</div>
                                <div class="text-sm text-gray-600">Attendees</div>
                            </div>
                        </div>
                        <div class="p-[1px] rounded-xl bg-gradient-to-r from-amber-500/60 to-rose-500/60">
                            <div class="rounded-xl bg-white p-4 text-center">
                                <div id="statVisits" class="text-2xl font-extrabold text-gray-900">0</div>
                                <div class="text-sm text-gray-600">Visits</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modern Slider -->
                <div class="slider-container px-4 pb-8">
                    <div class="relative">
                        <div class="slider-wrapper rounded-2xl overflow-hidden shadow-2xl bg-white"></div>
                        
                        <!-- Modern Navigation Buttons -->
                        <button class="prev-button absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white text-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center backdrop-blur-sm ring-1 ring-gray-200" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="next-button absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white text-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center backdrop-blur-sm ring-1 ring-gray-200" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        <!-- Dots indicator -->
                        <div id="sliderDots" class="flex justify-center mt-6 space-x-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Why Choose Event Planner?</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Streamline your event management with our powerful features designed for modern organizers.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Easy Management</h3>
                    <p class="text-gray-600">Create and manage events with our intuitive interface. No technical skills required.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Attendee Tracking</h3>
                    <p class="text-gray-600">Monitor registrations, manage capacity, and keep track of your attendees effortlessly.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics & Insights</h3>
                    <p class="text-gray-600">Get detailed insights into your event performance and attendee engagement.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php if($_SESSION['user'].'role' == 'organizer'){ 
            include __DIR__ . '/includes/footer.php'; 
        } else { 
            include __DIR__ . '/includes/footer.php'; 
        } 
    ?>

    <script>
        // Track visit
        fetch('/api/visits.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ page_url: window.location.pathname }) });

        // Navbar behavior (mobile toggle, active link, user area) is handled in includes/navbar.php

        const sliderWrapper = document.querySelector('.slider-wrapper');
        const prevButton = document.querySelector('.prev-button');
        const nextButton = document.querySelector('.next-button');
        const dotsContainer = document.getElementById('sliderDots');

        let currentIndex = 0;
        let slides = [];

        async function loadSlides() {
            try {
                const res = await fetch('/api/events.php');
                if (!res.ok) return [];
                const data = await res.json();
                const events = (data.events || []).filter(e => !!e.image_path);
                return events.slice(0, 5); // limit to 5 slides
            } catch {
                return [];
            }
        }

        function renderSlides(items) {
            sliderWrapper.innerHTML = items.map((e, idx) => `
                <div class=\"slide-item\" style=\"float:left; width:100%\">\n                    <a href=\"/event.php?id=${e.id}\">\n                        <img class=\"w-full h-auto object-cover\" src=\"${e.image_path}\" alt=\"${e.title}\">\n                        <div class=\"slide-content bg-gradient-to-t from-black/80 to-transparent\">\n                            <h3 class=\"text-white font-semibold text-lg sm:text-xl\">${e.title}</h3>\n                            <p class=\"text-white/90 text-sm sm:text-base\">${e.location || ''}</p>\n                        </div>\n                    </a>\n                </div>
            `).join('');
            slides = Array.from(sliderWrapper.querySelectorAll('.slide-item'));
            renderDots(slides.length);
            updateSliderPosition();
        }

        function renderDots(n) {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = Array.from({ length: n }).map((_, i) => `
                <div class=\"dot ${i === currentIndex ? 'bg-blue-600' : 'bg-gray-300'} w-3 h-3 rounded-full cursor-pointer transition-all duration-300\"></div>
            `).join('');
            Array.from(dotsContainer.children).forEach((dot, i) => {
                dot.addEventListener('click', () => goToSlide(i));
            });
        }

        function getSlideWidth() {
            const first = slides[0];
            return first ? first.clientWidth : 0;
        }

        function updateDots() {
            if (!dotsContainer) return;
            Array.from(dotsContainer.children).forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('bg-blue-600');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('bg-blue-600');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function updateSliderPosition() {
            const width = getSlideWidth();
            sliderWrapper.style.whiteSpace = 'nowrap';
            sliderWrapper.style.transform = `translateX(${-currentIndex * width}px)`;
            sliderWrapper.style.transition = 'transform 400ms ease';
            updateDots();
        }

        function goToSlide(index) {
            if (!slides.length) return;
            currentIndex = index;
            if (currentIndex < 0) currentIndex = slides.length - 1;
            if (currentIndex > slides.length - 1) currentIndex = 0;
            updateSliderPosition();
        }

        nextButton.addEventListener('click', () => goToSlide(currentIndex + 1));
        prevButton.addEventListener('click', () => goToSlide(currentIndex - 1));

        let autoPlayInterval = null;
        function startAutoPlay() {
            stopAutoPlay();
            autoPlayInterval = setInterval(() => goToSlide(currentIndex + 1), 4000);
        }
        function stopAutoPlay() {
            if (autoPlayInterval) clearInterval(autoPlayInterval);
        }

        sliderWrapper.addEventListener('mouseenter', stopAutoPlay);
        sliderWrapper.addEventListener('mouseleave', startAutoPlay);
        window.addEventListener('resize', updateSliderPosition);

        (async function initSlider() {
            const items = await loadSlides();
            if (!items.length) return; // keep static layout if no images
            renderSlides(items);
            startAutoPlay();
        })();

        // Load dynamic stats
        (async function loadStats() {
            try {
                const res = await fetch('/api/stats.php');
                if (!res.ok) return;
                const s = await res.json();
                const fmt = (n) => new Intl.NumberFormat().format(Number(n || 0));
                const eventsEl = document.getElementById('statEvents');
                const attendeesEl = document.getElementById('statAttendees');
                const visitsEl = document.getElementById('statVisits');
                if (eventsEl) eventsEl.textContent = fmt(s.events);
                if (attendeesEl) attendeesEl.textContent = fmt(s.attendees);
                if (visitsEl) visitsEl.textContent = fmt(s.visits);
            } catch (e) {
                // no-op
            }
        })();
    </script>
</body>
</html>
