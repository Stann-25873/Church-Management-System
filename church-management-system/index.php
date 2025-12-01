<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Router;

// Handle static files
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $requestUri)) {
    $filePath = __DIR__ . '/public' . $requestUri;
    if (file_exists($filePath)) {
        // Set appropriate MIME types
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml'
        ];
        
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (isset($mimeTypes[$ext])) {
            header('Content-Type: ' . $mimeTypes[$ext]);
        }
        readfile($filePath);
        exit;
    }
}

// Start session
session_start();

// Initialize router
$router = new Router();

// Define routes
$router->add('GET', '/', 'AuthController', 'login');
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'authenticate');
$router->add('GET', '/logout', 'AuthController', 'logout');
$router->add('POST', '/logout', 'AuthController', 'logout');

// Main page with all content in tabs
$router->add('GET', '/home', 'HomeController', 'index');

$router->add('GET', '/dashboard', 'DashboardController', 'index');

$router->add('GET', '/members', 'MemberController', 'index');
$router->add('GET', '/members/add', 'MemberController', 'add');
$router->add('POST', '/members/store', 'MemberController', 'store');
$router->add('GET', '/members/{id}', 'MemberController', 'show');
$router->add('GET', '/members/{id}/edit', 'MemberController', 'edit');
$router->add('POST', '/members/{id}/update', 'MemberController', 'update');
$router->add('POST', '/members/{id}/delete', 'MemberController', 'delete');

$router->add('GET', '/events', 'EventController', 'index');
$router->add('GET', '/events/add', 'EventController', 'add');
$router->add('POST', '/events/store', 'EventController', 'store');
$router->add('GET', '/events/{id}/edit', 'EventController', 'edit');
$router->add('POST', '/events/{id}/update', 'EventController', 'update');
$router->add('POST', '/events/{id}/delete', 'EventController', 'delete');

$router->add('GET', '/offerings', 'OfferingController', 'index');
$router->add('GET', '/offerings/add', 'OfferingController', 'add');
$router->add('POST', '/offerings/store', 'OfferingController', 'store');
$router->add('POST', '/offerings/{id}/delete', 'OfferingController', 'delete');

$router->add('GET', '/sermons', 'SermonController', 'index');
$router->add('GET', '/sermons/add', 'SermonController', 'add');
$router->add('POST', '/sermons/store', 'SermonController', 'store');
$router->add('POST', '/sermons/{id}/delete', 'SermonController', 'delete');

$router->add('GET', '/profile', 'MemberController', 'profile');

// Dispatch the request
$router->dispatch();
