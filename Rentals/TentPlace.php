<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>


<main class="rental-details-section rental-with-hero">
    <section class="rules-hero" aria-labelledby="rental-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rental-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['tent_place_title'] ?? 'Telšu vietas') ?></h1>
        </div>
    </section>

    <section class="rental-section original-design">
        <div class="rental-content">
            <div class="rental-text">
                <p><?= $translations['tent_place_paragraph'] ?? '' ?></p>
                <ul>
                    <li><?= $translations['tent_place_li1'] ?? '' ?></li>
                    <li><?= $translations['tent_place_li2'] ?? '' ?></li>
                    <li><?= $translations['tent_place_li3'] ?? '' ?></li>
                    <li><?= $translations['tent_place_li4'] ?? '' ?></li>
                    <li><?= $translations['tent_place_li5'] ?? '' ?></li>
                </ul>
            </div>
            <div class="rental-image">
                <img src="../images/teltis.jpeg" alt="Teltis">
            </div>
        </div>

        <div class="rental-prices">
            <h3><?= $translations['tent_place_price_title'] ?? 'Cenas' ?></h3>
            <table>
                <tr><th><?= $translations['tent_place_price1'] ?? '1 nakts' ?></th><th>1 <?= $translations['rental_prices_people'] ?? 'persona' ?></th><th>12 EUR</th></tr>
                <tr><td></td><td><?= $translations['tent_place_price2'] ?? 'Bērni no 4g.v. līdz 10g.v.' ?></td><td>6 EUR</td></tr>
                <tr><td></td><td><?= htmlspecialchars($translations['tent_place_price3'] ?? '') ?></td><td><strong><?= htmlspecialchars($translations['tent_place_free'] ?? '') ?></strong></td></tr>
            </table>
            <p class="price-note"><?= $translations['tent_place_note'] ?? '' ?></p>
        </div>

        <div class="rental-gallery">
            <h3><?= $translations['gallery_title'] ?? 'Galerija' ?></h3>
            <div class="gallery-row">
                <img src="../images/teltis_test.jpg" class="zoomable-image" alt="Teltis 1">
                <img src="../images/teltis1.jpeg" class="zoomable-image" alt="Teltis 2">
                <img src="../images/teltis2.jpeg" class="zoomable-image" alt="Teltis 3">
            </div>
        </div>
    </section>
</main>
<script src="../js/zoom.js" defer></script>

<?php include '../footer.php'; ?>
