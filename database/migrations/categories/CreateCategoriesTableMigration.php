<?php
// CreateCategoriesTableMigration.php

namespace Database\Migrations\Categories;

use Database\Interfaces\DatabaseConnectionInterface;
use Database\Migrations\MigrationInterface;
use Database\Migrations;
class CreateCategoriesTableMigration implements MigrationInterface
{
    public function up(DatabaseConnectionInterface $db): void
    {
        $pdo = $db->getConnection();
        echo getcwd();
        $sql = file_get_contents(__DIR__. '/2024_11_30_120922_create_categories_table.sql');
       
        $pdo->exec($sql);
        echo "Categories table created successfully.\n";
    }
    public function insert(DatabaseConnectionInterface $db): void
    {
        $pdo = $db->getConnection();
        $jsonData = file_get_contents('../data/categories.json');

        // Decode JSON data into a PHP array
        $data = json_decode($jsonData, true);
        // Prepare an insert statement
        $stmt = $pdo->prepare("INSERT IGNORE INTO categories (id,name,description,parent_id) VALUES (:id, :name, :description, :parent_id)");
        // Loop through the data array and insert each record
        foreach ($data as $category) {
            $stmt->bindParam(':id', $category['id']);
            $stmt->bindParam(':name', $category['name']);
            $stmt->bindParam(':description', $category['description']);
            $stmt->bindParam(':parent_id', $category['parent']);
            
            
            $stmt->execute();
            echo "Inserted category: " . $category['name'] . " with parent: " . $category['parent'] . "\n";
        }


    }

    public function down(DatabaseConnectionInterface $db): void
    {
        $pdo = $db->getConnection();
        $sql = "DROP TABLE IF EXISTS Categories";
        $pdo->exec($sql);
        echo "Categories table dropped successfully.\n";
    }
}
?>