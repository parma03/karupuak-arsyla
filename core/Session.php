<?php
// core/Session.php
namespace Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/',
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    // Auth helpers
    public static function isLoggedIn(): bool
    {
        return self::has('user_id');
    }

    public static function userId(): ?int
    {
        return self::get('user_id');
    }

    public static function userRole(): ?string
    {
        return self::get('user_role');
    }

    public static function isAdmin(): bool
    {
        return in_array(self::userRole(), ['admin', 'owner']);
    }

    public static function isOwner(): bool
    {
        return self::userRole() === 'owner';
    }

    public static function login(array $user): void
    {
        self::set('user_id',    $user['id']);
        self::set('user_name',  $user['name']);
        self::set('user_email', $user['email']);
        self::set('user_role',  $user['role']);
    }
}
