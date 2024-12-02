<?php
// DatabaseService.php

namespace Database\Services;

use PDO;
use Database\Interfaces\DatabaseConnectionInterface;

class DatabaseService {
    private $dbConnection;

    public function __construct(DatabaseConnectionInterface $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function connectToDatabase(): void {
        $this->dbConnection->connect();
    }

    public function disconnectFromDatabase(): void {
        $this->dbConnection->disconnect();
    }

    public function getConnection(): PDO {
        return $this->dbConnection->getConnection();
    }
}
?>
