<?php
// CreateCoursesTableMigration.php

namespace Database\Migrations\Courses;

use Database\Interfaces\DatabaseConnectionInterface;
use Database\Migrations\MigrationInterface;
class CreateCoursesTableMigration implements MigrationInterface {
    public function up(DatabaseConnectionInterface $db): void {
        $pdo = $db->getConnection();
        $sql = file_get_contents(__DIR__.'/2024_11_30_121442_create_courses_table.sql');

        $pdo->exec($sql);
        echo "Courses table created successfully.\n";
    }
    public function insert(DatabaseConnectionInterface $db): void
    {
        $pdo = $db->getConnection();
        $jsonData = file_get_contents('../data/course_list.json');

        // Decode JSON data into a PHP array
        $data = json_decode($jsonData, true);
        // Prepare an insert statement
        $stmt = $pdo->prepare("INSERT IGNORE INTO courses (course_id,title,description,image_preview,category_id) VALUES (:course_id, :title, :description, :image_preview, :category_id)");

        // Loop through the data array and insert each record
        foreach ($data as $course) {
            $stmt->bindParam(':course_id', $course['course_id']);
            $stmt->bindParam(':title', $course['title']);
            $stmt->bindParam(':description', $course['description']);
            $stmt->bindParam(':image_preview', $course['image_preview']);
            $stmt->bindParam(':category_id', $course['category_id']);
            
            $stmt->execute();
            echo "Inserted course: " . $course['title'] . "\n";
        }



    }
    public function down(DatabaseConnectionInterface $db): void {
        $pdo = $db->getConnection();
        $sql = "DROP TABLE IF EXISTS courses";
        $pdo->exec($sql);
        echo "Courses table dropped successfully.\n";
    }
}
?>
