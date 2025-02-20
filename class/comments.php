<?php
class Comment
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function addComment($user_id, $comment)
    {
        if (empty($user_id) || empty($comment)) {
            throw new Exception("L'ID de l'utilisateur et le commentaire sont obligatoires.");
        }

        $query = "INSERT INTO comments (user_id, comment, date_comment) VALUES (:user_id, :comment, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        return $stmt->execute();
    }

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

require_once 'Database.php';

class AdminDashboard
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function getComments($page = 1, $search = "")
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM comments WHERE comment LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotalCommentsCount($search = "")
    {
        $sql = "SELECT COUNT(*) FROM comments WHERE comment LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }


    public function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

class MessageManager
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Modifie un message.
     *
     * @param int $message_id L'ID du message à modifier.
     * @param int $user_id L'ID de l'utilisateur qui essaie de modifier le message.
     * @param string $new_message Le nouveau contenu du message.
     * @return bool Retourne true si la modification a réussi, sinon false.
     */
    public function updateMessage($message_id, $user_id, $new_message)
    {

        $query = "SELECT user_id FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->execute();

        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message || $message['user_id'] !== $user_id) {
            return false;
        }

        // Mettre à jour le message
        $query = "UPDATE comments SET comment = :new_message WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':new_message', $new_message, PDO::PARAM_STR);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Supprime un message.
     *
     * @param int $message_id L'ID du message à supprimer.
     * @param int $user_id L'ID de l'utilisateur qui essaie de supprimer le message.
     * @return bool Retourne true si la suppression a réussi, sinon false.
     */
    public function deleteMessage($message_id, $user_id)
    {
        // Vérifier que l'utilisateur est bien l'auteur du message
        $query = "SELECT user_id FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->execute();

        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message || $message['user_id'] !== $user_id) {
            return false; // L'utilisateur n'est pas l'auteur du message
        }

        // Supprimer le message
        $query = "DELETE FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
