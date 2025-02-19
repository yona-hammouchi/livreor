<?php
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

<<<<<<< HEAD
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
=======
            if ($user) {
                // Vérifier le mot de passe
                if (password_verify($password, $user['password'])) {
                    // Stocker les informations de l'utilisateur dans la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role']; // Si tu as un rôle dans ta table users

                    // Rediriger vers la page de profil
                    header('Location: profil.php');
                    exit; // Arrêter l'exécution du script après la redirection
                } else {
                    return ['error' => "Mot de passe incorrect."];
                }
>>>>>>> 1719a6a44fdda4391b8247ca561f00cd2ed130e6
            } else {
                return ['error' => "Nom d'utilisateur introuvable."];
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données
            return ['error' => "Une erreur s'est produite lors de la connexion. Veuillez réessayer."];
        }
    }
}