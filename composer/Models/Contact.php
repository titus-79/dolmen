<?php
namespace Titus\Dolmen\Models;

class Contact extends BaseModel
{
    // Définir le nom de la table
    protected $table = 'contact_messages';

    /**
     * Crée un nouveau message de contact
     *
     * Méthode qui insère un nouveau message dans la base de données
     *
     * @param array $data Données du message
     * @return bool Succès de l'insertion
     */
    public function createMessage($data)
    {
        $query = "
            INSERT INTO {$this->table} 
            (name, email, phone, subject, message, created_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        return $this->db->query($query, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['subject'],
            $data['message'],
            date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Récupère les messages de contact
     *
     * @return array Liste des messages
     */
    public function getAllMessages()
    {
        $query = "
            SELECT * 
            FROM {$this->table} 
            ORDER BY created_at DESC
        ";

        return $this->db->query($query);
    }

    /**
     * Vérifie la validité des données de contact
     *
     * @param array $data Données à valider
     * @return array Tableau des erreurs
     */
    public function validateContactData($data)
    {
        $errors = [];

        // Validation du nom
        if (empty($data['name'])) {
            $errors['name'] = 'Le nom est requis';
        }

        // Validation de l'email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir un email valide';
        }

        // Validation du sujet
        if (empty($data['subject'])) {
            $errors['subject'] = 'Le sujet est requis';
        }

        // Validation du message
        if (empty($data['message']) || strlen($data['message']) < 10) {
            $errors['message'] = 'Le message doit contenir au moins 10 caractères';
        }

        return $errors;
    }
}