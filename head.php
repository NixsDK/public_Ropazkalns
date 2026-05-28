<?php

require_once __DIR__ . '/lang.php';
require_once __DIR__ . '/includes/db_safe.php';
require_once __DIR__ . '/includes/user_auth.php';

$currentPage = basename($_SERVER['SCRIPT_NAME']);
$currentDir = dirname($_SERVER['SCRIPT_NAME']);
$dir = basename($currentDir);
$base = ($dir !== '' && $dir !== '.' && $dir !== basename(__DIR__)) ? '../' : '';

// Snapshot the current logged-in user once per request so the navbar
// can render the right buttons (login/register vs profile/logout).
$navUser = user_is_logged_in() ? user_current() : null;
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($langCode) ?>" data-public-auth="<?= $navUser ? 'in' : 'out' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ropazkalns - Outdoor Activities and Accommodation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= $base; ?>css/base.css" />
    <link rel="stylesheet" href="<?= $base; ?>css/layout.css" />
    <link rel="stylesheet" href="<?= $base; ?>css/components.css" />
    <link rel="stylesheet" href="<?= $base; ?>css/styles.css" />
    <?php if ($currentPage === 'Gallery.php' || $currentPage === 'Around.php' || $currentPage === 'AtRopazkalns.php' || $currentPage === 'Detail.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/gallery.css" />
    <?php } ?>
    <?php if ($currentPage === 'Around.php' || $currentPage === 'AtRopazkalns.php' || $currentPage === 'Detail.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/ActivitiesAround.css" />
    <?php } ?>
    <?php if ($currentPage === 'Contacts.php' || $currentPage === 'HomePage.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/contacts.css" />
    <?php } ?>
    <?php if ($currentPage === 'Gallery.php' || $currentPage === 'Contacts.php' || $currentPage === 'PersonalData.php' || $currentPage === 'TermsAndConditions.php' || $currentPage === 'Around.php' || $currentPage === 'AtRopazkalns.php' || $currentPage === 'Detail.php' || $dir === 'Rentals' || $dir === 'Contact' || $currentPage === 'login.php' || $currentPage === 'register.php' || $currentPage === 'profile.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/Rules.css" />
    <?php } ?>
    <?php if ($currentPage === 'login.php' || $currentPage === 'register.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/pages/login.css" />
    <?php } ?>
    <?php if ($currentPage === 'RentForPrivateEvents.php' || $currentPage === 'profile.php') { ?>
    <link rel="stylesheet" href="<?= $base; ?>css/pages/private-events.css" />
    <?php } ?>
    <link rel="stylesheet" href="<?= $base; ?>css/userProfile.css" />
    

