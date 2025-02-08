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
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            // Rediriger vers le dashboard
            header('Location: /account');
            exit;
        }

        // Si l'utilisateur n'est pas connecté, générer le token CSRF et afficher le formulaire
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

    public function showRegistrationForm()
    {
        // Si l'utilisateur est déjà connecté, le rediriger vers son compte
        if (isset($_SESSION['user'])) {
            header('Location: /account');
            exit;
        }

        // Générer un nouveau token CSRF pour le formulaire
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Afficher le formulaire d'inscription
        $this->render('auth/register', [
            'pageTitle' => 'Inscription - Chasseur de Dolmens',
            'csrf_token' => $_SESSION['csrf_token']
        ]);
    }

    public function register()
    {
        // Vérifier que la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Session invalide, veuillez réessayer";
            header('Location: /register');
            exit;
        }

        // Récupérer et nettoyer les données du formulaire
        $userData = [
            'name' => trim(htmlspecialchars($_POST['name_user'] ?? '')),
            'firstname' => trim(htmlspecialchars($_POST['firstname_user'] ?? '')),
            'login' => trim(htmlspecialchars($_POST['login_user'] ?? '')),
            'email' => trim(filter_var($_POST['user_email'] ?? '', FILTER_SANITIZE_EMAIL)),
            'tel' => trim(htmlspecialchars($_POST['tel_user'] ?? '')),
            'password' => $_POST['password_hash_user'] ?? ''
        ];

        // Validation des données
        $errors = $this->validateRegistrationData($userData);

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = array_diff_key($userData, ['password' => '']);
            header('Location: /register');
            exit;
        }

        try {
            // Créer le nouvel utilisateur
            $user = new User();
            $user->setName($userData['name'])
                ->setFirstname($userData['firstname'])
                ->setLogin($userData['login'])
                ->setEmail($userData['email'])
                ->setTel($userData['tel'])
                ->setPasswordHash(password_hash($userData['password'], PASSWORD_BCRYPT));

            if ($user->save()) {
                // Si l'option newsletter est cochée, enregistrer l'abonnement
                if (isset($_POST['newsletter_subscription'])) {
                    NewsletterSubscription::subscribe($user->getId(), $user->getEmail());
                }

                $_SESSION['success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
                header('Location: /login');
                exit;
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de la création du compte";
                header('Location: /register');
                exit;
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'inscription : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la création du compte";
            header('Location: /register');
            exit;
        }
    }

    private function validateRegistrationData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = "Le nom est requis";
        }

        if (empty($data['firstname'])) {
            $errors[] = "Le prénom est requis";
        }

        if (empty($data['login'])) {
            $errors[] = "Le login est requis";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $data['login'])) {
            $errors[] = "Le login doit contenir entre 3 et 20 caractères (lettres, chiffres, tirets et underscores)";
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide";
        }

        if (empty($data['password'])) {
            $errors[] = "Le mot de passe est requis";
        } elseif (strlen($data['password']) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }

        // Vérifier si le login existe déjà
        if (!empty($data['login'])) {
            $existingUser = User::findByLogin($data['login']);
            if ($existingUser) {
                $errors[] = "Ce login est déjà utilisé";
            }
        }

        return $errors;
    }
}