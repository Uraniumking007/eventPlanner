<?php
declare(strict_types=1);

// Basic API bootstrap: CORS, sessions, JSON helpers

// Allow simple CORS for local dev
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Ensure session for auth
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB
require_once __DIR__ . '/../config/database.php';

// Remember-me auto login
// Cookie format: userId:expires:signature where signature = HMAC_SHA256(userId|expires, SECRET)
// SECRET from env REMEMBER_ME_SECRET or fallback to DB_PASS (kept simple for this project)
if (!isset($_SESSION['user'])) {
    $cookieName = 'remember_me';
    $cookie = $_COOKIE[$cookieName] ?? null;
    $secret = getenv('REMEMBER_ME_SECRET') ?: (defined('DB_PASS') ? DB_PASS : 'change-me-secret');
    if ($cookie && $secret) {
        $parts = explode(':', $cookie, 3);
        if (count($parts) === 3) {
            [$uid, $exp, $sig] = $parts;
            $uid = (int) $uid;
            $exp = (int) $exp;
            if ($uid > 0 && $exp > time()) {
                $expected = hash_hmac('sha256', $uid . '|' . $exp, $secret);
                if (hash_equals($expected, $sig)) {
                    try {
                        $user = fetchOne('SELECT id, username, email, role, suspended, created_at FROM users WHERE id = ?', [$uid]);
                        if ($user) {
                            if (empty($user['suspended'])) {
                                $_SESSION['user'] = $user;
                            }
                        }
                    } catch (Exception $e) {
                        // ignore auto-login errors
                    }
                }
            }
        }
    }
}

function json_response($data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function read_json_body(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '[]', true);
    return is_array($data) ? $data : [];
}

function require_auth(): array {
    if (!isset($_SESSION['user'])) {
        json_response(['error' => 'Unauthorized'], 401);
    }
    return $_SESSION['user'];
}


