<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// POST /api/auth.php?action=login { email, password }
// GET  /api/auth.php?action=me
// POST /api/auth.php?action=logout

$action = $_GET['action'] ?? ($method === 'GET' ? 'me' : '');

switch ($action) {
    case 'login':
        if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
        $body = read_json_body();
        $email = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');
        if ($email === '' || $password === '') {
            json_response(['error' => 'Email and password required'], 422);
        }
        $user = fetchOne('SELECT id, username, email, password_hash, role, created_at FROM users WHERE email = ?', [$email]);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            json_response(['error' => 'Invalid credentials'], 401);
        }
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        json_response(['user' => $user]);
        break;

    case 'me':
        if (!isset($_SESSION['user'])) {
            json_response(['user' => null]);
        }
        json_response(['user' => $_SESSION['user']]);
        break;

    case 'logout':
        if ($method !== 'POST') json_response(['error' => 'Method not allowed'], 405);
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        json_response(['ok' => true]);
        break;

    default:
        json_response(['error' => 'Not found'], 404);
}


