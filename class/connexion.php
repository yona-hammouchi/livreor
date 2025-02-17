<?php

class Inscription
{
    private $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur : Impossible de se connecter à la base de données. " . $e->getMessage());
        }
    }

    public function handleInscription($postData)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($postData['username']);
            $password = $postData['password'];

            $error = $this->validateInputs($username, $password); // Modifié pour passer 2 paramètres seulement
            if ($error) {
                return ['error' => $error];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $this->registerUser($username, $hashed_password);

                // Redirection après une inscription réussie
                header("Location: ../pages/login.php");
                exit; // Stop l'exécution après redirection
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') {
                    return ['error' => "Le nom d'utilisateur existe déjà."];
                } else {
                    return ['error' => "Erreur lors de l'inscription : " . $e->getMessage()];
                }
            }
        }
    }

    // Méthode validateInputs corrigée pour ne vérifier que le username et le password
    private function validateInputs($username, $password)
    {
        if (empty($username) || empty($password)) {
            return "Tous les champs sont obligatoires.";
        }

        return null;
    }

    private function registerUser($username, $hashed_password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            ':username' => $username,
            ':password' => $hashed_password
        ]);
    }
}



class Login
{
    private $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur : Impossible de se connecter à la base de données. " . $e->getMessage());
        }
    }

    public function handleLogin($postData)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($postData['email']);
            $password = $postData['password'];

            if (empty($email) || empty($password)) {
                return ['error' => "Tous les champs sont obligatoires."];
            }

            try {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Connexion réussie
                    return ['success' => "Connexion réussie !"];
                } else {
                    return ['error' => "Email ou mot de passe incorrect."];
                }
            } catch (PDOException $e) {
                return ['error' => "Erreur lors de la connexion : " . $e->getMessage()];
            }
        }
    }
}
