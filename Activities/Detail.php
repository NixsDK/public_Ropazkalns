<?php
include "../lang.php";

$base = '../';
$from = isset($_GET['from']) ? strtolower((string) $_GET['from']) : '';
$item = isset($_GET['item']) ? (int) $_GET['item'] : 0;

if (!in_array($from, ['around', 'at'], true) || $item < 1 || $item > 6) {
    $redirLang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'lv');
    header('Location: ' . $base . 'Activities/Around.php?lang=' . urlencode($redirLang));
    exit;
}

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

if ($from === 'around') {
    $heroImage = $aroundImages[$item - 1] ?? 'Forest.jpg';
    $heroAlt = $aroundAlts[$item - 1] ?? '';
    $titleKey = 'activities_around_place_' . $item . '_title';
    $introKey = 'activities_around_detail_' . $item . '_intro';
    $bulletsKey = 'activities_around_detail_' . $item . '_bullets';
} else {
    $heroImage = $atImages[$item - 1] ?? ($atImages[0] ?? 'ugunskurs.png');
    $heroAlt = $atAlts[$item - 1] ?? '';
    $titleKey = 'activities_at_place_' . $item . '_title';
    $introKey = 'activities_at_detail_' . $item . '_intro';
    $bulletsKey = 'activities_at_detail_' . $item . '_bullets';
}

include "../head.php";

$pageTitle = $lang[$titleKey] ?? '';
$pageIntro = $lang[$introKey] ?? '';
$bullets = $lang[$bulletsKey] ?? [];
if (!is_array($bullets)) {
    $bullets = [];
}

$listUrl = $base . ($from === 'around' ? 'Activities/Around.php' : 'Activities/AtRopazkalns.php') . '?lang=' . urlencode($langCode);
$langQ = 'lang=' . urlencode($langCode);
$activitiesHeroKey = $from === 'around' ? 'activities_section_around' : 'activities_section_ropazkalns';
$activitiesHeroFallback = $from === 'around' ? 'Aktivitātes apkārt' : 'Aktivitātes Ropažkalnā';
?>

<main class="activities-around-page activities-detail-page">
    <section class="rules-hero" aria-labelledby="activities-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="activities-hero-title" class="rules-hero-title">
                <?= htmlspecialchars($lang[$activitiesHeroKey] ?? $activitiesHeroFallback) ?>
            </h1>
        </div>
    </section>

    <nav class="rules-subnav rules-subnav--split" aria-label="<?= htmlspecialchars($lang['activities_subnav_aria_label'] ?? 'Aktivitātes: apkārt un Ropažkalnā') ?>">
        <a href="<?= $base; ?>Activities/Around.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn<?= $from === 'around' ? ' is-active' : '' ?>"<?= $from === 'around' ? ' aria-current="page"' : '' ?>>
            <?= htmlspecialchars($lang['activities_tab_around'] ?? 'Apkārt') ?>
        </a>
        <a href="<?= $base; ?>Activities/AtRopazkalns.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn<?= $from === 'at' ? ' is-active' : '' ?>"<?= $from === 'at' ? ' aria-current="page"' : '' ?>>
            <?= htmlspecialchars($lang['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā') ?>
        </a>
    </nav>

    <div class="container activities-around-inner">
        <p class="activity-detail-back">
            <a href="<?= htmlspecialchars($listUrl) ?>" class="activity-detail-back-link">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <?= htmlspecialchars($lang['activities_detail_back'] ?? 'Atpakaļ uz sarakstu') ?>
            </a>
        </p>

        <article class="activity-detail-panel">
            <div class="activity-detail-split">
                <div class="activity-detail-media">
                    <img src="<?= $base; ?>images/<?= htmlspecialchars($heroImage) ?>" alt="<?= htmlspecialchars($heroAlt) ?>">
                    <div class="activity-detail-media-badge" aria-hidden="true">
                        <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="">
                    </div>
                </div>
                <div class="activity-detail-body">
                    <div class="activity-detail-body-watermark" aria-hidden="true"></div>
                    <h2 class="activity-detail-heading"><?= htmlspecialchars($pageTitle) ?></h2>
                    <?php if ($pageIntro !== '') : ?>
                        <p class="activity-detail-intro"><?= htmlspecialchars($pageIntro) ?></p>
                    <?php endif; ?>
                    <?php if (count($bullets) > 0) : ?>
                        <ul class="activity-detail-list">
                            <?php foreach ($bullets as $line) : ?>
                                <li><?= htmlspecialchars((string) $line) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </div>
</main>

<?php include '../footer.php'; ?>
