<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Services\Auth;
use Titus\Dolmen\Models\User;
use Titus\Dolmen\Models\Event;
use Titus\Dolmen\Models\Portfolio;
use Titus\Dolmen\Models\Prints;
use Titus\Dolmen\Models\Group;
use Titus\Dolmen\Models\Newsletter;
use Titus\Dolmen\Models\NewsletterSubscription;

class AdminController extends BaseController
{
    private $eventModel;
    private $portfolioModel;
    private $printsModel;
    private $userModel;

    public function __construct()
    {
        // Vérification des permissions d'administrateur
        if (!Auth::requireAdmin()) {
            header('Location: /');
            exit;
        }

        $this->eventModel = new Event();
        $this->portfolioModel = new Portfolio();
        $this->printsModel = new Prints();
        $this->userModel = new User();
    }

    // Dashboard administrateur
    public function index()
    {
        $data = [
            'pageTitle' => 'Administration - Chasseur de Dolmens',
            'userCount' => count($this->userModel->getAllUsers()),
            'eventCount' => count($this->eventModel->getAllEvents()),
            'portfolioCount' => count($this->portfolioModel->getAllPortfolios()),
            'printsCount' => count($this->printsModel->getAllPrints())
        ];

        $this->render('admin/dashboard', $data);
    }

    // Gestion des utilisateurs
    public function users()
    {
        $users = $this->userModel->getAllUsers();
        $data = [
            'pageTitle' => 'Gestion des utilisateurs',
            'users' => $users
        ];

        $this->render('admin/users/index', $data);
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name_user'] ?? '',
                'firstname' => $_POST['firstname_user'] ?? '',
                'login' => $_POST['login_user'] ?? '',
                'email' => $_POST['email_user'] ?? '',
                'tel' => $_POST['tel_user'] ?? '',
                'password' => $_POST['password_hash_user'] ?? '',
                'groups' => $_POST['groups'] ?? []
            ];

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
                $_SESSION['success'] = "Utilisateur créé avec succès";
                header('Location: /admin/users');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la création de l'utilisateur";
            }
        }

        // Récupérer la liste des groupes disponibles
        $groups = Group::getAllGroups();

        $data = [
            'pageTitle' => 'Créer un utilisateur',
            'groups' => $groups
        ];

        $this->render('admin/users/create', $data);
    }

    public function editUser(string $id)
    {
        // Conversion de l'ID en entier
        $userId = (int)$id;

        // Récupération de l'utilisateur
        $user = User::findById($userId);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $userData = [
                    'name' => $_POST['name_user'] ?? '',
                    'firstname' => $_POST['firstname_user'] ?? '',
                    'email' => $_POST['email_user'] ?? '',
                    'tel' => $_POST['tel_user'] ?? '',
                    'newsletter_subscription' => isset($_POST['newsletter_subscription']),
                    'role' => $_POST['role'] ?? 'Member' // Rôle par défaut si non spécifié
                ];

                // Ajoute le mot de passe uniquement s'il est fourni
                if (!empty($_POST['password_hash_user'])) {
                    $userData['password'] = $_POST['password_hash_user'];
                }

                if (User::updateUser($userId, $userData)) {
                    $_SESSION['success'] = "Utilisateur mis à jour avec succès";
                    header('Location: /admin/users');
                    exit;
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour de l'utilisateur";
                }
            } catch (\Exception $e) {
                error_log("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
                $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour";
            }
        }

        // Prépare les données pour la vue
        $data = [
            'pageTitle' => 'Modifier un utilisateur',
            'user' => $user
        ];

        // Affiche la vue d'édition
        $this->render('admin/users/edit', $data);
    }

    public function deleteUser(string $id)
    {
        // Conversion de l'ID en entier
        $userId = (int)$id;

        // Vérification que l'utilisateur existe
        $user = User::findById($userId);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /admin/users');
            exit;
        }

        // Vérification que l'utilisateur n'essaie pas de se supprimer lui-même
        $currentUser = \Titus\Dolmen\Services\Auth::user();
        if ($currentUser->getId() === $userId) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte";
            header('Location: /admin/users');
            exit;
        }

        try {
            if (User::deleteUser($userId)) {
                $_SESSION['success'] = "Utilisateur supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la suppression";
        }

        header('Location: /admin/users');
        exit;
    }

    public function newsletters()
    {
        $newsletters = Newsletter::getAllNewsletters();
        $data = [
            'pageTitle' => 'Gestion des newsletters',
            'newsletters' => $newsletters
        ];
        $this->render('admin/newsletters/index', $data);
    }

    public function createNewsletter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newsletterData = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'created_by' => Auth::user()->getId()
            ];

            if (Newsletter::create($newsletterData)) {
                if ($_POST['action'] === 'send') {
                    // Envoi immédiat
                    $newsletter->send();
                    $_SESSION['success'] = "Newsletter créée et envoyée avec succès";
                } else {
                    // Sauvegarde comme brouillon
                    $_SESSION['success'] = "Newsletter sauvegardée comme brouillon";
                }
                header('Location: /admin/newsletters');
                exit;
            }
        }

        $data = [
            'pageTitle' => 'Créer une newsletter',
            'subscriberCount' => count(NewsletterSubscription::getAllActiveSubscribers())
        ];
        $this->render('admin/newsletters/create', $data);
    }

    // Gestion des événements
    public function events()
    {
        $events = $this->eventModel->getAllEvents();
        $data = [
            'pageTitle' => 'Gestion des événements',
            'events' => $events
        ];

        $this->render('admin/events/index', $data);
    }

    public function editEvent($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventData = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'date' => $_POST['date'] ?? '',
                // Ajoutez d'autres champs selon besoin
            ];

            if ($id) {
                $success = $this->eventModel->updateEvent($id, $eventData);
            } else {
                $success = $this->eventModel->createEvent($eventData);
            }

            if ($success) {
                $_SESSION['success'] = $id ? "Événement mis à jour" : "Événement créé";
                header('Location: /admin/events');
                exit;
            }
        }

        $event = $id ? $this->eventModel->getEventById($id) : null;
        $data = [
            'pageTitle' => $id ? 'Modifier un événement' : 'Créer un événement',
            'event' => $event
        ];

        $this->render('admin/events/edit', $data);
    }

    // Gestion du portfolio
    public function portfolio()
    {
        $portfolios = $this->portfolioModel->getAllPortfolios();
        $data = [
            'pageTitle' => 'Gestion du portfolio',
            'portfolios' => $portfolios
        ];

        $this->render('admin/portfolio/index', $data);
    }

    // Gestion des tirages
    public function prints()
    {
        $prints = $this->printsModel->getAllPrints();
        $data = [
            'pageTitle' => 'Gestion des tirages',
            'prints' => $prints
        ];

        $this->render('admin/prints/index', $data);
    }

    // Gestion des commandes
    public function orders()
    {
        // À implémenter : logique de gestion des commandes
        $data = [
            'pageTitle' => 'Gestion des commandes'
        ];

        $this->render('admin/orders/index', $data);
    }
}