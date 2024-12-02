<?php
// MigrationRunner.php

namespace Database\Migrations;

use Database\Interfaces\DatabaseConnectionInterface;
class MigrationRunner
{
    private $dbConnection;
    private $migrations;

    public function __construct(DatabaseConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        $this->migrations = [];
    }

    // Add migrations to the runner
    public function addMigration(MigrationInterface $migration): void
    {
        $this->migrations[] = $migration;
    }

    // Run migrations (apply the "up" method)
    public function migrate(): void
    {
        foreach ($this->migrations as $migration) {
            $migration->up($this->dbConnection);
        }
    }
    // Run migrations (apply the "up" method)
    public function seed(): void
    {
        foreach ($this->migrations as $migration) {
            $migration->insert($this->dbConnection);
        }
    }
    // Rollback migrations (apply the "down" method)
    public function rollback(): void
    {
        foreach ($this->migrations as $migration) {
            $migration->down($this->dbConnection);
        }
    }
}
?>