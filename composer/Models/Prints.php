<?php
namespace Titus\Dolmen\Models;

class Prints extends BaseModel
{
    // Définir le nom de la table
    protected $table = 'print';

    /**
     * Récupère tous les tirages disponibles
     * @return array Liste des tirages
     */
    public function getAllPrints()
{
    $query = "
            SELECT p.*
            FROM {$this->table} p
            ORDER BY p.date_print DESC
        ";

    return $this->db->query($query);
}

    /**
     * Récupère les détails d'un tirage spécifique
     * @param int $id Identifiant du tirage
     * @return array|null Détails du tirage
     */
    public function getPrintById($id)
{
    $query = "
            SELECT p.*, 
                   pic.id_picture, 
                   pic.nom_picture, 
                   pic.path_picture, 
                   pic.alt_picture
            FROM {$this->table} p
            LEFT JOIN print_picture pp ON p.id_print = pp.id_print
            LEFT JOIN picture pic ON pp.id_picture = pic.id_picture
            WHERE p.id_print = ?
        ";

    $results = $this->db->query($query, [$id]);
    return $results ? $results[0] : null;
}

    /**
     * Récupère les images associées à un tirage
     * @param int $printId Identifiant du tirage
     * @return array Images du tirage
     */
    public function getPrintImages($printId)
{
    $query = "
            SELECT pic.*
            FROM picture pic
            JOIN print_picture pp ON pic.id_picture = pp.id_picture
            WHERE pp.id_print = ?
        ";

    return $this->db->query($query, [$printId]);
}

    /**
     * Crée un nouveau tirage
     * @param array $data Données du tirage
     * @return bool Succès de la création
     */
    public function createPrint($data)
{
    $query = "
            INSERT INTO {$this->table} 
            (title_print, state_print, description_print, date_print, created_at) 
            VALUES (?, ?, ?, ?, ?)
        ";

    return $this->db->query($query, [
        $data['title'],
        $data['state'],
        $data['description'],
        $data['date'],
        date('Y-m-d')
    ]);
}

    /**
     * Récupère les tirages disponibles à la vente
     * @return array Liste des tirages en vente
     */
    public function getAvailablePrints()
{
    $query = "
            SELECT p.*
            FROM {$this->table} p
            WHERE p.state_print = 'disponible'
            ORDER BY p.date_print DESC
        ";

    return $this->db->query($query);
}
}