</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container-fluid">

        <div class="d-flex align-items-center">
            <a href="<?= $base; ?>HomePage.php" class="navbar-brand me-3">
                <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="Ropažkalns Logo" width="120" />
            </a>
            <a class="nav-link d-none d-lg-inline" href="https://www.google.com/maps/place/Kalna+Paltes,+Ropa%C5%BEi,+Ropa%C5%BEu+pagasts,
            +Ropa%C5%BEu+novads,+LV-2135/@56.9126176,24.6625837,17z/data=!3m1!4b1!4m6!3m5!1s0x46e930af6443ea89:
            0xe26ba5f290ca8e65!8m2!3d56.9126176!4d24.665164!16s%2Fg%2F11mtml1dgp?entry=ttu&g_ep=EgoyMDI1MDUyOC4wIKXMDSoASAFQAw%3D%3D"
               target="_blank">
                <i class="fas fa-map-marker-alt" style="color: #3b6337;"></i> <?= $lang['location_directions'] ?? 'Braukšanas norādes' ?>
            </a>
        </div>

        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav align-items-center">
               
                <li class="nav-item"><a class="nav-link" href="<?= $base; ?>HomePage.php"><?= $lang['home'] ?? 'Sākumlapa' ?></a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $lang['about'] ?? 'Par' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <li><a class="dropdown-item" href="<?= $base; ?>About/Gallery.php"><?= $lang['gallery'] ?? 'Galerija' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>About/Contacts.php"><?= $lang['contact'] ?? 'Kontakti' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>About/PersonalData.php?lang=<?= urlencode($langCode) ?>"><?= $lang['rules_about_link_personal'] ?? 'Personal data' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>About/TermsAndConditions.php?lang=<?= urlencode($langCode) ?>"><?= $lang['rules_about_link_terms'] ?? 'Terms & conditions' ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $lang['activities_section_title'] ?? 'Aktivitātes' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="activitiesDropdown">
                        <li><a class="dropdown-item" href="<?= $base; ?>Activities/Around.php?lang=<?= urlencode($langCode) ?>"><?= $lang['activities_tab_around'] ?? 'Apkārt' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Activities/AtRopazkalns.php?lang=<?= urlencode($langCode) ?>"><?= $lang['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā' ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="rentalsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $lang['rent'] ?? 'Īre' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="rentalsDropdown">
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/RentForPrivateEvents.php"><?= $lang['private_events'] ?? 'Privātie pasākumi' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/CampingHauses.php"><?= $lang['camping_house'] ?? 'Kempinga namiņš' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/TentPlace.php"><?= $lang['tent_place'] ?? 'Telšu vieta' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/RentalPrices.php"><?= $lang['rental_prices'] ?? 'Īres cenas' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/RentSauna.php"><?= $lang['sauna'] ?? 'Pirts' ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Rentals/RentHotTub.php"><?= $lang['hot_tub'] ?? 'Kubls' ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="contactDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $lang['contact'] ?? 'Kontaktinformācija' ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="contactDropdown">
                        <li><a class="dropdown-item" href="<?= $base; ?>Contact/BookingInformation.php"><?= htmlspecialchars($lang['booking_calendar'] ?? 'Booking calendar') ?></a></li>
                        <li><a class="dropdown-item" href="<?= $base; ?>Contact/bookingpage.php"><?= $lang['booking_page'] ?? 'Rezervācijas lapa' ?></a></li>
                    </ul>
                </li>

                <!-- Action Buttons -->
                <?php if ($navUser): ?>
                    <!-- Logged in: pre-rendered dropdown so userMenu.js skips its own injection -->
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <?= htmlspecialchars($navUser['full_name'] ?: $navUser['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= $base; ?>UserProfile/profile.php?lang=<?= urlencode($langCode) ?>">
                                <i class="fas fa-user me-2"></i><?= htmlspecialchars($lang['my_profile'] ?? 'My Profile') ?>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $base; ?>login/logout.php?lang=<?= urlencode($langCode) ?>">
                                <i class="fas fa-sign-out-alt me-2"></i><?= htmlspecialchars($lang['logout'] ?? 'Log out') ?>
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-3">
                        <a href="<?= $base; ?>login/login.php?lang=<?= $langCode ?>" class="nav-action-btn nav-action-btn--ghost">
                            <?= htmlspecialchars($lang['login'] ?? 'Login') ?>
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="<?= $base; ?>register/register.php?lang=<?= $langCode ?>" class="nav-action-btn nav-action-btn--primary">
                            <?= htmlspecialchars($lang['register'] ?? 'Register') ?>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Language buttons -->
                <li class="nav-item ms-3 d-flex flex-column gap-2">
                    <a href="?lang=lv" class="lang-btn">LV</a>
                    <a href="?lang=en" class="lang-btn">ENG</a>
                </li>
            </ul>
        </div>

        <!-- Button for smaller screen navigatio nmenu -->
        <button class="navbar-toggler custom-toggler ms-auto d-flex"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar"
                aria-label="Toggle navigation"
                style="display: flex !important;">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </button>
    </div>
</nav>

<!-- Offcanvas: full site nav + info (matches beige / forest hero look) -->
<div class="offcanvas offcanvas-end offcanvas-site-nav" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-hero-strip">
        <div class="offcanvas-hero-strip__bg" role="presentation" aria-hidden="true"></div>
        <div class="offcanvas-hero-strip__row">
            <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="offcanvas-hero-logo" width="44" height="auto">
                <h2 class="offcanvas-hero-heading mb-0" id="offcanvasNavbarLabel">ROPAZKALNS</h2>
            </div>
            <button type="button" class="btn-close btn-close-white flex-shrink-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <div class="offcanvas-body">
        <nav class="offcanvas-nav-main" aria-label="Menu">
            <a class="offcanvas-nav-top" href="<?= $base; ?>HomePage.php"><?= htmlspecialchars($lang['home'] ?? 'Sākumlapa') ?></a>

            <div class="accordion accordion-flush offcanvas-nav-accordion" id="offcanvasNavAccordion">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offcanvasNavAbout" aria-expanded="false" aria-controls="offcanvasNavAbout">
                            <?= htmlspecialchars($lang['about'] ?? 'Par') ?>
                        </button>
                    </h3>
                    <div id="offcanvasNavAbout" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>About/Gallery.php"><?= htmlspecialchars($lang['gallery'] ?? 'Galerija') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>About/Contacts.php"><?= htmlspecialchars($lang['contact_title'] ?? $lang['contact'] ?? 'Kontakti') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>About/PersonalData.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['rules_about_link_personal'] ?? 'Personal data') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>About/TermsAndConditions.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['rules_about_link_terms'] ?? 'Terms & conditions') ?></a>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offcanvasNavActivities" aria-expanded="false" aria-controls="offcanvasNavActivities">
                            <?= htmlspecialchars($lang['activities_section_title'] ?? 'Aktivitātes') ?>
                        </button>
                    </h3>
                    <div id="offcanvasNavActivities" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Activities/Around.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['activities_tab_around'] ?? 'Apkārt') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Activities/AtRopazkalns.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā') ?></a>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offcanvasNavRentals" aria-expanded="false" aria-controls="offcanvasNavRentals">
                            <?= htmlspecialchars($lang['rent'] ?? 'Īre') ?>
                        </button>
                    </h3>
                    <div id="offcanvasNavRentals" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/RentForPrivateEvents.php"><?= htmlspecialchars($lang['private_events'] ?? 'Privātie pasākumi') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/CampingHauses.php"><?= htmlspecialchars($lang['camping_house'] ?? 'Kempinga namiņš') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/TentPlace.php"><?= htmlspecialchars($lang['tent_place'] ?? 'Telšu vieta') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/RentalPrices.php"><?= htmlspecialchars($lang['rental_prices'] ?? 'Īres cenas') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/RentSauna.php"><?= htmlspecialchars($lang['sauna'] ?? 'Pirts') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Rentals/RentHotTub.php"><?= htmlspecialchars($lang['hot_tub'] ?? 'Kubls') ?></a>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offcanvasNavBooking" aria-expanded="false" aria-controls="offcanvasNavBooking">
                            <?= htmlspecialchars($lang['footer_contact_heading'] ?? 'Kontaktinformācija') ?>
                        </button>
                    </h3>
                    <div id="offcanvasNavBooking" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Contact/BookingInformation.php"><?= htmlspecialchars($lang['booking_calendar'] ?? 'Booking calendar') ?></a>
                            <a class="offcanvas-nav-sublink" href="<?= $base; ?>Contact/bookingpage.php"><?= htmlspecialchars($lang['booking_page'] ?? 'Rezervācijas lapa') ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offcanvas-nav-actions d-flex flex-wrap gap-2">
                <?php if ($navUser): ?>
                    <a href="<?= $base; ?>UserProfile/profile.php?lang=<?= urlencode($langCode) ?>" class="nav-action-btn nav-action-btn--primary">
                        <i class="fas fa-user me-1"></i> <?= htmlspecialchars($lang['my_profile'] ?? 'My Profile') ?>
                    </a>
                    <a href="<?= $base; ?>login/logout.php?lang=<?= urlencode($langCode) ?>" class="nav-action-btn nav-action-btn--ghost">
                        <i class="fas fa-sign-out-alt me-1"></i> <?= htmlspecialchars($lang['logout'] ?? 'Log out') ?>
                    </a>
                <?php else: ?>
                    <a href="<?= $base; ?>login/login.php?lang=<?= urlencode($langCode) ?>" class="nav-action-btn nav-action-btn--ghost">
                        <?= htmlspecialchars($lang['login'] ?? 'Login') ?>
                    </a>
                    <a href="<?= $base; ?>register/register.php?lang=<?= urlencode($langCode) ?>" class="nav-action-btn nav-action-btn--primary">
                        <?= htmlspecialchars($lang['register'] ?? 'Register') ?>
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <hr class="offcanvas-divider my-4">

        <div class="offcanvas-description mb-3">
            <p>
                <?= htmlspecialchars($lang['offcanvas_description'] ?? 'Ropazkalns ir pilna servisa dabas aktivitāšu un izklaides centrs. Mēs piedāvājam dažādas aktivitātes dabā, kempinga namiņus, telšu vietas un daudz ko citu. Atklājiet dabas skaistumu kopā ar mums!') ?>
            </p>
        </div>

        <div class="offcanvas-image mb-4">
            <img src="<?= $base; ?>images/Ropazkalns1.JPG" alt="Ropazkalns" class="img-fluid rounded offcanvas-thumb-img">
        </div>

        <div class="offcanvas-contact pt-3 border-top border-secondary">
            <h6 class="mb-3 fw-bold"><?= htmlspecialchars($lang['contact_us'] ?? 'Sazinieties ar mums') ?></h6>

            <div class="contact-item mb-3">
                <i class="fas fa-map-marker-alt me-2"></i>
                <a href="https://www.google.com/maps/place/Kalna+Paltes,+Ropa%C5%BEi,+Ropa%C5%BEu+pagasts,
                +Ropa%C5%BEu+novads,+LV-2135/@56.9126176,24.6625837,17z/data=!3m1!4b1!4m6!3m5!1s0x46e930af6443ea89:
                0xe26ba5f290ca8e65!8m2!3d56.9126176!4d24.665164!16s%2Fg%2F11mtml1dgp?entry=ttu&g_ep=EgoyMDI1MDUyOC4wIKXMDSoASAFQAw%3D%3D"
                   target="_blank" rel="noopener" class="text-decoration-none">
                    <?= htmlspecialchars($lang['address'] ?? 'Kalna Paltes, Ropaži, Ropažu pagasts, LV-2135') ?>
                </a>
            </div>

            <div class="contact-item mb-3">
                <i class="fas fa-envelope me-2"></i>
                <a href="mailto:<?= htmlspecialchars($lang['email'] ?? 'ropazkalns@inbox.lv') ?>" class="text-decoration-none">
                    <?= htmlspecialchars($lang['email'] ?? 'ropazkalns@inbox.lv') ?>
                </a>
            </div>

            <div class="contact-item mb-4">
                <i class="fas fa-phone me-2"></i>
                <a href="tel:<?= htmlspecialchars(str_replace(' ', '', $lang['phone'] ?? '+37129727594')) ?>" class="text-decoration-none">
                    <?= htmlspecialchars($lang['phone'] ?? '+371 29 727 594') ?>
                </a>
            </div>

            <div class="social-icons d-flex gap-3 flex-wrap">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary">
            <div class="d-flex gap-2 flex-wrap">
                <a href="?lang=lv" class="lang-btn">
                    <img src="<?= $base; ?>images/flag_lv.png" width="18" height="18" alt=""> LV
                </a>
                <a href="?lang=en" class="lang-btn">
                    <img src="<?= $base; ?>images/flag_gb.png" width="18" height="18" alt=""> ENG
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pass PHP variable to JavaScript -->
<script>
    document.documentElement.setAttribute('data-lang', '<?= $langCode ?>');
    document.documentElement.setAttribute('data-base', '<?= $base ?>');
</script>

<!-- Custom JavaScript -->
<script src="<?= $base; ?>js/dropdownHover.js"></script>
<script src="<?= $base; ?>js/userMenu.js"></script>
