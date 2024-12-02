<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Course
{
    public $id;
    public $name;
    public $description;
    public $image_preview;
    public $category_id;
    public $category_name;
    public $created_at;
    public $updated_at;

    public function __construct($id, $name, $description, $image_preview, $category_id, $category_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image_preview = $image_preview;
        $this->category_id = $category_id;
        $this->category_name = $category_name;

    }
    // Method to fetch the category that this course belongs to (Many-to-One)
    public function Category($db)
    {
        $query = "SELECT * FROM categories WHERE id = :category_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            return new Category($row['id'], $row['name'], $row['description'], $row['parent_id'], $row['count_of_courses'], $row['created_at'], $row['updated_at']);
        }
        return null;
    }
    /**
     * Get courses by category ID
     * 
     * @param string $categoryId
     * @return array
     */
    public static function getByCategoryId($categoryId)
    {
        $db = DB::getConnection(); // Database connection
        $query = "SELECT courses.*, categories.name AS category_name 
                  FROM courses 
                  JOIN categories ON courses.category_id = categories.id 
                  WHERE courses.category_id = :category_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get all courses with their category names
     * 
     * @return array
     */
    public static function getAll()
    {
        $db = DB::getConnection(); // Database connection
        $query = "SELECT courses.*, categories.name AS category_name 
                  FROM courses 
                  JOIN categories ON courses.category_id = categories.id";

        $stmt = $db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>