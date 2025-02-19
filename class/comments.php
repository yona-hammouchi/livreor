<?php
class Comment
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Ajoute un commentaire à la base de données.
     *
     * @param int $user_id L'ID de l'utilisateur.
     * @param string $comment Le commentaire à ajouter.
     * @return bool Retourne true si le commentaire a été ajouté avec succès.
     */
    public function addComment($user_id, $comment)
    {
        // Valider les entrées
        if (empty($user_id) || empty($comment)) {
            throw new Exception("L'ID de l'utilisateur et le commentaire sont obligatoires.");
        }

        // Préparer et exécuter la requête SQL
        $query = "INSERT INTO comments (user_id, comment, date_comment) VALUES (:user_id, :comment, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Récupère tous les commentaires avec pagination.
     *
     * @param int $page Le numéro de la page.
     * @param int $perPage Le nombre de commentaires par page.
     * @return array Retourne un tableau de commentaires.
     */
    public function getComments($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT comments.*, users.username 
                  FROM comments 
                  JOIN users ON comments.user_id = users.id 
                  ORDER BY date_comment DESC 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le nombre total de commentaires.
     *
     * @return int Retourne le nombre total de commentaires.
     */
    public function getTotalComments()
    {
        $query = "SELECT COUNT(*) as total FROM comments";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function searchComments($keyword, $page = 1, $perPage = 10)
{
    $offset = ($page - 1) * $perPage;

    $query = "SELECT comments.*, users.username 
              FROM comments 
              JOIN users ON comments.user_id = users.id 
              WHERE comment LIKE :keyword 
              ORDER BY date_comment DESC 
              LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTotalSearchComments($keyword)
{
    $query = "SELECT COUNT(*) as total 
              FROM comments 
              WHERE comment LIKE :keyword";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function getUserMessages($user_id)
{
    $query = "SELECT * FROM comments WHERE user_id = :user_id ORDER BY date_comment DESC";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}

