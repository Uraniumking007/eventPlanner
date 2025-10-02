<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-grow-1">
    <div class="container py-4 py-lg-5">
        <div class="border rounded-3 shadow-sm bg-white p-4 p-lg-5 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-auto d-flex align-items-center gap-3">
                    <div id="avatar" class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-semibold" style="width:56px;height:56px"></div>
                    <div>
                        <h1 class="h4 fw-bold mb-1">My Profile</h1>
                        <div class="text-secondary small" id="meta"></div>
                    </div>
                </div>
                <div class="col-12 col-md-auto ms-auto">
                    <span id="roleBadge" class="badge text-bg-light"></span>
                </div>
            </div>
        </div>

        <div id="alert" class="alert d-none" role="alert"></div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="border rounded-3 shadow-sm bg-white p-4">
                    <h2 class="h6 mb-3">Account details</h2>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted" for="username">Username</label>
                            <input id="username" type="text" class="form-control" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small text-muted" for="email">Email</label>
                            <input id="email" type="email" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted" for="password">New Password (optional)</label>
                            <div class="position-relative">
                                <input id="password" type="password" class="form-control pe-5" placeholder="Leave blank to keep current" />
                                <button type="button" id="togglePw" class="btn btn-link position-absolute top-0 end-0 h-100 px-3 text-secondary"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 d-flex align-items-center gap-3">
                        <button id="saveBtn" class="btn btn-dark d-inline-flex align-items-center">
                            <span id="saveText">Save changes</span>
                            <span id="saveSpinner" class="d-none ms-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </button>
                        <span id="savedLabel" class="d-none small text-success">Saved</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="border rounded-3 shadow-sm bg-white p-4">
                    <h2 class="h6 mb-3">Security tips</h2>
                    <ul class="small text-secondary ps-3">
                        <li>Use a strong, unique password.</li>
                        <li>Do not share your credentials.</li>
                        <li>Update your password regularly.</li>
                    </ul>
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
            document.getElementById('username').value = user.username || '';
            document.getElementById('email').value = user.email || '';
            const initials = String(user.username || '?').trim().split(/\s+/).map(s => s[0]?.toUpperCase()).slice(0,2).join('') || '?';
            const avatar = document.getElementById('avatar');
            if (avatar) avatar.textContent = initials;
            const role = user.role ? String(user.role).replace(/^(.)/, (m, p1) => p1.toUpperCase()) : 'User';
            const since = user.created_at ? new Date(user.created_at).toLocaleDateString() : '';
            const meta = document.getElementById('meta');
            if (meta) meta.textContent = since ? `Member since ${since}` : '';
            const roleBadge = document.getElementById('roleBadge');
            if (roleBadge) roleBadge.textContent = role;
        }
        function showAlert(msg, ok = true) {
            const el = document.getElementById('alert');
            el.textContent = msg;
            el.classList.remove('d-none');
            el.className = `alert ${ok ? 'alert-success' : 'alert-danger'}`;
            setTimeout(() => { el.classList.add('d-none'); }, 4000);
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
            btn.disabled = true; spinner.classList.remove('d-none'); saved.classList.add('d-none');
            const res = await fetch('/api/auth.php?action=update', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!res.ok) {
                showAlert(data.error || 'Failed to update profile', false);
                btn.disabled = false; spinner.classList.add('hidden');
                return;
            }
            document.getElementById('password').value = '';
            showAlert('Profile updated successfully');
            btn.disabled = false; spinner.classList.add('d-none'); saved.classList.remove('d-none');
        }

        document.getElementById('saveBtn').addEventListener('click', saveProfile);
        document.getElementById('togglePw').addEventListener('click', () => {
            const pw = document.getElementById('password');
            if (!pw) return;
            pw.type = pw.type === 'password' ? 'text' : 'password';
        });
        loadProfile();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


