<?php
include "../lang.php";

$errors = [];
$values = [
    'username'  => '',
    'email'     => '',
    'full_name' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['username']  = trim((string) ($_POST['username']  ?? ''));
    $values['email']     = trim((string) ($_POST['email']     ?? ''));
    $values['full_name'] = trim((string) ($_POST['full_name'] ?? '')) ?: null;
    $password            = trim((string) ($_POST['password']         ?? ''));
    $confirm             = trim((string) ($_POST['confirm_password'] ?? ''));

    if (!preg_match('/^[A-Za-z0-9._-]{3,64}$/', $values['username'])) {
        $errors[] = 'Username must be 3–64 chars (letters, digits, . _ -).';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $errors[] = 'Design preview mode: registration backend is disabled in this public copy.';
    }
}

include "../head.php";
?>

<main class="auth-page rental-with-hero">
    <section class="rules-hero" aria-labelledby="auth-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="auth-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['register_title'] ?? $lang['register_button'] ?? 'Register') ?></h1>
        </div>
    </section>

    <div class="auth-page__wrap">
        <div class="auth-card auth-card--register">
            <?php if (!empty($errors)): ?>
                <div class="auth-form__error" style="background:#fdecea; color:#b3261e; border:1px solid #f3c4c0; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:.92rem;">
                    <?php foreach ($errors as $err): ?>
                        <div><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" id="registerForm" method="POST" autocomplete="on">
                <div class="auth-form__field">
                    <label class="visually-hidden" for="reg_username"><?= htmlspecialchars($lang['username'] ?? 'Username') ?></label>
                    <i class="fas fa-user auth-form__icon" aria-hidden="true"></i>
                    <input type="text" class="auth-form__input" id="reg_username" name="username"
                           placeholder="<?= htmlspecialchars($lang['username'] ?? 'Username') ?>"
                           autocomplete="username" required minlength="3" maxlength="64"
                           value="<?= htmlspecialchars($values['username']) ?>">
                </div>
                <div class="auth-form__field">
                    <label class="visually-hidden" for="reg_email"><?= htmlspecialchars($lang['email'] ?? 'Email') ?></label>
                    <i class="fas fa-envelope auth-form__icon" aria-hidden="true"></i>
                    <input type="email" class="auth-form__input" id="reg_email" name="email"
                           placeholder="<?= htmlspecialchars($lang['email'] ?? 'Email') ?>"
                           autocomplete="email" required maxlength="128"
                           value="<?= htmlspecialchars($values['email']) ?>">
                </div>
                <div class="auth-form__field">
                    <label class="visually-hidden" for="reg_password"><?= htmlspecialchars($lang['password'] ?? 'Password') ?></label>
                    <i class="fas fa-lock auth-form__icon" aria-hidden="true"></i>
                    <input type="password" class="auth-form__input" id="reg_password" name="password"
                           placeholder="<?= htmlspecialchars($lang['password'] ?? 'Password') ?>"
                           autocomplete="new-password" required minlength="8">
                </div>
                <div class="auth-form__field">
                    <label class="visually-hidden" for="reg_confirm"><?= htmlspecialchars($lang['confirm_password'] ?? 'Confirm Password') ?></label>
                    <i class="fas fa-lock auth-form__icon" aria-hidden="true"></i>
                    <input type="password" class="auth-form__input" id="reg_confirm" name="confirm_password"
                           placeholder="<?= htmlspecialchars($lang['confirm_password'] ?? 'Confirm Password') ?>"
                           autocomplete="new-password" required minlength="8">
                </div>
                <button type="submit" class="auth-form__submit">
                    <span><?= htmlspecialchars($lang['register_button'] ?? 'Register') ?></span>
                    <i class="fas fa-chevron-right auth-form__submit-icon" aria-hidden="true"></i>
                </button>
                <p class="auth-form__footer">
                    <a href="../login/login.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['have_account'] ?? 'Already have an account? Login') ?></a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php include "../footer.php"; ?>
