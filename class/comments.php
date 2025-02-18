<?php
class Comment {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Ajouter un commentaire
    public function addComment($user_id, $comment) {
        $query = "INSERT INTO comments (user_id, comment, date_comment) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $comment]);
    }

    // Récupérer tous les commentaires avec pagination
    public function getComments($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT comments.*, users.username 
                   FROM comments 
                   JOIN users ON comments.user_id = users.id 
                   ORDER BY date_comment DESC 
                   LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer le nombre total de commentaires (pour la pagination)
    public function getTotalComments() {
        $query = "SELECT COUNT(*) as total FROM comments";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Rechercher des commentaires par mot-clé
    public function searchComments($keyword, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT comments.*, users.username 
                  FROM comments 
                  JOIN users ON comments.user_id = users.id 
                  WHERE comment LIKE ? 
                  ORDER BY date_comment DESC 
                  LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$keyword%", $perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer le nombre total de commentaires pour la recherche (pagination)
    public function getTotalSearchComments($keyword) {
        $query = "SELECT COUNT(*) as total 
                  FROM comments 
                  WHERE comment LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}

?>