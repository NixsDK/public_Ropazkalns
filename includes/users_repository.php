<?php
/**
 * users_repository.php
 * --------------------------------------------------------------
 * CRUD for the public-site `users` table (sql/10_users.sql).
 * Auth helpers (session/CSRF/login/logout) live in user_auth.php –
 * this file is just data access.
 */

if (!function_exists('users_create')) {
    /**
     * @param array{username:string,email:string,password:string,full_name:?string} $data
     * @return array{ok:bool, id?:int, error?:string}
     */
    function users_create(PDO $db, array $data): array
    {
        try {
            $stmt = $db->prepare(
                'INSERT INTO users (username, email, password_hash, full_name)
                 VALUES (:u, :e, :p, :f)'
            );
            $stmt->execute([
                ':u' => $data['username'],
                ':e' => $data['email'],
                ':p' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':f' => $data['full_name'] ?: null,
            ]);
            return ['ok' => true, 'id' => (int) $db->lastInsertId()];
        } catch (PDOException $e) {
            // 1062 = duplicate key (UNIQUE on username or email)
            if ($e->getCode() === '23000' || (int) $e->errorInfo[1] === 1062) {
                $msg = (string) $e->getMessage();
                if (stripos($msg, 'uniq_users_email') !== false) {
                    return ['ok' => false, 'error' => 'That email is already registered.'];
                }
                return ['ok' => false, 'error' => 'That username is already taken.'];
            }
            error_log('[users_repo] create failed: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Could not create the account.'];
        } catch (Throwable $e) {
            error_log('[users_repo] create failed: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Could not create the account.'];
        }
    }
}

if (!function_exists('users_find_by_id')) {
    function users_find_by_id(PDO $db, int $id): ?array
    {
        try {
            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            error_log('[users_repo] find_by_id failed: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('users_find_by_login')) {
    /** Find by username OR email (case-insensitive). */
    function users_find_by_login(PDO $db, string $usernameOrEmail): ?array
    {
        try {
            $input = trim($usernameOrEmail);
            // Notice we now use :login1 and :login2
            $stmt = $db->prepare('SELECT * FROM users WHERE username = :login1 OR email = :login2 LIMIT 1');

            // We pass the exact same input to both placeholders
            $stmt->execute([
                ':login1' => $input,
                ':login2' => $input
            ]);

            $row = $stmt->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            // We put the error log back so it doesn't crash your live site
            error_log('[users_repo] find_by_login failed: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('users_touch_login')) {
    function users_touch_login(PDO $db, int $id): void
    {
        try {
            $db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
               ->execute([':id' => $id]);
        } catch (Throwable $e) {
            error_log('[users_repo] touch_login failed: ' . $e->getMessage());
        }
    }
}

if (!function_exists('users_update_profile')) {
    /**
     * Update the editable profile fields. Does NOT touch the password
     * (use users_update_password for that).
     *
     * @param array{username:string,email:string,full_name:?string} $data
     * @return array{ok:bool, error?:string}
     */
    function users_update_profile(PDO $db, int $id, array $data): array
    {
        try {
            $stmt = $db->prepare(
                'UPDATE users
                    SET username = :u, email = :e, full_name = :f
                  WHERE id = :id'
            );
            $stmt->execute([
                ':u'  => $data['username'],
                ':e'  => $data['email'],
                ':f'  => $data['full_name'] ?: null,
                ':id' => $id,
            ]);
            return ['ok' => true];
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' || (int) $e->errorInfo[1] === 1062) {
                $msg = (string) $e->getMessage();
                if (stripos($msg, 'uniq_users_email') !== false) {
                    return ['ok' => false, 'error' => 'That email is already in use.'];
                }
                return ['ok' => false, 'error' => 'That username is already taken.'];
            }
            error_log('[users_repo] update_profile failed: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Could not save changes.'];
        } catch (Throwable $e) {
            error_log('[users_repo] update_profile failed: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Could not save changes.'];
        }
    }
}

if (!function_exists('users_update_password')) {
    function users_update_password(PDO $db, int $id, string $newPassword): bool
    {
        try {
            $stmt = $db->prepare('UPDATE users SET password_hash = :p WHERE id = :id');
            return $stmt->execute([
                ':p'  => password_hash($newPassword, PASSWORD_DEFAULT),
                ':id' => $id,
            ]);
        } catch (Throwable $e) {
            error_log('[users_repo] update_password failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('users_set_avatar')) {
    function users_set_avatar(PDO $db, int $id, ?string $relPath): bool
    {
        try {
            $stmt = $db->prepare('UPDATE users SET avatar_path = :a WHERE id = :id');
            return $stmt->execute([':a' => $relPath, ':id' => $id]);
        } catch (Throwable $e) {
            error_log('[users_repo] set_avatar failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('users_bookings')) {
    /** All bookings linked to a user. */
    function users_bookings(PDO $db, int $userId): array
    {
        try {
            $stmt = $db->prepare(
                'SELECT * FROM bookings
                  WHERE user_id = :id
                  ORDER BY start_date DESC, id DESC'
            );
            $stmt->execute([':id' => $userId]);
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log('[users_repo] bookings failed: ' . $e->getMessage());
            return [];
        }
    }
}

if (!function_exists('users_pe_requests')) {
    function users_pe_requests(PDO $db, int $userId): array
    {
        try {
            $stmt = $db->prepare(
                'SELECT * FROM pe_requests
                  WHERE user_id = :id
                  ORDER BY created_at DESC'
            );
            $stmt->execute([':id' => $userId]);
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log('[users_repo] pe_requests failed: ' . $e->getMessage());
            return [];
        }
    }
}
