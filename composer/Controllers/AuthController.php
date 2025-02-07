<?php
// AuthController.php

namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\User;

class AuthController extends BaseController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutes en secondes

    public function showLoginForm()
    {
        // Génération d'un token CSRF
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->render('auth/login', [
            'pageTitle' => 'Connexion - Chasseur de Dolmens',
            'csrf_token' => $_SESSION['csrf_token']
        ]);
    }

    public function login(): void
    {
        error_log("Début de la méthode login");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Méthode non-POST détectée");
            header('Location: /login');
            exit;
        }

        // Vérification du token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Session invalide, veuillez réessayer";
            header('Location: /login');
            exit;
        }

        // Vérification des tentatives de connexion
        if ($this->isAccountLocked()) {
            $_SESSION['error'] = "Compte temporairement bloqué. Veuillez réessayer plus tard.";
            header('Location: /login');
            exit;
        }

        $login = trim(htmlspecialchars($_POST['login_user'] ?? ''));
        $password = $_POST['password_hash_user'] ?? '';

        error_log("Tentative de connexion pour l'utilisateur: " . $login);


        if (empty($login) || empty($password)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs";
            header('Location: /login');
            exit;
        }

        try {
            $user = User::findByLogin($login);

            if ($user && password_verify($password, $user->getPasswordHash())) {
                error_log("Authentification réussie");
                // Réinitialisation des tentatives de connexion
                $this->resetLoginAttempts();

                // Régénération de l'ID de session
                session_regenerate_id(true);

                // Création de la session
                $_SESSION['user'] = base64_encode(serialize($user));
                $_SESSION['last_activity'] = time();

                error_log("Session créée: " . print_r($_SESSION, true));
                error_log("Redirection vers /account");

                // Journal de connexion
                $this->logSuccessfulLogin($user->getId());

                header('Location: /account');
                exit;
            } else {
                error_log("Authentification échouée");
                $this->incrementLoginAttempts();
                $_SESSION['error'] = "Identifiants invalides";
                header('Location: /login');
                exit;
            }
        } catch (\Exception $e) {
            error_log("Erreur de connexion: " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue";
            header('Location: /login');
            exit;
        }
    }

    private function isAccountLocked(): bool
    {
        if (!isset($_SESSION['login_attempts']) || !isset($_SESSION['first_failed_attempt'])) {
            return false;
        }

        if ($_SESSION['login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $timeElapsed = time() - $_SESSION['first_failed_attempt'];
            if ($timeElapsed < self::LOCKOUT_TIME) {
                return true;
            }
            // Réinitialisation après la période de blocage
            $this->resetLoginAttempts();
        }
        return false;
    }

    private function incrementLoginAttempts(): void
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 1;
            $_SESSION['first_failed_attempt'] = time();
        } else {
            $_SESSION['login_attempts']++;
        }
    }

    private function resetLoginAttempts(): void
    {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['first_failed_attempt']);
    }

    private function logSuccessfulLogin(int $userId): void
    {
        // Implémentez ici la journalisation des connexions réussies
        // Par exemple, dans une table de la base de données
    }

    public function logout()
    {
        // Destruction de toutes les données de session
        session_unset();
        session_destroy();

        // Redirection avec un nouveau cookie de session
        session_start();
        $_SESSION['success'] = "Vous avez été déconnecté avec succès";
        header('Location: /login');
        exit;
    }
}