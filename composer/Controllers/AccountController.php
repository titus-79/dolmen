<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Services\Auth;
use Titus\Dolmen\Models\Order;
use Titus\Dolmen\Models\User;

class AccountController extends BaseController
{
    private ?Order $orderModel = null;

    public function __construct()
    {
        // La vérification d'auth se fait dans les méthodes plutôt que dans le constructeur
        $this->orderModel = new Order();
    }

    public function index()
    {
        error_log("AccountController::index - Début de la méthode");
        // Vérification de l'authentification
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        // Récupération de l'utilisateur connecté
        $user = Auth::user();

        // Récupération des commandes de l'utilisateur
        $orders = $this->orderModel->getUserOrders($user->getId());

        // Préparation des données pour la vue
        $data = [
            'pageTitle' => 'Mon Compte - Chasseur de Dolmens',
            'user' => $user,
            'orders' => $orders,
            'lastLogin' => $user->getLastConn(),
            'memberSince' => $user->getCreatedAt()
        ];

        // Rendu de la vue
        $this->render('account/dashboard', $data);
    }

    public function editProfile()
    {
        // Vérification de l'authentification
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userData = [
                    'name' => $_POST['name_user'],
                    'firstname' => $_POST['firstname_user'],
                    'email' => $_POST['user_email'],
                    'tel' => $_POST['tel_user'] ?? '',
                    'newsletter_subscription' => isset($_POST['newsletter_subscription']),
                ];

                // Add password to userData if it's being updated
                if (!empty($_POST['password_hash_user'])) {
                    $userData['password'] = $_POST['password_hash_user'];
                }

                // Use the updateUser method which handles newsletter subscription
                if (User::updateUser($user->getId(), $userData)) {
                    $_SESSION['success'] = "Profil mis à jour avec succès";

                    // Refresh user data in session
                    $updatedUser = User::findById($user->getId());
                    if ($updatedUser) {
                        $_SESSION['user'] = base64_encode(serialize($updatedUser));
                    }
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $_SESSION['error'] = "Une erreur est survenue";
            }

            header('Location: /account');
            exit;
        }

        $data = [
            'pageTitle' => 'Modifier mon profil - Chasseur de Dolmens',
            'user' => $user
        ];

        $this->render('account/edit', $data);
    }
}