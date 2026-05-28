<?php
/**
 * gallery_repository.php
 * -----------------------------------------------------------------
 * Thin data-access layer for the gallery page. Returns plain arrays
 * so the view code (About/Gallery.php) does not change shape.
 *
 * Tables: gallery_categories, gallery_images (see /sql/01_schema.sql)
 */

if (!function_exists('gallery_fetch_active_items')) {
    /**
     * Active images, ordered by category sort_order then image sort_order.
     * Each row: ['file' => string, 'cat' => string, 'alt' => ?string]
     * Returns [] on any error (caller should fall back to a default list).
     *
     * @return array<int, array{file:string, cat:string, alt:?string}>
     */
    function gallery_fetch_active_items(?PDO $db): array
    {
        if (!$db) {
            return [];
        }
        try {
            $sql = "SELECT i.file_name AS file,
                           c.slug      AS cat,
                           i.alt_text  AS alt
                    FROM gallery_images i
                    INNER JOIN gallery_categories c ON c.id = i.category_id
                    WHERE i.is_active = 1
                      AND c.is_active = 1
                    ORDER BY c.sort_order, c.id, i.sort_order, i.id";
            return $db->query($sql)->fetchAll();
        } catch (Throwable $e) {
            error_log('[gallery_repository] fetch items failed: ' . $e->getMessage());
            return [];
        }
    }
}

if (!function_exists('gallery_fetch_active_categories')) {
    /**
     * Active category slugs in display order.
     *
     * @return string[]
     */
    function gallery_fetch_active_categories(?PDO $db): array
    {
        if (!$db) {
            return [];
        }
        try {
            $sql = "SELECT slug
                    FROM gallery_categories
                    WHERE is_active = 1
                    ORDER BY sort_order, id";
            return $db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        } catch (Throwable $e) {
            error_log('[gallery_repository] fetch categories failed: ' . $e->getMessage());
            return [];
        }
    }
}
