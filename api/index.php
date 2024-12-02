<?php
// index.php



require __DIR__ . '/../vendor/autoload.php';  // Go up one level from 'api' to root

use Database\MySQLConnection;
use Database\Services\DatabaseService;
use Database\Migrations\MigrationRunner;
use Database\Migrations\categories\CreateCategoriesTableMigration;
use Database\Migrations\courses\CreateCoursesTableMigration;
use Dotenv\Dotenv;
use Api\Routes\ApiRoutes;



// // Now you can use your classes and autoloading

// Load the .env file

$dotenv = Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

// Access the variables
$host = $_ENV['DB_HOST'];
$dataBaseName = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// Create a MySQLConnection instance
$mysqlConnection = new MySQLConnection($host,  $dataBaseName  , $user ,    $password);

// Inject it into the DatabaseService
$databaseService = new DatabaseService($mysqlConnection);

// Connect to the database
$databaseService->connectToDatabase();


// Perform some database operations...


// // Create a MigrationRunner instance
// $migrationRunner = new MigrationRunner($mysqlConnection);

// // Add migrations
// $migrationRunner->addMigration(new CreateCategoriesTableMigration());
// $migrationRunner->addMigration(new CreateCoursesTableMigration());

// // Run migrations (apply the "up" methods)
// $migrationRunner->migrate();

// // Run migrations (apply the "insert" methods)
// $migrationRunner->seed();


// Instantiate and execute the routes
$routes = new ApiRoutes($mysqlConnection);

// Optionally, rollback migrations (apply the "down" methods)
// $migrationRunner->rollback();

// Disconnect from the database
// $databaseService->disconnectFromDatabase();
?>
