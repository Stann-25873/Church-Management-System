<?php

namespace Core;

class Controller
{
    protected function view($view, $data = [])
    {
        // Extract data to make variables available in the view
        extract($data);

        // Include the view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View not found: $view";
        }
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
