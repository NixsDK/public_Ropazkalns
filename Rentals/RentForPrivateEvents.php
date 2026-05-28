<?php
include "../lang.php";
include "../head.php";
// Public showcase mode: static extras list, no DB needed.
$peExtras = [
    ['id' => 1, 'label_lv' => 'Papildu galdi', 'label_en' => 'Extra tables'],
    ['id' => 2, 'label_lv' => 'Skaņas sistēma', 'label_en' => 'Sound system'],
    ['id' => 3, 'label_lv' => 'Dekorēšana', 'label_en' => 'Decoration'],
];

// Per-form CSRF token. Stored on the public-site session that lang.php
// already starts; submit_private_event.php verifies it.
if (empty($_SESSION['pe_csrf'])) {
    $_SESSION['pe_csrf'] = bin2hex(random_bytes(32));
}
$peCsrf = $_SESSION['pe_csrf'];

// Demo pre-fill values.
$prefillName  = 'Demo Guest';
$prefillEmail = 'demo@example.com';
?>

<main class="rental-with-hero">
    <section class="rules-hero" aria-labelledby="rental-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rental-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['private_events_title'] ?? '') ?></h1>
        </div>
    </section>

    <section class="rental-section private-events-section">
        <p class="rental-subtitle"><?= htmlspecialchars($lang['private_events_subtitle'] ?? $translations['private_events_subtitle'] ?? 'Perfect venue for your special occasions and corporate events') ?></p>

            <div class="private-events-inquiry">
                <h3 class="section-title">
                    <i class="fas fa-envelope-open-text"></i>
                    <?= htmlspecialchars($lang['private_events_form_title'] ?? 'Event inquiry') ?>
                </h3>
                <p class="private-events-inquiry__notice">
                    <?= htmlspecialchars($lang['private_events_pricing_notice'] ?? 'For large events, we discuss the prices for hosting weddings, anniversaries, and sports games individually, taking into account the duration, location, and other details of the event.') ?>
                </p>
                <form id="peForm" class="private-events-form" action="<?= $base ?>Rentals/submit_private_event.php" method="post" novalidate>
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($peCsrf) ?>">

                    <div class="private-events-form__row">
                        <div class="private-events-form__field">
                            <label for="pe_name"><?= htmlspecialchars($lang['form_name'] ?? 'Name') ?></label>
                            <input type="text" id="pe_name" name="name" autocomplete="name" required
                                   placeholder="<?= htmlspecialchars($lang['form_name'] ?? 'Name') ?>"
                                   value="<?= htmlspecialchars($prefillName) ?>">
                        </div>
                        <div class="private-events-form__field">
                            <label for="pe_email"><?= htmlspecialchars($lang['form_email'] ?? 'Email') ?></label>
                            <input type="email" id="pe_email" name="email" autocomplete="email" required
                                   placeholder="<?= htmlspecialchars($lang['form_email'] ?? 'Email') ?>"
                                   value="<?= htmlspecialchars($prefillEmail) ?>">
                        </div>
                    </div>
                    <div class="private-events-form__field">
                        <label for="pe_phone"><?= htmlspecialchars($lang['form_phone'] ?? 'Phone') ?></label>
                        <input type="tel" id="pe_phone" name="phone" autocomplete="tel" placeholder="<?= htmlspecialchars($lang['form_phone'] ?? 'Phone') ?>">
                    </div>
                    <div class="private-events-form__field">
                        <label for="pe_event_type"><?= htmlspecialchars($lang['private_events_form_event_type'] ?? 'Type of event') ?></label>
                        <select id="pe_event_type" name="event_type">
                            <option value=""><?= htmlspecialchars($lang['private_events_form_event_type_placeholder'] ?? 'Select…') ?></option>
                            <option value="wedding"><?= htmlspecialchars($lang['private_events_form_event_wedding'] ?? 'Wedding') ?></option>
                            <option value="anniversary"><?= htmlspecialchars($lang['private_events_form_event_anniversary'] ?? 'Anniversary') ?></option>
                            <option value="sports"><?= htmlspecialchars($lang['private_events_form_event_sports'] ?? 'Sports / games') ?></option>
                            <option value="corporate"><?= htmlspecialchars($lang['private_events_form_event_corporate'] ?? 'Corporate / other') ?></option>
                        </select>
                    </div>
                    <div class="private-events-form__field private-events-form__field--guests">
                        <label for="pe_guests"><?= htmlspecialchars($lang['private_events_form_guest_count'] ?? 'Expected number of guests') ?></label>
                        <input type="number" id="pe_guests" name="guest_count" min="1" step="1" inputmode="numeric" placeholder="<?= htmlspecialchars($lang['private_events_form_guest_count_ph'] ?? 'e.g. 50') ?>">
                    </div>
                    <div class="private-events-form__row">
                        <div class="private-events-form__field">
                            <label for="pe_date"><?= htmlspecialchars($lang['private_events_form_preferred_date'] ?? 'Preferred date') ?></label>
                            <input type="date" id="pe_date" name="preferred_date">
                            <small class="private-events-form__date-help">
                                <?= htmlspecialchars($lang['private_events_form_date_note'] ?? 'Greyed-out days are already taken.') ?>
                            </small>
                        </div>
                        <div class="private-events-form__field">
                            <label for="pe_duration"><?= htmlspecialchars($lang['private_events_form_duration'] ?? 'Approx. duration') ?></label>
                            <input type="text" id="pe_duration" name="duration" placeholder="<?= htmlspecialchars($lang['private_events_form_duration_ph'] ?? 'e.g. full day, evening') ?>">
                        </div>
                    </div>
                    <div class="private-events-form__field">
                        <label for="pe_location"><?= htmlspecialchars($lang['private_events_form_location_notes'] ?? 'Location / area on site') ?></label>
                        <textarea id="pe_location" name="location_notes" rows="2" placeholder="<?= htmlspecialchars($lang['private_events_form_location_ph'] ?? 'Which part of the venue, or special layout needs') ?>"></textarea>
                    </div>

                    <?php if (!empty($peExtras)) : ?>
                        <fieldset class="private-events-form__field private-events-form__extras">
                            <legend><?= htmlspecialchars($lang['private_events_form_extras'] ?? 'Optional extras') ?></legend>
                            <p class="private-events-form__extras-help">
                                <?= htmlspecialchars($lang['private_events_form_extras_help'] ?? 'Tick anything you would like us to arrange.') ?>
                            </p>
                            <div class="private-events-form__extras-grid">
                                <?php
                                $isLv = ($langCode ?? 'lv') === 'lv';
                                foreach ($peExtras as $ex) :
                                    $label = $isLv ? $ex['label_lv'] : $ex['label_en'];
                                    $cid   = 'pe_extra_' . (int) $ex['id'];
                                ?>
                                    <label class="private-events-form__extra" for="<?= htmlspecialchars($cid) ?>">
                                        <input type="checkbox" id="<?= htmlspecialchars($cid) ?>" name="extras[]" value="<?= (int) $ex['id'] ?>">
                                        <span><?= htmlspecialchars($label) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endif; ?>

                    <div class="private-events-form__field">
                        <label for="pe_details"><?= htmlspecialchars($lang['private_events_form_event_details'] ?? 'Event details') ?></label>
                        <textarea id="pe_details" name="event_details" rows="4" placeholder="<?= htmlspecialchars($lang['private_events_form_event_details_ph'] ?? 'Schedule, equipment, catering, accessibility, etc.') ?>"></textarea>
                    </div>

                    <div id="peFormStatus" class="private-events-form__status" role="status" aria-live="polite" hidden></div>

                    <button type="submit" class="private-events-form__submit"><?= htmlspecialchars($lang['private_events_form_submit'] ?? 'Send inquiry') ?></button>
                </form>
            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Inline so it lives next to the form; mirrors site palette. */
