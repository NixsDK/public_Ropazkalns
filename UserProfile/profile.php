<?php
include "../lang.php";
require_once __DIR__ . '/../includes/user_auth.php';

if (!user_is_logged_in()) {
    header('Location: ../login/login.php?lang=' . urlencode($langCode) . '&next=' . rawurlencode('UserProfile/profile.php?lang=' . $langCode));
    exit;
}

include "../head.php";

$user = user_current() ?: [
    'id' => 1,
    'username' => 'demo_user',
    'full_name' => 'Demo User',
    'email' => 'demo@example.com',
    'created_at' => '2026-01-15 12:00:00',
    'avatar_path' => '',
];
$myBookings = [
    ['id' => 101, 'start_date' => '2026-06-12', 'end_date' => '2026-06-14', 'people_count' => 3, 'total_price' => 235.00, 'created_at' => '2026-05-10 14:03:00'],
];
$myEvents = [
    ['id' => 77, 'event_type' => 'wedding', 'preferred_date' => '2026-07-05', 'status' => 'pending', 'created_at' => '2026-05-18 09:20:00'],
];
$flashes = user_flash_take();
if (empty($flashes)) {
    $flashes = [[
        'type' => 'ok',
        'message' => 'Design preview mode: profile data is mocked and not connected to a database.',
    ]];
}

// Status-pill colours for orders & event inquiries
function profile_pill(string $status): string {
    return match (strtolower($status)) {
        'accepted', 'delivered', 'confirmed' => 'background:#e3f1de;color:#245c2a;',
        'declined', 'canceled', 'cancelled' => 'background:#fdecea;color:#b3261e;',
        'pending', 'in_progress', 'in progress' => 'background:#fbf3d8;color:#8a6d1d;',
        default => 'background:#efefe8;color:#777a6e;',
    };
}

$avatarSrc = !empty($user['avatar_path'])
    ? $base . htmlspecialchars($user['avatar_path'])
    : $base . 'images/default-avatar.png';
?>

