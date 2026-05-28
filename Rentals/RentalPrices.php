<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<main class="rental-with-hero">
    <section class="rules-hero" aria-labelledby="rental-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rental-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['rental_prices_title'] ?? $texts['rental_prices_title'] ?? 'Īres cenas') ?></h1>
        </div>
    </section>

<section class="rental-section">
    <div class="price-category">
        <h3><i class="fa-solid fa-campground" style="color: #3b6337;"></i> <?php echo $texts['rental_prices_table_title_cabins'] ?? 'Kempinga Namiņi'; ?></h3>
        <table class="price-table">
            <tr><th><?php echo $texts['rental_prices_table_cabins_1night'] ?? '1 nakts'; ?></th><td>55 EUR</td></tr>
            <tr><th><?php echo $texts['rental_prices_table_cabins_2nights'] ?? '2 naktis'; ?></th><td>100 EUR</td></tr>
            <tr><th><?php echo $texts['rental_prices_table_cabins_extra'] ?? 'Papildu persona'; ?></th><td>+8 EUR</td></tr>
        </table>
    </div>

    <div class="price-category">
        <h3><i class="fa-solid fa-tent" style="color: #3b6337;"></i> <?php echo $texts['rental_prices_table_title_tents'] ?? 'Telšu Vietas'; ?></h3>
        <table class="price-table">
            <tr><th><?php echo $texts['rental_prices_table_tents_adult'] ?? '1 persona / nakts'; ?></th><td>12 EUR</td></tr>
            <tr><th><?php echo $texts['rental_prices_table_tents_kids_4_10'] ?? 'Bērni (4-10 gadi)'; ?></th><td>6 EUR</td></tr>
            <tr><th><?php echo $texts['rental_prices_table_tents_kids_under_4'] ?? 'Bērni līdz 4 gadiem'; ?></th><td><strong><?php echo $texts['free'] ?? 'Bez maksas'; ?></strong></td></tr>
        </table>
    </div>

    <div class="price-category">
        <h3><i class="fa-solid fa-tree" style="color: #3b6337;"></i> <?php echo $texts['rental_prices_table_title_activities'] ?? 'Aktivitātes Dabā'; ?></h3>
        <table class="price-table">
            <tr><th><?php echo $texts['rental_prices_table_activities_picnic'] ?? 'Pikniks (1 persona / stunda)'; ?></th><td>1 EUR</td></tr>
            <tr><th><?php echo $texts['rental_prices_table_activities_kids'] ?? 'Bērni līdz 10 gadiem'; ?></th><td>0.50 EUR</td></tr>
        </table>
    </div>

    <p style="margin-top: 30px; color: #555;"><?php echo $texts['rental_prices_included'] ?? 'Visās cenās iekļauts: autostāvvieta, pieeja sanitārajiem mezgliem, grils, ugunskura vieta, koplietošanas virtuve.'; ?></p>
</section>
</main>

<?php include '../footer.php'; ?>
