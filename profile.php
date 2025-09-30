<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="min-h-screen flex flex-col">
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="flex-1">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 p-[1px] shadow-lg mb-6">
            <div class="rounded-2xl bg-white/90 backdrop-blur-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div id="avatar" class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 text-white flex items-center justify-center text-xl font-semibold shadow"></div>
                        <div>
                            <h1 class="text-2xl font-extrabold text-gray-900">My Profile</h1>
                            <div class="text-sm text-gray-600" id="meta"></div>
                        </div>
                    </div>
                    <div id="roleBadge" class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 border"></div>
                </div>
            </div>
        </div>

        <div id="alert" class="hidden mb-4 p-3 rounded text-sm"></div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border rounded-xl shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Account details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1" for="username">Username</label>
                            <input id="username" type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1" for="email">Email</label>
                            <input id="email" type="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm text-gray-600 mb-1" for="password">New Password (optional)</label>
                            <div class="relative">
                                <input id="password" type="password" class="w-full border rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Leave blank to keep current" />
                                <button type="button" id="togglePw" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 flex items-center gap-3">
                        <button id="saveBtn" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition">
                            <span id="saveText">Save changes</span>
                            <span id="saveSpinner" class="hidden ml-2 h-4 w-4 border-2 border-white/60 border-t-transparent rounded-full animate-spin"></span>
                        </button>
                        <span id="savedLabel" class="hidden text-sm text-emerald-600">Saved</span>
                    </div>
                </div>
            </div>
            <div class="bg-white border rounded-xl shadow">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Security tips</h2>
                    <ul class="space-y-2 text-sm text-gray-600 list-disc pl-5">
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
            el.classList.remove('hidden');
            el.className = `mb-4 p-3 rounded text-sm ${ok ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200'}`;
            setTimeout(() => { el.classList.add('hidden'); }, 4000);
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
            btn.disabled = true; spinner.classList.remove('hidden'); saved.classList.add('hidden');
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
            btn.disabled = false; spinner.classList.add('hidden'); saved.classList.remove('hidden');
        }

        document.getElementById('saveBtn').addEventListener('click', saveProfile);
        document.getElementById('togglePw').addEventListener('click', () => {
            const pw = document.getElementById('password');
            if (!pw) return;
            pw.type = pw.type === 'password' ? 'text' : 'password';
        });
        loadProfile();
    </script>
</body>
</html>


