<?php
class Connexion extends Database
{
    public function handleLogin($postData)
    {
        $username = trim($postData['username']);
        $password = trim($postData['password']);

        // Vérification que les champs sont remplis
        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        // Vérification si l'utilisateur existe
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                return ['success' => "Connexion réussie !"];
            } else {
                return ['error' => "Mot de passe incorrect."];
            }
        } else {
            return ['error' => "Nom d'utilisateur introuvable."];
        }
    }
}
