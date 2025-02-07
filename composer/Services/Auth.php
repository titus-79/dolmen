<?php
namespace Titus\Dolmen\Services;

use Titus\Dolmen\Models\User;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?User
    {
        if (self::check()) {
            return unserialize(base64_decode($_SESSION['user']));
        }
        return null;
    }

    public static function requireAuth(bool $redirect = true): bool
    {
        if (!self::check()) {
            if ($redirect) {
                $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
                header('Location: /login');
                exit;
            }
            return false;
        }
        return true;
    }

    public static function requireAdmin(bool $redirect = true): bool
    {
        if (!self::requireAuth($redirect)) {
            return false;
        }

        $user = self::user();
        if (!$user->hasRole('Admin')) {
            if ($redirect) {
                $_SESSION['error'] = "Accès non autorisé";
                header('Location: /');
                exit;
            }
            return false;
        }
        return true;
    }
}