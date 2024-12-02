<?php

namespace App\Models;

use App\Models\Course;
class Category
{
    public $id;
    public $name;
    public $description;
    public $parent_id;
    public $count_of_courses;
    public $created_at;
    public $updated_at;

    public function __construct($id, $name, $description, $parent_id, $count_of_courses, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->parent_id = $parent_id;
        $this->count_of_courses = $count_of_courses;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    // Method to fetch all courses for this category (One-to-Many)
    public function Courses($db)
    {
        $query = "SELECT courses.*, categories.name AS category_name 
          FROM courses
          JOIN categories ON courses.category_id = categories.id
          WHERE courses.category_id = :category_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':category_id', $this->id);
        $stmt->execute();

        $courses = [];
        while ($row = $stmt->fetch()) {
            $courses[] = new Course($row['id'], $row['name'], $row['description'], $row['image_preview'], $row['category_name']);
        }
        return $courses;
    }
    // Optionally, you can add methods to interact with the Category model.
}
?>