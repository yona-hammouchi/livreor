<?php

class Comment extends Database
{
    public function getComments()
    {
        $stmt = $this->pdo->prepare("SELECT comments.comment, comments.date_comment, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        ORDER BY comments.date_comment DESC");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
