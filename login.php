<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Planner</title>
    <meta name="description" content="Login to your account to manage your events.">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Login -->
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 520px;">
            <div class="border rounded-3 shadow-sm bg-white p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success mb-3 shadow" style="width:56px;height:56px">
                        <i class="fas fa-right-to-bracket"></i>
                    </div>
                    <h1 class="h3 fw-bold mb-1">Sign in to your account</h1>
                    <p class="text-secondary small mb-0">Welcome back. Please enter your details.</p>
                </div>
                <form id="loginForm" class="vstack gap-3">
                    <div>
                        <label class="form-label small">Email</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y start-0 ps-3 text-secondary"><i class="fas fa-envelope"></i></span>
                            <input class="form-control ps-5" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small">Password</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y start-0 ps-3 text-secondary"><i class="fas fa-lock"></i></span>
                            <input id="passwordInput" class="form-control ps-5 pe-5" type="password" name="password" placeholder="Your password" autocomplete="current-password" required>
                            <button type="button" id="togglePwd" class="btn btn-link position-absolute top-50 translate-middle-y end-0 pe-3 text-secondary"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <input id="remember" name="remember" type="checkbox" class="form-check-input m-0">
                        <label for="remember" class="form-check-label">Remember me on this device</label>
                    </div>
                    <div>
                        <button id="submitBtn" class="btn btn-dark w-100" type="submit" disabled>Sign in</button>
                    </div>
                    <p id="loginError" class="text-center text-danger d-none small mb-0">Login failed. Check your email and password.</p>
                    <p id="loginErrorMsg" class="text-center text-danger d-none text-break small mb-0"></p>
                </form>
                <div class="text-center mt-4 small text-secondary">
                    Don't have an account?
                    <a class="link-success fw-semibold" href="/register.php">Create one</a>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Navbar active state
        (function(){
            const path = window.location.pathname;
            const home = document.getElementById('navHome');
            const events = document.getElementById('navEvents');
            if (path === '/' || path.endsWith('index.php')) home?.classList.add('active');
            if (path.endsWith('events.php')) events?.classList.add('active');
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
            errorEl.classList.add('d-none');
            loginErrorMsg.classList.add('d-none');
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
                errorEl.classList.remove('d-none');
                loginErrorMsg.textContent = String(err?.message || 'Login failed');
                loginErrorMsg.classList.remove('d-none');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


