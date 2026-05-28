<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// Demo-only blocked dates so the public showcase calendar still looks real.
$today = new DateTimeImmutable('today');
$blocked = [];
foreach ([2, 5, 9, 14, 21] as $offset) {
    $blocked[] = $today->modify('+' . $offset . ' day')->format('Y-m-d');
}

echo json_encode($blocked);
