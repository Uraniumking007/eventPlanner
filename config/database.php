<?php
/**
 * Database connection (PDO)
 *
 * Usage:
 *   require_once __DIR__ . '/database.php';
 *   $pdo = getDBConnection();
 */

// Configuration via environment variables (with sensible defaults)
// Default to internal Docker network service `db` and local credentials defined in docker-compose.yml
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'event_planner');
define('DB_USER', getenv('DB_USER') ?: 'eventplanner');
define('DB_PASS', getenv('DB_PASS') ?: 'eventplanner123');
define('DB_CHARSET', 'utf8mb4');

// PDO options
define('PDO_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

/**
 * Get singleton PDO connection
 * @return PDO
 * @throws Exception
 */
function getDBConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, PDO_OPTIONS);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }

    return $pdo;
}

/**
 * Execute a prepared statement
 * @param string $sql
 * @param array $params
 * @return PDOStatement
 */
function executeQuery($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch all rows
 * @param string $sql
 * @param array $params
 * @return array
 */
function fetchAll($sql, $params = []) {
    return executeQuery($sql, $params)->fetchAll();
}

/**
 * Fetch one row
 * @param string $sql
 * @param array $params
 * @return array|false
 */
function fetchOne($sql, $params = []) {
    return executeQuery($sql, $params)->fetch();
}

/**
 * Insert helper (returns last insert id)
 * @param string $sql
 * @param array $params
 * @return int
 */
function insert($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int) $pdo->lastInsertId();
}

/**
 * Update/Delete helper (returns affected rows)
 * @param string $sql
 * @param array $params
 * @return int
 */
function update($sql, $params = []) {
    return executeQuery($sql, $params)->rowCount();
}

/**
 * Alias for update when semantically deleting
 * @param string $sql
 * @param array $params
 * @return int
 */
function delete($sql, $params = []) {
    return update($sql, $params);
}


