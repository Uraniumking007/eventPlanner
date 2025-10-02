<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// POST /api/auth.php?action=login { email, password }
// GET  /api/auth.php?action=me
// POST /api/auth.php?action=logout
// PUT  /api/auth.php?action=update { username?, email?, password? }
// POST /api/auth.php?action=register { username, email, password, role? }

$action = $_GET['action'] ?? ($method === 'GET' ? 'me' : '');

switch ($action) {
    case 'login':
        try {
            if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
            $body = read_json_body();
            $email = trim((string)($body['email'] ?? ''));
            $password = (string)($body['password'] ?? '');
            $remember = (bool)($body['remember'] ?? false);
            if ($email === '' || $password === '') {
                json_response(['error' => 'Email and password required'], 422);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                json_response(['error' => 'Invalid email format'], 422);
            }
            $user = fetchOne('SELECT id, username, email, password_hash, role, avatar_path, suspended, created_at FROM users WHERE email = ?', [$email]);
            if (!$user || !password_verify($password, $user['password_hash'])) {
                json_response(['error' => 'Invalid credentials'], 401);
            }
            if (!empty($user['suspended'])) {
                json_response(['error' => 'Account suspended'], 403);
            }
            unset($user['password_hash']);
            $_SESSION['user'] = $user;
            if ($remember) {
                $cookieName = 'remember_me';
                $secret = getenv('REMEMBER_ME_SECRET') ?: (defined('DB_PASS') ? DB_PASS : 'change-me-secret');
                $expires = time() + 60 * 60 * 24 * 30; // 30 days
                $payload = $user['id'] . '|' . $expires;
                $sig = hash_hmac('sha256', $payload, $secret);
                $value = $user['id'] . ':' . $expires . ':' . $sig;
                $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
                setcookie($cookieName, $value, [ 'expires' => $expires, 'path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax' ]);
            }
            json_response(['user' => $user]);
        } catch (Throwable $e) {
            error_log('Auth login error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    case 'me':
        try {
            if (!isset($_SESSION['user'])) {
                json_response(['user' => null]);
            }
            json_response(['user' => $_SESSION['user']]);
        } catch (Throwable $e) {
            error_log('Auth me error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    case 'logout':
        try {
            if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie('remember_me', '', [ 'expires' => time() - 3600, 'path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax' ]);
            json_response(['ok' => true]);
        } catch (Throwable $e) {
            error_log('Auth logout error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    case 'register':
        try {
            if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
            $body = read_json_body();
            $username = trim((string)($body['username'] ?? ''));
            $email = trim((string)($body['email'] ?? ''));
            $password = (string)($body['password'] ?? '');
            $role = (string)($body['role'] ?? 'attendee');
            $remember = (bool)($body['remember'] ?? false);
            if ($username === '' || $email === '' || $password === '') {
                json_response(['error' => 'username, email, password required'], 422);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                json_response(['error' => 'Invalid email format'], 422);
            }
            if (strlen($password) < 6) {
                json_response(['error' => 'Password must be at least 6 characters'], 422);
            }
            if ($role !== 'organizer' && $role !== 'attendee') {
                $role = 'attendee';
            }
            $existing = fetchOne('SELECT id FROM users WHERE email = ?', [$email]);
            if ($existing) {
                json_response(['error' => 'Email already in use'], 409);
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $newId = insert('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)', [$username, $email, $hash, $role]);
            $user = fetchOne('SELECT id, username, email, role, avatar_path, suspended, created_at FROM users WHERE id = ?', [$newId]);
            $_SESSION['user'] = $user;
            if ($remember) {
                $cookieName = 'remember_me';
                $secret = getenv('REMEMBER_ME_SECRET') ?: (defined('DB_PASS') ? DB_PASS : 'change-me-secret');
                $expires = time() + 60 * 60 * 24 * 30;
                $payload = $user['id'] . '|' . $expires;
                $sig = hash_hmac('sha256', $payload, $secret);
                $value = $user['id'] . ':' . $expires . ':' . $sig;
                $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
                setcookie($cookieName, $value, [ 'expires' => $expires, 'path' => '/', 'secure' => $secure, 'httponly' => true, 'samesite' => 'Lax' ]);
            }
            json_response(['user' => $user], 201);
        } catch (Throwable $e) {
            error_log('Auth register error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    case 'update':
        try {
            if ($method !== 'PUT' && $method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
            $user = require_auth();
            $body = read_json_body();
            $newUsername = array_key_exists('username', $body) ? trim((string)$body['username']) : $user['username'];
            $newEmail = array_key_exists('email', $body) ? trim((string)$body['email']) : $user['email'];
            $newPassword = array_key_exists('password', $body) ? (string)$body['password'] : null;

            if ($newUsername === '' || $newEmail === '') {
                json_response(['error' => 'username and email required'], 422);
            }
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                json_response(['error' => 'Invalid email format'], 422);
            }
            // If email changed, ensure unique
            if (strcasecmp((string)$newEmail, (string)$user['email']) !== 0) {
                $existing = fetchOne('SELECT id FROM users WHERE email = ? AND id <> ?', [$newEmail, $user['id']]);
                if ($existing) json_response(['error' => 'Email already in use'], 409);
            }

            if ($newPassword !== null && $newPassword !== '') {
                if (strlen($newPassword) < 6) json_response(['error' => 'Password must be at least 6 characters'], 422);
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                update('UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?', [$newUsername, $newEmail, $hash, $user['id']]);
            } else {
                update('UPDATE users SET username = ?, email = ? WHERE id = ?', [$newUsername, $newEmail, $user['id']]);
            }
            $updated = fetchOne('SELECT id, username, email, role, avatar_path, suspended, created_at FROM users WHERE id = ?', [$user['id']]);
            $_SESSION['user'] = $updated;
            json_response(['user' => $updated]);
        } catch (Throwable $e) {
            error_log('Auth update error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    case 'upload_avatar':
        try {
            if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
            $user = require_auth();

            if (!isset($_FILES['avatar']) || !is_array($_FILES['avatar'])) {
                json_response(['error' => 'No file uploaded'], 400);
            }

            $file = $_FILES['avatar'];
            $err = (int)($file['error'] ?? UPLOAD_ERR_OK);
            if ($err !== UPLOAD_ERR_OK) {
                json_response(['error' => 'Upload error'], 400);
            }

            $tmp = $file['tmp_name'] ?? '';
            $size = (int)($file['size'] ?? 0);
            if (!is_uploaded_file($tmp)) {
                json_response(['error' => 'Invalid upload'], 400);
            }
            if ($size <= 0 || $size > 5 * 1024 * 1024) {
                json_response(['error' => 'Image too large (max 5MB)'], 422);
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? finfo_file($finfo, $tmp) : null;
            if ($finfo) finfo_close($finfo);
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!$mime || !isset($allowed[$mime])) {
                json_response(['error' => 'Unsupported image type'], 422);
            }

            switch ($mime) {
                case 'image/jpeg':
                    $src = imagecreatefromjpeg($tmp);
                    break;
                case 'image/png':
                    $src = imagecreatefrompng($tmp);
                    break;
                case 'image/webp':
                    if (!function_exists('imagecreatefromwebp')) json_response(['error' => 'WEBP not supported on server'], 500);
                    $src = imagecreatefromwebp($tmp);
                    break;
                default:
                    $src = null;
            }
            if (!$src) json_response(['error' => 'Failed to process image'], 500);

            $srcW = imagesx($src);
            $srcH = imagesy($src);
            $side = min($srcW, $srcH);
            $srcX = (int) max(0, ($srcW - $side) / 2);
            $srcY = (int) max(0, ($srcH - $side) / 2);

            $dstSize = 512;
            $dst = imagecreatetruecolor($dstSize, $dstSize);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $dstSize, $dstSize, $transparent);
            if (!imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $dstSize, $dstSize, $side, $side)) {
                imagedestroy($src);
                imagedestroy($dst);
                json_response(['error' => 'Failed to resize image'], 500);
            }
            imagedestroy($src);

            $root = dirname(__DIR__);
            $targetDir = $root . '/uploads/avatars';
            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                    imagedestroy($dst);
                    json_response(['error' => 'Failed to prepare upload directory'], 500);
                }
            }
            $filename = 'ava_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.png';
            $targetPath = $targetDir . '/' . $filename;
            if (!imagepng($dst, $targetPath)) {
                imagedestroy($dst);
                json_response(['error' => 'Failed to save image'], 500);
            }
            imagedestroy($dst);

            $webPath = '/uploads/avatars/' . $filename;
            update('UPDATE users SET avatar_path = ? WHERE id = ?', [$webPath, $user['id']]);
            $updated = fetchOne('SELECT id, username, email, role, avatar_path, suspended, created_at FROM users WHERE id = ?', [$user['id']]);
            $_SESSION['user'] = $updated;
            json_response(['user' => $updated]);
        } catch (Throwable $e) {
            error_log('Auth upload_avatar error: ' . $e->getMessage());
            json_response(['error' => 'Internal server error'], 500);
        }
        break;

    default:
        json_response(['error' => 'Not found'], 404);
}


