<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\Contact;

class ContactController extends BaseController
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new Contact();
    }

    /**
     * Affiche le formulaire de contact
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'Contactez Chasseur de Dolmens',
            'errors' => []
        ];

        $this->render('contact/index', $data);
    }

    /**
     * Traite la soumission du formulaire de contact
     */
    public function submit()
    {
        // Vérification de la méthode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contact');
            exit;
        }

        // Collecte des données du formulaire
        $contactData = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? null,
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? ''
        ];

        // Validation des données
        $errors = $this->contactModel->validateContactData($contactData);

        // Si des erreurs existent, réafficher le formulaire
        if (!empty($errors)) {
            $data = [
                'pageTitle' => 'Contactez Chasseur de Dolmens',
                'errors' => $errors,
                'formData' => $contactData
            ];
            $this->render('contact/index', $data);
            return;
        }

        // Tentative d'enregistrement du message
        $result = $this->contactModel->createMessage($contactData);

        if ($result) {
            // Envoi d'un email de notification (à implémenter)
            $this->sendNotificationEmail($contactData);

            // Redirection avec message de succès
            $_SESSION['contact_success'] = 'Votre message a été envoyé avec succès !';
            header('Location: /contact');
            exit;
        } else {
            // Gestion de l'erreur d'enregistrement
            $data = [
                'pageTitle' => 'Contactez Chasseur de Dolmens',
                'errors' => ['Une erreur est survenue. Veuillez réessayer.'],
                'formData' => $contactData
            ];
            $this->render('contact/index', $data);
        }
    }

    /**
     * Envoi d'un email de notification (méthode factice)
     *
     * @param array $contactData Données du message
     */
    private function sendNotificationEmail($contactData)
    {
        // TODO: Implémenter l'envoi réel d'email
        // Utiliser PHP mail() ou une bibliothèque comme PHPMailer
        $to = 'chasseurdedolmens@gmail.com';
        $subject = 'Nouveau message de contact : ' . $contactData['subject'];
        $message = "
            Nom : {$contactData['name']}
            Email : {$contactData['email']}
            Message : {$contactData['message']}
        ";

        // mail($to, $subject, $message);
    }
}