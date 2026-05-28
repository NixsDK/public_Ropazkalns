<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$langCode = $_SESSION['lang'] ?? 'lv';

// Calling translations from JSON file 
$translationsPath = __DIR__ . "/translations/$langCode/$langCode.json";
$translations     = file_exists($translationsPath)
    ? json_decode(file_get_contents($translationsPath), true)
    : [];
if (!is_array($translations)) {
    $translations = [];
}

// DB overrides JSON when it works.
// If the DB fails, translations still come from JSON (backup).
try {
    require_once __DIR__ . '/includes/db_safe.php';
    require_once __DIR__ . '/includes/text_repository.php';

    $textDb = db_safe_connect();
    if ($textDb instanceof PDO) {
        $overrides = text_load_overrides($textDb, $langCode);
        if (!empty($overrides)) {
            // Skips empty DB values so they don't replace JSON
            $overrides = array_filter(
                $overrides,
                static fn ($v) => $v !== null && trim((string) $v) !== ''
            );
            if (!empty($overrides)) {
                $translations = array_merge($translations, $overrides);
            }
        }
    }
} catch (Throwable $e) {
    error_log('[lang.php] override load failed: ' . $e->getMessage());
}

$texts = $translations;
$lang  = $translations;
