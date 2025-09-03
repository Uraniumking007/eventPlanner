<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Planner</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white navbar shadow">
        <div class="max-w-8xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center h-16">
                <a class="flex items-center gap-2 font-semibold" href="/index.php">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="hidden sm:inline">Event Planner</span>
                    <span class="sm:hidden">EP</span>
                </a>
            </div>
            <div class="endpoints flex items-center gap-1 md:gap-2">
                <a id="navHome" href="/" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Home</a>
                <a id="navEvents" href="/events.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white">Events</a>
            </div>
            <div id="userArea" class="text-sm">
                <a href="/login.php" class="px-3 py-1 rounded bg-white/10 hover:bg-white/20">Log in</a>
            </div>
        </div>
    </nav>

    <!-- Register -->
    <div class="max-w-sm sm:max-w-md mx-auto p-4 sm:p-6 bg-white rounded-xl shadow-lg mt-8 sm:mt-12 mx-4">
        <div class="text-center mb-4">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-700 mb-2">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-bold">Create your account</h2>
            <p class="text-gray-600 text-sm mt-1">Join and start planning or attending events.</p>
        </div>
        <form id="registerForm" class="space-y-4">
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Username</label>
                <input class="w-full border rounded px-3 py-2 text-sm sm:text-base" type="text" name="username" required>
            </div>
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input class="w-full border rounded px-3 py-2 text-sm sm:text-base" type="email" name="email" required>
            </div>
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input class="w-full border rounded px-3 py-2 text-sm sm:text-base" type="password" name="password" minlength="6" required>
            </div>
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Role</label>
                <select class="w-full border rounded px-3 py-2 text-sm sm:text-base" name="role">
                    <option value="attendee">Attendee</option>
                    <option value="organizer">Organizer</option>
                </select>
            </div>
            <div class="submit text-center mt-2">
                <input class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-black cursor-pointer text-sm sm:text-base w-full" type="submit" value="Create Account">
            </div>
            <p id="registerError" class="text-center text-red-600 hidden text-sm">Registration failed. Try a different email.</p>
        </form>
        <div class="text-center mt-4 text-sm text-gray-700">
            Already have an account?
            <a class="text-blue-700 font-medium hover:underline" href="/login.php">Sign in</a>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-6 mt-10">
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

    <script>
        // Navbar active state
        (function(){
            const path = window.location.pathname;
            const home = document.getElementById('navHome');
            const events = document.getElementById('navEvents');
            if (path === '/' || path.endsWith('index.php')) home.classList.add('bg-white/10','text-white');
            if (path.endsWith('events.php')) events.classList.add('bg-white/10','text-white');
        })();

        const form = document.getElementById('registerForm');
        const errorEl = document.getElementById('registerError');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('hidden');
            const formData = new FormData(form);
            const payload = {
                username: formData.get('username'),
                email: formData.get('email'),
                password: formData.get('password'),
                role: formData.get('role')
            };
            try {
                const res = await fetch('/api/auth.php?action=register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error('Register failed');
                const data = await res.json();
                if (!data.user) throw new Error('Register failed');
                window.location.href = '/events.php';
            } catch (err) {
                errorEl.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>


