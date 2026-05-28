<?php
require_once __DIR__ . '/../lang.php';

// Design-only public copy: avatar uploads are intentionally disabled.
$_SESSION['user_flash'][] = [
    'type' => 'danger',
    'message' => 'Design preview mode: avatar upload is disabled.',
];

header('Location: profile.php?lang=' . urlencode($langCode));
exit;
