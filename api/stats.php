<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

// Simple stats endpoint: counts for events, attendees (distinct), registrations, visits, organizers
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    json_response(['error' => 'Method not allowed'], 405);
}

try {
    $eventsCount = (int) fetchOne('SELECT COUNT(*) AS c FROM events')['c'];
    $registrationsCount = (int) fetchOne('SELECT COUNT(*) AS c FROM registrations')['c'];
    $attendeesCount = (int) fetchOne('SELECT COUNT(DISTINCT user_id) AS c FROM registrations')['c'];
    $visitsCount = (int) fetchOne('SELECT COUNT(*) AS c FROM visits')['c'];
    $organizersCount = (int) fetchOne("SELECT COUNT(*) AS c FROM users WHERE role = 'organizer'")['c'];

    json_response([
        'events' => $eventsCount,
        'registrations' => $registrationsCount,
        'attendees' => $attendeesCount,
        'visits' => $visitsCount,
        'organizers' => $organizersCount,
    ]);
} catch (Exception $e) {
    json_response(['error' => 'Failed to load stats'], 500);
}


