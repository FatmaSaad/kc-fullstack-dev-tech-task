<?php

// controllers/CourseController.php
namespace Api\Controllers;

use Database\Interfaces\DatabaseConnectionInterface;
use PDO;
class CourseController
{
    private $dbConnection;
    private $table = "courses";

    public function __construct(DatabaseConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection->getConnection();
    }
    public function getAllCourses()
    {
        $query = "SELECT courses.*, categories.name AS category_name 
        FROM courses
        JOIN categories ON courses.category_id = categories.id";
        $stmt = $this->dbConnection->query($query);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($courses);
    }

    public function getCourseById($id)
    {
        $query = "SELECT 
              courses.course_id AS id,
              courses.title AS name,
              courses.description,
              courses.image_preview AS preview,
              categories.name AS main_category_name,
              courses.created_at,
              courses.updated_at
          FROM courses
          JOIN categories ON courses.category_id = categories.id
          WHERE courses.course_id = :course_id";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":course_id", $id);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($course) {
            echo json_encode($course);
        } else {
            echo json_encode(["message" => "Course not found"]);
        }
    }
    public function getCoursesByCategoryId($categoryId)
    {

        // SQL query to fetch courses for the given category ID
        $query = "SELECT courses.*, categories.name AS category_name 
                  FROM courses
                  JOIN categories ON courses.category_id = categories.id
                  WHERE courses.category_id = :category_id";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Return as JSON response
        header('Content-Type: application/json');
        if ($courses) {
            echo json_encode($courses);
        } else {
            echo json_encode(["message" => "Courses not found"]);
        }

    }
    //returns courses for the specified category and all its subcategories (including nested subcategories
    public function getCoursesByCategoryIdAll($categoryId)
    {
    // SQL query to fetch courses for the given category ID
    $query = "WITH RECURSIVE CategoryHierarchy AS (
    -- Start with the given category ID
    SELECT id 
    FROM categories 
    WHERE id = :category_id
    UNION ALL
    -- Recursively find all subcategories
    SELECT c.id 
    FROM categories c
    INNER JOIN CategoryHierarchy ch ON c.parent_id = ch.id
)
SELECT 
    courses.*, 
    categories.name AS category_name 
FROM 
    courses
JOIN 
    categories ON courses.category_id = categories.id
WHERE 
    courses.category_id IN (SELECT id FROM CategoryHierarchy);
";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Return as JSON response
        header('Content-Type: application/json');
        if ($courses) {
            echo json_encode($courses);
        } else {
            echo json_encode(["message" => "Courses not found"]);
        }

    }

}
?>