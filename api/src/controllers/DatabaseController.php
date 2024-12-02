<?php

// controllers/DatabaseController.php
namespace Api\Controllers;
use Database\Interfaces\DatabaseConnectionInterface;
use Database\Migrations\MigrationRunner;
use Database\Migrations\Categories\CreateCategoriesTableMigration;
use Database\Migrations\Courses\CreateCoursesTableMigration;
use Database\Services\DatabaseService;
require __DIR__ . '/../index.php';  // Go up one level from 'api' to root

class DatabaseController
{
    private $migrationRunner;
    private $dbConnection;
    public function __construct(DatabaseConnectionInterface $databaseService)
    {
        // Create a MigrationRunner instance using the database connection
        $this->migrationRunner = new MigrationRunner($databaseService);


        // Automatically call the methods upon object instantiation
        $this->migrate();   // Run migrations the database
        $this->seed();   // Run seed the database


    }
    public function migrate()
    {



        // Add migrations
        $this->migrationRunner->addMigration(new CreateCategoriesTableMigration());
        $this->migrationRunner->addMigration(new CreateCoursesTableMigration());

        // Run migrations (apply the "up" methods)
        $this->migrationRunner->migrate();

        echo "Migrations  executed successfully!";
    }
    public function seed()
    {
        // Optionally, run seeders (apply the "insert" methods)
        $this->migrationRunner->seed();

        // Optionally, rollback migrations (apply the "down" methods)
        // $this->migrationRunner->rollback();

        echo " Seeders executed successfully!";
    }

    public function rollback()
    {
        // Optionally, rollback migrations (apply the "down" methods)
        $this->migrationRunner->rollback();

        echo " rollback executed successfully!";
    }
}
