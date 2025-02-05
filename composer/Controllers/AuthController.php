<?php

namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\User;

class AuthController extends BaseController
{
    public function showLoginForm()
    {
        $this->render('auth/login', [
            'pageTitle' => 'Connexion - Chasseur de Dolmens'
        ]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $login = $_POST['login_user'] ?? '';
        $password = $_POST['password_hash_user'] ?? '';

        if (empty($login) || empty($password)) {
            // Rediriger avec un message d'erreur
            $_SESSION['error'] = "Veuillez remplir tous les champs";
            header('Location: /login');
            exit;
        }

        try {
            $user = User::findByLogin($login);

            if ($user && password_verify($password, $user->getPasswordHash())) {
                // Créer la session
                $_SESSION['user'] = base64_encode(serialize($user));

                // Rediriger vers le tableau de bord
                header('Location: /account');
                exit;
            } else {
                $_SESSION['error'] = "Identifiants invalides";
                header('Location: /login');
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue";
            header('Location: /login');
            exit;
        }
    }

    public function showRegistrationForm()
    {
        $this->render('auth/register', [
            'pageTitle' => 'Inscription - Chasseur de Dolmens',
            'isModification' => false
        ]);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // Validation des données
        $requiredFields = ['name_user', 'login_user', 'password_hash_user',
            'firstname_user', 'user_email'];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis";
                header('Location: /register');
                exit;
            }
        }

        try {
            $user = new User();
            $user->setName($_POST['name_user'])
                ->setLogin($_POST['login_user'])
                ->setPasswordHash(password_hash($_POST['password_hash_user'], PASSWORD_BCRYPT))
                ->setEmail($_POST['user_email'])
                ->setFirstname($_POST['firstname_user']);

            if (isset($_POST['tel_user'])) {
                $user->setTel($_POST['tel_user']);
            }

            if ($user->save()) {
                $_SESSION['success'] = "Compte créé avec succès";
                header('Location: /login');
                exit;
            } else {
                throw new \Exception("Erreur lors de l'enregistrement");
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
            header('Location: /register');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /');
        exit;
    }
}