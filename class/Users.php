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
