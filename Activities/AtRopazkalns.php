<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<?php
$atImages = [
    'ugunskurs.png',
    'pargajiens.jpeg',
    'takas.png',
    'kubls.jpeg',
    'buggy.jpg',
    'motorbike.jpg',
];
$atAlts = [
    'Tent area',
    'Hiking trail',
    'takas',
    'Hot tub',
    'Buggy',
    'Motorbike',
];
$langQ = 'lang=' . urlencode($langCode);
?>

<main class="activities-around-page">
    <section class="rules-hero" aria-labelledby="activities-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="activities-hero-title" class="rules-hero-title">
                <?= htmlspecialchars($lang['activities_section_ropazkalns'] ?? 'Aktivitātes') ?>
            </h1>
        </div>
    </section>

    <nav class="rules-subnav rules-subnav--split" aria-label="<?= htmlspecialchars($lang['activities_subnav_aria_label'] ?? 'Aktivitātes: apkārt un Ropažkalnā') ?>">
        <a href="<?= $base; ?>Activities/Around.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn">
            <?= htmlspecialchars($lang['activities_tab_around'] ?? 'Apkārt') ?>
        </a>
        <a href="<?= $base; ?>Activities/AtRopazkalns.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn is-active" aria-current="page">
            <?= htmlspecialchars($lang['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā') ?>
        </a>
    </nav>

    <div class="container activities-around-inner">
        <section class="activities-cards-wrap">
            <div class="activities-places-grid">
                <?php for ($i = 1; $i <= 6; $i++) : ?>
                    <?php
                    $title = $lang['activities_at_place_' . $i . '_title'] ?? '';
                    $desc = $lang['activities_at_place_' . $i . '_desc'] ?? '';
                    $img = $atImages[$i - 1] ?? ($atImages[0] ?? 'ugunskurs.png');
                    $alt = $atAlts[$i - 1] ?? '';
                    ?>
                    <a href="<?= $base; ?>Activities/Detail.php?from=at&amp;item=<?= $i ?>&amp;lang=<?= urlencode($langCode) ?>" class="activities-place-card">
                        <div class="activities-place-card-media">
                            <img src="<?= $base; ?>images/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($alt) ?>">
                        </div>
                        <div class="activities-place-card-body">
                            <h2 class="activities-place-card-title"><?= htmlspecialchars($title) ?></h2>
                            <p class="activities-place-card-desc"><?= htmlspecialchars($desc) ?></p>
                        </div>
                    </a>
                <?php endfor; ?>
            </div>
        </section>
    </div>
</main>

<?php include '../footer.php'; ?>
