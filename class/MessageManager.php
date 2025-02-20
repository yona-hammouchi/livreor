<?php
class MessageManager
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

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

        $query = "UPDATE comments SET comment = :new_message WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':new_message', $new_message, PDO::PARAM_STR);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteMessage($message_id, $user_id)
    {

        $query = "SELECT user_id FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);
        $stmt->execute();

        $message = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$message || $message['user_id'] !== $user_id) {
            return false;
        }

        $query = "DELETE FROM comments WHERE id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
