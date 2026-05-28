<?php
/**
 * Demo auth for public showcase copy (no DB required).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('demo_user_record')) {
    /**
     * @return array<string,mixed>
     */
    function demo_user_record(): array
    {
        return [
            'id' => 1,
            'username' => 'demo_user',
            'email' => 'demo@example.com',
            'full_name' => 'Demo User',
            'created_at' => '2026-01-15 12:00:00',
            'avatar_path' => '',
        ];
    }
}

if (!function_exists('user_is_logged_in')) {
    function user_is_logged_in(): bool
    {
        return !empty($_SESSION['site_user_id']) && (int) $_SESSION['site_user_id'] === 1;
    }
}

if (!function_exists('user_current')) {
    /**
     * @return array<string,mixed>|null
     */
    function user_current(): ?array
    {
        if (!user_is_logged_in()) {
            return null;
        }
        return demo_user_record();
    }
}

if (!function_exists('user_require_login')) {
    function user_require_login(string $loginPath = '/login/login.php'): void
    {
        if (user_is_logged_in()) {
            return;
        }
        $next = $_SERVER['REQUEST_URI'] ?? '';
        $target = $loginPath;
        if ($loginPath !== '' && $loginPath[0] !== '/' && $loginPath[0] !== '.') {
            $target = '../' . ltrim($loginPath, '/');
        }
        header('Location: ' . $target . '?next=' . rawurlencode($next));
        exit;
    }
}

if (!function_exists('user_logout')) {
    function user_logout(): void
    {
        unset($_SESSION['site_user_id'], $_SESSION['user_csrf']);
    }
}

if (!function_exists('user_csrf_token')) {
    function user_csrf_token(): string
    {
        if (empty($_SESSION['user_csrf'])) {
            $_SESSION['user_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['user_csrf'];
    }
}

if (!function_exists('user_csrf_field')) {
    function user_csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="'
            . htmlspecialchars(user_csrf_token(), ENT_QUOTES) . '">';
    }
}

if (!function_exists('user_csrf_check')) {
    function user_csrf_check(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }
        $sent = $_POST['_csrf'] ?? '';
        $real = $_SESSION['user_csrf'] ?? '';
        if (!is_string($sent) || $sent === '' || !hash_equals($real, $sent)) {
            http_response_code(419);
            die('Session expired. Please refresh the page and try again.');
        }
    }
}

if (!function_exists('user_flash')) {
    function user_flash(string $type, string $message): void
    {
        $_SESSION['user_flash'][] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('user_flash_take')) {
    function user_flash_take(): array
    {
        $msgs = $_SESSION['user_flash'] ?? [];
        unset($_SESSION['user_flash']);
        return $msgs;
    }
}
