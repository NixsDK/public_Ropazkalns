<?php
// Demo-only price calculator used in the public showcase copy.
$rates = [
    'tent'   => 18.0,
    'house'  => 85.0,
    'rest'   => 55.0,
    'hottub' => 40.0,
];

$slugs = $_POST['rental_type'] ?? [];
$qtys  = $_POST['quantity'] ?? [];
$from  = (string) ($_POST['from_date'] ?? '');
$to    = (string) ($_POST['to_date'] ?? '');
$people = max(1, (int) ($_POST['people_count'] ?? 1));

if (empty($slugs) || $from === '' || $to === '') {
    exit('Please choose at least one item and dates.');
}

$days = 1;
try {
    $days = max(1, (new DateTimeImmutable($from))->diff(new DateTimeImmutable($to))->days);
} catch (Throwable $e) {
    $days = 1;
}

$total = 0.0;
foreach ($slugs as $i => $slug) {
    $slug = (string) $slug;
    if (!isset($rates[$slug])) {
        continue;
    }
    $qty = max(1, (int) ($qtys[$i] ?? 1));
    $total += $rates[$slug] * $qty * $days;
}

if ($people > 4) {
    $total += ($people - 4) * 7.5 * $days;
}

if (!empty($_POST['confirm'])) {
    echo 'Demo booking saved. Final total: €' . number_format($total, 2)
        . '<br><small>This is a design-only preview. No database write happened.</small>';
    exit;
}

echo '<strong>Estimated price for ' . $days . ' day(s): €' . number_format($total, 2) . '</strong>'
    . '<br><small>Design preview mode (mock calculation).</small>';
