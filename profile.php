<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Event Planner</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
        <!-- Profile Header -->
        <section class="hero-gradient text-white py-4">
            <div class="container hero-content">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div class="position-relative">
                            <img id="avatarImg" src="" alt="Avatar" class="rounded-circle d-none border border-3 border-white shadow" style="width:80px; height:80px; object-fit:cover" />
                            <div id="avatar" class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-4 border border-3 border-white shadow" style="width:80px; height:80px; background: rgba(255,255,255,0.2);"></div>
                            <label class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:28px; height:28px; cursor:pointer; background: var(--gradient-primary);">
                                <i class="fa-solid fa-camera text-white" style="font-size:12px"></i>
                                <input id="avatarInput" type="file" accept="image/*" class="d-none" />
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <h1 class="h3 fw-bold mb-1">My Profile</h1>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="small opacity-90" id="meta"></div>
                            <span id="roleBadge" class="badge bg-white bg-opacity-25 text-white"></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alert -->
        <div class="container mt-4">
            <div id="alert" class="alert d-none" role="alert"></div>
        </div>

        <!-- Profile Content -->
        <div class="container py-4">
            <div class="row g-4">
                <!-- Main Column - Account Details -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 fw-bold">
                                <i class="fas fa-user-circle text-primary me-2"></i>Account Details
                            </h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold small" for="username">
                                        <i class="fas fa-user me-1 text-muted"></i>Username
                                    </label>
                                    <input id="username" type="text" class="form-control" placeholder="Enter your username" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold small" for="email">
                                        <i class="fas fa-envelope me-1 text-muted"></i>Email Address
                                    </label>
                                    <input id="email" type="email" class="form-control" placeholder="Enter your email" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small" for="password">
                                        <i class="fas fa-lock me-1 text-muted"></i>New Password
                                    </label>
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control border-end-0" placeholder="Leave blank to keep current password" />
                                        <button type="button" id="togglePw" class="btn btn-outline-secondary bg-white">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Only fill this if you want to change your password
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex align-items-center gap-3">
                                <button id="saveBtn" class="btn btn-gradient d-inline-flex align-items-center">
                                    <i class="fas fa-save me-2"></i>
                                    <span id="saveText">Save Changes</span>
                                    <span id="saveSpinner" class="d-none ms-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </button>
                                <span id="savedLabel" class="d-none small text-success">
                                    <i class="fas fa-check-circle me-1"></i>Saved successfully!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-12 col-lg-4">
                    <!-- Security Tips Card -->
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-header bg-gradient text-white border-0 py-3" style="background: var(--gradient-primary);">
                            <h3 class="h6 mb-0 fw-bold">
                                <i class="fas fa-shield-alt me-2"></i>Security Tips
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <span class="small">Use a strong, unique password with letters, numbers, and symbols</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <span class="small">Never share your credentials with anyone</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <span class="small">Update your password regularly (every 3-6 months)</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <span class="small">Be cautious of phishing attempts and suspicious emails</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Account Info Card -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h3 class="h6 fw-bold mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>Account Information
                            </h3>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="small text-muted">Status</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="small text-muted">Role</span>
                                <span id="roleInfo" class="small fw-semibold"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="small text-muted">Member Since</span>
                                <span id="memberSince" class="small fw-semibold"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        async function fetchMe() {
            const res = await fetch('/api/auth.php?action=me', { credentials: 'same-origin' });
            return res.ok ? res.json() : { user: null };
        }

        async function loadProfile() {
            const { user } = await fetchMe();
            if (!user) {
                window.location.href = '/login.php';
                return;
            }

            // Set form values
            document.getElementById('username').value = user.username || '';
            document.getElementById('email').value = user.email || '';

            // Avatar
            const initials = String(user.username || '?').trim().split(/\s+/).map(s => s[0]?.toUpperCase()).slice(0, 2).join('') || '?';
            const avatar = document.getElementById('avatar');
            const avatarImg = document.getElementById('avatarImg');
            
            if (user.avatar_path) {
                if (avatarImg) {
                    avatarImg.src = user.avatar_path;
                    avatarImg.classList.remove('d-none');
                }
                if (avatar) avatar.classList.add('d-none');
            } else {
                if (avatarImg) {
                    avatarImg.classList.add('d-none');
                    avatarImg.src = '';
                }
                if (avatar) {
                    avatar.classList.remove('d-none');
                    avatar.textContent = initials;
                }
            }

            // Role and metadata
            const role = user.role ? String(user.role).replace(/^(.)/, (m, p1) => p1.toUpperCase()) : 'User';
            const since = user.created_at ? new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '';
            
            const meta = document.getElementById('meta');
            if (meta) meta.innerHTML = since ? `<i class="fas fa-calendar-alt me-1"></i>Member since ${since}` : '';
            
            const roleBadge = document.getElementById('roleBadge');
            if (roleBadge) roleBadge.textContent = role;
            
            const roleInfo = document.getElementById('roleInfo');
            if (roleInfo) roleInfo.textContent = role;
            
            const memberSince = document.getElementById('memberSince');
            if (memberSince) memberSince.textContent = since || 'N/A';
        }

        async function uploadAvatar(file) {
            const form = new FormData();
            form.append('avatar', file);
            
            try {
                const res = await fetch('/api/auth.php?action=upload_avatar', {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: form
                });
                const data = await res.json();
                
                if (!res.ok) {
                    showAlert(data.error || 'Failed to upload avatar', false);
                    return;
                }
                
                showAlert('Avatar updated successfully!');
                
                // Refresh avatar view
                const u = data.user;
                const avatar = document.getElementById('avatar');
                const avatarImg = document.getElementById('avatarImg');
                
                if (u && u.avatar_path && avatarImg) {
                    avatarImg.src = u.avatar_path + '?t=' + Date.now();
                    avatarImg.classList.remove('d-none');
                    if (avatar) avatar.classList.add('d-none');
                }
            } catch (err) {
                showAlert('Failed to upload avatar', false);
            }
        }

        function showAlert(msg, ok = true) {
            const el = document.getElementById('alert');
            el.innerHTML = `<i class="fas fa-${ok ? 'check-circle' : 'exclamation-circle'} me-2"></i>${msg}`;
            el.classList.remove('d-none', 'alert-success', 'alert-danger');
            el.classList.add(ok ? 'alert-success' : 'alert-danger');
            setTimeout(() => {
                el.classList.add('d-none');
            }, 4000);
        }

        async function saveProfile() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            const payload = { username, email };
            if (password && password.length > 0) payload.password = password;
            
            const btn = document.getElementById('saveBtn');
            const spinner = document.getElementById('saveSpinner');
            const saved = document.getElementById('savedLabel');
            
            btn.disabled = true;
            spinner.classList.remove('d-none');
            saved.classList.add('d-none');
            
            try {
                const res = await fetch('/api/auth.php?action=update', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if (!res.ok) {
                    showAlert(data.error || 'Failed to update profile', false);
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    return;
                }
                
                document.getElementById('password').value = '';
                showAlert('Profile updated successfully!');
                btn.disabled = false;
                spinner.classList.add('d-none');
                saved.classList.remove('d-none');
                
                // Hide success label after 3 seconds
                setTimeout(() => {
                    saved.classList.add('d-none');
                }, 3000);
            } catch (err) {
                showAlert('Failed to update profile', false);
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        }

        // Event listeners
        document.getElementById('saveBtn').addEventListener('click', saveProfile);
        
        document.getElementById('togglePw').addEventListener('click', () => {
            const pw = document.getElementById('password');
            const icon = document.querySelector('#togglePw i');
            if (!pw) return;
            
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pw.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('avatarInput').addEventListener('change', (e) => {
            const f = e.target.files && e.target.files[0];
            if (!f) return;
            uploadAvatar(f);
            e.target.value = '';
        });

        // Initialize
        loadProfile();
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
