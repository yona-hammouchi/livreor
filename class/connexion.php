<?php
class Connexion extends Database
{
    public function handleLogin($postData)
    {

        session_start();

        $username = trim($postData['username']);
        $password = trim($postData['password']);

        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        try {

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {

                if (password_verify($password, $user['password'])) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

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

            return ['error' => "Une erreur s'est produite lors de la connexion. Veuillez réessayer."];
        }
    }
}
