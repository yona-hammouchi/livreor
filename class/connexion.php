<?php

class Inscription
{
    private $db;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function handleInscription($postData)
    {
        // Vérifier que les données sont présentes
        $username = trim($postData['username']);
        $password = trim($postData['password']);

        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        // Vérifier que le nom d'utilisateur est unique
        $query = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindParam(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            return ['error' => "Le nom d'utilisateur est déjà pris."];
        }

        // Hasher le mot de passe pour plus de sécurité
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer les données dans la base
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return ['success' => "Inscription réussie ! Vous pouvez maintenant vous connecter."];
        } else {
            return ['error' => "Une erreur est survenue lors de l'inscription. Veuillez réessayer."];
        }
    }
}

class Login
{
    private $db;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function handleLogin($postData)
    {
        // Vérifier que les données sont présentes
        $username = trim($postData['username']);
        $password = trim($postData['password']);

        if (empty($username) || empty($password)) {
            return ['error' => "Tous les champs sont obligatoires."];
        }

        // Vérifier si le nom d'utilisateur existe
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifier si le mot de passe est correct
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
