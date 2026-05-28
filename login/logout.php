<?php
require_once __DIR__ . '/../lang.php';
require_once __DIR__ . '/../includes/user_auth.php';

user_logout();
header('Location: login.php?lang=' . urlencode($langCode));
exit;
