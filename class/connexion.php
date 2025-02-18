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
                // Démarrer une session et stocker les informations de l'utilisateur
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header("Location: pages/admin_dashboard.php");
                } elseif ($user['role'] === 'user') {
                    header("Location: pages/profil.php");
                } else {
                    return ['error' => "Rôle inconnu. Contactez l'administrateur."];
                }
                exit();
            } else {
                return ['error' => "Mot de passe incorrect."];
            }
        } else {
            return ['error' => "Nom d'utilisateur introuvable."];
        }
    }
}
