<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';

// Local helpers for this endpoint
function normalize_datetime_or_null(?string $value): ?string {
    if ($value === null) return null;
    $v = trim($value);
    if ($v === '') return null;
    // Accept HTML datetime-local value: YYYY-MM-DDTHH:MM or with seconds
    $v = str_replace('T', ' ', $v);
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $v)) {
        $v .= ':00';
    }
    return $v;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$action = $_GET['action'] ?? '';

function log_event_action(?int $eventId, int $adminUserId, string $action, ?string $reason): void {
    try {
        insert('INSERT INTO event_audit_logs (event_id, admin_user_id, action, reason) VALUES (?, ?, ?, ?)', [ $eventId, $adminUserId, $action, $reason ]);
    } catch (Throwable $e) {
        // ignore audit failures
    }
}

switch ($method) {
    case 'GET':
        if ($action === 'logs') {
            $user = $_SESSION['user'] ?? null;
            if (!$user || ($user['role'] ?? '') !== 'admin') json_response(['error' => 'Forbidden'], 403);
            $eventId = isset($_GET['event_id']) ? (int) $_GET['event_id'] : null;
            if ($eventId) {
                $logs = fetchAll('SELECT l.*, u.username AS admin_name FROM event_audit_logs l JOIN users u ON l.admin_user_id = u.id WHERE l.event_id = ? ORDER BY l.created_at DESC', [$eventId]);
            } else {
                $logs = fetchAll('SELECT l.*, u.username AS admin_name FROM event_audit_logs l JOIN users u ON l.admin_user_id = u.id ORDER BY l.created_at DESC LIMIT 200');
            }
            json_response(['logs' => $logs]);
        } elseif ($id) {
            $event = fetchOne("SELECT e.*, u.username AS organizer_name,
                (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registration_count,
                (SELECT l.reason FROM event_audit_logs l WHERE l.event_id = e.id AND l.action = 'update' ORDER BY l.created_at DESC LIMIT 1) AS suspend_reason
            FROM events e JOIN users u ON e.organizer_id = u.id WHERE e.id = ?", [$id]);
            if (!$event) json_response(['error' => 'Not found'], 404);
            json_response(['event' => $event]);
        } else {
            $events = fetchAll("SELECT e.*, u.username AS organizer_name,
                (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registration_count,
                (SELECT l.reason FROM event_audit_logs l WHERE l.event_id = e.id AND l.action = 'update' ORDER BY l.created_at DESC LIMIT 1) AS suspend_reason
            FROM events e JOIN users u ON e.organizer_id = u.id ORDER BY e.event_date DESC");
            json_response(['events' => $events]);
        }
        break;

    case 'POST':
        $user = require_auth();
        // Admins are not allowed to create events here
        if ($user['role'] === 'admin') json_response(['error' => 'Forbidden'], 403);
        if ($user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $isMultipart = stripos($contentType, 'multipart/form-data') !== false || !empty($_FILES);

        $title = '';
        $description = null;
        $event_date = '';
        $registration_close = null;
        $location = '';
        $image_path = null;

        if ($isMultipart) {
            // Read from form fields
            $title = trim((string)($_POST['title'] ?? ''));
            $description = (string)($_POST['description'] ?? null);
            $event_date = (string)($_POST['event_date'] ?? '');
            $registration_close = normalize_datetime_or_null(isset($_POST['registration_close']) ? (string)$_POST['registration_close'] : null);
            $location = trim((string)($_POST['location'] ?? ''));
            $image_url_fallback = (string)($_POST['image_path'] ?? '');

            // Handle uploaded image file if provided
            if (isset($_FILES['image_file']) && is_array($_FILES['image_file']) && (int)($_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['image_file'];
                $err = (int)($file['error'] ?? UPLOAD_ERR_OK);
                if ($err === UPLOAD_ERR_OK) {
                    $tmp = $file['tmp_name'] ?? '';
                    $size = (int)($file['size'] ?? 0);
                    if (!is_uploaded_file($tmp)) {
                        json_response(['error' => 'Invalid upload'], 400);
                    }
                    // Limit 5MB
                    if ($size <= 0 || $size > 5 * 1024 * 1024) {
                        json_response(['error' => 'Image too large (max 5MB)'], 422);
                    }
                    // Validate MIME type
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = $finfo ? finfo_file($finfo, $tmp) : null;
                    if ($finfo) finfo_close($finfo);
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    if (!$mime || !isset($allowed[$mime])) {
                        json_response(['error' => 'Unsupported image type'], 422);
                    }
                    $ext = $allowed[$mime];
                    // Ensure upload directory exists
                    $root = dirname(__DIR__);
                    $targetDir = $root . '/uploads/events';
                    if (!is_dir($targetDir)) {
                        if (!mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                            json_response(['error' => 'Failed to prepare upload directory'], 500);
                        }
                    }
                    // Generate unique filename
                    $base = 'evt_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4));
                    $filename = $base . '.' . $ext;
                    $targetPath = $targetDir . '/' . $filename;
                    if (!move_uploaded_file($tmp, $targetPath)) {
                        json_response(['error' => 'Failed to save image'], 500);
                    }
                    // Web path
                    $image_path = '/uploads/events/' . $filename;
                } else {
                    // If there was an upload error other than no file, return a message
                    if ($err !== UPLOAD_ERR_NO_FILE) {
                        json_response(['error' => 'Upload error'], 400);
                    }
                }
            }
            // Fallback to provided URL if no file was saved
            if ($image_path === null && $image_url_fallback !== '') {
                $image_path = $image_url_fallback;
            }
        } else {
            // Read JSON payload (existing behavior)
            $body = read_json_body();
            $title = trim((string)($body['title'] ?? ''));
            $description = (string)($body['description'] ?? null);
            $event_date = (string)($body['event_date'] ?? '');
            $registration_close = normalize_datetime_or_null(isset($body['registration_close']) ? (string)$body['registration_close'] : null);
            $location = trim((string)($body['location'] ?? ''));
            $image_path = (string)($body['image_path'] ?? null);
        }

        if ($title === '' || $event_date === '' || $location === '') {
            json_response(['error' => 'title, event_date, location required'], 422);
        }

        $newId = insert('INSERT INTO events (organizer_id, title, description, event_date, registration_close, location, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $user['id'], $title, $description, $event_date, $registration_close, $location, $image_path
        ]);
        $created = fetchOne('SELECT * FROM events WHERE id = ?', [$newId]);
        json_response(['event' => $created], 201);
        break;

    case 'PUT':
        $user = require_auth();
        $isAdmin = ($user['role'] === 'admin');
        if (!$isAdmin && $user['role'] !== 'organizer') json_response(['error' => 'Forbidden'], 403);
        if (!$id) json_response(['error' => 'id required'], 400);
        $body = read_json_body();
        $event = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
        if (!$event) json_response(['error' => 'Not found'], 404);
        if ($isAdmin) {
            // Admins can only toggle suspension via PUT { suspended, reason }
            if (!array_key_exists('suspended', $body)) json_response(['error' => 'suspended required'], 422);
            $suspended = (int) (!!$body['suspended']);
            $reason = isset($body['reason']) ? trim((string)$body['reason']) : null;
            update('UPDATE events SET suspended = ? WHERE id = ?', [ $suspended, $id ]);
            log_event_action($id, (int)$user['id'], $suspended ? 'update' : 'update', $reason);
            $updated = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
            json_response(['event' => $updated]);
        } else {
            if ((int)$event['organizer_id'] !== (int)$user['id']) json_response(['error' => 'Forbidden'], 403);
            $title = array_key_exists('title', $body) ? (string)$body['title'] : $event['title'];
            $description = array_key_exists('description', $body) ? (string)$body['description'] : $event['description'];
            $event_date = array_key_exists('event_date', $body) ? (string)$body['event_date'] : $event['event_date'];
            $registration_close_raw = array_key_exists('registration_close', $body) ? (string)$body['registration_close'] : (string)($event['registration_close'] ?? '');
            $registration_close = normalize_datetime_or_null($registration_close_raw);
            $location = array_key_exists('location', $body) ? (string)$body['location'] : $event['location'];
            $image_path = array_key_exists('image_path', $body) ? (string)$body['image_path'] : $event['image_path'];
            update('UPDATE events SET title = ?, description = ?, event_date = ?, registration_close = ?, location = ?, image_path = ? WHERE id = ?', [
                $title, $description, $event_date, $registration_close, $location, $image_path, $id
            ]);
            $updated = fetchOne('SELECT * FROM events WHERE id = ?', [$id]);
            json_response(['event' => $updated]);
        }
        break;

    case 'DELETE':
        $user = require_auth();
        $isAdmin = ($user['role'] === 'admin');
        if ($isAdmin) json_response(['error' => 'Forbidden'], 403);
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


