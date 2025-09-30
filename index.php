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
                        <a href="/events.php" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-calendar-plus mr-3 text-lg"></i>
                            Browse Events
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="/login.php" class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-gray-400 hover:bg-gray-50 transition-all duration-300">
                            <i class="fas fa-user mr-3"></i>
                            Get Started
                        </a>
                    </div>
                    
                    <!-- Stats (dynamic) -->
                    <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto mt-12 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <div id="statEvents" class="text-2xl font-bold text-gray-900">0</div>
                            <div class="text-sm text-gray-600">Events</div>
                        </div>
                        <div class="text-center">
                            <div id="statAttendees" class="text-2xl font-bold text-gray-900">0</div>
                            <div class="text-sm text-gray-600">Attendees</div>
                        </div>
                        <div class="text-center">
                            <div id="statVisits" class="text-2xl font-bold text-gray-900">0</div>
                            <div class="text-sm text-gray-600">Visits</div>
                        </div>
                    </div>
                </div>
                
                <!-- Modern Slider -->
                <div class="slider-container px-4 pb-8">
                    <div class="relative">
                        <div class="slider-wrapper rounded-2xl overflow-hidden shadow-2xl bg-white">
                            <div class="slide-item">
                                <img class="w-full h-auto object-cover" src="assets/images/slide1.jpg" alt="Slide 1">
                                <div class="slide-content bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-white font-semibold text-lg sm:text-xl">Corporate Events</h3>
                                    <p class="text-white/90 text-sm sm:text-base">Professional conferences and workshops</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img class="w-full h-auto object-cover" src="assets/images/slide2.jpeg" alt="Slide 2">
                                <div class="slide-content bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-white font-semibold text-lg sm:text-xl">Social Gatherings</h3>
                                    <p class="text-white/90 text-sm sm:text-base">Celebrations and meetups</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img class="w-full h-auto object-cover" src="assets/images/slide3.png" alt="Slide 3">
                                <div class="slide-content bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-white font-semibold text-lg sm:text-xl">Tech Meetups</h3>
                                    <p class="text-white/90 text-sm sm:text-base">Innovation and networking</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img class="w-full h-auto object-cover" src="assets/images/slide4.jpg" alt="Slide 4">
                                <div class="slide-content bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-white font-semibold text-lg sm:text-xl">Workshops</h3>
                                    <p class="text-white/90 text-sm sm:text-base">Learning and skill development</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img class="w-full h-auto object-cover" src="assets/images/slide5.jpg" alt="Slide 5">
                                <div class="slide-content bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-white font-semibold text-lg sm:text-xl">Exhibitions</h3>
                                    <p class="text-white/90 text-sm sm:text-base">Showcasing creativity</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modern Navigation Buttons -->
                        <button class="prev-button absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white text-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center backdrop-blur-sm" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="next-button absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white text-gray-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center backdrop-blur-sm" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        <!-- Dots indicator -->
                        <div class="flex justify-center mt-6 space-x-2">
                            <div class="dot active w-3 h-3 bg-blue-600 rounded-full cursor-pointer transition-all duration-300"></div>
                            <div class="dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all duration-300"></div>
                            <div class="dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all duration-300"></div>
                            <div class="dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all duration-300"></div>
                            <div class="dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all duration-300"></div>
                        </div>
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
        const slideItems = document.querySelectorAll('.slide-item');
        const prevButton = document.querySelector('.prev-button');
        const nextButton = document.querySelector('.next-button');
        const dots = document.querySelectorAll('.dot');

        let currentIndex = 0;

        function getSlideWidth() {
            const first = slideItems[0];
            return first ? first.clientWidth : 0;
        }

        function updateSliderPosition() {
            const width = getSlideWidth();
            sliderWrapper.style.transform = `translateX(${-currentIndex * width}px)`;
            updateDots();
        }

        function updateDots() {
            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('active', 'bg-blue-600');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('active', 'bg-blue-600');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSliderPosition();
        }

        nextButton.addEventListener('click', () => {
            currentIndex++;
            if (currentIndex > slideItems.length - 1) {
                currentIndex = 0;
            }
            updateSliderPosition();
        });

        prevButton.addEventListener('click', () => {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = slideItems.length - 1;
            }
            updateSliderPosition();
        });

        // Add click handlers for dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => goToSlide(index));
        });

        let autoPlayInterval = setInterval(() => {
            nextButton.click();
        }, 4000);

        sliderWrapper.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        sliderWrapper.addEventListener('mouseleave', () => {
            autoPlayInterval = setInterval(() => {
                nextButton.click();
            }, 4000);
        });

        // Recalculate on image load and window resize to ensure correct widths
        const slideImages = document.querySelectorAll('.slide-item img');
        slideImages.forEach(img => {
            if (img.complete) return; // if already loaded, skip
            img.addEventListener('load', updateSliderPosition);
        });

        window.addEventListener('resize', updateSliderPosition);

        // Initialize dots and position
        updateDots();
        updateSliderPosition();

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
