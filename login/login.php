<?php
include "../lang.php";
require_once __DIR__ . '/../includes/user_auth.php';

$next = (string) ($_POST['next'] ?? $_GET['next'] ?? '');
$demoMessage = null;

if (user_is_logged_in()) {
    header('Location: ../UserProfile/profile.php?lang=' . urlencode($langCode));
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    user_csrf_check();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (($username === 'demo_user' || $username === 'demo@example.com') && $password === 'demo123') {
        session_regenerate_id(true);
        $_SESSION['site_user_id'] = 1;
        user_flash('ok', 'Welcome to the demo account.');
        if ($next !== '') {
            if ($next[0] === '/') {
                header('Location: ' . $next);
            } else {
                header('Location: ../' . $next);
            }
        } else {
            header('Location: ../UserProfile/profile.php?lang=' . urlencode($langCode));
        }
        exit;
    }
    $demoMessage = 'Invalid demo credentials. Use demo_user / demo123.';
}

include "../head.php";
?>

<main class="auth-page rental-with-hero">
    <section class="rules-hero" aria-labelledby="auth-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="auth-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['login_title'] ?? $lang['login_button'] ?? 'Login') ?></h1>
        </div>
    </section>

    <div class="auth-page__wrap">
        <div class="auth-card">
            <?php if ($demoMessage): ?>
                <div class="auth-form__error" style="background:#fdecea; color:#b3261e; border:1px solid #f3c4c0; border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:.92rem;">
                    <?= htmlspecialchars($demoMessage) ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" id="loginForm" method="POST" autocomplete="on" action="">
                <?= user_csrf_field() ?>
                <?php if ($next !== ''): ?>
                    <input type="hidden" name="next" value="<?= htmlspecialchars($next, ENT_QUOTES) ?>">
                <?php endif; ?>
                <div class="auth-form__field">
                    <label class="visually-hidden" for="login_username"><?= htmlspecialchars($lang['username'] ?? 'Username') ?></label>
                    <i class="fas fa-user auth-form__icon" aria-hidden="true"></i>
                    <input type="text" class="auth-form__input" id="login_username" name="username"
                           placeholder="<?= htmlspecialchars($lang['username_or_email'] ?? 'Username or email') ?>"
                           autocomplete="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="auth-form__field">
                    <label class="visually-hidden" for="login_password"><?= htmlspecialchars($lang['password'] ?? 'Password') ?></label>
                    <i class="fas fa-lock auth-form__icon" aria-hidden="true"></i>
                    <input type="password" class="auth-form__input" id="login_password" name="password"
                           placeholder="<?= htmlspecialchars($lang['password'] ?? 'Password') ?>"
                           autocomplete="current-password" required>
                </div>
                <button type="submit" class="auth-form__submit">
                    <span><?= htmlspecialchars($lang['login_button'] ?? 'Login') ?></span>
                    <i class="fas fa-chevron-right auth-form__submit-icon" aria-hidden="true"></i>
                </button>
                <p class="auth-form__footer">
                    <a href="../register/register.php?lang=<?= urlencode($langCode) ?>"><?= htmlspecialchars($lang['no_account'] ?? "Don't have an account? Register") ?></a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php include "../footer.php"; ?>
