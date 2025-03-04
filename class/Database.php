<?php
class Database
{
    private $host = 'localhost';
    private $dbname = 'livreor';
    private $username = 'root';
    private $password = 'root';
    protected $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function createDefaultAdmin()
    {
        $adminUsername = 'admin';
        $adminPassword = 'admin123';
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin'");
        $query->bindParam(':username', $adminUsername);
        $query->execute();

        if ($query->rowCount() == 0) {
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')");
            $stmt->bindParam(':username', $adminUsername);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
