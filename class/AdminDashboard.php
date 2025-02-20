<?php
require_once 'Database.php';

class AdminDashboard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // On instancie la classe Database
    }

    /**
     * Récupère tous les commentaires avec la pagination.
     */
    public function getComments($page = 1, $search = "")
    {
        $limit = 10;  // Nombre de commentaires par page
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM comments WHERE comment LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre total de commentaires pour la pagination.
     */
    public function getTotalCommentsCount($search = "")
    {
        $sql = "SELECT COUNT(*) FROM comments WHERE comment LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Supprime un commentaire spécifique.
     */
    public function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
