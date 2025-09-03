<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white navbar">
        <div class="max-w-8xl mx-auto px-4 flex items-center place-content-between">
            <div class="flex items-center justify-between h-16">
                <a class="flex items-center gap-2 font-semibold" href="index.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Event Planner</span>
                </a>
            </div>
            <div class="endpoints flex items-center gap-4">
                <a href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white current">Home</a>
                <a href="/events.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-whiteages">Events</a>
            </div>
            <div>
                <a href="login.php">Log in</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="max-w-8xl mx-auto px-4">
            <div class="grid grid-cols-1 items-center justify-center text-center min-h-[75vh]">
                <div class="py-12">
                    <h1 class="text-black font-extrabold text-4xl lg:text-5xl mb-4">
                        Effortless Event Planning Starts Here
                    </h1>
                    <p class="text-black/90 text-lg mb-4">
                        From small gatherings to large-scale conferences, our platform provides everything you need to create, manage, and host successful events.
                    </p>
                </div>
                
                <div class="slider-container">
                        <div class="slider-wrapper">
                            <div class="slide-item">
                                <img src="assets/images/slide1.jpg" alt="Slide 1">
                                <div class="slide-content">
                                    <h3>Event 1</h3>
                                    <p>Description for Event 1.</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img src="assets/images/slide2.jpeg" alt="Slide 2">
                                <div class="slide-content">
                                    <h3>Event 2</h3>
                                    <p>Description for Event 2.</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img src="assets/images/slide3.png" alt="Slide 3">
                                <div class="slide-content">
                                    <h3>Event 3</h3>
                                    <p>Description for Event 3.</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img src="assets/images/slide4.jpg" alt="Slide 4">
                                <div class="slide-content">
                                    <h3>Event 4</h3>
                                    <p>Description for Event 4.</p>
                                </div>
                            </div>
                            <div class="slide-item">
                                <img src="assets/images/slide5.jpg" alt="Slide 5">
                                <div class="slide-content">
                                    <h3>Event 5</h3>
                                    <p>Description for Event 5.</p>
                                </div>
                            </div>
                        </div>
                        <button class="prev-button">❮</button>
                        <button class="next-button">❯</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="max-w-8xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-6 items-center">
                <div>
                    <h5 class="text-lg font-semibold">Event Planner</h5>
                    <p class="m-0 text-white/80">Making event planning simple and efficient.</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="m-0 text-white/80">&copy; <?php echo date('Y'); ?> Event Planner. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const slideItems = document.querySelectorAll('.slide-item');
        const prevButton = document.querySelector('.prev-button');
        const nextButton = document.querySelector('.next-button');

        let currentIndex = 0;
        const slideWidth = slideItems[0].clientWidth;

        function updateSliderPosition() {
            sliderWrapper.style.transform = `translateX(${-currentIndex * slideWidth}px)`;
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

        let autoPlayInterval = setInterval(() => {
            nextButton.click();
        }, 3000);

        sliderWrapper.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        sliderWrapper.addEventListener('mouseleave', () => {
            autoPlayInterval = setInterval(() => {
                nextButton.click();
            }, 3000);
        });
    </script>
</body>
</html>
