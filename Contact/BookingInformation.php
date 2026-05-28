<?php

if (isset($_GET['date'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');

    $date = $_GET['date'];

    $apiLang = isset($_GET['lang']) && in_array($_GET['lang'], ['lv', 'en'], true)
        ? $_GET['lang']
        : 'lv';
    $tPath   = __DIR__ . "/../translations/$apiLang/$apiLang.json";
    $t       = is_readable($tPath) ? (json_decode(file_get_contents($tPath), true) ?: []) : [];
    if (!is_array($t)) {
        $t = [];
    }

    $rentalTypeKey = static function (?string $type): ?string {
        if ($type === null || $type === '') {
            return null;
        }
        $map = [
            'teltis'     => 'reservation_item_tent',
            'namins'     => 'reservation_item_house',
            'teritorija' => 'reservation_item_rest',
            'kubls'      => 'reservation_item_hottub',
            'pirts'      => 'sauna',
        ];
        return $map[$type] ?? null;
    };

    $rentalLabel = static function (?string $type, ?string $name) use ($t, $rentalTypeKey): string {
        $key = $rentalTypeKey($type);
        if ($key !== null && !empty($t[$key])) {
            return (string) $t[$key];
        }
        if ($name !== null && trim($name) !== '') {
            return trim($name);
        }
        return (string) ($t['booking_calendar_rental_generic'] ?? 'Rental');
    };

    $peTypeLabel = static function (?string $eventType) use ($t): string {
        if ($eventType === null || trim($eventType) === '') {
            return '';
        }
        $slug = strtolower(trim($eventType));
        $map  = [
            'wedding'     => 'private_events_form_event_wedding',
            'anniversary' => 'private_events_form_event_anniversary',
            'sports'      => 'private_events_form_event_sports',
            'corporate'   => 'private_events_form_event_corporate',
        ];
        $key = $map[$slug] ?? null;
        if ($key !== null && !empty($t[$key])) {
            return (string) $t[$key];
        }
        return (string) $eventType;
    };

    // Demo-only sample data: weekends appear as occupied.
    $results = [];
    try {
        $weekday = (int) (new DateTimeImmutable($date))->format('N');
        if ($weekday >= 5) {
            $results[] = [
                'start_date' => $date,
                'end_date'   => $date,
                'item_name'  => $rentalLabel('namins', null),
            ];
        }
        if ($weekday === 6) {
            $pePrefix = (string) ($t['booking_calendar_private_event'] ?? 'Private event');
            $results[] = [
                'start_date' => $date,
                'end_date'   => $date,
                'item_name'  => $pePrefix . ' (' . $peTypeLabel('wedding') . ')',
            ];
        }
    } catch (Throwable $e) {
        $results = [];
    }

    echo json_encode($results);
    exit;
}
?>

<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<main class="rental-with-hero">
    <section class="rules-hero" aria-labelledby="booking-info-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="booking-info-hero-title" class="rules-hero-title"><?= htmlspecialchars($texts['contact_booking_title'] ?? 'Rezervācijas Kalendārs') ?></h1>
        </div>
    </section>

    <section class="rental-section booking-section">
        <p class="rental-subtitle"><?= htmlspecialchars($texts['contact_booking_paragraph'] ?? 'Lai veiktu rezervāciju, izvēlieties vēlamās datumus, aizpildiet savus kontaktus un vēlmes, un iesniedziet pieprasījumu. Mēs drīzumā apstiprināsim pieejamību.') ?></p>

        <?php include '../Calendar.php'; ?>
    </section>
</main>

<?php include "../footer.php"; ?>