.private-events-form__extras { border: 1px solid #d6d3c6; border-radius: 12px; padding: 14px 16px; background: #fbfaf2; }
.private-events-form__extras legend { padding: 0 6px; font-weight: 600; color: #2c4a29; font-size: 1rem; }
.private-events-form__extras-help { margin: 0 0 10px; color: #6c757d; font-size: .9rem; }
.private-events-form__extras-grid { display: grid; gap: 8px 16px; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
.private-events-form__extra { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 6px 8px; border-radius: 8px; transition: background .15s; }
.private-events-form__extra:hover { background: rgba(59, 99, 55, 0.08); }
.private-events-form__extra input { width: 18px; height: 18px; accent-color: #3b6337; cursor: pointer; }
.private-events-form__extra span { color: #2c3e50; font-size: .95rem; }
.private-events-form__status { margin: 14px 0 0; padding: 10px 14px; border-radius: 8px; font-size: .92rem; }
.private-events-form__status--ok    { background: #e3f1de; color: #245c2a; border: 1px solid #c5e0c2; }
.private-events-form__status--err   { background: #fdecea; color: #b3261e; border: 1px solid #f3c4c0; }
.private-events-form__date-help { display: block; margin-top: 4px; color: #6c757d; font-size: .82rem; }
</style>

<!-- flatpickr: date picker that supports disabled dates -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/lv.js"></script>
<script>
(function () {
    if (typeof flatpickr === 'undefined') return;
    const dateInput = document.getElementById('pe_date');
    if (!dateInput) return;

    const langCode = (document.documentElement.getAttribute('data-lang') || 'lv').toLowerCase();
    const locale   = (langCode === 'lv' && flatpickr.l10ns && flatpickr.l10ns.lv) ? flatpickr.l10ns.lv : null;
    const today    = new Date(); today.setHours(0,0,0,0);

    fetch('<?= $base ?>Contact/getReservedDates.php')
        .then(r => r.json())
        .then(blocked => {
            flatpickr(dateInput, {
                locale: locale || 'default',
                minDate: today,
                dateFormat: 'Y-m-d',
                disable: Array.isArray(blocked) ? blocked : [],
            });
        })
        .catch(err => console.warn('[private events] could not load reserved dates:', err));
})();
</script>

<script>
(function () {
    const form = document.getElementById('peForm');
    if (!form) return;

    const status = document.getElementById('peFormStatus');
    const submit = form.querySelector('.private-events-form__submit');
    const submitOriginal = submit ? submit.textContent : '';

    function showStatus(kind, message) {
        if (!status) return;
        status.hidden = false;
        status.className = 'private-events-form__status private-events-form__status--' + kind;
        status.textContent = message;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!form.reportValidity()) return;

        if (submit) { submit.disabled = true; submit.textContent = '…'; }
        showStatus('ok', '<?= htmlspecialchars($lang['private_events_form_sending'] ?? 'Sending…', ENT_QUOTES) ?>');

        try {
            const fd  = new FormData(form);
            const res = await fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin' });
            const data = await res.json().catch(() => ({ ok: false, error: 'Unexpected server response.' }));

            if (res.ok && data.ok) {
                showStatus('ok',
                    '<?= htmlspecialchars($lang['private_events_form_success'] ?? "Thanks! We received your inquiry and will get back to you shortly.", ENT_QUOTES) ?>'
                );
                form.reset();
            } else {
                showStatus('err', data.error || '<?= htmlspecialchars($lang['private_events_form_error'] ?? "Sorry, the inquiry could not be sent. Please try again.", ENT_QUOTES) ?>');
            }
        } catch (err) {
            showStatus('err', '<?= htmlspecialchars($lang['private_events_form_error'] ?? "Sorry, the inquiry could not be sent. Please try again.", ENT_QUOTES) ?>');
        } finally {
            if (submit) { submit.disabled = false; submit.textContent = submitOriginal; }
        }
    });
})();
</script>

<?php include "../footer.php"; ?>
