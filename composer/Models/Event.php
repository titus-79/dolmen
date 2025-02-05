<?php
namespace Titus\Dolmen\Models;

class Event extends BaseModel
{
    // Définir le nom de la table
    protected $table = 'event';

    /**
     * Récupère tous les événements
     * @return array Liste des événements
     */
    public function getAllEvents()
    {
        $query = "
            SELECT e.*, a.city_adress, a.country_adress
            FROM {$this->table} e
            LEFT JOIN adress a ON e.id_adress = a.id_adress
            ORDER BY e.date_event ASC
        ";

        return $this->db->query($query);
    }

    /**
     * Récupère un événement par son ID
     * @param int $id Identifiant de l'événement
     * @return array|null Détails de l'événement
     */
    public function getEventById($id)
    {
        $query = "
            SELECT e.*, a.city_adress, a.country_adress, a.street_adress
            FROM {$this->table} e
            LEFT JOIN adress a ON e.id_adress = a.id_adress
            WHERE e.id_event = ?
        ";

        $results = $this->db->query($query, [$id]);
        return $results ? $results[0] : null;
    }

    /**
     * Crée un nouvel événement
     * @param array $data Données de l'événement
     * @return bool Succès de la création
     */
    public function createEvent($data)
    {
        $query = "
            INSERT INTO {$this->table} 
            (title_event, description_event, author_even, date_event, created_at, id_adress) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        return $this->db->query($query, [
            $data['title'],
            $data['description'],
            $data['author'],
            $data['date'],
            date('Y-m-d'),
            $data['address_id']
        ]);
    }

    /**
     * Met à jour un événement existant
     * @param int $id Identifiant de l'événement
     * @param array $data Nouvelles données de l'événement
     * @return bool Succès de la mise à jour
     */
    public function updateEvent($id, $data)
    {
        $query = "
            UPDATE {$this->table}
            SET title_event = ?, 
                description_event = ?, 
                author_even = ?, 
                date_event = ?, 
                update_at = ?,
                id_adress = ?
            WHERE id_event = ?
        ";

        return $this->db->query($query, [
            $data['title'],
            $data['description'],
            $data['author'],
            $data['date'],
            date('Y-m-d'),
            $data['address_id'],
            $id
        ]);
    }

    /**
     * Supprime un événement
     * @param int $id Identifiant de l'événement
     * @return bool Succès de la suppression
     */
    public function deleteEvent($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id_event = ?";
        return $this->db->query($query, [$id]);
    }

    /**
     * Récupère les événements à venir
     * @return array Liste des événements futurs
     */
    public function getUpcomingEvents()
    {
        $query = "
            SELECT e.*, a.city_adress, a.country_adress
            FROM {$this->table} e
            LEFT JOIN adress a ON e.id_adress = a.id_adress
            WHERE e.date_event >= CURRENT_DATE
            ORDER BY e.date_event ASC
        ";

        return $this->db->query($query);
    }
}