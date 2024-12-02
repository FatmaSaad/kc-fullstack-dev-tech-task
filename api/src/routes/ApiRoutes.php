<?php

// routes/routes.php
namespace Api\Routes;

use Database\Interfaces\DatabaseConnectionInterface;
use Api\Controllers\CategoryController;
use Api\Controllers\CourseController;
use Api\Controllers\DatabaseController;
use Api\Utils\Request;
class ApiRoutes
{
    private $dbConnection;
    public function __construct(DatabaseConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        // Initialize the routing logic

        $this->handleRoutes();
    }

    public function handleRoutes(): void
    {
        // Routing logic
        $request = new Request();
        $request->allowResource();
        $requestUri = $request->getUri();
        $requestMethod = $request->getMethod();

        // Instantiate controllers with database connection

        $categoryController = new CategoryController($this->dbConnection);
        $courseController = new CourseController($this->dbConnection);
        // Handle routes
        switch ($requestUri) {

            case (preg_match('/^courses\/all\?category_id=[a-zA-Z0-9-]+$/', $requestUri) && isset($_GET['category_id'])):
                if ($requestMethod == 'GET') {
                    // Parse query parameters to get category_id
                    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);
                    $categoryId = $queryParams['category_id'] ?? null;

                    $courseController->getCoursesByCategoryIdAll($categoryId);

                }
                break;
            case (preg_match('/^courses\?category_id=[a-zA-Z0-9-]+$/', $requestUri) && isset($_GET['category_id'])):

                if ($requestMethod == 'GET') {
                    // Parse query parameters to get category_id
                    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);
                    $categoryId = $queryParams['category_id'] ?? null;

                    $courseController->getCoursesByCategoryId($categoryId);

                }
                break;

            case (preg_match('/^courses$/', $requestUri) ? true : false):

                if ($requestMethod == 'GET') {
                    $courseController->getAllCourses();
                }
                break;
            case (preg_match('/^courses\/[a-zA-Z0-9\-]+$/', $requestUri) ? true : false):

                $courseId = explode('/', $requestUri)[1];
                if ($requestMethod == 'GET') {
                    $courseController->getCourseById($courseId);
                }
                break;
            case (preg_match('/^categories\/[a-zA-Z0-9\-]+$/', $requestUri) ? true : false):
                $categoryId = explode('/', $requestUri)[1];
                if ($requestMethod == 'GET') {
                    $categoryController->getCategoryById($categoryId);
                }
                break;
            case 'categories':
                if ($requestMethod == 'GET') {
                    $categoryController->getAllCategories();
                }
                break;

            case 'categories-hierarchy':
                if ($requestMethod == 'GET') {
                    $categoryController->getCategoriesWithHierarchy();
                }
                break;
            case 'migrate--seed':
                if ($requestMethod == 'GET') {
                    $databaseController = new DatabaseController($this->dbConnection);

                }
                break;
            default:

                http_response_code(404);
                break;
        }
    }
}

?>