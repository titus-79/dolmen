<?php
namespace Titus\Dolmen\Models;

use Titus\Dolmen\Models\BaseModel;
use PDO;

class Portfolio extends BaseModel
{
    // Définissez le nom de la table principale
    protected $table = 'portfolio';

    // Méthode pour récupérer tous les portfolios avec leurs images
    public function getAllPortfolios()
    {
        $query = "
            SELECT p.*, 
                   pic.id_picture, 
                   pic.nom_picture, 
                   pic.alt_picture, 
                   pic.path_picture
            FROM {$this->table} p
            LEFT JOIN portfolio_picture pp ON p.id_portfolio = pp.id_portfolio
            LEFT JOIN picture pic ON pp.id_picture = pic.id_picture
        ";

        return $this->db->query($query);
    }

    // Récupérer un portfolio spécifique avec ses images
    public function getPortfolioById($id)
    {
        $query = "
            SELECT p.*, 
                   pic.id_picture, 
                   pic.nom_picture, 
                   pic.alt_picture, 
                   pic.path_picture
            FROM {$this->table} p
            LEFT JOIN portfolio_picture pp ON p.id_portfolio = pp.id_portfolio
            LEFT JOIN picture pic ON pp.id_picture = pic.id_picture
            WHERE p.id_portfolio = ?
        ";

        return $this->db->query($query, [$id]);
    }

    // Méthode pour ajouter un nouveau portfolio
    public function createPortfolio($data)
    {
        $query = "
            INSERT INTO {$this->table} 
            (title_portfolio, description_portfolio, slug, created_date, id_user) 
            VALUES (?, ?, ?, ?, ?)
        ";

        return $this->db->query($query, [
            $data['title'],
            $data['description'],
            $data['slug'],
            date('Y-m-d'),
            $data['user_id']
        ]);
    }

    // Ajouter une image à un portfolio
    public function addImageToPortfolio($portfolioId, $pictureId)
    {
        $query = "
            INSERT INTO portfolio_picture 
            (id_portfolio, id_picture) 
            VALUES (?, ?)
        ";

        return $this->db->query($query, [$portfolioId, $pictureId]);
    }

    // Supprimer une image d'un portfolio
    public function removeImageFromPortfolio($portfolioId, $pictureId)
    {
        $query = "
            DELETE FROM portfolio_picture 
            WHERE id_portfolio = ? AND id_picture = ?
        ";

        return $this->db->query($query, [$portfolioId, $pictureId]);
    }

    // Méthode pour mettre à jour un portfolio
    public function updatePortfolio($id, $data)
    {
        $query = "
            UPDATE {$this->table}
            SET title_portfolio = ?, 
                description_portfolio = ?, 
                slug = ?, 
                update_at = ?
            WHERE id_portfolio = ?
        ";

        return $this->db->query($query, [
            $data['title'],
            $data['description'],
            $data['slug'],
            date('Y-m-d'),
            $id
        ]);
    }
}