<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<main class="rental-details-section rental-with-hero">
    <section class="rules-hero" aria-labelledby="rental-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rental-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['hot_tub_rental_title'] ?? '') ?></h1>
        </div>
    </section>

    <section class="rental-section original-design">
        <div class="rental-content">
            <div class="rental-text">
                <h3><?= $translations['hot_tub_info_title'] ?></h3>
                <ul>
                    <li><?= $translations['hot_tub_li1'] ?></li>
                    <li><?= $translations['hot_tub_li2'] ?></li>
                    <li><?= $translations['hot_tub_li3'] ?></li>
                    <li><?= $translations['hot_tub_li4'] ?></li>
                </ul>
                <p><strong><?= $translations['hot_tub_available'] ?></strong></p>
            </div>
            <div class="rental-image">
                <img src="../images/Kubls.jpg" alt="Kubla noma">
            </div>
        </div>

        <div class="rental-prices">
            <h3><?= $translations['hot_tub_prices_title'] ?></h3>
            <table>
                <tr>
                    <th><?= $translations['hot_tub_prices_duration'] ?></th>
                    <th><?= $translations['hot_tub_prices_price'] ?></th>
                </tr>
                <tr>
                    <td><?= $translations['hot_tub_prices_row1_col1'] ?></td>
                    <td><?= $translations['hot_tub_prices_row1_col2'] ?></td>
                </tr>
                <tr>
                    <td><?= $translations['hot_tub_prices_row2_col1'] ?></td>
                    <td><?= $translations['hot_tub_prices_row2_col2'] ?></td>
                </tr>
                <tr>
                    <td><?= $translations['hot_tub_prices_row3_col1'] ?></td>
                    <td><?= $translations['hot_tub_prices_row3_col2'] ?></td>
                </tr>
            </table>
            <p class="price-note"><?= $translations['hot_tub_price_note'] ?></p>
        </div>

        <div class="rental-gallery">
            <h3><?= $translations['gallery_title'] ?></h3>
            <div class="gallery-row">
                <img src="../images/Kubls.jpg" class="zoomable-image" alt="Kubla foto 1">
                <img src="../images/Kubls1.png" class="zoomable-image" alt="Kubla foto 1">
                <img src="../images/Kubls2.jpg" class="zoomable-image" alt="Kubla foto 1">
                <img src="../images/Kubls3.jpg" class="zoomable-image" alt="Kubla foto 1">
            </div>
        </div>
    </section>
</main>
<script src="../js/zoom.js" defer></script>

<?php include '../footer.php'; ?>
