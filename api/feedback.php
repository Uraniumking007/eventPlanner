<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($method) {
    case 'POST':
        $body = read_json_body();
        $name = trim((string)($body['name'] ?? ''));
        $email = trim((string)($body['email'] ?? ''));
        $message = trim((string)($body['message'] ?? ''));
        if ($name === '' || $email === '' || $message === '') {
            json_response(['error' => 'name, email, message required'], 422);
        }
        insert('INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)', [$name, $email, $message]);
        json_response(['ok' => true], 201);
        break;

    case 'GET':
        // Simple listing for organizers only
        $user = require_auth();
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        $items = fetchAll('SELECT * FROM feedback ORDER BY submitted_at DESC');
        json_response(['feedback' => $items]);
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}


