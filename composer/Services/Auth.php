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

    public static function requireAuth()
    {
        if (!self::check()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin()
    {
        self::requireAuth();
        $user = self::user();

        if (!$user->hasRole('Admin')) {
            $_SESSION['error'] = "Accès non autorisé";
            header('Location: /');
            exit;
        }
    }
}