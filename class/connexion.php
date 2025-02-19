<?php
class Connexion extends Database
{
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

                    // Rediriger vers la page de profil
                    header('Location: profil.php');
                    exit; // Arrêter l'exécution du script après la redirection
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