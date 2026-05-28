<?php
include "../lang.php";
include "../head.php";
require_once __DIR__ . "/../includes/db_safe.php";
require_once __DIR__ . "/../includes/gallery_repository.php";

/**
 * Gallery data source:
 *   1) Try MySQL (gallery_categories + gallery_images).
 *   2) If the DB is unreachable OR returns nothing, fall back to the
 *      original hard-coded list so the page never appears empty.
 *
 * Category slugs (must match keys in translations/<lang>.json as
 * `gallery_cat_<slug>`): daba, pasakumi, aktivitates, teritorija, uzlabojumi.
 */
$galleryDb       = db_safe_connect();
$galleryItems    = gallery_fetch_active_items($galleryDb);
$categories      = gallery_fetch_active_categories($galleryDb);

if (empty($galleryItems)) {
    // Fallback list (identical to the legacy hard-coded gallery).
    $galleryItems = [
        ['file' => 'Forest.jpg',       'cat' => 'daba'],
        ['file' => 'mezs.jpg',         'cat' => 'daba'],
        ['file' => 'mezs1.jpg',        'cat' => 'daba'],
        ['file' => 'mezs2.jpg',        'cat' => 'daba'],
        ['file' => 'R2020.jpg',        'cat' => 'teritorija'],
        ['file' => 'R2020 (84).JPG',   'cat' => 'teritorija'],
        ['file' => 'JunO (432).jpg',   'cat' => 'teritorija'],
        ['file' => 'JunO (434).jpg',   'cat' => 'teritorija'],
        ['file' => 'miniFutbols.jpg',  'cat' => 'aktivitates'],
        ['file' => 'basketbols.jpeg',  'cat' => 'aktivitates'],
        ['file' => 'golfs.jpg',        'cat' => 'aktivitates'],
        ['file' => 'klinsuSiena.jpg',  'cat' => 'aktivitates'],
        ['file' => 'pasakums.png',     'cat' => 'pasakumi'],
        ['file' => 'pasakums2.jpg',    'cat' => 'pasakumi'],
        ['file' => 'pasakums3.jpg',    'cat' => 'pasakumi'],
    ];
}

if (empty($categories)) {
    $categories = ['daba', 'pasakumi', 'aktivitates', 'teritorija'];
}

/** One grid card per category; all photos in that category open in the modal carousel */
$galleryByCategory = [];
foreach ($galleryItems as $item) {
    $c = $item['cat'];
    if (!isset($galleryByCategory[$c])) {
        $galleryByCategory[$c] = [];
    }
    $galleryByCategory[$c][] = $item;
}
?>

