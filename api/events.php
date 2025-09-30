<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $event = fetchOne('SELECT e.*, u.username AS organizer_name,
                (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registration_count
            FROM events e JOIN users u ON e.organizer_id = u.id WHERE e.id = ?', [$id]);
            if (!$event) json_response(['error' => 'Not found'], 404);
            json_response(['event' => $event]);
        } else {
            $events = fetchAll('SELECT e.*, u.username AS organizer_name,
                (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registration_count
            FROM events e JOIN users u ON e.organizer_id = u.id ORDER BY e.event_date DESC');
            json_response(['events' => $events]);
        }
        break;

    case 'POST':
        $user = require_auth();
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        $body = read_json_body();
        $title = trim((string)($body['title'] ?? ''));
        $description = (string)($body['description'] ?? null);
        $event_date = (string)($body['event_date'] ?? '');
        $location = trim((string)($body['location'] ?? ''));
        $image_path = (string)($body['image_path'] ?? null);
        if ($title === '' || $event_date === '' || $location === '') {
            json_response(['error' => 'title, event_date, location required'], 422);
        }
        $newId = insert('INSERT INTO events (organizer_id, title, description, event_date, location, image_path) VALUES (?, ?, ?, ?, ?, ?)', [
            $user['id'], $title, $description, $event_date, $location, $image_path
        ]);
        $created = fetchOne('SELECT * FROM events WHERE id = ?', [$newId]);
        json_response(['event' => $created], 201);
        break;

    case 'PUT':
        $user = require_auth();
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        if (!$id) json_response(['error' => 'id required'], 400);
        $body = read_json_body();
        $event = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
        if (!$event) json_response(['error' => 'Not found'], 404);
        if ((int)$event['organizer_id'] !== (int)$user['id']) json_response(['error' => 'Forbidden'], 403);
        $title = array_key_exists('title', $body) ? (string)$body['title'] : $event['title'];
        $description = array_key_exists('description', $body) ? (string)$body['description'] : $event['description'];
        $event_date = array_key_exists('event_date', $body) ? (string)$body['event_date'] : $event['event_date'];
        $location = array_key_exists('location', $body) ? (string)$body['location'] : $event['location'];
        $image_path = array_key_exists('image_path', $body) ? (string)$body['image_path'] : $event['image_path'];
        update('UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, image_path = ? WHERE id = ?', [
            $title, $description, $event_date, $location, $image_path, $id
        ]);
        $updated = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
        json_response(['event' => $updated]);
        break;

    case 'DELETE':
        $user = require_auth();
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        if (!$id) json_response(['error' => 'id required'], 400);
        $event = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
        if (!$event) json_response(['error' => 'Not found'], 404);
        if ((int)$event['organizer_id'] !== (int)$user['id']) json_response(['error' => 'Forbidden'], 403);
        delete('DELETE FROM events WHERE id = ?', [$id]);
        json_response(['ok' => true]);
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}


