<?php include "../lang.php"; ?>
<?php include "../head.php"; ?>

<?php
$rulesActive = 'terms';
$langQ = 'lang=' . urlencode($langCode);
?>

<main class="rules-page">
    <section class="rules-hero" aria-labelledby="rules-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="rules-hero-title" class="rules-hero-title">
                <?= htmlspecialchars($lang['rules_hero_terms'] ?? 'CAMPING ROPAZKALNS TERMS AND CONDITIONS') ?>
            </h1>
        </div>
    </section>

    <nav class="rules-subnav rules-subnav--split" aria-label="<?= htmlspecialchars($lang['rules_subnav_aria'] ?? 'Legal documents') ?>">
        <a href="<?= $base; ?>About/PersonalData.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn<?= $rulesActive === 'personal' ? ' is-active' : '' ?>">
            <?= htmlspecialchars($lang['rules_nav_personal'] ?? 'Rules for the processing of personal data') ?>
        </a>
        <a href="<?= $base; ?>About/TermsAndConditions.php?<?= htmlspecialchars($langQ) ?>"
           class="rules-subnav-btn<?= $rulesActive === 'terms' ? ' is-active' : '' ?>"<?= $rulesActive === 'terms' ? ' aria-current="page"' : '' ?>>
            <?= htmlspecialchars($lang['rules_nav_terms'] ?? 'Camping Ropažkalns terms and conditions') ?>
        </a>
    </nav>

    <div class="container">
        <div class="rules-body">
            <article class="rules-content-box">
                <p class="rules-lead"><?= htmlspecialchars($lang['rules_terms_intro'] ?? '') ?></p>

                <h2><?= htmlspecialchars($lang['rules_terms_h2_1'] ?? '') ?></h2>
                <p><?= htmlspecialchars($lang['rules_terms_p_1'] ?? '') ?></p>

                <h2><?= htmlspecialchars($lang['rules_terms_h2_2'] ?? '') ?></h2>
                <p><?= htmlspecialchars($lang['rules_terms_p_2'] ?? '') ?></p>

                <h2><?= htmlspecialchars($lang['rules_terms_h2_3'] ?? '') ?></h2>
                <p><?= htmlspecialchars($lang['rules_terms_p_3'] ?? '') ?></p>

                <h2><?= htmlspecialchars($lang['rules_terms_h2_4'] ?? '') ?></h2>
                <p><?= htmlspecialchars($lang['rules_terms_p_4'] ?? '') ?></p>

                <h2><?= htmlspecialchars($lang['rules_terms_h2_5'] ?? '') ?></h2>
                <p><?= htmlspecialchars($lang['rules_terms_p_5'] ?? '') ?></p>
            </article>
        </div>
    </div>
</main>

<?php include "../footer.php"; ?>
