<?php
namespace Titus\Dolmen\Models;

use PDO;
use PDOException;

class Portfolio extends BaseModel
{
    protected $table = 'albums';

    /**
     * Récupère tous les albums principaux avec leurs sous-albums
     * @return array Structure hiérarchique des albums
     */
    public function getAllAlbums(): array
    {
        try {
            error_log("Début de getAllAlbums()");

            // Requête pour vérifier le nombre total d'albums
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table}";
            $totalCount = $this->db->query($countQuery);
            error_log("Nombre total d'albums dans la base : " . $totalCount[0]['total']);

            $query = "
            SELECT id, title, thumbnail_path, region, description
            FROM {$this->table}
            WHERE parent_id IS NULL
            ORDER BY region, title
        ";

            error_log("Requête SQL : " . $query);

            // Utilisation directe de PDO pour s'assurer d'avoir un tableau
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();
            $mainAlbums = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log("Résultat de la requête : " . print_r($mainAlbums, true));

            // Pour chaque album principal, récupère ses sous-albums
            foreach ($mainAlbums as &$album) {
                // Vérification explicite de l'ID avant de chercher les sous-albums
                if (isset($album['id'])) {
                    $album['subAlbums'] = $this->getSubAlbums((int)$album['id']);
                    error_log("Sous-albums ajoutés pour l'album {$album['id']}");
                } else {
                    $album['subAlbums'] = [];
                    error_log("Pas d'ID trouvé pour un album principal");
                }            }

            return $mainAlbums;

        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des albums : " . $e->getMessage());
            return [];
        }
    }
    /**
     * Récupère les sous-albums d'un album donné
     * @param int $parentId ID de l'album parent
     * @return array Liste des sous-albums
     */
    public function getSubAlbums(int $parentId): array
    {
        try {
            // Log pour le débogage
            error_log("Récupération des sous-albums pour l'ID parent: " . $parentId);

            $query = "
            SELECT id, title, thumbnail_path, description
            FROM {$this->table}
            WHERE parent_id = ?
            ORDER BY title
        ";

            // Utilisation de PDO directement pour s'assurer du type de retour
            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute([$parentId]);
            $subAlbums = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Log du résultat
            error_log("Sous-albums trouvés : " . print_r($subAlbums, true));

            // Assurons-nous de toujours retourner un tableau, même vide
            return $subAlbums ?: [];

        } catch (PDOException $e) {
            // En cas d'erreur, on log et on retourne un tableau vide
            error_log("Erreur lors de la récupération des sous-albums : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les détails d'un album spécifique avec ses photos
     * @param int $albumId ID de l'album
     * @return array|null Détails de l'album et ses photos
     */
    public function getAlbumWithPhotos(int $albumId): ?array
    {
        try {
            error_log("Récupération de l'album ID: " . $albumId);

            // D'abord, récupérer les informations de l'album
            $albumQuery = "
            SELECT a.*, parent.title as parent_title, parent.id as parent_id
            FROM {$this->table} a 
            LEFT JOIN {$this->table} parent ON a.parent_id = parent.id
            WHERE a.id = ?
        ";

            $stmt = $this->db->getConnection()->prepare($albumQuery);
            $stmt->execute([$albumId]);
            $album = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$album) {
                error_log("Aucun album trouvé avec l'ID: " . $albumId);
                return null;
            }

            // Vérifier si la colonne display_order existe
            $checkColumnQuery = "
            SELECT COUNT(*) 
            FROM information_schema.COLUMNS 
            WHERE TABLE_NAME = 'album_picture' 
            AND COLUMN_NAME = 'display_order'
        ";
            $stmt = $this->db->getConnection()->query($checkColumnQuery);
            $hasDisplayOrder = (bool)$stmt->fetchColumn();

            // Construire la requête des photos en fonction de la présence de display_order
            $photoQuery = "
            SELECT p.*
            FROM picture p
            JOIN album_picture ap ON p.id_picture = ap.id_picture
            WHERE ap.id_album = ?
            ORDER BY " . ($hasDisplayOrder ? "ap.display_order" : "p.created_at") . " ASC
        ";

            $stmt = $this->db->getConnection()->prepare($photoQuery);
            $stmt->execute([$albumId]);
            $photos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            error_log("Photos récupérées pour l'album $albumId : " . print_r($photos, true));

            // Ajouter les photos à l'album
            $album['photos'] = $photos;

            return $album;

        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'album : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crée un nouvel album
     * @param array $data Données de l'album
     * @return bool Succès de la création
     */
    public function createAlbum(array $data): bool
    {
        try {
            // Log pour débogage
            error_log("Tentative de création d'album avec les données : " . print_r($data, true));

            // Validation des données requises
            if (empty($data['title'])) {
                throw new \PDOException("Le titre est requis");
            }

            // Gestion de la région
            $region = $data['region'];
            if ($data['region'] === 'nouvelle' && !empty($data['new_region'] ?? '')) {
                $region = $data['new_region'];
            }

            $query = "
            INSERT INTO {$this->table} 
            (title, description, region, parent_id, thumbnail_path, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ";

            // Log de la requête et des paramètres
            error_log("Requête SQL préparée : " . $query);
            error_log("Paramètres : " . print_r([
                    $data['title'],
                    $data['description'] ?? '',
                    $region,
                    $data['parent_id'] ?: null,
                    $data['thumbnail_path']
                ], true));

            // Préparation de la requête
            $stmt = $this->db->getConnection()->prepare($query);

            // Exécution de la requête
            $result = $stmt->execute([
                $data['title'],
                $data['description'] ?? '',
                $region,
                $data['parent_id'] ?: null,
                $data['thumbnail_path']
            ]);

            error_log("Résultat de la création : " . ($result ? 'succès' : 'échec'));

            error_log("Création de l'album réussie");
//            return $result;
            return (int)$this->db->getConnection()->lastInsertId();


        } catch (\PDOException $e) {
            error_log("Erreur lors de la création de l'album : " . $e->getMessage());
            error_log("Trace complète : " . $e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * Associe une photo à un album
     * @param int $albumId ID de l'album
     * @param int $photoId ID de la photo
     * @return bool Succès de l'association
     */
    public function addPhotosToAlbum(int $albumId, array $files): array
    {
        try {
            error_log("Début de addPhotosToAlbum pour l'album $albumId");
            error_log("Fichiers reçus : " . print_r($files, true));

            $uploadDir = '/var/www/html/public/uploads/portfolio/photos/';
            $succeededUploads = [];

            // Création du répertoire si nécessaire
            if (!is_dir($uploadDir)) {
                error_log("Création du répertoire $uploadDir");
                mkdir($uploadDir, 0777, true);
            }

            $this->db->getConnection()->beginTransaction();

            // Traitement de chaque fichier
            foreach ($files['name'] as $key => $originalName) {
                if (empty($originalName)) continue; // Skip empty entries

                if ($files['error'][$key] !== UPLOAD_ERR_OK) {
                    error_log("Erreur upload pour le fichier $originalName : " . $files['error'][$key]);
                    continue;
                }

                $filename = uniqid('photo_') . '_' . time() . '_' . $key . '.' .
                    pathinfo($originalName, PATHINFO_EXTENSION);

                $uploadPath = $uploadDir . $filename;

                if (move_uploaded_file($files['tmp_name'][$key], $uploadPath)) {
                    // Insertion dans la table picture
                    $stmt = $this->db->getConnection()->prepare("
                    INSERT INTO picture (
                        nom_picture, 
                        alt_picture,
                        path_picture, 
                        auteur_picture,
                        texte_picture,
                        created_at,
                        id_adress
                    ) VALUES (?, ?, ?, ?, ?, NOW(), 1)
                ");

                    $stmt->execute([
                        $originalName,
                        "Photo de l'album",
                        '/uploads/portfolio/photos/' . $filename,
                        "Chasseur de Dolmens",
                        "Description de la photo"
                    ]);

                    $photoId = $this->db->getConnection()->lastInsertId();

                    // Association avec l'album
                    $stmt = $this->db->getConnection()->prepare("
                    INSERT INTO album_picture (id_album, id_picture)
                    VALUES (?, ?)
                ");

                    $stmt->execute([$albumId, $photoId]);
                    $succeededUploads[] = $photoId;
                }
            }

            if (!empty($succeededUploads)) {
                $this->db->getConnection()->commit();
                return $succeededUploads;
            }

            $this->db->getConnection()->rollBack();
            return [];

        } catch (\Exception $e) {
            error_log("Erreur dans addPhotosToAlbum : " . $e->getMessage());
            if ($this->db->getConnection()->inTransaction()) {
                $this->db->getConnection()->rollBack();
            }
            throw $e;
        }
    }

    public function uploadThumbnail(array $file): ?string
    {
        try {
            $uploadDir = '/var/www/html/public/uploads/portfolio/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = uniqid() . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Retourner un chemin relatif pour la base de données
                return '/uploads/portfolio/' . $filename;
            }

            return null;
        } catch (\Exception $e) {
            error_log("Erreur lors de l'upload du thumbnail : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère toutes les régions distinctes des albums
     * @return array Liste des régions
     */
    public function getAllRegions(): array
    {
        try {
            $query = "
            SELECT DISTINCT region 
            FROM {$this->table} 
            WHERE region IS NOT NULL AND region != ''
            ORDER BY region
        ";

            $stmt = $this->db->getConnection()->prepare($query);
            $stmt->execute();

            // Récupérer les résultats dans un format approprié
            $regions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            error_log("Régions trouvées : " . print_r($regions, true));

            return $regions;

        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des régions : " . $e->getMessage());
            return [];
        }
    }

    public function deleteAlbum(int $albumId): bool
    {
        try {
            error_log("Tentative de suppression de l'album ID: " . $albumId);

            // Début de la transaction
            $this->db->getConnection()->beginTransaction();

            // 1. Suppression des relations album_picture
            $queryDeletePictures = "DELETE FROM album_picture WHERE id_album = ?";
            $stmtPictures = $this->db->getConnection()->prepare($queryDeletePictures);
            $stmtPictures->execute([$albumId]);
            error_log("Relations album_picture supprimées");

            // 2. Mise à jour des sous-albums (mettre parent_id à NULL)
            $queryUpdateSubAlbums = "UPDATE {$this->table} SET parent_id = NULL WHERE parent_id = ?";
            $stmtSubAlbums = $this->db->getConnection()->prepare($queryUpdateSubAlbums);
            $stmtSubAlbums->execute([$albumId]);
            error_log("Sous-albums mis à jour");

            // 3. Suppression de l'album lui-même
            $queryDeleteAlbum = "DELETE FROM {$this->table} WHERE id = ?";
            $stmtAlbum = $this->db->getConnection()->prepare($queryDeleteAlbum);
            $result = $stmtAlbum->execute([$albumId]);
            error_log("Album supprimé");

            // Si tout s'est bien passé, on valide la transaction
            if ($result) {
                $this->db->getConnection()->commit();
                error_log("Suppression réussie - Transaction validée");
                return true;
            }

            // En cas d'échec, on annule la transaction
            $this->db->getConnection()->rollBack();
            error_log("Échec de la suppression - Transaction annulée");
            return false;

        } catch (PDOException $e) {
            // En cas d'erreur, on annule la transaction
            if ($this->db->getConnection()->inTransaction()) {
                $this->db->getConnection()->rollBack();
            }
            error_log("Erreur lors de la suppression de l'album : " . $e->getMessage());
            throw $e;
        }
    }

    public function removePhotoFromAlbum(int $albumId, int $photoId): bool {
        try {
            $conn = $this->db->getConnection();
            $conn->beginTransaction();

            // Récupérer le chemin du fichier avant la suppression
            $stmt = $conn->prepare("
                SELECT path_picture 
                FROM picture 
                WHERE id_picture = ?
            ");
            $stmt->execute([$photoId]);
            $photo = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Supprimer l'association album-photo
            $stmt = $conn->prepare("
                DELETE FROM album_picture 
                WHERE id_album = ? AND id_picture = ?
            ");
            $stmt->execute([$albumId, $photoId]);

            // Supprimer la photo de la base de données
            $stmt = $conn->prepare("
                DELETE FROM picture 
                WHERE id_picture = ?
            ");
            $stmt->execute([$photoId]);

            // Supprimer le fichier physique
            if ($photo && file_exists($_SERVER['DOCUMENT_ROOT'] . $photo['path_picture'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $photo['path_picture']);
            }

            $conn->commit();
            return true;

        } catch (\Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Erreur lors de la suppression de la photo : " . $e->getMessage());
            return false;
        }
    }

    public function updatePhotosOrder(int $albumId, array $order): bool {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("
                UPDATE album_picture 
                SET display_order = ? 
                WHERE id_album = ? AND id_picture = ?
            ");

            foreach ($order as $position => $photoId) {
                $stmt->execute([$position, $albumId, $photoId]);
            }

            return true;
        } catch (\Exception $e) {
            error_log("Erreur lors de la mise à jour de l'ordre des photos : " . $e->getMessage());
            return false;
        }
    }

    private function validateImagePath(string $path): string
    {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
        if (!file_exists($fullPath)) {
            error_log("Image non trouvée : " . $fullPath);
            return '/assets/images/placeholder.jpg'; // Chemin vers une image par défaut
        }
        return $path;
    }

    public function updateAlbum(int $albumId, array $data): bool
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // Mise à jour des informations de base de l'album
            $query = "
            UPDATE {$this->table} 
            SET title = ?,
                description = ?,
                region = ?,
                updated_at = NOW()
            WHERE id = ?
        ";

            $stmt = $this->db->getConnection()->prepare($query);
            $success = $stmt->execute([
                $data['title'],
                $data['description'] ?? '',
                $data['region'],
                $albumId
            ]);

            // Si une nouvelle image de couverture est fournie
            if (!empty($data['thumbnail_path'])) {
                $query = "UPDATE {$this->table} SET thumbnail_path = ? WHERE id = ?";
                $stmt = $this->db->getConnection()->prepare($query);
                $success = $stmt->execute([$data['thumbnail_path'], $albumId]) && $success;
            }

            // Si la mise à jour a réussi, on valide la transaction
            if ($success) {
                $this->db->getConnection()->commit();
                return true;
            }

            // En cas d'échec, on annule
            $this->db->getConnection()->rollBack();
            return false;

        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'album : " . $e->getMessage());
            if ($this->db->getConnection()->inTransaction()) {
                $this->db->getConnection()->rollBack();
            }
            throw $e;
        }
    }
}