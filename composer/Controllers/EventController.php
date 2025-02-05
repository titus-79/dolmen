<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\Event;

class EventController extends BaseController
{
    private $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    /**
     * Affiche la liste des événements
     */
    public function index()
    {
        $events = $this->eventModel->getAllEvents();

        $data = [
            'pageTitle' => 'Événements - Chasseur de Dolmens',
            'events' => $events
        ];

        $this->render('event/index', $data);
    }

    /**
     * Affiche les détails d'un événement spécifique
     * @param int $id Identifiant de l'événement
     */
    public function show($id)
    {
        $event = $this->eventModel->getEventById($id);

        if (!$event) {
            // Gérer le cas où l'événement n'existe pas
            // Redirection ou affichage d'une erreur
            header('Location: /events');
            exit;
        }

        $data = [
            'pageTitle' => $event['title_event'],
            'event' => $event
        ];

        $this->render('event/show', $data);
    }

    /**
     * Affiche le formulaire de création d'événement
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Créer un événement'
        ];

        $this->render('event/create', $data);
    }

    /**
     * Traite la création d'un nouvel événement
     */
    public function store()
    {
        // Validation et traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventData = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'author' => $_POST['author'] ?? '',
                'date' => $_POST['date'] ?? '',
                'address_id' => $_POST['address_id'] ?? null
            ];

            // Validation simple (à améliorer)
            if (empty($eventData['title']) || empty($eventData['date'])) {
                // Gérer les erreurs de validation
                $data = [
                    'pageTitle' => 'Créer un événement',
                    'errors' => ['Tous les champs requis ne sont pas remplis']
                ];
                $this->render('event/create', $data);
                return;
            }

            // Tentative de création
            $result = $this->eventModel->createEvent($eventData);

            if ($result) {
                // Redirection après création réussie
                header('Location: /events');
                exit;
            } else {
                // Gérer l'échec de création
                $data = [
                    'pageTitle' => 'Créer un événement',
                    'errors' => ['Erreur lors de la création de l\'événement']
                ];
                $this->render('event/create', $data);
            }
        }
    }

    /**
     * Affiche les événements à venir
     */
    public function upcoming()
    {
        $upcomingEvents = $this->eventModel->getUpcomingEvents();

        $data = [
            'pageTitle' => 'Événements à venir',
            'events' => $upcomingEvents
        ];

        $this->render('event/upcoming', $data);
    }
}