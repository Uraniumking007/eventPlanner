<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

// Admin-only user management API
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? '';

$current = require_auth();
if (($current['role'] ?? '') !== 'admin') {
    json_response(['error' => 'Forbidden'], 403);
}

switch ($method) {
    case 'GET':
        // List users
        $users = fetchAll('SELECT id, username, email, role, suspended, created_at FROM users ORDER BY created_at DESC');
        json_response(['users' => $users]);
        break;

    case 'PUT':
    case 'POST':
        $body = read_json_body();
        $userId = (int)($body['id'] ?? 0);
        if ($userId <= 0) json_response(['error' => 'id required'], 422);

        if ($action === 'role') {
            $role = (string)($body['role'] ?? '');
            if (!in_array($role, ['admin','organizer','attendee'], true)) json_response(['error' => 'invalid role'], 422);
            update('UPDATE users SET role = ? WHERE id = ?', [$role, $userId]);
            $user = fetchOne('SELECT id, username, email, role, suspended, created_at FROM users WHERE id = ?', [$userId]);
            json_response(['user' => $user]);
        } elseif ($action === 'suspend') {
            $suspended = (int) (!!($body['suspended'] ?? false));
            $reason = isset($body['reason']) ? (string)$body['reason'] : null;
            update('UPDATE users SET suspended = ? WHERE id = ?', [$suspended, $userId]);
            // Optional: record to feedback table as simple audit (kept minimal to avoid new table)
            if ($reason !== null && $reason !== '') {
                try { insert('INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)', ['admin_action', 'noreply@local', 'User ' . $userId . ' suspend=' . $suspended . ' reason=' . $reason]); } catch (Throwable $e) {}
            }
            $user = fetchOne('SELECT id, username, email, role, suspended, created_at FROM users WHERE id = ?', [$userId]);
            json_response(['user' => $user]);
        } elseif ($action === 'password') {
            $newPassword = (string)($body['password'] ?? '');
            if (strlen($newPassword) < 6) json_response(['error' => 'Password must be at least 6 characters'], 422);
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            update('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $userId]);
            json_response(['ok' => true]);
        } else {
            json_response(['error' => 'Unknown action'], 400);
        }
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}


