<?php
namespace Titus\Dolmen\Controllers;

use Titus\Dolmen\Models\Portfolio;

class PortfolioController extends BaseController
{
    private Portfolio $portfolioModel;

    public function __construct()
    {
        $this->portfolioModel = new Portfolio();
    }

    /**
     * Affiche la page principale du portfolio avec tous les albums
     */
    public function index()
    {
        try {
            $albums = $this->portfolioModel->getAllAlbums();

            $data = [
                'pageTitle' => 'Portfolio - Chasseur de Dolmens',
                'description' => 'Découvrez ma collection de photographies de dolmens à travers la France et l\'Europe',
                'albums' => $albums
            ];

            $this->render('portfolio/index', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans PortfolioController::index : " . $e->getMessage());
            // Redirection vers une page d'erreur ou affichage d'un message
            $this->render('error/500', [
                'pageTitle' => 'Erreur - Chasseur de Dolmens',
                'message' => 'Une erreur est survenue lors du chargement du portfolio'
            ]);
        }
    }

    /**
     * Affiche un album spécifique avec ses photos
     * @param int $id Identifiant de l'album
     */
    public function showAlbum(int $id)
    {
        try {
            $album = $this->portfolioModel->getAlbumWithPhotos($id);

            if (!$album) {
                // Album non trouvé
                $this->render('error/404', [
                    'pageTitle' => 'Album non trouvé - Chasseur de Dolmens',
                    'message' => 'L\'album demandé n\'existe pas'
                ]);
                return;
            }

            $data = [
                'pageTitle' => $album['title'] . ' - Portfolio - Chasseur de Dolmens',
                'album' => $album
            ];

            $this->render('portfolio/album', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans PortfolioController::showAlbum : " . $e->getMessage());
            $this->render('error/500', [
                'pageTitle' => 'Erreur - Chasseur de Dolmens',
                'message' => 'Une erreur est survenue lors du chargement de l\'album'
            ]);
        }
    }

    /**
     * Affiche les photos d'une région spécifique
     * @param string $region Nom de la région
     */
    public function showRegion(string $region)
    {
        try {
            // Récupère tous les albums de la région
            $albums = $this->portfolioModel->getAlbumsByRegion($region);

            if (empty($albums)) {
                $this->render('error/404', [
                    'pageTitle' => 'Région non trouvée - Chasseur de Dolmens',
                    'message' => 'Aucun album trouvé pour cette région'
                ]);
                return;
            }

            $data = [
                'pageTitle' => 'Dolmens en ' . $region . ' - Chasseur de Dolmens',
                'region' => $region,
                'albums' => $albums
            ];

            $this->render('portfolio/region', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans PortfolioController::showRegion : " . $e->getMessage());
            $this->render('error/500', [
                'pageTitle' => 'Erreur - Chasseur de Dolmens',
                'message' => 'Une erreur est survenue lors du chargement de la région'
            ]);
        }
    }

    /**
     * Affiche une photo individuelle avec ses détails
     * @param int $albumId ID de l'album
     * @param int $photoId ID de la photo
     */
    public function showPhoto(int $albumId, int $photoId)
    {
        try {
            $photo = $this->portfolioModel->getPhotoDetails($albumId, $photoId);

            if (!$photo) {
                $this->render('error/404', [
                    'pageTitle' => 'Photo non trouvée - Chasseur de Dolmens',
                    'message' => 'La photo demandée n\'existe pas'
                ]);
                return;
            }

            $data = [
                'pageTitle' => $photo['title'] . ' - Chasseur de Dolmens',
                'photo' => $photo
            ];

            $this->render('portfolio/photo', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans PortfolioController::showPhoto : " . $e->getMessage());
            $this->render('error/500', [
                'pageTitle' => 'Erreur - Chasseur de Dolmens',
                'message' => 'Une erreur est survenue lors du chargement de la photo'
            ]);
        }
    }

    public function createAlbum()
    {
        try {
            $regions = $this->portfolioModel->getAllRegions();
            error_log("Régions récupérées dans createAlbum : " . print_r($regions, true));

            $data = [
                'pageTitle' => 'Créer un nouvel album',
                'regions' => $regions,
                'mainAlbums' => $this->portfolioModel->getAllAlbums()
            ];

            $this->render('admin/portfolio/create', $data);
        } catch (\Exception $e) {
            error_log("Erreur dans createAlbum : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors du chargement du formulaire";
            header('Location: /admin/portfolio');
            exit;
        }
    }

    private function validateUploadedFile(array $file): ?string
    {
        $maxFileSize = 10 * 1024 * 1024; // 10 MB en octets
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $_SESSION['error'] = "Le fichier est trop volumineux (maximum 10 MB)";
                    break;
                default:
                    $_SESSION['error'] = "Une erreur s'est produite lors de l'upload";
            }
            return null;
        }

        if ($file['size'] > $maxFileSize) {
            $_SESSION['error'] = "Le fichier est trop volumineux (maximum 10 MB)";
            return null;
        }

        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = "Type de fichier non autorisé (JPG, PNG ou WebP uniquement)";
            return null;
        }

        return true;
    }

    public function storeAlbum()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/portfolio/create');
            exit;
        }

        try {
            // Log pour le débogage
            error_log("Début de storeAlbum");
            error_log("POST data : " . print_r($_POST, true));
            error_log("FILES data : " . print_r($_FILES, true));

            // Démarrer une transaction
            $this->db->getConnection()->beginTransaction();

            // 1. Validation et upload de la miniature
            if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception("L'image de couverture est requise");
            }

            $thumbnail_path = $this->portfolioModel->uploadThumbnail($_FILES['thumbnail']);
            if (!$thumbnail_path) {
                throw new \Exception("Erreur lors de l'upload de l'image de couverture");
            }

            // 2. Préparation et création de l'album
            $albumData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'region' => $_POST['region'],
                'new_region' => $_POST['region'] === 'nouvelle' ? ($_POST['new_region'] ?? '') : '',
                'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
                'thumbnail_path' => $thumbnail_path
            ];

            // 3. Création de l'album et récupération de son ID
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
    public function editAlbum(string $id) {
        try {
            // Récupération de l'album et de ses photos
            $album = $this->portfolioModel->getAlbumWithPhotos((int)$id);

            if (!$album) {
                $_SESSION['error'] = "Album non trouvé";
                header('Location: /admin/portfolio');
                exit;
            }

            // Ajout de la gestion des photos
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Gestion de l'upload de nouvelles photos
                if (isset($_FILES['new_photos'])) {
                    $this->portfolioModel->addPhotosToAlbum($id, $_FILES['new_photos']);
                }

                // Gestion de la suppression des photos
                if (isset($_POST['delete_photos']) && is_array($_POST['delete_photos'])) {
                    foreach ($_POST['delete_photos'] as $photoId) {
                        $this->portfolioModel->removePhotoFromAlbum($id, $photoId);
                    }
                }

                // Mise à jour de l'ordre des photos si nécessaire
                if (isset($_POST['photo_order'])) {
                    $this->portfolioModel->updatePhotosOrder($id, $_POST['photo_order']);
                }

                $_SESSION['success'] = "Album mis à jour avec succès";
                header('Location: /admin/portfolio/edit/' . $id);
                exit;
            }

            $data = [
                'pageTitle' => 'Modifier l\'album',
                'album' => $album,
                'regions' => $this->portfolioModel->getAllRegions()
            ];

            $this->render('admin/portfolio/edit', $data);

        } catch (\Exception $e) {
            error_log("Erreur lors de l'édition de l'album : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue";
            header('Location: /admin/portfolio');
            exit;
        }
    }
}