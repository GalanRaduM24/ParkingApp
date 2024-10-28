<?php
// index.php

$requestUri = $_SERVER['REQUEST_URI'];

// Simple routing based on the request URI
switch ($requestUri) {
    case '/':
    case '/index.php':
        include 'api/Home.php'; // This could be your home API
        break;

    case '/api/other_api':
        include 'api/other_api_files.php';
        break;

    // Add more cases as needed

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
