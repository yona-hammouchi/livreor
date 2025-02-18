<?php
class Inscription extends Database
{
    public function handleInscription($postData)
    {
        $username = trim($postData['username']);
        $password = trim($postData['password']);

        // Vérification de la validité des champs
        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        // Vérifier si l'utilisateur existe déjà
        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            return ['error' => "Le nom d'utilisateur est déjà pris."];
        }

        // Hachage du mot de passe avant insertion
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion de l'utilisateur dans la base de données
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        // Vérification de l'exécution de l'insertion
        if ($stmt->execute()) {
            return ['success' => "Inscription réussie ! Vous pouvez maintenant vous connecter."];
        } else {
            return ['error' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."];
        }
    }
}
