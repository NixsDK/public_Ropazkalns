<?php
/**
 * text_repository.php
 * -----------------------------------------------------------------
 * Loads editable text overrides from the `site_texts` table.
 *
 * The result is merged ON TOP of the JSON translations in lang.php,
 * so any key present here wins, and any key missing here keeps the
 * value from translations/<lang>/<lang>.json.
 */

if (!function_exists('text_load_overrides')) {
    /**
     * @return array<string, string>  map of text_key => text_value
     */
    function text_load_overrides(?PDO $db, string $langCode): array
    {
        if (!$db || $langCode === '') {
            return [];
        }
        try {
            $stmt = $db->prepare(
                'SELECT text_key, text_value
                   FROM site_texts
                  WHERE lang_code = :lang'
            );
            $stmt->execute([':lang' => $langCode]);
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        } catch (Throwable $e) {
            error_log('[text_repository] load overrides failed: ' . $e->getMessage());
            return [];
        }
    }
}
