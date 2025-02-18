<?php
class Inscription extends Database
{
    public function handleInscription($postData)
    {
        $username = trim($postData['username']);
        $password = trim($postData['password']);
        $role = isset($postData['role']) ? trim($postData['role']) : 'user';

        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        if (!in_array($role, ['user', 'admin'])) {
            return ['error' => "Rôle invalide."];
        }

        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            return ['error' => "Le nom d'utilisateur est déjà pris."];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return ['success' => "Inscription réussie en tant que " . htmlspecialchars($role) . " !"];
        } else {
            return ['error' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."];
        }
    }
}
