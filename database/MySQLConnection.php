<?php
// MySQLConnection.php

namespace Database;

use PDO;
use PDOException;
use Database\Interfaces\DatabaseConnectionInterface;
class MySQLConnection implements DatabaseConnectionInterface {
    private $pdo;
    private $host;
    private $dataBaseName;
    private $userName;
    private $password;

    public function __construct($host, $dataBaseName, $userName, $password) {
        $this->host = $host;
        $this->dataBaseName = $dataBaseName;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function connect(): void {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dataBaseName};";
           

            $this->pdo = new PDO($dsn, $this->userName, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected to the database successfully!";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            echo '/n';

            
        }
    }

    public function disconnect(): void {
        $this->pdo = null;
        echo "Disconnected from the database.";
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
?>
