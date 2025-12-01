<?php

namespace Controllers;

use Core\Controller;
use Models\User;

class AuthController extends Controller
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        $this->view('login');
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required.';
            header('Location: /');
            exit;
        }

        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: /');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
