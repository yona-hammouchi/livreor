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

class Connexion extends Database
{
    public function handleLogin($postData)
    {
        // Démarrer la session
        session_start();

        // Nettoyer les données du formulaire
        $username = trim($postData['username']);
        $password = trim($postData['password']);

        // Vérifier que les champs sont remplis
        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        try {
            // Vérifier si l'utilisateur existe
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Vérifier le mot de passe
                if (password_verify($password, $user['password'])) {
                    // Stocker les informations de l'utilisateur dans la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role']; // Si tu as un rôle dans ta table users

                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } elseif ($user['role'] === 'user') {
                        header("Location: profil.php");
                    } else {
                        return ['error' => "Rôle inconnu. Contactez l'administrateur."];
                    }
                } else {
                    return ['error' => "Mot de passe incorrect."];
                }
            } else {
                return ['error' => "Nom d'utilisateur introuvable."];
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données
            return ['error' => "Une erreur s'est produite lors de la connexion. Veuillez réessayer."];
        }
    }
}