<main class="gallery-page">
    <div class="container">

        <section class="rules-hero" aria-labelledby="gallery-hero-title">
            <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
            <div class="rules-hero-overlay">
                <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
                <h1 id="gallery-hero-title" class="rules-hero-title">
                    <?= htmlspecialchars($lang['gallery_title'] ?? 'Gallery') ?>
                </h1>
            </div>
        </section>

        <section class="gallery-section" aria-labelledby="gallery-heading">
            <h2 id="gallery-heading" class="visually-hidden"><?= htmlspecialchars($lang['gallery_title'] ?? 'Gallery') ?></h2>

            <?php if (!empty($lang['gallery_intro'])) : ?>
                <p class="gallery-intro"><?= htmlspecialchars($lang['gallery_intro']) ?></p>
            <?php endif; ?>

            <div class="gallery-filters" role="group" aria-label="<?= htmlspecialchars($lang['gallery_filter_label'] ?? 'Filter by category') ?>">
                <button type="button" class="gallery-filter is-active" data-filter="all">
                    <?= htmlspecialchars($lang['gallery_filter_all'] ?? 'All') ?>
                </button>
                <?php foreach ($categories as $catKey) : ?>
                    <button type="button" class="gallery-filter" data-filter="<?= htmlspecialchars($catKey) ?>">
                        <?= htmlspecialchars($lang['gallery_cat_' . $catKey] ?? $catKey) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="gallery-grid" id="galleryGrid">
                <?php
                $gridIdx = 0;
                foreach ($categories as $catKey) :
                    if (empty($galleryByCategory[$catKey])) {
                        continue;
                    }
                    $catItems = $galleryByCategory[$catKey];
                    $cover = $catItems[0];
                    $catLabel = $lang['gallery_cat_' . $catKey] ?? $catKey;
                    $coverUrl = $base . 'images/' . rawurlencode($cover['file']);
                    $slides = [];
                    foreach ($catItems as $it) {
                        $slides[] = [
                            'src' => $base . 'images/' . rawurlencode($it['file']),
                            'alt' => isset($it['alt']) ? (string) $it['alt'] : '',
                            'categoryLabel' => $catLabel,
                            'category' => $catKey,
                        ];
                    }
                    $slidesJson = json_encode($slides, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                    $countLabel = count($slides);
                    ?>
                    <div class="gallery-item gallery-item--category" data-category="<?= htmlspecialchars($catKey) ?>">
                        <button type="button" class="gallery-item__trigger"
                                data-category="<?= htmlspecialchars($catKey) ?>"
                                data-gallery-slides="<?= htmlspecialchars($slidesJson, ENT_QUOTES, 'UTF-8') ?>"
                                aria-label="<?= htmlspecialchars(($lang['gallery_open_category'] ?? 'Open') . ' — ' . $catLabel . ' (' . $countLabel . ')') ?>">
                            <img src="<?= htmlspecialchars($coverUrl) ?>"
                                 alt="<?= htmlspecialchars($cover['alt'] ?? '') ?>"
                                 loading="<?= $gridIdx < 6 ? 'eager' : 'lazy' ?>"
                                 width="400"
                                 height="260">
                            <span class="gallery-item-label"><?= htmlspecialchars($catLabel) ?></span>
                            <?php if ($countLabel > 1) : ?>
                                <span class="gallery-item-count" aria-hidden="true"><?= (int) $countLabel ?></span>
                            <?php endif; ?>
                        </button>
                    </div>
                <?php
                    $gridIdx++;
                endforeach;
                ?>
            </div>

            <div class="gallery-blocks">
                <div class="gallery-block">
                    <div class="gallery-part gallery-text">
                        <h2><?= htmlspecialchars($lang['gallery_forest_title'] ?? 'Forest & Nature') ?></h2>
                        <p><?= htmlspecialchars($lang['gallery_forest_desc'] ?? '') ?></p>
                    </div>
                    <div class="gallery-part gallery-image">
                        <img src="<?= htmlspecialchars($base); ?>images/Forest.jpg" alt="">
                    </div>
                </div>

                <div class="gallery-block">
                    <div class="gallery-part gallery-text">
                        <h2><?= htmlspecialchars($lang['gallery_camping_title'] ?? 'Camping Houses') ?></h2>
                        <p><?= htmlspecialchars($lang['gallery_camping_desc'] ?? '') ?></p>
                    </div>
                    <div class="gallery-part gallery-image">
                        <img src="<?= htmlspecialchars($base); ?>images/Namins.jpg" alt="">
                    </div>
                </div>

                <div class="gallery-block">
                    <div class="gallery-part gallery-text">
                        <h2><?= htmlspecialchars($lang['gallery_hottub_title'] ?? 'Hot Tub & Sauna') ?></h2>
                        <p><?= htmlspecialchars($lang['gallery_hottub_desc'] ?? '') ?></p>
                    </div>
                    <div class="gallery-part gallery-image">
                        <img src="<?= htmlspecialchars($base); ?>images/Sauna.jpg" alt="">
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content gallery-modal-content">
            <div class="modal-header">
                <p class="modal-title gallery-modal-category" id="galleryModalTitle"></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= htmlspecialchars($lang['gallery_modal_close'] ?? 'Close') ?>"></button>
            </div>
            <div class="modal-body gallery-modal-body">
                <div class="gallery-carousel-container">
                    <div class="gallery-carousel-stage">
                        <div id="galleryCarousel" class="carousel slide gallery-carousel" data-bs-ride="false" data-bs-interval="false">
                            <div class="carousel-inner" id="galleryCarouselInner"></div>
                            <button class="carousel-control-prev gallery-carousel-control" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev"
                                    aria-label="<?= htmlspecialchars($lang['gallery_modal_prev'] ?? 'Previous image') ?>">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next gallery-carousel-control" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next"
                                    aria-label="<?= htmlspecialchars($lang['gallery_modal_next'] ?? 'Next image') ?>">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="gallery-carousel-thumbs-wrap">
                            <div class="gallery-carousel-thumbs" id="galleryCarouselThumbs" role="tablist"
                                 aria-label="<?= htmlspecialchars($lang['gallery_thumbs_label'] ?? 'Gallery thumbnails') ?>"></div>
                        </div>
                    </div>
                    <p class="gallery-modal-caption" id="galleryModalCaption"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $base; ?>js/gallery.js" defer></script>
<?php include "../footer.php"; ?>
