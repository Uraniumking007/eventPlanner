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
                <a href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
                <a href="/events.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-whiteages">Events</a>
            </div>
        </div>
    </nav>

    <!-- Login -->
    <div class="Login-box">
        <form action="/index.php" methods="POST" class="login">
            <div class="input-item">
                <Label for>Username</Label>
                <input type="text" name="username">
            </div>
            <div class="input-item">
                <Label for>Email-Id</Label>
                <input type="text" name="email">
            </div>
            <div class="input-item">
                <Label for>Mobile No.</Label>
                <input type="text" name="mob-no">
            </div>
            <div class="input-item">
                <Label for>Password</Label>
                <input type="password" name="password">
            </div>
            <div class="input-item">
                <Label for>Confirm Password</Label>
                <input type="password" name="confirm-password">
            </div>
            <div class="submit">
                <input type="submit" value="Sign-Up">
            </div>
        </form>
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