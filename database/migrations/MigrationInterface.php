<?php
// MigrationInterface.php

namespace Database\Migrations;

use Database\Interfaces\DatabaseConnectionInterface;

interface MigrationInterface {
    public function up(DatabaseConnectionInterface $db): void;
    public function insert(DatabaseConnectionInterface $db): void;
    public function down(DatabaseConnectionInterface $db): void;
}
?>
