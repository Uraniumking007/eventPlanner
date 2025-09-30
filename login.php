<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Planner</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col">
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

    <!-- Login -->
    <div class="max-w-full sm:max-w-md md:max-w-lg mx-auto p-4 sm:p-6 md:p-8 bg-white/80 backdrop-blur rounded-2xl shadow-xl mt-8 sm:mt-12 md:mt-16 mx-4">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-green-100 to-emerald-200 text-emerald-700 mb-3 shadow">
                <i class="fas fa-right-to-bracket"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Sign in to your account</h1>
            <p class="text-gray-600 text-xs sm:text-sm mt-1">Welcome back. Please enter your details.</p>
        </div>
        <form id="loginForm" class="space-y-4">
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-envelope"></i></span>
                    <input class="w-full border rounded-lg pl-10 pr-3 py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-400" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
                </div>
            </div>
            <div class="text-left">
                <label class="block text-sm font-medium mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                    <input id="passwordInput" class="w-full border rounded-lg pl-10 pr-10 py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-400" type="password" name="password" placeholder="Your password" autocomplete="current-password" required>
                    <button type="button" id="togglePwd" class="absolute inset-y-0 right-0 pr-3 text-gray-400 hover:text-gray-600"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <input id="remember" name="remember" type="checkbox" class="mt-1">
                <label for="remember">Remember me on this device</label>
            </div>
            <div class="submit mt-2">
                <input id="submitBtn" class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-300 text-white px-4 py-3 rounded-lg cursor-pointer text-sm sm:text-base w-full transition" type="submit" value="Sign in" disabled>
            </div>
            <p id="loginError" class="text-center text-red-600 hidden text-sm">Login failed. Check your email and password.</p>
            <p id="loginErrorMsg" class="text-center text-red-600 hidden text-xs"></p>
        </form>
        <div class="text-center mt-5 text-sm text-gray-700">
            Don't have an account?
            <a class="text-emerald-700 font-medium hover:underline" href="/register.php">Create one</a>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-6 mt-auto">
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

        const form = document.getElementById('loginForm');
        const errorEl = document.getElementById('loginError');
        const submitBtn = document.getElementById('submitBtn');
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput = document.getElementById('passwordInput');
        const remember = document.getElementById('remember');
        const loginErrorMsg = document.getElementById('loginErrorMsg');

        togglePwd.addEventListener('click', () => {
            const isPwd = pwdInput.type === 'password';
            pwdInput.type = isPwd ? 'text' : 'password';
            togglePwd.innerHTML = isPwd ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        function validateForm() {
            const fd = new FormData(form);
            const ok = fd.get('email') && (fd.get('password') || '').length > 0;
            submitBtn.disabled = !ok;
        }

        form.addEventListener('input', validateForm);
        validateForm();

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('hidden');
            loginErrorMsg.classList.add('hidden');
            loginErrorMsg.textContent = '';
            const formData = new FormData(form);
            const payload = {
                email: formData.get('email'),
                password: formData.get('password'),
                remember: remember.checked
            };
            try {
                const res = await fetch('/api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data?.error || 'Login failed');
                if (!data.user) throw new Error('Login failed');
                const dest = data.user.role === 'organizer' ? '/dashboard.php' : '/events.php';
                window.location.href = dest;
            } catch (err) {
                errorEl.classList.remove('hidden');
                loginErrorMsg.textContent = String(err?.message || 'Login failed');
                loginErrorMsg.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>


