<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<?php
$aroundImages = [
    'Forest.jpg',
    'kempingaNamins.jpeg',
    'kubls.jpeg',
    'buggy.jpg',
    'motorbike.jpg',
    'Waterskies.jpg',
];
$aroundAlts = [
    'Forest and trails',
    'Camping area',
    'Hot tub',
    'Buggy',
    'Motorbike',
    'Water skis',
];
$langQ = 'lang=' . urlencode($langCode);
?>

<main class="activities-around-page">
    <section class="rules-hero" aria-labelledby="activities-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="activities-hero-title" class="rules-hero-title">
                <?= htmlspecialchars($lang['activities_section_around'] ?? 'Aktivitātes apkārt') ?>
            </h1>
        </div>
    </section>

    <nav class="rules-subnav rules-subnav--split" aria-label="<?= htmlspecialchars($lang['activities_subnav_aria_label'] ?? 'Aktivitātes: apkārt un Ropažkalnā') ?>">
        <a href="<?= $base; ?>Activities/Around.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn is-active" aria-current="page">
            <?= htmlspecialchars($lang['activities_tab_around'] ?? 'Apkārt') ?>
        </a>
        <a href="<?= $base; ?>Activities/AtRopazkalns.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn">
            <?= htmlspecialchars($lang['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā') ?>
        </a>
    </nav>

    <div class="container activities-around-inner">
        <section class="activities-cards-wrap">
            <div class="activities-places-grid">
                <?php for ($i = 1; $i <= 6; $i++) : ?>
                    <?php
                    $title = $lang['activities_around_place_' . $i . '_title'] ?? '';
                    $desc = $lang['activities_around_place_' . $i . '_desc'] ?? '';
                    $img = $aroundImages[$i - 1] ?? 'Forest.jpg';
                    $alt = $aroundAlts[$i - 1] ?? '';
                    ?>
                    <a href="<?= $base; ?>Activities/Detail.php?from=around&amp;item=<?= $i ?>&amp;lang=<?= urlencode($langCode) ?>" class="activities-place-card">
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
