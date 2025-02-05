<?php
// composer/Controllers/BaseController.php
namespace Titus\Dolmen\Controllers;

abstract class BaseController {
    protected function render(string $view, array $data = []): void
    {
        // Extrait les données pour les rendre disponibles dans la vue
        extract($data);

        // Le chemin vers nos vues - Notez le changement ici
        $viewPath = dirname(__DIR__) . '/Views/templates/' . $view . '.php';

//        echo "Tentative de chargement de la vue : " . $viewPath . "<br>";
//        echo "Le fichier existe ? : " . (file_exists($viewPath) ? 'Oui' : 'Non') . "<br>";
        // Démarre la mise en mémoire tampon
        ob_start();

        // Vérifie si le fichier de vue existe
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("Vue non trouvée: $view (chemin: $viewPath)");
        }

        // Récupère le contenu et nettoie la mémoire tampon
        $content = ob_get_clean();

        // Charge le template de base qui contiendra notre vue
        require dirname(__DIR__) . '/Views/templates/base.php';
    }
}