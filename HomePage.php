<?php include "lang.php"; ?>
<?php include "head.php"; ?>


    <main>
        <section class="hero-container">
            <img src="images/Ropazkalns2.JPG" alt="Ropažkalns Countryside" class="hero-image">
            <div class="hero-overlay">
                <h2><?php echo $texts['homepage_intro_1'] ?? 'Laipni lūdzam Ropažkalnā'; ?></h2>
                <p><?php echo $texts['homepage_intro_2'] ?? ''; ?></p>
                <div class="hero-buttons">
                    <a href="Rentals/RentForPrivateEvents.php?lang=<?= $langCode ?>" class="btn btn-rent"><?php echo $texts['homepage_button_events'] ?? 'Privātie pasākumi'; ?></a>
                    <a href="Rentals/RentalPrices.php?lang=<?= $langCode ?>" class="btn btn-rent"><?php echo $texts['homepage_button_prices'] ?? 'Īres cenas'; ?></a>
                </div>
            </div>
        </section>

        <section class="intro-section">
            <div class="info-box">
                <p><?php echo $texts['homepage_intro_paragraph_1'] ?? ''; ?></p>
                <p><?php echo $texts['homepage_intro_paragraph_2'] ?? ''; ?></p>
            </div>

            <div class="highlight-note">
                <p><?php echo $texts['homepage_note_quiet_policy'] ?? ''; ?></p>
            </div>

            <div class="info-box">
                <p><?php echo $texts['homepage_opening_season'] ?? ''; ?></p>
                <p><?php echo $texts['homepage_pet_policy'] ?? ''; ?></p>
            </div>

            <section class="kempings-section">
                <h2>
                    <i class="fa-solid fa-house" style="color: #508c39; margin-right: 8px;"></i>
                    <?= $texts['camping_section_title'] ?? 'Kempings' ?>
                </h2>
            </section>

            <div class="rental-cards">
                <div class="rental-card" onclick="location.href='Rentals/CampingHauses.php?lang=<?= $langCode ?>'">
                    <img src="images/Namins.jpg" alt="Kempinga namiņi">
                    <div class="card-label"><?= $texts['camping_houses_title'] ?? 'Kempinga namiņi'; ?></div>
                </div>

                <div class="rental-card" onclick="location.href='Rentals/TentPlace.php?lang=<?= $langCode ?>'">
                    <img src="images/telts.jpg" alt="Telšu vietas">
                    <div class="card-label"><?= $texts['tent_place_title'] ?? 'Telšu vietas'; ?></div>
                </div>

                <div class="rental-card" onclick="location.href='Activities/AtRopazkalns.php?lang=<?= $langCode ?>'">
                    <img src="images/takas.png" alt="<?= htmlspecialchars($texts['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā') ?>">
                    <div class="card-label"><?= $texts['activities_tab_at_ropazkalns'] ?? 'Ropažkalnā'; ?></div>
                </div>
            </div>

            <section class="kempings-section noma-spacing">
                <h2>
                    <i class="fa-solid fa-hot-tub-person" style="color: #508c32; margin-right: 8px;"></i>
                    <?= $texts['rental_section_title'] ?? 'Noma' ?>
                </h2>
            </section>

            <div class="rental-cards rent-section">
                <div class="rental-card" onclick="location.href='Rentals/RentSauna.php?lang=<?= $langCode ?>'">
                    <img src="images/Sauna.jpg" alt="Pirts noma">
                    <div class="card-label"><?= $texts['sauna_rental_title'] ?? 'Pirts noma'; ?></div>
                </div>

                <div class="rental-card" onclick="location.href='Rentals/RentHotTub.php?lang=<?= $langCode ?>'">
                    <img src="images/Kubls.jpg" alt="Kubls noma">
                    <div class="card-label"><?= $texts['hot_tub_rental_title'] ?? 'Kubls noma'; ?></div>
                </div>
            </div>
        </section>

        <section class="home-questions-section" aria-labelledby="home-questions-heading">
            <div class="container">
                <div class="contact-form-box">
                    <h3 class="contact-form-title" id="home-questions-heading"><?= htmlspecialchars($texts['contact_questions_title'] ?? 'Jautājumi') ?></h3>
                    <form class="contact-form" action="#" method="POST" onsubmit="return false;">
                        <div class="contact-form-row">
                            <div class="contact-form-field">
                                <label for="home_contact_name"><?= htmlspecialchars($texts['form_name'] ?? 'Vārds') ?></label>
                                <input type="text" id="home_contact_name" name="contact_name" required placeholder="<?= htmlspecialchars($texts['form_name'] ?? 'Vārds') ?>">
                            </div>
                            <div class="contact-form-field">
                                <label for="home_contact_email"><?= htmlspecialchars($texts['form_email'] ?? 'E-pasts') ?></label>
                                <input type="email" id="home_contact_email" name="contact_email" required placeholder="<?= htmlspecialchars($texts['form_email'] ?? 'E-pasts') ?>">
                            </div>
                        </div>
                        <div class="contact-form-field">
                            <label for="home_contact_phone"><?= htmlspecialchars($texts['form_phone'] ?? 'Tālrunis') ?></label>
                            <input type="tel" id="home_contact_phone" name="contact_phone" placeholder="<?= htmlspecialchars($texts['form_phone'] ?? 'Tālrunis') ?>">
                        </div>
                        <div class="contact-form-field">
                            <label for="home_contact_question"><?= htmlspecialchars($texts['form_question'] ?? 'Jautājums') ?></label>
                            <textarea id="home_contact_question" name="contact_question" rows="4" required placeholder="<?= htmlspecialchars($texts['form_question'] ?? 'Jautājums') ?>"></textarea>
                        </div>
                        <button type="submit" class="contact-form-submit"><?= htmlspecialchars($texts['form_submit'] ?? 'Nosūtīt') ?></button>
                    </form>
                </div>
            </div>
        </section>
    </main>

<?php include "footer.php"; ?>