<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Planner</title>
    
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

    <!-- Register -->
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 520px;">
            <div class="border rounded-3 shadow-sm bg-white p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success mb-3 shadow" style="width:56px;height:56px">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="h3 fw-bold mb-1">Create your account</h1>
                    <p class="text-secondary small mb-0">Join and start planning or attending events.</p>
                </div>
                <form id="registerForm" class="vstack gap-3">
                    <div>
                        <label class="form-label small">Username</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 translate-middle-y start-0 ps-3 text-secondary"><i class="fas fa-user"></i></span>
                            <input class="form-control ps-5" type="text" name="username" placeholder="Jane Doe" autocomplete="username" required>
                        </div>
                    </div>
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
                            <input id="passwordInput" class="form-control ps-5 pe-5" type="password" name="password" minlength="6" placeholder="At least 6 characters" autocomplete="new-password" required>
                            <button type="button" id="togglePwd" class="btn btn-link position-absolute top-50 translate-middle-y end-0 pe-3 text-secondary"><i class="fas fa-eye"></i></button>
                        </div>
                        <div id="pwdHint" class="small text-secondary mt-1">Use 6+ characters with a mix of letters and numbers.</div>
                    </div>
                    <div>
                        <label class="form-label small">Role</label>
                        <div class="row g-2" role="group" aria-label="Select role">
                            <div class="col-6"><button type="button" data-role="attendee" class="role-btn btn btn-outline-secondary w-100">Attendee</button></div>
                            <div class="col-6"><button type="button" data-role="organizer" class="role-btn btn btn-outline-secondary w-100">Organizer</button></div>
                        </div>
                        <input type="hidden" name="role" id="roleInput" value="attendee">
                    </div>
                    <div class="d-flex align-items-start gap-2 small text-secondary">
                        <input id="terms" type="checkbox" class="form-check-input m-0" required>
                        <label for="terms" class="form-check-label">I agree to the <a class="link-success" href="#">Terms</a> and <a class="link-success" href="#">Privacy Policy</a>.</label>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-secondary">
                        <input id="remember" name="remember" type="checkbox" class="form-check-input m-0">
                        <label for="remember" class="form-check-label">Remember me on this device</label>
                    </div>
                    <div>
                        <button id="submitBtn" class="btn btn-dark w-100" type="submit" disabled>Create Account</button>
                    </div>
                    <p id="registerError" class="text-center text-danger d-none small mb-0">Registration failed. Try a different email.</p>
                    <p id="registerErrorMsg" class="text-center text-danger d-none text-break small mb-0"></p>
                </form>
                <div class="text-center mt-4 small text-secondary">
                    Already have an account?
                    <a class="link-success fw-semibold" href="/login.php">Sign in</a>
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

        const form = document.getElementById('registerForm');
        const errorEl = document.getElementById('registerError');
        const submitBtn = document.getElementById('submitBtn');
        const terms = document.getElementById('terms');
        const roleInput = document.getElementById('roleInput');
        const roleBtns = document.querySelectorAll('.role-btn');
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput = document.getElementById('passwordInput');
        const remember = document.getElementById('remember');
        const registerErrorMsg = document.getElementById('registerErrorMsg');

        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                roleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                roleInput.value = btn.getAttribute('data-role');
            });
        });

        togglePwd.addEventListener('click', () => {
            const isPwd = pwdInput.type === 'password';
            pwdInput.type = isPwd ? 'text' : 'password';
            togglePwd.innerHTML = isPwd ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        function validateForm() {
            const fd = new FormData(form);
            const ok = fd.get('username') && fd.get('email') && (fd.get('password') || '').length >= 6 && terms.checked;
            submitBtn.disabled = !ok;
        }

        form.addEventListener('input', validateForm);
        terms.addEventListener('change', validateForm);
        validateForm();

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorEl.classList.add('hidden');
            registerErrorMsg.classList.add('hidden');
            registerErrorMsg.textContent = '';
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
                if (!res.ok) throw new Error(data?.error || 'Register failed');
                if (!data.user) throw new Error('Register failed');
                window.location.href = '/events.php';
            } catch (err) {
                errorEl.classList.remove('d-none');
                registerErrorMsg.textContent = String(err?.message || 'Register failed');
                registerErrorMsg.classList.remove('d-none');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


