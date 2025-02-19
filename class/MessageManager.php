<?php
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
        // Vérifier que l'utilisateur est bien l'auteur du message
        $query = "SELECT user_id FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->execute();

        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message || $message['user_id'] !== $user_id) {
            return false; // L'utilisateur n'est pas l'auteur du message
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