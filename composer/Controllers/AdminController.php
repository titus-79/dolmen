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
        try {
            // Récupération des comptages
            $userCount = is_array($this->userModel->getAllUsers()) ?
                count($this->userModel->getAllUsers()) : 0;

            $eventCount = is_array($this->eventModel->getAllEvents()) ?
                count($this->eventModel->getAllEvents()) : 0;

            $albums = $this->portfolioModel->getAllAlbums();
            $portfolioCount = is_array($albums) ? count($albums) : 0;

            $prints = $this->printsModel->getAllPrints();
            $printsCount = is_array($prints) ? count($prints) : 0;

            $data = [
                'pageTitle' => 'Administration - Chasseur de Dolmens',
                'userCount' => $userCount,
                'eventCount' => $eventCount,
                'portfolioCount' => $portfolioCount,
                'printsCount' => $printsCount
            ];

            $this->render('admin/dashboard', $data);

        } catch (\Exception $e) {
            error_log("Erreur dans AdminController::index : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors du chargement du tableau de bord";
            $data = [
                'pageTitle' => 'Administration - Chasseur de Dolmens',
                'userCount' => 0,
                'eventCount' => 0,
                'portfolioCount' => 0,
                'printsCount' => 0
            ];
            $this->render('admin/dashboard', $data);
        }
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
        $albums = $this->portfolioModel->getAllAlbums();
        $data = [
            'pageTitle' => 'Gestion du Portfolio',
            'albums' => $albums
        ];
        $this->render('admin/portfolio/index', $data);
    }

    public function createAlbum()
    {
        try {
            $regions = $this->portfolioModel->getAllRegions();
            $mainAlbums = $this->portfolioModel->getAllAlbums();

            $data = [
                'pageTitle' => 'Créer un nouvel album',
                'regions' => $regions,
                'mainAlbums' => $mainAlbums
            ];

            $this->render('admin/portfolio/create', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans createAlbum : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors du chargement du formulaire";
            header('Location: /admin/portfolio');
            exit;
        }
    }

    public function storeAlbum()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/portfolio/create');
            exit;
        }

        try {
            // Validation de base
            if (empty($_POST['title'])) {
                throw new \Exception("Le titre est requis");
            }

            if (empty($_POST['region'])) {
                throw new \Exception("La région est requise");
            }

            // Validation et upload du fichier
            if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("L'image de couverture est requise");
            }

            // Upload du fichier
            $thumbnail_path = $this->portfolioModel->uploadThumbnail($_FILES['thumbnail']);
            if (!$thumbnail_path) {
                throw new \Exception("Erreur lors de l'upload de l'image");
            }

            // Préparation des données
            $albumData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'region' => $_POST['region'],
                'new_region' => $_POST['region'] === 'nouvelle' ? ($_POST['new_region'] ?? '') : '',
                'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
                'thumbnail_path' => $thumbnail_path
            ];

            // Création de l'album
            $albumId = $this->portfolioModel->createAlbum($albumData);
            if (!$albumId) {
                throw new \Exception("Erreur lors de la création de l'album");
            }

            // Handle photos with address information
            if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
                $hasFiles = false;
                foreach ($_FILES['photos']['name'] as $filename) {
                    if (!empty($filename)) {
                        $hasFiles = true;
                        break;
                    }
                }

                if ($hasFiles) {
                    // Collect address data from the form
                    $addressData = [
                        'location_names' => $_POST['location_names'] ?? [],
                        'street_numbers' => $_POST['street_numbers'] ?? [],
                        'streets' => $_POST['streets'] ?? [],
                        'cities' => $_POST['cities'] ?? [],
                        'postal_codes' => $_POST['postal_codes'] ?? [],
                        'countries' => $_POST['countries'] ?? [],
                        'gps_coordinates' => $_POST['gps_coordinates'] ?? [],
                    ];

                    // Upload photos with address information
                    $uploadedPhotos = $this->portfolioModel->addPhotosToAlbum($albumId, $_FILES['photos'], $addressData);

                    if (empty($uploadedPhotos)) {
                        $this->db->getConnection()->rollBack();
                        throw new \Exception("Erreur lors de l'upload des photos");
                    }
                }
            }

            $_SESSION['success'] = "Album créé avec succès";
            header('Location: /admin/portfolio');
            exit;

        } catch (\Exception $e) {
            error_log("Erreur dans storeAlbum: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/portfolio/create');
            exit;
        }
    }

    public function deleteAlbum(string $id)
    {
        try {
            // Conversion de l'ID en entier
            $albumId = (int)$id;

            // Vérification que l'album existe
            $album = $this->portfolioModel->getAlbumWithPhotos($albumId);
            if (!$album) {
                $_SESSION['error'] = "Album non trouvé";
                header('Location: /admin/portfolio');
                exit;
            }

            // Tentative de suppression
            if ($this->portfolioModel->deleteAlbum($albumId)) {
                $_SESSION['success'] = "Album supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'album";
            }

        } catch (\Exception $e) {
            error_log("Erreur lors de la suppression de l'album : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la suppression";
        }

        // Redirection vers la liste des albums
        header('Location: /admin/portfolio');
        exit;
    }

    public function editAlbum(string $id)
    {
        $album = $this->portfolioModel->getAlbumWithPhotos((int)$id);
        if (!$album) {
            $_SESSION['error'] = "Album non trouvé";
            header('Location: /admin/portfolio');
            exit;
        }

        $data = [
            'pageTitle' => 'Modifier l\'album',
            'album' => $album,
            'regions' => $this->portfolioModel->getAllRegions()
        ];
        $this->render('admin/portfolio/edit', $data);
    }

    public function updateAlbum(string $id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /admin/portfolio');
                exit;
            }

            $albumId = (int)$id;
            $albumData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'region' => $_POST['region'],
            ];

            // Gestion de l'upload d'une nouvelle image de couverture
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbnail_path = $this->portfolioModel->uploadThumbnail($_FILES['thumbnail']);
                if ($thumbnail_path) {
                    $albumData['thumbnail_path'] = $thumbnail_path;
                }
            }

            if ($this->portfolioModel->updateAlbum($albumId, $albumData)) {
                // Gestion des nouvelles photos
                if (isset($_FILES['new_photos'])) {
                    $this->portfolioModel->addPhotosToAlbum($albumId, $_FILES['new_photos']);
                }

                $_SESSION['success'] = "Album mis à jour avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour de l'album";
            }

        } catch (\Exception $e) {
            error_log("Erreur lors de la mise à jour de l'album : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour";
        }

        header('Location: /admin/portfolio/edit/' . $id);
        exit;
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