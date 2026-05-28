<?php

include '../lang.php';
include '../head.php';

$slugOptions = [
    'tent'   => $translations['reservation_item_tent'] ?? 'Tent space',
    'house'  => $translations['reservation_item_house'] ?? 'Cabin (house)',
    'rest'   => $translations['reservation_item_rest'] ?? 'Territory',
    'hottub' => $translations['reservation_item_hottub'] ?? 'Hot tub'
];

// Public showcase mode: prefill with demo values.
$prefillName  = '';
$prefillEmail = '';
$prefillName  = 'Demo Guest';
$prefillEmail = 'demo@example.com';
?>

<main class="rental-with-hero">
    <section class="rules-hero" aria-labelledby="booking-page-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="booking-page-hero-title" class="rules-hero-title"><?= htmlspecialchars($translations['booking_page'] ?? 'Booking page') ?></h1>
        </div>
    </section>

    <section class="rental-section booking-section">
        <p class="rental-subtitle"><?= htmlspecialchars($translations['reservation_subtitle'] ?? '') ?></p>

        <div class="rental-content">
            <form id="rentalForm" class="reservation-form">

                <!-- Items Table -->
                <table id="itemsTable" class="items-table">
                    <thead>
                    <tr>
                        <th><?= $translations['reservation_item'] ?? 'Item' ?></th>
                        <th><?= $translations['reservation_quantity'] ?? 'Qty' ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" id="addRow">+ <?= $translations['reservation_add_item'] ?? 'Add item' ?></button>

                <!-- Dates -->
                <div class="form-row">
                    <label><?= $translations['reservation_from'] ?? 'From:' ?>
                        <input type="date" name="from_date" id="bp_from" required>
                    </label>
                    <label><?= $translations['reservation_to'] ?? 'To:' ?>
                        <input type="date" name="to_date" id="bp_to" required>
                    </label>
                </div>
                <p id="bp_dates_note" class="adm-help" style="margin-top:-6px; font-size:.85rem; color:#6c757d;">
                    <?= htmlspecialchars($translations['reservation_dates_note'] ?? 'Greyed-out days are already taken.') ?>
                </p>

                <!-- People -->
                <div class="form-row">
                    <label><?= $translations['reservation_people'] ?? 'People:' ?>
                        <input type="number" name="people_count" min="1" value="1" required>
                    </label>
                </div>

                <!-- Name & Email -->
                <div class="form-row">
                    <label><?= $translations['reservation_name'] ?? 'Your Name:' ?>
                        <input type="text" name="name" required value="<?= htmlspecialchars($prefillName) ?>">
                    </label>
                    <label><?= $translations['reservation_email'] ?? 'Your Email:' ?>
                        <input type="email" name="email" required value="<?= htmlspecialchars($prefillEmail) ?>">
                    </label>
                </div>

                <!-- Notes -->
                <label><?= $translations['reservation_notes'] ?? 'Notes:' ?>
                    <textarea name="notes" rows="2"></textarea>
                </label>

                <!-- Submit -->
                <button type="submit" id="calcBtn"><?= $translations['reservation_calc_price'] ?? 'Get Price' ?></button>
            </form>

            <div id="result" class="price-result"></div>
        </div>
    </section>
</main>

<script>
    const itemLabels = <?php echo json_encode($slugOptions, JSON_UNESCAPED_UNICODE); ?>;

    function buildRow() {
        const tr = document.createElement('tr');

        const sel = document.createElement('select');
        sel.name = 'rental_type[]';
        sel.required = true;
        sel.innerHTML =
            '<option value="">-- choose --</option>' +
            Object.entries(itemLabels)
                .map(([slug, label]) => `<option value="${slug}">${label}</option>`)
                .join('');
        tr.insertCell().appendChild(sel);

        const qty = document.createElement('input');
        qty.type = 'number';
        qty.name = 'quantity[]';
        qty.min = 1;
        qty.value = 1;
        tr.insertCell().appendChild(qty);

        const del = document.createElement('button');
        del.type = 'button';
        del.textContent = '×';
        del.title = 'Remove item';
        del.onclick = () => tr.remove();
        tr.insertCell().appendChild(del);

        return tr;
    }

    document.getElementById('addRow').addEventListener('click', () => {
        document.querySelector('#itemsTable tbody').appendChild(buildRow());
    });
    document.getElementById('addRow').click();

    const form = document.getElementById('rentalForm');
    const resultDiv = document.getElementById('result');

    form.addEventListener('submit', e => {
        e.preventDefault();
        resultDiv.textContent = '<?= $translations['reservation_calculating'] ?? 'Calculating…' ?>';
        resultDiv.classList.add('show'); // Show the price result box

        const fd = new FormData(form);
        fetch('calculate_price.php', { method: 'POST', body: fd })
            .then(r => r.text())
            .then(html => {
                resultDiv.innerHTML = html;
                addConfirmButton();
            })
            .catch(err => resultDiv.textContent = err);
    });

    function addConfirmButton() {
        if (document.getElementById('confirmBtn')) return;

        const btn = document.createElement('button');
        btn.id = 'confirmBtn';
        btn.type = 'button';
        btn.style.marginTop = '0.5rem';
        btn.textContent = '<?= $translations['reservation_confirm'] ?? 'Confirm & Save' ?>';

        btn.addEventListener('click', () => {
            const fd = new FormData(form);
            fd.append('confirm', '1');

            resultDiv.textContent = '<?= $translations['reservation_saving'] ?? 'Saving…' ?>';
            fetch('calculate_price.php', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(html => resultDiv.innerHTML = html)
                .catch(err => resultDiv.textContent = err);
        });

        resultDiv.appendChild(btn);
    }
</script>

<!-- flatpickr: date picker that supports disabled dates -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/lv.js"></script>
<script>
(function () {
    if (typeof flatpickr === 'undefined') return;
    const fromInput = document.getElementById('bp_from');
    const toInput   = document.getElementById('bp_to');
    if (!fromInput || !toInput) return;

    const langCode = (document.documentElement.getAttribute('data-lang') || 'lv').toLowerCase();
    const locale   = (langCode === 'lv' && flatpickr.l10ns && flatpickr.l10ns.lv) ? flatpickr.l10ns.lv : null;

    const today = new Date(); today.setHours(0,0,0,0);

    fetch('../Contact/getReservedDates.php')
        .then(r => r.json())
        .then(blocked => {
            const disabled = Array.isArray(blocked) ? blocked : [];

            const fpFrom = flatpickr(fromInput, {
                locale: locale || 'default',
                minDate: today,
                dateFormat: 'Y-m-d',
                disable: disabled,
                onChange: (selected) => {
                    if (selected[0]) fpTo.set('minDate', selected[0]);
                }
            });
            const fpTo = flatpickr(toInput, {
                locale: locale || 'default',
                minDate: today,
                dateFormat: 'Y-m-d',
                disable: disabled,
            });
        })
        .catch(err => console.warn('[bookingpage] could not load reserved dates:', err));
})();
</script>

<?php include '../footer.php'; ?>