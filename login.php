<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Event Planner</title>
    <meta name="description" content="Login to your account to manage your events.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Login Section -->
    <section class="hero-gradient text-white flex-grow-1 py-4 py-md-5">
        <div class="container hero-content">
            <div class="row align-items-center justify-content-center g-4">
                <!-- Left Column - Info -->
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="mb-3">
                        <h1 class="display-5 fw-bold mb-3">Welcome Back!</h1>
                        <p class="mb-4 opacity-90">
                            Sign in to access your dashboard, manage events, and connect with attendees.
                        </p>
                    </div>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-2 flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold mb-1">Manage Your Events</h3>
                                <p class="small opacity-90 mb-0">Create, edit, and track your events in real-time</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-2 flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold mb-1">Connect with Attendees</h3>
                                <p class="small opacity-90 mb-0">Engage with your community and build relationships</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-2 flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold mb-1">Track Performance</h3>
                                <p class="small opacity-90 mb-0">Get insights and analytics for your events</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Login Form -->
                <div class="col-lg-5 col-md-7">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: var(--gradient-primary);">
                                    <i class="fas fa-sign-in-alt fa-lg text-white"></i>
                                </div>
                                <h2 class="h4 fw-bold mb-2">Sign In</h2>
                                <p class="text-muted small mb-0">Welcome back! Please enter your details.</p>
                            </div>

                            <!-- Login Form -->
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </label>
                                    <input 
                                        class="form-control" 
                                        type="email" 
                                        name="email" 
                                        placeholder="you@example.com" 
                                        autocomplete="email" 
                                        required
                                    >
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">
                                        <i class="fas fa-lock me-1"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input 
                                            id="passwordInput" 
                                            class="form-control border-end-0" 
                                            type="password" 
                                            name="password" 
                                            placeholder="Enter your password" 
                                            autocomplete="current-password" 
                                            required
                                        >
                                        <button 
                                            type="button" 
                                            id="togglePwd" 
                                            class="btn btn-outline-secondary bg-white"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input 
                                        id="remember" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="form-check-input"
                                    >
                                    <label for="remember" class="form-check-label small text-muted">
                                        Remember me on this device
                                    </label>
                                </div>

                                <button 
                                    id="submitBtn" 
                                    class="btn btn-gradient w-100 mb-3" 
                                    type="submit" 
                                    disabled
                                >
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>

                                <!-- Error Messages -->
                                <div id="loginError" class="alert alert-danger small d-none" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <span id="loginErrorMsg">Login failed. Check your email and password.</span>
                                </div>
                            </form>

                            <!-- Divider -->
                            <div class="text-center my-3">
                                <hr class="mb-0">
                            </div>

                            <!-- Sign Up Link -->
                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    Don't have an account? 
                                    <a href="/register.php" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                        Create one <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-3">
                        <a href="/" class="text-white text-decoration-none opacity-75 hover-opacity-100 small">
                            <i class="fas fa-arrow-left me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        const form = document.getElementById('loginForm');
        const errorEl = document.getElementById('loginError');
        const errorMsgEl = document.getElementById('loginErrorMsg');
        const submitBtn = document.getElementById('submitBtn');
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput = document.getElementById('passwordInput');
        const remember = document.getElementById('remember');

        // Toggle password visibility
        togglePwd.addEventListener('click', () => {
            const isPwd = pwdInput.type === 'password';
            pwdInput.type = isPwd ? 'text' : 'password';
            togglePwd.innerHTML = isPwd ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        // Form validation
        function validateForm() {
            const fd = new FormData(form);
            const ok = fd.get('email') && (fd.get('password') || '').length > 0;
            submitBtn.disabled = !ok;
        }

        form.addEventListener('input', validateForm);
        validateForm();

        // Form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('d-none');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';

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
                
                // Success - redirect
                const dest = data.user.role === 'organizer' ? '/dashboard.php' : '/events.php';
                window.location.href = dest;
            } catch (err) {
                errorMsgEl.textContent = String(err?.message || 'Login failed. Please check your credentials.');
                errorEl.classList.remove('d-none');
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
                validateForm();
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
