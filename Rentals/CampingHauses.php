<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<main class="rental-details-section rental-with-hero">
    <section class="rules-hero" aria-labelledby="rental-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rental-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['camping_houses_title'] ?? 'Kempinga namiņi') ?></h1>
        </div>
    </section>

    <section class="rental-section original-design">
        <div class="rental-content">
            <div class="rental-text">
                <p><?= $translations['camping_houses_paragraph'] ?? 'Mājīgi koka namiņi mierīgai atpūtai pāriem, ģimenēm vai individuāliem ceļotājiem dabas tuvumā.'; ?></p>
                <ul>
                    <li><?= $translations['camping_houses_li1'] ?? 'Galdi un krēsli'; ?></li>
                    <li><?= $translations['camping_houses_li2'] ?? 'Ledusskapis'; ?></li>
                    <li><?= $translations['camping_houses_li3'] ?? 'Gultas veļa (divstāvu gultas)'; ?></li>
                </ul>
                <p><?= $translations['camping_houses_note1'] ?? 'Virtuve, tualete un dušas atrodas tuvējā sanitārajā mezglā.'; ?></p>
                <p><?= $translations['camping_houses_note2'] ?? 'Ierašanās no 15:00, izbraukšana līdz 12:00.'; ?></p>
            </div>
            <div class="rental-image">
                <img src="../images/Namins.jpg" alt="Kempings">
            </div>
        </div>

        <div class="rental-prices">
            <h3><?= $translations['rental_prices_title'] ?? 'Cenas'; ?></h3>
            <table>
                <tr>
                    <th><?= $translations['rental_prices_duration'] ?? 'Ilgums'; ?></th>
                    <th>2 <?= $translations['rental_prices_people'] ?? 'personas'; ?></th>
                    <th>3 <?= $translations['rental_prices_people'] ?? 'personas'; ?></th>
                    <th>4 <?= $translations['rental_prices_people'] ?? 'personas'; ?></th>
                </tr>
                <tr>
                    <td>1 <?= $translations['rental_prices_night'] ?? 'nakts'; ?></td>
                    <td>60 EUR</td>
                    <td>85 EUR</td>
                    <td>100 EUR</td>
                </tr>
                <tr>
                    <td>2+ <?= $translations['rental_prices_nights'] ?? 'naktis'; ?></td>
                    <td>50 EUR</td>
                    <td>70 EUR</td>
                    <td>80 EUR</td>
                </tr>
            </table>
            <p class="price-note"><strong><?= $translations['camping_houses_note3'] ?? 'Bērniem līdz 2 gadu vecumam: bez maksas'; ?></strong></p>
            <p class="price-note"><strong><?= $translations['camping_houses_note4'] ?? 'Katra desmitā nakts: bez maksas'; ?></strong></p>
        </div>

        <div class="rental-gallery">
            <h3><?= $translations['gallery_title'] ?? 'Galerija'; ?></h3>
            <div class="gallery-row">
                <img src="../images/Namins.jpg" class="zoomable-image" alt="Kempinga namiņš foto">
                <img src="../images/NaminaIekspuse.jpeg" class="zoomable-image" alt="Kempinga namiņš foto">
                <img src="../images/NaminaIekspuse1.jpeg" class="zoomable-image" alt="Kempinga namiņš foto">
            </div>
        </div>
    </section>
</main>
<script src="../js/zoom.js" defer></script>

<?php include '../footer.php'; ?>
