<?php
// 1. Include language file first so translations are available for error messages
include "../lang.php";

// 2. The Secure Backend Processing Logic
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$success = '';
$error = '';

// Check if form is submitted using the specific fields from your new HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_name'], $_POST['contact_email'], $_POST['contact_question'])) {

    // Load Environment Variables Securely
    try {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    } catch (\Exception $e) {
        error_log("Dotenv failed to load: " . $e->getMessage());
        die('System configuration error.');
    }

    // Strict Input Validation mapping to your new form inputs
    $name = htmlspecialchars(trim($_POST['contact_name']));
    $email = filter_var(trim($_POST['contact_email']), FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['contact_phone'] ?? ''));
    $question = htmlspecialchars(trim($_POST['contact_question']));

    // If validation fails
    if (!$email || empty($name) || empty($question)) {
        $error = $lang['form_error_validation'] ?? 'Please provide a valid name, email address, and message.';
    } else {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($_ENV['SMTP_TO_EMAIL']);
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission from ' . $name;
            // Added phone number to the body structure
            $mail->Body    = 'Name: ' . $name . '<br>Email: ' . $email . '<br>Phone: ' . $phone . '<br><br>Message:<br>' . nl2br($question);

            $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$question";

            $mail->send();
            $success = $lang['form_success'] ?? 'Email sent successfully.';

        } catch (Exception $e) {
            error_log("PHPMailer Transmission Error: {$mail->ErrorInfo}");
            $error = $lang['form_error'] ?? 'Message could not be sent due to a server error. Please try again later.';
        }
    }
}
?>

<?php include "../head.php"; ?>

    <main class="contact-page">
        <div class="container">

            <section class="rules-hero" aria-labelledby="contact-hero-title">
                <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
                <div class="rules-hero-overlay">
                    <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
                    <h1 id="contact-hero-title" class="rules-hero-title">
                        <?= htmlspecialchars($lang['contact_title'] ?? 'Contacts') ?>
                    </h1>
                </div>
            </section>

            <section class="contact-info-box">
                <p class="contact-info-line"><?= $lang['contact_reservation_info'] ?? 'Ropažkalna kempinga rezervācijas informācija' ?></p>
                <p class="contact-info-line"><?= $lang['contact_phone_email'] ?? '+371 20 000 000 , epasts@inbox.lv' ?></p>
            </section>

            <section class="contact-main">
                <div class="contact-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=24.655%2C56.905%2C24.678%2C56.920&layer=mapnik&marker=56.9126%2C24.6652" width="100%" height="100%" style="border:none;" title="Ropažkalns location map" loading="lazy"></iframe>
                    <a class="contact-map-link" href="https://www.google.com/maps/place/Kalna+Paltes,+Ropaži,+Ropažu+pagasts,+LV-2135/@56.9126176,24.6625837,17z" target="_blank" rel="noopener"><?= $lang['location_directions'] ?? 'View on Google Maps' ?></a>
                </div>
                <div class="contact-form-box">
                    <h3 class="contact-form-title"><?= $lang['contact_questions_title'] ?? 'Questions' ?></h3>

                    <?php if (!empty($success)): ?>
                        <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px; border-radius: 4px;">
                            <?= $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px; border-radius: 4px;">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>

                    <form class="contact-form" action="" method="POST">
                        <div class="contact-form-row">
                            <div class="contact-form-field">
                                <label for="contact_name"><?= $lang['form_name'] ?? 'Name' ?></label>
                                <input type="text" id="contact_name" name="contact_name" required placeholder="<?= $lang['form_name'] ?? 'Name' ?>">
                            </div>
                            <div class="contact-form-field">
                                <label for="contact_email"><?= $lang['form_email'] ?? 'Email' ?></label>
                                <input type="email" id="contact_email" name="contact_email" required placeholder="<?= $lang['form_email'] ?? 'Email' ?>">
                            </div>
                        </div>
                        <div class="contact-form-field">
                            <label for="contact_phone"><?= $lang['form_phone'] ?? 'Phone' ?></label>
                            <input type="tel" id="contact_phone" name="contact_phone" placeholder="<?= $lang['form_phone'] ?? 'Phone' ?>">
                        </div>
                        <div class="contact-form-field">
                            <label for="contact_question"><?= $lang['form_question'] ?? 'Question / Comments' ?></label>
                            <textarea id="contact_question" name="contact_question" rows="4" required placeholder="<?= $lang['form_question'] ?? 'Question / Comments' ?>"></textarea>
                        </div>
                        <button type="submit" class="contact-form-submit"><?= $lang['form_submit'] ?? 'Send' ?></button>
                    </form>
                </div>
            </section>

        </div>
    </main>

<?php include "../footer.php"; ?>