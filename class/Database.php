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
            echo 'Connexion réussie à la base de données!<br>';
        } catch (PDOException $e) {
            die('Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
