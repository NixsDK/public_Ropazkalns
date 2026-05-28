<?php
/**
 * db_safe.php
 * -----------------------------------------------------------------
 * Returns a PDO connection or NULL if the database is unreachable.
 *
 * Uses .env variables for secure, environment-agnostic credentials.
 */

// 1. Include Composer's autoloader from the project root
// dirname(__DIR__) goes up one level from /includes/ to the main folder
require_once dirname(__DIR__) . '/vendor/autoload.php';

if (!function_exists('db_safe_connect')) {
    function db_safe_connect(): ?PDO
    {
        static $cached = null;
        static $tried  = false;

        if ($tried) {
            return $cached;
        }
        $tried = true;

        try {
            // 2. Load the .env file from the project root
            $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->load();

            // 3. Retrieve variables securely
            $host    = $_ENV['DB_HOST'];
            $dbname  = $_ENV['DB_NAME'];
            $user    = $_ENV['DB_USER'];
            $pass    = $_ENV['DB_PASS'];
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4'; // Added fallback just in case

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            // 4. Maintain the safe, snappy PDO options
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 3, // seconds; keep page snappy
            ];

            $cached = new PDO($dsn, $user, $pass, $options);

        } catch (Throwable $e) {
            // 5. Fail gracefully: log the error and return null instead of dying
            error_log('[db_safe] connection failed: ' . $e->getMessage());
            $cached = null;
        }

        return $cached;
    }
}