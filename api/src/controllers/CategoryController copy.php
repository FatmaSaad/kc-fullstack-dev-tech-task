<?php

// controllers/CategoryController.php
namespace Api\Controllers;

use Database\Interfaces\DatabaseConnectionInterface;
use PDO;
class CategoryController
{
    private $dbConnection;
    private $table = "categories";


    public function __construct(DatabaseConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection->getConnection();
    }
    public function getAllCategories()
    {
        $query = "
        SELECT 
                c.id,
                c.name,
                c.description,
                c.parent_id,
                COUNT(cr.course_id) AS count_of_courses
            FROM 
                " . $this->table . " AS c
            LEFT JOIN 
                courses AS cr 
            ON 
                c.id = cr.category_id
            GROUP BY 
                c.id, c.name, c.description, c.parent_id
        ";

        $stmt = $this->dbConnection->query($query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    }

    public function getCategoryById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category) {
            echo json_encode($category);
        } else {
            echo json_encode(["message" => "Category not found"]);
        }
    }
    function getCategoriesWithHierarchy()
    {
        // Query to fetch categories and their course counts
        $query = "
            SELECT 
                c1.id AS id,
                c1.name AS name,
                c1.parent_id,
                COUNT(c2.course_id) AS count_of_courses
            FROM 
                categories AS c1
            LEFT JOIN 
                courses AS c2 ON c1.id = c2.category_id
            GROUP BY 
                c1.id
        ";

        $stmt = $this->dbConnection->query($query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize categories into a hierarchy
        $hierarchy = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] === null) {
                // Main category
                $hierarchy[$category['id']] = [
                    'name' => $category['name'],
                    'count_of_courses' => $category['count_of_courses'],
                    'subcategories' => []
                ];
            } else {
                // Subcategory
                $hierarchy[$category['parent_id']]['subcategories'][] = [
                    'name' => $category['name'],
                    'count_of_courses' => $category['count_of_courses']
                ];
                if (!isset($hierarchy[$category['parent_id']]['count_of_courses'])) {
                    $hierarchy[$category['parent_id']]['count_of_courses'] = 0;
                }

                $hierarchy[$category['parent_id']]['count_of_courses'] += $category['count_of_courses'];
            }
        }

        echo json_encode($hierarchy);
    }

}
?>