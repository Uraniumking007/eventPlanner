<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$eventId = isset($_GET['event_id']) ? (int) $_GET['event_id'] : null;

switch ($method) {
    case 'GET':
        if ($eventId) {
            $users = fetchAll('SELECT u.id, u.username, u.email, r.registered_at FROM registrations r JOIN users u ON r.user_id = u.id WHERE r.event_id = ? ORDER BY u.username', [$eventId]);
            json_response(['attendees' => $users]);
        } else {
            $user = require_auth();
            $regs = fetchAll('SELECT e.* FROM registrations r JOIN events e ON r.event_id = e.id WHERE r.user_id = ? ORDER BY e.event_date DESC', [$user['id']]);
            json_response(['my_registrations' => $regs]);
        }
        break;

    case 'POST':
        $user = require_auth();
        $body = read_json_body();
        $eventId = (int)($body['event_id'] ?? 0);
        if ($eventId <= 0) json_response(['error' => 'event_id required'], 422);
        $exists = fetchOne('SELECT id FROM events WHERE id = ?', [$eventId]);
        if (!$exists) json_response(['error' => 'Event not found'], 404);
        try {
            insert('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)', [$user['id'], $eventId]);
        } catch (Exception $e) {
            json_response(['error' => 'Already registered'], 409);
        }
        json_response(['ok' => true], 201);
        break;

    case 'DELETE':
        $user = require_auth();
        if (!$eventId) json_response(['error' => 'event_id required'], 400);
        delete('DELETE FROM registrations WHERE user_id = ? AND event_id = ?', [$user['id'], $eventId]);
        json_response(['ok' => true]);
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}


