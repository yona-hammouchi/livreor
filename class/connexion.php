<?php
class Connexion extends Database
{
    public function handleLogin($postData)
    {
<<<<<<< HEAD
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
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] === 'user') {
                    header("Location: profil.php");
                } else {
                    return ['error' => "Rôle inconnu. Contactez l'administrateur."];
                }
                exit();
            } else {
                return ['error' => "Mot de passe incorrect."];
            }
        } else {
            return ['error' => "Nom d'utilisateur introuvable."];
=======
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
>>>>>>> 1f531733772bcfdca521951968afc3f6d0f9e18c
        }
    }
}