<style>
.profile-flash { padding:10px 14px; border-radius:8px; margin-bottom:14px; font-size:.92rem; border:1px solid; }
.profile-flash--ok    { background:#e3f1de; color:#245c2a; border-color:#c5e0c2; }
.profile-flash--danger{ background:#fdecea; color:#b3261e; border-color:#f3c4c0; }
.status-pill { display:inline-block; padding:2px 10px; border-radius:999px; font-size:.78rem; font-weight:600; }
.empty-row { color:#6c757d; font-style:italic; }
</style>

<main class="profile-page rental-with-hero" data-server-user="1">
    <section class="rules-hero" aria-labelledby="profile-hero-title">
        <div class="rules-hero-bg" role="presentation" aria-hidden="true"></div>
        <div class="rules-hero-overlay">
            <img src="<?= $base; ?>images/RopazkalnsLogo2resize.png" alt="" class="rules-hero-logo" width="120" height="auto">
            <h1 id="profile-hero-title" class="rules-hero-title"><?= htmlspecialchars($lang['my_profile'] ?? 'My Profile') ?></h1>
        </div>
    </section>

    <div class="main-container profile-page__content">
        <div class="container">
            <?php foreach ($flashes as $f): ?>
                <div class="profile-flash profile-flash--<?= htmlspecialchars($f['type']) ?>">
                    <?= htmlspecialchars($f['message']) ?>
                </div>
            <?php endforeach; ?>

            <div class="row">
                <!-- Left Sidebar -->
                <div class="col-lg-4">
                    <div class="widget-author">
                        <div class="author-card">
                            <div class="author-card-cover"></div>
                            <div class="author-card-profile">
                                <div class="author-card-avatar">
                                    <img src="<?= $avatarSrc ?>" alt="" id="profileAvatar">
                                </div>
                                <div class="author-card-details">
                                    <div class="author-card-name" id="profileName"><?= htmlspecialchars($user['full_name'] ?: $user['username']) ?></div>
                                    <div class="author-card-position" id="profileEmail"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </div>

                            <div class="author-card-info">
                                <p id="profileInfo">
                                    <?= htmlspecialchars($lang['profile_joined'] ?? 'Joined') ?>
                                    <span id="memberSince"><?= htmlspecialchars(date('M j, Y', strtotime((string) $user['created_at']))) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush profile-side-nav">
                        <a href="#" class="list-group-item active" data-panel="orders">
                            <i class="fas fa-shopping-bag"></i> <?= htmlspecialchars($lang['profile_orders'] ?? 'My bookings') ?>
                            <span class="badge"><?= count($myBookings) + count($myEvents) ?></span>
                        </a>
                        <a href="#" class="list-group-item" data-panel="profile">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($lang['profile_settings'] ?? 'Profile settings') ?>
                        </a>
                        <a href="../login/logout.php?lang=<?= urlencode($langCode) ?>" class="list-group-item" style="color:#b3261e;">
                            <i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($lang['logout'] ?? 'Log out') ?>
                        </a>
                    </div>
                </div>

                <!-- Right Content Area -->
                <div class="col-lg-8">
                    <!-- Bookings + private events -->
                    <section class="rental-section private-events-section content-panel active" id="orders-panel">
                        <div class="rental-header">
                            <h2><?= htmlspecialchars($lang['profile_my_bookings'] ?? 'My bookings') ?></h2>
                            <p class="rental-subtitle"><?= htmlspecialchars($lang['profile_my_bookings_sub'] ?? 'Everything you have booked through your account.') ?></p>
                        </div>
                        <div class="private-events-content">
                            <h3 style="margin-top:8px;"><i class="fas fa-calendar-check"></i> <?= htmlspecialchars($lang['profile_regular_bookings'] ?? 'Regular bookings') ?></h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= htmlspecialchars($lang['profile_col_dates'] ?? 'Dates') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_people'] ?? 'People') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_total'] ?? 'Total') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_placed'] ?? 'Placed') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($myBookings)): ?>
                                        <tr><td colspan="5" class="empty-row">
                                            <?= htmlspecialchars($lang['profile_no_bookings'] ?? 'No bookings yet. Make one from the booking page – it will show here.') ?>
                                        </td></tr>
                                    <?php else: foreach ($myBookings as $b): ?>
                                        <tr>
                                            <td><?= (int) ($b['id'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars((string) ($b['start_date'] ?? '')) ?> &rarr; <?= htmlspecialchars((string) ($b['end_date'] ?? '')) ?></td>
                                            <td><?= htmlspecialchars((string) ($b['people_count'] ?? '—')) ?></td>
                                            <td>
                                                <?= isset($b['total_price']) ? '€' . number_format((float) $b['total_price'], 2) : '—' ?>
                                            </td>
                                            <td><span class="adm-help" style="color:#6c757d;font-size:.85rem;"><?= htmlspecialchars((string) ($b['created_at'] ?? '')) ?></span></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>

                            <h3 style="margin-top:24px;"><i class="fas fa-champagne-glasses"></i> <?= htmlspecialchars($lang['profile_event_inquiries'] ?? 'Private event inquiries') ?></h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= htmlspecialchars($lang['profile_col_event'] ?? 'Event') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_date_pref'] ?? 'Preferred date') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_status'] ?? 'Status') ?></th>
                                        <th><?= htmlspecialchars($lang['profile_col_placed'] ?? 'Placed') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($myEvents)): ?>
                                        <tr><td colspan="5" class="empty-row">
                                            <?= htmlspecialchars($lang['profile_no_events'] ?? 'No event inquiries yet.') ?>
                                        </td></tr>
                                    <?php else: foreach ($myEvents as $r):
                                        $statusKey = strtolower((string) $r['status']);
                                        $statusLabel = match ($statusKey) {
                                            'pending'  => $lang['profile_status_pending']  ?? 'Awaiting decision',
                                            'accepted' => $lang['profile_status_accepted'] ?? 'Accepted',
                                            'declined' => $lang['profile_status_declined'] ?? 'Declined',
                                            default    => $r['status'],
                                        };
                                    ?>
                                        <tr>
                                            <td><?= (int) ($r['id'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars((string) ($r['event_type'] ?? '—')) ?></td>
                                            <td><?= htmlspecialchars((string) ($r['preferred_date'] ?? '—')) ?></td>
                                            <td><span class="status-pill" style="<?= profile_pill($statusKey) ?>"><?= htmlspecialchars($statusLabel) ?></span></td>
                                            <td><span style="color:#6c757d;font-size:.85rem;"><?= htmlspecialchars((string) ($r['created_at'] ?? '')) ?></span></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Profile settings -->
                    <section class="rental-section private-events-section content-panel" id="profile-panel">
                        <div class="rental-header">
                            <h2><?= htmlspecialchars($lang['profile_settings'] ?? 'Profile settings') ?></h2>
                            <p class="rental-subtitle"><?= htmlspecialchars($lang['profile_settings_sub'] ?? 'Manage your account information') ?></p>
                        </div>
                        <div class="private-events-content">
                            <!-- Avatar uploader (its own form, multipart) -->
                            <form class="profile-edit-form" action="upload_avatar.php" method="POST" enctype="multipart/form-data" style="margin-bottom:24px;">
                                <?= user_csrf_field() ?>
                                <div class="profile-edit-section profile-avatar-section">
                                    <h3 class="profile-edit-section-title"><?= htmlspecialchars($lang['profile_avatar_label'] ?? 'Profile picture') ?></h3>
                                    <div class="profile-avatar-uploader">
                                        <div class="profile-avatar-preview">
                                            <img src="<?= $avatarSrc ?>" alt="" id="avatarPreview">
                                        </div>
                                        <div class="profile-avatar-actions">
                                            <input type="file" id="avatarInput" name="avatar" accept="image/*">
                                            <button type="submit" class="btn-avatar-upload" style="margin-top:8px;">
                                                <i class="fas fa-upload"></i>
                                                <span><?= htmlspecialchars($lang['profile_save'] ?? 'Save changes') ?></span>
                                            </button>
                                            <p class="profile-avatar-hint"><?= htmlspecialchars($lang['profile_avatar_hint'] ?? 'JPG or PNG, up to 2 MB.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Real profile-info form -->
                            <form class="profile-edit-form" action="save_profile.php" method="POST" autocomplete="on">
                                <?= user_csrf_field() ?>
                                <div class="profile-edit-section">
                                    <h3 class="profile-edit-section-title"><?= htmlspecialchars($lang['profile_edit_info'] ?? 'Edit profile information') ?></h3>

                                    <div class="profile-form-grid">
                                        <div class="profile-form-field">
                                            <label for="editFullName"><?= htmlspecialchars($lang['profile_full_name'] ?? 'Full name') ?></label>
                                            <input type="text" id="editFullName" name="full_name" autocomplete="name"
                                                   placeholder="<?= htmlspecialchars($lang['profile_full_name_placeholder'] ?? 'Your full name') ?>"
                                                   value="<?= htmlspecialchars((string) ($user['full_name'] ?? '')) ?>">
                                        </div>

                                        <div class="profile-form-field">
                                            <label for="editUsername"><?= htmlspecialchars($lang['username'] ?? 'Username') ?></label>
                                            <input type="text" id="editUsername" name="username" autocomplete="username"
                                                   required minlength="3" maxlength="64" pattern="[A-Za-z0-9._-]+"
                                                   value="<?= htmlspecialchars($user['username']) ?>">
                                        </div>

                                        <div class="profile-form-field profile-form-field--full">
                                            <label for="editEmail"><?= htmlspecialchars($lang['email'] ?? 'Email') ?></label>
                                            <input type="email" id="editEmail" name="email" autocomplete="email"
                                                   required maxlength="128"
                                                   value="<?= htmlspecialchars($user['email']) ?>">
                                        </div>

                                        <div class="profile-form-field">
                                            <label for="editPassword"><?= htmlspecialchars($lang['profile_new_password'] ?? 'New password') ?></label>
                                            <input type="password" id="editPassword" name="password" autocomplete="new-password" minlength="8"
                                                   placeholder="<?= htmlspecialchars($lang['profile_new_password_placeholder'] ?? 'Leave blank to keep current') ?>">
                                        </div>

                                        <div class="profile-form-field">
                                            <label for="editPasswordConfirm"><?= htmlspecialchars($lang['profile_confirm_password'] ?? 'Confirm new password') ?></label>
                                            <input type="password" id="editPasswordConfirm" name="password_confirm" autocomplete="new-password" minlength="8">
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-edit-actions">
                                    <button type="submit" class="btn-profile-save">
                                        <i class="fas fa-save"></i>
                                        <span><?= htmlspecialchars($lang['profile_save'] ?? 'Save changes') ?></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "../footer.php"; ?>
