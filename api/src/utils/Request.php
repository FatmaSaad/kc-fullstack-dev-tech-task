<?php

// utils/Request.php
namespace Api\Utils;

class Request
{

    // Constructor to automatically add CORS header
    public function __construct()
    {

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Respond with 200 status code to preflight requests
            header("HTTP/1.1 200 OK");
            exit();
        }
    }
    public static function allowResource()
    {
        // Allow all origins to access the resource
        ob_start();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

    }
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getUri()
    {
        return trim($_SERVER['REQUEST_URI'], '/'); // Remove leading/trailing slashes
    }

    public static function getParams()
    {
        $params = [];
        if (self::getMethod() == 'GET') {
            $params = $_GET;
        } elseif (self::getMethod() == 'POST') {
            $params = $_POST;
        }
        return $params;
    }

    public static function getParam($key)
    {
        return isset(self::getParams()[$key]) ? self::getParams()[$key] : null;
    }
}

?>