<?php
// DatabaseConnectionInterface.php
namespace Database\Interfaces;

use PDO;
interface DatabaseConnectionInterface {
    public function connect(): void;
    public function disconnect(): void;
    public function getConnection(): PDO;
}
?>
