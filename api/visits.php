<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($method) {
    case 'POST':
        $body = read_json_body();
        $sessionId = trim((string)($body['session_id'] ?? session_id()));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $pageUrl = (string)($body['page_url'] ?? null);
        insert('INSERT INTO visits (session_id, ip_address, page_url) VALUES (?, ?, ?)', [$sessionId, $ip, $pageUrl]);
        json_response(['ok' => true], 201);
        break;

    case 'GET':
        $user = require_auth();
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        $items = fetchAll('SELECT * FROM visits ORDER BY visit_time DESC LIMIT 200');
        json_response(['visits' => $items]);
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}


