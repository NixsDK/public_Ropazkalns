<?php
require_once __DIR__ . '/../lang.php';

// Design-only public copy: profile updates are intentionally disabled.
$_SESSION['user_flash'][] = [
    'type' => 'danger',
    'message' => 'Design preview mode: profile changes are not saved.',
];

header('Location: profile.php?lang=' . urlencode($langCode));
exit;
