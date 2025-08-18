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
    <nav class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a class="flex items-center gap-2 font-semibold" href="index.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Event Planner</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center min-h-[75vh]">
                <div class="py-12">
                    <h1 class="text-white font-extrabold text-4xl lg:text-5xl mb-4">
                        Plan Your Perfect Event
                    </h1>
                    <p class="text-white/90 text-lg mb-4">
                        Create, manage, and organize events with ease. Our platform helps you bring your vision to life.
                    </p>
                </div>
                <div class="text-center py-12">
                    <i class="fas fa-calendar-check text-white/80" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4">
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

    <!-- No JS dependencies needed for TailwindCDN on this page -->
</body>
</html>
