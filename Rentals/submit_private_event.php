<?php
require_once __DIR__ . '/../lang.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$sentToken = $_POST['_csrf'] ?? '';
$realToken = $_SESSION['pe_csrf'] ?? '';
if ($sentToken === '' || !is_string($sentToken) || !hash_equals($realToken, $sentToken)) {
    http_response_code(419);
    echo json_encode(['ok' => false, 'error' => 'Session expired. Please refresh the page and try again.']);
    exit;
}

$name  = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Please fill in required fields.']);
    exit;
}

$_SESSION['pe_csrf'] = bin2hex(random_bytes(32));
echo json_encode([
    'ok' => true,
    'id' => random_int(1000, 9999),
    'note' => 'Design preview mode: inquiry not stored in database.',
]);
