<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Services\Auth;
use Titus\Dolmen\Models\Order;

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
                $user->setName($_POST['name_user'])
                    ->setFirstname($_POST['firstname_user'])
                    ->setEmail($_POST['user_email']);

                if (!empty($_POST['tel_user'])) {
                    $user->setTel($_POST['tel_user']);
                }

                if (!empty($_POST['password_hash_user'])) {
                    $user->setPasswordHash(
                        password_hash($_POST['password_hash_user'], PASSWORD_BCRYPT)
                    );
                }

                if ($user->save()) {
                    $_SESSION['success'] = "Profil mis à jour avec succès";
                    $_SESSION['user'] = base64_encode(serialize($user));
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