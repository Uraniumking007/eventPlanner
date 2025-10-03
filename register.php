<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Event Planner</title>
    <meta name="description" content="Create your account and start planning or attending amazing events.">
    
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

    <!-- Register Section -->
    <section class="hero-gradient text-white flex-grow-1 py-4 py-md-5">
        <div class="container hero-content">
            <div class="row align-items-center justify-content-center g-4">
                <!-- Left Column - Info -->
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="mb-3">
                        <h1 class="display-6 fw-bold mb-2">Join Event Planner</h1>
                        <p class="mb-3 opacity-90">
                            Create unforgettable events or discover amazing experiences. Your journey starts here.
                        </p>
                    </div>
                    
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check small"></i>
                            </div>
                            <p class="mb-0 small">Create unlimited events</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check small"></i>
                            </div>
                            <p class="mb-0 small">Connect with attendees worldwide</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check small"></i>
                            </div>
                            <p class="mb-0 small">Access powerful analytics</p>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check small"></i>
                            </div>
                            <p class="mb-0 small">100% free to get started</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-25">
                        <p class="mb-0 small opacity-90">
                            <i class="fas fa-star text-warning me-2"></i>
                            Join <strong>1000+ organizers</strong> already using Event Planner.
                        </p>
                    </div>
                </div>

                <!-- Right Column - Register Form -->
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: var(--gradient-primary);">
                                    <i class="fas fa-user-plus fa-lg text-white"></i>
                                </div>
                                <h2 class="h4 fw-bold mb-2">Create Account</h2>
                                <p class="text-muted small mb-0">Get started in less than a minute</p>
                            </div>

                            <!-- Register Form -->
                            <form id="registerForm">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">
                                        <i class="fas fa-user me-1"></i>Full Name
                                    </label>
                                    <input 
                                        class="form-control" 
                                        type="text" 
                                        name="username" 
                                        placeholder="Jane Doe" 
                                        autocomplete="username" 
                                        required
                                    >
                                </div>

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
                                            minlength="6" 
                                            placeholder="At least 6 characters" 
                                            autocomplete="new-password" 
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
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-info-circle me-1"></i>Use 6+ characters
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small mb-2">
                                        <i class="fas fa-user-tag me-1"></i>I Want To
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button 
                                                type="button" 
                                                data-role="attendee" 
                                                class="role-btn btn btn-outline-secondary w-100 py-2 active"
                                            >
                                                <i class="fas fa-ticket-alt d-block mb-1"></i>
                                                <div class="fw-semibold small">Attend</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">Find events</small>
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button 
                                                type="button" 
                                                data-role="organizer" 
                                                class="role-btn btn btn-outline-secondary w-100 py-2"
                                            >
                                                <i class="fas fa-calendar-plus d-block mb-1"></i>
                                                <div class="fw-semibold small">Organize</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">Create events</small>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="role" id="roleInput" value="attendee">
                                </div>

                                <div class="form-check mb-2">
                                    <input 
                                        id="terms" 
                                        type="checkbox" 
                                        class="form-check-input" 
                                        required
                                    >
                                    <label for="terms" class="form-check-label small text-muted">
                                        I agree to the <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Terms</a> and <a href="#" class="text-decoration-none" style="color: var(--primary-color);">Privacy Policy</a>
                                    </label>
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
                                    <i class="fas fa-rocket me-2"></i>Create Account
                                </button>

                                <!-- Error Messages -->
                                <div id="registerError" class="alert alert-danger small d-none" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <span id="registerErrorMsg">Registration failed. Please try again.</span>
                                </div>
                            </form>

                            <!-- Divider -->
                            <div class="text-center my-3">
                                <hr class="mb-0">
                            </div>

                            <!-- Sign In Link -->
                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    Already have an account? 
                                    <a href="/login.php" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                        Sign in <i class="fas fa-arrow-right ms-1"></i>
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
        const form = document.getElementById('registerForm');
        const errorEl = document.getElementById('registerError');
        const errorMsgEl = document.getElementById('registerErrorMsg');
        const submitBtn = document.getElementById('submitBtn');
        const terms = document.getElementById('terms');
        const roleInput = document.getElementById('roleInput');
        const roleBtns = document.querySelectorAll('.role-btn');
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput = document.getElementById('passwordInput');
        const remember = document.getElementById('remember');

        // Role selection
        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                roleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                roleInput.value = btn.getAttribute('data-role');
            });
        });

        // Toggle password visibility
        togglePwd.addEventListener('click', () => {
            const isPwd = pwdInput.type === 'password';
            pwdInput.type = isPwd ? 'text' : 'password';
            togglePwd.innerHTML = isPwd ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        // Form validation
        function validateForm() {
            const fd = new FormData(form);
            const ok = fd.get('username') && 
                       fd.get('email') && 
                       (fd.get('password') || '').length >= 6 && 
                       terms.checked;
            submitBtn.disabled = !ok;
        }

        form.addEventListener('input', validateForm);
        terms.addEventListener('change', validateForm);
        validateForm();

        // Form submission
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('d-none');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating account...';

            const formData = new FormData(form);
            const payload = {
                username: formData.get('username'),
                email: formData.get('email'),
                password: formData.get('password'),
                role: formData.get('role'),
                remember: remember.checked
            };

            try {
                const res = await fetch('/api/auth.php?action=register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });
                const data = await res.json();
                
                if (!res.ok) throw new Error(data?.error || 'Registration failed');
                if (!data.user) throw new Error('Registration failed');
                
                // Success - redirect
                window.location.href = '/events.php';
            } catch (err) {
                errorMsgEl.textContent = String(err?.message || 'Registration failed. Please try again.');
                errorEl.classList.remove('d-none');
                submitBtn.innerHTML = '<i class="fas fa-rocket me-2"></i>Create Account';
                validateForm();
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
